@extends('layouts.app')
@section('title', 'FreelanceZone — Trouvez les meilleurs talents')
@section('content')

{{-- Hero --}}
<section style="min-height:92vh;display:flex;align-items:center;justify-content:center;padding:5rem 2rem;position:relative;overflow:hidden;">

    {{-- Background grid --}}
    <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(124,58,237,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(124,58,237,0.04) 1px,transparent 1px);background-size:60px 60px;pointer-events:none;"></div>

    {{-- Floating orbs --}}
    <div style="position:absolute;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(124,58,237,0.15),transparent 70%);top:10%;left:5%;animation:float 6s ease-in-out infinite;pointer-events:none;"></div>
    <div style="position:absolute;width:200px;height:200px;border-radius:50%;background:radial-gradient(circle,rgba(6,182,212,0.12),transparent 70%);bottom:20%;right:10%;animation:float 8s ease-in-out infinite 2s;pointer-events:none;"></div>

    <div style="max-width:860px;margin:0 auto;text-align:center;position:relative;z-index:1;" class="animate-fade-up">

        {{-- Pill badge --}}
        <div style="display:inline-flex;align-items:center;gap:0.6rem;background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.25);border-radius:50px;padding:0.4rem 1.1rem;margin-bottom:2rem;font-size:0.8rem;font-weight:600;color:#A78BFA;">
            <span style="width:6px;height:6px;border-radius:50%;background:#A78BFA;display:inline-block;animation:pulse-glow 2s infinite;"></span>
            ✦ La plateforme #1 des freelances au Maroc
        </div>

        <h1 style="font-family:'Syne',sans-serif;font-size:clamp(2.5rem,6vw,5rem);font-weight:800;line-height:1.05;letter-spacing:-0.04em;margin-bottom:1.5rem;">
            Trouvez le talent<br>
            <span class="text-gradient">parfait pour votre projet</span>
        </h1>

        <p style="font-size:1.2rem;color:var(--text-muted);max-width:580px;margin:0 auto 3rem;line-height:1.7;">
            Des milliers de freelances qualifiés, prêts à transformer vos idées en réalité. Rapide, sécurisé, et sans commission excessive.
        </p>

        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('projects.index') }}" class="btn btn-glow btn-xl">
                Explorer les projets <i class="fas fa-arrow-right" style="font-size:0.9rem;"></i>
            </a>
            @guest
            <a href="{{ route('register') }}" class="btn btn-ghost btn-xl">
                Créer un compte gratuit
            </a>
            @endguest
        </div>

        {{-- Social proof --}}
        <div style="display:flex;align-items:center;justify-content:center;gap:1.5rem;margin-top:3rem;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="display:flex;">
                    @for($i=0;$i<5;$i++)
                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,hue-rotate({{ $i*40 }}deg) #7C3AED,#06B6D4);border:2px solid var(--bg);margin-left:{{ $i>0 ? '-8px' : '0' }};display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;color:white;">
                        {{ ['AB','FZ','KM','NB','OT'][$i] }}
                    </div>
                    @endfor
                </div>
                <div style="text-align:left;">
                    <div style="font-weight:700;font-size:0.9rem;">10 000+ freelances</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">inscrits et actifs</div>
                </div>
            </div>
            <div style="width:1px;height:36px;background:var(--border-soft);"></div>
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <div style="display:flex;gap:2px;">
                    @for($i=0;$i<5;$i++)
                        <i class="fas fa-star" style="color:#F59E0B;font-size:0.85rem;"></i>
                    @endfor
                </div>
                <span style="font-size:0.85rem;"><strong>4.9/5</strong> <span style="color:var(--text-muted);">satisfaction client</span></span>
            </div>
        </div>
    </div>
</section>

{{-- Stats bar --}}
<section style="padding:2rem;border-top:1px solid var(--border-soft);border-bottom:1px solid var(--border-soft);background:var(--surface);">
    <div style="max-width:1000px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:2rem;text-align:center;">
        @foreach([
            ['10K+','Freelances','fas fa-users'],
            ['5K+','Projets','fas fa-folder-open'],
            ['98%','Satisfaction','fas fa-star'],
            ['72h','Délai moyen','fas fa-bolt'],
        ] as [$num,$label,$icon])
        <div>
            <div style="font-family:'Syne',sans-serif;font-size:2.2rem;font-weight:800;" class="text-gradient">{{ $num }}</div>
            <div style="color:var(--text-muted);font-size:0.85rem;margin-top:0.25rem;display:flex;align-items:center;justify-content:center;gap:0.4rem;">
                <i class="{{ $icon }}" style="font-size:0.75rem;color:#7C3AED;"></i> {{ $label }}
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- Categories --}}
<section style="padding:6rem 2rem;max-width:1280px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:3.5rem;">
        <div class="badge badge-violet" style="margin-bottom:1rem;">Catégories</div>
        <h2 class="section-title">Explorer par domaine</h2>
        <p style="color:var(--text-muted);margin-top:0.75rem;font-size:1rem;">Trouvez l'expertise exacte dont vous avez besoin</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;">
        @foreach([
            ['Développement Web','fas fa-code','#7C3AED','rgba(124,58,237,0.1)'],
            ['Design & Créatif','fas fa-palette','#EC4899','rgba(236,72,153,0.1)'],
            ['Marketing Digital','fas fa-bullhorn','#F59E0B','rgba(245,158,11,0.1)'],
            ['Rédaction','fas fa-pen-nib','#10B981','rgba(16,185,129,0.1)'],
            ['Vidéo & Motion','fas fa-film','#8B5CF6','rgba(139,92,246,0.1)'],
            ['Mobile','fas fa-mobile-screen','#06B6D4','rgba(6,182,212,0.1)'],
            ['Data & IA','fas fa-brain','#F43F5E','rgba(244,63,94,0.1)'],
            ['Cybersécurité','fas fa-shield-halved','#0EA5E9','rgba(14,165,233,0.1)'],
            ['Traduction','fas fa-language','#A78BFA','rgba(167,139,250,0.1)'],
            ['Finance','fas fa-chart-line','#34D399','rgba(52,211,153,0.1)'],
        ] as [$name,$icon,$color,$bg])
        <a href="{{ route('projects.index') }}" style="background:var(--surface);border:1px solid var(--border-soft);border-radius:16px;padding:1.5rem 1rem;text-align:center;text-decoration:none;transition:all 0.3s;display:block;" onmouseover="this.style.borderColor='{{ $color }}40';this.style.background='{{ $bg }}';this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='';this.style.background='var(--surface)';this.style.transform=''">
            <div style="width:48px;height:48px;border-radius:12px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;margin:0 auto 0.85rem;">
                <i class="{{ $icon }}" style="color:{{ $color }};font-size:1.2rem;"></i>
            </div>
            <div style="font-size:0.82rem;font-weight:600;color:var(--text);">{{ $name }}</div>
        </a>
        @endforeach
    </div>
</section>

{{-- How it works --}}
<section style="padding:6rem 2rem;background:var(--surface);border-top:1px solid var(--border-soft);border-bottom:1px solid var(--border-soft);">
    <div style="max-width:1100px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:4rem;">
            <div class="badge badge-cyan" style="margin-bottom:1rem;">Processus</div>
            <h2 class="section-title">Comment ça fonctionne ?</h2>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;position:relative;">
            {{-- Connector line --}}
            <div style="position:absolute;top:3rem;left:25%;right:25%;height:1px;background:linear-gradient(90deg,transparent,rgba(124,58,237,0.4),transparent);"></div>

            @foreach([
                ['01','Publiez votre projet','Décrivez votre besoin en quelques minutes. Définissez votre budget et recevez des offres rapidement.','fas fa-paper-plane','#7C3AED','rgba(124,58,237,0.12)'],
                ['02','Choisissez votre freelance','Comparez les profils, les évaluations et les offres. Échangez directement avant de vous décider.','fas fa-user-check','#06B6D4','rgba(6,182,212,0.12)'],
                ['03','Recevez votre livrable','Collaborez en toute sécurité. Le paiement n\'est libéré qu\'après votre validation.','fas fa-check-circle','#10B981','rgba(16,185,129,0.12)'],
            ] as [$step,$title,$desc,$icon,$color,$bg])
            <div class="card card-hover" style="text-align:center;padding:2.5rem 2rem;">
                <div style="width:60px;height:60px;border-radius:16px;background:{{ $bg }};border:1px solid {{ $color }}30;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                    <i class="{{ $icon }}" style="color:{{ $color }};font-size:1.4rem;"></i>
                </div>
                <div style="font-size:0.7rem;font-weight:800;color:{{ $color }};letter-spacing:0.1em;margin-bottom:0.75rem;">ÉTAPE {{ $step }}</div>
                <h3 style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:0.75rem;">{{ $title }}</h3>
                <p style="color:var(--text-muted);font-size:0.875rem;line-height:1.7;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section style="padding:8rem 2rem;text-align:center;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;background:radial-gradient(ellipse at center,rgba(124,58,237,0.12) 0%,transparent 70%);pointer-events:none;"></div>
    <div style="position:relative;z-index:1;max-width:700px;margin:0 auto;">
        <h2 style="font-family:'Syne',sans-serif;font-size:clamp(2rem,5vw,3.5rem);font-weight:800;letter-spacing:-0.04em;margin-bottom:1.5rem;line-height:1.1;">
            Prêt à démarrer<br><span class="text-gradient">votre prochain projet ?</span>
        </h2>
        <p style="color:var(--text-muted);font-size:1.1rem;margin-bottom:3rem;line-height:1.7;">
            Rejoignez des milliers de clients et freelances qui font confiance à FreelanceZone.
        </p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('register') }}" class="btn btn-glow btn-xl">
                Créer mon compte <i class="fas fa-rocket" style="font-size:0.9rem;"></i>
            </a>
            <a href="{{ route('projects.index') }}" class="btn btn-ghost btn-xl">
                Voir les projets
            </a>
        </div>
    </div>
</section>

@endsection
