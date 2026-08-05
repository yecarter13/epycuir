@extends('admin.layouts.master')

@section('title', 'Utilisateurs — Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-stable-500">{{ $admins->count() }} utilisateur(s) admin</p>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-safety hover:bg-safety-dark text-white text-sm font-medium rounded-lg transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Créer un administrateur
        </a>
    </div>

    <div class="bg-white rounded-xl border border-stable-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-stable-50 text-stable-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="text-left px-4 py-3 font-medium">Nom</th>
                    <th class="text-left px-4 py-3 font-medium">Email</th>
                    <th class="text-left px-4 py-3 font-medium">Créé le</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stable-50">
                @foreach($admins as $admin)
                <tr class="hover:bg-stable-50/50">
                    <td class="px-4 py-3 font-medium text-stable-900">{{ $admin->name }}</td>
                    <td class="px-4 py-3 text-stable-500">{{ $admin->email }}</td>
                    <td class="px-4 py-3 text-stable-400 text-xs">{{ $admin->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
