@extends('layouts.master')

@section('title', 'Garantie — Sellerie Epycuir')

@section('content')

<section class="bg-stable-900 py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-3 py-1 bg-safety/90 text-white text-xs font-semibold uppercase tracking-widest rounded-full mb-4">Service client</span>
        <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4">Garantie</h1>
        <p class="text-lg text-stable-300 max-w-2xl mx-auto">Tous nos produits bénéficient d'une garantie minimale de 12 mois</p>
    </div>
</section>

<section class="py-12 lg:py-16 bg-stable-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-stable-100 p-8 lg:p-10 space-y-8">
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Garantie 12 mois</h2>
                <p class="text-stable-600 leading-relaxed">Tous les produits vendus par Sellerie Epycuir sont couverts par une garantie d'une durée minimale de 12 mois à compter de la date d'achat. Cette garantie couvre les défauts de fabrication et la défaillance prématurée dans des conditions d'utilisation normales, et peut donner lieu à une réparation ou à un remplacement. En outre, vous bénéficiez de la garantie légale de conformité d'une durée de 2 ans, conformément au Code de la consommation.</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Ce qui est couvert</h2>
                <ul class="space-y-2 text-stable-600">
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Les défauts de fabrication liés aux matériaux ou à la confection</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> La défaillance prématurée dans des conditions d'utilisation normales</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-safety mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Les articles ne fonctionnant pas conformément à leur fonction d'origine</li>
                </ul>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Ce qui n'est pas couvert</h2>
                <ul class="space-y-2 text-stable-600">
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-cta mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> L'usure normale des articles (cuir, sangles, mousquetons, etc.)</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-cta mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Les dommages causés par une mauvaise installation ou une utilisation inappropriée</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-cta mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Les articles modifiés ou altérés après l'achat</li>
                    <li class="flex items-start gap-2"><svg class="w-4 h-4 text-cta mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Les frais de main-d'œuvre liés au montage ou au démontage des articles</li>
                </ul>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Comment faire une demande de garantie</h2>
                <ol class="space-y-3 text-stable-600 list-decimal list-inside">
                    <li>Contactez notre équipe à l'adresse info@scelle.com ou au +33 7 56 96 57 89, avec votre numéro de commande et une description du problème</li>
                    <li>Fournissez des photographies ou une vidéo à l'appui lorsque c'est possible</li>
                    <li>Notre équipe évalue la demande et vous répond sous 2 jours ouvrés</li>
                    <li>Si la demande est acceptée, nous organisons la réparation ou le remplacement de l'article, ou son remboursement</li>
                </ol>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Garanties étendues</h2>
                <p class="text-stable-600 leading-relaxed">Certaines marques proposent des garanties étendues au-delà de 12 mois. Consultez la description du produit ou contactez-nous pour obtenir des informations spécifiques sur la garantie de chaque produit.</p>
            </div>
        </div>
    </div>
</section>

@endsection
