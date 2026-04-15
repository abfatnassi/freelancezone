{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.app')
@section('title', $user->name . ' — Profil')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="grid md:grid-cols-3 gap-8">
        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="card text-center">
                <img src="{{ $user->avatar_url }}" class="w-28 h-28 rounded-full mx-auto mb-4 object-cover ring-4 ring-indigo-100">
                <h1 class="text-xl font-bold">{{ $user->name }}</h1>
                <p class="text-indigo-600 text-sm font-medium mt-1">{{ ucfirst($user->role) }}</p>
                @if($user->location)
                    <p class="text-gray-500 text-sm mt-1"><i class="fas fa-map-marker-alt mr-1"></i>{{ $user->location }}</p>
                @endif
                @if($user->hourly_rate)
                    <p class="text-gray-700 font-semibold mt-2">${{ number_format($user->hourly_rate) }}/h</p>
                @endif
                <div class="flex justify-center gap-1 mt-2">
                    @for($i=1; $i<=5; $i++)
                        <i class="fas fa-star {{ $i <= $user->average_rating ? 'text-yellow-400' : 'text-gray-200' }} text-sm"></i>
                    @endfor
                    <span class="text-sm text-gray-500 ml-1">{{ $user->average_rating }}/5 ({{ $user->reviews->count() }})</span>
                </div>
                @auth @if(Auth::id() !== $user->id)
                    <a href="{{ route('messages.conversation', $user) }}" class="mt-4 block btn-primary text-sm text-center">
                        <i class="fas fa-envelope mr-1"></i> Contacter
                    </a>
                @else
                    <a href="{{ route('profile.edit') }}" class="mt-4 block btn-secondary text-sm text-center">
                        <i class="fas fa-edit mr-1"></i> Modifier le profil
                    </a>
                @endif @endauth
            </div>

            <div class="card">
                <h3 class="font-semibold mb-3">Statistiques</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Projets complétés</span><span class="font-semibold">{{ $completedContracts }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Avis reçus</span><span class="font-semibold">{{ $user->reviews->count() }}</span></div>
                    @if($user->website)
                        <div class="flex justify-between"><span class="text-gray-500">Site web</span>
                            <a href="{{ $user->website }}" class="text-indigo-600 hover:underline" target="_blank">Visiter</a>
                        </div>
                    @endif
                </div>
            </div>

            @if($user->skills && count($user->skills))
            <div class="card">
                <h3 class="font-semibold mb-3">Compétences</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->skills as $skill)
                        <span class="bg-indigo-50 text-indigo-600 text-xs px-3 py-1.5 rounded-full">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Main --}}
        <div class="md:col-span-2 space-y-5">
            @if($user->bio)
            <div class="card">
                <h2 class="font-bold text-lg mb-3">À propos</h2>
                <p class="text-gray-600 leading-relaxed">{{ $user->bio }}</p>
            </div>
            @endif

            {{-- Reviews --}}
            <div class="card">
                <h2 class="font-bold text-lg mb-4">Avis ({{ $user->reviews->count() }})</h2>
                @if($user->reviews->isEmpty())
                    <p class="text-gray-400 text-sm text-center py-6">Aucun avis pour le moment.</p>
                @else
                    <div class="space-y-4">
                    @foreach($user->reviews->take(5) as $review)
                        <div class="border-b pb-4 last:border-0">
                            <div class="flex items-center gap-3 mb-2">
                                <img src="{{ $review->reviewer->avatar_url }}" class="w-9 h-9 rounded-full">
                                <div>
                                    <div class="font-medium text-sm">{{ $review->reviewer->name }}</div>
                                    <div class="flex gap-0.5">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <span class="ml-auto text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if($review->comment)
                                <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
