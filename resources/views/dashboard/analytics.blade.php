<x-layouts.dashboard>
  <x-slot name="title">Analytics & Performance</x-slot>
  <x-slot name="subtitle">Analyse avancée de vos performances de trading</x-slot>

  @push('styles')
  <style>
    .period-btn { padding:6px 16px; border-radius:8px; font-size:13px; font-weight:600; border:1px solid #e2e8f0; background:#fff; color:#64748b; cursor:pointer; transition:all .15s; }
    .period-btn.active, .period-btn:hover { background:#0f172a; color:#fff; border-color:#0f172a; }
    .stat-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:20px 22px; transition:box-shadow .2s; }
    .stat-card:hover { box-shadow:0 4px 20px rgba(0,0,0,.07); }
    .stat-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; margin-bottom:6px; }
    .stat-value { font-size:28px; font-weight:800; color:#0f172a; line-height:1; }
    .stat-value.pos { color:#16a34a; }
    .stat-value.neg { color:#dc2626; }
    .stat-sub { font-size:12px; color:#94a3b8; margin-top:4px; }
    .chart-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:22px; }
    .chart-title { font-size:14px; font-weight:700; color:#0f172a; margin-bottom:16px; }
    /* Heatmap */
    .heatmap-grid { display:grid; grid-template-columns:40px repeat(24,1fr); gap:2px; font-size:10px; }
    .hm-label { display:flex; align-items:center; justify-content:flex-end; padding-right:6px; color:#94a3b8; font-weight:600; }
    .hm-cell { height:20px; border-radius:3px; background:#f1f5f9; transition:transform .1s; cursor:default; }
    .hm-cell:hover { transform:scale(1.2); }
  </style>
  @endpush

  {{-- Sélecteur de période --}}
  <div class="flex items-center gap-2 mb-8 flex-wrap">
    <span class="text-sm font-semibold text-slate-500 mr-2">Période :</span>
    @foreach(['7'=>'7 jours','30'=>'30 jours','90'=>'3 mois','180'=>'6 mois','365'=>'1 an','all'=>'Tout'] as $val=>$lbl)
      <a href="?period={{ $val }}" class="period-btn {{ $period == $val ? 'active' : '' }}">{{ $lbl }}</a>
    @endforeach
  </div>

  {{-- KPI Cards --}}
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="stat-card">
      <div class="stat-label">Total Trades</div>
      <div class="stat-value">{{ $total }}</div>
      <div class="stat-sub">{{ $wins }} gagnants · {{ $losses }} perdants</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Win Rate</div>
      <div class="stat-value {{ $winRate >= 50 ? 'pos' : 'neg' }}">{{ $winRate }}%</div>
      <div class="stat-sub">Taux de réussite</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Profit Factor</div>
      <div class="stat-value {{ $profitFactor > 1 ? 'pos' : 'neg' }}">{{ $profitFactor }}</div>
      <div class="stat-sub">Profit / Perte bruts</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Drawdown Max</div>
      <div class="stat-value neg">{{ number_format($maxDrawdown, 1) }}%</div>
      <div class="stat-sub">Perte max relative</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Durée moy.</div>
      <div class="stat-value" style="font-size:20px">{{ $avgDuration ? gmdate('H\hi', (int)$avgDuration) : '—' }}</div>
      <div class="stat-sub">Par trade</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Actif principal</div>
      <div class="stat-value" style="font-size:18px">{{ $topAsset?->instrument?->symbol ?? '—' }}</div>
      <div class="stat-sub">Le plus tradé</div>
    </div>
  </div>

  {{-- Graphiques ligne 1 : Balance + Donut --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="chart-card lg:col-span-2">
      <div class="chart-title">📈 Évolution du solde</div>
      <canvas id="balanceChart" height="100"></canvas>
    </div>
    <div class="chart-card">
      <div class="chart-title">🥧 Répartition par actif</div>
      <canvas id="assetDonut" height="160"></canvas>
    </div>
  </div>

  {{-- Graphiques ligne 2 : Bar DOW + Scatter --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="chart-card">
      <div class="chart-title">📅 P&L moyen par jour de semaine</div>
      <canvas id="dowChart" height="120"></canvas>
    </div>
    <div class="chart-card">
      <div class="chart-title">⏱ Durée vs P&L (scatter)</div>
      <canvas id="scatterChart" height="120"></canvas>
    </div>
  </div>

  {{-- Heatmap --}}
  <div class="chart-card mb-6">
    <div class="chart-title">🌡 Heatmap des horaires de trading (P&L moyen par heure × jour)</div>
    <div class="overflow-x-auto">
      <div class="heatmap-grid" id="heatmapGrid" style="min-width:800px">
        {{-- Généré par JS --}}
      </div>
    </div>
    <div class="flex items-center gap-2 mt-3 text-xs text-slate-400">
      <span>Perte</span>
      <div style="width:80px;height:10px;border-radius:4px;background:linear-gradient(90deg,#dc2626,#f1f5f9,#16a34a)"></div>
      <span>Profit</span>
    </div>
  </div>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
  <script>
    const C = (id) => document.getElementById(id)?.getContext('2d');

    // ── Balance chart ─────────────────────────────────────────────
    @if($balanceChart->count() > 0)
    new Chart(C('balanceChart'), {
      type:'line',
      data:{
        labels: @json($balanceChart->pluck('date')),
        datasets:[{
          data: @json($balanceChart->pluck('pnl')),
          borderColor:'#547A95', backgroundColor:'rgba(84,122,149,.08)',
          fill:true, tension:.4, pointRadius:2, borderWidth:2
        }]
      },
      options:{ responsive:true, plugins:{legend:{display:false}},
        scales:{ x:{grid:{display:false}}, y:{ticks:{callback:v=>'$'+v}} } }
    });
    @endif

    // ── Asset donut ───────────────────────────────────────────────
    @if($byAsset->count() > 0)
    new Chart(C('assetDonut'), {
      type:'doughnut',
      data:{
        labels: @json($byAsset->pluck('label')),
        datasets:[{ data: @json($byAsset->pluck('count')),
          backgroundColor:['#547A95','#2C3947','#16a34a','#dc2626','#f59e0b','#8b5cf6'],
          borderWidth:0 }]
      },
      options:{ responsive:true, plugins:{legend:{position:'bottom',labels:{font:{size:11}}}} }
    });
    @endif

    // ── DOW bar ───────────────────────────────────────────────────
    const dowData = @json($dowData);
    new Chart(C('dowChart'), {
      type:'bar',
      data:{
        labels: @json($dowLabels),
        datasets:[{ data:dowData, label:'P&L moyen ($)',
          backgroundColor: dowData.map(v => v >= 0 ? 'rgba(22,163,74,.7)' : 'rgba(220,38,38,.7)'),
          borderRadius:6 }]
      },
      options:{ responsive:true, plugins:{legend:{display:false}},
        scales:{ y:{ticks:{callback:v=>'$'+v}, grid:{color:'#f1f5f9'}} } }
    });

    // ── Scatter ───────────────────────────────────────────────────
    const scatter = @json($scatter);
    new Chart(C('scatterChart'), {
      type:'scatter',
      data:{ datasets:[{ data:scatter, label:'Durée vs P&L',
        backgroundColor: scatter.map(p => p.y >= 0 ? 'rgba(22,163,74,.5)' : 'rgba(220,38,38,.5)'),
        pointRadius:4 }]},
      options:{ responsive:true, plugins:{legend:{display:false}},
        scales:{
          x:{title:{display:true,text:'Durée (min)'}},
          y:{title:{display:true,text:'P&L ($)'}, ticks:{callback:v=>'$'+v}}
        }}
    });

    // ── Heatmap ───────────────────────────────────────────────────
    const heatmap = @json($heatmap);
    const days = ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'];
    const grid = document.getElementById('heatmapGrid');
    if (grid) {
      // Ligne d'en-têtes heures
      const emptyCell = document.createElement('div');
      grid.appendChild(emptyCell);
      for (let h = 0; h < 24; h++) {
        const lbl = document.createElement('div');
        lbl.style.cssText = 'text-align:center;font-size:9px;color:#94a3b8;';
        lbl.textContent = h;
        grid.appendChild(lbl);
      }
      // Lignes jours
      for (let d = 1; d <= 7; d++) {
        const dayLbl = document.createElement('div');
        dayLbl.className = 'hm-label';
        dayLbl.textContent = days[d-1];
        grid.appendChild(dayLbl);
        for (let h = 0; h < 24; h++) {
          const val = heatmap[d]?.[h] ?? null;
          const cell = document.createElement('div');
          cell.className = 'hm-cell';
          if (val !== null) {
            const maxAbs = 50;
            const ratio = Math.min(Math.abs(val) / maxAbs, 1);
            if (val >= 0) cell.style.background = `rgba(22,163,74,${0.1 + ratio * 0.8})`;
            else cell.style.background = `rgba(220,38,38,${0.1 + ratio * 0.8})`;
            cell.title = `${days[d-1]} ${h}h : ${val >= 0 ? '+' : ''}$${val.toFixed(2)}`;
          }
          grid.appendChild(cell);
        }
      }
    }
  </script>
  @endpush

</x-layouts.dashboard>
