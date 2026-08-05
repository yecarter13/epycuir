<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BrandsSync extends Command
{
    protected $signature = 'brands:sync
                            {--from-products : Scan products table for unique brands}
                            {--name= : Add a single brand by name}
                            {--logo= : Logo filename for --name mode}';

    protected $description = 'Sync part manufacturer brands into config/brands.php';

    public function handle(): int
    {
        $configPath = config_path('brands.php');
        $brands = require $configPath;

        $added = 0;

        if ($this->option('name')) {
            $name = trim($this->option('name'));
            $logo = $this->option('logo') ?? Str::slug($name) . '.png';
            if (!isset($brands[$name])) {
                $brands[$name] = $logo;
                $added++;
                $this->info("Added brand: $name");
            } else {
                $this->warn("Brand already exists: $name");
            }
        }

        if ($this->option('from-products')) {
            $productBrands = Product::whereNotNull('brand')
                ->where('brand', '!=', '')
                ->distinct()
                ->pluck('brand');

            foreach ($productBrands as $name) {
                if (!isset($brands[$name])) {
                    $slug = Str::slug(str_replace('&', 'and', $name));
                    $brands[$name] = $slug . '.png';
                    $added++;
                    $this->line("  Added: $name");
                }
            }
        }

        if ($added === 0) {
            $this->info('No new brands to add.');
            return Command::SUCCESS;
        }

        ksort($brands);

        $content = "<?php\n\nreturn [\n";
        foreach ($brands as $name => $logo) {
            $escaped = str_replace("'", "\\'", $name);
            $content .= "    '$escaped' => '$logo',\n";
        }
        $content .= "];\n";

        file_put_contents($configPath, $content);
        $this->info("Added $added brand(s) to config/brands.php");

        return Command::SUCCESS;
    }
}
