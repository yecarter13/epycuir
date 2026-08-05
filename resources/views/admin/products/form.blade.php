@extends('admin.layouts.master')

@section('title', $product ? 'Modifier le produit' : 'Nouveau produit')

@section('content')
<a href="{{ route('admin.products.index') }}" class="text-stable-400 hover:text-stable-600 text-sm transition-colors">&larr; Retour aux produits</a>

<form action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 bg-white rounded-xl border border-stable-100 p-6 max-w-3xl" id="productForm">
    @csrf
    @if($product) @method('PUT') @endif

    <div class="flex items-center justify-between mb-6 pb-4 border-b border-stable-100">
        <h2 class="text-lg font-bold text-stable-900">{{ $product ? 'Modifier le produit' : 'Nouveau produit' }}</h2>
        <span class="text-xs text-stable-400 bg-stable-50 px-2.5 py-1 rounded-lg">Sellerie Super Confort AI</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-stable-900 mb-1.5">Nom du produit <span class="text-cta">*</span></label>
            <input type="text" name="name" id="productName" value="{{ old('name', $product?->name) }}" required class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
        </div>
        <div>
            <label class="block text-sm font-medium text-stable-900 mb-1.5">SKU <span class="text-stable-400 text-xs font-normal">(auto si vide)</span></label>
            <input type="text" name="sku" value="{{ old('sku', $product?->sku) }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all" placeholder="Laissez vide pour générer automatiquement">
            <p class="text-xs text-stable-400 mt-1">Code de référence unique du produit</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-stable-900 mb-1.5">Catégorie</label>
            <select name="category_id" id="categoryId" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all bg-white">
                <option value="">Aucune catégorie</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $product?->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-stable-900 mb-1.5">Prix (&euro;) <span class="text-cta">*</span></label>
            <input type="number" step="0.01" min="0" name="price" id="productPrice" value="{{ old('price', $product?->price) }}" required class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
        </div>
        <div>
            <label class="block text-sm font-medium text-stable-900 mb-1.5">Ancien prix</label>
            <input type="number" step="0.01" min="0" name="old_price" value="{{ old('old_price', $product?->old_price) }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
        </div>
        <div>
            <label class="block text-sm font-medium text-stable-900 mb-1.5">Quantité en stock</label>
            <input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', $product?->stock_quantity ?? 0) }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
        </div>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-stable-900 mb-1.5">Compatibilité</label>
        <input type="text" name="compatibility" value="{{ old('compatibility', $product?->compatibility) }}" placeholder="ex. cheval adulte, poney, licol, selle (2020-2025)" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
        <p class="text-xs text-stable-400 mt-1">Indiquez la marque, le modèle et les années pour lesquels cet article convient. Affiché sur la page produit pour aider les acheteurs à vérifier la compatibilité.</p>
    </div>

    <div class="mb-4 p-4 bg-stable-50 rounded-xl border border-stable-100">
        <label class="block text-sm font-medium text-stable-900 mb-2">Image du produit</label>
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div id="image-preview" class="w-24 h-24 bg-stable-100 rounded-xl border-2 border-dashed border-stable-200 flex items-center justify-center overflow-hidden">
                    @if($product && $product->image)
                    <img src="{{ $product->image }}" class="w-full h-full object-cover">
                    @else
                    <svg class="w-8 h-8 text-stable-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @endif
                </div>
            </div>
            <div class="flex-1 space-y-2">
                <input type="file" name="image_file" id="imageFile" accept="image/*" class="w-full text-sm text-stable-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-stable-100 file:text-stable-700 hover:file:bg-stable-200 transition-all">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-stable-400">Ou URL :</span>
                    <input type="url" name="image" id="imageUrl" value="{{ old('image', $product?->image) }}" placeholder="https://example.com/image.jpg" class="flex-1 px-3 py-1.5 border border-stable-200 rounded-lg text-sm focus:outline-none focus:border-safety transition-all">
                </div>
                <p class="text-xs text-stable-400">Téléversez une image ou collez une URL. Recommandé : 800&times;800px, JPG ou PNG.</p>
            </div>
        </div>
    </div>

    <div class="mb-4 p-4 bg-stable-50 rounded-xl border border-stable-100">
        <label class="block text-sm font-medium text-stable-900 mb-2">Images de la galerie</label>
        <p class="text-xs text-stable-400 mb-3">Téléversez plusieurs images pour la galerie du produit. Glissez pour réorganiser.</p>
        <div class="flex gap-2 flex-wrap mb-3" id="gallery-preview">
            @if($product && $product->gallery_images)
                @foreach($product->gallery_images as $img)
                <div class="gallery-item relative w-20 h-20 bg-stable-100 rounded-xl border border-stable-200 overflow-hidden group" data-path="{{ $loop->index < count($storedGallery) ? $storedGallery[$loop->index] : '' }}">
                    <img src="{{ $img }}" class="w-full h-full object-cover">
                    <button type="button" class="absolute top-1 right-1 w-5 h-5 bg-cta/90 hover:bg-cta rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity" onclick="removeGalleryItem(this)"> <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> </button>
                </div>
                @endforeach
            @endif
        </div>
        <div class="flex items-center gap-3">
            <input type="file" id="galleryFiles" accept="image/*" multiple class="w-full text-sm text-stable-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-stable-100 file:text-stable-700 hover:file:bg-stable-200 transition-all">
            <label class="flex items-center gap-1.5 text-xs text-stable-400 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Téléverser plusieurs
            </label>
        </div>
        <input type="hidden" name="gallery_images" id="galleryInput" value="{{ old('gallery_images', isset($storedGallery) && $storedGallery ? json_encode($storedGallery) : '[]') }}">
    </div>

    <div class="mb-4 p-4 bg-purple-50 border border-purple-200 rounded-xl">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <label class="text-sm font-medium text-stable-900">Générateur de description IA</label>
            </div>
            <span class="text-xs text-stable-400">Mots-clés séparés par des virgules</span>
        </div>
        <div class="flex gap-2">
            <input type="text" id="aiKeywords" placeholder="ex. confort, cuir, qualité premium" class="flex-1 px-4 py-2 border border-purple-200 rounded-lg text-sm focus:outline-none focus:border-purple-400 transition-all">
            <button type="button" id="generateAiBtn" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 disabled:bg-purple-400 text-white font-semibold rounded-lg text-sm transition-all duration-200 flex items-center gap-2 flex-shrink-0">
                <svg id="ai-loader" class="w-4 h-4 hidden animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <svg id="ai-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span id="ai-btn-text">Générer</span>
            </button>
        </div>
        <div id="ai-error" class="mt-2 hidden"></div>
        <div id="ai-success" class="mt-2 hidden"></div>
    </div>

    <div class="mb-4">
        <div class="flex items-center justify-between mb-1.5">
            <label class="block text-sm font-medium text-stable-900">Description</label>
            <button type="button" id="mediaBrowserBtn" class="text-xs text-purple-600 hover:text-purple-800 font-medium flex items-center gap-1 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Médiathèque
            </button>
        </div>
        <textarea name="description" id="productDescription" rows="5" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all font-mono">{{ old('description', $product?->description) }}</textarea>
        <p class="text-xs text-stable-400 mt-1">HTML pris en charge — utilisez la Médiathèque pour insérer des images et des vidéos.</p>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-stable-900 mb-1.5">Caractéristiques</label>
        <textarea name="specifications" id="productSpecs" rows="3" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">{{ old('specifications', $product?->specifications) }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-stable-900 mb-1.5">Titre SEO</label>
            <input type="text" name="meta_title" id="metaTitle" value="{{ old('meta_title', $product?->meta_title) }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
        </div>
        <div>
            <label class="block text-sm font-medium text-stable-900 mb-1.5">Méta description</label>
            <input type="text" name="meta_description" id="metaDescription" value="{{ old('meta_description', $product?->meta_description) }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
        </div>
    </div>

    <div class="flex items-center gap-6 mb-6">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product?->is_new) ? 'checked' : '' }} class="rounded border-stable-300 text-safety focus:ring-safety">
            <span class="text-sm text-stable-700">Marquer comme nouveau</span>
        </label>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product?->is_active ?? true) ? 'checked' : '' }} class="rounded border-stable-300 text-safety focus:ring-safety">
            <span class="text-sm text-stable-700">Active</span>
        </label>
    </div>

    <button type="submit" class="px-6 py-2.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-lg text-sm transition-all duration-200">
        {{ $product ? 'Mettre à jour le produit' : 'Créer le produit' }}
    </button>
</form>

@push('scripts')
<script>
// Media Browser
document.getElementById('mediaBrowserBtn')?.addEventListener('click', function() {
    if (document.getElementById('media-modal')) return;
    const overlay = document.createElement('div');
    overlay.id = 'media-modal';
    overlay.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/50';
    overlay.innerHTML = '<div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[80vh] overflow-hidden mx-4"><div class="flex items-center justify-between p-4 border-b border-stable-100"><h3 class="font-semibold text-stable-900">Médiathèque</h3><button id="closeModal" class="p-1 hover:bg-stable-50 rounded-lg transition-colors"><svg class="w-5 h-5 text-stable-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></div><div id="mediaGrid" class="p-4 grid grid-cols-4 gap-3 overflow-y-auto max-h-[60vh]"><div class="col-span-4 text-center text-stable-400 py-8"><svg class="w-8 h-8 mx-auto mb-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>Chargement...</div></div><div class="p-4 border-t border-stable-100"><label class="flex items-center gap-2 cursor-pointer"><svg class="w-5 h-5 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg><span class="text-sm text-stable-600">Téléverser un fichier</span><input type="file" id="modalFileUpload" accept="image/*,video/mp4" class="hidden"></label></div></div>';
    document.body.appendChild(overlay);

    document.getElementById('closeModal')?.addEventListener('click', () => overlay.remove());
    overlay.addEventListener('click', function(e) { if (e.target === this) overlay.remove(); });

    fetch('{{ route("admin.media.browser") }}')
        .then(r => r.json())
        .then(files => {
            const grid = document.getElementById('mediaGrid');
            if (!files.length) {
                grid.innerHTML = '<div class="col-span-4 text-center text-stable-400 py-8">Aucun média téléversé pour le moment. Téléversez une image ou une vidéo ci-dessus.</div>';
                return;
            }
            grid.innerHTML = files.map(f => {
                const isVideo = f.name.match(/\.(mp4|webm|ogg)$/i);
                const tag = isVideo ? '<video src="' + f.url + '" class="w-full h-full object-cover"></video>' : '<img src="' + f.url + '" class="w-full h-full object-cover">';
                const code = isVideo ? '<video controls><source src="' + f.url + '" type="video/mp4"></video>' : '<img src="' + f.url + '" alt="" style="max-width:100%">';
                return '<div class="media-item cursor-pointer rounded-xl border border-stable-100 overflow-hidden hover:border-safety transition-all aspect-square bg-stable-50" data-code="' + escapeHtml(code) + '">' + tag + '</div>';
            }).join('');

            document.querySelectorAll('.media-item').forEach(el => {
                el.addEventListener('click', function() {
                    const ta = document.getElementById('productDescription');
                    const code = this.dataset.code;
                    ta.value = ta.value + '\n' + code;
                    overlay.remove();
                });
            });
        });
});

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Image preview
document.getElementById('imageFile')?.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
            preview.classList.remove('border-dashed', 'border-stable-200');
        };
        reader.readAsDataURL(file);
    }
});
document.getElementById('imageUrl')?.addEventListener('input', function() {
    if (this.value) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '<img src="' + this.value + '" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML=\'<svg class=\\\'w-8 h-8 text-stable-300\\\' fill=\\\'none\\\' stroke=\\\'currentColor\\\' viewBox=\\\'0 0 24 24\\\'><path stroke-linecap=\\\'round\\\' stroke-linejoin=\\\'round\\\' stroke-width=\\\'2\\\' d=\\\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\\\'/></svg>\'">';
        preview.classList.remove('border-dashed', 'border-stable-200');
    }
});

document.addEventListener('change', function(e) {
    if (e.target.id === 'modalFileUpload') {
        const file = e.target.files[0];
        if (!file) return;
        const formData = new FormData();
        formData.append('file', file);
        formData.append('folder', 'descriptions');
        formData.append('_token', '{{ csrf_token() }}');
        const btn = e.target.closest('label');
        btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> <span class="text-sm">Téléversement...</span>';
        fetch('{{ route("admin.media.upload") }}', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.url) {
                    const ta = document.getElementById('productDescription');
                    const isVideo = data.name.match(/\.(mp4|webm|ogg)$/i);
                    const code = isVideo ? '<video controls><source src="' + data.url + '" type="video/mp4"></video>' : '<img src="' + data.url + '" alt="" style="max-width:100%">';
                    ta.value = ta.value + '\n' + code;
                    document.getElementById('media-modal')?.remove();
                }
            })
            .catch(() => { alert('Échec du téléversement'); document.getElementById('media-modal')?.remove(); });
    }
});

// Gallery images upload
const galleryInput = document.getElementById('galleryInput');
const galleryUrls = JSON.parse(galleryInput?.value || '[]').filter(Boolean);
const galleryError = document.createElement('div');
galleryError.className = 'mt-2 text-xs text-red-600 hidden';
document.getElementById('gallery-preview')?.after(galleryError);

document.getElementById('galleryFiles')?.addEventListener('change', function() {
    const files = Array.from(this.files);
    const preview = document.getElementById('gallery-preview');
    const input = document.getElementById('galleryInput');
    galleryError.classList.add('hidden');
    let pending = files.length;
    files.forEach(file => {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('folder', 'products');
        formData.append('_token', '{{ csrf_token() }}');
        fetch('{{ route("admin.media.upload") }}', { method: 'POST', body: formData })
            .then(r => { if (!r.ok) throw new Error('Échec du téléversement'); return r.json(); })
            .then(data => {
                if (data.url) {
                    const storeUrl = data.path || data.url;
                    galleryUrls.push(storeUrl);
                    input.value = JSON.stringify(galleryUrls);
                    const div = document.createElement('div');
                    div.className = 'gallery-item relative w-20 h-20 bg-stable-100 rounded-xl border border-stable-200 overflow-hidden group';
                    div.setAttribute('data-path', storeUrl);
                    div.innerHTML = '<img src="' + data.url + '" class="w-full h-full object-cover"><button type="button" class="absolute top-1 right-1 w-5 h-5 bg-cta/90 hover:bg-cta rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity" onclick="removeGalleryItem(this)"><svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
                    preview.appendChild(div);
                }
            })
            .catch(e => {
                galleryError.textContent = 'Échec du téléversement : ' + (e.message || 'erreur serveur');
                galleryError.classList.remove('hidden');
            })
            .finally(() => { pending--; if (!pending) document.getElementById('galleryFiles').value = ''; });
    });
});

window.removeGalleryItem = function(btn) {
    const item = btn.closest('.gallery-item');
    const path = item.getAttribute('data-path') || '';
    const idx = galleryUrls.indexOf(path);
    if (idx > -1) galleryUrls.splice(idx, 1);
    document.getElementById('galleryInput').value = JSON.stringify(galleryUrls);
    item.remove();
};

document.getElementById('generateAiBtn')?.addEventListener('click', function() {
    const name = document.getElementById('productName')?.value.trim();
    const category = document.getElementById('categoryId')?.value;
    const price = document.getElementById('productPrice')?.value;
    const keywords = document.getElementById('aiKeywords')?.value.trim();
    const errorEl = document.getElementById('ai-error');
    const successEl = document.getElementById('ai-success');
    const loader = document.getElementById('ai-loader');
    const icon = document.getElementById('ai-icon');
    const btnText = document.getElementById('ai-btn-text');

    errorEl.classList.add('hidden');
    successEl.classList.add('hidden');

    if (!name && !keywords) {
        errorEl.className = 'mt-2 p-2.5 bg-red-50 border border-red-200 rounded-lg text-red-600 text-xs flex items-center gap-2';
        errorEl.innerHTML = '<svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> Saisissez un nom de produit ou des mots-clés pour générer.';
        errorEl.classList.remove('hidden');
        (name ? document.getElementById('productName') : document.getElementById('aiKeywords'))?.focus();
        return;
    }

    this.disabled = true;
    loader.classList.remove('hidden');
    icon.classList.add('hidden');
    btnText.textContent = 'Génération...';

    fetch('{{ route("admin.ai.generate") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ name, category_id: category, price, keywords })
    })
    .then(r => {
        if (!r.ok) {
            return r.json().then(err => { throw new Error(err.message || err.errors?.name?.[0] || 'Erreur serveur'); }).catch(() => { throw new Error('Erreur serveur (' + r.status + ')'); });
        }
        return r.json();
    })
    .then(data => {
        let filled = 0;
        if (data.description) { document.getElementById('productDescription').value = data.description; filled++; }
        if (data.specifications) { document.getElementById('productSpecs').value = data.specifications; filled++; }
        if (data.meta_title) { document.getElementById('metaTitle').value = data.meta_title; filled++; }
        if (data.meta_description) { document.getElementById('metaDescription').value = data.meta_description; filled++; }

        successEl.className = 'mt-2 p-2.5 bg-green-50 border border-green-200 rounded-lg text-green-700 text-xs flex items-center gap-2';
        successEl.innerHTML = '<svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Terminé ! ' + filled + ' champ' + (filled > 1 ? 's' : '') + ' rempli' + (filled > 1 ? 's' : '') + '.';
        successEl.classList.remove('hidden');
        setTimeout(() => { successEl.classList.add('hidden'); }, 5000);
    })
    .catch(e => {
        errorEl.className = 'mt-2 p-2.5 bg-red-50 border border-red-200 rounded-lg text-red-600 text-xs flex items-center gap-2';
        errorEl.innerHTML = '<svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> ' + e.message;
        errorEl.classList.remove('hidden');
    })
    .finally(() => {
        this.disabled = false;
        loader.classList.add('hidden');
        icon.classList.remove('hidden');
        btnText.textContent = 'Générer';
    });
});
</script>
@endpush

@endsection
