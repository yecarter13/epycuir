<?php
$p = new PDO("mysql:host=localhost;dbname=u104009834_autoparts", "u104009834_treize", "Gille2004$");
$s = $p->query("SELECT id, sku, name, image FROM products WHERE sku LIKE 'VW%' ORDER BY id DESC LIMIT 10");
while ($r = $s->fetch(PDO::FETCH_ASSOC)) {
    echo $r["id"] . " | " . $r["sku"] . " | " . substr($r["name"], 0, 40) . " | " . $r["image"] . "\n";
}
