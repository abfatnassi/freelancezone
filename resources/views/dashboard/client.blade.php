{{-- resources/views/dashboard/client.blade.php --}}
@extends('layouts.app')
@section('title','Dashboard Client')
@section('content')
<div style="max-width:1280px;margin:0 auto;padding:3rem 2rem;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2.5rem;flex-wrap:wrap;gap:1rem;">
        <div style="display:flex;align-items:center;gap:1.25rem;">
            <img src="{{ Auth::user()->avatar_url }}" class="avatar" style="width:56px;height:56px;border:2px solid rgba(124,58,237,0.4);">
            <div>
                <p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:0.2rem;">Tableau de bord</p>
                <h1 style="font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800;letter-spacing:-0.03em;">
                    Bonjour, {{ explode(' ', Auth::user()->name)[0] }} 👋
                </h1>
            </div>
        </div>
        <a href="{{ route('projects.create') }}" class="btn btn-primary btn-md">
            <i class="fas fa-plus" style="font-size:0.8rem;"></i> Nouveau projet
        </a>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2.5rem;">
        @foreach([
            [$stats['total_projects'],'Projets publiés','fas fa-folder-open','linear-gradient(135deg,#7C3AED,#5B21B6)','rgba(124,58,237,0.12)'],
            [$stats['active_projects'],'En cours','fas fa-spinner','linear-gradient(135deg,#06B6D4,#0284C7)','rgba(6,182,212,0.12)'],
            [$stats['completed_projects'],'Terminés','fas fa-check-circle','linear-gradient(135deg,#10B981,#059669)','rgba(16,185,129,0.12)'],
            [$stats['total_bids'],'Offres reçues','fas fa-gavel','linear-gradient(135deg,#F59E0B,#D97706)','rgba(245,158,11,0.12)'],
        ] as [$val,$label,$icon,$grad,$bg])
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $bg }};border:1px solid rgba(255,255,255,0.05);">
                <i class="{{ $icon }}" style="background:{{ $grad }};-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-size:1.1rem;"></i>
            </div>
            <div>
                <div class="stat-value">{{ $val }}</div>
                <div class="stat-label">{{ $label }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Projects table --}}
    <div class="card card-glow">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <h2 style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;">Mes projets récents</h2>
            <a href="{{ route('projects.mine') }}" style="font-size:0.825rem;color:#A78BFA;text-decoration:none;font-weight:600;">Voir tout →</a>
        </div>

        @if($recentProjects->isEmpty())
            <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted);">
                <i class="fas fa-folder-open" style="font-size:2rem;color:var(--text-dim);margin-bottom:0.75rem;display:block;"></i>
                Aucun projet publié.
                <a href="{{ route('projects.create') }}" style="color:#A78BFA;font-weight:600;text-decoration:none;"> Créer votre premier projet →</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr>
                        <th>Projet</th><th>Statut</th><th>Offres</th><th>Budget</th><th>Date</th><th></th>
                    </tr></thead>
                    <tbody>
                    @foreach($recentProjects as $p)
                        @php $statusMap=['open'=>'badge-emerald','in_progress'=>'badge-cyan','completed'=>'badge-gray','cancelled'=>'badge-rose']; @endphp
                        <tr>
                            <td style="font-weight:600;color:var(--text);max-width:220px;">{{ Str::limit($p->title,45) }}</td>
                            <td><span class="badge {{ $statusMap[$p->status]??'badge-gray' }}">{{ ucfirst(str_replace('_',' ',$p->status)) }}</span></td>
                            <td><span style="font-weight:600;color:#A78BFA;">{{ $p->bids->count() }}</span></td>
                            <td style="color:#34D399;font-weight:600;">{{ $p->budget_range }}</td>
                            <td>{{ $p->created_at->format('d/m/Y') }}</td>
                            <td><a href="{{ route('projects.show',$p) }}" style="color:#A78BFA;text-decoration:none;font-size:0.8rem;font-weight:600;">Voir →</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
