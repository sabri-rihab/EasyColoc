@props(['active' => 'dashboard'])

<nav>
    <div style="display: flex; align-items: center; gap: 32px;">
        <a href="{{ route('dashboard') }}" class="nav-logo">
            <div class="nav-logo-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            EasyColoc
        </a>

        <div class="nav-menu">
            <a href="{{ route('dashboard') }}" class="nav-menu-item {{ $active === 'dashboard' ? 'active' : '' }}">
                Dashboard
            </a>
            @if(auth()->user()->hasActiveColocation())
                <a href="{{ route('colocations.show', auth()->user()->currentColocation()) }}" class="nav-menu-item {{ $active === 'colocation' ? 'active' : '' }}">
                    Ma Colocation
                </a>
            @endif
            @if(auth()->user()->is_global_admin)
                <a href="{{ route('admin.dashboard') }}" class="nav-menu-item {{ $active === 'admin' ? 'active' : '' }}">
                    Admin
                </a>
            @endif
        </div>
    </div>

    <div class="nav-right">
        {{-- Invitations Dropdown --}}
        <div class="relative" x-data="{ openNotifications: false }">
            @php $pendingInvitations = Auth::user()->pendingInvitations()->with('colocation')->get(); @endphp
            <button @click="openNotifications = !openNotifications" class="nav-user relative" style="display:flex;align-items:center;gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
                Invitations
                @if($pendingInvitations->count() > 0)
                    <span class="notif-badge">{{ $pendingInvitations->count() }}</span>
                @endif
            </button>

            <div x-show="openNotifications" @click.away="openNotifications = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="dropdown-menu" style="width: 280px;" x-cloak>
                <div class="dropdown-section-title">Invitations en attente</div>
                @forelse($pendingInvitations as $pInvite)
                    @if(is_object($pInvite))
                    <div class="invitation-item">
                        <div class="invitation-icon">🏠</div>
                        <div style="flex:1;">
                            <p style="font-size: 11px; color: var(--text); margin-bottom: 6px;">
                                Rejoindre <strong style="color:var(--accent);">{{ $pInvite->colocation->name ?? 'Colocation' }}</strong>
                            </p>
                            <div style="display: flex; gap: 6px;">
                                <form method="POST" action="{{ route('invitations.accept', $pInvite) }}">
                                    @csrf
                                    <button type="submit" class="btn-xs-green">Accepter</button>
                                </form>
                                <form method="POST" action="{{ route('invitations.reject', $pInvite) }}">
                                    @csrf
                                    <button type="submit" class="btn-xs-red">Refuser</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                @empty
                    <div style="padding: 16px; text-align: center; font-size: 11px; color: var(--text-muted);">
                        Aucune invitation en attente
                    </div>
                @endforelse
            </div>
        </div>

        {{-- User Dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="nav-user" style="display:flex;align-items:center;gap:8px;">
                <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                {{ auth()->user()->name }}
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>

            <div x-show="open" @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="dropdown-menu" x-cloak>
                <div style="padding: 12px 16px; border-bottom: 1px solid var(--border);">
                    <div style="font-size: 13px; color: var(--text); font-weight: 600;">{{ auth()->user()->name }}</div>
                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">{{ auth()->user()->email }}</div>
                    @if(auth()->user()->is_global_admin)
                        <div style="margin-top: 6px;" class="role-badge admin">GLOBAL ADMIN</div>
                    @endif
                </div>

                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Mon Profil
                </a>
                @if(auth()->user()->is_global_admin)
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        Administration
                    </a>
                @endif

                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item" style="color: var(--danger);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Déconnexion
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
