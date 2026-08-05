@extends('layouts.master')

@section('title', 'Confidentialité — Sellerie Super Confort')

@section('content')

<section class="bg-stable-900 py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-3 py-1 bg-safety/90 text-white text-xs font-semibold uppercase tracking-widest rounded-full mb-4">Legal</span>
        <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4">Confidentialité</h1>
        <p class="text-lg text-stable-300 max-w-2xl mx-auto">Comment nous collectons, utilisons et protégeons vos données personnelles</p>
    </div>
</section>

<section class="py-12 lg:py-16 bg-stable-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-stable-100 p-8 lg:p-10 space-y-8">
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Qui sommes-nous ?</h2>
                <p class="text-stable-600 leading-relaxed">Sellerie Super Confort est une boutique d'équipement équestre située au 25 rue de Cogiandant Crénière, 10270 Courteranges, en France. Cette politique de confidentialité explique comment nous collectons, utilisons et protégeons vos données personnelles lorsque vous utilisez notre site web et nos services.</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Informations que nous collectons</h2>
                <ul class="space-y-2 text-stable-600">
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg> Nom, adresse e-mail, numéro de téléphone et adresse de livraison</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg> Informations de paiement (traitées de manière sécurisée par Stripe — nous ne stockons pas les données de carte bancaire)</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg> Comportement de navigation et historique des commandes</li>
                </ul>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Comment nous utilisons vos données</h2>
                <ul class="space-y-2 text-stable-600">
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg> Pour traiter et livrer vos commandes</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg> Pour assurer le service client et répondre à vos demandes</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg> Pour vous envoyer le suivi de vos commandes et les notifications de livraison</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg> Pour vous adresser, si vous y avez consenti, notre lettre d'information (newsletter)</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg> Pour améliorer notre site web et notre offre de produits</li>
                </ul>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Conservation et partage des données</h2>
                <p class="text-stable-600 leading-relaxed">Vos données personnelles sont conservées uniquement pendant la durée nécessaire aux finalités décrites ci-dessus et conformément aux obligations légales applicables. Les données de commande sont notamment conservées à des fins comptables et de garantie. Vos informations de paiement sont partagées avec notre prestataire de paiement Stripe, qui les traite de manière sécurisée ; nous ne stockons jamais les données de carte bancaire.</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Protection des données</h2>
                <p class="text-stable-600 leading-relaxed">Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour protéger vos données personnelles contre tout accès, modification, divulgation ou destruction non autorisés. Toutes les transactions de paiement sont chiffrées à l'aide de la technologie SSL 256 bits et sont traitées par Stripe, un prestataire de paiement certifié PCI DSS de niveau 1.</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Cookies</h2>
                <p class="text-stable-600 leading-relaxed">Notre site web utilise des cookies, notamment à des fins de fonctionnement, de mesure d'audience et de personnalisation. Vous pouvez configurer votre navigateur pour refuser les cookies ou être alerté de leur utilisation. Le refus de certains cookies peut toutefois limiter le bon fonctionnement du site.</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Vos droits</h2>
                <p class="text-stable-600 leading-relaxed">Conformément au Règlement général sur la protection des données (RGPD), vous disposez d'un droit d'accès, de rectification, d'effacement, de limitation et d'opposition concernant vos données personnelles, ainsi que d'un droit à la portabilité. Vous pouvez également introduire une réclamation auprès de la CNIL (www.cnil.fr). Pour exercer ces droits, contactez-nous à l'adresse info@scelle.com.</p>
            </div>
        </div>
    </div>
</section>

@endsection
