<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductsImport extends Command
{
    protected $signature = 'products:import
                            {file : Path to JSON or CSV file}
                            {--disk=local : Storage disk for the file}
                            {--download-images : Download product images}
                            {--category= : Default category ID or name}
                            {--update-existing : Update products that already exist (matched by SKU)}
                            {--batch=50 : Number of products per batch}
                            {--price-multiplier=0.85 : Multiply all prices by this factor (e.g. 0.85 for 15% off)}
                            {--min-price=0 : Skip products with price below this value (after multiplier)}
                            {--auto-description : Auto-generate descriptions when missing}';

    protected $description = 'Import products from a JSON or CSV file';

    public function handle(): int
    {
        $filePath = $this->argument('file');
        $disk = $this->option('disk');
        $downloadImages = $this->option('download-images');
        $defaultCategory = $this->option('category');
        $updateExisting = $this->option('update-existing');
        $batchSize = (int) $this->option('batch');
        $priceMultiplier = (float) $this->option('price-multiplier');
        $autoDescription = $this->option('auto-description');
        $minPrice = (float) $this->option('min-price');

        if (!Storage::disk($disk)->exists($filePath) && !file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return Command::FAILURE;
        }

        $content = file_exists($filePath)
            ? file_get_contents($filePath)
            : Storage::disk($disk)->get($filePath);

        $rawProducts = $this->parseFile($content, $filePath);
        if (empty($rawProducts)) {
            $this->error('No products found in file');
            return Command::FAILURE;
        }

        $categoryId = $this->resolveCategory($defaultCategory);

        $this->info("Found " . count($rawProducts) . " products to import");
        if ($priceMultiplier !== 1.0) {
            $this->info("Price multiplier: $priceMultiplier");
        }

        $bar = $this->output->createProgressBar(count($rawProducts));
        $bar->start();

        $imported = 0;
        $updated = 0;
        $skipped = 0;

        foreach (array_chunk($rawProducts, $batchSize) as $batch) {
            foreach ($batch as $data) {
                $result = $this->importProduct($data, $categoryId, $downloadImages, $updateExisting, $priceMultiplier, $autoDescription, $minPrice);
                match ($result) {
                    'imported' => $imported++,
                    'updated' => $updated++,
                    default => $skipped++,
                };
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("Import complete: $imported imported, $updated updated, $skipped skipped");

        return Command::SUCCESS;
    }

    private function parseFile(string $content, string $filePath): array
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($ext === 'csv') {
            return $this->parseCsv($content);
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Invalid JSON: " . json_last_error_msg());
            return [];
        }

        return isset($data[0]) ? $data : [$data];
    }

    private function parseCsv(string $content): array
    {
        $lines = explode("\n", trim($content));
        if (empty($lines)) return [];

        $headers = str_getcsv(array_shift($lines));
        $products = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $values = str_getcsv($line);
            $row = [];
            foreach ($headers as $i => $header) {
                $row[$header] = $values[$i] ?? null;
            }
            $products[] = $row;
        }

        return $products;
    }

    private function resolveCategory(?string $input): ?int
    {
        if (empty($input)) return null;

        if (is_numeric($input)) {
            return (int) $input;
        }

        $category = Category::where('name', $input)->orWhere('slug', Str::slug($input))->first();
        if ($category) {
            $this->info("Using category: {$category->name} (ID: {$category->id})");
            return $category->id;
        }

        $category = Category::create([
            'name' => $input,
            'is_active' => true,
        ]);
        $this->info("Created category: {$category->name} (ID: {$category->id})");

        return $category->id;
    }

    private array $categoryCache = [];

    private function resolveCategoryFromName(?string $name, ?int $defaultId): ?int
    {
        if (empty($name)) return $defaultId;

        if (isset($this->categoryCache[$name])) {
            return $this->categoryCache[$name];
        }

        $category = Category::where('name', $name)->first();
        if ($category) {
            $this->categoryCache[$name] = $category->id;
            return $category->id;
        }

        $slug = Str::slug($name);
        $baseSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $category = Category::create([
            'name' => $name,
            'slug' => $slug,
            'is_active' => true,
        ]);
        $this->categoryCache[$name] = $category->id;
        $this->line("  Created category: {$category->name} ({$slug})");
        return $category->id;
    }

    private function importProduct(array $data, ?int $defaultCategoryId, bool $downloadImages, bool $updateExisting, float $priceMultiplier, bool $autoDescription, float $minPrice = 0): string
    {
        $sku = $data['sku'] ?? $data['part_number'] ?? $data['partno'] ?? null;
        $name = $data['name'] ?? $data['title'] ?? $data['part_name'] ?? null;

        if (empty($name)) {
            return 'skipped';
        }

        $existing = $sku ? Product::where('sku', $sku)->first() : null;

        if ($existing && !$updateExisting) {
            return 'skipped';
        }

        $price = $this->parsePrice($data['price'] ?? $data['cost'] ?? 0) * $priceMultiplier;
        $oldPrice = $this->parsePrice($data['old_price'] ?? $data['list_price'] ?? null) * $priceMultiplier;

        if ($minPrice > 0 && $price < $minPrice) {
            return 'skipped';
        }

        $brand = $data['brand'] ?? $data['make'] ?? $data['manufacturer'] ?? null;
        $partManufacturer = $data['manufacturer'] ?? null;

        $description = $data['description'] ?? $data['desc'] ?? null;
        $compatibility = $data['compatibility'] ?? $data['fitment'] ?? $data['vehicle'] ?? null;

        if ($autoDescription && empty($description)) {
            $description = $this->generateDescription($name, $brand, $compatibility, $partManufacturer);
        }

        $payload = [
            'name' => $name,
            'description' => $description,
            'specifications' => $data['specifications'] ?? $data['specs'] ?? null,
            'price' => round($price, 2),
            'old_price' => $oldPrice ? round($oldPrice, 2) : null,
            'sku' => $sku ?? 'AP-' . strtoupper(Str::random(6)),
            'brand' => $brand,
            'compatibility' => $compatibility,
            'stock_quantity' => (int) ($data['stock_quantity'] ?? $data['stock'] ?? 10),
            'is_active' => filter_var($data['is_active'] ?? $data['active'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'is_new' => filter_var($data['is_new'] ?? $data['new'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'meta_title' => $data['meta_title'] ?? $name,
            'meta_description' => $data['meta_description'] ?? Str::limit(strip_tags($description ?? $name), 160),
            'category_id' => $data['category_id'] ?? $this->resolveCategoryFromName($data['category'] ?? null, $defaultCategoryId),
        ];

        if ($imageUrl = $data['image'] ?? $data['image_url'] ?? $data['img'] ?? null) {
            if ($downloadImages && str_starts_with($imageUrl, 'http')) {
                $payload['image'] = $this->downloadImage($imageUrl);
            } else {
                $payload['image'] = $imageUrl;
            }
        }

        $galleryRaw = $data['gallery_images'] ?? $data['images'] ?? null;
        if ($galleryRaw) {
            $payload['gallery_images'] = is_array($galleryRaw)
                ? json_encode($galleryRaw)
                : (is_string($galleryRaw) && str_starts_with($galleryRaw, '[') ? $galleryRaw : null);
        }

        if ($existing) {
            $existing->update($payload);
            return 'updated';
        }

        Product::create($payload);
        return 'imported';
    }

    private function parsePrice(mixed $value): float
    {
        if (empty($value)) return 0;
        $value = preg_replace('/[^0-9.]/', '', (string) $value);
        return (float) $value;
    }

    private function generateDescription(string $name, ?string $brand, ?string $compatibility, ?string $partManufacturer = null): string
    {
        $parts = [];

        if ($brand) {
            $parts[] = "$brand";
        }

        $parts[] = $name;

        if ($partManufacturer) {
            $parts[] = "Manufactured by $partManufacturer.";
        }

        if ($compatibility) {
            $parts[] = "Compatible with $compatibility.";
        }

        $parts[] = "High quality replacement part. Direct fit - no modifications required.";

        return implode(' ', $parts);
    }

    private function downloadImage(string $url): ?string
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Referer' => 'https://autopartsway.com/',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ])->timeout(10)->get($url);
            if ($response->successful()) {
                $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $filename = 'products/' . Str::random(20) . '.' . $ext;
                Storage::disk('public')->put($filename, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {
            $this->warn("Failed to download image: $url");
        }
        return $url;
    }
}
