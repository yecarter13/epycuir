@extends('layouts.master')

@section('title', 'Retours & remboursements — Sellerie Super Confort')

@section('content')

<section class="bg-stable-900 py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-3 py-1 bg-safety/90 text-white text-xs font-semibold uppercase tracking-widest rounded-full mb-4">Service client</span>
        <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4">Retours & remboursements</h1>
        <p class="text-lg text-stable-300 max-w-2xl mx-auto">Retours simples sous 14 jours après la réception de votre commande</p>
    </div>
</section>

<section class="py-12 lg:py-16 bg-stable-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-stable-100 p-8 lg:p-10 space-y-8">
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Délai de rétractation de 14 jours</h2>
                <p class="text-stable-600 leading-relaxed">Conformément à la loi française pour la confiance dans l'économie numérique, vous disposez d'un délai de rétractation de 14 jours à compter de la réception de votre commande pour retourner vos articles et obtenir un remboursement complet ou un échange.</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Conditions de retour</h2>
                <ul class="space-y-2 text-stable-600">
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Les articles doivent être inutilisés, non endommagés et dans leur emballage d'origine</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Une preuve d'achat est requise (numéro de commande ou reçu)</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Les frais de retour sont à la charge du client, sauf si l'article est défectueux</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Le droit de rétractation ne s'applique pas aux articles personnalisés ou sur mesure</li>
                </ul>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Comment retourner un article</h2>
                <ol class="space-y-3 text-stable-600 list-decimal list-inside">
                    <li>Contactez notre équipe par e-mail (info@scelle.com) ou par téléphone (+33 7 56 96 57 89) pour obtenir un numéro d'autorisation de retour (NAR)</li>
                    <li>Emballez l'article avec soin dans son emballage d'origine</li>
                    <li>Expédiez l'article à notre adresse : 25 rue de Cogiandant Crénière, 10270 Courteranges, en indiquant clairement le numéro d'autorisation de retour</li>
                    <li>Une fois l'article reçu et contrôlé, nous procédons au remboursement sous 14 jours</li>
                </ol>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Articles défectueux ou non conformes</h2>
                <p class="text-stable-600 leading-relaxed">Si vous recevez un article défectueux ou non conforme, contactez-nous immédiatement. Nous organisons l'échange ou le remboursement sans frais supplémentaires, et les frais de retour sont à notre charge.</p>
            </div>
        </div>
    </div>
</section>

@endsection
