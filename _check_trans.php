<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\Product;

// Check sample products with weird names
$samples = Product::where('sku', 'like', 'WC-%')->where('name', 'like', '%Collecteur%')->orWhere('name', 'like', '%Papillon%')->orWhere('name', 'like', '%Admission%')->get();
foreach ($samples as $p) {
    echo "SKU: {$p->sku}\n";
    echo "Name: {$p->name}\n";
    echo "Brand: {$p->brand}\n";
    echo "CatID: {$p->category_id}\n";
    echo "\n";
}

// Check products with null category
$nullCat = Product::whereNull('category_id')->where('sku', 'like', 'WC-%')->count();
$nullBrand = Product::whereNull('brand')->orWhere('brand', '')->where('sku', 'like', 'WC-%')->count();
echo "WC products with null category: $nullCat\n";
echo "WC products with null/empty brand: $nullBrand\n";
