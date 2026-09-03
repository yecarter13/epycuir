<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Services\InstagramLocalExtractor;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstagramProductsImport extends Command
{
    protected $signature = 'instagram:products
                            {--file= : Path to parsed posts JSON (default: instagram export storage)}
                            {--dry-run : Generate metadata only, do not write products}
                            {--force : Re-process posts already generated}
                            {--limit= : Maximum number of posts to process}';

    protected $description = 'Create products from Instagram publications (name, category, price, emoji-free description)';

    public function handle(): int
    {
        $extractor = new InstagramLocalExtractor();

        $file = $this->option('file');
        if (!$file) {
            $file = base_path('instagram-selleries_surper_confort-2026-08-05-YYEOHgW0/storage/app/instagram/instagram_posts.json');
        }
        if (!file_exists($file)) {
            $this->error("Parsed posts file not found: $file");
            return Command::FAILURE;
        }

        $posts = json_decode(file_get_contents($file), true);
        if (empty($posts)) {
            $this->error('No posts in file');
            return Command::FAILURE;
        }

        $dryRun = $this->option('dry-run');
        $force = $this->option('force');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $outFile = dirname($file) . '/generated.json';
        $generated = [];
        if (file_exists($outFile)) {
            $generated = json_decode(file_get_contents($outFile), true);
        }

        $bar = $this->output->createProgressBar($limit ?? count($posts));
        $bar->start();

        $created = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($posts as $i => $post) {
            if ($limit !== null && $i >= $limit) break;

            $mediaId = $post['image'] ? pathinfo(basename($post['image']), PATHINFO_FILENAME) : $post['index'];
            $key = (string) $mediaId;

            if (!$force && isset($generated[$key])) {
                $skipped++;
                $bar->advance();
                continue;
            }

            if (empty(trim($post['caption'] ?? ''))) {
                $skipped++;
                $bar->advance();
                continue;
            }

            if ($extractor->shouldSkip($post['caption'])) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $meta = $extractor->extract($post['caption']);

            if ($meta === null || empty($meta['name'])) {
                $failed++;
                $this->newLine();
                $this->warn("  Failed for post $key");
                $bar->advance();
                continue;
            }

            $generated[$key] = [
                'post_index' => $post['index'],
                'media_id' => $mediaId,
                'image' => $post['image'],
                'gallery' => $post['gallery'],
                'caption' => $post['caption'],
                'meta' => $meta,
            ];
            file_put_contents($outFile, json_encode($generated, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

            if (!$dryRun) {
                $result = $this->createProduct($generated[$key], $outFile);
                $generated[$key]['product_id'] = $result;
                file_put_contents($outFile, json_encode($generated, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                if ($result !== null) {
                    $created++;
                } else {
                    $failed++;
                }
            } else {
                $created++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $mode = $dryRun ? 'generated' : 'created';
        $this->info("Done: $created $mode, $skipped skipped (already processed), $failed failed.");

        return Command::SUCCESS;
    }

    protected function createProduct(array $data, string $outFile): ?int
    {
        $meta = $data['meta'];
        $mediaId = $data['media_id'];

        $categoryId = $this->resolveCategory($meta['category']);

        $image = $data['image'] ? 'products/' . basename($data['image']) : null;
        $gallery = [];
        foreach ($data['gallery'] as $g) {
            $gallery[] = 'products/' . basename($g);
        }

        $description = $meta['description'];
        $name = $meta['name'];
        $price = $meta['price'] ?? 0;
        $oldPrice = $meta['old_price'] ?? null;
        $brand = $meta['brand'];
        $specs = $meta['specifications'];

        if (str_ends_with(strtolower($name), '.jpg') || empty($name)) {
            $name = 'Article Sellerie';
        }

        try {
            $product = Product::create([
                'category_id' => $categoryId,
                'name' => $name,
                'description' => $description,
                'specifications' => $specs,
                'price' => $price > 0 ? $price : 0.01,
                'old_price' => $oldPrice && $oldPrice > $price ? $oldPrice : null,
                'sku' => 'IG-' . $mediaId,
                'image' => $image,
                'gallery_images' => json_encode($gallery),
                'brand' => $brand,
                'is_active' => true,
                'stock_quantity' => 1,
                'meta_title' => $name,
                'meta_description' => Str::limit(strip_tags($description), 160),
            ]);
            return $product->id;
        } catch (\Throwable $e) {
            $this->newLine();
            $this->warn('  Create failed for ' . $mediaId . ': ' . $e->getMessage());
            return null;
        }
    }

    protected function resolveCategory(string $name): int
    {
        $existing = Category::all(['id', 'name', 'slug']);

        $clean = function (string $s): string {
            $s = mb_strtolower($s);
            $s = strtr($s, [
                'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
                'à' => 'a', 'â' => 'a', 'ä' => 'a',
                'ù' => 'u', 'û' => 'u', 'ü' => 'u',
                'ô' => 'o', 'ö' => 'o', 'î' => 'i', 'ï' => 'i', 'ç' => 'c',
            ]);
            return trim(preg_replace('/[^a-z0-9 ]/', ' ', $s));
        };

        $needle = $clean($name);

        foreach ($existing as $cat) {
            if ($clean($cat->name) === $needle) {
                return $cat->id;
            }
        }

        // slug / substring matching
        $best = null;
        $bestScore = 0;
        foreach ($existing as $cat) {
            $catClean = $clean($cat->name);
            $score = 0;
            if (str_contains($needle, $catClean) || str_contains($catClean, $needle)) {
                $score = 6;
            } else {
                similar_text($needle, $catClean, $pct);
                if ($pct > 55) $score = 4;
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $cat;
            }
        }

        if ($best) {
            return $best->id;
        }

        $category = Category::create([
            'name' => $name,
            'is_active' => true,
        ]);
        $this->newLine();
        $this->info('  Created category: ' . $category->name);

        return $category->id;
    }
}
