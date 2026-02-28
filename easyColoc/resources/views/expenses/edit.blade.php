<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc — Modifier la dépense</title>
    <x-app-styles />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .form-page-layout { max-width: 560px; margin: 0 auto; }
        .form-card { background:var(--surface); border:1px solid var(--border); border-radius:20px; overflow:hidden; animation: fadeUp 0.5s 0.1s ease both; }
        .form-card-header { padding:24px 32px 20px; border-bottom:1px solid var(--border); background:linear-gradient(135deg, rgba(244,114,182,0.04), rgba(129,140,248,0.03)); }
        .form-card-title { font-family:'Syne',sans-serif; font-size:19px; font-weight:800; letter-spacing:-0.02em; margin-bottom:5px; }
        .form-card-desc { font-size:11px; color:var(--text-muted); letter-spacing:0.04em; line-height:1.7; }
        .form-card-body { padding:24px 32px; display:flex; flex-direction:column; gap:16px; }
        .form-card-footer { padding:18px 32px; border-top:1px solid var(--border); background:var(--surface2); display:flex; align-items:center; justify-content:space-between; }
        .current-value { display:inline-flex; align-items:center; gap:5px; padding:3px 8px; background:rgba(110,231,183,0.06); border:1px solid rgba(110,231,183,0.15); border-radius:6px; font-size:10px; color:var(--text-muted); margin-bottom:7px; }
    </style>
</head>
<body>
    <x-app-nav active="colocation" />

    <main>
        <div class="form-page-layout">
            <!-- Breadcrumb -->
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;font-size:11px;">
                <a href="{{ route('dashboard') }}" style="color:var(--text-muted);text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--text-muted)'">Dashboard</a>
                <span style="color:var(--border-hover);">/</span>
                <a href="{{ route('colocations.show', $colocation) }}" style="color:var(--text-muted);text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--text-muted)'">{{ $colocation->name }}</a>
                <span style="color:var(--border-hover);">/</span>
                <span style="color:var(--text-dim);">Modifier dépense</span>
            </div>

            <div class="page-label">Modification</div>
            <h1 class="page-title" style="font-size:32px;margin-bottom:24px;">Modifier la dépense</h1>

            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-title">✏️ {{ $expense->title }}</div>
                    <p class="form-card-desc">
                        Modifiez les informations de cette dépense. La répartition sera recalculée automatiquement.
                    </p>
                </div>

                <form method="POST" action="{{ route('expenses.update', [$colocation, $expense]) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-card-body">
                        <div class="form-group">
                            <label class="form-label" for="title">Titre *</label>
                            <div class="current-value">Actuel : {{ $expense->title }}</div>
                            <input id="title" type="text" name="title" class="form-input" value="{{ old('title', $expense->title) }}" required autofocus>
                            @error('title') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="amount">Montant (MAD) *</label>
                            <div class="current-value">Actuel : {{ number_format($expense->amount, 2) }} MAD</div>
                            <input id="amount" type="number" name="amount" class="form-input" value="{{ old('amount', $expense->amount) }}" step="0.01" min="0.01" required>
                            @error('amount') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="payer_id">Payé par *</label>
                            <select id="payer_id" name="payer_id" class="form-input" required>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('payer_id', $expense->payer_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}{{ $member->id === Auth::id() ? ' (moi)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payer_id') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="expense_date">Date *</label>
                            <input id="expense_date" type="date" name="expense_date" class="form-input" value="{{ old('expense_date', $expense->expense_date?->format('Y-m-d')) }}" required>
                            @error('expense_date') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="category_id">Catégorie</label>
                            <select id="category_id" name="category_id" class="form-input">
                                <option value="">— Sélectionner une catégorie —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $expense->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->icon }} {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <textarea id="description" name="description" class="form-input" rows="3" style="resize:vertical;">{{ old('description', $expense->description) }}</textarea>
                            @error('description') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <!-- Info recalcul -->
                        <div style="padding:10px 14px; background:rgba(251,146,60,0.05); border:1px solid rgba(251,146,60,0.15); border-radius:10px; font-size:10px; color:var(--text-muted); letter-spacing:0.04em;">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="2" style="display:inline;margin-right:4px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            La répartition sera recalculée entre <strong style="color:#fb923c;">{{ $members->count() }} membres</strong>. Les statuts de paiement existants seront conservés.
                        </div>
                    </div>

                    <div class="form-card-footer">
                        <a href="{{ route('colocations.show', $colocation) }}" class="btn-secondary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                            Annuler
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v14a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
