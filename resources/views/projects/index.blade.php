@extends('layouts.app')
@section('title','Explorer les projets')
@section('content')
<div style="max-width:1280px;margin:0 auto;padding:3rem 2rem;">

    {{-- Header --}}
    <div style="margin-bottom:2.5rem;display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
            <div class="badge badge-violet" style="margin-bottom:0.75rem;">
                <i class="fas fa-compass" style="font-size:0.65rem;"></i> Projets disponibles
            </div>
            <h1 class="page-title" style="font-size:2rem;">Explorer les projets
                <span style="font-size:1.1rem;font-weight:400;color:var(--text-muted);"> ({{ $projects->total() }})</span>
            </h1>
        </div>
        @auth @if(Auth::user()->isClient())
            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-md">
                <i class="fas fa-plus" style="font-size:0.8rem;"></i> Publier un projet
            </a>
        @endif @endauth
    </div>

    <div style="display:grid;grid-template-columns:260px 1fr;gap:2rem;align-items:start;">

        {{-- Sidebar --}}
        <aside>
            <div class="filter-sidebar">
                <div class="filter-title">
                    <i class="fas fa-sliders" style="color:#7C3AED;font-size:0.85rem;"></i> Filtres
                </div>
                <form method="GET" style="display:flex;flex-direction:column;gap:1.1rem;">
                    <div>
                        <label class="label">Recherche</label>
                        <div style="position:relative;">
                            <i class="fas fa-search" style="position:absolute;left:0.85rem;top:50%;transform:translateY(-50%);color:var(--text-dim);font-size:0.8rem;"></i>
                            <input class="input" type="text" name="q" value="{{ request('q') }}" placeholder="Mot-clé..." style="padding-left:2.4rem;">
                        </div>
                    </div>
                    <div>
                        <label class="label">Catégorie</label>
                        <select class="input" name="category">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>
                                    {{ $cat->name }} ({{ $cat->projects_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Budget max ($)</label>
                        <input class="input" type="number" name="budget_max" value="{{ request('budget_max') }}" placeholder="Ex: 5000">
                    </div>
                    <div>
                        <label class="label">Trier par</label>
                        <select class="input" name="sort">
                            <option value="newest">Plus récent</option>
                            <option value="oldest" {{ request('sort')==='oldest'?'selected':'' }}>Plus ancien</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;padding:0.7rem;">
                        <i class="fas fa-filter" style="font-size:0.75rem;"></i> Appliquer
                    </button>
                    @if(request()->hasAny(['q','category','budget_max','sort']))
                        <a href="{{ route('projects.index') }}" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;">
                            <i class="fas fa-xmark" style="font-size:0.75rem;"></i> Réinitialiser
                        </a>
                    @endif
                </form>
            </div>
        </aside>

        {{-- Project list --}}
        <div>
            @if($projects->isEmpty())
                <div class="card" style="text-align:center;padding:4rem 2rem;">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(124,58,237,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                        <i class="fas fa-search" style="color:#7C3AED;font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:0.5rem;">Aucun projet trouvé</h3>
                    <p style="color:var(--text-muted);font-size:0.875rem;">Essayez d'autres mots-clés ou réinitialisez les filtres.</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    @foreach($projects as $project)
                    <div class="card card-hover card-glow" style="padding:1.5rem;">
                        <div style="display:flex;gap:1.25rem;align-items:flex-start;">
                            <img src="{{ $project->client->avatar_url }}" class="avatar avatar-md" style="flex-shrink:0;">
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:0.6rem;">
                                    <a href="{{ route('projects.show', $project) }}" style="font-family:'Syne',sans-serif;font-size:1.05rem;font-weight:700;color:var(--text);text-decoration:none;transition:color 0.2s;line-height:1.3;" onmouseover="this.style.color='#A78BFA'" onmouseout="this.style.color='var(--text)'">
                                        {{ $project->title }}
                                    </a>
                                    <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                                        @if($project->is_featured)
                                            <span class="badge badge-amber"><i class="fas fa-star" style="font-size:0.6rem;"></i> Featured</span>
                                        @endif
                                        @php $statusMap = ['open'=>'badge-emerald','in_progress'=>'badge-cyan','completed'=>'badge-gray','cancelled'=>'badge-rose']; @endphp
                                        <span class="badge {{ $statusMap[$project->status] ?? 'badge-gray' }}">{{ ucfirst(str_replace('_',' ',$project->status)) }}</span>
                                    </div>
                                </div>
                                <p style="color:var(--text-muted);font-size:0.875rem;line-height:1.6;margin-bottom:1rem;">{{ Str::limit($project->description, 140) }}</p>

                                <div style="display:flex;flex-wrap:wrap;gap:0.4rem;margin-bottom:1rem;">
                                    @if($project->category)
                                        <span class="badge badge-violet">{{ $project->category->name }}</span>
                                    @endif
                                    @foreach(array_slice($project->required_skills ?? [], 0, 4) as $skill)
                                        <span class="badge badge-gray">{{ $skill }}</span>
                                    @endforeach
                                </div>

                                <div style="display:flex;align-items:center;gap:1.5rem;font-size:0.82rem;color:var(--text-muted);flex-wrap:wrap;">
                                    <span style="display:flex;align-items:center;gap:0.4rem;color:#34D399;font-weight:600;">
                                        <i class="fas fa-dollar-sign" style="font-size:0.75rem;"></i> {{ $project->budget_range }}
                                    </span>
                                    <span style="display:flex;align-items:center;gap:0.4rem;">
                                        <i class="fas fa-gavel" style="font-size:0.75rem;color:#7C3AED;"></i> {{ $project->bids->count() }} offre(s)
                                    </span>
                                    @if($project->deadline)
                                    <span style="display:flex;align-items:center;gap:0.4rem;">
                                        <i class="fas fa-calendar" style="font-size:0.75rem;color:#F59E0B;"></i> {{ $project->deadline->format('d/m/Y') }}
                                    </span>
                                    @endif
                                    <span style="margin-left:auto;color:var(--text-dim);">{{ $project->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div style="margin-top:2rem;">
                    {{ $projects->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
