<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Console\Command;
use Stichoza\GoogleTranslate\GoogleTranslate;

class WcTranslate extends Command
{
    protected $signature = 'products:translate-wc
                            {file : Path to original WooCommerce CSV}
                            {--dry-run : Show translations without saving}';

    protected $description = 'Re-translate WooCommerce product names & descriptions using Google Translate and fix categories';

    protected array $categoryMap = [
        'phares' => 'Electrical, Lighting and Body',
        'eclairage' => 'Electrical, Lighting and Body',
        'moteurs' => 'Engine',
        'moteur' => 'Engine',
        'jantes' => 'Tire and Wheel',
        'roues' => 'Tire and Wheel',
        'sieges' => 'Interior',
        'siege' => 'Interior',
        'sièges' => 'Interior',
        'intérieur' => 'Interior',
        'interieur' => 'Interior',
        'habitacle' => 'Interior',
        'volants' => 'Interior',
        'volant' => 'Interior',
        'echappement' => 'Exhaust',
        'freinage' => 'Brake',
        'suspension' => 'Suspension',
        'direction' => 'Steering',
        'transmission' => 'Transmission',
        'boite' => 'Transmission',
        'carrosserie' => 'Body',
        'exterieur' => 'Body',
        'pare-chocs' => 'Body',
        'caisse' => 'Body',
        'electricite' => 'Electrical, Charging and Starting',
        'electronique' => 'Electrical, Charging and Starting',
        'accessoires' => 'Accessories and Fluids',
        'divers' => 'Accessories and Fluids',
    ];

    public function handle(): int
    {
        $filePath = $this->argument('file');
        $dryRun = $this->option('dry-run');

        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return Command::FAILURE;
        }

        $products = $this->parseCSV($filePath);
        $this->info('Found ' . count($products) . ' products in CSV');

        $updated = 0;
        $skipped = 0;
        $errors = 0;
        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        $tr = new GoogleTranslate('en', 'fr', ['verify' => false]);

        foreach ($products as $row) {
            $id = $row['ID'] ?? '';
            $sku = 'WC-' . $id;
            $frName = trim($row['Nom'] ?? '');
            $frDesc = trim($row['Description'] ?? '');
            $frCats = trim($row['Categories'] ?? '');

            if (empty($frName)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $existing = Product::where('sku', $sku)->first();
            if (!$existing) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $enName = null;
            $enDesc = null;

            try {
                $enName = $tr->translate($frName);
                usleep(200000);
            } catch (\Exception $e) {
                $this->warn("\nTranslate failed for name '$frName': " . $e->getMessage());
                $errors++;
            }

            if (!empty($frDesc)) {
                $shortDesc = mb_substr($frDesc, 0, 3000);
                try {
                    $enDesc = $tr->translate($shortDesc);
                    usleep(200000);
                } catch (\Exception $e) {
                    $this->warn("\nTranslate failed for description of $sku: " . $e->getMessage());
                }
            }

            $catId = $this->mapCategory($frCats, $frName);

            $data = [];
            if ($enName) $data['name'] = mb_substr($enName, 0, 250);
            if ($enDesc) $data['description'] = $enDesc;
            if ($catId) $data['category_id'] = $catId;

            if (empty($data)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            if ($dryRun) {
                $bar->advance();
                continue;
            }

            try {
                $existing->update($data);
                $updated++;
            } catch (\Exception $e) {
                $this->warn("\nFailed to update $sku: " . $e->getMessage());
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Done: $updated updated, $skipped skipped, $errors errors");

        return Command::SUCCESS;
    }

    protected function parseCSV(string $filePath): array
    {
        $f = fopen($filePath, 'r');
        $rawHeaders = fgetcsv($f);
        $headerMap = [
            'ID' => 'ID', 'Nom' => 'Nom',
            'Publi' => 'Publie', 'Tarifrgulier' => 'Tarifregulier',
            'Description' => 'Description',
            'Catgories' => 'Categories', 'Images' => 'Images',
        ];
        $normalized = [];
        foreach ($rawHeaders as $h) {
            $ascii = preg_replace('/[^\x20-\x7E]/u', '', $h);
            $key = str_replace([' ', '?'], '', trim($ascii));
            $normalized[] = $headerMap[$key] ?? $key;
        }
        $rows = [];
        while ($line = fgetcsv($f)) {
            if (count($line) === count($normalized)) {
                $rows[] = array_combine($normalized, $line);
            }
        }
        fclose($f);
        return $rows;
    }

    protected function mapCategory(string $categories, string $name): ?int
    {
        $parts = array_map('trim', explode(',', $categories));
        foreach ($parts as $part) {
            $lower = mb_strtolower(trim($part));
            if ($lower === 'uncategorized' || empty($lower)) continue;
            if (isset($this->categoryMap[$lower])) {
                $cat = Category::where('name', $this->categoryMap[$lower])->first();
                if ($cat) return $cat->id;
            }
            foreach ($this->categoryMap as $fr => $en) {
                if (str_contains($lower, $fr)) {
                    $cat = Category::where('name', $en)->first();
                    if ($cat) return $cat->id;
                }
            }
        }

        $lowerName = mb_strtolower($name);
        $keywords = [
            'Engine' => ['moteur', 'engine', 'moteurs', 'v8', 'v6', '4 cylindres', '6 cylindres'],
            'Brake' => ['frein', 'brake', 'disque', 'plaquette'],
            'Interior' => ['siege', 'siège', 'seat', 'volant', 'intérieur', 'interior', 'habitacle'],
            'Exhaust' => ['echappement', 'exhaust', 'silencieux'],
            'Suspension' => ['suspension', 'amortisseur', 'ressort'],
            'Steering' => ['direction', 'steering', 'crémaillère'],
            'Body' => ['carrosserie', 'body', 'aile', 'capot', 'porte', 'pare-chocs', 'bumper'],
            'Tire and Wheel' => ['jante', 'rim', 'roue', 'wheel', 'pneu', 'tire'],
            'Electrical, Lighting and Body' => ['phare', 'headlight', 'feu', 'light', 'eclairage', 'clignotant'],
            'Transmission' => ['transmission', 'boite', 'vitesse', 'embrayage', 'clutch'],
            'Accessories and Fluids' => ['accessoire', 'accessory', 'divers'],
        ];

        foreach ($keywords as $catName => $terms) {
            foreach ($terms as $term) {
                if (str_contains($lowerName, $term)) {
                    $cat = Category::where('name', $catName)->first();
                    if ($cat) return $cat->id;
                }
            }
        }

        return null;
    }
}
