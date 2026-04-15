@extends('layouts.app')
@section('title', $project->title)
@section('content')
<div style="max-width:1280px;margin:0 auto;padding:3rem 2rem;">

    {{-- Breadcrumb --}}
    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.8rem;color:var(--text-muted);margin-bottom:2rem;">
        <a href="{{ route('projects.index') }}" style="color:var(--text-muted);text-decoration:none;" onmouseover="this.style.color='#A78BFA'" onmouseout="this.style.color=''">Projets</a>
        <i class="fas fa-chevron-right" style="font-size:0.65rem;"></i>
        <span style="color:var(--text);">{{ Str::limit($project->title, 50) }}</span>
    </div>

    <div style="display:grid;grid-template-columns:1fr 320px;gap:2rem;align-items:start;">

        {{-- Main content --}}
        <div style="display:flex;flex-direction:column;gap:1.5rem;">

            {{-- Project card --}}
            <div class="card card-glow">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.25rem;flex-wrap:wrap;">
                    <div>
                        @if($project->category)
                            <span class="badge badge-violet" style="margin-bottom:0.75rem;">{{ $project->category->name }}</span>
                        @endif
                        <h1 style="font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800;letter-spacing:-0.03em;line-height:1.2;">{{ $project->title }}</h1>
                    </div>
                    @php $sm=['open'=>'badge-emerald','in_progress'=>'badge-cyan','completed'=>'badge-gray','cancelled'=>'badge-rose']; @endphp
                    <span class="badge {{ $sm[$project->status]??'badge-gray' }}" style="font-size:0.75rem;padding:0.4rem 0.85rem;">
                        {{ ucfirst(str_replace('_',' ',$project->status)) }}
                    </span>
                </div>

                <div style="color:var(--text-muted);font-size:0.9rem;line-height:1.8;margin-bottom:1.5rem;">
                    {!! nl2br(e($project->description)) !!}
                </div>

                @if($project->required_skills)
                    <div style="display:flex;flex-wrap:wrap;gap:0.4rem;margin-bottom:1.5rem;">
                        @foreach($project->required_skills as $skill)
                            <span class="badge badge-gray">{{ $skill }}</span>
                        @endforeach
                    </div>
                @endif

                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;padding:1.25rem;background:var(--surface2);border-radius:14px;border:1px solid var(--border-soft);">
                    <div style="text-align:center;">
                        <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;color:#34D399;">{{ $project->budget_range }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:0.2rem;">Budget</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;color:#A78BFA;">{{ $project->bids->count() }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:0.2rem;">Offres</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;">{{ $project->deadline?->format('d/m/Y') ?? '—' }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:0.2rem;">Deadline</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-family:'Syne',sans-serif;font-size:0.85rem;font-weight:600;">{{ $project->created_at->diffForHumans() }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:0.2rem;">Publié</div>
                    </div>
                </div>
            </div>

            {{-- Bid form --}}
            @auth
            @if(Auth::user()->isFreelancer() && $project->status === 'open')
                @if($userBid)
                <div class="card" style="border-color:rgba(124,58,237,0.3);background:rgba(124,58,237,0.05);">
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(124,58,237,0.15);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-check" style="color:#A78BFA;font-size:0.9rem;"></i>
                        </div>
                        <h3 style="font-family:'Syne',sans-serif;font-weight:700;">Votre offre soumise</h3>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1rem;">
                        <div style="background:var(--surface2);border-radius:10px;padding:0.85rem;text-align:center;">
                            <div style="font-weight:700;color:#34D399;">${{ number_format($userBid->amount) }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">Montant</div>
                        </div>
                        <div style="background:var(--surface2);border-radius:10px;padding:0.85rem;text-align:center;">
                            <div style="font-weight:700;">{{ $userBid->delivery_days }} j</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">Délai</div>
                        </div>
                        <div style="background:var(--surface2);border-radius:10px;padding:0.85rem;text-align:center;">
                            @php $sm=['pending'=>'#FCD34D','accepted'=>'#34D399','rejected'=>'#FB7185','withdrawn'=>'#8B8BA0']; @endphp
                            <div style="font-weight:700;color:{{ $sm[$userBid->status]??'#8B8BA0' }};">{{ ucfirst($userBid->status) }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">Statut</div>
                        </div>
                    </div>
                    <p style="color:var(--text-muted);font-size:0.875rem;line-height:1.7;">{{ $userBid->cover_letter }}</p>
                </div>
                @else
                <div class="card card-glow">
                    <h3 style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.6rem;">
                        <i class="fas fa-paper-plane" style="color:#7C3AED;font-size:0.9rem;"></i> Soumettre une offre
                    </h3>
                    <form action="{{ route('bids.store', $project) }}" method="POST" style="display:flex;flex-direction:column;gap:1.1rem;">
                        @csrf
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                            <div>
                                <label class="label">Montant ($)</label>
                                <input class="input" type="number" name="amount" min="5" step="0.01" value="{{ old('amount') }}" placeholder="500">
                                @error('amount')<p style="color:#FB7185;font-size:0.78rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="label">Délai (jours)</label>
                                <input class="input" type="number" name="delivery_days" min="1" value="{{ old('delivery_days') }}" placeholder="14">
                                @error('delivery_days')<p style="color:#FB7185;font-size:0.78rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="label">Lettre de motivation</label>
                            <textarea class="input" name="cover_letter" rows="5" placeholder="Décrivez votre expérience et pourquoi vous êtes le meilleur candidat...">{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')<p style="color:#FB7185;font-size:0.78rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-md">
                            <i class="fas fa-paper-plane" style="font-size:0.8rem;"></i> Envoyer mon offre
                        </button>
                    </form>
                </div>
                @endif
            @endif
            @endauth

            {{-- Bids list for client --}}
            @auth
            @if(Auth::id() === $project->client_id && $project->bids->count())
            <div class="card card-glow">
                <h3 style="font-family:'Syne',sans-serif;font-size:1.05rem;font-weight:700;margin-bottom:1.25rem;">
                    Offres reçues <span style="color:#7C3AED;">({{ $project->bids->count() }})</span>
                </h3>
                <div style="display:flex;flex-direction:column;gap:1rem;">
                @foreach($project->bids->sortBy('amount') as $bid)
                    <div style="background:var(--surface2);border:1px solid {{ $bid->status==='accepted' ? 'rgba(16,185,129,0.3)' : 'var(--border-soft)' }};border-radius:14px;padding:1.25rem;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.85rem;flex-wrap:wrap;gap:0.75rem;">
                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                <img src="{{ $bid->freelancer->avatar_url }}" class="avatar avatar-sm">
                                <div>
                                    <a href="{{ route('profile.show',$bid->freelancer) }}" style="font-weight:600;font-size:0.9rem;color:var(--text);text-decoration:none;" onmouseover="this.style.color='#A78BFA'" onmouseout="this.style.color='var(--text)'">{{ $bid->freelancer->name }}</a>
                                    <div style="font-size:0.75rem;color:#FCD34D;">★ {{ $bid->freelancer->average_rating }}/5</div>
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-family:'Syne',sans-serif;font-size:1.25rem;font-weight:700;color:#34D399;">${{ number_format($bid->amount) }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $bid->delivery_days }} jours</div>
                            </div>
                        </div>
                        <p style="font-size:0.85rem;color:var(--text-muted);line-height:1.6;margin-bottom:0.85rem;">{{ Str::limit($bid->cover_letter, 180) }}</p>
                        @if($bid->status === 'pending' && $project->status === 'open')
                            <form action="{{ route('bids.accept',$bid) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check" style="font-size:0.7rem;"></i> Accepter cette offre
                                </button>
                            </form>
                        @elseif($bid->status === 'accepted')
                            <span class="badge badge-emerald"><i class="fas fa-check-circle" style="font-size:0.65rem;"></i> Offre acceptée</span>
                        @endif
                    </div>
                @endforeach
                </div>
            </div>
            @endif
            @endauth
        </div>

        {{-- Sidebar --}}
        <div style="display:flex;flex-direction:column;gap:1rem;position:sticky;top:88px;">
            <div class="card" style="text-align:center;">
                <img src="{{ $project->client->avatar_url }}" class="avatar avatar-lg" style="margin:0 auto 1rem;">
                <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;margin-bottom:0.2rem;">{{ $project->client->name }}</h3>
                <p style="font-size:0.8rem;color:#A78BFA;margin-bottom:0.5rem;">Client</p>
                @if($project->client->location)
                    <p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:1rem;">
                        <i class="fas fa-location-dot" style="font-size:0.7rem;"></i> {{ $project->client->location }}
                    </p>
                @endif
                <a href="{{ route('profile.show',$project->client) }}" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;">Voir le profil</a>
                @auth @if(Auth::id() !== $project->client_id)
                    <a href="{{ route('messages.conversation',$project->client) }}" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;margin-top:0.5rem;">
                        <i class="fas fa-envelope" style="font-size:0.75rem;"></i> Contacter
                    </a>
                @endif @endauth
            </div>

            @if($project->contract)
            <div class="card" style="border-color:rgba(6,182,212,0.3);background:rgba(6,182,212,0.05);">
                <div style="font-size:0.8rem;font-weight:600;color:#22D3EE;margin-bottom:0.5rem;">
                    <i class="fas fa-file-contract" style="margin-right:0.4rem;"></i> Contrat actif
                </div>
                <a href="{{ route('contracts.show',$project->contract) }}" style="font-size:0.875rem;color:#22D3EE;text-decoration:none;font-weight:600;">
                    Voir le contrat #{{ $project->contract->id }} →
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
