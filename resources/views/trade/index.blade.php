<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Trade — Workspace</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ============================================================
   RESET & VARIABLES
   ============================================================ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --bg-deep:    #060A12;
  --bg-panel:   #0D1320;
  --bg-card:    #111827;
  --bg-hover:   #1A2235;
  --bg-input:   #0A0F1E;
  --border:     #1E2D45;
  --border-lit: #2A4A6E;

  --cyan:       #00D4FF;
  --cyan-dim:   #0099BB;
  --green:      #00FF88;
  --green-dim:  #00CC6A;
  --red:        #FF3B5C;
  --red-dim:    #CC2040;
  --gold:       #FFB800;
  --white:      #E8EDF5;
  --muted:      #4A6080;
  --muted2:     #2A3F58;

  --font-ui:    'Inter', sans-serif;
  --font-mono:  'JetBrains Mono', monospace;

  --radius-sm:  4px;
  --radius:     8px;
  --radius-lg:  12px;

  --h-header:   56px;
  --w-sidebar:  240px;
  --w-order:    280px;
  --h-bottom:   220px;
}

html, body { height: 100%; overflow: hidden; }

body {
  font-family: var(--font-ui);
  background: var(--bg-deep);
  color: var(--white);
  font-size: 13px;
  line-height: 1.4;
}

/* ============================================================
   LAYOUT GRID
   ============================================================ */
#app {
  display: grid;
  grid-template-rows: var(--h-header) 1fr var(--h-bottom);
  grid-template-columns: var(--w-sidebar) 1fr var(--w-order);
  height: 100vh;
  gap: 0;
}

/* ============================================================
   HEADER
   ============================================================ */
#header {
  grid-column: 1 / -1;
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 0 16px;
  background: var(--bg-panel);
  border-bottom: 1px solid var(--border);
  z-index: 100;
}

.logo {
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: var(--font-mono);
  font-weight: 700;
  font-size: 15px;
  color: var(--cyan);
  text-transform: uppercase;
  letter-spacing: 2px;
  white-space: nowrap;
  margin-right: 8px;
}
.logo-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--cyan); box-shadow: 0 0 10px var(--cyan); }

.header-divider { width: 1px; height: 32px; background: var(--border); }

.balance-block {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  min-width: 100px;
}
.balance-label { font-size: 10px; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; }
.balance-value {
  font-family: var(--font-mono);
  font-size: 16px;
  font-weight: 700;
  color: var(--white);
}
.balance-value.up { color: var(--green); }
.balance-value.down { color: var(--red); }

.equity-block {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  min-width: 100px;
}

.pnl-header {
  font-family: var(--font-mono);
  font-size: 14px;
  font-weight: 600;
}

#account-mode {
  display: flex;
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
  margin-left: auto;
}
.mode-btn {
  padding: 6px 16px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  cursor: pointer;
  border: none;
  background: transparent;
  color: var(--muted);
  transition: all 0.2s;
}
.mode-btn.active.real { background: var(--cyan); color: #000; }
.mode-btn.active.demo { background: var(--gold); color: #000; }
.mode-btn:not(.active):hover { color: var(--white); }

.user-chip {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 6px 12px;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
}
.user-avatar {
  width: 28px; height: 28px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--cyan), #0060AA);
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 11px; color: #000;
}
.user-name { font-size: 12px; font-weight: 500; color: var(--white); }

/* ============================================================
   SIDEBAR — MARKETS
   ============================================================ */
#sidebar {
  background: var(--bg-panel);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.sidebar-search {
  padding: 10px;
  border-bottom: 1px solid var(--border);
}
.search-input {
  width: 100%;
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 7px 10px 7px 30px;
  color: var(--white);
  font-family: var(--font-ui);
  font-size: 12px;
  outline: none;
  transition: border-color 0.2s;
  position: relative;
}
.search-wrap { position: relative; }
.search-icon {
  position: absolute; left: 9px; top: 50%; transform: translateY(-50%);
  color: var(--muted); font-size: 12px; pointer-events: none;
}
.search-input:focus { border-color: var(--cyan); }

.sidebar-tabs {
  display: flex;
  border-bottom: 1px solid var(--border);
}
.stab {
  flex: 1;
  padding: 7px 4px;
  text-align: center;
  font-size: 10px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  cursor: pointer;
  color: var(--muted);
  border-bottom: 2px solid transparent;
  transition: all 0.2s;
}
.stab.active { color: var(--cyan); border-bottom-color: var(--cyan); }
.stab:hover:not(.active) { color: var(--white); }

.market-list {
  flex: 1;
  overflow-y: auto;
  padding: 4px 0;
}
.market-list::-webkit-scrollbar { width: 4px; }
.market-list::-webkit-scrollbar-track { background: transparent; }
.market-list::-webkit-scrollbar-thumb { background: var(--muted2); border-radius: 2px; }

.market-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 9px 12px;
  cursor: pointer;
  border-left: 2px solid transparent;
  transition: all 0.15s;
}
.market-item:hover { background: var(--bg-hover); }
.market-item.active {
  background: rgba(0,212,255,0.06);
  border-left-color: var(--cyan);
}
.market-symbol { font-family: var(--font-mono); font-weight: 600; font-size: 12px; color: var(--white); }
.market-name { font-size: 10px; color: var(--muted); margin-top: 1px; }
.market-price-col { text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 2px; }
.market-price { font-family: var(--font-mono); font-size: 12px; font-weight: 500; }
.market-change { font-family: var(--font-mono); font-size: 10px; }
.market-bid-ask {
  display: flex;
  gap: 4px;
  font-family: var(--font-mono);
  font-size: 10px;
}
.market-bid-ask .bid-val { color: var(--red); }
.market-bid-ask .ask-val { color: var(--green); }
.market-bid-ask .sep { color: var(--muted2); }
.up { color: var(--green); }
.down { color: var(--red); }

/* ============================================================
   CHART AREA
   ============================================================ */
#chart-area {
  display: flex;
  flex-direction: column;
  overflow: hidden;
  background: var(--bg-deep);
}

.chart-topbar {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 12px;
  background: var(--bg-panel);
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}
.chart-symbol-name {
  font-family: var(--font-mono);
  font-weight: 700;
  font-size: 16px;
  color: var(--white);
}
.chart-bid-ask {
  display: flex;
  gap: 12px;
  font-family: var(--font-mono);
  font-size: 13px;
}
.chart-spread {
  font-size: 10px;
  color: var(--muted);
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 2px 7px;
}

.timeframe-bar {
  display: flex;
  gap: 2px;
  margin-left: auto;
}
.tf-btn {
  padding: 3px 8px;
  font-size: 10px;
  font-weight: 600;
  font-family: var(--font-mono);
  background: transparent;
  border: 1px solid transparent;
  border-radius: var(--radius-sm);
  color: var(--muted);
  cursor: pointer;
  transition: all 0.15s;
}
.tf-btn:hover { color: var(--white); border-color: var(--border); }
.tf-btn.active { background: var(--bg-input); border-color: var(--border-lit); color: var(--cyan); }

#tradingview-widget { flex: 1; overflow: hidden; }
#tradingview-widget iframe { width: 100%; height: 100%; }

/* ============================================================
   ORDER PANEL
   ============================================================ */
#order-panel {
  background: var(--bg-panel);
  border-left: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.order-header {
  padding: 12px;
  border-bottom: 1px solid var(--border);
  font-weight: 600;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--cyan);
}

.order-body {
  flex: 1;
  overflow-y: auto;
  padding: 12px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.order-body::-webkit-scrollbar { width: 4px; }
.order-body::-webkit-scrollbar-thumb { background: var(--muted2); }

.field-label {
  font-size: 10px;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.8px;
  margin-bottom: 4px;
}
.field-input {
  width: 100%;
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 8px 10px;
  color: var(--white);
  font-family: var(--font-mono);
  font-size: 13px;
  outline: none;
  transition: border-color 0.2s;
}
.field-input:focus { border-color: var(--cyan); }
.field-input[readonly] { color: var(--muted); cursor: default; }

.input-row { display: flex; gap: 6px; }
.input-row .field-input { flex: 1; }

.toggle-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
}
.toggle-label { font-size: 11px; color: var(--muted); }
.toggle-switch {
  position: relative;
  width: 36px;
  height: 18px;
  flex-shrink: 0;
}
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-track {
  position: absolute; inset: 0;
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: 10px;
  cursor: pointer;
  transition: background 0.2s;
}
.toggle-switch input:checked + .toggle-track { background: var(--cyan-dim); border-color: var(--cyan); }
.toggle-track::after {
  content: '';
  position: absolute;
  width: 12px; height: 12px;
  border-radius: 50%;
  background: var(--muted);
  top: 2px; left: 2px;
  transition: 0.2s;
}
.toggle-switch input:checked + .toggle-track::after {
  transform: translateX(18px);
  background: var(--cyan);
}

.optional-field { display: none; }
.optional-field.visible { display: block; }

.exec-btns-row {
  display: flex;
  gap: 8px;
}
.btn-exec-buy,
.btn-exec-sell {
  flex: 1;
  padding: 14px 8px;
  border: none;
  border-radius: var(--radius);
  font-family: var(--font-ui);
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  text-transform: uppercase;
  letter-spacing: 1px;
  transition: opacity 0.15s, transform 0.1s;
}
.btn-exec-buy {
  background: linear-gradient(135deg, var(--green-dim), var(--green));
  color: #000;
  box-shadow: 0 2px 16px rgba(0,255,136,0.25);
}
.btn-exec-sell {
  background: linear-gradient(135deg, var(--red-dim), var(--red));
  color: #fff;
  box-shadow: 0 2px 16px rgba(255,59,92,0.25);
}
.btn-exec-buy:hover,
.btn-exec-sell:hover  { opacity: 0.88; transform: translateY(-1px); }
.btn-exec-buy:active,
.btn-exec-sell:active { opacity: 1; transform: translateY(0); }

.separator { border: none; border-top: 1px solid var(--border); }

.price-display {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px;
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
}
.price-side { text-align: center; flex: 1; }
.price-side-label { font-size: 9px; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; }
.price-side-val {
  font-family: var(--font-mono);
  font-size: 18px;
  font-weight: 700;
}
.price-side-val.bid { color: var(--red); }
.price-side-val.ask { color: var(--green); }
.price-spread-mid { font-size: 10px; color: var(--muted); padding: 0 8px; }

.margin-info {
  display: flex;
  justify-content: space-between;
  font-size: 11px;
  color: var(--muted);
}
.margin-info span { font-family: var(--font-mono); color: var(--white); }

/* ============================================================
   BOTTOM PANEL — POSITIONS + HISTORY
   ============================================================ */
#bottom-panel {
  grid-column: 1 / -1;
  background: var(--bg-panel);
  border-top: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.bottom-tabs {
  display: flex;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
  padding: 0 12px;
}
.btab {
  padding: 8px 16px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  cursor: pointer;
  color: var(--muted);
  border-bottom: 2px solid transparent;
  transition: all 0.2s;
}
.btab.active { color: var(--cyan); border-bottom-color: var(--cyan); }
.btab:hover:not(.active) { color: var(--white); }
.badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 16px; height: 16px;
  border-radius: 50%;
  font-size: 9px;
  font-weight: 700;
  background: var(--cyan);
  color: #000;
  margin-left: 6px;
}

.bottom-content { flex: 1; overflow: hidden; }
.tab-panel { display: none; height: 100%; overflow-y: auto; }
.tab-panel.active { display: block; }
.tab-panel::-webkit-scrollbar { height: 4px; width: 4px; }
.tab-panel::-webkit-scrollbar-thumb { background: var(--muted2); }

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 11px;
}
thead th {
  padding: 6px 12px;
  text-align: left;
  font-size: 10px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: var(--muted);
  border-bottom: 1px solid var(--border);
  position: sticky;
  top: 0;
  background: var(--bg-panel);
  white-space: nowrap;
}
tbody tr {
  border-bottom: 1px solid rgba(30,45,69,0.5);
  transition: background 0.1s;
}
tbody tr:hover { background: var(--bg-hover); }
tbody td {
  padding: 7px 12px;
  white-space: nowrap;
  font-family: var(--font-mono);
  font-size: 11px;
}
td.symbol { font-weight: 700; color: var(--white); font-size: 12px; }

.type-badge {
  display: inline-block;
  padding: 2px 7px;
  border-radius: 3px;
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
}
.type-badge.buy { background: rgba(0,255,136,0.12); color: var(--green); border: 1px solid rgba(0,255,136,0.2); }
.type-badge.sell { background: rgba(255,59,92,0.12); color: var(--red); border: 1px solid rgba(255,59,92,0.2); }

.btn-close-pos {
  padding: 3px 10px;
  background: transparent;
  border: 1px solid var(--red);
  border-radius: var(--radius-sm);
  color: var(--red);
  font-size: 10px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s;
  font-family: var(--font-ui);
}
.btn-close-pos:hover { background: var(--red); color: #fff; }

.btn-mod-pos {
  padding: 3px 10px;
  background: transparent;
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  color: var(--muted);
  font-size: 10px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s;
  font-family: var(--font-ui);
}
.btn-mod-pos:hover { border-color: var(--cyan); color: var(--cyan); }

/* empty state */
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 120px;
  color: var(--muted);
  gap: 6px;
  font-size: 12px;
}
.empty-state svg { opacity: 0.3; }

/* ============================================================
   TOAST
   ============================================================ */
#toast-container {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 8px;
  pointer-events: none;
}
.toast {
  padding: 12px 16px;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  font-size: 12px;
  color: var(--white);
  box-shadow: 0 8px 32px rgba(0,0,0,0.5);
  animation: slideIn 0.3s ease;
  pointer-events: all;
  max-width: 300px;
}
.toast.success { border-color: var(--green); border-left: 3px solid var(--green); }
.toast.error   { border-color: var(--red);   border-left: 3px solid var(--red); }
.toast.info    { border-color: var(--cyan);  border-left: 3px solid var(--cyan); }
@keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }

/* ============================================================
   MODAL — Modifier position
   ============================================================ */
.modal-overlay {
  display: none;
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.7);
  z-index: 1000;
  align-items: center;
  justify-content: center;
  backdrop-filter: blur(4px);
}
.modal-overlay.open { display: flex; }
.modal-box {
  background: var(--bg-card);
  border: 1px solid var(--border-lit);
  border-radius: var(--radius-lg);
  padding: 24px;
  width: 340px;
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.modal-title {
  font-size: 14px;
  font-weight: 700;
  color: var(--cyan);
  text-transform: uppercase;
  letter-spacing: 1px;
}
.modal-actions { display: flex; gap: 8px; }
.btn-modal {
  flex: 1;
  padding: 10px;
  border-radius: var(--radius-sm);
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s;
  border: 1px solid var(--border);
  background: var(--bg-input);
  color: var(--white);
}
.btn-modal.primary {
  background: var(--cyan-dim);
  border-color: var(--cyan);
  color: #000;
}
.btn-modal:hover { opacity: 0.85; }

/* ============================================================
   SKELETON LOADING
   ============================================================ */
@keyframes shimmer {
  0%   { background-position: -400px 0; }
  100% { background-position: 400px 0; }
}
.skeleton {
  background: linear-gradient(90deg, var(--bg-card) 25%, var(--bg-hover) 50%, var(--bg-card) 75%);
  background-size: 800px 100%;
  animation: shimmer 1.4s infinite linear;
  border-radius: var(--radius-sm);
  display: inline-block;
}
.skeleton-text  { height: 12px; }
.skeleton-value { height: 18px; border-radius: 3px; }

.skeleton-market-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 9px 12px;
}
.skeleton-market-left { display: flex; flex-direction: column; gap: 5px; }
.skeleton-market-right { display: flex; flex-direction: column; gap: 5px; align-items: flex-end; }

/* loader spinner */
.loader {
  display: none;
  width: 14px; height: 14px;
  border: 2px solid var(--border);
  border-top-color: var(--cyan);
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

@keyframes flashGreen {
  0%   { background: rgba(0,255,136,0.2); }
  100% { background: transparent; }
}
@keyframes flashRed {
  0%   { background: rgba(255,59,92,0.2); }
  100% { background: transparent; }
}
.flash-up   { animation: flashGreen 0.6s ease; }
.flash-down { animation: flashRed   0.6s ease; }

/* ============================================================
   FOXBOT UI
   ============================================================ */
.order-tabs {
  display: flex;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}
.otab {
  flex: 1;
  padding: 9px 4px;
  text-align: center;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  cursor: pointer;
  color: var(--muted);
  border-bottom: 2px solid transparent;
  transition: all 0.2s;
}
.otab.active { color: var(--cyan); border-bottom-color: var(--cyan); }
.otab:hover:not(.active) { color: var(--white); }

.foxbot-body {
  display: none;
  flex: 1;
  overflow-y: auto;
  padding: 12px;
  flex-direction: column;
  gap: 10px;
}
.foxbot-body.visible { display: flex; }
.foxbot-body::-webkit-scrollbar { width: 4px; }
.foxbot-body::-webkit-scrollbar-thumb { background: var(--muted2); }

/* Bot card */
.bot-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 12px;
  cursor: pointer;
  transition: all 0.2s;
  position: relative;
  overflow: hidden;
}
.bot-card:hover { border-color: var(--border-lit); background: var(--bg-hover); }
.bot-card.selected { border-color: var(--cyan); box-shadow: 0 0 0 1px var(--cyan), 0 4px 20px rgba(0,212,255,0.12); }
.bot-card-top { display: flex; align-items: center; gap: 10px; }
.bot-emoji { font-size: 24px; line-height: 1; }
.bot-info { flex: 1; min-width: 0; }
.bot-name { font-weight: 700; font-size: 13px; color: var(--white); }
.bot-strategy { font-size: 10px; color: var(--cyan); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.bot-badge {
  font-size: 10px;
  padding: 2px 8px;
  border-radius: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.bot-badge.active { background: rgba(0,255,136,0.12); color: var(--green); border: 1px solid rgba(0,255,136,0.3); }
.bot-badge.off    { background: rgba(255,59,92,0.10);  color: var(--red);   border: 1px solid rgba(255,59,92,0.2); }

.bot-stats-row {
  display: flex;
  gap: 6px;
  margin-top: 10px;
}
.bot-stat {
  flex: 1;
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 6px 8px;
  text-align: center;
}
.bot-stat-val {
  font-family: var(--font-mono);
  font-size: 13px;
  font-weight: 700;
}
.bot-stat-lbl { font-size: 9px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

/* FoxBot pulse animation */
@keyframes botPulse {
  0%, 100% { box-shadow: 0 0 0 0 rgba(0,255,136,0.4); }
  50%       { box-shadow: 0 0 0 8px rgba(0,255,136,0); }
}
.bot-running-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: var(--green);
  display: inline-block;
  animation: botPulse 1.5s infinite;
  margin-right: 4px;
  vertical-align: middle;
}

/* Bot controls */
.bot-controls {
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
}
.btn-bot {
  flex: 1;
  padding: 10px 8px;
  border: none;
  border-radius: var(--radius-sm);
  font-size: 11px;
  font-weight: 700;
  cursor: pointer;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  transition: all 0.15s;
  min-width: 70px;
}
.btn-bot-start  { background: linear-gradient(135deg, var(--green-dim), var(--green)); color: #000; }
.btn-bot-stop   { background: linear-gradient(135deg, var(--red-dim), var(--red)); color: #fff; }
.btn-bot-close  { background: var(--bg-input); border: 1px solid var(--border); color: var(--muted); }
.btn-bot:hover  { opacity: 0.85; transform: translateY(-1px); }
.btn-bot:active { opacity: 1; transform: translateY(0); }
.btn-bot:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

/* Bot live metrics */
.bot-live-panel {
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 10px 12px;
}
.bot-live-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 11px;
  padding: 3px 0;
}
.bot-live-row + .bot-live-row { border-top: 1px solid rgba(30,45,69,0.5); }
.bot-live-key   { color: var(--muted); }
.bot-live-val   { font-family: var(--font-mono); font-weight: 600; }

/* Progress bar for win rate */
.win-rate-bar { height: 4px; background: var(--bg-hover); border-radius: 2px; margin-top: 6px; overflow: hidden; }
.win-rate-fill { height: 100%; border-radius: 2px; background: linear-gradient(90deg, var(--green-dim), var(--green)); transition: width 0.5s ease; }
</style>
</head>
<body>

<div id="app">

  <!-- ===== HEADER ===== -->
  <header id="header">
    <div class="logo"><div class="logo-dot"></div>Xendaro Fox</div>
    <div class="header-divider"></div>

    <div class="balance-block">
      <div class="balance-label">Solde</div>
      <div class="balance-value" id="hdr-balance"><span class="skeleton skeleton-value" style="width:80px;">&nbsp;</span></div>
    </div>

    <div class="header-divider"></div>

    <div class="balance-block">
      <div class="balance-label">Équité</div>
      <div class="balance-value" id="hdr-equity"><span class="skeleton skeleton-value" style="width:80px;">&nbsp;</span></div>
    </div>

    <div class="header-divider"></div>

    <div class="equity-block">
      <div class="balance-label">P&amp;L Ouvert</div>
      <div class="pnl-header" id="hdr-pnl"><span class="skeleton skeleton-value" style="width:60px;">&nbsp;</span></div>
    </div>

    <div class="header-divider"></div>

    <div class="balance-block">
      <div class="balance-label">Marge utilisée</div>
      <div class="balance-value" id="hdr-margin"><span class="skeleton skeleton-value" style="width:60px;">&nbsp;</span></div>
    </div>

    <div id="account-mode">
      <button class="mode-btn real active" onclick="switchMode('real')">Réel</button>
      <button class="mode-btn demo" onclick="switchMode('demo')">Démo</button>
    </div>

    <div class="user-chip">
      <div class="user-avatar" id="user-avatar"><span class="skeleton" style="width:28px;height:28px;border-radius:50%;display:block;">&nbsp;</span></div>
      <div class="user-name" id="user-name"><span class="skeleton skeleton-text" style="width:60px;">&nbsp;</span></div>
    </div>
  </header>

  <!-- ===== SIDEBAR ===== -->
  <aside id="sidebar">
    <div class="sidebar-search">
      <div class="search-wrap">
        <span class="search-icon">🔍</span>
        <input class="search-input" id="market-search" type="text" placeholder="Chercher un marché..." oninput="filterMarkets(this.value)">
      </div>
    </div>

    <div class="sidebar-tabs">
      <div class="stab active" onclick="filterCategory('all',this)">Tous</div>
      <div class="stab" onclick="filterCategory('forex',this)">Forex</div>
      <div class="stab" onclick="filterCategory('crypto',this)">Crypto</div>
      <div class="stab" onclick="filterCategory('indices',this)">Indices</div>
      <div class="stab" onclick="filterCategory('metals',this)">Métaux</div>
    </div>

    <div class="market-list" id="market-list">
      <!-- Skeleton initial -->
      <div class="skeleton-market-item"><div class="skeleton-market-left"><span class="skeleton skeleton-text" style="width:64px;">&nbsp;</span><span class="skeleton skeleton-text" style="width:80px;">&nbsp;</span></div><div class="skeleton-market-right"><span class="skeleton skeleton-text" style="width:52px;">&nbsp;</span><span class="skeleton skeleton-text" style="width:36px;">&nbsp;</span></div></div>
      <div class="skeleton-market-item"><div class="skeleton-market-left"><span class="skeleton skeleton-text" style="width:56px;">&nbsp;</span><span class="skeleton skeleton-text" style="width:72px;">&nbsp;</span></div><div class="skeleton-market-right"><span class="skeleton skeleton-text" style="width:52px;">&nbsp;</span><span class="skeleton skeleton-text" style="width:36px;">&nbsp;</span></div></div>
      <div class="skeleton-market-item"><div class="skeleton-market-left"><span class="skeleton skeleton-text" style="width:60px;">&nbsp;</span><span class="skeleton skeleton-text" style="width:68px;">&nbsp;</span></div><div class="skeleton-market-right"><span class="skeleton skeleton-text" style="width:52px;">&nbsp;</span><span class="skeleton skeleton-text" style="width:36px;">&nbsp;</span></div></div>
      <div class="skeleton-market-item"><div class="skeleton-market-left"><span class="skeleton skeleton-text" style="width:64px;">&nbsp;</span><span class="skeleton skeleton-text" style="width:76px;">&nbsp;</span></div><div class="skeleton-market-right"><span class="skeleton skeleton-text" style="width:52px;">&nbsp;</span><span class="skeleton skeleton-text" style="width:36px;">&nbsp;</span></div></div>
      <div class="skeleton-market-item"><div class="skeleton-market-left"><span class="skeleton skeleton-text" style="width:56px;">&nbsp;</span><span class="skeleton skeleton-text" style="width:84px;">&nbsp;</span></div><div class="skeleton-market-right"><span class="skeleton skeleton-text" style="width:52px;">&nbsp;</span><span class="skeleton skeleton-text" style="width:36px;">&nbsp;</span></div></div>
    </div>
  </aside>

  <!-- ===== CHART ===== -->
  <main id="chart-area">
    <div class="chart-topbar">
      <div>
        <div class="chart-symbol-name" id="chart-sym-name">EURUSD</div>
        <div style="font-size:10px;color:var(--muted);" id="chart-sym-full">Euro / US Dollar</div>
      </div>
      <div class="chart-bid-ask">
        <div>
          <div style="font-size:9px;color:var(--muted);text-transform:uppercase;">Vente</div>
          <div class="down" id="chart-bid" style="font-family:var(--font-mono);font-size:18px;font-weight:700;"><span class="skeleton skeleton-value" style="width:72px;">&nbsp;</span></div>
        </div>
        <div>
          <div style="font-size:9px;color:var(--muted);text-transform:uppercase;">Achat</div>
          <div class="up" id="chart-ask" style="font-family:var(--font-mono);font-size:18px;font-weight:700;"><span class="skeleton skeleton-value" style="width:72px;">&nbsp;</span></div>
        </div>
      </div>
      <div class="chart-spread" id="chart-spread">Spread: —</div>

      <div class="timeframe-bar">
        <button class="tf-btn" onclick="setTimeframe('1',this)">M1</button>
        <button class="tf-btn" onclick="setTimeframe('5',this)">M5</button>
        <button class="tf-btn" onclick="setTimeframe('15',this)">M15</button>
        <button class="tf-btn" onclick="setTimeframe('60',this)">H1</button>
        <button class="tf-btn active" onclick="setTimeframe('240',this)">H4</button>
        <button class="tf-btn" onclick="setTimeframe('D',this)">1J</button>
        <button class="tf-btn" onclick="setTimeframe('W',this)">1S</button>
      </div>
    </div>
    <div id="tradingview-widget"></div>
  </main>

  <!-- ===== ORDER PANEL ===== -->
  <aside id="order-panel">
    <div class="order-tabs">
      <div class="otab active" onclick="switchOrderTab('manual',this)">📋 Manuel</div>
      <div class="otab" onclick="switchOrderTab('foxbot',this)">🤖 FoxBot</div>
    </div>

    <!-- ─── PANEL MANUEL ─── -->
    <div class="order-body" id="order-tab-manual" style="display:flex;">

      <div class="price-display">
        <div class="price-side">
          <div class="price-side-label">Vente (BID)</div>
          <div class="price-side-val bid" id="op-bid"><span class="skeleton skeleton-value" style="width:60px;">&nbsp;</span></div>
        </div>
        <div class="price-spread-mid" id="op-spread"><span class="skeleton skeleton-text" style="width:28px;">&nbsp;</span></div>
        <div class="price-side">
          <div class="price-side-label">Achat (ASK)</div>
          <div class="price-side-val ask" id="op-ask"><span class="skeleton skeleton-value" style="width:60px;">&nbsp;</span></div>
        </div>
      </div>

      <div>
        <div class="field-label">Volume (Lots)</div>
        <div style="display:flex;gap:6px;align-items:center;">
          <button onclick="adjustLot(-0.01)" style="background:var(--bg-input);border:1px solid var(--border);color:var(--white);width:28px;height:32px;border-radius:var(--radius-sm);cursor:pointer;font-size:16px;">−</button>
          <input class="field-input" id="op-lot" type="number" value="0.01" min="0.01" max="100" step="0.01" oninput="updateMarginEstimate()">
          <button onclick="adjustLot(0.01)" style="background:var(--bg-input);border:1px solid var(--border);color:var(--white);width:28px;height:32px;border-radius:var(--radius-sm);cursor:pointer;font-size:16px;">+</button>
        </div>
      </div>

      <div>
        <div class="field-label">Prix d'entrée (Market)</div>
        <input class="field-input" id="op-entry-price" type="text" value="Market" readonly>
      </div>

      <hr class="separator">

      <div class="toggle-row">
        <span class="toggle-label">Stop Loss</span>
        <label class="toggle-switch">
          <input type="checkbox" id="tog-sl" onchange="toggleField('sl-field', this)">
          <div class="toggle-track"></div>
        </label>
      </div>
      <div class="optional-field" id="sl-field">
        <div class="field-label">Prix Stop Loss</div>
        <input class="field-input" id="op-sl" type="number" placeholder="0.00000" step="0.00001">
      </div>

      <div class="toggle-row">
        <span class="toggle-label">Take Profit</span>
        <label class="toggle-switch">
          <input type="checkbox" id="tog-tp" onchange="toggleField('tp-field', this)">
          <div class="toggle-track"></div>
        </label>
      </div>
      <div class="optional-field" id="tp-field">
        <div class="field-label">Prix Take Profit</div>
        <input class="field-input" id="op-tp" type="number" placeholder="0.00000" step="0.00001">
      </div>

      <div class="toggle-row">
        <span class="toggle-label">Breakeven Auto</span>
        <label class="toggle-switch">
          <input type="checkbox" id="tog-be">
          <div class="toggle-track"></div>
        </label>
      </div>

      <hr class="separator">

      <div class="margin-info">
        <div>Marge requise :</div>
        <div><span id="op-margin-req">—</span> USD</div>
      </div>
      <div class="margin-info">
        <div>Solde dispo :</div>
        <div><span id="op-balance-avail">—</span> USD</div>
      </div>

      <hr class="separator">

      <div class="exec-btns-row">
        <button class="btn-exec-buy" onclick="executeOrder('buy')">▲ ACHETER</button>
        <button class="btn-exec-sell" onclick="executeOrder('sell')">▼ VENDRE</button>
      </div>

    </div><!-- /order-tab-manual -->

    <!-- ─── PANEL FOXBOT ─── -->
    <div class="foxbot-body" id="order-tab-foxbot">

      <!-- Sélection du bot -->
      <div>
        <div class="field-label">Bot disponible</div>
        <div id="foxbot-card-list">
          <!-- Bot par défaut (chargé statiquement, enrichi via JS) -->
          <div class="bot-card selected" id="foxbot-default-card" onclick="selectBot('default')">
            <div class="bot-card-top">
              <div class="bot-emoji">🦊</div>
              <div class="bot-info">
                <div class="bot-name">FoxBot Alpha</div>
                <div class="bot-strategy">Scalping • M1 / M5</div>
              </div>
              <div class="bot-badge active" id="foxbot-status-badge">OFF</div>
            </div>
            <div class="bot-stats-row">
              <div class="bot-stat">
                <div class="bot-stat-val up" id="bot-winrate-val">75%</div>
                <div class="bot-stat-lbl">Win Rate</div>
              </div>
              <div class="bot-stat">
                <div class="bot-stat-val up" id="bot-gain-val">+2.5%/h</div>
                <div class="bot-stat-lbl">Gain/Heure</div>
              </div>
              <div class="bot-stat">
                <div class="bot-stat-val" id="bot-trades-val">0</div>
                <div class="bot-stat-lbl">Trades</div>
              </div>
            </div>
            <div class="win-rate-bar" style="margin-top:8px;">
              <div class="win-rate-fill" id="bot-winrate-bar" style="width:75%;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Mode de compte -->
      <div>
        <div class="field-label">Compte</div>
        <div style="display:flex;gap:6px;">
          <label style="flex:1;display:flex;align-items:center;gap:6px;padding:7px 10px;background:var(--bg-input);border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;font-size:11px;">
            <input type="radio" name="bot-account" value="demo" checked onchange="botAccountChange(this)" style="accent-color:var(--cyan);"> Démo
          </label>
          <label style="flex:1;display:flex;align-items:center;gap:6px;padding:7px 10px;background:var(--bg-input);border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;font-size:11px;">
            <input type="radio" name="bot-account" value="real" onchange="botAccountChange(this)" style="accent-color:var(--cyan);"> Réel
          </label>
        </div>
      </div>

      <!-- Métriques en direct -->
      <div class="bot-live-panel" id="bot-live-panel" style="display:none;">
        <div class="bot-live-row">
          <span class="bot-live-key"><span class="bot-running-dot"></span>Statut</span>
          <span class="bot-live-val" id="bot-live-status" style="color:var(--green);">En cours...</span>
        </div>
        <div class="bot-live-row">
          <span class="bot-live-key">Positions actives</span>
          <span class="bot-live-val" id="bot-live-positions">0 / 3</span>
        </div>
        <div class="bot-live-row">
          <span class="bot-live-key">P&L session</span>
          <span class="bot-live-val" id="bot-live-pnl">+0.00 $</span>
        </div>
        <div class="bot-live-row">
          <span class="bot-live-key">Trades session</span>
          <span class="bot-live-val" id="bot-live-trades">0</span>
        </div>
        <div class="bot-live-row">
          <span class="bot-live-key">Durée session</span>
          <span class="bot-live-val" id="bot-live-timer">00:00:00</span>
        </div>
      </div>

      <!-- Boutons contrôle -->
      <div class="bot-controls">
        <button class="btn-bot btn-bot-start" id="btn-bot-start" onclick="startFoxBot()">▶ Démarrer</button>
        <button class="btn-bot btn-bot-stop" id="btn-bot-stop" onclick="stopFoxBot()" disabled>■ Arrêter</button>
        <button class="btn-bot btn-bot-close" id="btn-bot-close" onclick="closeAllBotPositions()" disabled>✕ Fermer tout</button>
      </div>

      <!-- Info -->
      <div style="background:rgba(0,212,255,0.04);border:1px solid rgba(0,212,255,0.15);border-radius:var(--radius-sm);padding:10px;font-size:10px;color:var(--muted);line-height:1.6;">
        <strong style="color:var(--cyan);">ℹ️ FoxBot</strong> — Bot de trading automatique simulé. Utilise la configuration définie par l'administrateur. Pertes et gains restent virtuels (plateforme éducative).
      </div>

    </div><!-- /foxbot-body -->
  </aside>

  <!-- ===== BOTTOM PANEL ===== -->
  <div id="bottom-panel">
    <div class="bottom-tabs">
      <div class="btab active" onclick="switchBottomTab('positions', this)">
        Positions ouvertes <span class="badge" id="pos-badge">0</span>
      </div>
      <div class="btab" onclick="switchBottomTab('history', this)">Historique</div>
      <div style="margin-left:auto;display:flex;align-items:center;gap:8px;padding-right:8px;">
        <span style="font-size:10px;color:var(--muted);">Total P&amp;L ouvert :</span>
        <span style="font-family:var(--font-mono);font-size:13px;font-weight:700;" id="total-pnl">+0.00 USD</span>
      </div>
    </div>

    <div class="bottom-content">
      <div class="tab-panel active" id="panel-positions">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Symbole</th>
              <th>Type</th>
              <th>Volume</th>
              <th>Prix Ouvert</th>
              <th>Prix Actuel</th>
              <th>Stop Loss</th>
              <th>Take Profit</th>
              <th>Swap</th>
              <th>P&amp;L</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="positions-tbody">
            <tr><td colspan="11">
              <div class="empty-state">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 12h6M9 15h4"/></svg>
                <div>Aucune position ouverte</div>
              </div>
            </td></tr>
          </tbody>
        </table>
      </div>

      <div class="tab-panel" id="panel-history">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Symbole</th>
              <th>Type</th>
              <th>Volume</th>
              <th>Prix Ouvert</th>
              <th>Prix Clôture</th>
              <th>Durée</th>
              <th>P&amp;L</th>
              <th>Date Clôture</th>
            </tr>
          </thead>
          <tbody id="history-tbody">
            <tr><td colspan="9">
              <div class="empty-state">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                <div>Aucun historique de trade</div>
              </div>
            </td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- ===== TOAST ===== -->
<div id="toast-container"></div>

<!-- ===== MODAL — Modifier position ===== -->
<div class="modal-overlay" id="modal-modify">
  <div class="modal-box">
    <div class="modal-title">✏️ Modifier la Position</div>
    <input type="hidden" id="mod-pos-id">
    <div>
      <div class="field-label">Stop Loss</div>
      <input class="field-input" id="mod-sl" type="number" placeholder="Laisser vide pour désactiver" step="0.00001">
    </div>
    <div>
      <div class="field-label">Take Profit</div>
      <input class="field-input" id="mod-tp" type="number" placeholder="Laisser vide pour désactiver" step="0.00001">
    </div>
    <div class="toggle-row">
      <span class="toggle-label">Breakeven (déplacer SL au prix d'entrée)</span>
      <label class="toggle-switch">
        <input type="checkbox" id="mod-be" onchange="applyBreakeven()">
        <div class="toggle-track"></div>
      </label>
    </div>
    <div class="modal-actions">
      <button class="btn-modal" onclick="closeModal()">Annuler</button>
      <button class="btn-modal primary" onclick="saveModify()">Appliquer</button>
    </div>
  </div>
</div>

<!-- ===== TRADINGVIEW WIDGET SCRIPT ===== -->
<script src="https://s3.tradingview.com/tv.js"></script>

<script>
/* ============================================================
   CONFIG — données injectées par Laravel (Blade)
   ============================================================ */
const CSRF_TOKEN   = '{{ csrf_token() }}';
const LEVERAGE     = 100;
const TICK_MS      = 2000;
const PNL_MS       = 1000;

/* Routes Laravel */
const ROUTES = {
  openPosition:  '{{ route("trade.position.open") }}',
  closePosition: '{{ route("trade.position.close", ["id" => "__ID__"]) }}',
  getPositions:  '{{ route("trade.positions.json") }}',
  getHistory:    '{{ route("trade.history.json") }}',
  getBalance:    '{{ route("trade.balance.json") }}',
  updateBalance: '{{ route("trade.balance.update") }}',
};

/* Données utilisateur injectées côté serveur */
const SERVER_USER = {
  id:           {{ $user->id }},
  name:         '{{ addslashes($user->name) }}',
  first_name:   '{{ addslashes($user->first_name ?? $user->name) }}',
  last_name:    '{{ addslashes($user->last_name ?? "") }}',
  email:        '{{ $user->email }}',
  balance_real: {{ (float)($wallet->balance ?? 0) }},
  balance_demo: {{ (float)($wallet->demo_balance ?? 10000) }},
  margin_used:  {{ (float)($wallet->margin_used ?? 0) }},
};

/* ============================================================
   MARKET DATA
   ============================================================ */
const BASE_PRICES = {
  EURUSD:1.0850, GBPUSD:1.2650, USDJPY:149.50, AUDUSD:0.6530,
  USDCAD:1.3600, EURGBP:0.8580, USDCHF:0.9050, NZDUSD:0.6020,
  BTCUSD:67500,  ETHUSD:3400,   BNBUSD:580,    SOLUSD:165,
  SPX500:5300,   US30:39800,    NAS100:18600,  GER40:18400,
  XAUUSD:2340,   XAGUSD:30.50,
};

const MARKETS = [
  { symbol:'EURUSD', name:'Euro / USD',       tv:'FX:EURUSD',       cat:'forex',   digits:5, pip:0.0001,  contractSize:100000, spread:0.00015 },
  { symbol:'GBPUSD', name:'Livre / USD',      tv:'FX:GBPUSD',       cat:'forex',   digits:5, pip:0.0001,  contractSize:100000, spread:0.00018 },
  { symbol:'USDJPY', name:'USD / Yen',        tv:'FX:USDJPY',       cat:'forex',   digits:3, pip:0.01,    contractSize:100000, spread:0.02    },
  { symbol:'AUDUSD', name:'AUD / USD',        tv:'FX:AUDUSD',       cat:'forex',   digits:5, pip:0.0001,  contractSize:100000, spread:0.00018 },
  { symbol:'USDCAD', name:'USD / CAD',        tv:'FX:USDCAD',       cat:'forex',   digits:5, pip:0.0001,  contractSize:100000, spread:0.00020 },
  { symbol:'EURGBP', name:'Euro / Livre',     tv:'FX:EURGBP',       cat:'forex',   digits:5, pip:0.0001,  contractSize:100000, spread:0.00015 },
  { symbol:'USDCHF', name:'USD / CHF',        tv:'FX:USDCHF',       cat:'forex',   digits:5, pip:0.0001,  contractSize:100000, spread:0.00018 },
  { symbol:'NZDUSD', name:'NZD / USD',        tv:'FX:NZDUSD',       cat:'forex',   digits:5, pip:0.0001,  contractSize:100000, spread:0.00020 },
  { symbol:'BTCUSD', name:'Bitcoin / USD',    tv:'BINANCE:BTCUSDT', cat:'crypto',  digits:2, pip:1,       contractSize:1,      spread:15      },
  { symbol:'ETHUSD', name:'Ethereum / USD',   tv:'BINANCE:ETHUSDT', cat:'crypto',  digits:2, pip:0.1,     contractSize:1,      spread:1.5     },
  { symbol:'BNBUSD', name:'BNB / USD',        tv:'BINANCE:BNBUSDT', cat:'crypto',  digits:2, pip:0.01,    contractSize:1,      spread:0.5     },
  { symbol:'SOLUSD', name:'Solana / USD',     tv:'BINANCE:SOLUSDT', cat:'crypto',  digits:2, pip:0.01,    contractSize:1,      spread:0.2     },
  { symbol:'SPX500', name:'S&P 500',          tv:'OANDA:SPX500USD', cat:'indices', digits:2, pip:0.1,     contractSize:1,      spread:1.0     },
  { symbol:'US30',   name:'Dow Jones',        tv:'OANDA:US30USD',   cat:'indices', digits:2, pip:1,       contractSize:1,      spread:5.0     },
  { symbol:'NAS100', name:'Nasdaq 100',       tv:'OANDA:NAS100USD', cat:'indices', digits:2, pip:0.1,     contractSize:1,      spread:2.0     },
  { symbol:'GER40',  name:'DAX 40',           tv:'OANDA:DE30EUR',   cat:'indices', digits:2, pip:0.1,     contractSize:1,      spread:1.5     },
  { symbol:'XAUUSD', name:'Or / USD',         tv:'OANDA:XAUUSD',   cat:'metals',  digits:2, pip:0.1,     contractSize:100,    spread:0.4     },
  { symbol:'XAGUSD', name:'Argent / USD',     tv:'OANDA:XAGUSD',   cat:'metals',  digits:3, pip:0.01,    contractSize:5000,   spread:0.03    },
];

/* ============================================================
   STATE
   ============================================================ */
let state = {
  user:          null,
  mode:          'real',
  currentMarket: MARKETS[0],
  prices:        {},
  positions:     [],
  history:       [],
  currentTF:     '240',
  tvWidget:      null,
  posCounter:    Date.now(),
};

let _currentCat    = 'all';
let _currentSearch = '';

/* ============================================================
   INIT
   ============================================================ */
(async function init() {
  /* Charger l'user depuis les données Blade (pas d'appel API supplémentaire) */
  state.user = SERVER_USER;
  renderUserHeader();

  startPriceEngine();
  selectMarket(MARKETS[0]);
  setInterval(updateAllPnL, PNL_MS);
})();

/* ============================================================
   USER HEADER
   ============================================================ */
function renderUserHeader() {
  const u = state.user;
  const initials = ((u.first_name||'')[0]||'') + ((u.last_name||'')[0]||'') || (u.name||'?')[0];
  const avatarEl = document.getElementById('user-avatar');
  const nameEl   = document.getElementById('user-name');
  avatarEl.innerHTML  = '';
  avatarEl.textContent = initials.toUpperCase();
  nameEl.innerHTML    = '';
  nameEl.textContent   = u.first_name || u.name;
  updateBalanceHeader();
}

function currentBalance() {
  if (!state.user) return 0;
  return parseFloat(state.mode === 'real' ? state.user.balance_real : state.user.balance_demo) || 0;
}

function updateBalanceHeader() {
  const bal     = currentBalance();
  const openPnl = calcTotalPnL();
  const equity  = bal + openPnl;

  const balEl    = document.getElementById('hdr-balance');
  const equityEl = document.getElementById('hdr-equity');
  const marginEl = document.getElementById('hdr-margin');
  balEl.innerHTML    = ''; balEl.textContent    = fmtMoney(bal);
  equityEl.innerHTML = ''; equityEl.textContent = fmtMoney(equity);

  const pnlEl = document.getElementById('hdr-pnl');
  pnlEl.innerHTML   = '';
  pnlEl.textContent = (openPnl >= 0 ? '+' : '') + fmtMoney(openPnl);
  pnlEl.className   = 'pnl-header ' + (openPnl >= 0 ? 'up' : 'down');

  const usedMargin = state.positions.reduce((s, p) => s + (p.margin || 0), 0);
  marginEl.innerHTML = ''; marginEl.textContent = fmtMoney(usedMargin);
  document.getElementById('op-balance-avail').textContent = fmtMoney(bal);

  const totalPnlEl = document.getElementById('total-pnl');
  totalPnlEl.textContent  = (openPnl >= 0 ? '+' : '') + fmtMoney(openPnl) + ' USD';
  totalPnlEl.className    = openPnl >= 0 ? 'up' : 'down';
  totalPnlEl.style.cssText = 'font-family:var(--font-mono);font-size:13px;font-weight:700;';
}

/* ============================================================
   MARKET LIST
   ============================================================ */
function renderMarketList(cat, search) {
  const list     = document.getElementById('market-list');
  const filtered = MARKETS.filter(m => {
    const matchCat    = cat === 'all' || m.cat === cat;
    const matchSearch = m.symbol.toLowerCase().includes(search.toLowerCase()) ||
                        m.name.toLowerCase().includes(search.toLowerCase());
    return matchCat && matchSearch;
  });

  if (!filtered.length) {
    list.innerHTML = '<div class="empty-state" style="padding:20px 0;"><div>Aucun marché trouvé</div></div>';
    return;
  }

  list.innerHTML = filtered.map(m => {
    const p          = state.prices[m.symbol];
    const bidStr     = p ? p.bid.toFixed(m.digits) : '—';
    const askStr     = p ? p.ask.toFixed(m.digits) : '—';
    const changeStr  = p ? (p.change >= 0 ? '+' : '') + p.change.toFixed(2) + '%' : '';
    const changeClass = p ? (p.change >= 0 ? 'up' : 'down') : '';
    const isActive   = state.currentMarket.symbol === m.symbol;
    return `<div class="market-item ${isActive ? 'active' : ''}" onclick="selectMarket(MARKETS.find(x=>x.symbol==='${m.symbol}'))">
      <div>
        <div class="market-symbol">${m.symbol}</div>
        <div class="market-name">${m.name}</div>
      </div>
      <div class="market-price-col">
        <div class="market-price ${changeClass}" id="ml-price-${m.symbol}">${bidStr}</div>
        <div class="market-bid-ask">
          <span class="bid-val" id="ml-bid-${m.symbol}">${bidStr}</span>
          <span class="sep">/</span>
          <span class="ask-val" id="ml-ask-${m.symbol}">${askStr}</span>
        </div>
        <div class="market-change ${changeClass}" id="ml-change-${m.symbol}">${changeStr}</div>
      </div>
    </div>`;
  }).join('');
}

function filterCategory(cat, el) {
  _currentCat = cat;
  document.querySelectorAll('.stab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  renderMarketList(cat, _currentSearch);
}
function filterMarkets(q) {
  _currentSearch = q;
  renderMarketList(_currentCat, q);
}

/* ============================================================
   MARKET SELECTION
   ============================================================ */
function selectMarket(m) {
  if (!m) return;
  state.currentMarket = m;
  document.getElementById('chart-sym-name').textContent = m.symbol;
  document.getElementById('chart-sym-full').textContent = m.name;
  loadTradingViewWidget(m);
  updateOrderPanelPrices();
  renderMarketList(_currentCat, _currentSearch);
}

/* ============================================================
   TRADINGVIEW WIDGET
   ============================================================ */
function loadTradingViewWidget(m) {
  const container = document.getElementById('tradingview-widget');
  container.innerHTML = '';
  if (typeof TradingView !== 'undefined') {
    state.tvWidget = new TradingView.widget({
      container_id:      'tradingview-widget',
      symbol:            m.tv,
      interval:          state.currentTF,
      timezone:          'Europe/Paris',
      theme:             'dark',
      style:             '1',
      locale:            'fr',
      toolbar_bg:        '#0D1320',
      enable_publishing: false,
      hide_side_toolbar: false,
      allow_symbol_change: false,
      save_image:        false,
      studies:           ['RSI@tv-study', 'MASimple@tv-study'],
      width:             '100%',
      height:            '100%',
    });
  } else {
    container.innerHTML = `<div style="display:flex;align-items:center;justify-content:center;height:100%;flex-direction:column;gap:8px;color:var(--muted);">
      <div style="font-size:32px;">📈</div>
      <div>Graphique TradingView</div>
      <div style="font-size:11px;">${m.symbol} — ${m.name}</div>
    </div>`;
  }
}

function setTimeframe(tf, el) {
  state.currentTF = tf;
  document.querySelectorAll('.tf-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
  loadTradingViewWidget(state.currentMarket);
}

/* ============================================================
   PRICE ENGINE
   ============================================================ */
function startPriceEngine() {
  MARKETS.forEach(m => {
    const base = BASE_PRICES[m.symbol] || 1;
    state.prices[m.symbol] = { bid: base, ask: base + m.spread, change: (Math.random() - 0.5) * 2, base };
  });
  renderMarketList(_currentCat, _currentSearch);
  updateAllDisplayPrices();
  setInterval(tickPrices, TICK_MS);
}

function tickPrices() {
  MARKETS.forEach(m => {
    const p = state.prices[m.symbol];
    const delta = (Math.random() - 0.499) * m.spread * 8;
    p.bid = Math.max(p.bid + delta, p.base * 0.5);
    p.ask = p.bid + m.spread;
    p.change += (Math.random() - 0.501) * 0.05;
    p.change = Math.max(-5, Math.min(5, p.change));
  });
  updateAllDisplayPrices();
}

function updateAllDisplayPrices() {
  MARKETS.forEach(m => {
    const p        = state.prices[m.symbol];
    const priceEl  = document.getElementById(`ml-price-${m.symbol}`);
    const changeEl = document.getElementById(`ml-change-${m.symbol}`);
    const bidEl    = document.getElementById(`ml-bid-${m.symbol}`);
    const askEl    = document.getElementById(`ml-ask-${m.symbol}`);
    if (priceEl) { priceEl.textContent = p.bid.toFixed(m.digits); priceEl.className = 'market-price ' + (p.change >= 0 ? 'up' : 'down'); }
    if (bidEl)   bidEl.textContent  = p.bid.toFixed(m.digits);
    if (askEl)   askEl.textContent  = p.ask.toFixed(m.digits);
    if (changeEl){ changeEl.textContent = (p.change >= 0 ? '+' : '') + p.change.toFixed(2) + '%'; changeEl.className = 'market-change ' + (p.change >= 0 ? 'up' : 'down'); }
  });
  updateOrderPanelPrices();
}

function updateOrderPanelPrices() {
  const m = state.currentMarket;
  const p = state.prices[m.symbol];
  if (!p) return;
  const bidStr     = p.bid.toFixed(m.digits);
  const askStr     = p.ask.toFixed(m.digits);
  const spreadPips = (m.spread / m.pip).toFixed(1);
  ['op-bid','chart-bid'].forEach(id => { const el = document.getElementById(id); if (el) { el.innerHTML = ''; el.textContent = bidStr; } });
  ['op-ask','chart-ask'].forEach(id => { const el = document.getElementById(id); if (el) { el.innerHTML = ''; el.textContent = askStr; } });
  const spreadEl = document.getElementById('op-spread');
  if (spreadEl) { spreadEl.innerHTML = ''; spreadEl.textContent = `${spreadPips} pips`; }
  document.getElementById('chart-spread').textContent = `Spread: ${spreadPips} pips`;
  updateMarginEstimate();
}

/* ============================================================
   ORDER PANEL
   ============================================================ */
function adjustLot(delta) {
  const input = document.getElementById('op-lot');
  let val = Math.max(0.01, Math.min(100, parseFloat((parseFloat(input.value) + delta).toFixed(2))));
  input.value = val;
  updateMarginEstimate();
}

function updateMarginEstimate() {
  const lot  = parseFloat(document.getElementById('op-lot').value) || 0.01;
  const m    = state.currentMarket;
  const p    = state.prices[m.symbol];
  if (!p) return;
  document.getElementById('op-margin-req').textContent = fmtMoney((lot * m.contractSize * p.ask) / LEVERAGE);
}

function toggleField(fieldId, checkbox) {
  document.getElementById(fieldId).classList.toggle('visible', checkbox.checked);
}

async function executeOrder(dir) {
  const m     = state.currentMarket;
  const p     = state.prices[m.symbol];
  if (!p) { toast('Prix non disponible, patientez...', 'error'); return; }

  const lot     = parseFloat(document.getElementById('op-lot').value) || 0.01;
  const sl      = document.getElementById('tog-sl').checked ? parseFloat(document.getElementById('op-sl').value) || null : null;
  const tp      = document.getElementById('tog-tp').checked ? parseFloat(document.getElementById('op-tp').value) || null : null;
  const be_auto = document.getElementById('tog-be').checked;
  const price   = dir === 'buy' ? p.ask : p.bid;
  const margin  = (lot * m.contractSize * price) / LEVERAGE;
  const bal     = currentBalance();

  if (margin > bal) { toast('Marge insuffisante pour ouvrir cette position', 'error'); return; }

  if (sl !== null) {
    if (dir === 'buy'  && sl >= price) { toast('Stop Loss doit être inférieur au prix actuel (achat)', 'error'); return; }
    if (dir === 'sell' && sl <= price) { toast('Stop Loss doit être supérieur au prix actuel (vente)', 'error'); return; }
  }
  if (tp !== null) {
    if (dir === 'buy'  && tp <= price) { toast('Take Profit doit être supérieur au prix actuel (achat)', 'error'); return; }
    if (dir === 'sell' && tp >= price) { toast('Take Profit doit être inférieur au prix actuel (vente)', 'error'); return; }
  }

  // Payload aligné sur TradeController::openPosition()
  const serverPayload = {
    symbol:        m.symbol,
    direction:     dir.toUpperCase(),
    volume:        lot,
    entry_price:   price,
    stop_loss:     sl,
    take_profit:   tp,
    margin:        margin,
    contract_size: m.contractSize,
    account_type:  state.mode,
    is_bot:        false,
  };

  // State local optimiste
  let posId = ++state.posCounter;
  const pos = {
    id: posId, symbol: m.symbol, type: dir, volume: lot,
    open_price: price, stop_loss: sl, take_profit: tp,
    breakeven: be_auto, margin, account_type: state.mode,
    opened_at: new Date().toISOString(),
    current_price: price, pnl: 0, swap: 0, be_triggered: false,
  };

  // Déduire la marge du solde local immédiatement
  if (state.mode === 'real') state.user.balance_real = Math.max(0, state.user.balance_real - margin);
  else                       state.user.balance_demo = Math.max(0, state.user.balance_demo - margin);

  state.positions.push(pos);
  renderPositions();
  updateBalanceHeader();
  toast(`Position ${dir.toUpperCase()} ${lot} lot(s) ${m.symbol} @ ${price.toFixed(m.digits)}`, 'success');

  // Envoi serveur et resynchronisation du vrai solde wallet
  try {
    const resp = await fetch(ROUTES.openPosition, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
      body: JSON.stringify(serverPayload),
    });
    const data = await resp.json();
    if (data.trade_id) { pos.id = data.trade_id; }
    if (data.balance) {
      state.user.balance_real = data.balance.real_balance;
      state.user.balance_demo = data.balance.demo_balance;
      updateBalanceHeader();
    }
    if (data.error) toast('Erreur serveur : ' + data.error, 'error');
  } catch(e) { /* solde optimiste déjà appliqué */ }
}

/* ============================================================
   P&L CALC
   ============================================================ */
function calcPnL(pos) {
  const m = MARKETS.find(x => x.symbol === pos.symbol);
  const p = state.prices[pos.symbol];
  if (!m || !p) return 0;
  const currentPrice = pos.type === 'buy' ? p.bid : p.ask;
  return (pos.type === 'buy' ? currentPrice - pos.open_price : pos.open_price - currentPrice) * pos.volume * m.contractSize;
}

function calcTotalPnL() {
  return state.positions.reduce((s, p) => s + calcPnL(p), 0);
}

function updateAllPnL() {
  if (!state.positions.length) { updateBalanceHeader(); return; }
  const toClose = [];
  state.positions.forEach(pos => {
    const m = MARKETS.find(x => x.symbol === pos.symbol);
    const p = state.prices[pos.symbol];
    if (!m || !p) return;
    const currentPrice = pos.type === 'buy' ? p.bid : p.ask;
    pos.current_price = currentPrice;
    pos.pnl = calcPnL(pos);

    if (pos.breakeven && !pos.be_triggered) {
      if (pos.type === 'buy'  && currentPrice > pos.open_price + m.spread * 10) { pos.stop_loss = pos.open_price; pos.be_triggered = true; toast(`Breakeven déclenché : ${pos.symbol}`, 'info'); }
      if (pos.type === 'sell' && currentPrice < pos.open_price - m.spread * 10) { pos.stop_loss = pos.open_price; pos.be_triggered = true; toast(`Breakeven déclenché : ${pos.symbol}`, 'info'); }
    }
    if (pos.stop_loss) {
      if (pos.type === 'buy'  && currentPrice <= pos.stop_loss) toClose.push({ pos, reason: 'SL' });
      if (pos.type === 'sell' && currentPrice >= pos.stop_loss) toClose.push({ pos, reason: 'SL' });
    }
    if (pos.take_profit) {
      if (pos.type === 'buy'  && currentPrice >= pos.take_profit) toClose.push({ pos, reason: 'TP' });
      if (pos.type === 'sell' && currentPrice <= pos.take_profit) toClose.push({ pos, reason: 'TP' });
    }
    const pnlCell   = document.getElementById(`pnl-${pos.id}`);
    const priceCell = document.getElementById(`cprice-${pos.id}`);
    if (pnlCell)   { pnlCell.textContent = (pos.pnl >= 0 ? '+' : '') + fmtMoney(pos.pnl); pnlCell.className = pos.pnl >= 0 ? 'up' : 'down'; }
    if (priceCell) priceCell.textContent = currentPrice.toFixed(m.digits);
  });
  toClose.forEach(({ pos, reason }) => closePosition(pos.id, reason));
  updateBalanceHeader();
}

/* ============================================================
   POSITIONS TABLE
   ============================================================ */
function renderPositions() {
  const tbody = document.getElementById('positions-tbody');
  document.getElementById('pos-badge').textContent = state.positions.length;
  if (!state.positions.length) {
    tbody.innerHTML = `<tr><td colspan="11"><div class="empty-state"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 12h6M9 15h4"/></svg><div>Aucune position ouverte</div></div></td></tr>`;
    return;
  }
  tbody.innerHTML = state.positions.map(pos => {
    const m = MARKETS.find(x => x.symbol === pos.symbol);
    return `<tr>
      <td style="color:var(--muted)">#${pos.id}</td>
      <td class="symbol">${pos.symbol}</td>
      <td><span class="type-badge ${pos.type}">${pos.type.toUpperCase()}</span></td>
      <td>${pos.volume}</td>
      <td>${pos.open_price.toFixed(m.digits)}</td>
      <td id="cprice-${pos.id}">${(pos.current_price||pos.open_price).toFixed(m.digits)}</td>
      <td style="color:var(--red)">${pos.stop_loss ? pos.stop_loss.toFixed(m.digits) : '—'}</td>
      <td style="color:var(--green)">${pos.take_profit ? pos.take_profit.toFixed(m.digits) : '—'}</td>
      <td style="color:var(--muted)">0.00</td>
      <td id="pnl-${pos.id}" class="${(pos.pnl||0) >= 0 ? 'up' : 'down'}">${(pos.pnl||0) >= 0 ? '+' : ''}${fmtMoney(pos.pnl||0)}</td>
      <td><div style="display:flex;gap:4px;">
        <button class="btn-mod-pos" onclick="openModifyModal(${pos.id})">Modifier</button>
        <button class="btn-close-pos" onclick="closePosition(${pos.id}, 'Manuel')">Fermer</button>
      </div></td>
    </tr>`;
  }).join('');
}

/* ============================================================
   CLOSE POSITION
   ============================================================ */
async function closePosition(posId, reason) {
  const idx = state.positions.findIndex(p => p.id === posId);
  if (idx === -1) return;
  const pos        = state.positions[idx];
  const m          = MARKETS.find(x => x.symbol === pos.symbol);
  const p          = state.prices[pos.symbol];
  const closePrice = pos.type === 'buy' ? p.bid : p.ask;
  const finalPnl   = calcPnL(pos);
  const closedAt   = new Date().toISOString();

  const diffMin    = Math.floor((new Date() - new Date(pos.opened_at)) / 60000);
  const durationStr = diffMin < 60 ? `${diffMin}m` : `${Math.floor(diffMin/60)}h${diffMin%60}m`;

  state.history.unshift({ id: pos.id, symbol: pos.symbol, type: pos.type, volume: pos.volume,
    open_price: pos.open_price, close_price: closePrice, duration: durationStr, pnl: finalPnl, closed_at: closedAt, reason });
  state.positions.splice(idx, 1);

  // Mise à jour optimiste : marge restituée + P&L
  const returned = (pos.margin || 0) + finalPnl;
  if (pos.account_type === 'real') state.user.balance_real = Math.max(0, state.user.balance_real + returned);
  else                              state.user.balance_demo = Math.max(0, state.user.balance_demo + returned);

  renderPositions();
  renderHistory();
  updateBalanceHeader();
  toast(`Position #${posId} fermée [${reason}] — P&L: ${(finalPnl >= 0 ? '+' : '') + fmtMoney(finalPnl)}`, finalPnl >= 0 ? 'success' : 'error');

  // Envoi serveur avec les champs attendus par TradeController::closePosition()
  try {
    const closeUrl = ROUTES.closePosition.replace('__ID__', posId);
    const resp = await fetch(closeUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
      body: JSON.stringify({ exit_price: closePrice, close_reason: reason }),
    });
    const data = await resp.json();
    // Resynchroniser avec le vrai solde wallet après fermeture
    if (data.balance) {
      state.user.balance_real = data.balance.real_balance;
      state.user.balance_demo = data.balance.demo_balance;
      updateBalanceHeader();
    }
  } catch(e) { /* solde optimiste déjà appliqué */ }
}

/* ============================================================
   HISTORY TABLE
   ============================================================ */
function renderHistory() {
  const tbody = document.getElementById('history-tbody');
  if (!state.history.length) {
    tbody.innerHTML = `<tr><td colspan="9"><div class="empty-state"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg><div>Aucun historique de trade</div></div></td></tr>`;
    return;
  }
  tbody.innerHTML = state.history.map(h => `<tr>
    <td style="color:var(--muted)">#${h.id}</td>
    <td class="symbol">${h.symbol}</td>
    <td><span class="type-badge ${h.type}">${h.type.toUpperCase()}</span></td>
    <td>${h.volume}</td>
    <td>${h.open_price}</td>
    <td>${h.close_price.toFixed ? h.close_price.toFixed(5) : h.close_price}</td>
    <td style="color:var(--muted)">${h.duration}</td>
    <td class="${h.pnl >= 0 ? 'up' : 'down'}">${h.pnl >= 0 ? '+' : ''}${fmtMoney(h.pnl)}</td>
    <td style="color:var(--muted)">${new Date(h.closed_at).toLocaleString('fr-FR')}</td>
  </tr>`).join('');
}

/* ============================================================
   MODIFY MODAL
   ============================================================ */
function openModifyModal(posId) {
  const pos = state.positions.find(p => p.id === posId);
  if (!pos) return;
  document.getElementById('mod-pos-id').value  = posId;
  document.getElementById('mod-sl').value       = pos.stop_loss || '';
  document.getElementById('mod-tp').value       = pos.take_profit || '';
  document.getElementById('mod-be').checked     = pos.breakeven || false;
  document.getElementById('modal-modify').classList.add('open');
}
function closeModal() { document.getElementById('modal-modify').classList.remove('open'); }
function applyBreakeven() {
  if (!document.getElementById('mod-be').checked) return;
  const pos = state.positions.find(p => p.id === parseInt(document.getElementById('mod-pos-id').value));
  if (pos) document.getElementById('mod-sl').value = pos.open_price.toFixed(5);
}
function saveModify() {
  const posId = parseInt(document.getElementById('mod-pos-id').value);
  const pos   = state.positions.find(p => p.id === posId);
  if (!pos) return;
  pos.stop_loss   = parseFloat(document.getElementById('mod-sl').value) || null;
  pos.take_profit = parseFloat(document.getElementById('mod-tp').value) || null;
  pos.breakeven   = document.getElementById('mod-be').checked;
  closeModal();
  renderPositions();
  toast(`Position #${posId} modifiée`, 'success');
}

/* ============================================================
   ACCOUNT MODE
   ============================================================ */
function switchMode(mode) {
  state.mode = mode;
  document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active'));
  document.querySelector(`.mode-btn.${mode}`).classList.add('active');
  updateBalanceHeader();
  toast(`Mode ${mode === 'real' ? 'Réel' : 'Démo'} activé`, 'info');
}

/* ============================================================
   BOTTOM TABS
   ============================================================ */
function switchBottomTab(tab, el) {
  document.querySelectorAll('.btab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  document.getElementById(`panel-${tab}`).classList.add('active');
}

/* ============================================================
   TOAST
   ============================================================ */
function toast(msg, type = 'info') {
  const el = document.createElement('div');
  el.className = `toast ${type}`;
  el.textContent = msg;
  document.getElementById('toast-container').appendChild(el);
  setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity 0.3s'; setTimeout(() => el.remove(), 300); }, 3500);
}

/* ============================================================
   UTILS
   ============================================================ */
function fmtMoney(n) {
  return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n);
}

document.getElementById('modal-modify').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

/* ============================================================
   ORDER PANEL TABS
   ============================================================ */
function switchOrderTab(tab, el) {
  document.querySelectorAll('.otab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('order-tab-manual').style.display  = tab === 'manual'  ? 'flex' : 'none';
  document.getElementById('order-tab-foxbot').style.display  = tab === 'foxbot'  ? 'flex' : 'none';
  if (tab === 'foxbot') loadBotStats();
}

/* ============================================================
   FOXBOT — état
   ============================================================ */
const BOT_STATE = {
  running:       false,
  accountType:   'demo',
  sessionPnl:    0,
  sessionTrades: 0,
  startedAt:     null,
  timerInterval: null,
  tickInterval:  null,
  activeBotPositions: [],
};

const BOT_API_ROUTES = {
  open:     '{{ route("trade.foxbot") }}',
  tick:     '{{ route("trade.foxbot.tick") }}',
};

function botAccountChange(radio) {
  BOT_STATE.accountType = radio.value;
}

function selectBot(id) { /* future multi-bot support */ }

async function loadBotStats() {
  try {
    const resp = await fetch('/api/trade/operations/foxbot/stats', {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
    });
    if (!resp.ok) return;
    const data = await resp.json();
    const d = data.data ?? data;
    const wr = d.win_rate ?? 75;
    document.getElementById('bot-winrate-val').textContent  = wr.toFixed(1) + '%';
    document.getElementById('bot-winrate-bar').style.width  = wr + '%';
    document.getElementById('bot-trades-val').textContent   = d.total_trades ?? 0;
  } catch(e) { /* silently */ }
}

function startFoxBot() {
  if (BOT_STATE.running) return;
  BOT_STATE.running       = true;
  BOT_STATE.startedAt     = Date.now();
  BOT_STATE.sessionPnl    = 0;
  BOT_STATE.sessionTrades = 0;
  BOT_STATE.activeBotPositions = [];

  document.getElementById('btn-bot-start').disabled  = true;
  document.getElementById('btn-bot-stop').disabled   = false;
  document.getElementById('btn-bot-close').disabled  = false;
  document.getElementById('bot-live-panel').style.display = 'flex';
  document.getElementById('foxbot-status-badge').textContent = 'ON';
  document.getElementById('foxbot-status-badge').className   = 'bot-badge active';
  document.getElementById('bot-live-status').textContent     = 'En cours...';
  document.getElementById('bot-live-status').style.color     = 'var(--green)';

  BOT_STATE.timerInterval = setInterval(updateBotTimer, 1000);
  BOT_STATE.tickInterval  = setInterval(botTick, 8000);
  botTick(); // premier tick immédiat
  toast('🤖 FoxBot démarré en mode ' + (BOT_STATE.accountType === 'demo' ? 'Démo' : 'Réel'), 'success');
}

function stopFoxBot() {
  if (!BOT_STATE.running) return;
  BOT_STATE.running = false;
  clearInterval(BOT_STATE.timerInterval);
  clearInterval(BOT_STATE.tickInterval);

  document.getElementById('btn-bot-start').disabled  = false;
  document.getElementById('btn-bot-stop').disabled   = true;
  document.getElementById('bot-live-status').textContent = 'Arrêté';
  document.getElementById('bot-live-status').style.color = 'var(--muted)';
  document.getElementById('foxbot-status-badge').textContent = 'OFF';
  document.getElementById('foxbot-status-badge').className   = 'bot-badge off';
  toast('🤖 FoxBot arrêté — Session P&L: ' + (BOT_STATE.sessionPnl >= 0 ? '+' : '') + fmtMoney(BOT_STATE.sessionPnl) + ' $', BOT_STATE.sessionPnl >= 0 ? 'success' : 'error');
}

function updateBotTimer() {
  if (!BOT_STATE.startedAt) return;
  const sec  = Math.floor((Date.now() - BOT_STATE.startedAt) / 1000);
  const h    = String(Math.floor(sec / 3600)).padStart(2,'0');
  const m    = String(Math.floor((sec % 3600) / 60)).padStart(2,'0');
  const s    = String(sec % 60).padStart(2,'0');
  document.getElementById('bot-live-timer').textContent = `${h}:${m}:${s}`;
}

async function botTick() {
  if (!BOT_STATE.running) return;
  const m = state.currentMarket;
  const p = state.prices[m.symbol];
  if (!p) return;

  // Respecter la limite de positions concurrentes (max 3)
  if (BOT_STATE.activeBotPositions.length >= 3) {
    // Tenter de fermer une position ouverte
    await closeBotPosition(BOT_STATE.activeBotPositions[0]);
    return;
  }

  // Ouvrir une nouvelle position bot
  const dir       = Math.random() > 0.5 ? 'BUY' : 'SELL';
  const entryPx   = dir === 'BUY' ? p.ask : p.bid;
  const slDist    = entryPx * 0.0015;
  const sl        = dir === 'BUY' ? entryPx - slDist : entryPx + slDist;
  const tp        = dir === 'BUY' ? entryPx + slDist * 2 : entryPx - slDist * 2;
  const bal       = currentBalance();
  const margin    = Math.max(1, bal * 0.02);

  try {
    const resp = await fetch(BOT_API_ROUTES.open, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
      body: JSON.stringify({
        symbol:       m.symbol,
        direction:    dir,
        volume:       0.01,
        entry_price:  entryPx,
        stop_loss:    parseFloat(sl.toFixed(m.digits)),
        take_profit:  parseFloat(tp.toFixed(m.digits)),
        margin:       parseFloat(margin.toFixed(2)),
        contract_size: m.contractSize,
        account_type: BOT_STATE.accountType,
      }),
    });
    const data = await resp.json();
    if (resp.ok && data.data?.trade_id) {
      const botPos = {
        id: data.data.trade_id,
        symbol: m.symbol,
        direction: dir,
        entryPx,
        sl, tp, margin,
        openedAt: Date.now(),
      };
      BOT_STATE.activeBotPositions.push(botPos);
      document.getElementById('bot-live-positions').textContent = BOT_STATE.activeBotPositions.length + ' / 3';

      // Auto-close après 8–25s
      const holdMs = 8000 + Math.random() * 17000;
      setTimeout(() => closeBotPosition(botPos), holdMs);
    }
  } catch(e) { /* silently */ }
}

async function closeBotPosition(botPos) {
  const idx = BOT_STATE.activeBotPositions.indexOf(botPos);
  if (idx === -1) return;

  const m   = MARKETS.find(x => x.symbol === botPos.symbol);
  const p   = state.prices[botPos.symbol];
  const isWin = Math.random() < 0.75; // 75% win rate
  const exitPx = isWin
    ? (botPos.direction === 'BUY' ? botPos.tp : botPos.sl)
    : (botPos.direction === 'BUY' ? botPos.sl : botPos.tp);

  BOT_STATE.activeBotPositions.splice(idx, 1);
  const pnl = isWin ? botPos.margin * 0.04 : -botPos.margin * 0.02;
  BOT_STATE.sessionPnl    += pnl;
  BOT_STATE.sessionTrades += 1;

  try {
    const closeUrl = '{{ route("trade.position.close", ["id" => "__ID__"]) }}'.replace('__ID__', botPos.id);
    const resp = await fetch(closeUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
      body: JSON.stringify({ exit_price: exitPx ?? botPos.entryPx, close_reason: isWin ? 'TP (FoxBot)' : 'SL (FoxBot)' }),
    });
    const data = await resp.json();
    // Resynchroniser avec le vrai solde wallet
    if (data.balance) {
      state.user.balance_real = data.balance.real_balance;
      state.user.balance_demo = data.balance.demo_balance;
    } else {
      // Fallback optimiste si le serveur ne répond pas
      if (BOT_STATE.accountType === 'real') state.user.balance_real = (state.user.balance_real || 0) + pnl;
      else                                  state.user.balance_demo = (state.user.balance_demo || 0) + pnl;
    }
  } catch(e) {
    if (BOT_STATE.accountType === 'real') state.user.balance_real = (state.user.balance_real || 0) + pnl;
    else                                  state.user.balance_demo = (state.user.balance_demo || 0) + pnl;
  }
  updateBalanceHeader();

  // UI
  document.getElementById('bot-live-positions').textContent = BOT_STATE.activeBotPositions.length + ' / 3';
  document.getElementById('bot-live-pnl').textContent       = (BOT_STATE.sessionPnl >= 0 ? '+' : '') + fmtMoney(BOT_STATE.sessionPnl) + ' $';
  document.getElementById('bot-live-pnl').style.color       = BOT_STATE.sessionPnl >= 0 ? 'var(--green)' : 'var(--red)';
  document.getElementById('bot-live-trades').textContent    = BOT_STATE.sessionTrades;
}

async function closeAllBotPositions() {
  const possCopy = [...BOT_STATE.activeBotPositions];
  for (const pos of possCopy) await closeBotPosition(pos);
  toast('Toutes les positions bot fermées', 'info');
}
</script>
</body>
</html>
