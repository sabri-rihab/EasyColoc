<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc — {{ $colocation->name }}</title>
    <x-app-styles />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 20px;
            align-items: start;
        }

        .side-panel { display: flex; flex-direction: column; gap: 20px; }

        .coloc-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            position: relative;
            overflow: hidden;
            animation: fadeUp 0.5s ease both;
        }

        .coloc-card::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(129,140,248,0.1), transparent 70%);
            pointer-events: none;
        }

        .coloc-name {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text);
            margin-bottom: 6px;
        }

        .coloc-address {
            font-size: 11px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 20px;
        }

        .coloc-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .coloc-stat {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px;
        }

        .coloc-stat-val {
            font-family: 'Syne', sans-serif;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 4px;
        }

        .coloc-stat-label {
            font-size: 9px;
            letter-spacing: 0.12em;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .member-list { display: flex; flex-direction: column; gap: 10px; }

        .member-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 12px;
            transition: all 0.15s;
        }
        .member-item:hover { border-color: var(--border-hover); background: rgba(0,0,0,0.03); }

        .member-info { flex: 1; min-width: 0; }
        .member-name { font-size: 13px; font-weight: 500; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .member-rep { font-size: 10px; color: var(--text-muted); margin-top: 2px; }

        .member-badges { display: flex; gap: 5px; align-items: center; flex-shrink: 0; }

        .main-panel { display: flex; flex-direction: column; gap: 20px; }

        .expense-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }
        .expense-row:last-child { border-bottom: none; }
        .expense-row:hover { background: rgba(0,0,0,0.02); }

        .expense-cat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(129,140,248,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .expense-info { flex: 1; min-width: 0; }
        .expense-title { font-size: 13px; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .expense-meta { font-size: 10px; color: var(--text-muted); margin-top: 2px; }

        .expense-amount {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--accent3);
            flex-shrink: 0;
        }

        .quick-actions {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            animation: fadeUp 0.5s 0.15s ease both;
        }

        .quick-action-title {
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .action-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-dim);
            text-decoration: none;
            font-size: 12px;
            transition: all 0.15s;
        }
        .action-link:hover { border-color: var(--border-hover); color: var(--text); background: rgba(0,0,0,0.04); }
        .action-link svg { width: 14px; height: 14px; stroke: currentColor; flex-shrink: 0; }

        .balance-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            animation: fadeUp 0.5s 0.1s ease both;
        }

        .my-balance-highlight {
            background: linear-gradient(135deg, rgba(129,140,248,0.08), rgba(110,231,183,0.06));
            border: 1px solid rgba(129,140,248,0.2);
            border-radius: 14px;
            padding: 20px;
            margin: 0 20px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .balance-label-sm {
            font-size: 10px;
            letter-spacing: 0.12em;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .balance-amount-lg {
            font-family: 'Syne', sans-serif;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .balance-positive { color: var(--accent); }
        .balance-negative { color: var(--danger); }
        .balance-zero { color: var(--text-muted); }
    </style>
</head>
<body>
    <x-app-nav active="dashboard" />

    <main>
        <!-- Header -->
        <div class="page-header" style="display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:32px;">
            <div>
                <div class="page-label">Votre Colocation</div>
                <h1 class="page-title">{{ $colocation->name }}</h1>
                <p class="page-sub" style="display:flex; align-items:center; gap:6px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ $colocation->adresse }}
                </p>
            </div>
            <div style="display:flex; gap:10px; align-items:center;">
                @if(auth()->id() === $colocation->owner_id)
                    <a href="{{ route('colocations.edit', $colocation) }}" class="btn-secondary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Modifier
                    </a>
                @endif
                <a href="{{ route('colocations.show', $colocation) }}" class="btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><grid-icon/><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Gérer la colocation
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert success"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert error"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ session('error') }}</div>
        @endif

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Side Panel -->
            <div class="side-panel">

                <!-- Colocation Info Card -->
                <div class="coloc-card">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
                        <div class="status-badge active"><span class="dot"></span> Active</div>
                        @if(auth()->id() === $colocation->owner_id)
                            <div class="role-badge owner">Owner</div>
                        @else
                            <div class="role-badge member">Member</div>
                        @endif
                    </div>

                    <div class="coloc-stats">
                        <div class="coloc-stat">
                            <div class="coloc-stat-val" style="color: var(--accent2);">{{ $members->count() }}</div>
                            <div class="coloc-stat-label">Membres</div>
                        </div>
                        <div class="coloc-stat">
                            <div class="coloc-stat-val" style="color: var(--accent);">{{ $reputation >= 0 ? '+' : '' }}{{ $reputation }}</div>
                            <div class="coloc-stat-label">Ma Réputation</div>
                        </div>
                    </div>

                    <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--border);">
                        <div style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:var(--text-muted); margin-bottom:8px;">Code d'invitation</div>
                        <div class="invite-code">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                            {{ $colocation->invitation_code }}
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="quick-action-title">Actions rapides</div>
                    <a href="{{ route('colocations.show', $colocation) }}" class="action-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                        Voir la colocation complète
                    </a>
                    <a href="{{ route('profile.edit') }}" class="action-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Mon profil
                    </a>
                    @if(auth()->id() !== $colocation->owner_id)
                        <form method="POST" action="{{ route('colocations.leave', $colocation) }}" onsubmit="return confirm('Quitter la colocation ?');">
                            @csrf
                            <button type="submit" class="action-link" style="width:100%;text-align:left;color:var(--danger);border-color:rgba(248,113,113,0.2);background:rgba(248,113,113,0.04); cursor:pointer; font-family:'DM Mono',monospace;">
                               <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Quitter la colocation
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Members Card -->
                <div class="card" style="animation: fadeUp 0.5s 0.2s ease both;">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon blue">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            </div>
                            Membres
                        </div>
                        <span style="font-family:'Syne',sans-serif; font-size:13px; font-weight:700; color:var(--accent2);">{{ $members->count() }}</span>
                    </div>
                    <div style="padding:14px 16px;">
                        <div class="member-list">
                            @foreach($members as $member)
                            <div class="member-item">
                                <div class="avatar" style="width:32px;height:32px;border-radius:8px;font-size:11px;background:linear-gradient(135deg,{{ ['#818cf8,#6ee7b7','#f472b6,#fb923c','#6ee7b7,#818cf8','#fb923c,#f472b6'][$member->id % 4] }})">
                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                </div>
                                <div class="member-info">
                                    <div class="member-name">{{ $member->name }}</div>
                                    <div class="member-rep">Rép: {{ $member->reputation >= 0 ? '+' : '' }}{{ $member->reputation }}</div>
                                </div>
                                <div class="member-badges">
                                    @if($member->id === $colocation->owner_id)
                                        <span class="role-badge owner">Owner</span>
                                    @endif
                                    @if($member->id === auth()->id())
                                        <span style="font-size:9px; color:var(--text-muted); letter-spacing:0.08em;">(vous)</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Panel -->
            <div class="main-panel">
                <!-- My Balance Card -->
                <div class="balance-section">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon green">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            </div>
                            Mon Solde
                        </div>
                    </div>
                    <div style="padding:16px 20px;">
                        <div class="my-balance-highlight">
                            <div>
                                <div class="stat-label">Mon Solde Net</div>
                                <div class="stat-value" style="color: {{ $balance > 0 ? 'var(--accent)' : ($balance < 0 ? 'var(--danger)' : 'var(--text)') }}">
                                    {{ $balance > 0 ? '+' : '' }}{{ number_format($balance, 2) }} MAD
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-label">Déboursé Total</div>
                                <div style="font-family:'Syne',sans-serif; font-size:18px; font-weight:700; color: var(--accent);">{{ number_format($totalPaid, 2) }} MAD</div>
                            </div>
                        </div>

                        <!-- Debts & Credits Details -->
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:24px;">
                            <!-- Who owes me -->
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title" style="font-size:12px; color:var(--accent);">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        On me doit
                                    </div>
                                </div>
                                <div style="display:flex; flex-direction:column; gap:8px;">
                                    @forelse($whoOwesMe as $credit)
                                        <div style="display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid var(--border-light);">
                                            <div>
                                                <div style="font-size:12px; font-weight:600;">{{ $credit->user_name }}</div>
                                                <div style="font-size:9px; color:var(--text-muted);">{{ Str::limit($credit->expense_title, 15) }}</div>
                                            </div>
                                            <div style="font-size:13px; font-weight:700; color:var(--accent);">+{{ number_format($credit->amount_owed, 2) }}</div>
                                        </div>
                                    @empty
                                        <div style="font-size:11px; color:var(--text-muted); text-align:center; padding:10px;">Personne ne vous doit rien.</div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Whom I owe -->
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title" style="font-size:12px; color:var(--danger);">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                        Je dois à
                                    </div>
                                </div>
                                <div style="display:flex; flex-direction:column; gap:8px;">
                                    @forelse($whomIOwe as $debt)
                                        <div style="display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid var(--border-light);">
                                            <div>
                                                <div style="font-size:12px; font-weight:600;">{{ $debt->user_name }}</div>
                                                <div style="font-size:9px; color:var(--text-muted);">{{ Str::limit($debt->expense_title, 15) }}</div>
                                            </div>
                                            <div style="font-size:13px; font-weight:700; color:var(--danger);">-{{ number_format($debt->amount_owed, 2) }}</div>
                                        </div>
                                    @empty
                                        <div style="font-size:11px; color:var(--text-muted); text-align:center; padding:10px;">Vous n'avez aucune dette.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="section-title">Actions rapides</div>
                        <div class="quick-actions">
                            <a href="{{ route('expenses.create', $colocation) }}" class="action-card">
                                <div class="action-icon" style="background: var(--bg-accent);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                </div>
                                <span>Nouvelle Dépense</span>
                            </a>
                            <a href="{{ route('colocations.show', $colocation) }}" class="action-card">
                                <div class="action-icon" style="background: rgba(110,231,183,0.1);">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                </div>
                                <span>Voir les Détails</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="card" style="animation: fadeUp 0.5s 0.15s ease both;">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon purple">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f472b6" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            </div>
                            Dépenses Récentes
                        </div>
                        <a href="{{ route('colocations.show', $colocation) }}" style="font-size:11px; color:var(--accent2); text-decoration:none; transition:color 0.15s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--accent2)'">
                            Voir tout →
                        </a>
                    </div>

                    @if($recentExpenses->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon">💸</div>
                            <div class="empty-state-title">Aucune dépense</div>
                            <div class="empty-state-text">Aucune dépense enregistrée pour le moment.<br>Commencez par en ajouter une depuis la page colocation.</div>
                        </div>
                    @else
                        @foreach($recentExpenses as $expense)
                        <div class="expense-row">
                            <div class="expense-cat-icon">
                                @switch($expense->category)
                                    @case('alimentation') 🛒 @break
                                    @case('loyer') 🏠 @break
                                    @case('electricite') ⚡ @break
                                    @case('eau') 💧 @break
                                    @case('internet') 📡 @break
                                    @case('transport') 🚗 @break
                                    @default 💰
                                @endswitch
                            </div>
                            <div class="expense-info">
                                <div class="expense-title">{{ $expense->title }}</div>
                                <div class="expense-meta">
                                    Payé par {{ $expense->payer->name ?? 'N/A' }} · {{ $expense->expense_date?->format('d/m/Y') ?? '' }}
                                    @if($expense->category) · <span style="color:var(--accent2);">{{ ucfirst($expense->category) }}</span> @endif
                                </div>
                            </div>
                            <div class="expense-amount">{{ number_format($expense->amount, 2) }} MAD</div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>
