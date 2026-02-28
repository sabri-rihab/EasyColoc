<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc — Dashboard</title>
    <x-app-styles />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 80px 24px;
            animation: fadeUp 0.6s ease both;
        }

        .hero-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(129,140,248,0.2), rgba(110,231,183,0.2));
            border: 1px solid rgba(129,140,248,0.3);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
            font-size: 36px;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .hero-title {
            font-family: 'Syne', sans-serif;
            font-size: 52px;
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1;
            background: linear-gradient(135deg, var(--text) 0%, rgba(240,240,245,0.6) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
        }

        .hero-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            max-width: 480px;
            line-height: 1.8;
            letter-spacing: 0.03em;
            margin-bottom: 40px;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 60px;
            animation: fadeUp 0.6s 0.2s ease both;
        }

        .feature-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px 24px;
            text-align: left;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, var(--card-glow, transparent), transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .feature-card:hover { transform: translateY(-3px); border-color: var(--border-hover); }
        .feature-card:hover::before { opacity: 1; }
        .feature-card.f1 { --card-glow: rgba(129,140,248,0.1); }
        .feature-card.f2 { --card-glow: rgba(110,231,183,0.1); }
        .feature-card.f3 { --card-glow: rgba(244,114,182,0.1); }

        .feature-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 22px;
        }

        .feature-title {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.8;
            letter-spacing: 0.03em;
        }

        .divider-line {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 60px 0 32px;
        }
        .divider-line::before,
        .divider-line::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .divider-text {
            font-size: 10px;
            letter-spacing: 0.15em;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .user-stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            animation: fadeUp 0.6s 0.35s ease both;
        }

        .mini-stat {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .mini-stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mini-stat-val {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
        }

        .mini-stat-label {
            font-size: 10px;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <x-app-nav active="dashboard" />

    <main>
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Hero -->
        <div class="hero-section">
            <div class="hero-icon">🏠</div>
            <div class="page-label">Bienvenue, {{ auth()->user()->name }}</div>
            <h1 class="hero-title">Votre espace colocation</h1>
            <p class="hero-subtitle">
                Vous ne faites actuellement partie d'aucune colocation. Créez-en une nouvelle ou attendez une invitation pour commencer.
            </p>
            <div class="hero-actions">
                <a href="{{ route('colocations.create') }}" class="btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Créer une colocation
                </a>
                <a href="{{ route('profile.edit') }}" class="btn-secondary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Mon Profil
                </a>
            </div>
        </div>

        <!-- User Quick Stats -->
        <div class="user-stats-row">
            <div class="mini-stat">
                <div class="mini-stat-icon" style="background: rgba(129,140,248,0.12);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <div>
                    <div class="mini-stat-val" style="color: var(--accent2);">{{ auth()->user()->reputation ?? 0 }}</div>
                    <div class="mini-stat-label">Réputation</div>
                </div>
            </div>

            <div class="mini-stat">
                <div class="mini-stat-icon" style="background: rgba(110,231,183,0.12);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <div class="mini-stat-val" style="color: var(--accent);">0</div>
                    <div class="mini-stat-label">Colocation active</div>
                </div>
            </div>

            <div class="mini-stat">
                <div class="mini-stat-icon" style="background: rgba(244,114,182,0.12);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f472b6" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                </div>
                <div>
                    <div class="mini-stat-val" style="color: {{ $invitations->count() > 0 ? 'var(--accent3)' : 'var(--text-muted)' }};">{{ $invitations->count() }}</div>
                    <div class="mini-stat-label">Invitation(s) en attente</div>
                </div>
            </div>
        </div>

        <!-- Pending Invitations -->
        @if($invitations->isNotEmpty())
            <div style="margin-top:40px; animation: fadeUp 0.6s 0.3s ease both;">
                <div class="divider-line" style="margin:20px 0;">
                    <span class="divider-text">Invitations en attente</span>
                </div>
                <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:16px;">
                    @foreach($invitations as $invite)
                    <div class="feature-card f2" style="padding: 24px; display:flex; flex-direction:column; gap:16px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="avatar" style="width:40px;height:40px;border-radius:12px;background:var(--accent2);color:var(--bg);font-weight:800;">
                                {{ strtoupper(substr($invite->colocation->name, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-size:14px; font-weight:700; color:var(--text);">{{ $invite->colocation->name }}</div>
                                <div style="font-size:10px; color:var(--text-muted);">Invité par {{ $invite->inviter->name }}</div>
                            </div>
                        </div>
                        <div style="display:flex; gap:10px;">
                            <form method="POST" action="{{ route('invitations.accept', $invite) }}" style="flex:1;">
                                @csrf
                                <button type="submit" class="btn-primary" style="width:100%; justify-content:center; padding:8px 0; font-size:11px;">Accepter</button>
                            </form>
                            <form method="POST" action="{{ route('invitations.reject', $invite) }}" style="flex:1;">
                                @csrf
                                <button type="submit" class="btn-secondary" style="width:100%; justify-content:center; padding:8px 0; font-size:11px; color:var(--danger); border-color:rgba(248,113,113,0.2);">Refuser</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Divider -->
        <div class="divider-line">
            <span class="divider-text">Fonctionnalités disponibles</span>
        </div>

        <!-- Features Grid -->
        <div class="features-grid">
            <div class="feature-card f1">
                <div class="feature-icon" style="background: rgba(129,140,248,0.12);">💰</div>
                <div class="feature-title">Suivi des dépenses</div>
                <p class="feature-desc">Ajoutez et catégorisez vos dépenses partagées. Chaque membre voit automatiquement ce qu'il doit.</p>
            </div>
            <div class="feature-card f2">
                <div class="feature-icon" style="background: rgba(110,231,183,0.12);">⚖️</div>
                <div class="feature-title">Balances automatiques</div>
                <p class="feature-desc">Calcul instantané des soldes et vue synthétique des remboursements entre membres.</p>
            </div>
            <div class="feature-card f3">
                <div class="feature-icon" style="background: rgba(244,114,182,0.12);">⭐</div>
                <div class="feature-title">Système de réputation</div>
                <p class="feature-desc">Gagnez ou perdez des points selon votre comportement financier au sein de la colocation.</p>
            </div>
        </div>
    </main>
</body>
</html>
