{{-- resources/views/dashboard/freelancer.blade.php --}}
@extends('layouts.app')
@section('title','Dashboard Freelance')
@section('content')
<div style="max-width:1280px;margin:0 auto;padding:3rem 2rem;">

    <div style="display:flex;align-items:center;gap:1.25rem;margin-bottom:2.5rem;">
        <img src="{{ Auth::user()->avatar_url }}" class="avatar" style="width:56px;height:56px;border:2px solid rgba(124,58,237,0.4);">
        <div>
            <p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:0.2rem;">Tableau de bord Freelance</p>
            <h1 style="font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800;letter-spacing:-0.03em;">
                Bonjour, {{ explode(' ', Auth::user()->name)[0] }} 👋
            </h1>
        </div>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2.5rem;">
        @foreach([
            [$stats['total_bids'],'Offres soumises','fas fa-paper-plane','linear-gradient(135deg,#7C3AED,#5B21B6)','rgba(124,58,237,0.12)'],
            [$stats['accepted_bids'],'Offres acceptées','fas fa-check','linear-gradient(135deg,#10B981,#059669)','rgba(16,185,129,0.12)'],
            [$stats['active_contracts'],'Contrats actifs','fas fa-file-contract','linear-gradient(135deg,#06B6D4,#0284C7)','rgba(6,182,212,0.12)'],
            ['$'.number_format($stats['total_earned']),'Total gagné','fas fa-coins','linear-gradient(135deg,#F59E0B,#D97706)','rgba(245,158,11,0.12)'],
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

        {{-- Recent bids --}}
        <div class="card card-glow">
            <h2 style="font-family:'Syne',sans-serif;font-size:1.05rem;font-weight:700;margin-bottom:1.25rem;">Mes dernières offres</h2>
            @if($recentBids->isEmpty())
                <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:0.875rem;">
                    Aucune offre soumise.<br>
                    <a href="{{ route('projects.index') }}" style="color:#A78BFA;font-weight:600;text-decoration:none;">Explorer les projets →</a>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:0.75rem;">
                @foreach($recentBids as $bid)
                    @php $sm=['pending'=>'badge-amber','accepted'=>'badge-emerald','rejected'=>'badge-rose','withdrawn'=>'badge-gray']; @endphp
                    <div style="display:flex;align-items:center;justify-content:space-between;background:var(--surface2);border:1px solid var(--border-soft);border-radius:12px;padding:1rem;">
                        <div style="min-width:0;">
                            <a href="{{ route('projects.show',$bid->project) }}" style="font-size:0.875rem;font-weight:600;color:var(--text);text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;" onmouseover="this.style.color='#A78BFA'" onmouseout="this.style.color='var(--text)'">{{ Str::limit($bid->project->title,35) }}</a>
                            <div style="font-size:0.78rem;color:var(--text-muted);margin-top:0.2rem;">${{ number_format($bid->amount) }} · {{ $bid->delivery_days }} jours</div>
                        </div>
                        <span class="badge {{ $sm[$bid->status]??'badge-gray' }}">{{ ucfirst($bid->status) }}</span>
                    </div>
                @endforeach
                </div>
            @endif
        </div>

        {{-- Open projects --}}
        <div class="card card-glow">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                <h2 style="font-family:'Syne',sans-serif;font-size:1.05rem;font-weight:700;">Projets disponibles</h2>
                <a href="{{ route('projects.index') }}" style="font-size:0.825rem;color:#A78BFA;text-decoration:none;font-weight:600;">Voir tout →</a>
            </div>
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
            @foreach($openProjects as $p)
                <a href="{{ route('projects.show',$p) }}" style="display:flex;align-items:center;justify-content:space-between;background:var(--surface2);border:1px solid var(--border-soft);border-radius:12px;padding:1rem;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.borderColor='rgba(124,58,237,0.35)';this.style.background='rgba(124,58,237,0.06)'" onmouseout="this.style.borderColor='var(--border-soft)';this.style.background='var(--surface2)'">
                    <div>
                        <div style="font-size:0.875rem;font-weight:600;color:var(--text);">{{ Str::limit($p->title,38) }}</div>
                        <div style="font-size:0.78rem;color:var(--text-muted);margin-top:0.2rem;">{{ $p->category?->name }} · {{ $p->budget_range }}</div>
                    </div>
                    <i class="fas fa-arrow-right" style="color:var(--text-dim);font-size:0.8rem;"></i>
                </a>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
