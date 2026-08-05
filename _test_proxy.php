<?php
$urls = [
    'https://www.yakaequiper.com/images/lame-golf-7-gti-40100-carbone.jpg',
    'https://www.yakaequiper.com/images/40128-becquet-golf-7-rline-noir.jpg',
    'https://www.yakaequiper.com/images/bas-caisse-look-gti-vw-golf-7-PGVW06.jpg',
    'https://media.cdn.kaufland.de/product-images/1024x1024/8579a28c59026bf2da8aa7c3129da794.webp',
];
foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => [
            'Referer: https://autopartsway.com/',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ],
    ]);
    $r = curl_exec($ch);
    $h = curl_getinfo($ch);
    $headerSize = $h['header_size'];
    $body = substr($r, $headerSize);
    echo basename($url) . ": HTTP {$h['http_code']}, Size: {$h['size_download']}B, BodyLen: " . strlen($body) . "\n";
    if ($h['http_code'] === 200) {
        file_put_contents('/tmp/' . basename($url), $body);
        echo "  -> Saved to /tmp/" . basename($url) . "\n";
    }
    curl_close($ch);
}
