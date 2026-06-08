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
        @media (max-width: 1024px) {
            .user-profile-dropdown { padding: 5px 10px; }
            .user-avatar { width: 26px; height: 26px; font-size: 11px; }
            .user-name { font-size: 10px; }
            .user-email { font-size: 8px; }
            .dropdown-menu { min-width: 170px; }
            .dropdown-menu a { padding: 8px 12px; font-size: 12px; }
        }
        @media (max-width: 768px) {
            .user-profile-dropdown { padding: 4px 8px; gap: 6px; }
            .user-avatar { width: 24px; height: 24px; font-size: 10px; }
            .user-info { display: none; }
            .dropdown-menu { min-width: 150px; right: -5px; }
            .dropdown-menu a { padding: 8px 10px; font-size: 12px; }
            .live-badge { padding: 4px 8px; font-size: 10px; }
            .live-badge span:first-child { width: 6px; height: 6px; }
        }
        @media (max-width: 480px) {
            .user-avatar { width: 22px; height: 22px; font-size: 9px; }
            .dropdown-menu { min-width: 140px; }
            .dropdown-menu a { padding: 6px 8px; font-size: 11px; gap: 8px; }
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
    console.log('[Trade] 🚀 Initializing Puprime Fox workspace...');
    
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
        
        console.log('[Trade] ✅ Workspace initialized successfully');
    } catch (e) {
        console.error('[Trade] ❌ Init error:', e.message);
    }
}

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
// - Realistic delay simulation (5-25 seconds)
// - Full BDD persistence with transaction support
// - Dynamic risk management
// ═══════════════════════════════════════════════════════════════════════════

const FOXBOT_SYSTEM = {
    config: {
        minWinRate:        0.70,       // 70% minimum win rate
        maxWinRate:        0.80,       // 80% maximum win rate
        minHoldTimeMs:     5000,       // 5 seconds minimum
        maxHoldTimeMs:     25000,      // 25 seconds maximum
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

        // Random delay between cycles (8-20 seconds)
        const delayMs = 8000 + Math.random() * 12000;
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

        console.log(`[FoxBot] Trade ${tradeId}: ${isWin ? '✅ WIN' : '❌ LOSS'} in ${holdTimeMs.toFixed(0)}ms`);

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
