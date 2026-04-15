{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title','Administration — FreelanceZone')
@section('content')
<div style="max-width:1280px;margin:0 auto;padding:3rem 2rem;">

    <div style="margin-bottom:2.5rem;">
        <div class="badge badge-rose" style="margin-bottom:0.75rem;">
            <i class="fas fa-shield-halved" style="font-size:0.65rem;"></i> Administration
        </div>
        <h1 style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;letter-spacing:-0.03em;">Tableau de bord Admin</h1>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2.5rem;">
        @foreach([
            [$stats['total_users'],'Utilisateurs','fas fa-users','linear-gradient(135deg,#7C3AED,#5B21B6)','rgba(124,58,237,0.12)'],
            [$stats['total_projects'],'Projets totaux','fas fa-folder-open','linear-gradient(135deg,#06B6D4,#0284C7)','rgba(6,182,212,0.12)'],
            [$stats['active_contracts'],'Contrats actifs','fas fa-file-contract','linear-gradient(135deg,#10B981,#059669)','rgba(16,185,129,0.12)'],
            ['$'.number_format($stats['total_revenue'],0),'Revenus (10%)','fas fa-coins','linear-gradient(135deg,#F59E0B,#D97706)','rgba(245,158,11,0.12)'],
        ] as [$val,$label,$icon,$grad,$bg])
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $bg }};">
                <i class="{{ $icon }}" style="background:{{ $grad }};-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-size:1.1rem;"></i>
            </div>
            <div>
                <div class="stat-value">{{ $val }}</div>
                <div class="stat-label">{{ $label }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        {{-- Users --}}
        <div class="card card-glow">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                <h2 style="font-family:'Syne',sans-serif;font-size:1.05rem;font-weight:700;">Derniers utilisateurs</h2>
                <a href="{{ route('admin.users') }}" style="font-size:0.825rem;color:#A78BFA;text-decoration:none;font-weight:600;">Gérer →</a>
            </div>
            <div style="display:flex;flex-direction:column;gap:0;">
            @foreach($recentUsers as $u)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.85rem 0;border-bottom:1px solid var(--border-soft);" class="{{ $loop->last ? 'last:border-0' : '' }}">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <img src="{{ $u->avatar_url }}" class="avatar avatar-sm">
                        <div>
                            <div style="font-weight:600;font-size:0.875rem;">{{ $u->name }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ $u->email }}</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        @php $rm=['admin'=>'badge-rose','client'=>'badge-cyan','freelancer'=>'badge-emerald']; @endphp
                        <span class="badge {{ $rm[$u->role]??'badge-gray' }}">{{ ucfirst($u->role) }}</span>
                        <form action="{{ route('admin.users.toggle',$u) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm" style="font-size:0.75rem;padding:0.3rem 0.7rem;color:{{ $u->is_active ? '#FB7185' : '#34D399' }};">
                                {{ $u->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
            </div>
        </div>

        {{-- Projects --}}
        <div class="card card-glow">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                <h2 style="font-family:'Syne',sans-serif;font-size:1.05rem;font-weight:700;">Derniers projets</h2>
                <a href="{{ route('admin.projects') }}" style="font-size:0.825rem;color:#A78BFA;text-decoration:none;font-weight:600;">Gérer →</a>
            </div>
            <div style="display:flex;flex-direction:column;gap:0;">
            @foreach($recentProjects as $p)
                @php $sm=['open'=>'badge-emerald','in_progress'=>'badge-cyan','completed'=>'badge-gray','cancelled'=>'badge-rose']; @endphp
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.85rem 0;border-bottom:1px solid var(--border-soft);">
                    <div>
                        <a href="{{ route('projects.show',$p) }}" style="font-weight:600;font-size:0.875rem;color:var(--text);text-decoration:none;" onmouseover="this.style.color='#A78BFA'" onmouseout="this.style.color='var(--text)'">{{ Str::limit($p->title,38) }}</a>
                        <div style="font-size:0.75rem;color:var(--text-muted);margin-top:0.15rem;">{{ $p->client->name }} · {{ $p->category?->name }}</div>
                    </div>
                    <span class="badge {{ $sm[$p->status]??'badge-gray' }}">{{ ucfirst(str_replace('_',' ',$p->status)) }}</span>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
