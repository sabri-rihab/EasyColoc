<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EasyColoc — Mon Profil</title>
    <x-app-styles />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .profile-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 24px;
            align-items: start;
        }

        /* Side Profile Card */
        .profile-identity {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            animation: fadeUp 0.5s ease both;
            position: sticky;
            top: 80px;
        }

        .profile-banner {
            height: 80px;
            background: linear-gradient(135deg, rgba(129,140,248,0.3), rgba(110,231,183,0.2));
            position: relative;
        }

        .profile-avatar-wrap {
            position: absolute;
            bottom: -24px;
            left: 24px;
        }

        .profile-avatar-lg {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent2), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: var(--bg);
            border: 3px solid var(--surface);
            box-shadow: 0 4px 16px -4px rgba(0,0,0,0.4);
        }

        .profile-identity-body {
            padding: 36px 24px 24px;
        }

        .profile-name {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .profile-email {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.04em;
            margin-bottom: 16px;
        }

        .profile-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 20px;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .profile-stat {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px;
        }

        .profile-stat-val {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 3px;
        }

        .profile-stat-lbl {
            font-size: 9px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        /* Main panel */
        .profile-sections {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .section-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            animation: fadeUp 0.5s ease both;
        }
        .section-card:nth-child(1) { animation-delay: 0.05s; }
        .section-card:nth-child(2) { animation-delay: 0.1s; }
        .section-card:nth-child(3) { animation-delay: 0.15s; }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
        }

        .section-title-text {
            flex: 1;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .section-desc {
            font-size: 10px;
            color: var(--text-muted);
            letter-spacing: 0.04em;
            margin-top: 2px;
        }

        .section-body {
            padding: 24px;
        }

        .fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .field-full { grid-column: 1 / -1; }

        .section-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            background: var(--surface2);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .save-flash {
            font-size: 11px;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Danger zone */
        .danger-section {
            background: rgba(248,113,113,0.03);
            border: 1px solid rgba(248,113,113,0.12);
            border-radius: 16px;
            overflow: hidden;
            animation: fadeUp 0.5s 0.2s ease both;
        }

        .danger-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid rgba(248,113,113,0.1);
        }

        .danger-body {
            padding: 20px 24px;
        }

        .danger-text {
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.8;
            letter-spacing: 0.03em;
            margin-bottom: 16px;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .modal-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            box-shadow: 0 24px 60px -12px rgba(0,0,0,0.7);
            animation: fadeUp 0.3s ease both;
        }

        .modal-header {
            padding: 24px 28px 20px;
            border-bottom: 1px solid var(--border);
        }

        .modal-title {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: var(--danger);
            margin-bottom: 6px;
        }

        .modal-sub {
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .modal-body { padding: 24px 28px; }

        .modal-footer {
            padding: 16px 28px;
            border-top: 1px solid var(--border);
            background: var(--surface2);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }
    </style>
</head>
<body>
    <x-app-nav active="profile" />

    <main>
        <!-- Page Header -->
        <div class="page-header" style="margin-bottom:32px;">
            <div class="page-label">Paramètres</div>
            <h1 class="page-title">Mon Profil</h1>
            <p class="page-sub">Gérez vos informations personnelles et la sécurité de votre compte.</p>
        </div>

        <div class="profile-layout">
            <!-- Identity Card (Sidebar) -->
            <div class="profile-identity">
                <div class="profile-banner">
                    <div class="profile-avatar-wrap">
                        <div class="profile-avatar-lg">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                    </div>
                </div>

                <div class="profile-identity-body">
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-email">{{ $user->email }}</div>

                    <div class="profile-badges">
                        @if($user->is_global_admin)
                            <span class="role-badge admin">Global Admin</span>
                        @endif
                        @if($user->hasActiveColocation())
                            <span class="role-badge owner">
                                {{ $user->currentColocation()->isOwner($user) ? 'Owner' : 'Member' }}
                            </span>
                        @endif
                        @if($user->is_banned)
                            <span class="role-badge banned">Banni</span>
                        @endif
                    </div>

                    <div class="profile-stats">
                        <div class="profile-stat">
                            <div class="profile-stat-val" style="color:var(--accent2);">{{ $user->reputation >= 0 ? '+' : '' }}{{ $user->reputation }}</div>
                            <div class="profile-stat-lbl">Réputation</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-val" style="color:var(--accent);">
                                {{ $user->hasActiveColocation() ? '1' : '0' }}
                            </div>
                            <div class="profile-stat-lbl">Coloc active</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sections -->
            <div class="profile-sections">

                {{-- 1. Profile Info --}}
                <div class="section-card">
                    <div class="section-header">
                        <div class="card-icon blue">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div class="section-title-text">
                            <div class="section-title">Informations du profil</div>
                            <div class="section-desc">Mettez à jour votre nom et votre adresse email.</div>
                        </div>
                    </div>

                    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">@csrf</form>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="section-body">
                            <div class="fields-grid">
                                <div class="form-group field-full">
                                    <label class="form-label" for="name">Nom complet</label>
                                    <input id="name" type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required autocomplete="name">
                                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group field-full">
                                    <label class="form-label" for="email">Adresse email</label>
                                    <input id="email" type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="username">
                                    @error('email') <div class="form-error">{{ $message }}</div> @enderror

                                    @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                        <div style="margin-top:8px; padding:10px 14px; background:rgba(251,146,60,0.08); border:1px solid rgba(251,146,60,0.2); border-radius:8px; font-size:11px; color:var(--accent4);">
                                            Email non vérifié.
                                            <button form="send-verification" style="color:var(--accent2);background:none;border:none;cursor:pointer;font-family:'DM Mono',monospace;font-size:11px;text-decoration:underline;">
                                                Renvoyer le lien
                                            </button>
                                            @if(session('status') === 'verification-link-sent')
                                                <div style="margin-top:4px; color:var(--accent);">Email de vérification envoyé !</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="section-footer">
                            <button type="submit" class="btn-primary" style="padding:9px 20px;font-size:12px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                Sauvegarder
                            </button>
                            @if(session('status') === 'profile-updated')
                                <div class="save-flash" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)" x-transition>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Modifications sauvegardées
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- 2. Password --}}
                <div class="section-card">
                    <div class="section-header">
                        <div class="card-icon green">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        </div>
                        <div class="section-title-text">
                            <div class="section-title">Mot de passe</div>
                            <div class="section-desc">Utilisez un mot de passe long et aléatoire pour plus de sécurité.</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="section-body">
                            <div style="display:flex;flex-direction:column;gap:16px;">
                                <div class="form-group">
                                    <label class="form-label" for="current_password">Mot de passe actuel</label>
                                    <input id="current_password" name="current_password" type="password" class="form-input" placeholder="••••••••" autocomplete="current-password">
                                    @error('current_password', 'updatePassword') <div class="form-error">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="new_password">Nouveau mot de passe</label>
                                    <input id="new_password" name="password" type="password" class="form-input" placeholder="Minimum 8 caractères" autocomplete="new-password">
                                    @error('password', 'updatePassword') <div class="form-error">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="password_confirmation">Confirmer le nouveau mot de passe</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-input" placeholder="••••••••" autocomplete="new-password">
                                    @error('password_confirmation', 'updatePassword') <div class="form-error">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="section-footer">
                            <button type="submit" class="btn-primary" style="padding:9px 20px;font-size:12px;background:linear-gradient(135deg,#6ee7b7,#818cf8);">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                Changer le mot de passe
                            </button>
                            @if(session('status') === 'password-updated')
                                <div class="save-flash" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)" x-transition>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Mot de passe mis à jour
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- 3. Danger Zone --}}
                <div class="danger-section">
                    <div class="danger-header">
                        <div class="card-icon red">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        </div>
                        <div class="section-title-text">
                            <div class="section-title" style="color:var(--danger);">Zone de Danger</div>
                            <div class="section-desc">Actions irréversibles sur votre compte.</div>
                        </div>
                    </div>

                    <div class="danger-body">
                        <p class="danger-text">
                            Une fois votre compte supprimé, toutes vos données et ressources seront <strong style="color:var(--danger)">définitivement effacées</strong>. Assurez-vous d'avoir exporté toutes les informations importantes avant de procéder.
                        </p>

                        <button
                            type="button"
                            class="btn-danger"
                            onclick="document.getElementById('delete-modal').style.display='flex'"
                        >
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                            Supprimer mon compte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Delete Modal --}}
    <div id="delete-modal" style="display:none;" class="modal-overlay" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">
                    ⚠️ Supprimer le compte
                </div>
                <div class="modal-sub">
                    Cette action est <strong style="color:var(--danger)">irréversible</strong>. Toutes vos données seront définitivement supprimées. Entrez votre mot de passe pour confirmer.
                </div>
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" for="delete-password">Votre mot de passe</label>
                        <input
                            id="delete-password"
                            name="password"
                            type="password"
                            class="form-input"
                            placeholder="Confirmez avec votre mot de passe"
                            style="border-color:rgba(248,113,113,0.3)"
                        >
                        @error('password', 'userDeletion')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn-secondary"
                        onclick="document.getElementById('delete-modal').style.display='none'"
                    >
                        Annuler
                    </button>
                    <button type="submit" class="btn-danger">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                        Oui, supprimer définitivement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-open modal if there are deletion errors
        @if($errors->userDeletion->isNotEmpty())
            document.getElementById('delete-modal').style.display = 'flex';
        @endif
    </script>
</body>
</html>
