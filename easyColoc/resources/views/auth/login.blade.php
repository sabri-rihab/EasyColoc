<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc — Connexion</title>

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
            overflow: hidden;
        }

        /* Animated background */
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
            background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, transparent 70%);
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
            max-width: 440px;
            padding: 24px;
            animation: fadeUp 0.5s ease both;
        }

        /* Brand */
        .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 40px;
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
            margin-bottom: 16px;
            box-shadow: 0 8px 32px -8px rgba(129,140,248,0.4);
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .brand-tagline {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.06em;
            margin-top: 4px;
        }

        /* Card */
        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px -20px rgba(0,0,0,0.1);
        }

        .auth-card-header {
            padding: 28px 32px 20px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, rgba(129,140,248,0.04), rgba(110,231,183,0.03));
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
            padding: 28px 32px;
            display: flex;
            flex-direction: column;
            gap: 18px;
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
            border-color: rgba(129,140,248,0.5);
            box-shadow: 0 0 0 3px rgba(129,140,248,0.1);
        }
        .form-input::placeholder { color: var(--text-muted); }

        .form-error {
            font-size: 11px;
            color: var(--danger);
            margin-top: 4px;
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            color: var(--text-dim);
            cursor: pointer;
        }

        .remember-check {
            width: 16px;
            height: 16px;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: var(--surface2);
            accent-color: var(--accent2);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 11px;
            color: var(--accent2);
            text-decoration: none;
            transition: color 0.15s;
            letter-spacing: 0.04em;
        }
        .forgot-link:hover { color: var(--accent); }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--accent2), var(--accent));
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0);
            transition: background 0.2s;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 10px 28px -8px rgba(129,140,248,0.5); }
        .btn-submit:active { transform: translateY(0); }

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

        .alert-error {
            padding: 10px 14px;
            background: rgba(248,113,113,0.08);
            border: 1px solid rgba(248,113,113,0.2);
            border-radius: 10px;
            font-size: 11px;
            color: var(--danger);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            padding: 10px 14px;
            background: rgba(110,231,183,0.08);
            border: 1px solid rgba(110,231,183,0.2);
            border-radius: 10px;
            font-size: 11px;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 8px;
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
            <div class="brand-tagline">Gérez votre colocation simplement</div>
        </div>

        <!-- Card -->
        <div class="auth-card">
            <div class="auth-card-header">
                <div class="auth-card-title">Connexion</div>
                <div class="auth-card-sub">Accédez à votre espace colocation</div>
            </div>

            <div class="auth-card-body">
                {{-- Status / Error --}}
                @if(session('status'))
                    <div class="alert-success">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-error">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:18px;">
                    @csrf

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
                            autofocus
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
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        >
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="form-row">
                        <label class="remember-label">
                            <input type="checkbox" class="remember-check" name="remember">
                            Se souvenir de moi
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit">
                        Se connecter
                    </button>
                </form>
            </div>

            <div class="auth-footer">
                Pas encore de compte ?&nbsp;
                <a href="{{ route('register') }}">Créer un compte</a>
            </div>
        </div>
    </div>
</body>
</html>
