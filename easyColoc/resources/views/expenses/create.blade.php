<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc — Nouvelle dépense</title>
    <x-app-styles />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .form-page-layout { max-width: 560px; margin: 0 auto; }
        .form-card { background:var(--surface); border:1px solid var(--border); border-radius:20px; overflow:hidden; animation: fadeUp 0.5s 0.1s ease both; }
        .form-card-header { padding:24px 32px 20px; border-bottom:1px solid var(--border); background:linear-gradient(135deg, rgba(129,140,248,0.04), rgba(244,114,182,0.03)); }
        .form-card-title { font-family:'Syne',sans-serif; font-size:19px; font-weight:800; letter-spacing:-0.02em; margin-bottom:5px; }
        .form-card-desc { font-size:11px; color:var(--text-muted); letter-spacing:0.04em; line-height:1.7; }
        .form-card-body { padding:24px 32px; display:flex; flex-direction:column; gap:16px; }
        .form-card-footer { padding:18px 32px; border-top:1px solid var(--border); background:var(--surface2); display:flex; align-items:center; justify-content:space-between; }
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
                <span style="color:var(--text-dim);">Nouvelle dépense</span>
            </div>

            <div class="page-label">Dépenses</div>
            <h1 class="page-title" style="font-size:32px;margin-bottom:24px;">Ajouter une dépense</h1>

            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-title">💸 Nouvelle dépense</div>
                    <p class="form-card-desc">
                        Enregistrez une dépense effectuée pour la colocation. Elle sera divisée équitablement entre les membres.
                    </p>
                </div>

                <form method="POST" action="{{ route('expenses.store', $colocation) }}">
                    @csrf

                    <div class="form-card-body">
                        <div class="form-group">
                            <label class="form-label" for="title">Titre *</label>
                            <input id="title" type="text" name="title" class="form-input" value="{{ old('title') }}" placeholder="Ex: Courses Carrefour" required autofocus>
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
                                    <option value="{{ $member->id }}" {{ old('payer_id', Auth::id()) == $member->id ? 'selected' : '' }}>
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
                            <select id="category" name="category" class="form-input">
                                <option value="">— Sélectionner une catégorie —</option>
                                <option value="alimentation" {{ old('category') === 'alimentation' ? 'selected' : '' }}>🛒 Alimentation</option>
                                <option value="loyer"        {{ old('category') === 'loyer'        ? 'selected' : '' }}>🏠 Loyer / Charges</option>
                                <option value="electricite"  {{ old('category') === 'electricite'  ? 'selected' : '' }}>⚡ Électricité</option>
                                <option value="eau"          {{ old('category') === 'eau'          ? 'selected' : '' }}>💧 Eau</option>
                                <option value="internet"     {{ old('category') === 'internet'     ? 'selected' : '' }}>📡 Internet</option>
                                <option value="transport"    {{ old('category') === 'transport'    ? 'selected' : '' }}>🚗 Transport</option>
                                <option value="autre"        {{ old('category') === 'autre'        ? 'selected' : '' }}>💰 Autre</option>
                            </select>
                            @error('category') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Description (Optionnel)</label>
                            <textarea id="description" name="description" class="form-input" rows="3" style="resize:vertical;" placeholder="Précisez les détails ici...">{{ old('description') }}</textarea>
                            @error('description') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div style="padding:10px 14px; background:rgba(129,140,248,0.05); border:1px solid rgba(129,140,248,0.15); border-radius:10px; font-size:10px; color:var(--text-muted); letter-spacing:0.04em;">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" style="display:inline;margin-right:4px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Le montant sera réparti entre les <strong style="color:var(--accent);">{{ $members->count() }} membres</strong> de la colocation.
                        </div>
                    </div>

                    <div class="form-card-footer">
                        <a href="{{ route('colocations.show', $colocation) }}" class="btn-secondary">
                            Annuler
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Créer la dépense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
