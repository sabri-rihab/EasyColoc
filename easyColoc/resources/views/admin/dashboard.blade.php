<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc — Administration</title>
    <x-app-styles />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .admin-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: rgba(251,146,60,0.1);
            border: 1px solid rgba(251,146,60,0.25);
            border-radius: 20px;
            font-size: 9px;
            letter-spacing: 0.12em;
            color: var(--accent4);
            text-transform: uppercase;
            font-weight: 600;
        }
        .colocation-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 8px;
            background: rgba(110,231,183,0.08);
            border: 1px solid rgba(110,231,183,0.15);
            border-radius: 6px;
            font-size: 10px;
            color: var(--accent);
        }
        .no-colocation-chip {
            font-size: 10px;
            color: var(--text-muted);
            font-style: italic;
        }
        .section-gap {
            margin-bottom: 24px;
        }
        .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.15s; }
        .stat-card:nth-child(4) { animation-delay: 0.2s; }
    </style>
</head>
<body>
    <x-app-nav active="admin" />

    <main>
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-label">Administration Globale</div>
            <h1 class="page-title">EasyColoc</h1>
            <p class="page-sub">Vue d'ensemble de la plateforme — {{ now()->format('d/m/Y') }}</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-top">
                    <div class="stat-icon blue">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f472b6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"/>
                            <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                        </svg>
                    </div>
                    <span class="stat-badge">Cumulé</span>
                </div>
                <div class="stat-value" style="font-size:28px;">{{ number_format($stats['total_expenses'], 0) }} MAD</div>
                <div class="stat-label">Dépenses totales</div>
            </div>

            <div class="stat-card red">
                <div class="stat-top">
                    <div class="stat-icon red">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                        </svg>
                    </div>
                    <span class="stat-badge">Surveillés</span>
                </div>
                <div class="stat-value">{{ $stats['banned_users'] }}</div>
                <div class="stat-label">Utilisateurs bannis</div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert success" style="margin-bottom: 24px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert error" style="margin-bottom: 24px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Global Debts Table -->
        <div class="table-section section-gap">
            <div class="table-header">
                <div class="table-title">
                    <div class="card-icon orange" style="width:28px;height:28px;border-radius:8px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                    Récapitulatif des Dettes (Plateforme)
                </div>
                <div class="table-meta">Dettes non-réglées</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Debiteur (Doit)</th>
                        <th>Payeur (Dû à)</th>
                        <th>Montant</th>
                        <th>Dépense</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($globalDebts as $debt)
                    <tr>
                        <td style="color:var(--danger); font-weight:600;">{{ $debt->debtor_name }}</td>
                        <td style="color:var(--accent); font-weight:600;">{{ $debt->payer_name }}</td>
                        <td style="font-weight:700;">{{ number_format($debt->amount_owed, 2) }} MAD</td>
                        <td style="font-size:11px; color:var(--text-muted);">{{ Str::limit($debt->title, 25) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="empty-state">Aucune dette impayée sur la plateforme</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Colocations Table -->
        <div class="table-section section-gap">
            <div class="table-header">
                <div class="table-title">
                    <div class="card-icon green" style="width:28px;height:28px;border-radius:8px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    Dernières Colocations
                </div>
                <div class="table-meta">10 dernières créées</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Propriétaire</th>
                        <th>Adresse</th>
                        <th>Code</th>
                        <th>Dépenses Totales</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($colocations as $coloc)
                    <tr>
                        <td style="font-weight:600; color:var(--text);">{{ $coloc->name }}</td>
                        <td>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div class="avatar" style="width:24px;height:24px;font-size:9px;">{{ strtoupper(substr($coloc->owner->name ?? '?', 0, 2)) }}</div>
                                <span style="font-size:12px;">{{ $coloc->owner->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="email-cell">{{ Str::limit($coloc->adresse, 30) }}</td>
                        <td><code style="background:var(--surface2); padding:2px 6px; border-radius:4px; font-size:10px; color:var(--accent);">{{ $coloc->invitation_code }}</code></td>
                        <td style="font-weight:700; color:var(--accent3);">{{ number_format($coloc->expenses()->sum('amount'), 2) }} MAD</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">Aucune colocation</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Users Table -->
        <div class="table-section">
            <div class="table-header">
                <div class="table-title">
                    <div class="card-icon blue" style="width:28px;height:28px;border-radius:8px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    Gestion des Utilisateurs
                </div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="admin-label">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        Accès Administrateur
                    </div>
                    <span class="table-meta">MàJ: {{ now()->format('H:i') }}</span>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Réputation</th>
                        <th>Colocation</th>
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
                                <div class="avatar" style="background: linear-gradient(135deg, {{ ['#818cf8,#6ee7b7','#f472b6,#fb923c','#6ee7b7,#818cf8','#fb923c,#f472b6'][$user->id % 4] }})">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $user->name }}</div>
                                    @if($user->is_global_admin)
                                        <div class="role-badge admin" style="margin-top:3px; font-size:8px;">Admin</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="email-cell">{{ $user->email }}</td>
                        <td>
                            <div class="rep-cell">
                                <span class="rep-val" style="color: {{ ($user->reputation ?? 0) >= 0 ? 'var(--accent)' : 'var(--danger)' }}">
                                    {{ ($user->reputation ?? 0) >= 0 ? '+' : '' }}{{ $user->reputation ?? 0 }}
                                </span>
                                <div class="rep-bar-bg">
                                    @php $repPct = min(max(($user->reputation ?? 0) + 10, 0), 20) / 20 * 100; @endphp
                                    <div class="rep-bar-fill" style="width: {{ $repPct }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php $userColoc = $user->currentColocation(); @endphp
                            @if($userColoc)
                                <span class="colocation-chip">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                    {{ Str::limit($userColoc->name, 18) }}
                                </span>
                            @else
                                <span class="no-colocation-chip">Aucune</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_banned)
                                <span class="status-badge inactive">
                                    <span class="dot"></span> Banni
                                </span>
                            @else
                                <span class="status-badge active">
                                    <span class="dot"></span> Actif
                                </span>
                            @endif
                        </td>
                        <td>
                            @if(auth()->user()->id === $user->id)
                                <span class="action-protected">Protégé</span>
                            @else
                                @if($user->is_banned)
                                    <form method="POST" action="{{ route('admin.users.unban', $user) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-edit">
                                            Réactiver
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.ban', $user) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-ban">Bannir</button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">👥</div>
                                <div class="empty-state-title">Aucun utilisateur</div>
                                <div class="empty-state-text">Il n'y a pas encore d'utilisateurs inscrits.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 24px; border-top:1px solid var(--border); background:var(--surface2);">
                <span style="font-size:11px; color:var(--text-muted);">
                    <span style="color:var(--text);font-weight:500;">{{ $users->count() }}</span> utilisateur(s) au total
                </span>
                <span style="font-size:11px; color:var(--text-muted);">
                    <span style="color:var(--danger);">{{ $stats['banned_users'] }}</span> banni(s)
                </span>
            </div>
        </div>
    </main>
</body>
</html>