{{-- resources/views/projects/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Publier un projet')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">Publier un nouveau projet</h1>
    <div class="card">
        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Titre du projet <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
                       placeholder="Ex: Développement d'une application e-commerce Laravel">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Description détaillée <span class="text-red-500">*</span></label>
                <textarea name="description" rows="6"
                          class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror"
                          placeholder="Décrivez votre projet en détail : fonctionnalités attendues, technologies souhaitées, contraintes...">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Catégorie <span class="text-red-500">*</span></label>
                <select name="category_id" class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('category_id') border-red-500 @enderror">
                    <option value="">Choisir une catégorie</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Budget minimum ($) <span class="text-red-500">*</span></label>
                    <input type="number" name="budget_min" value="{{ old('budget_min') }}" min="5" step="1"
                           class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('budget_min') border-red-500 @enderror"
                           placeholder="100">
                    @error('budget_min')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Budget maximum ($) <span class="text-red-500">*</span></label>
                    <input type="number" name="budget_max" value="{{ old('budget_max') }}" min="5" step="1"
                           class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('budget_max') border-red-500 @enderror"
                           placeholder="500">
                    @error('budget_max')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Date limite</label>
                <input type="date" name="deadline" value="{{ old('deadline') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Compétences requises</label>
                <input type="text" name="required_skills_input" value="{{ old('required_skills_input') }}"
                       class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                       placeholder="Laravel, Vue.js, MySQL (séparées par virgule)">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Pièces jointes (max 5 Mo chacune)</label>
                <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.png"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary"><i class="fas fa-upload mr-2"></i> Publier le projet</button>
                <a href="{{ route('projects.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
