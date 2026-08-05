@extends('admin.layouts.master')

@section('title', $category ? 'Modifier la catégorie' : 'Créer une catégorie')

@section('content')
<a href="{{ route('admin.categories.index') }}" class="text-stable-400 hover:text-stable-600 text-sm transition-colors">&larr; Retour aux catégories</a>

<form action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" class="mt-4 bg-white rounded-xl border border-stable-100 p-6 max-w-2xl">
    @csrf
    @if($category) @method('PUT') @endif

    <div class="mb-4">
        <label class="block text-sm font-medium text-stable-900 mb-1.5">Nom de la catégorie</label>
        <input type="text" name="name" value="{{ old('name', $category?->name) }}" required class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-stable-900 mb-1.5">Description</label>
        <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">{{ old('description', $category?->description) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-stable-900 mb-1.5">Image (nom du fichier)</label>
            <input type="text" name="image" value="{{ old('image', $category?->image) }}" placeholder="ex. selles.png" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
        </div>
        <div>
            <label class="block text-sm font-medium text-stable-900 mb-1.5">Ordre d'affichage</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $category?->sort_order ?? 0) }}" min="0" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
        </div>
    </div>

    <div class="mb-6">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }} class="rounded border-stable-300 text-safety focus:ring-safety">
            <span class="text-sm text-stable-700">Active</span>
        </label>
    </div>

    <button type="submit" class="px-6 py-2.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-lg text-sm transition-all duration-200">
        {{ $category ? 'Mettre à jour la catégorie' : 'Créer la catégorie' }}
    </button>
</form>
@endsection
