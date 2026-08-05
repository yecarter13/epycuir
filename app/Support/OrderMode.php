<?php

namespace App\Support;

use App\Models\SiteSetting;

class OrderMode
{
    public static function isWhatsapp(): bool
    {
        return SiteSetting::getValue('order_mode', 'checkout') === 'whatsapp';
    }

    public static function isCheckout(): bool
    {
        return ! self::isWhatsapp();
    }

    public static function whatsappNumber(): ?string
    {
        $number = SiteSetting::getValue('whatsapp_number');
        if (! $number) return null;
        $digits = preg_replace('/\D+/', '', $number);
        return $digits !== '' ? $digits : null;
    }

    public static function waLink(string $message): ?string
    {
        $number = self::whatsappNumber();
        if (! $number) return null;
        return 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
    }

    public static function productMessage($product, int $qty = 1): string
    {
        $ref = $product->sku ?? $product->slug;
        return "Bonjour Sellerie Super Confort, je souhaite commander cet article :\n\n"
            . "- Produit : {$product->name}\n"
            . "- Référence : {$ref}\n"
            . "- Quantité : {$qty}\n"
            . "- Prix : €" . number_format((float) $product->price * $qty, 2) . "\n\n"
            . "Pouvez-vous me confirmer la disponibilité et l'expédition ?";
    }

    public static function cartMessage($cartItems, float $total): string
    {
        $text = "Bonjour Sellerie Super Confort, je souhaite commander les articles suivants :\n\n";
        foreach ($cartItems as $item) {
            $text .= "- {$item['name']} x{$item['quantity']} - €" . number_format((float) $item['price'] * (int) $item['quantity'], 2) . "\n";
        }
        $text .= "\nTotal : €" . number_format($total, 2) . "\n\n"
            . "Pouvez-vous me confirmer la disponibilité et l'expédition ?";
        return $text;
    }
}
