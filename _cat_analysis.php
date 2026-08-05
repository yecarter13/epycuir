<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\Product;

$nullCat = Product::where('sku', 'like', 'WC-%')->whereNull('category_id')->get();
echo "Products with null category: " . $nullCat->count() . "\n\n";

$f = fopen('storage/app/wc-product-export-21-7-2026-1784659228008.csv', 'r');
$rawHeaders = fgetcsv($f);
$idIdx = 0;
$catIdx = 27;
$catMap = [];
while ($row = fgetcsv($f)) {
    $sku = 'WC-' . $row[$idIdx];
    $catName = trim($row[$catIdx] ?? '');
    $catMap[$sku] = $catName;
}
fclose($f);

$counts = [];
foreach ($nullCat as $p) {
    $origCat = $catMap[$p->sku] ?? 'Unknown';
    $counts[$origCat] = ($counts[$origCat] ?? 0) + 1;
}

echo "Original categories of null-category products:\n";
ksort($counts);
foreach ($counts as $cat => $cnt) echo "  '$cat': $cnt\n";
