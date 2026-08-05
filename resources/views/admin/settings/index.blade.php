@extends('admin.layouts.master')

@section('title', 'Paramètres')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" class="max-w-3xl">
    @csrf

    <div class="bg-white rounded-xl border border-stable-100 p-6 mb-6">
        <h2 class="font-semibold text-stable-900 mb-1">Mode de commande</h2>
        <p class="text-sm text-stable-500 mb-4">Choisissez comment passer commande sur le site.</p>
        <div class="space-y-3">
            @php $orderMode = App\Models\SiteSetting::getValue('order_mode', 'checkout'); @endphp
            <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer transition-all {{ $orderMode === 'checkout' ? 'border-safety bg-orange-50' : 'border-stable-200 hover:border-stable-300' }}">
                <input type="radio" name="order_mode" value="checkout" class="mt-1 accent-orange-500" {{ $orderMode === 'checkout' ? 'checked' : '' }}>
                <div>
                    <p class="font-semibold text-sm text-stable-900">Commande en ligne (Stripe)</p>
                    <p class="text-xs text-stable-500 mt-0.5">Les clients paient directement sur le site via Stripe. Le panier complet et le parcours de paiement sont actifs.</p>
                </div>
            </label>
            <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer transition-all {{ $orderMode === 'whatsapp' ? 'border-green-500 bg-green-50' : 'border-stable-200 hover:border-stable-300' }}">
                <input type="radio" name="order_mode" value="whatsapp" class="mt-1 accent-green-500" {{ $orderMode === 'whatsapp' ? 'checked' : '' }}>
                <div>
                    <p class="font-semibold text-sm text-stable-900">Commandes WhatsApp</p>
                    <p class="text-xs text-stable-500 mt-0.5">Le paiement en ligne est désactivé. Chaque bouton de commande redirige vers WhatsApp avec les détails du produit/panier pré-remplis. Le numéro WhatsApp ci-dessus doit être renseigné.</p>
                </div>
            </label>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-stable-100 p-6 mb-6">
        <h2 class="font-semibold text-stable-900 mb-4">Informations de contact</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Numéro WhatsApp</label>
                <input type="text" name="whatsapp_number" value="{{ App\Models\SiteSetting::getValue('whatsapp_number') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all" placeholder="447123456789">
            </div>
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Téléphone</label>
                <input type="text" name="phone" value="{{ App\Models\SiteSetting::getValue('phone') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ App\Models\SiteSetting::getValue('email') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Horaires d'ouverture</label>
                <input type="text" name="opening_hours" value="{{ App\Models\SiteSetting::getValue('opening_hours') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-sm font-medium text-stable-900 mb-1.5">Adresse</label>
            <textarea name="address" rows="2" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">{{ App\Models\SiteSetting::getValue('address') }}</textarea>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-stable-100 p-6 mb-6">
        <h2 class="font-semibold text-stable-900 mb-4">Réseaux sociaux</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Facebook URL</label>
                <input type="url" name="facebook_url" value="{{ App\Models\SiteSetting::getValue('facebook_url') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all" placeholder="https://facebook.com/...">
            </div>
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Instagram URL</label>
                <input type="url" name="instagram_url" value="{{ App\Models\SiteSetting::getValue('instagram_url') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all" placeholder="https://instagram.com/...">
            </div>
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">TikTok URL</label>
                <input type="url" name="tiktok_url" value="{{ App\Models\SiteSetting::getValue('tiktok_url') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all" placeholder="https://tiktok.com/@...">
            </div>
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Twitter / X URL</label>
                <input type="url" name="twitter_url" value="{{ App\Models\SiteSetting::getValue('twitter_url') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all" placeholder="https://twitter.com/...">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-stable-100 p-6 mb-6">
        <h2 class="font-semibold text-stable-900 mb-4">Bannières et médias</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Bannière principale 1</label>
                <input type="url" name="hero_banner_1" value="{{ App\Models\SiteSetting::getValue('hero_banner_1') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all" placeholder="https://...">
                <p class="text-xs text-stable-400 mt-1">Diapositive d'accueil 1 (1920x600)</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Bannière principale 2</label>
                <input type="url" name="hero_banner_2" value="{{ App\Models\SiteSetting::getValue('hero_banner_2') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all" placeholder="https://...">
                <p class="text-xs text-stable-400 mt-1">Diapositive d'accueil 2</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Bannière principale 3</label>
                <input type="url" name="hero_banner_3" value="{{ App\Models\SiteSetting::getValue('hero_banner_3') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all" placeholder="https://...">
                <p class="text-xs text-stable-400 mt-1">Diapositive d'accueil 3</p>
            </div>
        </div>
    </div>

    <button type="submit" class="px-6 py-2.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-lg text-sm transition-all duration-200">
        Enregistrer les paramètres
    </button>
</form>

@push('scripts')
<script>
document.querySelectorAll('input[type="url"]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value && this.previousElementSibling?.tagName !== 'P') {
            const preview = this.closest('div').querySelector('.preview');
            if (preview) preview.src = this.value;
        }
    });
});
</script>
@endpush
@endsection
