@extends('admin.layouts.master')

@section('title', 'Créer un administrateur')

@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-sm text-stable-500 hover:text-stable-700 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux utilisateurs
    </a>

    <div class="bg-white rounded-xl border border-stable-100 p-6 lg:p-8">
        <h2 class="text-xl font-bold text-stable-900 mb-6">Créer un administrateur</h2>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Nom</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
                @error('name')<p class="text-cta text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Email</label>
                <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
                @error('email')<p class="text-cta text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stable-900 mb-1.5">Mot de passe</label>
                <input type="password" name="password" required class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
                @error('password')<p class="text-cta text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="px-6 py-2.5 bg-safety hover:bg-safety-dark text-white font-medium rounded-xl transition-colors">Créer l'administrateur</button>
        </form>
    </div>
</div>
@endsection
