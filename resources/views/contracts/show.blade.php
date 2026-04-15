{{-- resources/views/contracts/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Contrat #' . $contract->id)
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-2">Contrat <span class="text-indigo-600">#{{ $contract->id }}</span></h1>
    <p class="text-gray-500 text-sm mb-8">Projet : <a href="{{ route('projects.show', $contract->project) }}" class="text-indigo-600 hover:underline">{{ $contract->project->title }}</a></p>

    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-5">
            <div class="card">
                <h2 class="font-bold mb-4">Détails du contrat</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><div class="text-gray-500">Montant</div><div class="font-bold text-lg text-indigo-600">${{ number_format($contract->amount, 2) }}</div></div>
                    <div><div class="text-gray-500">Date de livraison</div><div class="font-semibold">{{ $contract->delivery_date->format('d/m/Y') }}</div></div>
                    <div><div class="text-gray-500">Statut</div>
                        <span class="font-semibold px-2 py-0.5 rounded text-xs
                            @if($contract->status==='active') bg-blue-100 text-blue-700
                            @elseif($contract->status==='completed') bg-green-100 text-green-700
                            @elseif($contract->status==='disputed') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($contract->status) }}
                        </span>
                    </div>
                    <div><div class="text-gray-500">Créé le</div><div class="font-semibold">{{ $contract->created_at->format('d/m/Y') }}</div></div>
                </div>
            </div>

            {{-- Actions --}}
            @if($contract->status === 'active')
            <div class="card bg-gray-50">
                <h3 class="font-semibold mb-3">Actions</h3>
                <div class="flex flex-wrap gap-3">
                    @if(Auth::id() === $contract->client_id)
                        <form action="{{ route('contracts.complete', $contract) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition"
                                    onclick="return confirm('Marquer ce contrat comme terminé ?')">
                                <i class="fas fa-check mr-2"></i> Marquer comme terminé
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('contracts.dispute', $contract) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-100 text-red-700 hover:bg-red-200 px-5 py-2.5 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Ouvrir un litige
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Review --}}
            @if($contract->status === 'completed')
            @php
                $myType = Auth::id() === $contract->client_id ? 'client_to_freelancer' : 'freelancer_to_client';
                $hasReviewed = $contract->review && $contract->review->type === $myType;
            @endphp
            @if(!$hasReviewed)
            <div class="card border-indigo-200 bg-indigo-50">
                <h3 class="font-bold text-indigo-700 mb-3">Laisser un avis</h3>
                <form action="{{ route('reviews.store', $contract) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Note (1–5)</label>
                        <div class="flex gap-2">
                            @for($i=1; $i<=5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                                <i class="fas fa-star text-2xl text-gray-300 hover:text-yellow-400 transition" id="star-{{ $i }}"></i>
                            </label>
                            @endfor
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Commentaire</label>
                        <textarea name="comment" rows="3" class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>
                    <button type="submit" class="btn-primary text-sm">Soumettre l'avis</button>
                </form>
            </div>
            @else
            <div class="card bg-green-50 border-green-200">
                <p class="text-green-700 text-sm font-medium"><i class="fas fa-check-circle mr-2"></i> Vous avez déjà soumis un avis pour ce contrat.</p>
            </div>
            @endif
            @endif
        </div>

        {{-- Parties --}}
        <div class="space-y-4">
            <div class="card text-center">
                <div class="text-xs text-gray-400 mb-2">CLIENT</div>
                <img src="{{ $contract->client->avatar_url }}" class="w-14 h-14 rounded-full mx-auto mb-2">
                <div class="font-semibold">{{ $contract->client->name }}</div>
                <a href="{{ route('messages.conversation', $contract->client) }}" class="text-xs text-indigo-600 hover:underline">Contacter</a>
            </div>
            <div class="card text-center">
                <div class="text-xs text-gray-400 mb-2">FREELANCE</div>
                <img src="{{ $contract->freelancer->avatar_url }}" class="w-14 h-14 rounded-full mx-auto mb-2">
                <div class="font-semibold">{{ $contract->freelancer->name }}</div>
                <a href="{{ route('messages.conversation', $contract->freelancer) }}" class="text-xs text-indigo-600 hover:underline">Contacter</a>
            </div>
        </div>
    </div>
</div>
@endsection
