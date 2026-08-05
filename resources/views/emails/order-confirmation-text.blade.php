Sellerie Super Confort - Confirmation de commande

Bonjour {{ $order->customer_name }},

Merci pour votre commande. Elle a bien été enregistrée et nous la traitons actuellement.

Référence de commande : #{{ $order->order_number }}

Articles commandés :
@foreach($order->items as $item)
- {{ $item->product_name }} x{{ $item->quantity }} - €{{ number_format($item->price,2) }} (Total : €{{ number_format($item->total,2) }})
@endforeach

Sous-total : €{{ number_format($order->subtotal,2) }}
Livraison : 
@if($order->shipping > 0)
€{{ number_format($order->shipping,2) }}
@else
Offerte
@endif
Total de la commande : €{{ number_format($order->total,2) }}

Adresse de livraison :
{{ $order->shipping_address }}
{{ $order->shipping_city }} {{ $order->shipping_postcode }}
@if($order->customer_phone)
Tél : {{ $order->customer_phone }}
@endif

Si vous avez des questions, contactez-nous :
info@scelle.com

(c) {{ date('Y') }} Sellerie Super Confort. Tous droits réservés.
www.scelle.com

Cet e-mail a été envoyé pour confirmer votre commande sur Sellerie Super Confort.