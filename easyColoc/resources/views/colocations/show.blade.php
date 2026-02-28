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
        .show-tabs {
            display: flex;
            gap: 4px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 28px;
        }
        .show-tab {
            padding: 10px 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            text-decoration: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: all 0.15s;
            cursor: pointer;
        }
        .show-tab:hover { color: var(--text-dim); }
        .show-tab.active { color: var(--accent2); border-bottom-color: var(--accent2); }

        /* Panneaux */
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* Layout 2 colonnes pour l'onglet dépenses */
        .expenses-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
        }

        /* Add expense form */
        .add-expense-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            position: sticky;
            top: 80px;
            animation: fadeUp 0.5s 0.1s ease both;
        }

        /* Month filter bar */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .filter-bar-label {
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .filter-select {
            flex: 1;
            padding: 8px 12px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: 'DM Mono', monospace;
            font-size: 12px;
            outline: none;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .filter-select:focus { border-color: rgba(129,140,248,0.5); }

        .filter-reset {
            padding: 7px 12px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-muted);
            font-family: 'DM Mono', monospace;
            font-size: 11px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .filter-reset:hover { border-color: var(--border-hover); color: var(--text); }

        /* Expense list */
        .expense-list-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            animation: fadeUp 0.5s ease both;
        }

        .expense-item {
            display: flex;
            flex-direction: column;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }
        .expense-item:last-child { border-bottom: none; }
        .expense-item:hover { background: rgba(0,0,0,0.02); }

        .expense-main {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .expense-cat {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(129,140,248,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .expense-body { flex: 1; min-width: 0; }
        .expense-title { font-size: 13px; font-weight: 500; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .expense-meta { font-size: 10px; color: var(--text-muted); margin-top: 3px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

        .expense-right { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }

        .expense-amount {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: var(--accent3);
        }

        .split-detail {
            font-size: 9px;
            color: var(--text-muted);
            letter-spacing: 0.06em;
            text-align: right;
            line-height: 1.5;
        }

        /* Detail section (who owes what) */
        .expense-details {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed var(--border);
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 8px;
        }

        .debt-pill {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surface2);
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 10px;
            border: 1px solid var(--border);
        }
        .debt-pill.paid { border-color: var(--accent); opacity: 0.7; }

        .debt-user { color: var(--text-dim); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 60px; }
        .debt-val { font-weight: 600; color: var(--accent3); }
        .debt-pill.paid .debt-val { color: var(--accent); }

        .pay-action { background: none; border: none; padding: 0; cursor: pointer; color: var(--accent); display: flex; align-items: center; }
        .pay-action:hover { transform: scale(1.1); }

        .expense-actions { display: flex; gap: 6px; }

        .action-btn {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-family: 'DM Mono', monospace;
            cursor: pointer;
            border: 1px solid var(--border);
            background: var(--surface2);
            color: var(--text-dim);
            transition: all 0.15s;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .action-btn:hover { border-color: var(--border-hover); color: var(--text); }
        .action-btn.danger { border-color: rgba(248,113,113,0.2); color: var(--danger); background: rgba(248,113,113,0.04); }
        .action-btn.danger:hover { background: rgba(248,113,113,0.1); }

        /* Summary bar below list */
        .expense-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-top: 1px solid var(--border);
            background: var(--surface2);
        }

        .summary-label { font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-muted); }
        .summary-val { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; color: var(--accent3); }

        /* Balance tab card */
        .balance-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .balance-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }
        .balance-row:last-child { border-bottom: none; }

        .balance-user-info { display: flex; align-items: center; gap: 12px; }

        .balance-amount { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 800; }
        .balance-pos { color: var(--accent); }
        .balance-neg { color: var(--danger); }

        /* Settlement simplified section */
        .settlement-card {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin-top: 20px;
        }

        .settlement-title { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; color: var(--accent2); margin-bottom: 10px; }
        .settlement-item { font-size: 12px; color: var(--text-dim); margin-bottom: 6px; display: flex; align-items: center; gap: 8px; }

    </style>
</head>
<body>
    <x-app-nav active="colocation" />

    <main>
        <!-- Page Header -->
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px; animation: fadeUp 0.4s ease both;">
            <div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                    <a href="{{ route('dashboard') }}" style="font-size:11px; color:var(--text-muted); text-decoration:none; display:flex; align-items:center; gap:4px; transition:color 0.15s;" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--text-muted)'">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                        Dashboard
                    </a>
                    <span style="color:var(--border-hover);">/</span>
                    <span style="font-size:11px; color:var(--text-dim);">{{ $colocation->name }}</span>
                </div>
                <div class="page-label">Ma Colocation</div>
                <h1 class="page-title">{{ $colocation->name }}</h1>
            </div>
            @if($colocation->owner_id === Auth::id())
                <a href="{{ route('colocations.edit', $colocation) }}" class="btn-secondary" style="margin-top:16px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Modifier
                </a>
            @endif
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert success"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert error"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ session('error') }}</div>
        @endif

        <!-- Tabs -->
        <div class="show-tabs" id="show-tabs">
            <span class="show-tab active" onclick="switchTab('expenses', this)">
                💸 Dépenses
                <span style="margin-left:6px; padding:1px 7px; background:rgba(244,114,182,0.1); border:1px solid rgba(244,114,182,0.2); border-radius:10px; font-size:9px; color:var(--accent3);">{{ $expenses->count() }}</span>
            </span>
            <span class="show-tab" onclick="switchTab('balances', this)">
                ⚖️ Soldes
            </span>
            <span class="show-tab" onclick="switchTab('members', this)">
                👥 Membres
                <span style="margin-left:6px; padding:1px 7px; background:rgba(129,140,248,0.1); border:1px solid rgba(129,140,248,0.2); border-radius:10px; font-size:9px; color:var(--accent2);">{{ $members->count() }}</span>
            </span>
            @if($colocation->owner_id === Auth::id())
            <span class="show-tab" onclick="switchTab('settings', this)">
                ⚙️ Paramètres
            </span>
            @endif
        </div>

        {{-- ============ TAB: EXPENSES ============ --}}
        <div id="tab-expenses" class="tab-panel active">
            <div class="expenses-layout">
                <!-- Left: List + Filter -->
                <div>
                    <!-- Month Filter Bar -->
                    <form method="GET" action="{{ route('colocations.show', $colocation) }}" id="filter-form">
                        <div class="filter-bar">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <span class="filter-bar-label">Filtrer par mois</span>
                            <select
                                name="month"
                                class="filter-select"
                                onchange="this.form.submit()"
                            >
                                <option value="">Tous les mois</option>
                                @foreach($availableMonths as $month)
                                    @php
                                        $parts = explode('-', $month);
                                        $frMonths = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril',
                                                     '05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août',
                                                     '09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Décembre'];
                                        $label = ($frMonths[$parts[1]] ?? $parts[1]) . ' ' . $parts[0];
                                    @endphp
                                    <option value="{{ $month }}" {{ $selectedMonth === $month ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @if($selectedMonth)
                                <a href="{{ route('colocations.show', $colocation) }}" class="filter-reset">✕ Réinitialiser</a>
                            @endif
                        </div>
                    </form>

                    <!-- Expense List -->
                    <div class="expense-list-card">
                        @if($expenses->isEmpty())
                            <div class="empty-state">
                                <div class="empty-state-icon">💸</div>
                                <div class="empty-state-title">
                                    {{ $selectedMonth ? 'Aucune dépense ce mois' : 'Aucune dépense' }}
                                </div>
                                <div class="empty-state-text">
                                    {{ $selectedMonth
                                        ? 'Il n\'y a pas de dépenses pour ce mois. Essayez un autre mois ou ajoutez une dépense.'
                                        : 'Commencez par ajouter la première dépense de la colocation.' }}
                                </div>
                            </div>
                        @else
                            @foreach($expenses as $expense)
                            <div class="expense-item">
                                <div class="expense-main">
                                    <div class="expense-cat">
                                        {{ $expense->category_rel->icon ?? '💰' }}
                                    </div>

                                    <div class="expense-body">
                                        <div class="expense-title">{{ $expense->title }}</div>
                                        <div class="expense-meta">
                                            <span>{{ $expense->expense_date?->format('d/m/Y') }}</span>
                                            @if($expense->is_settled)
                                                <span class="status-badge active" style="font-size:8px; padding:2px 6px; transform: scale(0.9); margin-left: 2px;">
                                                    <span class="dot"></span> Terminée
                                                </span>
                                            @endif
                                            @if($expense->category_rel)
                                                <span class="cat-badge">{{ $expense->category_rel->name }}</span>
                                            @endif
                                            <span>Payé par <strong style="color:var(--text-dim)">{{ $expense->payer->name ?? '?' }}</strong></span>
                                        </div>
                                    </div>

                                    <div class="expense-right">
                                        <div class="expense-amount">{{ number_format($expense->amount, 2) }} MAD</div>

                                        @if(Auth::id() === $expense->payer_id || Auth::id() === $colocation->owner_id)
                                            <div class="expense-actions">
                                                <a href="{{ route('expenses.edit', [$colocation, $expense]) }}" class="action-btn">
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                </a>
                                                <form method="POST" action="{{ route('expenses.destroy', [$colocation, $expense]) }}" onsubmit="return confirm('Supprimer cette dépense ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn danger">
                                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Details & Payment Actions --}}
                                <div class="expense-details">
                                    @foreach($expense->debtors as $debtor)
                                        @php
                                            $isPayer = $debtor->id === $expense->payer_id;
                                            $canMark = (Auth::id() === $debtor->id || Auth::id() === $colocation->owner_id) && !$isPayer;
                                        @endphp
                                        <div class="debt-pill {{ $debtor->pivot->is_paid ? 'paid' : '' }}">
                                            <span class="debt-user" title="{{ $debtor->name }}">{{ $debtor->name }}</span>
                                            <span class="debt-val">{{ number_format($debtor->pivot->amount_owed, 2) }} MAD</span>
                                            
                                            @if($isPayer)
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--accent2)" stroke-width="3" style="margin-left:4px;"><polyline points="20 6 9 17 4 12"/></svg>
                                            @elseif($debtor->pivot->is_paid)
                                                <form method="POST" action="{{ route('payments.mark-unpaid', [$colocation, $expense, $debtor]) }}">
                                                    @csrf
                                                    <button type="submit" class="pay-action" title="Annuler le paiement">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                                    </button>
                                                </form>
                                            @elseif($canMark)
                                                <form method="POST" action="{{ route('payments.mark-paid', [$colocation, $expense, $debtor]) }}">
                                                    @csrf
                                                    <button type="submit" class="pay-action" style="color:var(--text-muted)" title="Marquer comme payé">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach

                            <div class="expense-summary">
                                <div>
                                    <div class="summary-label">{{ $selectedMonth ? 'Total du mois' : 'Total affiché' }}</div>
                                    <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">{{ $expenses->count() }} dépense(s)</div>
                                </div>
                                <div class="summary-val">{{ number_format($filteredTotal, 2) }} MAD</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Add Expense Form -->
                <div class="add-expense-card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon purple" style="width:28px;height:28px;border-radius:8px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f472b6" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </div>
                            Ajouter une dépense
                        </div>
                    </div>

                    <div style="padding: 20px;">
                        <form method="POST" action="{{ route('expenses.store', $colocation) }}" style="display:flex;flex-direction:column;gap:14px;">
                            @csrf

                            <div class="form-group">
                                <label class="form-label" for="title">Titre *</label>
                                <input id="title" type="text" name="title" class="form-input" value="{{ old('title') }}" placeholder="Ex: Courses Lidl" required>
                                @error('title') <div class="form-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="amount">Montant (MAD) *</label>
                                <input id="amount" type="number" name="amount" class="form-input" value="{{ old('amount') }}" placeholder="0.00" step="0.01" min="0.01" required>
                                @error('amount') <div class="form-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="payer_id">Payé par *</label>
                                <select id="payer_id" name="payer_id" class="form-input" required>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ (old('payer_id', Auth::id()) == $member->id) ? 'selected' : '' }}>
                                            {{ $member->name }}{{ $member->id === Auth::id() ? ' (moi)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payer_id') <div class="form-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="expense_date">Date *</label>
                                <input id="expense_date" type="date" name="expense_date" class="form-input" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                                @error('expense_date') <div class="form-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="category">Catégorie</label>
                                <select id="category_id" name="category_id" class="form-input">
                                    <option value="">— Aucune catégorie —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->icon }} {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category') <div class="form-error">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Ajouter la dépense
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ TAB: BALANCES ============ --}}
        <div id="tab-balances" class="tab-panel">
            <div style="max-width: 800px; margin: 0 auto; animation: fadeUp 0.5s ease both;">
                <div class="page-label">Situation Financière</div>
                <h2 class="page-title" style="font-size: 28px; margin-bottom: 24px;">Soldes de la colocation</h2>

                <div class="balance-card">
                    {{-- Monthly Contribution Overview --}}
                    @if($selectedMonth)
                        @php
                            $parts = explode('-', $selectedMonth);
                            $frMonths = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Décembre'];
                            $monthLabel = ($frMonths[$parts[1]] ?? $parts[1]) . ' ' . $parts[0];
                        @endphp
                        <div style="padding: 20px 24px; border-bottom: 2px solid var(--border); background: var(--surface2);">
                            <div style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:var(--text-muted); margin-bottom:12px;">Dépenses effectuées en {{ $monthLabel }}</div>
                            <div style="display:flex; flex-direction:column; gap:10px;">
                                @foreach($members as $m)
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <div style="font-size:12px; font-weight:500; color:var(--text);">{{ $m->name }}</div>
                                        <div style="font-size:13px; font-weight:700; color:var(--accent2);">{{ number_format($monthlySpending[$m->id] ?? 0, 2) }} MAD</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div style="padding: 24px;">
                        <div style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:var(--text-muted); margin-bottom:16px;">Soldes Actuels (Globaux)</div>
                        @foreach($userBalances as $id => $data)
                            <div class="balance-row">
                                <div class="balance-user-info">
                                    <div class="avatar" style="background: linear-gradient(135deg, {{ ['#818cf8,#6ee7b7','#f472b6,#fb923c','#6ee7b7,#818cf8','#fb923c,#f472b6'][$data['user']->id % 4] }})">
                                        {{ strtoupper(substr($data['user']->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-size: 14px; font-weight: 600; color: var(--text);">{{ $data['user']->name }}</div>
                                        <div style="font-size: 10px; color: var(--text-muted);">{{ $id === Auth::id() ? 'Votre solde net' : 'Solde net du membre' }}</div>
                                    </div>
                                </div>
                                <div class="balance-amount {{ $data['balance'] >= 0 ? 'balance-pos' : 'balance-neg' }}">
                                    {{ $data['balance'] >= 0 ? '+' : '' }}{{ number_format($data['balance'], 2) }} MAD
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @php
                        // Simple settlement logic: find who owes who
                        $pos = []; $neg = [];
                        foreach($userBalances as $id => $data) {
                            if($data['balance'] > 0.01) $pos[$data['user']->name] = $data['balance'];
                            elseif($data['balance'] < -0.01) $neg[$data['user']->name] = abs($data['balance']);
                        }
                        
                        $repayments = [];
                        foreach($neg as $debtor => $amount) {
                            foreach($pos as $creditor => $credit) {
                                if($amount <= 0) break;
                                if($credit <= 0) continue;
                                $pay = min($amount, $credit);
                                $repayments[] = ['from' => $debtor, 'to' => $creditor, 'amount' => $pay];
                                $amount -= $pay;
                                $pos[$creditor] -= $pay;
                            }
                        }
                    @endphp

                    @if(!empty($repayments))
                        <div class="settlement-card">
                            <div class="settlement-title">Remboursements suggérés</div>
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                @foreach($repayments as $r)
                                    <div class="settlement-item">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                                        <span style="color:var(--text);">{{ $r['from'] }}</span> 
                                        doit <span style="font-weight:700; color:var(--accent3);">{{ number_format($r['amount'], 2) }} MAD</span> 
                                        à <span style="color:var(--text);">{{ $r['to'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div style="text-align:center; padding: 20px; color: var(--text-muted); font-size: 12px; border: 1px dashed var(--border); border-radius: 12px;">
                            Tout est équilibré. Aucun remboursement nécessaire.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============ TAB: MEMBERS ============ --}}
        <div id="tab-members" class="tab-panel">
            <div class="members-layout">
                <!-- Members list -->
                <div class="card" style="animation: fadeUp 0.4s ease both;">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon blue">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            </div>
                            Membres de la colocation
                        </div>
                        <span style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:var(--accent2);">{{ $members->count() }}</span>
                    </div>

                    @foreach($members as $member)
                    <div class="member-row">
                        <div class="avatar" style="background: linear-gradient(135deg, {{ ['#818cf8,#6ee7b7','#f472b6,#fb923c','#6ee7b7,#818cf8','#fb923c,#f472b6'][$member->id % 4] }})">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </div>
                        <div class="member-row-info">
                            <div class="member-row-name">
                                {{ $member->name }}
                                @if($member->id === Auth::id()) <span style="font-size:10px;color:var(--text-muted);margin-left:4px;">(vous)</span> @endif
                            </div>
                            <div class="member-row-sub">
                                <span>Rép: <span style="color: {{ ($member->reputation ?? 0) >= 0 ? 'var(--accent)' : 'var(--danger)' }}">{{ ($member->reputation ?? 0) >= 0 ? '+' : '' }}{{ $member->reputation ?? 0 }}</span></span>
                                @if($member->id === $colocation->owner_id)
                                    <span class="role-badge owner">Propriétaire</span>
                                @else
                                    <span class="role-badge member">Membre</span>
                                @endif
                            </div>
                        </div>
                        <div class="member-row-actions">
                            @if(Auth::id() === $colocation->owner_id && $member->id !== Auth::id())
                                <form method="POST" action="{{ route('colocations.members.remove', [$colocation, $member]) }}" onsubmit="return confirm('Retirer {{ addslashes($member->name) }} ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-ban" style="font-size:10px;padding:5px 10px;">Retirer</button>
                                </form>
                            @endif
                            @if(Auth::id() === $member->id && $member->id !== $colocation->owner_id)
                                <form method="POST" action="{{ route('colocations.leave', $colocation) }}" onsubmit="return confirm('Quitter la colocation ?');">
                                    @csrf
                                    <button type="submit" class="btn-ban" style="font-size:10px;padding:5px 10px;">Quitter</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Info + Invite -->
                <div style="display:flex;flex-direction:column;gap:20px;">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="card-icon orange">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                </div>
                                Informations
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Adresse</span>
                            <span class="info-val" style="max-width:180px;text-align:right;font-size:12px;">{{ $colocation->adresse }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Statut</span>
                            <span class="status-badge active"><span class="dot"></span> Active</span>
                        </div>
                        <div style="padding:16px 24px;">
                            <div class="info-key" style="margin-bottom:8px;">Code d'invitation</div>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span class="invite-code" id="invite-code-text">{{ $colocation->invitation_code }}</span>
                                <button class="copy-btn" onclick="copyCode()">Copier</button>
                            </div>
                        </div>
                    </div>

                    @if(Auth::id() === $colocation->owner_id)
                    <div class="invite-section" style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden;">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="card-icon green">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                Inviter un membre
                            </div>
                        </div>
                        <div style="padding: 20px 24px;">
                            <form method="POST" action="{{ route('invitations.store', $colocation) }}" style="display:flex;gap:12px;align-items:flex-end;">
                                @csrf
                                <div class="form-group" style="flex:1;margin-bottom:0;">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-input" placeholder="membre@email.com" required>
                                </div>
                                <button type="submit" class="btn-primary" style="flex-shrink:0;padding:10px 14px;">Inviter</button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============ TAB: SETTINGS (owner only) ============ --}}
        @if($colocation->owner_id === Auth::id())
        <div id="tab-settings" class="tab-panel">
            <div style="max-width:640px; display:flex; flex-direction:column; gap:24px;">
                
                {{-- Custom Categories Section --}}
                <div class="card" style="animation: fadeUp 0.5s ease both;">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon purple">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f472b6" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </div>
                            Catégories Personnalisées
                        </div>
                    </div>
                    <div style="padding: 24px;">
                        <p style="font-size:11px; color:var(--text-muted); margin-bottom:16px;">
                            Créez des catégories spécifiques à votre colocation. Elles ne seront visibles que par vous et vos colocataires.
                        </p>
                        
                        <form method="POST" action="{{ route('categories.store', $colocation) }}" style="display:grid; grid-template-columns: 1fr 100px auto; gap:12px; align-items:flex-end;">
                            @csrf
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" style="font-size:10px;">Nom de la catégorie</label>
                                <input type="text" name="name" class="form-input" placeholder="Ex: Ménage, Jardin..." required>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" style="font-size:10px;">Icône (Emoji)</label>
                                <input type="text" name="icon" class="form-input" placeholder="✨" max="5">
                            </div>
                            <button type="submit" class="btn-primary" style="padding:10px 16px;">Ajouter</button>
                        </form>

                        @php $customCats = $categories->whereNotNull('colocation_id'); @endphp
                        @if($customCats->isNotEmpty())
                            <div style="margin-top:24px;">
                                <div style="font-size:10px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.04em; margin-bottom:12px;">Vos catégories :</div>
                                <div style="display:flex; flex-wrap:wrap; gap:8px;">
                                    @foreach($customCats as $cc)
                                        <div style="padding:6px 12px; background:var(--surface2); border:1px solid var(--border); border-radius:8px; font-size:12px; display:flex; align-items:center; gap:8px;">
                                            <span>{{ $cc->icon }}</span>
                                            <span style="font-weight:600; color:var(--text);">{{ $cc->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="danger-zone" style="margin-top:8px;">
                    <div class="danger-zone-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Zone de Danger
                    </div>
                    <p class="danger-zone-text">
                        Annuler la colocation est une action <strong style="color:var(--danger)">irréversible</strong>. Tous les membres seront retirés et l'historique des dépenses sera supprimé.
                    </p>
                    <form method="POST" action="{{ route('colocations.destroy', $colocation) }}" onsubmit="return confirm('Êtes-vous sûr ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                            Annuler la colocation
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </main>

    <script>
        function switchTab(name, el) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.show-tab').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-' + name).classList.add('active');
            el.classList.add('active');
        }

        function copyCode() {
            const code = document.getElementById('invite-code-text').textContent.trim();
            navigator.clipboard.writeText(code).then(() => {
                const btn = event.target;
                const orig = btn.textContent;
                btn.textContent = 'Copié !';
                btn.style.color = 'var(--accent)';
                setTimeout(() => { btn.textContent = orig; btn.style.color = ''; }, 2000);
            });
        }
    </script>
</body>
</html>
