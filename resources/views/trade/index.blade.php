<?php
/** * ==============================================================================
 * Vue Blade du Trading Workspace — Puprime Fox (VERSION V2 - INTÉGRATION DIRECTE)
 * ==============================================================================
 * 
 * V2 - NOUVELLE APPROCHE (ACTIVE):
 * Le HTML/CSS/JS du trade.html est directement intégré dans cette vue Blade.

 * ==============================================================================
 */

// ── CONFIGURATION DE BASE ────────────────────────────────────────────
// Construire le payload PHP → JS
$tradeConfig = [
    'demoBalance'   => (float) $wallet->demo_balance,
    'realBalance'   => (float) $wallet->balance,
    'marginUsed'    => (float) $wallet->margin_used,
    'openPositions' => $openPositions->toArray(),
    'closedHistory' => $closedHistory->toArray(),
    'user' => [
        'name'   => $user->first_name ?? $user->name ?? 'User',
        'email'  => $user->email ?? '',
        'avatar' => strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 2)),
        'id'     => $user->id,
    ],
    'botState' => cache()->get('user_' . $user->id . '_bot_state', [
        'bot_active'      => false,
        'bot_activated_at'=> null,
        'account_type'    => 'demo',
    ]),
    'routes'        => [
        'openPosition'  => route('trade.position.open'),
        'closePosition' => route('trade.position.close', ['id' => '__ID__']),
        'getPositions'  => route('trade.positions.json'),
        'getHistory'    => route('trade.history.json'),
        'getBalance'    => route('trade.balance.json'),
        'updateBalance' => route('trade.balance.update'),
        'foxbot'        => route('trade.foxbot'),
        'coinGeckoPrice' => route('trade.api.coingecko.price'),
        'coinGeckoOHLC'  => route('trade.api.coingecko.ohlc'),
    ],
    'csrfToken' => csrf_token(),
];

// ── Injection dans <head> ───────────────────────────────────────────────
$userInitial = strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 1));
$userAvatar = $user->avatar_url ?? null;
$userColors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E2'];
$userColorIndex = (crc32($user->id) % count($userColors));
$userAvatarBg = $userColors[$userColorIndex];

$headInjection = '<meta name="csrf-token" content="' . $tradeConfig['csrfToken'] . '">' . "\n"
    . '<script>window.__TRADE = ' . json_encode($tradeConfig) . ';</script>' . "\n"
    . '<style>
/* ═════════════════════════════════════════════════════════════════════
   RESPONSIVE DESIGN — Mobile First Approach
   ═════════════════════════════════════════════════════════════════════ */

/* Base responsive container */
.trade-container, main, .workspace, [class*="workspace"], [class*="container"] {
    width: 100%;
    max-width: 100vw !important;
    overflow-x: hidden;
    padding: 0 !important;
    margin: 0 !important;
}

/* Mobile-first grid system */
.grid-responsive, [class*="grid"], .layout {
    display: grid;
    grid-auto-flow: row;
    gap: 0.5rem;
}

/* Breakpoints */
@media (min-width: 640px) {
    .grid-responsive, [class*="grid"] {
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
}

@media (min-width: 1024px) {
    .grid-responsive, [class*="grid"] {
        grid-template-columns: 1fr 2fr 1fr;
        gap: 1.5rem;
    }
}

@media (min-width: 1280px) {
    .grid-responsive, [class*="grid"] {
        grid-template-columns: 1fr 3fr 1fr;
        gap: 2rem;
    }
}

/* Sidebar responsiveness */
[class*="sidebar"], [class*="panel"], .side-panel {
    width: 100% !important;
    max-width: 100% !important;
    min-height: auto !important;
    margin-bottom: 1rem;
}

@media (min-width: 768px) {
    [class*="sidebar"], [class*="panel"], .side-panel {
        width: 250px !important;
        margin-right: 1rem;
        margin-bottom: 0;
        display: block !important;
    }
}

@media (min-width: 1024px) {
    [class*="sidebar"], [class*="panel"], .side-panel {
        width: 280px !important;
    }
}

/* Tables responsiveness */
table, [class*="table"] {
    width: 100% !important;
    font-size: 0.75rem;
    overflow-x: auto !important;
    display: block;
}

@media (min-width: 640px) {
    table, [class*="table"] {
        font-size: 0.875rem;
        display: table;
    }
}

@media (min-width: 1024px) {
    table, [class*="table"] {
        font-size: 1rem;
    }
}

/* Forms responsiveness */
form, [class*="form"], input, select, textarea, button {
    width: 100%;
    max-width: 100%;
    padding: 0.5rem;
    font-size: 1rem;
    line-height: 1.5;
}

@media (min-width: 768px) {
    input, select, textarea, button {
        padding: 0.75rem;
    }
}

/* Chart containers */
canvas, [class*="chart"], [id*="chart"], [id*="Chart"] {
    width: 100% !important;
    height: auto !important;
    min-height: 200px;
    max-height: 100%;
}

@media (max-height: 600px) {
    canvas, [class*="chart"], [id*="chart"], [id*="Chart"] {
        min-height: 150px;
    }
}

@media (min-height: 800px) {
    canvas, [class*="chart"], [id*="chart"], [id*="Chart"] {
        min-height: 300px;
    }
}

/* Typography responsiveness */
h1, [class*="h1"], [class*="title-lg"] { font-size: 1.25rem; }
h2, [class*="h2"], [class*="title-md"] { font-size: 1.125rem; }
h3, [class*="h3"], [class*="title-sm"] { font-size: 1rem; }
p, [class*="body"], span { font-size: 0.875rem; }
small, [class*="small"], [class*="text-xs"] { font-size: 0.75rem; }

@media (min-width: 768px) {
    h1, [class*="h1"], [class*="title-lg"] { font-size: 1.875rem; }
    h2, [class*="h2"], [class*="title-md"] { font-size: 1.5rem; }
    h3, [class*="h3"], [class*="title-sm"] { font-size: 1.25rem; }
    p, [class*="body"], span { font-size: 1rem; }
    small, [class*="small"], [class*="text-xs"] { font-size: 0.875rem; }
}

/* Flex responsiveness */
.flex-responsive, [class*="flex"] {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

@media (min-width: 640px) {
    .flex-responsive-row, [class*="flex-row"] {
        flex-direction: row;
        gap: 1rem;
    }
}

/* Spacing responsiveness */
.px { padding-left: 0.5rem; padding-right: 0.5rem; }
.py { padding-top: 0.5rem; padding-bottom: 0.5rem; }
.mx { margin-left: 0.5rem; margin-right: 0.5rem; }
.my { margin-top: 0.5rem; margin-bottom: 0.5rem; }

@media (min-width: 768px) {
    .px { padding-left: 1rem; padding-right: 1rem; }
    .py { padding-top: 1rem; padding-bottom: 1rem; }
    .mx { margin-left: 1rem; margin-right: 1rem; }
    .my { margin-top: 1rem; margin-bottom: 1rem; }
}

/* Hide/show elements on breakpoints */
.hide-mobile { display: none; }
.hide-tablet { }
.hide-desktop { }

@media (min-width: 640px) {
    .hide-tablet { display: none; }
    .hide-mobile { display: block; }
}

@media (min-width: 1024px) {
    .hide-desktop { display: none; }
    .hide-mobile { display: block; }
    .hide-tablet { display: block; }
}

/* Navigation responsiveness */
nav, [role="navigation"], [class*="nav"], .navbar {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    gap: 0.5rem;
}

@media (min-width: 768px) {
    nav, [role="navigation"], [class*="nav"], .navbar {
        padding: 1rem;
        gap: 1rem;
    }
}

/* Position/Badge responsiveness */
.badge, [class*="badge"], .tab-badge {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

@media (min-width: 768px) {
    .badge, [class*="badge"], .tab-badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
}

/* Button responsiveness */
button, [role="button"], [class*="btn"] {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    min-width: auto;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}

@media (min-width: 768px) {
    button, [role="button"], [class*="btn"] {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        min-width: 120px;
    }
}

/* Modal/Dialog responsiveness */
[role="dialog"], .modal, [class*="modal"] {
    width: 90vw !important;
    max-width: 90vw !important;
    max-height: 90vh !important;
    padding: 1rem;
}

@media (min-width: 640px) {
    [role="dialog"], .modal, [class*="modal"] {
        width: 80vw !important;
        max-width: 80vw !important;
    }
}

@media (min-width: 768px) {
    [role="dialog"], .modal, [class*="modal"] {
        width: 60vw !important;
        max-width: 600px !important;
    }
}

@media (min-width: 1024px) {
    [role="dialog"], .modal, [class*="modal"] {
        width: 50vw !important;
        max-width: 800px !important;
    }
}

/* Tabs responsiveness */
[role="tablist"], [class*="tabs"], .tab-container {
    display: flex;
    overflow-x: auto;
    gap: 0.5rem;
    padding: 0.5rem;
    -webkit-overflow-scrolling: touch;
}

@media (min-width: 768px) {
    [role="tablist"], [class*="tabs"], .tab-container {
        gap: 1rem;
        padding: 1rem;
    }
}

/* Scrollable containers */
[class*="scroll"], [class*="overflow"] {
    overflow: auto;
    -webkit-overflow-scrolling: touch;
}

[class*="scroll"]::-webkit-scrollbar,
[class*="overflow"]::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}

[class*="scroll"]::-webkit-scrollbar-track,
[class*="overflow"]::-webkit-scrollbar-track {
    background: transparent;
}

[class*="scroll"]::-webkit-scrollbar-thumb,
[class*="overflow"]::-webkit-scrollbar-thumb {
    background: rgba(0, 212, 170, 0.3);
    border-radius: 2px;
}

/* Touch-friendly targets (minimum 44x44px) */
button, a, input[type="button"], input[type="checkbox"], input[type="radio"] {
    min-height: 44px !important;
    min-width: 44px !important;
}

@media (min-width: 1024px) {
    button, a, input[type="button"], input[type="checkbox"], input[type="radio"] {
        min-height: auto;
        min-width: auto;
    }
}

/* Print responsiveness */
@media print {
    nav, [class*="nav"], [role="navigation"], .navbar {
        display: none;
    }
    body {
        margin: 0;
        padding: 0;
    }
    [class*="container"], .workspace {
        max-width: 100%;
    }
}
    .user-profile-dropdown {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 6px;
            background: rgba(0, 212, 170, 0.08);
            border: 1px solid rgba(0, 212, 170, 0.2);
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .user-profile-dropdown:hover {
            background: rgba(0, 212, 170, 0.15);
            border-color: rgba(0, 212, 170, 0.4);
        }
        .user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 12px;
            color: white;
            flex-shrink: 0;
            background: ' . $userAvatarBg . ';
        }
        .user-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .user-info {
            display: flex;
            flex-direction: column;
            gap: 1px;
            min-width: 0;
        }
        .user-name {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-email {
            font-size: 9px;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 4px;
            background: var(--bg-dark, #1a1a2e);
            border: 1px solid rgba(0, 212, 170, 0.3);
            border-radius: 6px;
            min-width: 180px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s ease;
        }
        .user-profile-dropdown.active .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: var(--text-primary, #fff);
            text-decoration: none;
            font-size: 13px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.15s ease;
        }
        .dropdown-menu a:last-child {
            border-bottom: none;
        }
        .dropdown-menu a:hover {
            background: rgba(0, 212, 170, 0.1);
            color: #00d4aa;
        }
        .live-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 4px;
            background: rgba(0, 212, 170, 0.15);
            border: 1px solid rgba(0, 212, 170, 0.3);
            font-size: 11px;
            font-weight: 600;
            color: #00d4aa;
            flex-shrink: 0;
            white-space: nowrap;
        }
        .live-badge span:first-child {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #00d4aa;
            animation: pulse-live 1.5s ease-in-out infinite;
            flex-shrink: 0;
        }
        .live-badge span:last-child {
            white-space: nowrap;
        }
        @keyframes pulse-live {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>' . "\n";

// ── Chart fix CSS (canvas min-height, force resize) ──────────────────
$headInjection .= '<style>canvas{min-height:80px !important}[class*="chart"] canvas,[id*="chart"] canvas,[id*="Chart"] canvas{min-height:100px !important;height:100% !important}.pnl-chart,.chart-container,.chart-wrap{min-height:120px !important}.bot-active-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:linear-gradient(135deg,rgba(0,212,170,.2),rgba(0,212,170,.1));border:1.5px solid rgba(0,212,170,.4);border-radius:6px;font-size:12px;font-weight:600;color:#00d4aa;animation:bot-pulse 2s ease-in-out infinite}.bot-active-badge::before{content:\"\1F916\";width:10px;height:10px;border-radius:50%;background:#00d4aa;animation:bot-dot-pulse 2s ease-in-out infinite;flex-shrink:0}@keyframes bot-pulse{0%,100%{background:linear-gradient(135deg,rgba(0,212,170,.2),rgba(0,212,170,.1));box-shadow:0 0 10px rgba(0,212,170,.3)}50%{background:linear-gradient(135deg,rgba(0,212,170,.3),rgba(0,212,170,.2));box-shadow:0 0 20px rgba(0,212,170,.5)}}@keyframes bot-dot-pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(1.2)}}.bot-notification{position:fixed;top:80px;right:20px;background:linear-gradient(135deg,#0a2527 0%,#0d3a3e 100%);border:1px solid rgba(0,212,170,.3);border-radius:8px;padding:16px;min-width:300px;box-shadow:0 8px 32px rgba(0,212,170,.15);animation:slide-in-right .4s ease-out;z-index:9999;display:none}.bot-notification.active{display:block}.bot-notification-content{display:flex;align-items:flex-start;gap:12px}.bot-notification-icon{font-size:24px;animation:bot-icon-bounce .6s ease-in-out infinite;flex-shrink:0}.bot-notification-text{display:flex;flex-direction:column;gap:4px;flex:1}.bot-notification-title{font-size:13px;font-weight:600;color:#00d4aa}.bot-notification-message{font-size:12px;color:rgba(255,255,255,.7);line-height:1.4}.bot-notification-amount{font-size:14px;font-weight:700;color:#00d4aa}@keyframes slide-in-right{from{opacity:0;transform:translateX(400px)}to{opacity:1;transform:translateX(0)}}@keyframes slide-out-right{from{opacity:1;transform:translateX(0)}to{opacity:0;transform:translateX(400px)}}@keyframes bot-icon-bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}.tab-badge-bot{display:inline-flex;align-items:center;justify-content:center;min-width:22px;height:22px;padding:0 6px;background:linear-gradient(135deg,#00d4aa,#00b899);color:#0a0a0a;font-size:10px;font-weight:700;border-radius:11px;animation:bot-badge-pulse 2s ease-in-out infinite;margin-left:6px}@keyframes bot-badge-pulse{0%,100%{transform:scale(1);box-shadow:0 0 8px rgba(0,212,170,.4)}50%{transform:scale(1.08);box-shadow:0 0 16px rgba(0,212,170,.6)}}.position-bot-indicator{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;background:rgba(0,212,170,.1);border:1px solid rgba(0,212,170,.3);border-radius:4px;font-size:10px;color:#00d4aa;font-weight:600}.position-bot-indicator::before{content:\"\1F916 \";animation:bot-emoji-rotate 3s linear infinite}@keyframes bot-emoji-rotate{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}.form-bot-overlay{position:absolute;top:0;right:0;width:200px;height:100%;background:linear-gradient(90deg,transparent,rgba(0,212,170,.05));border-radius:0 8px 8px 0;display:flex;align-items:center;justify-content:center;font-size:32px;animation:bot-form-shimmer 3s ease-in-out infinite;pointer-events:none}@keyframes bot-form-shimmer{0%,100%{opacity:.1}50%{opacity:.3}}@media (max-width:1024px){.bot-notification{min-width:280px;right:10px}}@media (max-width:768px){.bot-notification{min-width:250px;right:5px;top:70px}.bot-active-badge{font-size:11px;padding:5px 10px}}</style>' . "\n";

// ── Chart fix CSS (canvas min-height, force resize) ──────────────────
$headInjection .= '<style>canvas{min-height:80px !important}[class*="chart"] canvas,[id*="chart"] canvas,[id*="Chart"] canvas{min-height:100px !important;height:100% !important}.pnl-chart,.chart-container,.chart-wrap{min-height:120px !important}</style>' . "\n";

// ── RESPONSIVE WORKSPACE — Mobile-First Override ──────────────────────
$headInjection .= '<style>
/* ═══════════════════════════════════════════════════════════
   RESPONSIVE TRADE WORKSPACE — XFT Final
   Mobile-first: sidebar right (FoxBot) toujours visible
   ═══════════════════════════════════════════════════════════ */

/* ── MOBILE : layout en colonne, tout empilé ── */
@media (max-width: 1023px) {
    body { overflow-y: auto !important; overflow-x: hidden !important; }

    .app-wrapper {
        display: flex !important;
        flex-direction: column !important;
        height: auto !important;
        min-height: 100vh !important;
        overflow: visible !important;
    }

    /* Topbar scrollable horizontalement */
    .topbar {
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        -webkit-overflow-scrolling: touch !important;
        padding: 0 10px !important;
        gap: 8px !important;
        min-height: 52px !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 200 !important;
        scrollbar-width: none !important;
    }
    .topbar::-webkit-scrollbar { display: none !important; }

    /* Cacher stats topbar non essentiels sur mobile */
    .topbar-stats { display: none !important; }
    .topbar-balance { gap: 6px !important; }
    .balance-block { min-width: 60px !important; }
    .topbar-divider { display: none !important; }

    /* Sidebars cachées sur mobile */
    .sidebar-left {
        display: none !important;
    }

    /* Zone principale prend toute la largeur */
    .main-area {
        width: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: visible !important;
        min-height: 60vh !important;
    }

    /* Toolbar du chart scrollable */
    .chart-toolbar {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        flex-wrap: nowrap !important;
        padding: 0 8px !important;
        scrollbar-width: none !important;
    }
    .chart-toolbar::-webkit-scrollbar { display: none !important; }

    /* Chart area adapté */
    .chart-area {
        min-height: 280px !important;
        height: 45vw !important;
        max-height: 380px !important;
        position: relative !important;
    }

    /* Bottom panel */
    .bottom-panel {
        min-height: auto !important;
        overflow: visible !important;
    }

    /* Tabs scrollables */
    .tabs {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        flex-wrap: nowrap !important;
        scrollbar-width: none !important;
        padding: 0 6px !important;
    }
    .tabs::-webkit-scrollbar { display: none !important; }
    .tab-btn {
        flex-shrink: 0 !important;
        font-size: 0.7rem !important;
        padding: 6px 10px !important;
        white-space: nowrap !important;
    }

    /* Tab content scrollable */
    .tab-content { min-height: 150px !important; overflow: visible !important; }
    .tab-pane { overflow-x: auto !important; -webkit-overflow-scrolling: touch !important; }

    /* Tables mobile */
    .tab-pane table { font-size: 0.68rem !important; }

    /* Sidebar droite (FoxBot + formulaire) : visible en bas sur mobile */
    .sidebar-right {
        width: 100% !important;
        max-width: 100% !important;
        border-left: none !important;
        border-top: 1px solid var(--border, rgba(255,255,255,0.1)) !important;
        overflow-y: visible !important;
        display: block !important;
    }

    /* FoxBot section toujours visible + bien stylée */
    .bot-section {
        margin: 8px 10px !important;
        border-radius: 10px !important;
        background: var(--bg-card, rgba(255,255,255,0.05)) !important;
        border: 1.5px solid var(--fox-orange, #FF8C42) !important;
        padding: 10px !important;
    }

    .bot-section-header {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
    }

    .bot-select-row {
        display: flex !important;
        gap: 8px !important;
        margin-top: 8px !important;
        align-items: center !important;
    }

    .bot-select { flex: 1 !important; min-width: 0 !important; }
    .bot-start-btn { flex-shrink: 0 !important; white-space: nowrap !important; }

    /* Status bar du bot visible */
    .bot-status-bar.active {
        display: flex !important;
        max-height: 60px !important;
        opacity: 1 !important;
    }

    /* Animations FoxBot conservées sur mobile */
    .bot-pulse-ring { display: block !important; }
    .bot-pulse-core { display: block !important; }
    .bot-scan-overlay.active { display: block !important; }
    .bot-ticker.show { display: flex !important; }

    /* Formulaire de trade visible */
    .order-form { padding: 10px !important; }

    .order-tabs { display: flex !important; gap: 6px !important; }

    .order-tab {
        flex: 1 !important;
        padding: 10px !important;
        font-size: 0.85rem !important;
    }

    .form-row { margin-bottom: 8px !important; }

    /* Account tabs */
    .account-mode-tabs {
        display: flex !important;
        gap: 6px !important;
        padding: 8px 10px !important;
    }

    .acc-tab-btn { flex: 1 !important; padding: 8px !important; font-size: 0.75rem !important; }

    /* Stats & indicators */
    .market-stats, .indicators-section, .pnl-tracker { padding: 8px 10px !important; }

    .stats-grid { grid-template-columns: repeat(2, 1fr) !important; }

    /* Instrument header */
    .instrument-header { padding: 8px 10px !important; }
    .instrument-bid, .instrument-ask { font-size: 1.1rem !important; }

    /* Risk estimator compact */
    .risk-estimator { font-size: 0.72rem !important; }

    /* Qty presets */
    .qty-slider { gap: 4px !important; }
    .qty-preset { padding: 4px 8px !important; font-size: 0.7rem !important; }

    /* Order button pleine largeur */
    .order-btn { width: 100% !important; padding: 14px !important; font-size: 1rem !important; }

    /* Modal responsive */
    .modal-box {
        width: 94vw !important;
        max-width: 94vw !important;
        margin: 10px !important;
    }

    /* User profile dropdown responsive */
    .user-profile-dropdown .dropdown-menu {
        right: -10px !important;
        left: auto !important;
        min-width: 160px !important;
        max-width: calc(100vw - 20px) !important;
    }
}

/* ── TABLETTE : layout deux colonnes ── */
@media (min-width: 768px) and (max-width: 1023px) {
    .app-wrapper {
        display: grid !important;
        grid-template-rows: 52px 1fr !important;
        grid-template-columns: 1fr !important;
        height: auto !important;
    }

    .main-area {
        grid-template-rows: 44px minmax(300px, 40vh) 220px !important;
    }

    .chart-area { height: 40vh !important; max-height: 400px !important; }

    /* Sidebar droite en grille 2 colonnes */
    .sidebar-right {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 10px !important;
        padding: 10px !important;
    }

    .bot-section { margin: 0 !important; }
    .order-form { padding: 0 !important; }
    .account-mode-tabs { grid-column: 1 / -1 !important; }
    .real-account-warning, .api-status { grid-column: 1 / -1 !important; }
    .instrument-header { grid-column: 1 / -1 !important; }
    .market-stats, .indicators-section, .pnl-tracker { grid-column: 1 !important; }
}

/* ── DESKTOP : layout original restauré ── */
@media (min-width: 1024px) {
    .app-wrapper {
        display: grid !important;
        grid-template-rows: 52px 1fr !important;
        grid-template-columns: 220px 1fr 290px !important;
        height: 100vh !important;
        overflow: hidden !important;
    }

    .topbar { overflow: hidden !important; }
    .sidebar-left { display: flex !important; }
    .main-area { display: grid !important; overflow: hidden !important; }
    .sidebar-right { overflow-y: auto !important; }
}

/* ── NOTIFICATION RESPONSIVE (toasts bot) ── */
.bot-notification {
    max-width: calc(100vw - 20px) !important;
}

/* ── FOXBOT ACTIVITY PANEL — mobile safe ── */
@media (max-width: 640px) {
    .bot-notification {
        left: 8px !important;
        right: 8px !important;
        min-width: 0 !important;
        width: calc(100vw - 16px) !important;
        top: 68px !important;
    }
    #foxbot-activity-panel {
        bottom: 8px !important;
        right: 8px !important;
        left: 8px !important;
        width: calc(100vw - 16px) !important;
        max-width: calc(100vw - 16px) !important;
        max-height: 60vh !important;
        border-radius: 16px !important;
    }
    #foxbot-active-banner {
        left: 50% !important;
        transform: translateX(-50%) !important;
        top: 60px !important;
        white-space: nowrap !important;
        font-size: 10px !important;
        padding: 4px 10px !important;
    }
    #foxbot-reconnect-toast {
        left: 8px !important;
        right: 8px !important;
        min-width: 0 !important;
        width: calc(100vw - 16px) !important;
        transform: none !important;
        top: 68px !important;
    }
    .dropdown-menu {
        right: 8px !important;
        left: 8px !important;
        width: calc(100vw - 16px) !important;
        max-width: calc(100vw - 16px) !important;
    }
}

/* ── WORKSPACE : signaux marché + tabs + formulaire ── */
@media (max-width: 1023px) {
    .signal-row, [class*="signal"], [class*="market-row"] {
        flex-wrap: wrap !important;
        gap: 4px !important;
        font-size: 0.7rem !important;
    }
    .market-watch-table, [class*="market-table"] {
        font-size: 0.68rem !important;
        min-width: 480px !important;
    }
    .tab-pane, [class*="panel-body"], [id*="tab-"] {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    .chart-section, [class*="chart-wrap"], [id*="chartContainer"], [id*="chart-container"] {
        min-height: 280px !important;
        height: clamp(240px, 50vw, 380px) !important;
        width: 100% !important;
    }
    .buy-btn, .sell-btn, [class*="order-btn"], button[class*="buy"], button[class*="sell"] {
        width: 100% !important;
        padding: 12px !important;
        font-size: 0.9rem !important;
    }
    .form-input, input[type="number"], input[type="text"], select {
        font-size: 16px !important;
    }
    [class*="position-card"], [class*="trade-row"] {
        font-size: 0.7rem !important;
        padding: 8px !important;
    }
}

/* ── FOXBOT ANIMATIONS — préservées sur tous écrans ── */
@keyframes botRing {
    0% { transform: scale(.5); opacity: 1 }
    100% { transform: scale(1.8); opacity: 0 }
}
@keyframes botCore {
    0%, 100% { transform: translate(-50%,-50%) scale(1) }
    50% { transform: translate(-50%,-50%) scale(1.25) }
}
@keyframes scanLine {
    0% { top: 0; opacity: .7 }
    100% { top: 100%; opacity: .2 }
}
@keyframes tradeFlash {
    0% { opacity: 0; transform: translate(-50%,-60%) scale(.8) }
    30% { opacity: 1; transform: translate(-50%,-50%) scale(1.1) }
    70% { opacity: 1; transform: translate(-50%,-50%) scale(1) }
    100% { opacity: 0; transform: translate(-50%,-40%) scale(.9) }
}
</style>' . "\n";

// ── Overrides AJAX (à insérer juste avant </body>) ─────────────────────
// On utilise output buffering pour capturer le rendu du partial blade
ob_start();
?>
<script>
/* ================================================================
   OVERRIDES AJAX — Puprime Fox (Laravel Integration)
   Ces fonctions remplacent les versions locales du trade.html.
   Elles synchronisent les actions de trading avec le backend.
================================================================ */

const ROUTES = window.__TRADE.routes;
const CSRF   = window.__TRADE.csrfToken;

// ── Helpers fetch ────────────────────────────────────────────────
async function apiPost(url, data) {
    const res = await fetch(url, {
        method: 'POST',
        credentials: 'include',  // ✅ CRUCIAL: Include session cookies
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
        },
        body: JSON.stringify(data),
    });
    if (!res.ok) {
        console.error(`[apiPost] HTTP ${res.status}:`, await res.text());
    }
    return res.json();
}

async function apiGet(url) {
    const res = await fetch(url, {
        credentials: 'include',  // ✅ CRUCIAL: Include session cookies
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
    });
    if (!res.ok) {
        console.error(`[apiGet] HTTP ${res.status}:`, await res.text());
    }
    return res.json();
}

// ── POLLING DES PRIX EN TEMPS RÉEL ────────────────────────────────────
// Mettre à jour les P&L des positions ouvertes toutes les 500ms
let pnlPollingInterval = null;
async function startLivePnLUpdates() {
    if (pnlPollingInterval) return;
    console.log('[LivePnL] ✅ Démarrage des mises à jour en temps réel');
    
    pnlPollingInterval = setInterval(async () => {
        try {
            const pnlData = await apiGet('/trade/positions/pnl/all');
            if (!Array.isArray(pnlData)) return;
            
            // Mettre à jour chaque position
            pnlData.forEach(pos => {
                updatePositionUI(pos);
            });
        } catch (e) {
            console.warn('[LivePnL] Erreur polling:', e.message);
        }
    }, 500);
}

function stopLivePnLUpdates() {
    if (pnlPollingInterval) {
        clearInterval(pnlPollingInterval);
        pnlPollingInterval = null;
        console.log('[LivePnL] ⏹ Mises à jour arrêtées');
    }
}

// Mettre à jour l'affichage d'une position
function updatePositionUI(pos) {
    const posRow = document.querySelector(`[data-pos-id="${pos.trade_id}"]`);
    if (!posRow) return;
    
    // Mettre à jour le P&L avec couleur
    const pnlElement = posRow.querySelector('.position-pnl') || posRow.querySelector('[class*="pnl"]');
    if (pnlElement) {
        const isProfit = pos.pnl >= 0;
        pnlElement.textContent = (isProfit ? '+' : '') + pos.pnl.toFixed(2) + ' $';
        pnlElement.style.color = isProfit ? 'var(--green, #00d4aa)' : 'var(--red, #ff6b6b)';
        
        // Animation pulse si P&L changeant
        pnlElement.style.animation = 'none';
        setTimeout(() => {
            pnlElement.style.animation = 'pulse 0.3s ease-out';
        }, 10);
    }
    
    // Mettre à jour le prix courant
    const priceElement = posRow.querySelector('.position-current-price') || posRow.querySelector('[class*="price"]');
    if (priceElement && pos.current_price) {
        priceElement.textContent = pos.current_price.toFixed(5);
    }
    
    // Mettre à jour le pourcentage P&L
    const pctElement = posRow.querySelector('.position-pnl-percent');
    if (pctElement && pos.pnl_percent !== undefined) {
        pctElement.textContent = (pos.pnl_percent >= 0 ? '+' : '') + pos.pnl_percent.toFixed(2) + '%';
        pctElement.style.color = pos.pnl_percent >= 0 ? 'var(--green, #00d4aa)' : 'var(--red, #ff6b6b)';
    }
}

// Ajouter les CSS pour les animations
const liveStyleSheet = document.createElement('style');
liveStyleSheet.textContent = `
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideOutUp {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-20px); }
    }
    .position-new { animation: slideInDown 0.4s ease-out; }
    .position-closing { animation: slideOutUp 0.4s ease-out forwards; }
`;
if (document.head) document.head.appendChild(liveStyleSheet);

console.log('[LivePnL] 📊 Système de mises à jour en temps réel chargé');

// ── Init : charger positions + historique depuis le serveur ──────
// ⚠️ ATTENTION: Ne PAS appeler _originalInit() pour éviter la récursion
async function initTradeWorkspace() {
    console.log('[Trade] 🚀 Initializing Xendaro Fox workspace...');
    
    // Force refresh soldes depuis la BDD au chargement
    try {
        console.log('[Trade] Fetching fresh balance from server...');
        const freshBalance = await apiGet(ROUTES.getBalance);
        if (freshBalance && freshBalance.balance) {
            state.demoBalance = parseFloat(freshBalance.balance.demo_balance) || window.__TRADE.demoBalance || 10000;
            state.realBalance = parseFloat(freshBalance.balance.real_balance) || window.__TRADE.realBalance || 0;
            console.log('[Trade] ✅ Balances loaded from server:', { demo: state.demoBalance, real: state.realBalance });
        }
    } catch (e) {
        console.warn('[Trade] Could not refresh balance from server:', e.message);
        // Fallback aux valeurs injectées en PHP
        state.demoBalance = window.__TRADE.demoBalance || 10000;
        state.realBalance = window.__TRADE.realBalance || 0;
    }
    
    // ① Charger les données depuis le serveur
    const serverPositions = window.__TRADE.openPositions || []; 
    const serverHistory   = window.__TRADE.closedHistory || [];

    // ② Initialiser les balances depuis les vraies valeurs serveur
    state.demoBalance      = window.__TRADE.demoBalance || 10000;
    state.realBalance      = window.__TRADE.realBalance || 0;
    state.startDemoBalance = state.demoBalance;
    state.startRealBalance = state.realBalance;

    // ③ Charger les positions ouvertes
    if (serverPositions.length > 0) {
        serverPositions.forEach(p => {
            if (!state.positions.find(sp => sp.id === p.id)) {
                state.positions.push({
                    id:           p.id,
                    symbol:       p.symbol,
                    side:         p.side,
                    qty:          p.qty,
                    entry:        p.entry,
                    sl:           p.sl,
                    tp:           p.tp,
                    margin:       p.margin,
                    contractSize: p.contract_size,
                    mode:         p.account_type,
                    isBot:        p.is_bot,
                    time:         p.time,
                    currentPnl:   0,
                    currentPrice: p.entry,
                });
            }
        });
    }

    // ④ Charger l'historique
    if (serverHistory.length > 0) {
        serverHistory.forEach(h => {
            if (!state.history.find(sh => sh.id === h.id)) {
                state.history.push({
                    id:     h.id,
                    symbol: h.symbol,
                    side:   h.side,
                    qty:    h.qty,
                    entry:  h.entry,
                    exit:   h.exit,
                    pnl:    h.pnl,
                    reason: h.reason,
                    mode:   h.account_type,
                    isBot:  h.is_bot,
                    time:   h.time,
                });
            }
        });
    }

    // ⑤ Initialiser l'interface (copies de trade.html init)
    try {
        buildSymbolSelect();
        initChart();
        initPnlChart();
        buildWatchlist();
        updateBalanceDisplay();
        await loadSymbol(state.symbol);
        startLiveFeed();
        applyTranslations();
        
        // ⑥ Afficher les données serveur
        renderPositionsTable();
        renderSidebarPositions();
        const cnt = state.positions.filter(p => p.mode === state.accountMode).length;
        document.getElementById('posCountBadge').textContent = cnt;
        document.getElementById('tabPosBadge').textContent   = cnt;
        document.getElementById('tabHistBadge').textContent =
            state.history.filter(h => h.mode === state.accountMode).length;
        
        // ⑦ Démarrer les mises à jour en temps réel
        startLivePnLUpdates();
        
        // ⑧ Initialiser l'affichage du bot actif
        initBotActiveDisplay();
        
        console.log('[Trade] ✅ Workspace initialized successfully');
    } catch (e) {
        console.error('[Trade] ❌ Init error:', e.message);
    }
}

// ═══════════════════════════════════════════════════════════════════════
// 🤖 BOT ACTIVE STATUS & NOTIFICATIONS SYSTEM
// ═══════════════════════════════════════════════════════════════════════

/**
 * Initialize bot status display and monitoring
 */
function initBotActiveDisplay() {
    const botState = window.__TRADE.botState || {};
    const isBotActive = botState.bot_active === true;

    if (isBotActive) {
        console.log('[Bot] 🤖 Bot is ACTIVE - Initializing display...');
        
        // Show bot badge in header
        const headerNav = document.querySelector('[class*="header"], nav, [role="navigation"]');
        if (headerNav) {
            createBotActiveBadge();
        }

        // Add bot indicators to tabs
        updateTabsWithBotIndicator();

        // Show initial bot notification
        showBotNotification('Bot Activated', 'Your FoxBot is actively trading on your account', '+0.00');

        // Monitor for balance updates from server
        monitorBotBalanceUpdates();
    } else {
        console.log('[Bot] Bot is inactive');
    }
}

/**
 * Create and inject bot active badge
 */
function createBotActiveBadge() {
    // Check if badge already exists
    if (document.querySelector('.bot-active-badge')) {
        return;
    }

    const badge = document.createElement('div');
    badge.className = 'bot-active-badge';
    badge.innerHTML = '🤖 FoxBot Active';
    badge.title = 'Your FoxBot is actively trading';
    badge.style.cssText = `
        position: fixed;
        top: 20px;
        right: 120px;
        z-index: 99;
    `;

    document.body.appendChild(badge);
    console.log('[Bot] Bot active badge created');
}

/**
 * Show bot notification toast
 */
function showBotNotification(title, message, amount = '+0.00') {
    const notification = document.createElement('div');
    notification.className = 'bot-notification active';
    notification.innerHTML = `
        <div class="bot-notification-content">
            <div class="bot-notification-icon">🤖</div>
            <div class="bot-notification-text">
                <div class="bot-notification-title">${title}</div>
                <div class="bot-notification-message">${message}</div>
                <div class="bot-notification-amount">${amount}</div>
            </div>
        </div>
    `;

    document.body.appendChild(notification);

    // Auto-remove after 6 seconds
    setTimeout(() => {
        notification.style.animation = 'slide-out-right 0.4s ease-in forwards';
        setTimeout(() => notification.remove(), 400);
    }, 6000);

    console.log('[Bot] Notification shown:', { title, message, amount });
}

/**
 * Add bot indicator to tabs
 */
function updateTabsWithBotIndicator() {
    // Find tab elements
    const tabElements = document.querySelectorAll('[role="tab"], [class*="tab"]');
    
    tabElements.forEach(tab => {
        const tabText = tab.innerText || tab.textContent;
        
        // Add bot badge to relevant tabs
        if (tabText.includes('Position') || tabText.includes('position')) {
            addBotBadgeToTab(tab, 'Positions');
        } else if (tabText.includes('History') || tabText.includes('historique')) {
            addBotBadgeToTab(tab, 'History');
        } else if (tabText.includes('Form') || tabText.includes('formulaire')) {
            addBotBadgeToTab(tab, 'Form');
        }
    });
}

/**
 * Add bot badge to specific tab
 */
function addBotBadgeToTab(tabElement, tabName) {
    // Check if badge already exists
    if (tabElement.querySelector('.tab-badge-bot')) {
        return;
    }

    const badge = document.createElement('span');
    badge.className = 'tab-badge-bot';
    badge.title = `Bot is active on ${tabName}`;
    badge.innerText = '●';

    tabElement.appendChild(badge);
    console.log(`[Bot] Badge added to ${tabName} tab`);
}

/**
 * Add bot indicator to open positions
 */
function addBotIndicatorToPosition(positionElement, isBot = true) {
    if (!isBot || !positionElement) return;

    // Check if indicator already exists
    if (positionElement.querySelector('.position-bot-indicator')) {
        return;
    }

    const indicator = document.createElement('div');
    indicator.className = 'position-bot-indicator';
    indicator.innerText = 'Auto Trading';
    indicator.title = 'This position is managed by FoxBot';

    // Insert at the beginning
    positionElement.insertBefore(indicator, positionElement.firstChild);
}

/**
 * Monitor bot balance updates from server
 * Polling amélioré: chaque 10 secondes au lieu de 30 (plus réactif)
 */
function monitorBotBalanceUpdates() {
    // Poll server for balance updates every 10 seconds (was 30)
    const pollInterval = setInterval(async () => {
        try {
            const response = await apiGet(ROUTES.getBalance);
            if (response && response.balance) {
                const demoBalBefore = state.demoBalance;
                const realBalBefore = state.realBalance;

                state.demoBalance = response.balance.demo_balance || demoBalBefore;
                state.realBalance = response.balance.real_balance || realBalBefore;

                // Check if balance was updated (profit/loss from bot)
                if (state.accountMode === 'demo' && Math.abs(state.demoBalance - demoBalBefore) > 0.01) {
                    const increment = (state.demoBalance - demoBalBefore).toFixed(2);
                    if (state.demoBalance > demoBalBefore) {
                        showBotNotification(
                            'FoxBot Profit Generated',
                            'Automatic bot trading profit received',
                            `+$${increment}`
                        );
                    } else {
                        showBotNotification(
                            'FoxBot Loss Recorded',
                            'Position closed with loss',
                            `-$${Math.abs(increment)}`
                        );
                    }
                    console.log(`[Bot] Demo balance updated: ${increment >= 0 ? '+' : ''}$${increment}`);
                    updateBalanceDisplay();
                    // Persist to database
                    debounceBalanceSync();
                } else if (state.accountMode === 'real' && Math.abs(state.realBalance - realBalBefore) > 0.01) {
                    const increment = (state.realBalance - realBalBefore).toFixed(2);
                    if (state.realBalance > realBalBefore) {
                        showBotNotification(
                            'FoxBot Profit Generated',
                            'Automatic bot trading profit received',
                            `+$${increment}`
                        );
                    } else {
                        showBotNotification(
                            'FoxBot Loss Recorded',
                            'Position closed with loss',
                            `-$${Math.abs(increment)}`
                        );
                    }
                    console.log(`[Bot] Real balance updated: ${increment >= 0 ? '+' : ''}$${increment}`);
                    updateBalanceDisplay();
                    // Persist to database
                    debounceBalanceSync();
                }
            }
        } catch (e) {
            console.warn('[Bot] Balance update poll error:', e.message);
        }
    }, 10000); // Poll every 10 seconds (increased reactivity)

    // Store interval ID for cleanup
    window._botMonitorInterval = pollInterval;
}

/**
 * Cleanup bot monitoring
 */
function stopBotMonitoring() {
    if (window._botMonitorInterval) {
        clearInterval(window._botMonitorInterval);
        window._botMonitorInterval = null;
        console.log('[Bot] Monitoring stopped');
    }
}

/**
 * Synchronize balances with the server (persist to database)
 * PRIORITÉ: Cette fonction doit être appelée après CHAQUE transaction
 */
async function syncBalanceToServer() {
    try {
        const response = await apiPost(ROUTES.updateBalance, {
            demo_balance: state.demoBalance,
            real_balance: state.realBalance,
            margin_used: state.marginUsed || 0
        });
        
        if (response && response.success) {
            console.log('[Balance] ✅ Synchronized with server:', response.balance);
        } else {
            console.warn('[Balance] ⚠️ Sync response unexpected:', response);
        }
    } catch (e) {
        console.error('[Balance] ❌ Sync failed:', e.message);
    }
}

/**
 * Monitor local balance changes and sync to server
 * Called after transactions, closures, bot trades, etc.
 * CETTE FONCTION DOIT ÊTRE APPELÉE APRÈS CHAQUE ACTION
 */
window._lastSyncTime = Date.now();
window._syncDebounceTimer = null;

function debounceBalanceSync() {
    // Avoid syncing too frequently - max once per 2 seconds
    if (window._syncDebounceTimer) {
        clearTimeout(window._syncDebounceTimer);
    }
    
    window._syncDebounceTimer = setTimeout(async () => {
        const now = Date.now();
        if (now - window._lastSyncTime > 2000) {
            window._lastSyncTime = now;
            console.log('[Balance] Syncing to server:', { demo: state.demoBalance, real: state.realBalance });
            await syncBalanceToServer();
        }
    }, 500);
}

// ═════════════════════════════════════════════════════════════════════════

// Appeler la nouvelle fonction au chargement
window.addEventListener('load', initTradeWorkspace, { once: true });

// ⚠️ Désactiver l'ancien écouteur de charge (éviter double-init)
// Si init() est appelée par le trade.html, il ne fera rien
const _originalInit = window.init || function() {};
window.init = function() {
    console.warn('[Trade] init() appel supprimé - using initTradeWorkspace() instead');
    return Promise.resolve();
};

// ── Override : confirmOrder → AJAX openPosition ──────────────────
const _originalConfirmOrder = confirmOrder;
async function confirmOrder() {
    console.log('[confirmOrder] Starting order placement...');
    
    const sym       = state.symbol;
    const pr        = state.prices[sym];
    const instr     = INSTRUMENTS[sym];
    if (!pr) {
        showToast('warn', 'Erreur', 'Prix non disponible pour ' + sym);
        console.error('[confirmOrder] No price for symbol:', sym);
        return;
    }

    const orderType  = document.getElementById('orderType').value;
    const qty        = parseFloat(document.getElementById('orderQty').value) || 1;
    const side       = state.orderSide;
    const sl         = parseFloat(document.getElementById('stopLoss').value) || null;
    const tp         = parseFloat(document.getElementById('takeProfit').value) || null;
    const entryPrice = side === 'buy' ? pr.ask : pr.bid;
    const posValue   = qty * instr.contractSize * entryPrice;
    const margin     = posValue / instr.leverage;
    const balance    = getBalance();

    console.log('[confirmOrder] Data:', { symbol: sym, side, qty, margin, balance });

    if (margin > balance) {
        showToast('warn', t('marginInsuf'), `${t('marginReq')}: $${margin.toFixed(2)} | ${t('disponible')}: $${balance.toFixed(2)}`);
        console.error('[confirmOrder] Insufficient margin:', { margin, balance });
        return;
    }

    // Fermer le modal d'abord
    closeModal();

    // Gérer les ordres limites localement (pas persistés côté serveur pour l'instant)
    if (orderType !== 'market') {
        const limitPrice = parseFloat(document.getElementById('limitPrice').value) || null;
        if (limitPrice) {
            state.limitOrders.push({
                id: state.posIdCounter++, symbol: sym, side, qty, limitPrice, sl, tp,
                type: orderType, time: Date.now(), mode: state.accountMode
            });
            renderLimitOrders();
            showToast('info', currentLang === 'en' ? 'Limit order placed' : 'Ordre limite placé',
                `${sym} ${side === 'buy' ? t('buyLabel') : t('sellLabel')} @ ${limitPrice.toFixed(instr.decimals)}`);
            return;
        }
    }

    // Désactiver le bouton pendant l'envoi
    const btn = document.getElementById('orderBtn');
    btn.disabled = true;

    try {
        const payload = {
            symbol:        sym,
            direction:     side === 'buy' ? 'BUY' : 'SELL',
            volume:        qty,
            entry_price:   entryPrice,
            stop_loss:     sl,
            take_profit:   tp,
            margin:        margin,
            contract_size: instr.contractSize,
            account_type:  state.accountMode,
            is_bot:        false,
        };
        
        console.log('[confirmOrder] Sending to:', ROUTES.openPosition);
        console.log('[confirmOrder] Payload:', payload);
        
        const res = await apiPost(ROUTES.openPosition, payload);

        console.log('[confirmOrder] Response:', res);

        if (res.error) {
            showToast('warn', 'Erreur Serveur', res.error);
            console.error('[confirmOrder] API error:', res.error);
            return;
        }

        // Mettre à jour les balances depuis la réponse serveur
        if (res.balance) {
            state.demoBalance = parseFloat(res.balance.demo_balance) || state.demoBalance;
            state.realBalance = parseFloat(res.balance.real_balance) || state.realBalance;
            updateBalanceDisplay();
            // SYNCHRONISER vers la base de données
            debounceBalanceSync();
        }

        // Ajouter la position dans l'état local avec l'ID serveur
        const pos = {
            id:           res.trade_id,
            symbol:       sym,
            side,
            qty,
            entry:        entryPrice,
            sl,
            tp,
            margin,
            contractSize: instr.contractSize,
            time:         Date.now(),
            currentPnl:   0,
            currentPrice: entryPrice,
            mode:         state.accountMode,
            isBot:        false,
        };
        state.positions.push(pos);

        const sideLabel = side === 'buy' ? t('buyLabel') : t('sellLabel');
        showToast(side, `${sideLabel} ${t('lotExec')}`,
            `${qty} lot(s) ${sym} @ ${entryPrice.toFixed(instr.decimals)}`);
        addJournalEntry(`${sideLabel} ${qty} lot(s) <b>${sym}</b> @ ${entryPrice.toFixed(instr.decimals)} | ${t('margin')}: $${margin.toFixed(2)} [${state.accountMode.toUpperCase()}]`);

        renderPositionsTable();
        renderSidebarPositions();
        
        // Animation sur la nouvelle position
        setTimeout(() => {
            const newPosEl = document.querySelector(`[data-pos-id="${res.trade_id}"]`);
            if (newPosEl) {
                newPosEl.classList.add('position-new');
                setTimeout(() => newPosEl.classList.remove('position-new'), 400);
            }
        }, 100);
        
        const cnt = state.positions.filter(p => p.mode === state.accountMode).length;
        document.getElementById('posCountBadge').textContent = cnt;
        document.getElementById('tabPosBadge').textContent   = cnt;
    } catch (e) {
        showToast('warn', 'Erreur', 'Impossible de placer l\'ordre: ' + e.message);
        console.error('[confirmOrder] Exception:', e);
    } finally {
        btn.disabled = false;
    }
}

// ── Override : closePosition → AJAX closePosition ────────────────
const _originalClosePosition = closePosition;
async function closePosition(posId, reason = 'Manuel') {
    console.log('[closePosition] Closing position:', posId, 'Reason:', reason);
    
    const idx = state.positions.findIndex(p => p.id === posId);
    if (idx === -1) {
        console.error('[closePosition] Position not found:', posId);
        return;
    }
    
    const pos = state.positions[idx];
    const pr  = state.prices[pos.symbol];
    if (!pr) {
        console.error('[closePosition] No price for symbol:', pos.symbol);
        return;
    }

    const instr      = INSTRUMENTS[pos.symbol];
    const closePrice = pos.side === 'buy' ? pr.bid : pr.ask;

    try {
        const url = ROUTES.closePosition.replace('__ID__', posId);
        console.log('[closePosition] Sending to:', url);
        
        const res = await apiPost(url, {
            exit_price:   closePrice,
            close_reason: reason,
        });

        console.log('[closePosition] Response:', res);

        if (res.error) {
            showToast('warn', 'Erreur', res.error);
            console.error('[closePosition] API error:', res.error);
            return;
        }

        // Mettre à jour les balances
        if (res.balance) {
            state.demoBalance = parseFloat(res.balance.demo_balance) || state.demoBalance;
            state.realBalance = parseFloat(res.balance.real_balance) || state.realBalance;
            updateBalanceDisplay();
            // SYNCHRONISER vers la base de données
            debounceBalanceSync();
        }

        const pnl = res.pnl ?? 0;
        state.dayPnl += pnl;

        state.history.push({
            id:     pos.id,
            symbol: pos.symbol,
            side:   pos.side,
            qty:    pos.qty,
            entry:  pos.entry,
            exit:   closePrice,
            pnl,
            duration: Date.now() - pos.time,
            reason,
            time:   Date.now(),
            mode:   pos.mode,
            isBot:  pos.isBot || false,
        });
        
        state.pnlHistory.push(
            state.history.filter(h => h.mode === state.accountMode).reduce((s, t) => s + t.pnl, 0)
        );
        
        state.positions.splice(idx, 1);

        const pnlStr = (pnl >= 0 ? '+$' : '-$') + Math.abs(pnl).toFixed(2);
        showToast(pnl >= 0 ? 'buy' : 'sell', `${t('closedPos')} — ${reason}`, `${pos.symbol} ${pnlStr}`);
        addJournalEntry(`${t('closedPos')} <b>${pos.symbol}</b> @ ${closePrice.toFixed(instr.decimals)} | PnL: <span style="color:${pnl >= 0 ? 'var(--green)' : 'var(--red)'}">${pnlStr}</span> | ${reason} [${pos.mode.toUpperCase()}]`);

        updateBalanceDisplay();
        
        // Animation de fermeture
        const posRow = document.querySelector(`[data-pos-id="${posId}"]`);
        if (posRow) {
            posRow.classList.add('position-closing');
            setTimeout(() => {
                renderPositionsTable();
                renderSidebarPositions();
                updatePnlMiniChart();
            }, 400);
        } else {
            renderPositionsTable();
            renderSidebarPositions();
            updatePnlMiniChart();
        }

        const cnt = state.positions.filter(p => p.mode === state.accountMode).length;
        document.getElementById('posCountBadge').textContent = cnt;
        document.getElementById('tabPosBadge').textContent   = cnt;
        document.getElementById('tabHistBadge').textContent  =
            state.history.filter(h => h.mode === state.accountMode).length;
        renderHistory();
    } catch (e) {
        showToast('warn', 'Erreur', 'Impossible de fermer la position: ' + e.message);
        console.error('[closePosition] Exception:', e);
    }
}

// ═══════════════════════════════════════════════════════════════════════════
// 🤖 FOXBOT v3.0 - ROBUST AUTOMATED TRADING SYSTEM
// ═══════════════════════════════════════════════════════════════════════════
// - 70-80% win rate (configurable)
// - Real API integration (not local-only)
// - Opérations horaires (1 cycle ~ toutes les 45-75 minutes, hold 45-90 minutes)
// - Full BDD persistence with transaction support
// - Dynamic risk management
// ═══════════════════════════════════════════════════════════════════════════

const FOXBOT_SYSTEM = {
    config: {
        minWinRate:        0.70,       // 70% minimum win rate
        maxWinRate:        0.80,       // 80% maximum win rate
        minHoldTimeMs:     45 * 60 * 1000,  // 45 minutes minimum
        maxHoldTimeMs:     90 * 60 * 1000,  // 1h30 maximum
        riskPerTrade:      0.02,       // 2% of balance per trade
        maxConcurrentBots: 3,          // Max 3 concurrent positions
    },
    
    state: {
        isRunning:    false,
        botInterval:  null,
        openTrades:   {},             // { tradeId: { timeout, symbol, side, ... } }
        stats:        {
            totalOpened: 0,
            totalClosed: 0,
            wins:        0,
            losses:      0,
        }
    },

    /**
     * Start the bot
     */
    async start(botId) {
        if (this.state.isRunning) {
            console.warn('[FoxBot] Already running');
            return;
        }

        console.log('[FoxBot] 🚀 Starting bot:', botId);
        this.state.isRunning = true;
        this.state.openTrades = {};
        this.state.stats = { totalOpened: 0, totalClosed: 0, wins: 0, losses: 0 };

        // Schedule first cycle
        this.scheduleCycle();
    },

    /**
     * Stop the bot
     */
    async stop() {
        if (!this.state.isRunning) {
            console.warn('[FoxBot] Not running');
            return;
        }

        console.log('[FoxBot] 🛑 Stopping bot');
        this.state.isRunning = false;

        // Clear scheduled cycle
        if (this.state.botInterval) {
            clearTimeout(this.state.botInterval);
            this.state.botInterval = null;
        }

        // Close all auto-close timers
        Object.values(this.state.openTrades).forEach(trade => {
            if (trade.closeTimer) clearTimeout(trade.closeTimer);
        });
        this.state.openTrades = {};
    },

    /**
     * Schedule next bot cycle
     */
    scheduleCycle() {
        if (!this.state.isRunning) return;

        // Random delay between cycles (45 - 75 minutes) — opérations horaires
        const delayMs = 45 * 60 * 1000 + Math.random() * 30 * 60 * 1000;
        this.state.botInterval = setTimeout(() => {
            this.runCycle();
            this.scheduleCycle();
        }, delayMs);
    },

    /**
     * Main bot trading cycle
     */
    async runCycle() {
        if (!this.state.isRunning) return;

        try {
            const balance = getBalance();

            // Check minimum balance
            if (balance < 50) {
                console.warn('[FoxBot] Insufficient balance:', balance);
                this.stop();
                return;
            }

            // Check concurrent position limit
            const botCount = Object.keys(this.state.openTrades).length;
            if (botCount >= this.config.maxConcurrentBots) {
                console.log('[FoxBot] Max concurrent positions reached:', botCount);
                return;
            }

            // Pick a random symbol
            const symbols = Object.keys(INSTRUMENTS);
            const symbol = symbols[Math.floor(Math.random() * symbols.length)];
            const instr = INSTRUMENTS[symbol];
            const pr = state.prices[symbol];

            if (!pr || pr.price <= 0) {
                console.warn('[FoxBot] Invalid price for symbol:', symbol);
                return;
            }

            // Decide trade direction with technical bias
            let side = 'buy';
            if (state.candles && state.candles.length >= 20) {
                // Simple momentum: favor direction based on last candle
                const lastCandle = state.candles[state.candles.length - 1];
                if (lastCandle && lastCandle.close < lastCandle.open) {
                    side = Math.random() < 0.6 ? 'buy' : 'sell';  // Oversold bias
                } else {
                    side = Math.random() < 0.4 ? 'buy' : 'sell';  // Overbought bias
                }
            } else {
                side = Math.random() < 0.5 ? 'buy' : 'sell';
            }

            // Calculate position size based on risk management
            const riskAmount = balance * this.config.riskPerTrade;
            const entryPrice = side === 'buy' ? pr.ask : pr.bid;
            const slDistance = entryPrice * 0.015;  // 1.5% SL distance
            let qty = riskAmount / (slDistance * instr.contractSize);
            
            // Safety clamps
            qty = Math.max(0.01, Math.min(qty, balance * 0.05 / (entryPrice * instr.contractSize / instr.leverage)));
            qty = parseFloat(qty.toFixed(2));

            const posValue = qty * instr.contractSize * entryPrice;
            const margin = posValue / instr.leverage;

            if (margin > balance) {
                console.warn('[FoxBot] Insufficient margin:', margin, 'balance:', balance);
                return;
            }

            // Calculate SL and TP
            const sl = side === 'buy'
                ? parseFloat((entryPrice - slDistance).toFixed(instr.decimals))
                : parseFloat((entryPrice + slDistance).toFixed(instr.decimals));
            const tp = side === 'buy'
                ? parseFloat((entryPrice + slDistance * 2).toFixed(instr.decimals))  // 2x SL for TP
                : parseFloat((entryPrice - slDistance * 2).toFixed(instr.decimals));

            // Open bot position via API (using web route, not API route)
            await this.openBotPosition({
                symbol,
                direction: side.toUpperCase(),
                volume: qty,
                entry_price: entryPrice,
                stop_loss: sl,
                take_profit: tp,
                margin: margin,
                contract_size: instr.contractSize,
                account_type: state.accountMode,
            });

        } catch (e) {
            console.error('[FoxBot] Cycle error:', e.message);
        }
    },

    /**
     * Open a bot position via API
     */
    async openBotPosition(data) {
        try {
            console.log('[FoxBot] Opening position with data:', data);
            console.log('[FoxBot] Using route:', ROUTES.foxbot);
            
            // Use the web route for bot trading (not API route)
            const res = await apiPost(ROUTES.foxbot, {
                symbol: data.symbol,
                direction: data.direction,
                volume: data.volume,
                entry_price: data.entry_price,
                stop_loss: data.stop_loss,
                take_profit: data.take_profit,
                margin: data.margin,
                contract_size: data.contract_size,
                account_type: data.account_type,
            });

            console.log('[FoxBot] Open response:', res);

            if (res.error) {
                console.error('[FoxBot] Open error:', res.error);
                showToast('warn', '🤖 FoxBot Error', res.error);
                return;
            }

            const tradeId = res.trade_id;
            console.log('[FoxBot] ✅ Position opened:', tradeId, data.symbol);

            // Update balance
            if (res.balance) {
                state.demoBalance = parseFloat(res.balance.demo_balance) || state.demoBalance;
                state.realBalance = parseFloat(res.balance.real_balance) || state.realBalance;
                updateBalanceDisplay();
            }

            // Show notification
            const sideLabel = data.direction === 'BUY' ? '🟢 BUY' : '🔴 SELL';
            showToast('info', '🤖 FoxBot Trade', `${sideLabel} ${data.volume} ${data.symbol}`);
            addJournalEntry(`<span style="color:#FF8C42">🤖 FoxBot</span> ${sideLabel} ${data.volume} lot(s) <b>${data.symbol}</b> @ ${data.entry_price.toFixed(INSTRUMENTS[data.symbol].decimals)}`);

            // Track this open trade
            this.state.openTrades[tradeId] = {
                symbol: data.symbol,
                side: data.direction,
                entry: data.entry_price,
                sl: data.stop_loss,
                tp: data.take_profit,
                volume: data.volume,
                margin: data.margin,
                contractSize: data.contract_size,
            };

            this.state.stats.totalOpened++;

            // Schedule auto-close
            this.scheduleAutoClose(tradeId, data.symbol);

        } catch (e) {
            console.error('[FoxBot] Open exception:', e.message, e);
        }
    },

    /**
     * Schedule automatic position close
     */
    scheduleAutoClose(tradeId, symbol) {
        const holdTimeMs = this.config.minHoldTimeMs + 
                          Math.random() * (this.config.maxHoldTimeMs - this.config.minHoldTimeMs);
        
        // Decide if this is a winning trade (70-80%)
        const winRate = this.config.minWinRate + 
                       Math.random() * (this.config.maxWinRate - this.config.minWinRate);
        const isWin = Math.random() < winRate;

        console.log(`[FoxBot] Trade ${tradeId}: ${isWin ? '✅ WIN' : '❌ LOSS'} in ${(holdTimeMs / 60000).toFixed(0)}min`);

        const closeTimer = setTimeout(async () => {
            await this.closeBotPosition(tradeId, symbol, isWin);
        }, holdTimeMs);

        if (this.state.openTrades[tradeId]) {
            this.state.openTrades[tradeId].closeTimer = closeTimer;
        }
    },

    /**
     * Close a bot position via API
     */
    async closeBotPosition(tradeId, symbol, isWin) {
        try {
            const instr = INSTRUMENTS[symbol];
            
            // Generate exit price based on win/loss
            const tradeData = this.state.openTrades[tradeId];
            if (!tradeData) {
                console.warn('[FoxBot] Trade data not found:', tradeId);
                return;
            }

            let exitPrice;
            if (isWin) {
                // Exit near take profit
                const wiggleRoom = (tradeData.tp - tradeData.entry) * 0.1;
                exitPrice = tradeData.tp - Math.random() * wiggleRoom;
            } else {
                // Exit near stop loss
                const wiggleRoom = (tradeData.entry - tradeData.sl) * 0.1;
                exitPrice = tradeData.sl + Math.random() * wiggleRoom;
            }

            exitPrice = parseFloat(exitPrice.toFixed(instr.decimals));

            // Build close URL: replace __ID__ placeholder
            const closeUrl = ROUTES.closePosition.replace('__ID__', tradeId);
            
            console.log('[FoxBot] Closing position:', tradeId, 'URL:', closeUrl);

            // Close via web route (using closePosition endpoint)
            const res = await apiPost(closeUrl, {
                exit_price: exitPrice,
                is_winning_trade: isWin,
            });

            console.log('[FoxBot] Close response:', res);

            if (res.error) {
                console.error('[FoxBot] Close error:', res.error);
                showToast('warn', '🤖 FoxBot Error', res.error);
                return;
            }

            console.log('[FoxBot] ✅ Position closed:', tradeId, 'PnL:', res.pnl);

            // Update balance
            if (res.balance) {
                state.demoBalance = parseFloat(res.balance.demo_balance) || state.demoBalance;
                state.realBalance = parseFloat(res.balance.real_balance) || state.realBalance;
                updateBalanceDisplay();
            }

            // Update stats
            this.state.stats.totalClosed++;
            if (isWin) {
                this.state.stats.wins++;
            } else {
                this.state.stats.losses++;
            }

            const pnl = res.pnl || 0;
            const pnlStr = (pnl >= 0 ? '+$' : '-$') + Math.abs(pnl).toFixed(2);
            const status = isWin ? '✅ WIN' : '❌ LOSS';

            showToast(isWin ? 'buy' : 'sell', `🤖 FoxBot ${status}`, `${symbol} ${pnlStr}`);
            addJournalEntry(`<span style="color:#FF8C42">🤖 FoxBot</span> ${status} ${tradeData.side} ${tradeData.volume} <b>${symbol}</b> @ ${exitPrice.toFixed(instr.decimals)} | PnL: <b style="color:${isWin ? 'var(--green)' : 'var(--red)'}">${pnlStr}</b>`);

            // Clean up
            delete this.state.openTrades[tradeId];

        } catch (e) {
            console.error('[FoxBot] Close exception:', e.message, e);
        }
    },

    /**
     * Get bot statistics
     */
    getStats() {
        return {
            ...this.state.stats,
            openCount: Object.keys(this.state.openTrades).length,
            winRate: this.state.stats.totalClosed > 0 
                ? ((this.state.stats.wins / this.state.stats.totalClosed) * 100).toFixed(1)
                : 0,
        };
    }
};

// Override the original runBotCycle to use the new system
const _originalRunBotCycle = runBotCycle;
window.runBotCycle = function() {
    return FOXBOT_SYSTEM.runCycle();
};

// ── AUTO ANIMATIONS ───────────────────────────────────────────────────
// Ajouter des animations automatiques au workspace
setTimeout(() => {
    // Animation 1: Scroll auto du watchlist
    const watchlist = document.querySelector('.watchlist-items');
    if (watchlist) {
        let scrollDir = 1;
        setInterval(() => {
            watchlist.scrollLeft += scrollDir * 2;
            if (watchlist.scrollLeft >= watchlist.scrollWidth - watchlist.clientWidth) {
                scrollDir = -1;
            } else if (watchlist.scrollLeft <= 0) {
                scrollDir = 1;
            }
        }, 50);
    }

    // Animation 2: Pulse sur les prix qui changent
    const originalUpdatePriceDisplay = updatePriceDisplay;
    window.updatePriceDisplay = function(sym) {
        originalUpdatePriceDisplay(sym);
        const priceEl = document.querySelector('.current-price');
        if (priceEl) {
            priceEl.style.animation = 'none';
            setTimeout(() => {
                priceEl.style.animation = 'pulse 0.6s ease-in-out';
            }, 10);
        }
    };

    // Animation 3: Fade-in pour les nouvelles entrées du journal
    const originalAddJournalEntry = addJournalEntry;
    window.addJournalEntry = function(text) {
        originalAddJournalEntry(text);
        const journal = document.querySelector('.journal-entries');
        if (journal && journal.firstChild) {
            journal.firstChild.style.opacity = '0';
            journal.firstChild.style.animation = 'slideInLeft 0.4s ease-out forwards';
        }
    };

    // Animation 4: Glow sur les positions fermées avec profit
    const originalClosePosition = closePosition;
    window.closePosition = async function(posId, reason) {
        const result = await originalClosePosition(posId, reason);
        setTimeout(() => {
            const posRow = document.querySelector(`[data-pos-id="${posId}"]`);
            if (posRow) {
                posRow.style.animation = 'slideOutRight 0.4s ease-in forwards';
                setTimeout(() => posRow.remove(), 400);
            }
        }, 300);
        return result;
    };

    console.log('[Animations] Auto-animations initialized');
}, 2000);

// ── DROPDOWN PROFIL UTILISATEUR ────────────────────────────────────────
// Fermer le dropdown en cliquant ailleurs
document.addEventListener('click', function(event) {
    const profileDropdown = document.getElementById('userProfileDropdown');
    if (profileDropdown && !profileDropdown.contains(event.target)) {
        profileDropdown.classList.remove('active');
    }
});

// Fermer le dropdown au clic sur un lien du menu
document.addEventListener('DOMContentLoaded', function() {
    const profileDropdown = document.getElementById('userProfileDropdown');
    if (profileDropdown) {
        const links = profileDropdown.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function() {
                setTimeout(() => {
                    profileDropdown.classList.remove('active');
                }, 200);
            });
        });
    }
});

// Arrêter les mises à jour en temps réel au déchargement
window.addEventListener('beforeunload', function() {
    stopLivePnLUpdates();
    console.log('[Trade] 🛑 Mises à jour en temps réel arrêtées');
});

console.log('[Trade] ✅ Tous les modules chargés avec succès');

// ═══════════════════════════════════════════════════════════════════
// 🚀 FOXBOT ENHANCEMENTS — Markets, Charts, Animations, Reconnect
// ═══════════════════════════════════════════════════════════════════

// ── 1. Chart resize fix ────────────────────────────────────────────
setTimeout(function() {
    window.dispatchEvent(new Event('resize'));
    if (typeof Chart !== 'undefined') {
        var instances = Chart.instances ? (Array.isArray(Chart.instances) ? Chart.instances : Object.values(Chart.instances)) : [];
        instances.forEach(function(c) { try { c.resize(); } catch(e) {} });
    }
    document.querySelectorAll('canvas').forEach(function(c) {
        if (c.offsetHeight < 10) { c.style.minHeight = '120px'; c.style.display = 'block'; }
    });
    try { if (typeof initChart    === 'function') initChart();    } catch(e) {}
    try { if (typeof initPnlChart === 'function') initPnlChart(); } catch(e) {}
}, 1500);

// ── 2. Extra Instruments injection ────────────────────────────────
setTimeout(function() {
    if (typeof INSTRUMENTS === 'undefined') return;
    var extra = {
        'ADA/USD':  {leverage:20,  contractSize:1000,   decimals:5},
        'AVAX/USD': {leverage:50,  contractSize:1,      decimals:3},
        'LINK/USD': {leverage:50,  contractSize:1,      decimals:4},
        'DOT/USD':  {leverage:20,  contractSize:10,     decimals:4},
        'MATIC/USD':{leverage:20,  contractSize:1000,   decimals:5},
        'LTC/USD':  {leverage:50,  contractSize:1,      decimals:2},
        'UNI/USD':  {leverage:20,  contractSize:10,     decimals:4},
        'ATOM/USD': {leverage:20,  contractSize:10,     decimals:4},
        'USD/CHF':  {leverage:100, contractSize:100000, decimals:5},
        'USD/CAD':  {leverage:100, contractSize:100000, decimals:5},
        'AUD/USD':  {leverage:100, contractSize:100000, decimals:5},
        'NZD/USD':  {leverage:100, contractSize:100000, decimals:5},
        'EUR/GBP':  {leverage:100, contractSize:100000, decimals:5},
        'EUR/JPY':  {leverage:100, contractSize:100000, decimals:3},
        'GBP/JPY':  {leverage:100, contractSize:100000, decimals:3},
        'XAG/USD':  {leverage:100, contractSize:5000,   decimals:3},
        'USOIL':    {leverage:100, contractSize:1000,   decimals:2},
        'UKOIL':    {leverage:100, contractSize:1000,   decimals:2},
        'US500':    {leverage:100, contractSize:1,      decimals:2},
        'NASDAQ':   {leverage:100, contractSize:1,      decimals:2},
        'DOWJONES': {leverage:100, contractSize:1,      decimals:2},
    };
    Object.assign(INSTRUMENTS, extra);
    try { if (typeof buildSymbolSelect === 'function') buildSymbolSelect(); } catch(e) {}
    try { if (typeof buildWatchlist    === 'function') buildWatchlist();    } catch(e) {}
    console.log('[Markets] +' + Object.keys(extra).length + ' extra instruments injected');
}, 600);

// ── 3. Bot Activity Panel (HTML + CSS) ────────────────────────────
(function injectFoxbotPanel() {
    if (document.getElementById('foxbot-activity-panel')) return;
    var s = document.createElement('style');
    s.textContent = '#foxbot-activity-panel{position:fixed;bottom:20px;right:20px;width:288px;max-height:370px;background:rgba(14,14,22,.97);border:1px solid rgba(0,212,170,.35);border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.5);z-index:9999;display:none;flex-direction:column;overflow:hidden;animation:fbpIn .3s ease-out}#foxbot-activity-panel.fbp-on{display:flex}@keyframes fbpIn{from{opacity:0;transform:translateY(14px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}#fbp-hdr{display:flex;align-items:center;justify-content:space-between;padding:9px 13px;background:rgba(0,212,170,.1);border-bottom:1px solid rgba(0,212,170,.2);cursor:pointer;user-select:none}#fbp-title{font-size:12px;font-weight:700;color:#00d4aa;display:flex;align-items:center;gap:7px}#fbp-badge{background:#00d4aa;color:#000;font-size:10px;font-weight:800;padding:1px 6px;border-radius:20px;min-width:18px;text-align:center}#fbp-x{color:rgba(255,255,255,.4);font-size:14px;cursor:pointer}#fbp-x:hover{color:#fff}#fbp-stats{display:flex;gap:5px;padding:7px 12px;border-bottom:1px solid rgba(255,255,255,.05)}.fbp-s{flex:1;text-align:center;padding:4px;border-radius:6px;background:rgba(255,255,255,.04)}.fbp-sv{font-size:12px;font-weight:700;color:#eeeef5}.fbp-sl{font-size:9px;color:rgba(255,255,255,.3);text-transform:uppercase;letter-spacing:.4px}#fbp-list{flex:1;overflow-y:auto;max-height:240px}#fbp-list::-webkit-scrollbar{width:3px}#fbp-list::-webkit-scrollbar-thumb{background:rgba(0,212,170,.3);border-radius:2px}.fbp-item{display:flex;align-items:center;gap:8px;padding:6px 12px;border-bottom:1px solid rgba(255,255,255,.04);animation:fbpItem .3s ease-out}@keyframes fbpItem{from{opacity:0;transform:translateX(8px)}to{opacity:1;transform:translateX(0)}}.fbp-dot{width:7px;height:7px;border-radius:50%;flex-shrink:0}.fbp-w{background:#00d4aa;box-shadow:0 0 5px rgba(0,212,170,.5)}.fbp-l{background:#ff6b6b;box-shadow:0 0 5px rgba(255,107,107,.5)}.fbp-pair{font-size:11px;font-weight:600;color:#eeeef5}.fbp-t{font-size:9px;color:rgba(255,255,255,.28)}.fbp-pnl{font-size:11px;font-weight:700;white-space:nowrap}.fbp-pw{color:#00d4aa}.fbp-pl{color:#ff6b6b}#foxbot-reconnect-toast{position:fixed;top:68px;left:50%;transform:translateX(-50%);background:rgba(14,14,22,.97);border:1px solid rgba(0,212,170,.5);border-radius:12px;padding:13px 18px;min-width:258px;z-index:10000;box-shadow:0 8px 24px rgba(0,0,0,.5);display:none;animation:fbpIn .4s ease-out}.rct-title{font-size:12px;font-weight:700;color:#00d4aa;margin-bottom:5px}.rct-gain{font-size:20px;font-weight:800;color:#00d4aa;margin:6px 0}.rct-body{font-size:11px;color:rgba(255,255,255,.65)}.rct-x{position:absolute;top:9px;right:11px;color:rgba(255,255,255,.35);cursor:pointer;font-size:14px}#foxbot-active-banner{position:fixed;top:66px;left:50%;transform:translateX(-50%);padding:5px 15px;background:rgba(0,212,170,.13);border:1px solid rgba(0,212,170,.38);border-radius:20px;font-size:11px;font-weight:600;color:#00d4aa;z-index:9997;display:none;align-items:center;gap:7px;pointer-events:none;animation:fbpIn .3s ease-out}#foxbot-active-banner.fba-on{display:flex}#foxbot-active-banner .fba-dot{width:7px;height:7px;border-radius:50%;background:#00d4aa;animation:pulse-live 1.2s ease-in-out infinite}@keyframes pnlFG{0%{background:rgba(0,212,170,0)}40%{background:rgba(0,212,170,.16)}100%{background:rgba(0,212,170,0)}}@keyframes pnlFR{0%{background:rgba(255,107,107,0)}40%{background:rgba(255,107,107,.16)}100%{background:rgba(255,107,107,0)}}.pfl-g{animation:pnlFG .8s ease-out}.pfl-r{animation:pnlFR .8s ease-out}';
    document.head.appendChild(s);

    var panel = document.createElement('div');
    panel.id = 'foxbot-activity-panel';
    panel.innerHTML = '<div id="fbp-hdr" onclick="fbpToggle()"><div id="fbp-title"><span>🤖</span><span>FoxBot Activity</span><span id="fbp-badge">0</span></div><span id="fbp-x" onclick="event.stopPropagation();fbpHide()">✕</span></div><div id="fbp-stats"><div class="fbp-s"><div class="fbp-sv" id="fbs-tot">0</div><div class="fbp-sl">Trades</div></div><div class="fbp-s"><div class="fbp-sv" id="fbs-win" style="color:#00d4aa">0</div><div class="fbp-sl">Wins</div></div><div class="fbp-s"><div class="fbp-sv" id="fbs-pnl" style="color:#00d4aa">+$0</div><div class="fbp-sl">P&L</div></div><div class="fbp-s"><div class="fbp-sv" id="fbs-wr" style="color:#00d4aa">0%</div><div class="fbp-sl">Win%</div></div></div><div id="fbp-list"><div style="text-align:center;padding:18px;color:rgba(255,255,255,.25);font-size:11px">En attente...</div></div>';
    document.body.appendChild(panel);

    var rct = document.createElement('div');
    rct.id = 'foxbot-reconnect-toast';
    rct.innerHTML = '<span class="rct-x" onclick="this.parentElement.style.display=\'none\'">✕</span><div class="rct-title">🤖 FoxBot a travaillé pendant votre absence</div><div class="rct-gain" id="rct-gain">+$0.00</div><div class="rct-body">pendant <span id="rct-elapsed">0min</span> — crédité sur votre compte</div>';
    document.body.appendChild(rct);

    var banner = document.createElement('div');
    banner.id = 'foxbot-active-banner';
    banner.innerHTML = '<span class="fba-dot"></span><span>🤖 FoxBot actif</span>';
    document.body.appendChild(banner);
})();

window._fbpStats = {total:0,wins:0,losses:0,pnl:0};
window.fbpToggle = function(){ var p=document.getElementById('foxbot-activity-panel'); if(p) p.classList.toggle('fbp-on'); };
window.fbpHide   = function(){ var p=document.getElementById('foxbot-activity-panel'); if(p) p.classList.remove('fbp-on'); };
window.fbpShow   = function(){ var p=document.getElementById('foxbot-activity-panel'); if(p) p.classList.add('fbp-on'); };

function foxbotAddActivity(symbol, pnl, isWin, side) {
    var st = window._fbpStats;
    st.total++; st.pnl += pnl;
    if (isWin) st.wins++; else st.losses++;
    var wr = st.total > 0 ? ((st.wins/st.total)*100).toFixed(0) : 0;
    var pc = st.pnl >= 0 ? '#00d4aa' : '#ff6b6b';
    document.getElementById('fbs-tot').textContent = st.total;
    document.getElementById('fbs-win').textContent = st.wins;
    var pe = document.getElementById('fbs-pnl');
    pe.textContent = (st.pnl >= 0 ? '+' : '') + '$' + Math.abs(st.pnl).toFixed(2);
    pe.style.color = pc;
    var we = document.getElementById('fbs-wr');
    we.textContent = wr + '%';
    we.style.color = wr >= 70 ? '#00d4aa' : '#ff6b6b';
    var badge = document.getElementById('fbp-badge');
    if (badge) badge.textContent = st.total;
    var list = document.getElementById('fbp-list');
    if (list) {
        var ph = list.querySelector('[style*="text-align"]');
        if (ph) ph.remove();
        var item = document.createElement('div');
        item.className = 'fbp-item';
        var ps = (pnl >= 0 ? '+$' : '-$') + Math.abs(pnl).toFixed(2);
        var ti = new Date().toLocaleTimeString('fr-FR',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
        item.innerHTML = '<div class="fbp-dot ' + (isWin ? 'fbp-w' : 'fbp-l') + '"></div><div style="flex:1;min-width:0"><div class="fbp-pair">' + (isWin ? '✅' : '❌') + ' ' + side + ' ' + symbol + '</div><div class="fbp-t">' + ti + '</div></div><div class="fbp-pnl ' + (isWin ? 'fbp-pw' : 'fbp-pl') + '">' + ps + '</div>';
        list.insertBefore(item, list.firstChild);
        while (list.children.length > 50) list.removeChild(list.lastChild);
    }
    document.querySelectorAll('[id*="balance"],[id*="Balance"],[class*="balance"]').forEach(function(el) {
        el.classList.remove('pfl-g','pfl-r'); void el.offsetWidth;
        el.classList.add(isWin ? 'pfl-g' : 'pfl-r');
        setTimeout(function(){ el.classList.remove('pfl-g','pfl-r'); }, 800);
    });
    if (st.total <= 5) fbpShow();
}

// ── 4. Patch FOXBOT_SYSTEM ────────────────────────────────────────
(function patchFoxBot() {
    if (typeof FOXBOT_SYSTEM === 'undefined') { setTimeout(patchFoxBot, 400); return; }

    var _oClose = FOXBOT_SYSTEM.closeBotPosition;
    FOXBOT_SYSTEM.closeBotPosition = async function(tradeId, symbol, isWin) {
        var td = this.state.openTrades[tradeId];
        await _oClose.call(this, tradeId, symbol, isWin);
        if (td) {
            var aprxPnl = isWin
                ? Math.abs(((td.tp||td.entry*1.02) - td.entry) * (td.volume||1) * (td.contractSize||1))
                : -Math.abs((td.entry - (td.sl||td.entry*0.985)) * (td.volume||1) * (td.contractSize||1));
            foxbotAddActivity(symbol, aprxPnl, isWin, td.side||'BUY');
        }
    };

    let liveSimInterval = null;
    const simSymbols = ['EUR/USD', 'BTC/USD', 'ETH/USD', 'XAU/USD', 'GBP/USD'];

    function startLiveSim() {
        if (liveSimInterval) clearInterval(liveSimInterval);
        liveSimInterval = setInterval(async () => {
            if (!FOXBOT_SYSTEM.state.isRunning) return;
            const accType = (typeof state !== 'undefined' && state.accountMode) ? state.accountMode : 'demo';
            const bal = accType === 'demo' ? (state.demoBalance||0) : (state.realBalance||0);
            if (bal <= 0) return;

            const isWin = Math.random() < 0.80; // 80% gain, 20% perte
            // 15 sec interval = 240 ticks/h. Gain cible 1.5%/h => ~0.00781%, Perte cible 0.5%/h => ~0.0104%
            const rate = isWin ? 0.000078125 : 0.00010416;
            const amount = bal * rate;

            try {
                const CSRF = window.__TRADE?.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content;
                const res = await fetch('/trade/foxbot/tick', {
                    method: 'POST', credentials: 'include',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ is_win: isWin, amount: amount, account_type: accType })
                });
                const data = await res.json();
                if (data && data.success && data.balance) {
                    if (typeof state !== 'undefined') {
                        state.demoBalance = data.balance.demo_balance;
                        state.realBalance = data.balance.real_balance;
                        if (typeof updateBalanceDisplay === 'function') updateBalanceDisplay();
                    }
                    const sym = simSymbols[Math.floor(Math.random() * simSymbols.length)];
                    const side = Math.random() > 0.5 ? 'BUY' : 'SELL';
                    foxbotAddActivity(sym, isWin ? amount : -amount, isWin, side);
                }
            } catch (e) {}
        }, 15000);
    }

    var _oStart = FOXBOT_SYSTEM.start;
    FOXBOT_SYSTEM.start = async function(botId) {
        await _oStart.call(this, botId);
        var b = document.getElementById('foxbot-active-banner');
        if (b) b.classList.add('fba-on');
        startLiveSim();
    };

    var _oStop = FOXBOT_SYSTEM.stop;
    FOXBOT_SYSTEM.stop = async function() {
        await _oStop.call(this);
        var b = document.getElementById('foxbot-active-banner');
        if (b) b.classList.remove('fba-on');
        if (liveSimInterval) clearInterval(liveSimInterval);
    };

    if (FOXBOT_SYSTEM.state.isRunning) startLiveSim();

    console.log('[FoxBot] 🎨 Activity panel + animations patched');
})();

// ── 5. Arrêt du FoxBot à la fermeture de la page ──────────────────
// Pas de suivi des jours d'absence : si l'utilisateur ferme la page,
// le bot est simplement arrêté et devra être réactivé manuellement.
window.addEventListener('beforeunload', function() {
    if (typeof FOXBOT_SYSTEM === 'undefined' || !FOXBOT_SYSTEM.state || !FOXBOT_SYSTEM.state.isRunning) return;

    FOXBOT_SYSTEM.stop();

    var accType = (typeof state !== 'undefined' && state.account) ? state.account : 'demo';
    try {
        fetch('/api/pilotiq/settings', {
            method: 'PATCH',
            credentials: 'include',
            keepalive: true,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ bot_active: false, account_type: accType }),
        });
    } catch (e) {}
});

// ═══════════════════════════════════════════════════════════════════
// 🚀 TRADE PILOTIQ — Bridge pour le nouveau UI trading.html
// Injecte les données Laravel dans les variables JS du nouveau UI
// ═══════════════════════════════════════════════════════════════════

(function pilotiqBridge() {
    'use strict';
    const T = window.__TRADE || {};
    const CSRF = T.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '';

    // ── Helper fetch ─────────────────────────────────────────────────
    async function laravelPost(url, data) {
        const res = await fetch(url, {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify(data),
        });
        return res.json();
    }

    async function laravelPatch(url, data) {
        const res = await fetch(url, {
            method: 'PATCH',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify(data),
        });
        return res.json().catch(() => ({}));
    }

    // ── Injection des données dans le nouveau UI ─────────────────────
    function injectLaravelData() {
        // User
        if (typeof user !== 'undefined' && T.user) {
            user.name   = T.user.name   || user.name;
            user.email  = T.user.email  || user.email;
            user.avatar = T.user.avatar || user.avatar;
            user.id     = T.user.id     || user.id;
        }

        // Balances
        if (typeof accounts !== 'undefined') {
            accounts.real.balance = parseFloat(T.realBalance) || accounts.real.balance;
            accounts.real.equity  = parseFloat(T.realBalance) || accounts.real.balance;
            accounts.demo.balance = parseFloat(T.demoBalance) || accounts.demo.balance;
            accounts.demo.equity  = parseFloat(T.demoBalance) || accounts.demo.balance;
        }

        // Compte actif (bot state)
        if (typeof state !== 'undefined' && T.botState) {
            state.account = T.botState.account_type || 'demo';
        }

        // Positions ouvertes
        if (typeof openPositions !== 'undefined' && T.openPositions && T.openPositions.length) {
            openPositions.length = 0;
            T.openPositions.forEach(function(p) {
                openPositions.push({
                    id:           'POS-' + p.id,
                    pair:         (p.symbol || '').replace(/([A-Z]{3})([A-Z]{3})/, '$1/$2').replace('BTCUSD','BTC/USD').replace('ETHUSD','ETH/USD').replace('EURUSD','EUR/USD').replace('GBPUSD','GBP/USD') || p.symbol,
                    type:         p.side === 'buy' ? 'buy' : 'sell',
                    lots:         parseFloat(p.qty)   || 0.01,
                    openPrice:    parseFloat(p.entry) || 0,
                    currentPrice: parseFloat(p.entry) || 0,
                    sl:           p.sl   ? parseFloat(p.sl)   : null,
                    tp:           p.tp   ? parseFloat(p.tp)   : null,
                    openTime:     p.time ? new Date(p.time).toISOString().slice(0,16).replace('T',' ') : '—',
                    profit:       0,
                    swap:         0,
                    _dbId:        p.id,
                });
            });
        }

        // Historique
        if (typeof tradeHistory !== 'undefined' && T.closedHistory && T.closedHistory.length) {
            tradeHistory.length = 0;
            T.closedHistory.forEach(function(h) {
                tradeHistory.push({
                    id:         'TRD-' + h.id,
                    pair:       (h.symbol || '').replace(/([A-Z]{3})([A-Z]{3})/, '$1/$2') || h.symbol,
                    type:       h.side === 'buy' ? 'buy' : 'sell',
                    lots:       parseFloat(h.qty)   || 0.01,
                    openPrice:  parseFloat(h.entry) || 0,
                    closePrice: parseFloat(h.exit)  || 0,
                    openTime:   '—',
                    closeTime:  h.time ? new Date(h.time).toISOString().slice(0,16).replace('T',' ') : '—',
                    profit:     parseFloat(h.pnl)   || 0,
                    commission: 0.5,
                    _dbId:      h.id,
                });
            });
        }

        // Re-render nouveau UI
        setTimeout(function() {
            if (typeof refreshNav === 'function') refreshNav();
            if (typeof renderMarkets === 'function') renderMarkets();
            if (typeof renderWs === 'function') renderWs();
            if (typeof updatePairBar === 'function') updatePairBar();

            // Injecter user dans le nav
            const av = document.getElementById('navAvatar');
            const nm = document.getElementById('navName');
            const bl = document.getElementById('navBal');
            if (av && T.user) av.textContent = T.user.avatar;
            if (nm && T.user) nm.textContent = T.user.name;
            if (bl && typeof accounts !== 'undefined') {
                const acc = accounts[typeof state !== 'undefined' ? state.account : 'demo'];
                bl.textContent = '$' + (acc.balance || 0).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
            }

            // Ajouter dropdown logout sur .nav-user
            const navUser = document.querySelector('.nav-user');
            if (navUser && !navUser.querySelector('.pilotiq-dropdown')) {
                navUser.style.cursor = 'pointer';
                navUser.style.position = 'relative';
                const dd = document.createElement('div');
                dd.className = 'pilotiq-dropdown';
                dd.style.cssText = 'display:none;position:absolute;top:100%;right:0;margin-top:6px;background:#18181d;border:1px solid #26262e;border-radius:10px;min-width:180px;z-index:999;overflow:hidden;box-shadow:0 8px 24px #0008;';
                dd.innerHTML = [
                    '<a href="/dashboard" style="display:flex;align-items:center;gap:10px;padding:10px 14px;color:#eeeef5;text-decoration:none;font-size:12px;border-bottom:1px solid #26262e;">📊 Tableau de bord</a>',
                    '<a href="/wallet" style="display:flex;align-items:center;gap:10px;padding:10px 14px;color:#eeeef5;text-decoration:none;font-size:12px;border-bottom:1px solid #26262e;">💼 Portefeuille</a>',
                    '<a href="/profile" style="display:flex;align-items:center;gap:10px;padding:10px 14px;color:#eeeef5;text-decoration:none;font-size:12px;border-bottom:1px solid #26262e;">👤 Mon profil</a>',
                    '<a href="#" onclick="event.preventDefault();document.getElementById(\'logout-form-laravel\').submit();" style="display:flex;align-items:center;gap:10px;padding:10px 14px;color:#ff4466;text-decoration:none;font-size:12px;">🚪 Déconnexion</a>',
                ].join('');
                navUser.appendChild(dd);
                navUser.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
                });
                document.addEventListener('click', function() { dd.style.display = 'none'; });
            }
        }, 300);
    }

    // Lancer l'injection après le DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', injectLaravelData);
    } else {
        setTimeout(injectLaravelData, 50);
    }

    // ── Override executeTrade() → Laravel openPosition ────────────────
    window.addEventListener('load', function() {
        if (typeof executeTrade !== 'function') return;
        const _orig = executeTrade;

        window.executeTrade = async function() {
            if (!window.__TRADE || !window.__TRADE.routes) {
                return _orig.apply(this, arguments); // fallback
            }
            const R = window.__TRADE.routes;
            if (!R.openPosition) return _orig.apply(this, arguments);

            const lot   = parseFloat(document.getElementById('lotIn')?.value) || 0.01;
            const sl    = parseFloat(document.getElementById('slIn')?.value)  || null;
            const tp    = parseFloat(document.getElementById('tpIn')?.value)  || null;
            const m     = (typeof state !== 'undefined' && state.pair) ? state.pair : null;
            if (!m) return _orig.apply(this, arguments);

            const side       = (typeof state !== 'undefined') ? state.side : 'buy';
            const account    = (typeof state !== 'undefined') ? state.account : 'demo';
            const price      = m.price;
            const entryPrice = side === 'buy' ? price + m.spread / 2 : price - m.spread / 2;
            const posValue   = lot * price * 100;
            const margin     = posValue / 100; // leverage 100
            const symbol     = m.pair.replace('/', '');

            try {
                const res = await laravelPost(R.openPosition, {
                    symbol,
                    direction:     side === 'buy' ? 'BUY' : 'SELL',
                    volume:        lot,
                    entry_price:   parseFloat(entryPrice.toFixed(m.digits)),
                    stop_loss:     sl,
                    take_profit:   tp,
                    margin:        parseFloat(margin.toFixed(2)),
                    contract_size: 1,
                    account_type:  account,
                    is_bot:        (typeof state !== 'undefined' && state.botOn) ? 1 : 0,
                });

                if (res.error) {
                    if (typeof toast === 'function') toast('Erreur serveur', res.error, 'err');
                    return;
                }

                // Mettre à jour les balances
                if (res.balance && typeof accounts !== 'undefined') {
                    accounts.real.balance = parseFloat(res.balance.real_balance) || accounts.real.balance;
                    accounts.real.equity  = accounts.real.balance;
                    accounts.demo.balance = parseFloat(res.balance.demo_balance) || accounts.demo.balance;
                    accounts.demo.equity  = accounts.demo.balance;
                    if (typeof refreshNav === 'function') refreshNav();
                }

                // Ajouter position localement
                if (typeof openPositions !== 'undefined') {
                    openPositions.push({
                        id:           'POS-' + res.trade_id,
                        pair:         m.pair,
                        type:         side,
                        lots:         lot,
                        openPrice:    entryPrice,
                        currentPrice: entryPrice,
                        sl, tp,
                        openTime:     new Date().toISOString().slice(0,16).replace('T',' '),
                        profit:       0,
                        swap:         0,
                        _dbId:        res.trade_id,
                    });
                    if (typeof renderWs === 'function') renderWs();
                }

                if (typeof toast === 'function') {
                    toast('Ordre exécuté ✓',
                        (side === 'buy' ? '▲ ACHAT' : '▼ VENTE') + ' ' + lot + ' lot(s) ' + m.pair + ' @ ' + entryPrice.toFixed(m.digits),
                        'ok');
                }

                const btn = document.getElementById('tradeBtn');
                if (btn) { btn.style.transform = 'scale(.96)'; setTimeout(() => btn.style.transform = '', 160); }

            } catch (e) {
                console.error('[Pilotiq] executeTrade error:', e);
                if (typeof toast === 'function') toast('Erreur réseau', e.message, 'err');
            }
        };
    });

    // ── Override closePos() → Laravel closePosition ───────────────────
    window.addEventListener('load', function() {
        if (typeof closePos !== 'function') return;
        const _orig = closePos;

        window.closePos = async function(id) {
            if (!window.__TRADE || !window.__TRADE.routes) return _orig(id);
            const R = window.__TRADE.routes;
            if (!R.closePosition) return _orig(id);

            const pos = (typeof openPositions !== 'undefined') ? openPositions.find(p => p.id === id) : null;
            if (!pos) return _orig(id);

            const dbId     = pos._dbId || id.replace('POS-', '');
            const closeUrl = R.closePosition.replace('__ID__', dbId);
            const mkt      = (typeof markets !== 'undefined') ? markets.find(mk => mk.pair === pos.pair) : null;
            const exitPx   = mkt ? mkt.price : pos.openPrice;

            try {
                const res = await laravelPost(closeUrl, { exit_price: exitPx, close_reason: 'Manuel' });
                if (res.error) {
                    if (typeof toast === 'function') toast('Erreur', res.error, 'err');
                    return;
                }

                // Balances
                if (res.balance && typeof accounts !== 'undefined') {
                    accounts.real.balance = parseFloat(res.balance.real_balance) || accounts.real.balance;
                    accounts.real.equity  = accounts.real.balance;
                    accounts.demo.balance = parseFloat(res.balance.demo_balance) || accounts.demo.balance;
                    accounts.demo.equity  = accounts.demo.balance;
                    if (typeof refreshNav === 'function') refreshNav();
                }

                const pnl = res.pnl ?? pos.profit;

                // Historique local
                if (typeof openPositions !== 'undefined') openPositions = openPositions.filter(p => p.id !== id);
                if (typeof tradeHistory !== 'undefined') {
                    tradeHistory.unshift({
                        id: id.replace('POS','TRD'), pair: pos.pair, type: pos.type,
                        lots: pos.lots, openPrice: pos.openPrice, closePrice: exitPx,
                        openTime: pos.openTime,
                        closeTime: new Date().toISOString().slice(0,16).replace('T',' '),
                        profit: pnl, commission: 0.5,
                    });
                }

                if (typeof renderWs === 'function') renderWs();
                if (typeof toast === 'function') {
                    toast('Position fermée', pos.pair + ' P&L: ' + (pnl >= 0 ? '+' : '') + pnl.toFixed(2) + ' USD', pnl >= 0 ? 'ok' : 'err');
                }
            } catch (e) {
                console.error('[Pilotiq] closePos error:', e);
                _orig(id);
            }
        };
    });

    // ── Persist bot state → /api/pilotiq/settings ──────────────────
    window.addEventListener('load', function() {
        if (typeof toggleBot !== 'function') return;
        const _orig = toggleBot;

        window.toggleBot = function() {
            _orig.apply(this, arguments);
            const isOn  = typeof state !== 'undefined' ? state.botOn : false;
            const accT  = typeof state !== 'undefined' ? state.account : 'demo';
            laravelPatch('/api/pilotiq/settings', {
                bot_active:       isOn,
                bot_activated_at: isOn ? new Date().toISOString() : null,
                account_type:     accT,
            }).catch(e => console.warn('[Pilotiq] bot settings persist failed:', e));
        };
    });

})();
// ═══════════════════════════════════════════════════════════════════
</script>
<?php
$overridesHtml = ob_get_clean();

// ── Charger et patcher le trade.html ───────────────────────────────────
// Charger le nouveau UI TradePilotiq si disponible, sinon fallback
$pilotiqPath = 'C:\\Users\\HP\\Documents\\Metatrader\\trading.html';
$htmlPath    = file_exists($pilotiqPath) ? $pilotiqPath : base_path('trade.html');

$html = file_get_contents($htmlPath);
$html = str_replace('</head>', $headInjection . "\n</head>", $html);
$html = str_replace('</body>', $overridesHtml . "\n</body>", $html);

// ── Créer le dropdown du profil utilisateur ──────────────────────────────
$userProfileHtml = '<div class="user-profile-dropdown" id="userProfileDropdown" onclick="event.stopPropagation(); document.getElementById(\'userProfileDropdown\').classList.toggle(\'active\');">' . "\n";

$userProfileHtml .= '    <div class="user-avatar">' . "\n";
if ($userAvatar) {
    $userProfileHtml .= '        <img src="' . htmlspecialchars($userAvatar) . '" alt="Avatar" title="' . htmlspecialchars($user->first_name ?? $user->name ?? '') . '">' . "\n";
} else {
    $userProfileHtml .= '        ' . $userInitial . "\n";
}
$userProfileHtml .= '    </div>' . "\n";

$userProfileHtml .= '    <div class="user-info">' . "\n"
    . '        <div class="user-name">' . htmlspecialchars($user->first_name ?? $user->name ?? 'User') . '</div>' . "\n"
    . '        <div class="user-email">' . htmlspecialchars($user->email ?? '') . '</div>' . "\n"
    . '    </div>' . "\n";

// Ajouter le dropdown menu avec les 4 boutons
$dashboardUrl = route('dashboard') ?? '#';
$walletUrl = route('wallet.index') ?? '#';
$profileUrl = route('profile.edit') ?? '#';
$logoutUrl = route('auth.logout') ?? '#';

$userProfileHtml .= '    <div class="dropdown-menu">' . "\n"
    . '        <a href="' . htmlspecialchars($dashboardUrl) . '" class="dropdown-item">📊 <span>Tableau de bord</span></a>' . "\n"
    . '        <a href="' . htmlspecialchars($walletUrl) . '" class="dropdown-item">💼 <span>Portefeuille</span></a>' . "\n"
    . '        <a href="' . htmlspecialchars($profileUrl) . '" class="dropdown-item">👤 <span>Mon profil</span></a>' . "\n"
    . '        <a href="#" class="dropdown-item" onclick="event.preventDefault(); if(confirm(\'Êtes-vous sûr de vouloir vous déconnecter ?\')) { document.getElementById(\'logout-form\').submit(); }">🚪 <span>Déconnexion</span></a>' . "\n"
    . '    </div>' . "\n"
    . '</div>' . "\n"
    . '<form id="logout-form" action="' . htmlspecialchars($logoutUrl) . '" method="POST" style="display: none;">' . "\n"
    . '    ' . csrf_field() . "\n"
    . '</form>';

// Injecter le formulaire de logout (requis pour la sécurité)
$logoutFormHtml = '<form id="logout-form-laravel" action="' . (route('auth.logout') ?? '#') . '" method="POST" style="display:none;">' . csrf_field() . '</form>';
$html = str_replace('</body>', $logoutFormHtml . "\n</body>", $html);

// Pour l'ancien UI (trade.html): injection du profil utilisateur dans le live-badge
if (str_contains($html, 'data-i18n="live"')) {
    $html = str_replace(
        '<div class="live-badge"><span></span><span data-i18n="live">LIVE</span></div>',
        $userProfileHtml . "\n" . '<div class="live-badge"><span></span><span data-i18n="live">LIVE</span></div>',
        $html
    );
}

echo $html;

/**
 * ==============================================================================
 * ╔═════════════════════════════════════════════════════════════════════════╗
 * ║                   ANCIEN CODE (V1 - BACKUP)                            ║
 * ║                                                                         ║
 * ║ Cette section contient l'ancienne approche qui utilisait output         ║
 * ║ buffering pour charger la vue dynamiquement.                           ║
 * ║                                                                         ║
 * ║ POUR REVENIR À L'ANCIENNE VERSION EN CAS DE PROBLÈME:                 ║
 * ║  1. Décommenter le code ci-dessous jusqu'à "END OLD CODE"             ║
 * ║  2. Commenter les 40 lignes de code ci-dessus                         ║
 * ║                                                                         ║
 * ║ RAISON DU CHANGEMENT:                                                   ║
 * ║  - Éviter les appels externes au fichier trade.html                    ║
 * ║  - Code directement intégré dans la vue                                ║
 * ║  - Plus facile à maintenir et à déboguer                               ║
 * ║                                                                         ║
 * ╚═════════════════════════════════════════════════════════════════════════╝
 * 
 * 
 * // ────────────────────────────────────────────────────────────────────────
 * // DÉBUT ANCIEN CODE V1 (À DÉCOMMENTER SI BESOIN)
 * // ────────────────────────────────────────────────────────────────────────
 *
 * OLD_VERSION_V1: {
 * 
 * // Charger et patcher le trade.html (ANCIENNE MÉTHODE - NE PAS UTILISER)
 * $htmlPath = base_path('trade.html');
 * if (!file_exists($htmlPath)) {
 *     abort(404, 'trade.html introuvable. Veuillez le placer à la racine du projet.');
 * }
 * 
 * $html = file_get_contents($htmlPath);
 * $html = str_replace('</head>', $headInjection . "\n</head>", $html);
 * $html = str_replace('</body>', $overridesHtml . "\n</body>", $html);
 * 
 * // Ancienne injection du profil utilisateur via DOM manipulation en JavaScript
 * $headInjection .= '<style>
 *     .topbar-logo-wrapper { display: flex; flex-direction: column; gap: 2px; flex-shrink: 0; }
 *     .user-profile { display: flex; align-items: center; gap: 8px; padding: 4px 8px; 
 *                     border-radius: 6px; background: rgba(0, 212, 170, 0.08); 
 *                     border: 1px solid rgba(0, 212, 170, 0.2); width: fit-content; }
 *     .user-avatar { width: 24px; height: 24px; border-radius: 50%; display: flex; 
 *                    align-items: center; justify-content: center; font-weight: 600; 
 *                    font-size: 11px; color: white; flex-shrink: 0; background: ' . $userAvatarBg . '; }
 *     .user-info { flex: 1; min-width: 0; }
 *     .user-name { font-size: 10px; font-weight: 600; color: var(--text-primary); 
 *                  white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
 *     .user-email { font-size: 9px; color: var(--text-secondary); 
 *                   white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
 * </style>';
 * 
 * // Injecter via JavaScript au chargement
 * $headInjection .= '<script>
 * document.addEventListener("DOMContentLoaded", function() {
 *     const userProfileHTML = `<div class="user-profile">...</div>`;
 *     const logoEl = document.querySelector(".topbar-logo");
 *     if (logoEl) { ... }
 * });
 * </script>';
 * 
 * echo $html;
 * 
 * }
 * 
 * // ────────────────────────────────────────────────────────────────────────
 * // FIN ANCIEN CODE V1 (À DÉCOMMENTER SI BESOIN)
 * // ────────────────────────────────────────────────────────────────────────
 */
