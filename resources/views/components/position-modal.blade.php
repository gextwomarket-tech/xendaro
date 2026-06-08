<!-- 
  COMPOSANT MODAL - PRISE DE POSITION
  Simplifié, rapide, moderne
  À inclure dans resources/views/trade/index.blade.php
-->

<div id="positionModal" class="position-modal-overlay">
  <div class="position-modal">
    <!-- HEADER -->
    <div class="modal-header">
      <h2 class="modal-title">Nouvelle Position</h2>
      <button type="button" class="modal-close" onclick="closePositionModal()">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <!-- BODY -->
    <form id="positionForm" class="modal-body" onsubmit="submitPositionForm(event)">
      <!-- ROW 1: Symbole et Direction -->
      <div class="form-row">
        <div class="form-group">
          <label>Instrument</label>
          <select id="tradeSymbol" required style="font-size: 14px; font-weight: 600;">
            <option value="">-- Sélectionner --</option>
            <option value="EURUSD">EUR/USD</option>
            <option value="GBPUSD">GBP/USD</option>
            <option value="USDJPY">USD/JPY</option>
            <option value="BTCUSD">BTC/USD</option>
            <option value="ETHUSD">ETH/USD</option>
          </select>
        </div>

        <div class="form-group">
          <label>Direction</label>
          <div class="btn-group">
            <button type="button" class="btn-side buy active" data-side="buy" onclick="selectSide('buy')">
              <span>🟢 ACHAT</span>
            </button>
            <button type="button" class="btn-side sell" data-side="sell" onclick="selectSide('sell')">
              <span>🔴 VENTE</span>
            </button>
          </div>
          <input type="hidden" id="tradeSide" value="buy" required />
        </div>
      </div>

      <!-- ROW 2: Volume et Prix d'Entrée -->
      <div class="form-row">
        <div class="form-group">
          <label>Volume (Lots)</label>
          <input type="number" id="tradeVolume" min="0.01" step="0.01" value="1.00" required placeholder="1.00" />
        </div>

        <div class="form-group">
          <label>Prix d'Entrée</label>
          <input type="number" id="tradeEntryPrice" min="0" step="0.00001" required placeholder="Prix actuel" />
          <small class="text-muted">Automatique</small>
        </div>
      </div>

      <!-- ROW 3: Stop Loss et Take Profit -->
      <div class="form-row">
        <div class="form-group">
          <label>Stop Loss</label>
          <input type="number" id="tradeStopLoss" min="0" step="0.00001" placeholder="Optionnel" />
        </div>

        <div class="form-group">
          <label>Take Profit</label>
          <input type="number" id="tradeTakeProfit" min="0" step="0.00001" placeholder="Optionnel" />
        </div>
      </div>

      <!-- ROW 4: Compte -->
      <div class="form-row">
        <div class="form-group">
          <label>Type de Compte</label>
          <div class="account-selector">
            <input type="radio" id="accountReal" name="account" value="real" />
            <label for="accountReal" class="account-label">
              💰 Réel
              <span class="balance-preview" id="realBalancePreview">$0.00</span>
            </label>

            <input type="radio" id="accountDemo" name="account" value="demo" checked />
            <label for="accountDemo" class="account-label active">
              🎮 Démo
              <span class="balance-preview" id="demoBalancePreview">$10,000.00</span>
            </label>
          </div>
        </div>
      </div>

      <!-- INFO CALCULS -->
      <div class="form-info">
        <div class="info-row">
          <span>Valeur Position:</span>
          <span id="infoPositionValue">$0.00</span>
        </div>
        <div class="info-row">
          <span>Marge Requise:</span>
          <span id="infoMarginRequired">$0.00</span>
        </div>
        <div class="info-row highlight">
          <span>Solde Disponible:</span>
          <span id="infoAvailableBalance">$0.00</span>
        </div>
      </div>

      <!-- BOUTONS -->
      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closePositionModal()">Annuler</button>
        <button type="submit" id="submitPositionBtn" class="btn-primary">
          <span class="material-symbols-outlined">check</span>
          Ouvrir Position
        </button>
      </div>
    </form>
  </div>
</div>

<!-- STYLES MODAL -->
<style>
.position-modal-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  z-index: 1000;
  backdrop-filter: blur(3px);
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.2s ease-out;
}

.position-modal-overlay.active {
  display: flex;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.position-modal {
  background: var(--bg-panel, #131820);
  border: 1px solid var(--border, #1f2d3d);
  border-radius: 12px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
  from { transform: translateY(30px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid var(--border, #1f2d3d);
  background: var(--bg-secondary, #0f1218);
}

.modal-title {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-primary, #e8f0fe);
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  color: var(--text-muted, #4a5f72);
  cursor: pointer;
  font-size: 24px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s;
}

.modal-close:hover {
  color: var(--text-secondary, #7a8fa8);
}

.modal-body {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group label {
  font-size: 11px;
  font-weight: 600;
  color: var(--text-muted, #4a5f72);
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.form-group input[type="number"],
.form-group select {
  padding: 8px 12px;
  border: 1px solid var(--border, #1f2d3d);
  border-radius: 6px;
  background: var(--bg-input, #1a2231);
  color: var(--text-primary, #e8f0fe);
  font-family: inherit;
  font-size: 13px;
  transition: border-color 0.2s;
}

.form-group input[type="number"]:focus,
.form-group select:focus {
  outline: none;
  border-color: var(--accent, #00d4aa);
}

.btn-group {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 8px;
}

.btn-side {
  padding: 8px 12px;
  border: 1px solid var(--border, #1f2d3d);
  border-radius: 6px;
  background: var(--bg-input, #1a2231);
  color: var(--text-secondary, #7a8fa8);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 12px;
}

.btn-side.buy.active {
  border-color: var(--green, #00d4aa);
  background: rgba(0, 212, 170, 0.15);
  color: var(--green, #00d4aa);
}

.btn-side.sell.active {
  border-color: var(--red, #ff6b6b);
  background: rgba(255, 107, 107, 0.15);
  color: var(--red, #ff6b6b);
}

.account-selector {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 8px;
}

.account-selector input[type="radio"] {
  display: none;
}

.account-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 12px 16px;
  border: 1px solid var(--border, #1f2d3d);
  border-radius: 6px;
  background: var(--bg-input, #1a2231);
  color: var(--text-secondary, #7a8fa8);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 12px;
}

.account-selector input[type="radio"]:checked + .account-label.active,
.account-label.active {
  border-color: var(--accent, #00d4aa);
  background: rgba(0, 212, 170, 0.15);
  color: var(--accent, #00d4aa);
}

.balance-preview {
  font-size: 11px;
  opacity: 0.8;
}

.form-info {
  padding: 12px 16px;
  background: rgba(0, 212, 170, 0.08);
  border: 1px solid rgba(0, 212, 170, 0.2);
  border-radius: 6px;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.info-row {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  color: var(--text-secondary, #7a8fa8);
}

.info-row span:last-child {
  font-weight: 600;
  color: var(--text-primary, #e8f0fe);
}

.info-row.highlight {
  color: var(--accent, #00d4aa);
  font-weight: 600;
}

.text-muted {
  font-size: 10px;
  color: var(--text-muted, #4a5f72);
  margin-top: 2px;
}

.modal-footer {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  padding: 20px;
  border-top: 1px solid var(--border, #1f2d3d);
  background: var(--bg-secondary, #0f1218);
}

.btn-secondary,
.btn-primary {
  padding: 10px 16px;
  border: 1px solid var(--border, #1f2d3d);
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  font-size: 13px;
}

.btn-secondary {
  background: var(--bg-input, #1a2231);
  color: var(--text-secondary, #7a8fa8);
}

.btn-secondary:hover {
  background: var(--bg-hover, #1e2a3a);
  color: var(--text-primary, #e8f0fe);
}

.btn-primary {
  background: var(--accent, #00d4aa);
  color: var(--bg-primary, #0a0c10);
  font-weight: 700;
  border-color: var(--accent, #00d4aa);
}

.btn-primary:hover:not(:disabled) {
  opacity: 0.9;
  transform: translateY(-1px);
}

.btn-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .position-modal {
    width: 95%;
    max-width: 100%;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .modal-footer {
    grid-template-columns: 1fr;
  }
}
</style>

<!-- JAVASCRIPT POUR LE MODAL -->
<script>
function openPositionModal() {
  const modal = document.getElementById('positionModal');
  if (modal) {
    modal.classList.add('active');
    document.getElementById('tradeSymbol')?.focus();
    updatePositionFormPreview();
  }
}

function closePositionModal() {
  const modal = document.getElementById('positionModal');
  if (modal) {
    modal.classList.remove('active');
  }
}

function selectSide(side) {
  document.getElementById('tradeSide').value = side;
  
  // Mettre à jour les boutons
  document.querySelectorAll('.btn-side').forEach(btn => {
    btn.classList.remove('active');
  });
  document.querySelector(`[data-side="${side}"]`)?.classList.add('active');
  
  updatePositionFormPreview();
}

function selectAccount(account) {
  // Mettre à jour les radios
  document.getElementById(`account${account.charAt(0).toUpperCase() + account.slice(1)}`)?.click();
  updatePositionFormPreview();
}

function updatePositionFormPreview() {
  const symbol = document.getElementById('tradeSymbol').value;
  const volume = parseFloat(document.getElementById('tradeVolume').value) || 0;
  const account = document.querySelector('input[name="account"]:checked')?.value || 'demo';
  
  if (!symbol || !INSTRUMENTS[symbol]) return;
  
  const instr = INSTRUMENTS[symbol];
  const entryPrice = parseFloat(document.getElementById('tradeEntryPrice').value) || (state.prices[symbol]?.ask || 0);
  
  // Calculs
  const positionValue = volume * instr.contractSize * entryPrice;
  const marginRequired = positionValue / instr.leverage;
  const availableBalance = account === 'demo' ? state.demoBalance : state.realBalance;
  
  // Mise à jour
  document.getElementById('infoPositionValue').textContent = '$' + positionValue.toFixed(2);
  document.getElementById('infoMarginRequired').textContent = '$' + marginRequired.toFixed(2);
  document.getElementById('infoAvailableBalance').textContent = '$' + availableBalance.toFixed(2);
  
  // Bouton submit enable/disable
  const btn = document.getElementById('submitPositionBtn');
  if (btn) {
    btn.disabled = marginRequired > availableBalance;
  }
}

async function submitPositionForm(event) {
  event.preventDefault();
  
  const symbol = document.getElementById('tradeSymbol').value;
  const side = document.getElementById('tradeSide').value;
  const volume = parseFloat(document.getElementById('tradeVolume').value);
  const account = document.querySelector('input[name="account"]:checked')?.value || 'demo';
  const sl = parseFloat(document.getElementById('tradeStopLoss').value) || null;
  const tp = parseFloat(document.getElementById('tradeTakeProfit').value) || null;
  
  if (!symbol || !volume || volume <= 0) {
    showToast('error', 'Erreur', 'Données invalides');
    return;
  }
  
  const instr = INSTRUMENTS[symbol];
  const pr = state.prices[symbol];
  const entryPrice = pr[side === 'buy' ? 'ask' : 'bid'];
  const posValue = volume * instr.contractSize * entryPrice;
  const margin = posValue / instr.leverage;
  
  const btn = document.getElementById('submitPositionBtn');
  btn.disabled = true;
  
  try {
    const res = await apiPost(ROUTES['trade.position.open'], {
      symbol,
      direction: side.toUpperCase(),
      volume,
      entry_price: entryPrice,
      stop_loss: sl,
      take_profit: tp,
      margin,
      contract_size: instr.contractSize,
      account_type: account,
      is_bot: false,
    });
    
    if (res.error) {
      showToast('error', 'Erreur', res.error);
    } else if (res.success) {
      closePositionModal();
      showToast('success', 'Position Ouverte', `${symbol} ${volume} lot(s)`);
      
      // Mettre à jour le solde
      if (res.balance) {
        state.realBalance = res.balance.real_balance;
        state.demoBalance = res.balance.demo_balance;
        updateBalanceDisplay();
      }
      
      // Rafraîchir les positions
      await loadPositions();
    }
  } catch (e) {
    showToast('error', 'Erreur', e.message);
  } finally {
    btn.disabled = false;
  }
}

// Fermer le modal au clic sur le fond
document.addEventListener('click', (e) => {
  const modal = document.getElementById('positionModal');
  if (e.target === modal) {
    closePositionModal();
  }
});
</script>
