<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FreelanceZone') — Trouvez les meilleurs talents</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --violet: #7C3AED;
            --violet-light: #8B5CF6;
            --violet-dark: #5B21B6;
            --rose: #F43F5E;
            --cyan: #06B6D4;
            --amber: #F59E0B;
            --emerald: #10B981;
            --bg: #0A0A0F;
            --surface: #111118;
            --surface2: #1A1A24;
            --surface3: #22222F;
            --border: rgba(124,58,237,0.15);
            --border-soft: rgba(255,255,255,0.06);
            --text: #F8F8FF;
            --text-muted: #8B8BA0;
            --text-dim: #4A4A5E;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1,h2,h3,h4,h5 { font-family: 'Syne', sans-serif; }

        /* ── Grain overlay ── */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        /* ── Ambient glow ── */
        .ambient {
            position: fixed; border-radius: 50%; filter: blur(120px);
            pointer-events: none; z-index: 0;
        }
        .ambient-1 { width: 600px; height: 600px; background: rgba(124,58,237,0.12); top: -200px; right: -100px; }
        .ambient-2 { width: 400px; height: 400px; background: rgba(6,182,212,0.07); bottom: 100px; left: -100px; }

        /* ── Navbar ── */
        .navbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(10,10,15,0.8);
            backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--border-soft);
            padding: 0 2rem;
        }
        .navbar-inner {
            max-width: 1280px; margin: 0 auto;
            height: 68px; display: flex; align-items: center; justify-content: space-between; gap: 2rem;
        }
        .nav-logo {
            font-family: 'Syne', sans-serif;
            font-size: 1.35rem; font-weight: 800;
            background: linear-gradient(135deg, #A78BFA, #7C3AED, #06B6D4);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-decoration: none; letter-spacing: -0.02em;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .nav-logo-icon {
            width: 34px; height: 34px; border-radius: 10px;
            background: linear-gradient(135deg, #7C3AED, #06B6D4);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; -webkit-text-fill-color: white;
        }
        .nav-links { display: flex; align-items: center; gap: 0.25rem; }
        .nav-link {
            color: var(--text-muted); text-decoration: none;
            font-size: 0.875rem; font-weight: 500;
            padding: 0.5rem 0.9rem; border-radius: 8px;
            transition: all 0.2s;
        }
        .nav-link:hover { color: var(--text); background: var(--surface2); }
        .nav-actions { display: flex; align-items: center; gap: 0.75rem; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            font-family: 'DM Sans', sans-serif; font-weight: 600;
            border-radius: 10px; cursor: pointer;
            text-decoration: none; transition: all 0.2s;
            border: none; white-space: nowrap;
        }
        .btn-sm { font-size: 0.8rem; padding: 0.45rem 1rem; }
        .btn-md { font-size: 0.875rem; padding: 0.6rem 1.25rem; }
        .btn-lg { font-size: 1rem; padding: 0.85rem 2rem; }
        .btn-xl { font-size: 1.05rem; padding: 1rem 2.5rem; border-radius: 14px; }

        .btn-primary {
            background: linear-gradient(135deg, #7C3AED, #5B21B6);
            color: white;
            box-shadow: 0 0 0 1px rgba(124,58,237,0.3), 0 4px 20px rgba(124,58,237,0.25);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #8B5CF6, #6D28D9);
            box-shadow: 0 0 0 1px rgba(124,58,237,0.5), 0 8px 30px rgba(124,58,237,0.4);
            transform: translateY(-1px);
        }
        .btn-ghost {
            background: transparent; color: var(--text-muted);
            border: 1px solid var(--border-soft);
        }
        .btn-ghost:hover { color: var(--text); background: var(--surface2); border-color: rgba(255,255,255,0.12); }

        .btn-outline {
            background: transparent; color: #A78BFA;
            border: 1px solid rgba(124,58,237,0.4);
        }
        .btn-outline:hover { background: rgba(124,58,237,0.1); border-color: rgba(124,58,237,0.7); }

        .btn-glow {
            background: linear-gradient(135deg, #7C3AED, #06B6D4);
            color: white;
            box-shadow: 0 0 40px rgba(124,58,237,0.4);
        }
        .btn-glow:hover {
            box-shadow: 0 0 60px rgba(124,58,237,0.6);
            transform: translateY(-2px);
        }
        .btn-danger { background: linear-gradient(135deg,#F43F5E,#be123c); color:white; }
        .btn-success { background: linear-gradient(135deg,#10B981,#059669); color:white; }

        /* ── Card ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: 18px;
            padding: 1.75rem;
            position: relative; overflow: hidden;
        }
        .card-glow::before {
            content: ''; position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle at top right, rgba(124,58,237,0.05) 0%, transparent 60%);
            pointer-events: none;
        }
        .card-hover { transition: all 0.3s; }
        .card-hover:hover {
            border-color: rgba(124,58,237,0.3);
            box-shadow: 0 8px 40px rgba(124,58,237,0.12);
            transform: translateY(-2px);
        }

        /* ── Glass ── */
        .glass {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            backdrop-filter: blur(12px);
            border-radius: 16px;
        }

        /* ── Badge ── */
        .badge {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.72rem; font-weight: 600;
            padding: 0.25rem 0.65rem; border-radius: 20px;
            letter-spacing: 0.02em;
        }
        .badge-violet { background: rgba(124,58,237,0.15); color: #A78BFA; border: 1px solid rgba(124,58,237,0.25); }
        .badge-emerald { background: rgba(16,185,129,0.12); color: #34D399; border: 1px solid rgba(16,185,129,0.2); }
        .badge-rose { background: rgba(244,63,94,0.12); color: #FB7185; border: 1px solid rgba(244,63,94,0.2); }
        .badge-cyan { background: rgba(6,182,212,0.12); color: #22D3EE; border: 1px solid rgba(6,182,212,0.2); }
        .badge-amber { background: rgba(245,158,11,0.12); color: #FCD34D; border: 1px solid rgba(245,158,11,0.2); }
        .badge-gray { background: rgba(255,255,255,0.05); color: var(--text-muted); border: 1px solid var(--border-soft); }

        /* ── Input ── */
        .input {
            width: 100%; background: var(--surface2);
            border: 1px solid var(--border-soft);
            border-radius: 10px; padding: 0.75rem 1rem;
            color: var(--text); font-family: 'DM Sans', sans-serif; font-size: 0.9rem;
            outline: none; transition: all 0.2s;
        }
        .input::placeholder { color: var(--text-dim); }
        .input:focus { border-color: rgba(124,58,237,0.5); box-shadow: 0 0 0 3px rgba(124,58,237,0.1); }
        .input:hover:not(:focus) { border-color: rgba(255,255,255,0.1); }

        select.input { cursor: pointer; }
        textarea.input { resize: vertical; min-height: 120px; }

        .label {
            display: block; font-size: 0.8rem; font-weight: 600;
            color: var(--text-muted); margin-bottom: 0.4rem;
            letter-spacing: 0.04em; text-transform: uppercase;
        }

        /* ── Avatar ── */
        .avatar {
            border-radius: 50%; object-fit: cover;
            border: 2px solid rgba(124,58,237,0.3);
        }
        .avatar-sm { width: 36px; height: 36px; }
        .avatar-md { width: 48px; height: 48px; }
        .avatar-lg { width: 72px; height: 72px; }
        .avatar-xl { width: 96px; height: 96px; }

        /* ── Stat card ── */
        .stat-card {
            background: var(--surface2);
            border: 1px solid var(--border-soft);
            border-radius: 14px; padding: 1.25rem 1.5rem;
            display: flex; align-items: center; gap: 1rem;
        }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; flex-shrink: 0;
        }
        .stat-value { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 0.78rem; color: var(--text-muted); margin-top: 0.2rem; font-weight: 500; }

        /* ── Flash messages ── */
        .flash {
            border-radius: 12px; padding: 0.85rem 1.25rem;
            font-size: 0.875rem; font-weight: 500;
            display: flex; align-items: center; gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .flash-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.25); color: #34D399; }
        .flash-error   { background: rgba(244,63,94,0.1);  border: 1px solid rgba(244,63,94,0.25);  color: #FB7185; }
        .flash-info    { background: rgba(6,182,212,0.1);  border: 1px solid rgba(6,182,212,0.25);  color: #22D3EE; }
        .flash-warning { background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25); color: #FCD34D; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; border-radius: 14px; border: 1px solid var(--border-soft); }
        table { width: 100%; border-collapse: collapse; }
        thead tr { border-bottom: 1px solid var(--border-soft); }
        thead th {
            padding: 0.85rem 1.25rem; text-align: left;
            font-size: 0.75rem; font-weight: 600; color: var(--text-dim);
            letter-spacing: 0.06em; text-transform: uppercase;
        }
        tbody tr { border-bottom: 1px solid var(--border-soft); transition: background 0.15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,0.02); }
        tbody td { padding: 1rem 1.25rem; font-size: 0.875rem; color: var(--text-muted); }
        tbody td:first-child { color: var(--text); font-weight: 500; }

        /* ── Divider ── */
        .divider { height: 1px; background: var(--border-soft); }

        /* ── Sidebar ── */
        .filter-sidebar {
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: 18px; padding: 1.5rem;
            position: sticky; top: 88px;
        }
        .filter-title {
            font-family: 'Syne', sans-serif; font-size: 0.95rem;
            font-weight: 700; margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 0.5rem;
        }

        /* ── Footer ── */
        .footer {
            background: var(--surface);
            border-top: 1px solid var(--border-soft);
            margin-top: 6rem;
        }
        .footer-inner {
            max-width: 1280px; margin: 0 auto;
            padding: 4rem 2rem 2rem;
        }

        /* ── Pagination ── */
        .pagination { display: flex; gap: 0.4rem; flex-wrap: wrap; }
        .pagination a, .pagination span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 36px; height: 36px; padding: 0 0.6rem;
            border-radius: 8px; font-size: 0.85rem; font-weight: 500;
            text-decoration: none; transition: all 0.2s;
        }
        .pagination a { color: var(--text-muted); border: 1px solid var(--border-soft); }
        .pagination a:hover { color: var(--text); border-color: rgba(124,58,237,0.4); background: rgba(124,58,237,0.08); }
        .pagination .active span {
            background: linear-gradient(135deg,#7C3AED,#5B21B6); color: white;
            border: none; box-shadow: 0 4px 12px rgba(124,58,237,0.3);
        }

        /* ── Animations ── */
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        @keyframes pulse-glow { 0%,100% { box-shadow: 0 0 20px rgba(124,58,237,0.3); } 50% { box-shadow: 0 0 40px rgba(124,58,237,0.6); } }
        @keyframes float { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-8px); } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }

        .animate-fade-up { animation: fadeUp 0.5s ease forwards; }
        .animate-float { animation: float 4s ease-in-out infinite; }

        /* ── Gradient text ── */
        .text-gradient {
            background: linear-gradient(135deg, #A78BFA 0%, #7C3AED 40%, #06B6D4 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .text-gradient-rose {
            background: linear-gradient(135deg, #F43F5E, #FB923C);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        /* ── Section title ── */
        .section-title { font-family:'Syne',sans-serif; font-size:1.75rem; font-weight:800; letter-spacing:-0.03em; }
        .page-title { font-family:'Syne',sans-serif; font-size:2.5rem; font-weight:800; letter-spacing:-0.04em; }

        /* ── User chip ── */
        .user-chip {
            display: flex; align-items: center; gap: 0.6rem;
            background: var(--surface2); border: 1px solid var(--border-soft);
            border-radius: 50px; padding: 0.3rem 0.9rem 0.3rem 0.3rem;
            text-decoration: none; transition: all 0.2s; cursor: pointer;
        }
        .user-chip:hover { border-color: rgba(124,58,237,0.4); background: var(--surface3); }
        .user-chip span { font-size: 0.85rem; font-weight: 500; color: var(--text); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .navbar { padding: 0 1rem; }
            .nav-links { display: none; }
            .page-title { font-size: 1.8rem; }
        }
    </style>
</head>
<body>
    <div class="ambient ambient-1"></div>
    <div class="ambient ambient-2"></div>

    {{-- Navbar --}}
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="nav-logo">
                <div class="nav-logo-icon">
                    <i class="fas fa-bolt" style="-webkit-text-fill-color:white;font-size:0.85rem;"></i>
                </div>
                FreelanceZone
            </a>

            <div class="nav-links">
                <a href="{{ route('projects.index') }}" class="nav-link">
                    <i class="fas fa-compass" style="font-size:0.8rem;margin-right:0.3rem;"></i> Explorer
                </a>
                @auth
                    <a href="{{ route('messages.inbox') }}" class="nav-link">Messages</a>
                    <a href="{{ route('contracts.index') }}" class="nav-link">Contrats</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link" style="color:#A78BFA;">Admin</a>
                    @endif
                @endauth
            </div>

            <div class="nav-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="user-chip">
                        <img src="{{ Auth::user()->avatar_url }}" class="avatar avatar-sm" style="border-radius:50%;width:28px;height:28px;">
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                    @if(Auth::user()->isClient())
                        <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus" style="font-size:0.7rem;"></i> Nouveau projet
                        </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-ghost btn-sm" type="submit">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                        Commencer <i class="fas fa-arrow-right" style="font-size:0.7rem;"></i>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @foreach(['success'=>['flash-success','fa-check-circle'],'error'=>['flash-error','fa-circle-xmark'],'info'=>['flash-info','fa-circle-info'],'warning'=>['flash-warning','fa-triangle-exclamation']] as $type=>[$cls,$icon])
        @if(session($type))
            <div style="max-width:1280px;margin:1rem auto;padding:0 2rem;">
                <div class="flash {{ $cls }}">
                    <i class="fas {{ $icon }}"></i> {{ session($type) }}
                </div>
            </div>
        @endif
    @endforeach

    {{-- Main --}}
    <main style="position:relative;z-index:1;">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer" style="position:relative;z-index:1;">
        <div class="footer-inner">
            <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;margin-bottom:3rem;">
                <div>
                    <a href="{{ route('home') }}" class="nav-logo" style="display:inline-flex;margin-bottom:1rem;">
                        <div class="nav-logo-icon"><i class="fas fa-bolt" style="-webkit-text-fill-color:white;font-size:0.85rem;"></i></div>
                        FreelanceZone
                    </a>
                    <p style="color:var(--text-muted);font-size:0.875rem;line-height:1.7;max-width:280px;">
                        La plateforme qui connecte les clients avec les meilleurs freelances du Maroc et du monde arabe.
                    </p>
                    <div style="display:flex;gap:0.75rem;margin-top:1.25rem;">
                        @foreach(['twitter'=>'fab fa-twitter','linkedin'=>'fab fa-linkedin','github'=>'fab fa-github'] as $name=>$icon)
                            <a href="#" style="width:36px;height:36px;border-radius:8px;background:var(--surface2);border:1px solid var(--border-soft);display:flex;align-items:center;justify-content:center;color:var(--text-muted);text-decoration:none;transition:all 0.2s;" onmouseover="this.style.color='#A78BFA';this.style.borderColor='rgba(124,58,237,0.4)'" onmouseout="this.style.color='';this.style.borderColor=''">
                                <i class="{{ $icon }}" style="font-size:0.85rem;"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
                @foreach([
                    'Clients' => [['Publier un projet',route('projects.create')],['Trouver un freelance',route('projects.index')]],
                    'Freelances' => [['Explorer les projets',route('projects.index')],["S'inscrire",route('register')]],
                    'Légal' => [['Confidentialité','#'],['Conditions d\'utilisation','#']],
                ] as $title => $links)
                <div>
                    <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.85rem;color:var(--text);margin-bottom:1rem;letter-spacing:0.02em;">{{ $title }}</div>
                    @foreach($links as [$label,$url])
                        <a href="{{ $url }}" style="display:block;color:var(--text-muted);text-decoration:none;font-size:0.85rem;margin-bottom:0.6rem;transition:color 0.2s;" onmouseover="this.style.color='#A78BFA'" onmouseout="this.style.color=''">{{ $label }}</a>
                    @endforeach
                </div>
                @endforeach
            </div>
            <div class="divider" style="margin-bottom:1.5rem;"></div>
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
                <p style="color:var(--text-dim);font-size:0.8rem;">© {{ date('Y') }} FreelanceZone. Tous droits réservés.</p>
                <div class="badge badge-violet" style="font-size:0.72rem;">
                    <i class="fas fa-shield-halved" style="font-size:0.65rem;"></i> Paiements sécurisés
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
