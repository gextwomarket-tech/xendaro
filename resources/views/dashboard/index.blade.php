<x-layouts.dashboard>
  <x-slot name="title">Dashboard</x-slot>

  @push('styles')
    <style>
      .stat-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        transition: all 0.3s ease;
      }

      .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
      }

      .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        margin-top: 8px;
      }

      .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
      }

      .stat-change {
        font-size: 12px;
        margin-top: 8px;
        font-weight: 600;
      }

      .stat-change.positive {
        color: #16a34a;
      }

      .stat-change.negative {
        color: #dc2626;
      }

      .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 12px;
      }

      .icon-wallet {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
      }

      .icon-chart {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
      }

      .icon-arrow {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
      }

      .icon-trend {
        background: rgba(249, 115, 22, 0.1);
        color: #f97316;
      }

      .card-section {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
      }

      .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      .view-all {
        font-size: 14px;
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
      }

      .view-all:hover {
        color: #1d4ed8;
      }

      .transaction-item {
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .transaction-item:last-child {
        border-bottom: none;
      }

      .transaction-info {
        display: flex;
        align-items: center;
        gap: 12px;
      }

      .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
      }

      .transaction-icon.deposit {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
      }

      .transaction-icon.withdrawal {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
      }

      .transaction-details {
        display: flex;
        flex-direction: column;
      }

      .transaction-type {
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
      }

      .transaction-time {
        font-size: 12px;
        color: #94a3b8;
      }

      .transaction-amount {
        font-weight: 700;
        font-size: 14px;
      }

      .transaction-amount.positive {
        color: #22c55e;
      }

      .transaction-amount.negative {
        color: #ef4444;
      }

      .quick-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 16px;
        border-radius: 12px;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
      }

      .quick-action:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: translateY(-2px);
      }

      .quick-action-icon {
        font-size: 24px;
      }

      .chart-placeholder {
        background: linear-gradient(135deg, #f0f4f8 0%, #f8fafc 100%);
        border-radius: 12px;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-weight: 500;
      }

      .welcome-banner {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        border-radius: 12px;
        padding: 24px;
        color: white;
        margin-bottom: 24px;
      }

      .welcome-banner h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
      }

      .welcome-banner p {
        font-size: 14px;
        opacity: 0.9;
      }
    </style>
  @endpush

  <!-- Welcome Banner -->
  <div class="welcome-banner">
    <h2>Bienvenue, {{ $user->first_name }}! 👋</h2>
    <p>Vous êtes connecté à votre compte Purprime Fox. Commencez à trader dès maintenant.</p>
  </div>

  {{-- Rappel KYC : affichée si non vérifié (n'empêche pas l'accès) --}}
  @if($user->kyc_status !== 'verified')
    <div id="kycAlertBanner" style="display:flex;align-items:flex-start;gap:12px;padding:14px 18px;margin-bottom:20px;border-radius:12px;
      {{ $user->kyc_status === 'pending' ? 'background:#fefce8;border:1px solid #fde047;color:#713f12;' : 'background:#eff6ff;border:1px solid #93c5fd;color:#1e3a5f;' }}">
      <span class="material-symbols-outlined" style="margin-top:2px;{{ $user->kyc_status === 'pending' ? 'color:#ca8a04' : 'color:#2563eb' }}">
        {{ $user->kyc_status === 'pending' ? 'schedule' : 'info' }}
      </span>
      <div style="flex:1">
        @if($user->kyc_status === 'pending')
          <p style="font-weight:600;font-size:14px;margin-bottom:2px">✓ Vérification KYC en cours</p>
          <p style="font-size:13px;opacity:.85">Vos documents sont en cours d'examen (1 à 24 h). Vous pouvez utiliser la plateforme sans restriction en attendant. Merci de votre patience!</p>
        @else
          <p style="font-weight:600;font-size:14px;margin-bottom:2px">ℹ Rappel : Complétez votre KYC</p>
          <p style="font-size:13px;opacity:.85">Terminez votre vérification d'identité pour débloquer toutes les fonctionnalités premium de trading.</p>
        @endif
      </div>
      <div style="display:flex;align-items:center;gap:8px">
        @if($user->kyc_status !== 'pending')
          <a href="{{ route('kyc.show') }}" style="font-size:12px;font-weight:700;padding:5px 14px;border-radius:8px;background:#2563eb;color:#fff;text-decoration:none;white-space:nowrap">
            Vérifier maintenant →
          </a>
        @endif
        <button onclick="document.getElementById('kycAlertBanner').remove()" style="background:none;border:none;cursor:pointer;opacity:.6;padding:4px">
          <span class="material-symbols-outlined" style="font-size:18px">close</span>
        </button>
      </div>
    </div>
  @endif

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Balance Widget -->
    <div class="stat-card">
      <div class="icon-box icon-wallet">💰</div>
      <div class="stat-label">Solde Total</div>
      <div class="stat-value">${{ $wallet ? number_format((float) $wallet->balance, 2) : '0.00' }}</div>
      <div class="stat-change">{{ $wallet?->currency ?? 'USD' }}</div>
    </div>

    <!-- Portfolio Widget -->
    <div class="stat-card">
      <div class="icon-box icon-chart">📈</div>
      <div class="stat-label">Valeur du Portefeuille</div>
      <div class="stat-value">${{ number_format($portfolioValue, 2) }}</div>
      <div class="stat-change @if($unrealisedPnl >= 0) positive @else negative @endif">
        @if($unrealisedPnl >= 0) ↑ @else ↓ @endif P&L non réalisé : ${{ number_format(abs($unrealisedPnl), 2) }}
      </div>
    </div>

    <!-- Profit/Loss Widget -->
    <div class="stat-card">
      <div class="icon-box icon-arrow">🎯</div>
      <div class="stat-label">Profit/Perte Réalisé</div>
      <div class="stat-value @if($realisedPnl >= 0) " style="color:#16a34a" @else " style="color:#dc2626" @endif">
        @if($realisedPnl >= 0)+@endif${{ number_format(abs($realisedPnl), 2) }}
      </div>
      <div class="stat-change @if($realisedPnl >= 0) positive @else negative @endif">Positions fermées</div>
    </div>

    <!-- Open Positions Widget -->
    <div class="stat-card">
      <div class="icon-box icon-trend">📊</div>
      <div class="stat-label">Positions Ouvertes</div>
      <div class="stat-value">{{ $openTradesCount }}</div>
      <div class="stat-change">{{ $openTradesProfitable }} profitable{{ $openTradesProfitable > 1 ? 's' : '' }}, {{ $openTradesPending }} en attente</div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content Area -->
    <div class="lg:col-span-2">
      <!-- Quick Actions -->
      <div class="card-section">
        <div class="section-title">Actions Rapides</div>
        <div class="grid grid-cols-4 gap-4">
          <a href="{{ route('trade.index') }}" class="quick-action">
            <div class="quick-action-icon">💹</div>
            <span>Trader</span>
          </a>
          <a href="{{ route('wallet.index') }}" class="quick-action">
            <div class="quick-action-icon">💳</div>
            <span>Wallet</span>
          </a>
          <a href="{{ route('wallet.index') }}" class="quick-action">
            <div class="quick-action-icon">🔄</div>
            <span>Transactions</span>
          </a>
          <a href="{{ route('profile.show') }}" class="quick-action">
            <div class="quick-action-icon">👤</div>
            <span>Profil</span>
          </a>
        </div>
      </div>

      <!-- Performance Chart -->
      <div class="card-section">
        <div class="section-title">
          <span>Performance — Trades récents</span>
          <a href="{{ route('trades.history') }}" class="view-all">Historique complet →</a>
        </div>
        @if($recentTrades->isNotEmpty())
          <canvas id="dashPerfChart" height="110"></canvas>
        @else
          <div style="height:120px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#94a3b8;font-size:14px;gap:8px;">
            <span style="font-size:32px;">📊</span>
            Aucun trade pour afficher le graphique.
          </div>
        @endif
      </div>

      <!-- Recent Trades -->
      <div class="card-section">
        <div class="section-title">
          <span>Trades Récents</span>
          <a href="{{ route('trades.history') }}" class="view-all">Voir tout →</a>
        </div>
        <div class="space-y-0">
          @forelse($recentTrades as $trade)
            <div class="transaction-item">
              <div class="transaction-info">
                <div class="transaction-icon {{ $trade->profit_loss >= 0 ? 'deposit' : 'withdrawal' }}">
                  {{ $trade->direction === 'buy' ? '📈' : '📉' }}
                </div>
                <div class="transaction-details">
                  <div class="transaction-type">
                    {{ ucfirst($trade->direction === 'buy' ? 'Achat' : 'Vente') }}
                    {{ $trade->instrument?->symbol ?? '—' }}
                  </div>
                  <div class="transaction-time">{{ $trade->opened_at?->diffForHumans() ?? '—' }}</div>
                </div>
              </div>
              <div class="transaction-amount {{ $trade->profit_loss >= 0 ? 'positive' : 'negative' }}">
                @if($trade->profit_loss >= 0)+@endif${{ number_format(abs((float) $trade->profit_loss), 2) }}
              </div>
            </div>
          @empty
            <div style="text-align:center; color:#94a3b8; padding: 24px 0; font-size:14px;">Aucun trade pour le moment.</div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div>
      <!-- Recent Deposits -->
      <div class="card-section">
        <div class="section-title">Dépôts Récents</div>
        <div class="space-y-0">
          @forelse($recentDeposits as $deposit)
            <div class="transaction-item">
              <div class="transaction-info">
                <div class="transaction-icon deposit">💵</div>
                <div class="transaction-details">
                  <div class="transaction-type">{{ ucfirst($deposit->method ?? 'Dépôt') }}</div>
                  <div class="transaction-time">{{ $deposit->created_at->diffForHumans() }}</div>
                </div>
              </div>
              <div class="transaction-amount positive">+${{ number_format((float) $deposit->amount, 2) }}</div>
            </div>
          @empty
            <div style="text-align:center; color:#94a3b8; padding: 24px 0; font-size:14px;">Aucun dépôt pour le moment.</div>
          @endforelse
        </div>
      </div>

      <!-- Referral -->
      <div class="card-section">
        <div class="section-title">Votre Code Référent</div>
        <div style="background: #f0f4f8; border-radius: 8px; padding: 12px; margin-bottom: 12px; word-break: break-all; font-family: monospace; font-size: 14px; font-weight: 600; color: #1e293b;">
          {{ $user->referral_code ?? 'N/A' }}
        </div>
        <a href="{{ route('referral.index') }}" style="display: block; text-align: center; padding: 10px; background: #3b82f6; color: white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
          Gérer les Parrainages →
        </a>
      </div>

      <!-- Account Status -->
      <div class="card-section">
        <div class="section-title">État du Compte</div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 14px; color: #64748b;">Email Vérifié</span>
            @if($user->email_verified_at)
              <span style="color: #22c55e; font-weight: 600;">✓</span>
            @else
              <span style="color: #ef4444; font-weight: 600;">✗</span>
            @endif
          </div>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 14px; color: #64748b;">2FA Activé</span>
            @if($user->two_factor_enabled)
              <span style="color: #22c55e; font-weight: 600;">✓</span>
            @else
              <span style="color: #ef4444; font-weight: 600;">✗</span>
            @endif
          </div>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 14px; color: #64748b;">KYC Vérifié</span>
            @if($user->kyc_status === 'verified')
              <span style="color: #22c55e; font-weight: 600;">✓</span>
            @elseif($user->kyc_status === 'rejected')
              <span style="color: #ef4444; font-weight: 600;">✗</span>
            @else
              <span style="color: #f59e0b; font-weight: 600;">⏳</span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  @php
    $chartTrades = $recentTrades->map(fn($t) => [
      'label' => ($t->instrument?->symbol ?? '?') . ' ' . ($t->opened_at?->format('d/m') ?? ''),
      'pnl'   => round((float)$t->profit_loss, 2),
      'dir'   => $t->direction,
    ]);
  @endphp

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // ── Graphique performances récentes ───────────────────────────
      const chartEl = document.getElementById('dashPerfChart');
      if (chartEl) {
        const trades = @json($chartTrades);

        const labels = trades.map(t => t.label);
        const values = trades.map(t => t.pnl);
        const colors = values.map(v => v >= 0 ? 'rgba(22,163,74,.8)' : 'rgba(220,38,38,.8)');
        const borders= values.map(v => v >= 0 ? '#16a34a' : '#dc2626');

        new Chart(chartEl, {
          type: 'bar',
          data: {
            labels,
            datasets: [{
              label: 'P&L ($)',
              data: values,
              backgroundColor: colors,
              borderColor: borders,
              borderWidth: 1.5,
              borderRadius: 6,
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: { display: false },
              tooltip: { callbacks: { label: ctx => ' ' + (ctx.parsed.y >= 0 ? '+' : '') + '$' + ctx.parsed.y.toFixed(2) } }
            },
            scales: {
              x: { grid: { display: false }, ticks: { font: { size: 11 } } },
              y: {
                grid: { color: '#f1f5f9' },
                ticks: { callback: v => (v >= 0 ? '+' : '') + '$' + v },
                grace: '10%',
              }
            }
          }
        });
      }

      console.log('Dashboard chargé pour :', '{{ addslashes($user->first_name ?? $user->name ?? "") }}');
    });
  </script>
  @endpush
</x-layouts.dashboard>
