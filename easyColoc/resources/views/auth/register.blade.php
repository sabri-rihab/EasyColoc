<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc — Inscription</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg: #f8fafc;
            --surface: #ffffff;
            --surface2: #f1f5f9;
            --border: rgba(0,0,0,0.1);
            --border-hover: rgba(0,0,0,0.2);
            --accent: #10b981;
            --accent2: #6366f1;
            --accent3: #db2777;
            --text: #0a0a0f;
            --text-muted: rgba(10,10,15,0.65);
            --text-dim: rgba(10,10,15,0.8);
            --danger: #dc2626;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Mono', monospace;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(110,231,183,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(110,231,183,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .glow-left {
            position: fixed;
            bottom: -200px;
            left: -100px;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(110,231,183,0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .glow-right {
            position: fixed;
            top: -200px;
            right: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(129,140,248,0.08) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            animation: fadeUp 0.5s ease both;
        }

        .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 36px;
            text-align: center;
        }

        .brand-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--accent2), var(--accent));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            box-shadow: 0 8px 32px -8px rgba(129,140,248,0.4);
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .brand-tagline {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.06em;
            margin-top: 4px;
        }

        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px -20px rgba(0,0,0,0.1);
        }

        .auth-card-header {
            padding: 24px 32px 20px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, rgba(244,114,182,0.04), rgba(129,140,248,0.03));
        }

        .auth-card-title {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .auth-card-sub {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.04em;
        }

        .auth-card-body {
            padding: 24px 32px 28px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-group { display: flex; flex-direction: column; gap: 7px; }

        .form-label {
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .form-input {
            width: 100%;
            padding: 11px 16px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: 'DM Mono', monospace;
            font-size: 13px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: rgba(244,114,182,0.5);
            box-shadow: 0 0 0 3px rgba(244,114,182,0.1);
        }
        .form-input::placeholder { color: var(--text-muted); }

        .form-error {
            font-size: 11px;
            color: var(--danger);
        }

        .form-hint {
            font-size: 10px;
            color: var(--text-muted);
            letter-spacing: 0.04em;
            line-height: 1.6;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--accent3), var(--accent2));
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 4px;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 10px 28px -8px rgba(244,114,182,0.4); }

        .auth-footer {
            padding: 16px 32px;
            border-top: 1px solid var(--border);
            background: var(--surface2);
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
        }
        .auth-footer a {
            color: var(--accent2);
            text-decoration: none;
            letter-spacing: 0.03em;
            transition: color 0.15s;
        }
        .auth-footer a:hover { color: var(--accent); }

        /* Perks strip */
        .perks-strip {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .perk {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        .perk-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--accent);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        svg { display: block; }
    </style>
</head>
<body>
    <div class="glow-left"></div>
    <div class="glow-right"></div>

    <div class="auth-wrapper">
        <!-- Brand -->
        <div class="brand">
            <div class="brand-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <div class="brand-name">EasyColoc</div>
            <div class="brand-tagline">Rejoignez la communauté</div>
        </div>

        <!-- Perks -->
        <div class="perks-strip">
            <div class="perk"><div class="perk-dot"></div> Gratuit</div>
            <div class="perk"><div class="perk-dot" style="background:var(--accent2)"></div> Suivi des dépenses</div>
            <div class="perk"><div class="perk-dot" style="background:var(--accent3)"></div> Système de réputation</div>
        </div>

        <!-- Card -->
        <div class="auth-card">
            <div class="auth-card-header">
                <div class="auth-card-title">Créer un compte</div>
                <div class="auth-card-sub">Commencez à gérer votre colocation dès aujourd'hui</div>
            </div>

            <div class="auth-card-body">
                <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:16px;">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group">
                        <label class="form-label" for="name">Nom complet</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            class="form-input"
                            value="{{ old('name') }}"
                            placeholder="Jean Dupont"
                            required
                            autofocus
                            autocomplete="name"
                        >
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label class="form-label" for="email">Adresse email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-input"
                            value="{{ old('email') }}"
                            placeholder="votre@email.com"
                            required
                            autocomplete="username"
                        >
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label class="form-label" for="password">Mot de passe</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-input"
                            placeholder="Minimum 8 caractères"
                            required
                            autocomplete="new-password"
                        >
                        <div class="form-hint">Au moins 8 caractères recommandés.</div>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="form-input"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                        >
                        @error('password_confirmation')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        Créer mon compte
                    </button>
                </form>
            </div>

            <div class="auth-footer">
                Déjà inscrit ?&nbsp;
                <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>
