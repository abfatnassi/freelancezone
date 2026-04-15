{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')
@section('title','Connexion — FreelanceZone')
@section('content')
<div style="min-height:calc(100vh - 68px);display:flex;align-items:center;justify-content:center;padding:3rem 1.5rem;">
    <div style="width:100%;max-width:440px;">

        <div style="text-align:center;margin-bottom:2.5rem;">
            <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#7C3AED,#06B6D4);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;box-shadow:0 0 30px rgba(124,58,237,0.35);">
                <i class="fas fa-bolt" style="color:white;font-size:1.4rem;"></i>
            </div>
            <h1 style="font-family:'Syne',sans-serif;font-size:1.75rem;font-weight:800;letter-spacing:-0.03em;margin-bottom:0.5rem;">Bon retour !</h1>
            <p style="color:var(--text-muted);font-size:0.9rem;">Connectez-vous à votre compte</p>
        </div>

        <div class="card card-glow">
            @if(session('status'))
                <div class="flash flash-success" style="margin-bottom:1.25rem;">{{ session('status') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST" style="display:flex;flex-direction:column;gap:1.25rem;">
                @csrf
                <div>
                    <label class="label">Adresse email</label>
                    <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="ahmed@exemple.com" autofocus>
                    @error('email')<p style="color:#FB7185;font-size:0.78rem;margin-top:0.35rem;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.4rem;">
                        <label class="label" style="margin:0;">Mot de passe</label>
                        <a href="#" style="color:#A78BFA;font-size:0.78rem;text-decoration:none;">Mot de passe oublié ?</a>
                    </div>
                    <input class="input" type="password" name="password" placeholder="••••••••">
                </div>
                <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;">
                    <input type="checkbox" name="remember" style="accent-color:#7C3AED;width:16px;height:16px;">
                    <span style="font-size:0.875rem;color:var(--text-muted);">Se souvenir de moi</span>
                </label>
                <button type="submit" class="btn btn-primary btn-md" style="width:100%;justify-content:center;padding:0.85rem;font-size:0.95rem;">
                    Se connecter <i class="fas fa-arrow-right" style="font-size:0.8rem;"></i>
                </button>
            </form>

            <div style="text-align:center;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--border-soft);">
                <p style="color:var(--text-muted);font-size:0.875rem;">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" style="color:#A78BFA;font-weight:600;text-decoration:none;"> S'inscrire gratuitement →</a>
                </p>
            </div>
        </div>

        {{-- Demo accounts --}}
        <div style="margin-top:1.5rem;background:rgba(124,58,237,0.06);border:1px solid rgba(124,58,237,0.15);border-radius:14px;padding:1.25rem;">
            <p style="font-size:0.78rem;font-weight:600;color:#A78BFA;margin-bottom:0.75rem;letter-spacing:0.05em;text-transform:uppercase;">
                <i class="fas fa-flask" style="margin-right:0.4rem;"></i> Comptes de démonstration
            </p>
            @foreach([
                ['Admin','admin@freelancezone.ma','fas fa-shield-halved','#F43F5E'],
                ['Client','youssef@client.ma','fas fa-briefcase','#06B6D4'],
                ['Freelance','abdel@freelance.ma','fas fa-laptop-code','#10B981'],
            ] as [$role,$email,$icon,$color])
            <div style="display:flex;align-items:center;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid var(--border-soft);" class="{{ !$loop->last ? '' : '' }}">
                <div style="display:flex;align-items:center;gap:0.6rem;">
                    <i class="{{ $icon }}" style="color:{{ $color }};font-size:0.8rem;width:14px;"></i>
                    <span style="font-size:0.8rem;color:var(--text-muted);">{{ $email }}</span>
                </div>
                <span style="font-size:0.75rem;color:var(--text-dim);">password</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
