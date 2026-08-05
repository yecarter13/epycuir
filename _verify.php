<?php
$json = json_decode(file_get_contents('scraper/subaru-full.json'), true);
$withGallery = 0;
$total = count($json);
foreach ($json as $i => $p) {
    if (!empty($p['gallery_images'])) $withGallery++;
    if ($i === 0) {
        echo 'First product:' . PHP_EOL;
        echo '  name: ' . $p['name'] . PHP_EOL;
        echo '  gallery count: ' . count($p['gallery_images'] ?? []) . PHP_EOL;
        echo '  image: ' . $p['image'] . PHP_EOL;
    }
}
echo "Total: $total, with gallery_images: $withGallery" . PHP_EOL;
