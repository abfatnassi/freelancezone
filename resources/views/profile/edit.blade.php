{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Modifier le profil')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">Modifier mon profil</h1>
    <div class="card">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div class="flex items-center gap-5">
                <img src="{{ $user->avatar_url }}" class="w-20 h-20 rounded-full object-cover">
                <div>
                    <label class="block text-sm font-medium mb-1">Photo de profil</label>
                    <input type="file" name="avatar" accept="image/*" class="text-sm text-gray-600">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Localisation</label>
                    <input type="text" name="location" value="{{ old('location', $user->location) }}"
                           class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500" placeholder="Marrakech, Maroc">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Bio</label>
                <textarea name="bio" rows="4"
                          class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                          placeholder="Décrivez votre expérience et vos services...">{{ old('bio', $user->bio) }}</textarea>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Site web</label>
                    <input type="url" name="website" value="{{ old('website', $user->website) }}"
                           class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500" placeholder="https://monsite.com">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500" placeholder="+212 6XX XXX XXX">
                </div>
            </div>
            @if($user->isFreelancer())
            <div>
                <label class="block text-sm font-medium mb-1">Taux horaire ($)</label>
                <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $user->hourly_rate) }}" min="1" step="0.5"
                       class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500" placeholder="25">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Compétences (séparées par virgule)</label>
                <input type="text" name="skills_input" value="{{ implode(', ', $user->skills ?? []) }}"
                       class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                       placeholder="Laravel, Vue.js, MySQL, Docker">
                <p class="text-xs text-gray-400 mt-1">Ces compétences sont affichées sur votre profil public.</p>
            </div>
            @endif
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Sauvegarder</button>
                <a href="{{ route('profile.show', $user) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
