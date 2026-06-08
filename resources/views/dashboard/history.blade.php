<x-layouts.dashboard>
  <x-slot name="title">Historique des Trades</x-slot>
  <x-slot name="subtitle">Analyse complète de vos trades clôturés</x-slot>

  @push('styles')
  <style>
    /* ── KPI Cards ─────────────────────────────────── */
    .kpi-card {
      background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
      padding: 20px 24px; transition: box-shadow .2s;
    }
    .kpi-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.06); }
    .kpi-label { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; }
    .kpi-value { font-size: 26px; font-weight: 700; color: #0f172a; margin-top: 4px; line-height: 1.2; }
    .kpi-value.positive { color: #16a34a; }
    .kpi-value.negative { color: #dc2626; }

    /* ── Filters ───────────────────────────────────── */
    .filter-bar { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; }
    .filter-group label { display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px; }
    .filter-input {
      padding: 7px 12px; border: 1px solid #e2e8f0; border-radius: 8px;
      font-size: 13px; background: #fff; color: #0f172a; outline: none;
      transition: border-color .2s;
    }
    .filter-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }

    /* ── Table ─────────────────────────────────────── */
    .trades-table th { font-size: 11px; font-weight: 700; text-transform: uppercase;
      letter-spacing:.04em; color: #94a3b8; padding: 12px 16px; text-align: left;
      cursor: pointer; user-select: none; white-space: nowrap; }
    .trades-table th:hover { color: #475569; }
    .trades-table td { padding: 14px 16px; font-size: 13px; color: #334155; vertical-align: middle; }
    .trades-table tbody tr { border-top: 1px solid #f1f5f9; transition: background .15s; cursor: pointer; }
    .trades-table tbody tr:hover { background: #f8fafc; }

    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; }
    .badge-buy  { background: #dcfce7; color: #16a34a; }
    .badge-sell { background: #fee2e2; color: #dc2626; }
    .badge-profit { background: #dcfce7; color: #16a34a; }
    .badge-loss   { background: #fee2e2; color: #dc2626; }

    /* ── Slide-over ────────────────────────────────── */
    #slideOver {
      position: fixed; top: 0; right: 0; bottom: 0; width: 420px; max-width: 95vw;
      background: #fff; box-shadow: -8px 0 40px rgba(0,0,0,.12);
      transform: translateX(100%); transition: transform .3s ease;
      z-index: 50; overflow-y: auto;
    }
    #slideOver.open { transform: translateX(0); }
    #slideOverBackdrop {
      position: fixed; inset: 0; background: rgba(0,0,0,.3);
      opacity: 0; visibility: hidden; transition: all .3s; z-index: 49;
    }
    #slideOverBackdrop.open { opacity: 1; visibility: visible; }

    .detail-row { display: flex; justify-content: space-between; padding: 10px 0;
      border-bottom: 1px solid #f1f5f9; font-size: 13px; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: #94a3b8; font-weight: 500; }
    .detail-value { color: #0f172a; font-weight: 600; }
  </style>
  @endpush

  {{-- ══════════════ KPI CARDS ══════════════ --}}
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-4 mb-8">
    <div class="kpi-card">
      <div class="kpi-label">Total trades</div>
      <div class="kpi-value">{{ $totalTrades }}</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Win Rate</div>
      <div class="kpi-value {{ $winRate >= 50 ? 'positive' : 'negative' }}">{{ $winRate }}%</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Profit total</div>
      <div class="kpi-value positive">+${{ number_format((float)$totalProfit, 2) }}</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Perte totale</div>
      <div class="kpi-value negative">${{ number_format((float)$totalLoss, 2) }}</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Meilleur</div>
      <div class="kpi-value positive">+${{ number_format((float)$bestTrade, 2) }}</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Pire</div>
      <div class="kpi-value negative">${{ number_format((float)$worstTrade, 2) }}</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Durée moy.</div>
      <div class="kpi-value" style="font-size:18px">
        @if($avgDuration)
          {{ gmdate('H\hi', (int)$avgDuration) }}
        @else
          —
        @endif
      </div>
    </div>
  </div>

  {{-- ══════════════ GRAPHIQUE P&L CUMULATIF ══════════════ --}}
  <div class="bg-white border border-slate-200 rounded-xl p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-semibold text-slate-800">P&L Cumulatif</h2>
      <span class="text-xs text-slate-400">Tous les trades clôturés</span>
    </div>
    @if($chartData->count() > 0)
      <canvas id="pnlChart" height="80"></canvas>
    @else
      <div class="h-24 flex items-center justify-center text-slate-400 text-sm">
        Pas encore de données de trades clôturés.
      </div>
    @endif
  </div>

  {{-- ══════════════ FILTRES ══════════════ --}}
  <div class="bg-white border border-slate-200 rounded-xl p-5 mb-6">
    <form method="GET" action="{{ route('trades.history') }}" class="filter-bar">
      <div class="filter-group">
        <label>Symbole</label>
        <input type="text" name="symbol" value="{{ request('symbol') }}" placeholder="EURUSD…" class="filter-input w-28"/>
      </div>
      <div class="filter-group">
        <label>Direction</label>
        <select name="direction" class="filter-input">
          <option value="">Tous</option>
          <option value="buy"  {{ request('direction') === 'buy'  ? 'selected' : '' }}>BUY</option>
          <option value="sell" {{ request('direction') === 'sell' ? 'selected' : '' }}>SELL</option>
        </select>
      </div>
      <div class="filter-group">
        <label>Résultat</label>
        <select name="result" class="filter-input">
          <option value="">Tous</option>
          <option value="profit" {{ request('result') === 'profit' ? 'selected' : '' }}>Profit</option>
          <option value="loss"   {{ request('result') === 'loss'   ? 'selected' : '' }}>Perte</option>
        </select>
      </div>
      <div class="filter-group">
        <label>Du</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-input"/>
      </div>
      <div class="filter-group">
        <label>Au</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-input"/>
      </div>
      <div class="flex gap-2 mt-auto">
        <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors">
          Filtrer
        </button>
        <a href="{{ route('trades.history') }}" class="px-4 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold rounded-lg transition-colors">
          Reset
        </a>
      </div>
      <div class="mt-auto ml-auto">
        <a href="{{ route('transactions.export') }}" class="inline-flex items-center gap-1 px-4 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold rounded-lg transition-colors">
          <span class="material-symbols-outlined text-[16px]">download</span> CSV
        </a>
      </div>
    </form>
  </div>

  {{-- ══════════════ TABLEAU ══════════════ --}}
  <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full trades-table">
        <thead class="bg-slate-50 border-b border-slate-200">
          <tr>
            <th>Date ouv.</th>
            <th>Date clôt.</th>
            <th>Symbole</th>
            <th>Direction</th>
            <th>Volume</th>
            <th>Prix entrée</th>
            <th>Prix sortie</th>
            <th>SL</th>
            <th>TP</th>
            <th>P&L ($)</th>
            <th>Pips</th>
            <th>Durée</th>
          </tr>
        </thead>
        <tbody>
          @forelse($trades as $trade)
            @php $pnl = (float)$trade->profit_loss; @endphp
            <tr onclick="openDetail({{ $trade->id }}, this)" data-id="{{ $trade->id }}">
              <td class="text-slate-400">{{ $trade->opened_at?->format('d/m/Y H:i') ?? '—' }}</td>
              <td class="text-slate-400">{{ $trade->closed_at?->format('d/m/Y H:i') ?? '—' }}</td>
              <td class="font-semibold text-slate-900">{{ $trade->instrument?->symbol ?? '—' }}</td>
              <td>
                <span class="badge {{ $trade->direction === 'buy' ? 'badge-buy' : 'badge-sell' }}">
                  {{ strtoupper($trade->direction) }}
                </span>
              </td>
              <td class="font-mono">{{ number_format((float)$trade->volume, 2) }}</td>
              <td class="font-mono">{{ number_format((float)$trade->entry_price, 5) }}</td>
              <td class="font-mono">{{ $trade->exit_price ? number_format((float)$trade->exit_price, 5) : '—' }}</td>
              <td class="font-mono text-slate-400">{{ $trade->stop_loss ? number_format((float)$trade->stop_loss, 5) : '—' }}</td>
              <td class="font-mono text-slate-400">{{ $trade->take_profit ? number_format((float)$trade->take_profit, 5) : '—' }}</td>
              <td>
                <span class="{{ $pnl >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                  {{ $pnl >= 0 ? '+' : '' }}${{ number_format(abs($pnl), 2) }}
                </span>
              </td>
              <td>
                @if($trade->profit_loss_pips !== null)
                  <span class="{{ (float)$trade->profit_loss_pips >= 0 ? 'text-green-600' : 'text-red-600' }} font-mono text-xs">
                    {{ (float)$trade->profit_loss_pips >= 0 ? '+' : '' }}{{ number_format((float)$trade->profit_loss_pips, 1) }}
                  </span>
                @else —
                @endif
              </td>
              <td class="text-slate-400">
                {{ $trade->duration_seconds ? gmdate('H\hi\ms\s', (int)$trade->duration_seconds) : '—' }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="12" class="py-16 text-center text-slate-400">
                <span class="material-symbols-outlined text-4xl mb-2 block">history</span>
                Aucun trade clôturé trouvé.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($trades->hasPages())
      <div class="px-6 py-4 border-t border-slate-100">
        {{ $trades->links() }}
      </div>
    @endif
  </div>

  {{-- ══════════════ SLIDE-OVER DÉTAIL ══════════════ --}}
  <div id="slideOverBackdrop" onclick="closeDetail()"></div>
  <div id="slideOver">
    <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-4 flex items-center justify-between z-10">
      <h3 class="font-bold text-slate-900 text-lg">Détail du trade</h3>
      <button onclick="closeDetail()" class="text-slate-400 hover:text-slate-700 transition-colors">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <div id="slideOverContent" class="px-6 py-6">
      <div class="text-slate-400 text-sm text-center py-8">Chargement…</div>
    </div>
  </div>

  {{-- Données JSON pour le slide-over --}}
  <script id="tradesData" type="application/json">
    @json($trades->items())
  </script>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
  <script>
    // ─── Chart P&L cumulatif ─────────────────────────────────────────
    @if($chartData->count() > 0)
    const chartEl = document.getElementById('pnlChart');
    if (chartEl) {
      const labels = @json($chartData->pluck('date'));
      const data   = @json($chartData->pluck('pnl'));
      const lastVal = data[data.length - 1] || 0;
      const color  = lastVal >= 0 ? '#16a34a' : '#dc2626';

      new Chart(chartEl, {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: 'P&L cumulatif ($)',
            data,
            borderColor: color,
            backgroundColor: lastVal >= 0 ? 'rgba(22,163,74,.08)' : 'rgba(220,38,38,.08)',
            fill: true,
            tension: 0.4,
            pointRadius: 3,
            pointBackgroundColor: color,
            borderWidth: 2,
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: ctx => ' $' + ctx.parsed.y.toFixed(2)
              }
            }
          },
          scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: { grid: { color: '#f1f5f9' }, ticks: { callback: v => '$' + v } }
          }
        }
      });
    }
    @endif

    // ─── Slide-over détail ───────────────────────────────────────────
    const tradesData = JSON.parse(document.getElementById('tradesData').textContent);

    function openDetail(id, row) {
      const trade = tradesData.find(t => t.id === id);
      if (!trade) return;

      const pnl = parseFloat(trade.profit_loss || 0);
      const isPos = pnl >= 0;
      const dir = (trade.direction || '').toUpperCase();

      document.getElementById('slideOverContent').innerHTML = `
        <div style="margin-bottom:16px">
          <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 14px;border-radius:99px;font-size:12px;font-weight:700;background:${dir==='BUY'?'#dcfce7':'#fee2e2'};color:${dir==='BUY'?'#16a34a':'#dc2626'}">
            ${dir}
          </span>
          <span style="margin-left:8px;font-size:18px;font-weight:700;color:#0f172a">${trade.instrument?.symbol ?? '—'}</span>
        </div>
        <div style="background:${isPos?'#f0fdf4':'#fef2f2'};border-radius:10px;padding:16px;margin-bottom:20px;text-align:center">
          <div style="font-size:13px;color:#94a3b8;margin-bottom:4px">Résultat</div>
          <div style="font-size:32px;font-weight:800;color:${isPos?'#16a34a':'#dc2626'}">${isPos?'+':''}$${Math.abs(pnl).toFixed(2)}</div>
        </div>
        <div class="detail-row"><span class="detail-label">Date ouverture</span><span class="detail-value">${trade.opened_at ? new Date(trade.opened_at).toLocaleString('fr-FR') : '—'}</span></div>
        <div class="detail-row"><span class="detail-label">Date clôture</span><span class="detail-value">${trade.closed_at ? new Date(trade.closed_at).toLocaleString('fr-FR') : '—'}</span></div>
        <div class="detail-row"><span class="detail-label">Volume</span><span class="detail-value">${parseFloat(trade.volume || 0).toFixed(2)} lots</span></div>
        <div class="detail-row"><span class="detail-label">Prix entrée</span><span class="detail-value font-mono">${parseFloat(trade.entry_price || 0).toFixed(5)}</span></div>
        <div class="detail-row"><span class="detail-label">Prix sortie</span><span class="detail-value font-mono">${trade.exit_price ? parseFloat(trade.exit_price).toFixed(5) : '—'}</span></div>
        <div class="detail-row"><span class="detail-label">Stop Loss</span><span class="detail-value font-mono">${trade.stop_loss ? parseFloat(trade.stop_loss).toFixed(5) : '—'}</span></div>
        <div class="detail-row"><span class="detail-label">Take Profit</span><span class="detail-value font-mono">${trade.take_profit ? parseFloat(trade.take_profit).toFixed(5) : '—'}</span></div>
        <div class="detail-row"><span class="detail-label">Résultat (pips)</span><span class="detail-value" style="color:${isPos?'#16a34a':'#dc2626'}">${trade.profit_loss_pips !== null ? (parseFloat(trade.profit_loss_pips) >= 0 ? '+' : '') + parseFloat(trade.profit_loss_pips || 0).toFixed(1) : '—'}</span></div>
        <div class="detail-row"><span class="detail-label">Durée</span><span class="detail-value">${trade.duration_seconds ? formatDuration(trade.duration_seconds) : '—'}</span></div>
      `;

      document.getElementById('slideOver').classList.add('open');
      document.getElementById('slideOverBackdrop').classList.add('open');
    }

    function closeDetail() {
      document.getElementById('slideOver').classList.remove('open');
      document.getElementById('slideOverBackdrop').classList.remove('open');
    }

    function formatDuration(secs) {
      const h = Math.floor(secs / 3600);
      const m = Math.floor((secs % 3600) / 60);
      const s = secs % 60;
      return `${h}h ${m}m ${s}s`;
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetail(); });
  </script>
  @endpush

</x-layouts.dashboard>
