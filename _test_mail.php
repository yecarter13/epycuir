<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

Mail::raw('Test email depuis AutoParts UK - ceci est un test de validation', function ($msg) {
    $msg->to('pouabegille@gmail.com')->subject('Test Email - AutoParts UK');
});

echo "Email envoyé avec succès !\n";
