<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc - Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --bg: #0a0a0f;
            --surface: #111118;
            --surface2: #16161f;
            --border: rgba(255,255,255,0.07);
            --border-hover: rgba(255,255,255,0.15);
            --accent: #6ee7b7;
            --accent2: #818cf8;
            --accent3: #f472b6;
            --accent4: #fb923c;
            --text: #f0f0f5;
            --text-muted: rgba(240,240,245,0.4);
            --text-dim: rgba(240,240,245,0.65);
            --danger: #f87171;
        }

        /* Reset any Breeze styles that might interfere */
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }

        body {
            background: var(--bg) !important;
            color: var(--text) !important;
            font-family: 'DM Mono', monospace !important;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Background grid */
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

        /* Ambient glow */
        body::after {
            content: '';
            position: fixed;
            top: -200px;
            right: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(129,140,248,0.08) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* NAV */
        nav {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            height: 64px;
            background: rgba(10,10,15,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 16px;
            letter-spacing: -0.02em;
            text-decoration: none;
            color: var(--text);
        }

        .nav-logo:hover {
            color: var(--accent);
        }

        .nav-logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--accent2), var(--accent));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
        }

        .nav-user {
            font-size: 12px;
            color: var(--text-dim);
            letter-spacing: 0.05em;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background 0.15s;
        }

        .nav-user:hover {
            background: rgba(255,255,255,0.05);
        }

        .status-pill {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            background: rgba(110,231,183,0.08);
            border: 1px solid rgba(110,231,183,0.2);
            border-radius: 20px;
            font-size: 10px;
            font-weight: 500;
            color: var(--accent);
            letter-spacing: 0.08em;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            width: 200px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s;
            z-index: 1000;
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--text-dim);
            text-decoration: none;
            font-size: 12px;
            transition: all 0.15s;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'DM Mono', monospace;
        }

        .dropdown-item:hover {
            background: rgba(255,255,255,0.05);
            color: var(--text);
        }

        .dropdown-item svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 8px 0;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(0.8); }
        }

        /* MAIN */
        main {
            position: relative;
            z-index: 1;
            max-width: 1280px;
            margin: 0 auto;
            padding: 48px 40px 80px;
        }

        /* Page header */
        .page-header {
            margin-bottom: 48px;
            animation: fadeUp 0.5s ease both;
        }

        .page-label {
            font-size: 11px;
            letter-spacing: 0.15em;
            color: var(--accent);
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 48px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            background: linear-gradient(135deg, var(--text) 0%, rgba(240,240,245,0.5) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-sub {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 8px;
            letter-spacing: 0.05em;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            cursor: default;
            transition: border-color 0.2s, transform 0.2s;
            animation: fadeUp 0.5s ease both;
        }

        .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.15s; }
        .stat-card:nth-child(4) { animation-delay: 0.2s; }

        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, var(--card-glow, transparent) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .stat-card:hover { 
            border-color: var(--border-hover); 
            transform: translateY(-2px); 
        }
        
        .stat-card:hover::before { 
            opacity: 1; 
        }

        .stat-card.blue { --card-glow: rgba(129,140,248,0.12); }
        .stat-card.green { --card-glow: rgba(110,231,183,0.12); }
        .stat-card.purple { --card-glow: rgba(244,114,182,0.12); }
        .stat-card.red { --card-glow: rgba(248,113,113,0.12); }

        .stat-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .stat-icon.blue { background: rgba(129,140,248,0.12); }
        .stat-icon.green { background: rgba(110,231,183,0.12); }
        .stat-icon.purple { background: rgba(244,114,182,0.12); }
        .stat-icon.red { background: rgba(248,113,113,0.12); }

        .stat-badge {
            font-size: 9px;
            letter-spacing: 0.12em;
            color: var(--text-muted);
            text-transform: uppercase;
            padding: 3px 8px;
            border: 1px solid var(--border);
            border-radius: 20px;
        }

        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 40px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-card.blue .stat-value { color: var(--accent2); }
        .stat-card.green .stat-value { color: var(--accent); }
        .stat-card.purple .stat-value { color: var(--accent3); }
        .stat-card.red .stat-value { color: var(--danger); }

        .stat-label {
            font-size: 10px;
            letter-spacing: 0.12em;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        /* Table section */
        .table-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            animation: fadeUp 0.5s 0.3s ease both;
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 28px;
            border-bottom: 1px solid var(--border);
        }

        .table-title {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .table-meta {
            font-size: 10px;
            color: var(--text-muted);
            letter-spacing: 0.05em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            padding: 12px 28px;
            text-align: left;
            font-size: 9px;
            letter-spacing: 0.15em;
            color: var(--text-muted);
            text-transform: uppercase;
            background: var(--surface2);
            font-weight: 500;
            border-bottom: 1px solid var(--border);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,0.02); }

        td {
            padding: 16px 28px;
            font-size: 12px;
            vertical-align: middle;
        }

        .id-cell {
            color: var(--text-muted);
            font-size: 11px;
            letter-spacing: 0.05em;
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent2), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 12px;
            font-weight: 700;
            color: var(--bg);
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
        }

        .email-cell {
            color: var(--text-dim);
            font-size: 11px;
        }

        .rep-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rep-val {
            font-size: 13px;
            color: var(--text);
            min-width: 20px;
        }

        .rep-bar-bg {
            width: 60px;
            height: 3px;
            background: rgba(255,255,255,0.08);
            border-radius: 4px;
            overflow: hidden;
        }

        .rep-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent2), var(--accent));
            border-radius: 4px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            letter-spacing: 0.06em;
            font-weight: 500;
        }

        .status-badge.active {
            background: rgba(110,231,183,0.08);
            border: 1px solid rgba(110,231,183,0.2);
            color: var(--accent);
        }

        .status-badge .dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .action-protected {
            font-size: 9px;
            letter-spacing: 0.12em;
            padding: 4px 10px;
            border: 1px solid var(--border);
            border-radius: 20px;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        .btn-edit {
            font-size: 10px;
            letter-spacing: 0.06em;
            padding: 5px 12px;
            border-radius: 8px;
            background: rgba(129,140,248,0.1);
            border: 1px solid rgba(129,140,248,0.2);
            color: var(--accent2);
            cursor: pointer;
            transition: all 0.15s;
            font-family: 'DM Mono', monospace;
        }

        .btn-edit:hover {
            background: rgba(129,140,248,0.2);
            border-color: rgba(129,140,248,0.4);
        }

        .btn-ban {
            font-size: 10px;
            letter-spacing: 0.06em;
            padding: 5px 12px;
            border-radius: 8px;
            background: rgba(248,113,113,0.08);
            border: 1px solid rgba(248,113,113,0.2);
            color: var(--danger);
            cursor: pointer;
            transition: all 0.15s;
            font-family: 'DM Mono', monospace;
        }

        .btn-ban:hover {
            background: rgba(248,113,113,0.18);
            border-color: rgba(248,113,113,0.4);
        }

        .table-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 28px;
            border-top: 1px solid var(--border);
            background: var(--surface2);
        }

        .footer-count {
            font-size: 11px;
            color: var(--text-muted);
        }

        .footer-count span {
            color: var(--text);
            font-weight: 500;
        }

        .btn-see-all {
            font-size: 11px;
            color: var(--accent2);
            cursor: pointer;
            background: none;
            border: none;
            font-family: 'DM Mono', monospace;
            letter-spacing: 0.04em;
            padding: 0;
            transition: color 0.15s;
            text-decoration: none;
        }

        .btn-see-all:hover { color: var(--accent); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        svg { display: block; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <a href="{{ route('dashboard') }}" class="nav-logo">
            <div class="nav-logo-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            Dashboard
        </a>
        
        <div class="nav-right">
            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="nav-user">
                    {{ auth()->user()->name }}
                </button>
                
                <div x-show="open" @click.away="open = false" class="dropdown-menu" :class="{ 'show': open }">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Profile
                    </a>
                    
                    <div class="dropdown-divider"></div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="status-pill">
                <div class="status-dot"></div>
                EN LIGNE
            </div>
        </div>
    </nav>

    <main>
        <div class="page-header">
            <div class="page-label">Admin / Supervision</div>
            <h1 class="page-title">SUPERVISION<br>GLOBALE</h1>
            <p class="page-sub">Vue d'ensemble de la plateforme</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-top">
                    <div class="stat-icon blue">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                            <path d="M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                    <span class="stat-badge">Total</span>
                </div>
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Utilisateurs</div>
            </div>

            <div class="stat-card green">
                <div class="stat-top">
                    <div class="stat-icon green">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                    <span class="stat-badge">Actives</span>
                </div>
                <div class="stat-value">{{ $stats['active_colocations'] }}</div>
                <div class="stat-label">Colocations</div>
            </div>

            <div class="stat-card purple">
                <div class="stat-top">
                    <div class="stat-icon purple">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f472b6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v4l3 3"/>
                        </svg>
                    </div>
                    <span class="stat-badge">Total cumulé</span>
                </div>
                <div class="stat-value">{{ number_format($stats['total_expenses'], 2) }}€</div>
                <div class="stat-label">Dépenses</div>
            </div>

            <div class="stat-card red">
                <div class="stat-top">
                    <div class="stat-icon red">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                        </svg>
                    </div>
                    <span class="stat-badge">À surveiller</span>
                </div>
                <div class="stat-value">{{ $stats['banned_users'] }}</div>
                <div class="stat-label">Bannis</div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-section">
            <div class="table-header">
                <span class="table-title">Gestion des Utilisateurs</span>
                <span class="table-meta">Dernière mise à jour: {{ now()->format('d/m/Y H:i') }}</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Réputation</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="id-cell">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar" @if(!$loop->first) style="background: linear-gradient(135deg, #f472b6, #fb923c)" @endif>
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <span class="user-name">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="email-cell">{{ $user->email }}</td>
                        <td>
                            <div class="rep-cell">
                                <span class="rep-val">{{ $user->reputation ?? 0 }}</span>
                                <div class="rep-bar-bg">
                                    <div class="rep-bar-fill" style="width: {{ min($user->reputation ?? 0, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge active">
                                <span class="dot"></span>
                                Actif
                            </span>
                        </td>
                        <td>
                            @if(auth()->user()->id === $user->id)
                                <span class="action-protected">Protégé</span>
                            @else
                                <div class="action-btns">
                                    <button class="btn-edit">Éditer</button>
                                    <button class="btn-ban">Bannir</button>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px; color: var(--text-muted);">
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="table-footer">
                <p class="footer-count">Affichage de <span>{{ $users->total() }}</span> utilisateurs</p>
                <a href="{{ route('admin.users.index') }}" class="btn-see-all">Voir tous les utilisateurs →</a>
            </div>
        </div>
    </main>

    <!-- Alpine.js for dropdown (included with Breeze) -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dropdown', () => ({
                open: false,
                toggle() {
                    this.open = !this.open;
                }
            }))
        })
    </script>
</body>
</html>