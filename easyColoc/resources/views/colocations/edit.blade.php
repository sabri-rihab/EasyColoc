<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc — Modifier {{ $colocation->name }}</title>
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

        .current-value {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: rgba(110,231,183,0.06);
            border: 1px solid rgba(110,231,183,0.15);
            border-radius: 6px;
            font-size: 10px;
            color: var(--text-muted);
            margin-bottom: 8px;
            letter-spacing: 0.04em;
        }
    </style>
</head>
<body>
    <x-app-nav active="colocation" />

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
                    <a href="{{ route('colocations.show', $colocation) }}" style="font-size:11px; color:var(--text-muted); text-decoration:none; transition:color 0.15s;" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--text-muted)'">{{ $colocation->name }}</a>
                    <span style="color:var(--border-hover);">/</span>
                    <span style="font-size:11px; color:var(--text-dim);">Modifier</span>
                </div>
                <div class="page-label">Modification</div>
                <h1 class="page-title" style="font-size:36px;">Modifier la Colocation</h1>
                <p class="page-sub">Mettez à jour les informations de votre colocation.</p>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-title">
                        <span style="margin-right:10px;">✏️</span> Modifier {{ $colocation->name }}
                    </div>
                    <p class="form-card-desc">
                        Modifiez le nom et l'adresse de votre colocation. Le code d'invitation reste inchangé.
                    </p>
                </div>

                <form method="POST" action="{{ route('colocations.update', $colocation) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-card-body">
                        <div class="form-group">
                            <label class="form-label" for="name">
                                Nom de la colocation
                            </label>
                            <div class="current-value">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Actuel : {{ $colocation->name }}
                            </div>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                class="form-input"
                                value="{{ old('name', $colocation->name) }}"
                                required
                                autofocus
                            >
                            @error('name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="adresse">
                                Adresse complète
                            </label>
                            <div class="current-value">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Actuel : {{ $colocation->adresse }}
                            </div>
                            <input
                                id="adresse"
                                type="text"
                                name="adresse"
                                class="form-input"
                                value="{{ old('adresse', $colocation->adresse) }}"
                                required
                            >
                            @error('adresse')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Invite Code Display -->
                        <div style="padding: 16px; background: var(--surface2); border: 1px solid var(--border); border-radius: 12px;">
                            <div style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:var(--text-muted); margin-bottom:10px;">Code d'invitation (non modifiable)</div>
                            <div class="invite-code">{{ $colocation->invitation_code }}</div>
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
