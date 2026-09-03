@extends('layouts.master')

@section('title', 'Contactez-nous — Sellerie Epycuir')

@section('content')

{{-- Hero --}}
<section class="bg-stable-900 py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-3 py-1 bg-safety/90 text-white text-xs font-semibold uppercase tracking-widest rounded-full mb-4">Contactez-nous</span>
        <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4">Nous sommes là pour vous aider</h1>
        <p class="text-lg text-stable-300 max-w-2xl mx-auto">Besoin d'aide pour choisir votre matériel équestre ? Notre équipe est à votre écoute.</p>
    </div>
</section>

<section class="py-12 lg:py-16 bg-stable-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-10">

            {{-- Coordonnées Sidebar --}}
            @php
                $address = App\Models\SiteSetting::getValue('address');
                $phone = App\Models\SiteSetting::getValue('phone');
                $email = App\Models\SiteSetting::getValue('email');
                $hours = App\Models\SiteSetting::getValue('opening_hours');
            @endphp
            <div class="lg:col-span-1 space-y-5 mb-8 lg:mb-0">
                @if($address)
                <div class="bg-white rounded-xl border border-stable-100 p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-safety/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-stable-900">Venez nous rendre visite</h3>
                            <p class="text-stable-500 text-sm mt-1 leading-relaxed">{{ $address }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($email)
                <div class="bg-white rounded-xl border border-safety/30 p-6 hover:shadow-lg transition-all duration-300 ring-1 ring-safety/20">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-safety rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-stable-900">Écrivez-nous — Contact privilégié</h3>
                            <a href="mailto:{{ $email }}" class="text-safety hover:text-safety-dark text-sm mt-1 block transition-colors font-medium">{{ $email }}</a>
                            <p class="text-stable-400 text-xs mt-1">Nous répondons sous 24 h pendant les heures d'ouverture</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($phone)
                <div class="bg-white rounded-xl border border-stable-100 p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-safety/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-stable-900">Appelez-nous</h3>
                            <p class="text-stable-500 text-sm mt-1">{{ $phone }}</p>
                            @if($hours)<p class="text-stable-400 text-xs mt-1">{{ $hours }}</p>@endif
                        </div>
                    </div>
                </div>
                @elseif($hours)
                <div class="bg-white rounded-xl border border-stable-100 p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-safety/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-stable-900">Horaires d'ouverture</h3>
                            <p class="text-stable-500 text-sm mt-1">{{ $hours }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($hours && $phone)
                <div class="bg-white rounded-xl border border-stable-100 p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-safety/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-stable-900">Horaires d'ouverture</h3>
                            <p class="text-stable-500 text-sm mt-1">{{ $hours }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-2">
                @if(session('success'))
                <div class="mb-6 p-4 bg-stable-50 border border-stable-200 rounded-xl text-safety-dark text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-safety flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="bg-white rounded-xl border border-stable-100 p-6 lg:p-8">
                    @csrf
                    <h2 class="text-2xl font-bold text-stable-900 mb-6">Envoyez-nous un message</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-stable-900 mb-1.5">Nom complet <span class="text-cta">*</span></label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety focus:ring-1 focus:ring-safety transition-all @error('name') border-cta @enderror"
                                   placeholder="Ex. Jean Dupont">
                            @error('name')<p class="text-cta text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-stable-900 mb-1.5">Adresse e-mail <span class="text-cta">*</span></label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety focus:ring-1 focus:ring-safety transition-all @error('email') border-cta @enderror"
                                   placeholder="Ex. jean@exemple.com">
                            @error('email')<p class="text-cta text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-stable-900 mb-1.5">Numéro de téléphone</label>
                            <input type="tel" id="phone" name="phone"
                                   class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety focus:ring-1 focus:ring-safety transition-all"
                                   placeholder="Ex. 07 56 96 57 89">
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-stable-900 mb-1.5">Objet <span class="text-cta">*</span></label>
                            <select id="subject" name="subject" required
                                    class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety focus:ring-1 focus:ring-safety transition-all bg-white @error('subject') border-cta @enderror">
                                <option value="">Sélectionnez un objet</option>
                                <option value="Product Inquiry">Demande produit</option>
                                <option value="Commander Support">Support commande</option>
                                <option value="Returns & Exchanges">Retours et échanges</option>
                                <option value="Trade / Wholesale">Professionnels / Grossiste</option>
                                <option value="Technical Support">Support technique</option>
                                <option value="Other">Autre</option>
                            </select>
                            @error('subject')<p class="text-cta text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="message" class="block text-sm font-medium text-stable-900 mb-1.5">Message <span class="text-cta">*</span></label>
                        <textarea id="message" name="message" rows="5" required
                                  class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety focus:ring-1 focus:ring-safety transition-all @error('message') border-cta @enderror"
                                   placeholder="Décrivez votre demande..."></textarea>
                        @error('message')<p class="text-cta text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-safety/25 hover:shadow-safety/40">
                        Envoyer le message
                        <svg class="ml-2 w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

{{-- Map --}}
<section class="bg-white py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl lg:text-3xl font-bold text-stable-900">Où nous trouver</h2>
            <p class="text-stable-500 mt-2">Rendez-vous au 25 rue de Cogiandant Crénière, 10270 Courteranges, ou passez retirer votre commande sur place.</p>
        </div>
        <div class="aspect-video max-w-5xl mx-auto bg-stable-100 rounded-2xl overflow-hidden border border-stable-200">
            <iframe
                src="https://www.google.com/maps?q=25+rue+de+Cogiandant+Cr%C3%A9ni%C3%A8re,10270+Courteranges&output=embed"
                width="100%" height="100%" style="border:0; min-height:400px;" allowfullscreen loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                title="Localisation de Sellerie Epycuir"></iframe>
            <div class="hidden w-full h-full items-center justify-center bg-stable-100" style="min-height:400px;">
                <div class="text-center p-8">
                    <svg class="w-16 h-16 text-stable-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-stable-400 font-medium">Carte indisponible</p>
                    <p class="text-stable-400 text-sm">25 rue de Cogiandant Crénière, 10270 Courteranges, France</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FAQs --}}
<section class="py-12 lg:py-16 bg-stable-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <span class="text-safety font-semibold text-sm uppercase tracking-widest">FAQ</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-stable-900 mt-2">Questions fréquentes</h2>
        </div>
        <div class="space-y-3">
            @foreach($faqs as $index => $faq)
            <div class="faq-item bg-white rounded-xl border border-stable-100 overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-5 text-left transition-all duration-200 hover:bg-stable-50" data-index="{{ $index }}">
                    <span class="font-medium text-stable-900 text-sm pr-4">{{ $faq->question }}</span>
                    <svg class="faq-icon w-5 h-5 text-stable-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden px-5 pb-5">
                    <p class="text-stable-500 text-sm leading-relaxed">{{ $faq->answer }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.querySelectorAll('.faq-toggle').forEach(btn => {
        btn.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('.faq-icon');
            const isOpen = !answer.classList.contains('hidden');
            document.querySelectorAll('.faq-answer').forEach(a => a.classList.add('hidden'));
            document.querySelectorAll('.faq-icon').forEach(i => i.classList.remove('rotate-180'));
            if (!isOpen) {
                answer.classList.remove('hidden');
                icon.classList.add('rotate-180');
            }
        });
    });
</script>
@endpush

@endsection
