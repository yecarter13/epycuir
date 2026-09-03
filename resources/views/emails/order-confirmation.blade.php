<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="center" style="padding:40px 20px;">

<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;">

<!-- Header -->
<tr>
<td style="background:linear-gradient(135deg,#334e68 0%,#243b53 100%);padding:40px 40px 30px;text-align:center;">
<h1 style="margin:0 0 6px;color:#ffffff;font-size:26px;font-weight:800;">Sellerie <span style="color:#fed7aa;">Epycuir</span></h1>
<p style="margin:0;color:rgba(255,255,255,0.85);font-size:15px;font-weight:400;">Confirmation de commande</p>
</td>
</tr>

<!-- Greeting -->
<tr>
<td style="padding:36px 40px 0;">
<p style="margin:0 0 6px;font-size:18px;color:#0f172a;font-weight:600;">Bonjour {{ $order->customer_name }},</p>
<p style="margin:0 0 24px;font-size:15px;color:#334155;line-height:1.7;">Merci pour votre commande. Elle a bien été enregistrée et nous la traitons actuellement. Vous recevrez une confirmation d'expédition dès que vos articles seront envoyés.</p>
</td>
</tr>

<!-- Commander Reference -->
<tr>
<td style="padding:0 40px;">
<p style="margin:0 0 4px;font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:1px;">Référence de commande</p>
<p style="margin:0 0 24px;font-size:22px;font-weight:700;color:#334e68;">#{{ $order->order_number }}</p>
</td>
</tr>

<tr>
<td style="padding:0 40px;"><hr style="border:none;border-top:1px solid #e2e8f0;margin:0;"></td>
</tr>

<!-- Items -->
<tr>
<td style="padding:28px 40px 0;">
<h2 style="margin:0 0 16px;font-size:16px;color:#0f172a;font-weight:600;">Articles commandés</h2>
<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
<tr style="border-bottom:1px solid #e2e8f0;">
<th align="left" style="padding:8px 0;font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Produit</th>
<th align="center" style="padding:8px 12px;font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Qté</th>
<th align="right" style="padding:8px 0;font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Prix</th>
<th align="right" style="padding:8px 0;font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Total</th>
</tr>
@foreach($order->items as $item)
<tr style="border-bottom:1px solid #f1f5f9;">
<td style="padding:14px 0;font-size:14px;color:#0f172a;">{{ $item->product_name }}</td>
<td align="center" style="padding:14px 12px;font-size:14px;color:#475569;">{{ $item->quantity }}</td>
<td align="right" style="padding:14px 0;font-size:14px;color:#475569;">&euro;{{ number_format($item->price,2) }}</td>
<td align="right" style="padding:14px 0;font-size:14px;color:#0f172a;font-weight:600;">&euro;{{ number_format($item->total,2) }}</td>
</tr>
@endforeach
</table>
</td>
</tr>

<!-- Totals -->
<tr>
<td style="padding:20px 40px 0;">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="padding:4px 0;font-size:15px;color:#475569;">Sous-total</td>
<td align="right" style="padding:4px 0;font-size:15px;color:#475569;">&euro;{{ number_format($order->subtotal,2) }}</td>
</tr>
<tr>
<td style="padding:4px 0;font-size:15px;color:#475569;">Livraison</td>
<td align="right" style="padding:4px 0;font-size:15px;color:#475569;">
@if($order->shipping > 0)
&euro;{{ number_format($order->shipping,2) }}
@else
<span style="color:#22c55e;">Offerte</span>
@endif
</td>
</tr>
<tr>
<td style="padding:14px 0 4px;border-top:2px solid #334e68;font-size:17px;font-weight:700;color:#0f172a;">Total de la commande</td>
<td align="right" style="padding:14px 0 4px;border-top:2px solid #334e68;font-size:20px;font-weight:700;color:#334e68;">&euro;{{ number_format($order->total,2) }}</td>
</tr>
</table>
</td>
</tr>

<tr>
<td style="padding:0 40px;"><hr style="border:none;border-top:1px solid #e2e8f0;margin:24px 0 0;"></td>
</tr>

<!-- Livraison Address -->
<tr>
<td style="padding:24px 40px 0;">
<h2 style="margin:0 0 10px;font-size:16px;color:#0f172a;font-weight:600;">Adresse de livraison</h2>
<p style="margin:0;font-size:15px;color:#334155;line-height:1.7;">
{{ $order->shipping_address }}<br>
{{ $order->shipping_city }} {{ $order->shipping_postcode }}
</p>
@if($order->customer_phone)
<p style="margin:6px 0 0;font-size:14px;color:#475569;">Tél : {{ $order->customer_phone }}</p>
@endif
</td>
</tr>

<tr>
<td style="padding:0 40px;"><hr style="border:none;border-top:1px solid #e2e8f0;margin:24px 0 0;"></td>
</tr>

<!-- Support -->
<tr>
<td style="padding:24px 40px 36px;">
<p style="margin:0 0 4px;font-size:14px;color:#475569;">Si vous avez des questions, contactez-nous :</p>
<p style="margin:0;font-size:14px;"><a href="mailto:info@scelle.com" style="color:#334e68;text-decoration:none;">info@scelle.com</a> &mdash; +33 7 56 96 57 89</p>
</td>
</tr>

<!-- Footer -->
<tr>
<td style="padding:24px 40px;background:#f8fafc;text-align:center;border-top:1px solid #e2e8f0;">
<p style="margin:0 0 4px;font-size:12px;color:#94a3b8;">&copy; {{ date('Y') }} Sellerie Epycuir. Tous droits réservés.</p>
<p style="margin:0;font-size:11px;color:#94a3b8;">www.scelle.com</p>
</td>
</tr>

</table>

<p style="margin:18px 0 0;font-size:11px;color:#94a3b8;text-align:center;">Cet e-mail a été envoyé pour confirmer votre commande sur Sellerie Epycuir.</p>

</td>
</tr>
</table>

</body>
</html>
