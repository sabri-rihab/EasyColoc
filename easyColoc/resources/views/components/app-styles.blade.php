<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

<style>
    :root {
        --bg: #f8fafc;
        --surface: #ffffff;
        --surface2: #f1f5f9;
        --surface3: #e2e8f0;
        --border: rgba(0,0,0,0.1);
        --border-hover: rgba(0,0,0,0.2);
        --accent: #10b981;
        --accent2: #6366f1;
        --accent3: #db2777;
        --accent4: #f97316;
        --text: #0a0a0f;
        --text-muted: rgba(10,10,15,0.65);
        --text-dim: rgba(10,10,15,0.8);
        --danger: #dc2626;
        --success: #059669;
        --warning: #d97706;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

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
            linear-gradient(rgba(110,231,183,0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(110,231,183,0.05) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
        z-index: 0;
    }

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

    /* ─── NAV ─── */
    nav {
        position: sticky;
        top: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 40px;
        height: 64px;
        background: rgba(255,255,255,0.9);
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
        transition: color 0.2s;
    }
    .nav-logo:hover { color: var(--accent); }

    .nav-logo-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--accent2), var(--accent));
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-menu {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-left: 16px;
    }

    .nav-menu-item {
        font-family: 'Syne', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-dim);
        text-decoration: none;
        padding: 7px 14px;
        border-radius: 8px;
        transition: all 0.15s;
        letter-spacing: -0.01em;
    }
    .nav-menu-item:hover { color: var(--accent); background: rgba(0,0,0,0.04); }
    .nav-menu-item.active { color: var(--accent); background: rgba(16,185,129,0.08); }

    .nav-right {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
    }

    .nav-user {
        font-size: 12px;
        color: var(--text-dim);
        letter-spacing: 0.04em;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 8px;
        transition: background 0.15s;
        background: none;
        border: none;
        font-family: 'DM Mono', monospace;
    }
    .nav-user:hover { background: rgba(0,0,0,0.05); color: var(--text); }

    .nav-avatar {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--accent2), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Syne', sans-serif;
        font-size: 10px;
        font-weight: 800;
        color: var(--bg);
        flex-shrink: 0;
    }

    .notif-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 18px;
        height: 18px;
        padding: 0 4px;
        background: var(--danger);
        color: white;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        font-family: 'Syne', sans-serif;
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

    /* ─── DROPDOWN ─── */
    .relative { position: relative; }

    .dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 220px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 6px;
        box-shadow: 0 16px 40px -8px rgba(0,0,0,0.1), 0 0 0 1px rgba(0,0,0,0.04);
        z-index: 1000;
    }

    .dropdown-section-title {
        padding: 8px 12px 6px;
        font-size: 9px;
        letter-spacing: 0.15em;
        color: var(--text-muted);
        text-transform: uppercase;
        border-bottom: 1px solid var(--border);
        margin-bottom: 4px;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
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
        letter-spacing: 0.02em;
    }
    .dropdown-item:hover { background: rgba(0,0,0,0.04); color: var(--text); }
    .dropdown-item svg { width: 15px; height: 15px; stroke: currentColor; flex-shrink: 0; }

    .dropdown-divider { height: 1px; background: var(--border); margin: 6px 0; }

    .invitation-item {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        padding: 10px 12px;
        border-radius: 8px;
        transition: background 0.15s;
    }
    .invitation-item:hover { background: rgba(0,0,0,0.03); }

    .invitation-icon {
        width: 28px;
        height: 28px;
        background: rgba(110,231,183,0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    /* ─── MAIN ─── */
    main {
        position: relative;
        z-index: 1;
        max-width: 1280px;
        margin: 0 auto;
        padding: 48px 40px 80px;
    }

    /* ─── PAGE HEADER ─── */
    .page-header { margin-bottom: 40px; animation: fadeUp 0.5s ease both; }
    .page-label {
        font-size: 11px;
        letter-spacing: 0.15em;
        color: var(--accent);
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .page-title {
        font-family: 'Syne', sans-serif;
        font-size: 42px;
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1;
        color: var(--text);
    }
    .page-sub {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 8px;
        letter-spacing: 0.04em;
    }

    /* ─── CARDS ─── */
    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .card:hover { border-color: var(--border-hover); }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
    }

    .card-title {
        font-family: 'Syne', sans-serif;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: -0.01em;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card-icon.green { background: rgba(110,231,183,0.12); }
    .card-icon.blue { background: rgba(129,140,248,0.12); }
    .card-icon.purple { background: rgba(244,114,182,0.12); }
    .card-icon.orange { background: rgba(251,146,60,0.12); }
    .card-icon.red { background: rgba(248,113,113,0.12); }

    .card-body { padding: 24px; }

    /* ─── STAT CARDS ─── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 22px;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s, transform 0.2s;
        animation: fadeUp 0.5s ease both;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top left, var(--card-glow, transparent) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
    }
    .stat-card:hover { border-color: var(--border-hover); transform: translateY(-2px); }
    .stat-card:hover::before { opacity: 1; }

    .stat-card.blue { --card-glow: rgba(129,140,248,0.12); }
    .stat-card.green { --card-glow: rgba(110,231,183,0.12); }
    .stat-card.purple { --card-glow: rgba(244,114,182,0.12); }
    .stat-card.red { --card-glow: rgba(248,113,113,0.12); }
    .stat-card.orange { --card-glow: rgba(251,146,60,0.12); }

    .stat-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-icon.blue { background: rgba(129,140,248,0.12); }
    .stat-icon.green { background: rgba(110,231,183,0.12); }
    .stat-icon.purple { background: rgba(244,114,182,0.12); }
    .stat-icon.red { background: rgba(248,113,113,0.12); }
    .stat-icon.orange { background: rgba(251,146,60,0.12); }

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
        font-size: 36px;
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-card.blue .stat-value { color: var(--accent2); }
    .stat-card.green .stat-value { color: var(--accent); }
    .stat-card.purple .stat-value { color: var(--accent3); }
    .stat-card.red .stat-value { color: var(--danger); }
    .stat-card.orange .stat-value { color: var(--accent4); }

    .stat-label {
        font-size: 10px;
        letter-spacing: 0.12em;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    /* ─── TABLE ─── */
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
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
    }

    .table-title {
        font-family: 'Syne', sans-serif;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: -0.01em;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-meta {
        font-size: 10px;
        color: var(--text-muted);
        letter-spacing: 0.05em;
    }

    table { width: 100%; border-collapse: collapse; }

    thead th {
        padding: 10px 24px;
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
    tbody tr:hover { background: rgba(0,0,0,0.02); }

    td {
        padding: 14px 24px;
        font-size: 12px;
        vertical-align: middle;
    }

    /* ─── BADGES & PILLS ─── */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .role-badge.owner {
        background: rgba(129,140,248,0.1);
        border: 1px solid rgba(129,140,248,0.25);
        color: var(--accent2);
    }
    .role-badge.member {
        background: rgba(110,231,183,0.08);
        border: 1px solid rgba(110,231,183,0.2);
        color: var(--accent);
    }
    .role-badge.admin {
        background: rgba(251,146,60,0.1);
        border: 1px solid rgba(251,146,60,0.2);
        color: var(--accent4);
    }
    .role-badge.banned {
        background: rgba(248,113,113,0.08);
        border: 1px solid rgba(248,113,113,0.2);
        color: var(--danger);
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
    .status-badge.active { background: rgba(110,231,183,0.08); border: 1px solid rgba(110,231,183,0.2); color: var(--accent); }
    .status-badge.inactive { background: rgba(248,113,113,0.08); border: 1px solid rgba(248,113,113,0.2); color: var(--danger); }
    .status-badge .dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

    /* ─── BUTTONS ─── */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--accent2), var(--accent));
        border: none;
        border-radius: 10px;
        color: #ffffff;
        font-family: 'Syne', sans-serif;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 20px -6px rgba(129,140,248,0.5); }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        background: rgba(0,0,0,0.03);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-dim);
        font-family: 'DM Mono', monospace;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
    }
    .btn-secondary:hover { border-color: var(--border-hover); color: var(--text); background: rgba(0,0,0,0.06); }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        background: rgba(248,113,113,0.08);
        border: 1px solid rgba(248,113,113,0.2);
        border-radius: 10px;
        color: var(--danger);
        font-family: 'DM Mono', monospace;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
    }
    .btn-danger:hover { background: rgba(248,113,113,0.15); border-color: rgba(248,113,113,0.4); }

    .btn-xs-green {
        padding: 4px 10px;
        border-radius: 6px;
        background: rgba(110,231,183,0.1);
        border: 1px solid rgba(110,231,183,0.25);
        color: var(--accent);
        font-family: 'DM Mono', monospace;
        font-size: 10px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-xs-green:hover { background: rgba(110,231,183,0.2); }

    .btn-xs-red {
        padding: 4px 10px;
        border-radius: 6px;
        background: rgba(248,113,113,0.08);
        border: 1px solid rgba(248,113,113,0.2);
        color: var(--danger);
        font-family: 'DM Mono', monospace;
        font-size: 10px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-xs-red:hover { background: rgba(248,113,113,0.18); }

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
    .btn-edit:hover { background: rgba(129,140,248,0.2); border-color: rgba(129,140,248,0.4); }

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
    .btn-ban:hover { background: rgba(248,113,113,0.18); border-color: rgba(248,113,113,0.4); }

    /* ─── FORM ELEMENTS ─── */
    .form-group { margin-bottom: 20px; }

    .form-label {
        display: block;
        font-size: 10px;
        letter-spacing: 0.12em;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 11px 16px;
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text);
        font-family: 'DM Mono', monospace;
        font-size: 13px;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }
    .form-input:focus {
        border-color: rgba(129,140,248,0.4);
        box-shadow: 0 0 0 3px rgba(129,140,248,0.1);
    }
    .form-input::placeholder { color: var(--text-muted); }

    .form-error {
        margin-top: 6px;
        font-size: 11px;
        color: var(--danger);
    }

    /* ─── ALERTS ─── */
    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: fadeUp 0.3s ease both;
    }
    .alert.success {
        background: rgba(110,231,183,0.08);
        border: 1px solid rgba(110,231,183,0.2);
        color: var(--accent);
    }
    .alert.error {
        background: rgba(248,113,113,0.08);
        border: 1px solid rgba(248,113,113,0.2);
        color: var(--danger);
    }
    .alert.warning {
        background: rgba(251,146,60,0.08);
        border: 1px solid rgba(251,146,60,0.2);
        color: var(--accent4);
    }

    /* ─── AVATAR ─── */
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
        color: #ffffff;
        flex-shrink: 0;
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

    /* ─── REP ─── */
    .rep-cell { display: flex; align-items: center; gap: 10px; }
    .rep-val { font-size: 13px; color: var(--text); min-width: 24px; }
    .rep-bar-bg { width: 60px; height: 3px; background: rgba(0,0,0,0.08); border-radius: 4px; overflow: hidden; }
    .rep-bar-fill { height: 100%; background: linear-gradient(90deg, var(--accent2), var(--accent)); border-radius: 4px; }

    /* ─── ID CELL ─── */
    .id-cell { color: var(--text-muted); font-size: 11px; letter-spacing: 0.05em; }
    .user-cell { display: flex; align-items: center; gap: 12px; }
    .user-name { font-size: 13px; font-weight: 500; color: var(--text); }
    .email-cell { color: var(--text-dim); font-size: 11px; }

    /* ─── GRID LAYOUTS ─── */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .section-gap { margin-bottom: 24px; }

    /* ─── DIVIDER ─── */
    .section-divider {
        height: 1px;
        background: var(--border);
        margin: 28px 0;
    }

    /* ─── CODE / INVITE ─── */
    .invite-code {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 16px;
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-family: 'DM Mono', monospace;
        font-size: 14px;
        letter-spacing: 0.1em;
        color: var(--accent);
    }

    /* ─── EMPTY STATE ─── */
    .empty-state {
        text-align: center;
        padding: 60px 24px;
        color: var(--text-muted);
    }
    .empty-state-icon {
        font-size: 40px;
        margin-bottom: 16px;
    }
    .empty-state-title {
        font-family: 'Syne', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dim);
        margin-bottom: 8px;
    }
    .empty-state-text {
        font-size: 12px;
        letter-spacing: 0.04em;
        line-height: 1.8;
    }

    /* ─── DANGER ZONE ─── */
    .danger-zone {
        background: rgba(248,113,113,0.04);
        border: 1px solid rgba(248,113,113,0.15);
        border-radius: 16px;
        padding: 24px;
        margin-top: 24px;
    }
    .danger-zone-title {
        font-family: 'Syne', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: var(--danger);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .danger-zone-text {
        font-size: 11px;
        color: var(--text-muted);
        line-height: 1.7;
        margin-bottom: 16px;
    }

    /* ─── ANIMATIONS ─── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(0.8); }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    svg { display: block; }

    [x-cloak] { display: none !important; }
</style>
