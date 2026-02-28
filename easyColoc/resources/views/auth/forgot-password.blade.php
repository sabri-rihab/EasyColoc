<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc — Mot de passe oublié</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg: #f8fafc; --surface: #ffffff; --surface2: #f1f5f9;
            --border: rgba(0,0,0,0.1); --border-hover: rgba(0,0,0,0.2);
            --accent: #10b981; --accent2: #6366f1; --text: #0a0a0f;
            --text-muted: rgba(10,10,15,0.65); --text-dim: rgba(10,10,15,0.8);
            --danger: #dc2626;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--bg); color: var(--text); font-family: 'DM Mono', monospace;
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px;
        }
        body::before {
            content: ''; position: fixed; inset: 0;
            background-image: linear-gradient(rgba(110,231,183,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(110,231,183,0.03) 1px, transparent 1px);
            background-size: 40px 40px; pointer-events: none; z-index: 0;
        }
        .glow { position: fixed; top: -200px; right: -100px; width: 600px; height: 600px; background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%); pointer-events: none; z-index: 0; }
        .auth-wrapper { position: relative; z-index: 1; width: 100%; max-width: 420px; animation: fadeUp 0.5s ease both; }
        .brand { text-align: center; margin-bottom: 36px; }
        .brand-icon { width: 52px; height: 52px; background: linear-gradient(135deg, var(--accent2), var(--accent)); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; box-shadow: 0 8px 28px -8px rgba(99,102,241,0.4); }
        .brand-name { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; letter-spacing: -0.03em; }
        .auth-card { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px -20px rgba(0,0,0,0.1); }
        .auth-card-header { padding: 24px 28px 20px; border-bottom: 1px solid var(--border); background: linear-gradient(135deg, rgba(129,140,248,0.04), rgba(110,231,183,0.03)); }
        .auth-card-title { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 6px; }
        .auth-card-desc { font-size: 11px; color: var(--text-muted); line-height: 1.7; letter-spacing: 0.03em; }
        .auth-card-body { padding: 24px 28px; display: flex; flex-direction: column; gap: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 7px; }
        .form-label { font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text-muted); }
        .form-input { width: 100%; padding: 11px 16px; background: var(--surface2); border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-family: 'DM Mono', monospace; font-size: 13px; transition: border-color 0.2s, box-shadow 0.2s; outline: none; }
        .form-input:focus { border-color: rgba(129,140,248,0.5); box-shadow: 0 0 0 3px rgba(129,140,248,0.1); }
        .form-input::placeholder { color: var(--text-muted); }
        .form-error { font-size: 11px; color: var(--danger); }
        .btn-submit { width: 100%; padding: 13px; background: linear-gradient(135deg, var(--accent2), var(--accent)); border: none; border-radius: 12px; color: #ffffff; font-family: 'Syne', sans-serif; font-size: 13px; font-weight: 800; cursor: pointer; transition: all 0.2s; }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 10px 28px -8px rgba(129,140,248,0.5); }
        .auth-footer { padding: 16px 28px; border-top: 1px solid var(--border); background: var(--surface2); text-align: center; font-size: 12px; color: var(--text-muted); }
        .auth-footer a { color: var(--accent2); text-decoration: none; transition: color 0.15s; }
        .auth-footer a:hover { color: var(--accent); }
        .alert-success { padding: 10px 14px; background: rgba(110,231,183,0.08); border: 1px solid rgba(110,231,183,0.2); border-radius: 10px; font-size: 11px; color: var(--accent); display: flex; align-items: center; gap: 8px; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        svg { display: block; }
    </style>
</head>
<body>
    <div class="glow"></div>
    <div class="auth-wrapper">
        <div class="brand">
            <div class="brand-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="brand-name">EasyColoc</div>
        </div>

        <div class="auth-card">
            <div class="auth-card-header">
                <div class="auth-card-title">Mot de passe oublié ?</div>
                <p class="auth-card-desc">Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
            </div>

            <div class="auth-card-body">
                @if(session('status'))
                    <div class="alert-success">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" style="display:flex;flex-direction:column;gap:16px;">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="email">Adresse email</label>
                        <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="votre@email.com" required autofocus>
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn-submit">Envoyer le lien de réinitialisation</button>
                </form>
            </div>

            <div class="auth-footer">
                <a href="{{ route('login') }}">← Retour à la connexion</a>
            </div>
        </div>
    </div>
</body>
</html>
