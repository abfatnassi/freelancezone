{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')
@section('title','Inscription — FreelanceZone')
@section('content')
<div style="min-height:calc(100vh - 68px);display:flex;align-items:center;justify-content:center;padding:3rem 1.5rem;">
    <div style="width:100%;max-width:480px;">

        {{-- Header --}}
        <div style="text-align:center;margin-bottom:2.5rem;">
            <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#7C3AED,#06B6D4);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;box-shadow:0 0 30px rgba(124,58,237,0.35);">
                <i class="fas fa-bolt" style="color:white;font-size:1.4rem;"></i>
            </div>
            <h1 style="font-family:'Syne',sans-serif;font-size:1.75rem;font-weight:800;letter-spacing:-0.03em;margin-bottom:0.5rem;">Créer un compte</h1>
            <p style="color:var(--text-muted);font-size:0.9rem;">Rejoignez FreelanceZone gratuitement</p>
        </div>

        <div class="card card-glow">
            <form action="{{ route('register') }}" method="POST" style="display:flex;flex-direction:column;gap:1.25rem;">
                @csrf

                <div>
                    <label class="label">Nom complet</label>
                    <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="Ahmed Benali" autocomplete="name">
                    @error('name')<p style="color:#FB7185;font-size:0.78rem;margin-top:0.35rem;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Adresse email</label>
                    <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="ahmed@exemple.com">
                    @error('email')<p style="color:#FB7185;font-size:0.78rem;margin-top:0.35rem;">{{ $message }}</p>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label class="label">Mot de passe</label>
                        <input class="input" type="password" name="password" placeholder="••••••••">
                        @error('password')<p style="color:#FB7185;font-size:0.78rem;margin-top:0.35rem;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Confirmer</label>
                        <input class="input" type="password" name="password_confirmation" placeholder="••••••••">
                    </div>
                </div>

                {{-- Role selector --}}
                <div>
                    <label class="label">Je suis un</label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                        <label style="position:relative;cursor:pointer;">
                            <input type="radio" name="role" value="client" {{ old('role','client')==='client'?'checked':'' }} style="position:absolute;opacity:0;">
                            <div class="role-card" data-role="client" style="border:1px solid rgba(124,58,237,0.4);border-radius:12px;padding:1rem;text-align:center;background:rgba(124,58,237,0.08);transition:all 0.2s;">
                                <i class="fas fa-briefcase" style="color:#A78BFA;font-size:1.3rem;display:block;margin-bottom:0.5rem;"></i>
                                <div style="font-weight:700;font-size:0.9rem;margin-bottom:0.2rem;">Client</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">Je cherche des freelances</div>
                            </div>
                        </label>
                        <label style="position:relative;cursor:pointer;">
                            <input type="radio" name="role" value="freelancer" {{ old('role')==='freelancer'?'checked':'' }} style="position:absolute;opacity:0;">
                            <div class="role-card" data-role="freelancer" style="border:1px solid var(--border-soft);border-radius:12px;padding:1rem;text-align:center;background:var(--surface2);transition:all 0.2s;">
                                <i class="fas fa-laptop-code" style="color:var(--text-muted);font-size:1.3rem;display:block;margin-bottom:0.5rem;"></i>
                                <div style="font-weight:700;font-size:0.9rem;margin-bottom:0.2rem;">Freelance</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">Je propose mes services</div>
                            </div>
                        </label>
                    </div>
                    @error('role')<p style="color:#FB7185;font-size:0.78rem;margin-top:0.35rem;">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn btn-primary btn-md" style="width:100%;justify-content:center;padding:0.85rem;font-size:0.95rem;margin-top:0.5rem;">
                    Créer mon compte <i class="fas fa-arrow-right" style="font-size:0.8rem;"></i>
                </button>
            </form>

            <div style="text-align:center;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--border-soft);">
                <p style="color:var(--text-muted);font-size:0.875rem;">
                    Déjà inscrit ?
                    <a href="{{ route('login') }}" style="color:#A78BFA;font-weight:600;text-decoration:none;"> Se connecter →</a>
                </p>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.querySelectorAll('input[name="role"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.role-card').forEach(card => {
            card.style.borderColor = 'var(--border-soft)';
            card.style.background = 'var(--surface2)';
            card.querySelector('i').style.color = 'var(--text-muted)';
        });
        const active = document.querySelector(`.role-card[data-role="${radio.value}"]`);
        active.style.borderColor = 'rgba(124,58,237,0.5)';
        active.style.background = 'rgba(124,58,237,0.1)';
        active.querySelector('i').style.color = '#A78BFA';
    });
});
</script>
@endpush
@endsection
