<?php

namespace App\Http\Controllers;

use App\Mail\AdminMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $faqs = [
            (object) ['question' => 'Quels moyens de paiement acceptez-vous ?', 'answer' => 'Nous acceptons toutes les principales cartes bancaires (Visa, Mastercard, American Express), PayPal, Apple Pay et Google Pay. Tous les paiements sont sécurisés via Stripe.'],
            (object) ['question' => 'Comment s\'effectue la livraison ?', 'answer' => 'Nous expédions partout en France métropolitaine sous 24 à 48h ouvrées. La livraison est offerte dès 80 € d\'achat. Les colis sont remis avec suivi et contre-signature.'],
            (object) ['question' => 'Puis-je retourner un article qui ne convient pas ?', 'answer' => 'Oui. Vous disposez de 30 jours pour retourner un article neuf, dans son emballage d\'origine, pour un remboursement ou un échange. Contactez simplement notre équipe pour organiser le retour.'],
            (object) ['question' => 'Proposez-vous une garantie sur vos produits ?', 'answer' => 'Absolument. Chaque article vendu bénéficie d\'une garantie minimale de 12 mois. Les selles et le harnachement haut de gamme peuvent bénéficier d\'une garantie fabricant prolongée.'],
            (object) ['question' => 'Comment choisir la bonne taille de selle pour mon cheval ?', 'answer' => 'Nos selliers sont à votre disposition pour vous conseiller. Vous pouvez nous envoyer les mesures de votre cheval par email ou nous appeler directement. Un ajustement sur rendez-vous est possible à la boutique.'],
        ];

        return view('pages.contact', compact('faqs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            $body = "<p><strong>Nouveau message depuis le site Sellerie Super Confort</strong></p>"
                . "<p><strong>Nom :</strong> " . e($validated['name']) . "<br>"
                . "<strong>Email :</strong> " . e($validated['email']) . "<br>"
                . "<strong>Téléphone :</strong> " . e($validated['phone'] ?? 'Non renseigné') . "<br>"
                . "<strong>Sujet :</strong> " . e($validated['subject']) . "</p>"
                . "<p><strong>Message :</strong><br>" . nl2br(e($validated['message'])) . "</p>";

            Mail::to(\App\Models\SiteSetting::getValue('email', 'info@scelle.com'))
                ->send(new AdminMail('Message contact — ' . $validated['subject'], $body));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Contact mail failed', ['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Merci pour votre message. Notre équipe vous répondra dans les plus brefs délais.');
    }
}
