<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc — Créer une Colocation</title>
    <x-app-styles />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .form-page-layout {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            animation: fadeUp 0.5s 0.1s ease both;
        }

        .form-card-header {
            padding: 28px 32px 24px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, rgba(129,140,248,0.04), rgba(110,231,183,0.03));
        }

        .form-card-title {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .form-card-desc {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.04em;
            line-height: 1.7;
        }

        .form-card-body {
            padding: 28px 32px;
        }

        .form-card-footer {
            padding: 20px 32px;
            border-top: 1px solid var(--border);
            background: var(--surface2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .tips-section {
            background: rgba(129,140,248,0.05);
            border: 1px solid rgba(129,140,248,0.15);
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 20px;
        }

        .tips-title {
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent2);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .tip-item {
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.8;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .tip-item + .tip-item { margin-top: 4px; }
        .tip-dot {
            width: 4px;
            height: 4px;
            background: var(--accent2);
            border-radius: 50%;
            margin-top: 7px;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <x-app-nav active="dashboard" />

    <main>
        <div class="form-page-layout">
            <!-- Page Header -->
            <div class="page-header" style="margin-bottom: 28px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                    <a href="{{ route('dashboard') }}" style="font-size:11px; color:var(--text-muted); text-decoration:none; display:flex; align-items:center; gap:4px; transition:color 0.15s;" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--text-muted)'">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                        Dashboard
                    </a>
                    <span style="color:var(--border-hover);">/</span>
                    <span style="font-size:11px; color:var(--text-dim);">Nouvelle Colocation</span>
                </div>
                <div class="page-label">Création</div>
                <h1 class="page-title" style="font-size:36px;">Nouvelle Colocation</h1>
                <p class="page-sub">Créez votre espace de colocation et invitez vos colocataires.</p>
            </div>

            <!-- Flash Messages -->
            @if(session('error'))
                <div class="alert error"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ session('error') }}</div>
            @endif

            <!-- Form Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-title">
                        <span style="margin-right:10px;">🏠</span> Informations de la colocation
                    </div>
                    <p class="form-card-desc">
                        Choisissez un nom distinctif et indiquez l'adresse complète. Un code d'invitation unique sera généré automatiquement.
                    </p>
                </div>

                <form method="POST" action="{{ route('colocations.store') }}">
                    @csrf

                    <div class="form-card-body">
                        <div class="form-group">
                            <label class="form-label" for="name">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                Nom de la colocation
                            </label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                class="form-input"
                                value="{{ old('name') }}"
                                placeholder="Ex: Appart du 5ème, Villa des Colos..."
                                required
                                autofocus
                            >
                            @error('name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="adresse">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Adresse complète
                            </label>
                            <input
                                id="adresse"
                                type="text"
                                name="adresse"
                                class="form-input"
                                value="{{ old('adresse') }}"
                                placeholder="Ex: 12 Rue des Lilas, 75011 Paris"
                                required
                            >
                            @error('adresse')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="tips-section">
                            <div class="tips-title">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                Bon à savoir
                            </div>
                            <div class="tip-item"><div class="tip-dot"></div>Vous serez automatiquement désigné propriétaire (owner) de la colocation.</div>
                            <div class="tip-item"><div class="tip-dot"></div>Un code d'invitation unique sera généré pour inviter vos colocataires.</div>
                            <div class="tip-item"><div class="tip-dot"></div>Vous ne pouvez être membre que d'une seule colocation active à la fois.</div>
                        </div>
                    </div>

                    <div class="form-card-footer">
                        <a href="{{ route('dashboard') }}" class="btn-secondary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                            Annuler
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Créer la colocation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
