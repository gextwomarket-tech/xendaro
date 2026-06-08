<x-layouts.dashboard>
  <x-slot name="title">Marchés</x-slot>
  <x-slot name="subtitle">Explorez tous les actifs disponibles avec cotations en temps réel</x-slot>

  @push('styles')
  <style>
    /* ── Tabs ────────────────────────────────────── */
    .market-tabs { display: flex; gap: 4px; background: #f1f5f9; border-radius: 10px; padding: 4px; }
    .market-tab {
      padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 600;
      cursor: pointer; transition: all 0.2s; border: none; background: transparent;
      color: #64748b; white-space: nowrap;
    }
    .market-tab.active { background: #fff; color: #1e293b; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    .market-tab:hover:not(.active) { color: #334155; background: rgba(255,255,255,.5); }

    /* ── Table ───────────────────────────────────── */
    .markets-table th { cursor: pointer; user-select: none; white-space: nowrap; }
    .markets-table th:hover { background: #f8fafc; }
    .sort-icon { font-size: 14px; vertical-align: middle; opacity: 0.4; }
    .sort-icon.active { opacity: 1; color: #2563eb; }

    /* ── Variation badge ─────────────────────────── */
    .change-positive { color: #16a34a; font-weight: 600; }
    .change-negative { color: #dc2626; font-weight: 600; }
    @keyframes flicker { 0%,100%{opacity:1} 50%{opacity:.4} }
    .flicker { animation: flicker .4s ease; }

    /* ── Status badge ────────────────────────────── */
    .status-open  { background:#dcfce7; color:#16a34a; padding:2px 8px; border-radius:99px; font-size:11px; font-weight:700; }
    .status-closed{ background:#fee2e2; color:#dc2626; padding:2px 8px; border-radius:99px; font-size:11px; font-weight:700; }

    /* ── Favorite star ───────────────────────────── */
    .fav-btn { background:none; border:none; cursor:pointer; padding:4px; border-radius:6px; transition:all .15s; }
    .fav-btn:hover { background:#fef9c3; }
    .fav-star { font-size: 20px; transition: color .2s, transform .2s; }
    .fav-star.filled  { color: #f59e0b; }
    .fav-star.empty   { color: #cbd5e1; }
    .fav-btn:active .fav-star { transform: scale(1.4); }

    /* ── Skeleton ────────────────────────────────── */
    .skeleton { background: linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);
      background-size: 200% 100%; animation: shimmer 1.4s infinite; border-radius:6px; }
    @keyframes shimmer { 0%{background-position:200%} 100%{background-position:-200%} }

    /* ── Sparkline canvas ────────────────────────── */
    canvas.sparkline { display: block; }
  </style>
  @endpush

  {{-- ══════════════ TOOLBAR ══════════════ --}}
  <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">

    {{-- Tabs catégories --}}
    <div class="market-tabs overflow-x-auto flex-shrink-0" id="categoryTabs">
      <button class="market-tab active" data-cat="all" onclick="filterCat('all',this)">Tous</button>
      @foreach($categories as $cat)
        <button class="market-tab" data-cat="{{ strtolower($cat) }}" onclick="filterCat('{{ strtolower($cat) }}',this)">
          {{ ucfirst($cat) }}
        </button>
      @endforeach
      <button class="market-tab" data-cat="favorites" onclick="filterCat('favorites',this)">⭐ Favoris</button>
    </div>

    {{-- Recherche --}}
    <div class="relative flex-1 min-w-0 sm:max-w-xs">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
      <input
        id="searchInput"
        type="text"
        placeholder="Rechercher un actif…"
        oninput="applyFilters()"
        class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
    </div>
  </div>

  {{-- ══════════════ TABLE ══════════════ --}}
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

    {{-- Header stats --}}
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
      <span class="text-sm text-slate-500">
        <span id="visibleCount">{{ $instruments->count() }}</span> actif(s) affiché(s)
      </span>
      <span class="text-xs text-slate-400">Cotations indicatives</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full markets-table" id="marketsTable">
        <thead class="bg-slate-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide" onclick="sortTable('symbol')">
              Symbole <span class="material-symbols-outlined sort-icon" id="sort-symbol">unfold_more</span>
            </th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide" onclick="sortTable('bid')">
              Bid <span class="material-symbols-outlined sort-icon" id="sort-bid">unfold_more</span>
            </th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide" onclick="sortTable('ask')">
              Ask <span class="material-symbols-outlined sort-icon" id="sort-ask">unfold_more</span>
            </th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide" onclick="sortTable('spread')">
              Spread <span class="material-symbols-outlined sort-icon" id="sort-spread">unfold_more</span>
            </th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide" onclick="sortTable('change')">
              Var. 24h <span class="material-symbols-outlined sort-icon" id="sort-change">unfold_more</span>
            </th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tendance</th>
            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Statut</th>
            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">⭐</th>
            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Action</th>
          </tr>
        </thead>
        <tbody id="marketsBody" class="divide-y divide-slate-100">
          @forelse($instruments as $instrument)
            @php
              $isFav   = in_array($instrument->id, $favoriteIds);
              $change  = (float) ($instrument->change_24h_percent ?? 0);
              $isOpen  = $instrument->is_active;
              $cat     = strtolower($instrument->category ?? 'other');
            @endphp
            <tr
              class="market-row hover:bg-slate-50 transition-colors"
              data-symbol="{{ strtolower($instrument->symbol) }}"
              data-name="{{ strtolower($instrument->name ?? $instrument->symbol) }}"
              data-cat="{{ $cat }}"
              data-fav="{{ $isFav ? '1' : '0' }}"
              data-bid="{{ (float)$instrument->bid }}"
              data-ask="{{ (float)$instrument->ask }}"
              data-spread="{{ (float)$instrument->spread }}"
              data-change="{{ $change }}"
            >
              {{-- Symbole --}}
              <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-600">
                    {{ strtoupper(substr($instrument->symbol, 0, 2)) }}
                  </div>
                  <div>
                    <div class="font-semibold text-slate-900 text-sm">{{ $instrument->symbol }}</div>
                    <div class="text-xs text-slate-400">{{ $instrument->name ?? $instrument->category }}</div>
                  </div>
                </div>
              </td>

              {{-- Bid --}}
              <td class="px-4 py-3 text-sm font-mono text-slate-700">
                {{ number_format((float)$instrument->bid, 5) }}
              </td>

              {{-- Ask --}}
              <td class="px-4 py-3 text-sm font-mono text-slate-700">
                {{ number_format((float)$instrument->ask, 5) }}
              </td>

              {{-- Spread --}}
              <td class="px-4 py-3 text-sm text-slate-500">
                {{ number_format((float)$instrument->spread, 1) }}
              </td>

              {{-- Variation 24h --}}
              <td class="px-4 py-3">
                <span class="{{ $change >= 0 ? 'change-positive' : 'change-negative' }} text-sm change-cell">
                  {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 2) }}%
                </span>
              </td>

              {{-- Sparkline --}}
              <td class="px-4 py-3">
                <canvas class="sparkline" width="80" height="28" data-change="{{ $change }}"></canvas>
              </td>

              {{-- Statut --}}
              <td class="px-4 py-3 text-center">
                @if($isOpen)
                  <span class="status-open">Ouvert</span>
                @else
                  <span class="status-closed">Fermé</span>
                @endif
              </td>

              {{-- Favori --}}
              <td class="px-4 py-3 text-center">
                <form method="POST" action="{{ route('favorites.toggle', $instrument->symbol) }}" class="fav-form inline">
                  @csrf
                  <button type="submit" class="fav-btn" title="{{ $isFav ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                    <span class="material-symbols-outlined fav-star {{ $isFav ? 'filled' : 'empty' }}"
                          style="{{ $isFav ? 'font-variation-settings:\'FILL\' 1' : '' }}">
                      star
                    </span>
                  </button>
                </form>
              </td>

              {{-- Trader --}}
              <td class="px-4 py-3 text-center">
                <a
                  href="{{ route('trade.index') }}?symbol={{ $instrument->symbol }}"
                  class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-900 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition-colors"
                >
                  <span class="material-symbols-outlined text-[14px]">show_chart</span>
                  Trader
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-6 py-16 text-center text-slate-400">
                <span class="material-symbols-outlined text-4xl mb-3 block">bar_chart</span>
                Aucun instrument disponible pour le moment.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Empty state --}}
    <div id="emptyState" class="hidden px-6 py-16 text-center text-slate-400">
      <span class="material-symbols-outlined text-4xl mb-3 block">search_off</span>
      Aucun résultat pour cette recherche.
    </div>
  </div>

  @push('scripts')
  <script>
    // ─── State ───────────────────────────────────────────────────────
    let currentCat  = 'all';
    let currentSort = { col: null, dir: 'asc' };
    const FAVE_IDS  = @json($favoriteIds);

    // ─── Filter by category tab ───────────────────────────────────────
    function filterCat(cat, btn) {
      currentCat = cat;
      document.querySelectorAll('.market-tab').forEach(t => t.classList.remove('active'));
      btn.classList.add('active');
      applyFilters();
    }

    // ─── Apply all filters (search + cat) ────────────────────────────
    function applyFilters() {
      const query = document.getElementById('searchInput').value.toLowerCase().trim();
      const rows  = document.querySelectorAll('.market-row');
      let visible = 0;

      rows.forEach(row => {
        const sym  = row.dataset.symbol;
        const name = row.dataset.name;
        const cat  = row.dataset.cat;
        const fav  = row.dataset.fav === '1';

        const matchSearch   = !query || sym.includes(query) || name.includes(query);
        const matchCat      = currentCat === 'all'
                            || (currentCat === 'favorites' ? fav : cat === currentCat);

        if (matchSearch && matchCat) {
          row.style.display = '';
          visible++;
        } else {
          row.style.display = 'none';
        }
      });

      document.getElementById('visibleCount').textContent = visible;
      document.getElementById('emptyState').classList.toggle('hidden', visible > 0);
    }

    // ─── Sort table ───────────────────────────────────────────────────
    function sortTable(col) {
      if (currentSort.col === col) {
        currentSort.dir = currentSort.dir === 'asc' ? 'desc' : 'asc';
      } else {
        currentSort.col = col;
        currentSort.dir = 'asc';
      }

      // Update icons
      document.querySelectorAll('.sort-icon').forEach(el => {
        el.textContent = 'unfold_more';
        el.classList.remove('active');
      });
      const icon = document.getElementById('sort-' + col);
      if (icon) {
        icon.textContent = currentSort.dir === 'asc' ? 'keyboard_arrow_up' : 'keyboard_arrow_down';
        icon.classList.add('active');
      }

      const tbody = document.getElementById('marketsBody');
      const rows  = Array.from(tbody.querySelectorAll('.market-row'));

      rows.sort((a, b) => {
        let va, vb;
        if (col === 'symbol') {
          va = a.dataset.symbol; vb = b.dataset.symbol;
          return currentSort.dir === 'asc' ? va.localeCompare(vb) : vb.localeCompare(va);
        }
        const map = { bid:'bid', ask:'ask', spread:'spread', change:'change' };
        va = parseFloat(a.dataset[map[col]] || 0);
        vb = parseFloat(b.dataset[map[col]] || 0);
        return currentSort.dir === 'asc' ? va - vb : vb - va;
      });

      rows.forEach(r => tbody.appendChild(r));
    }

    // ─── Sparkline mini-chart ─────────────────────────────────────────
    function drawSparkline(canvas, change) {
      const ctx = canvas.getContext('2d');
      const w = canvas.width, h = canvas.height;
      const isUp = change >= 0;
      const color = isUp ? '#16a34a' : '#dc2626';

      // Generate pseudo-data based on change direction
      const pts = [];
      let v = h / 2;
      for (let i = 0; i < 10; i++) {
        v += (Math.random() - (isUp ? 0.35 : 0.65)) * 6;
        v = Math.max(4, Math.min(h - 4, v));
        pts.push(v);
      }

      ctx.clearRect(0, 0, w, h);

      // Fill area
      const grad = ctx.createLinearGradient(0, 0, 0, h);
      grad.addColorStop(0, isUp ? 'rgba(22,163,74,.2)' : 'rgba(220,38,38,.2)');
      grad.addColorStop(1, 'rgba(255,255,255,0)');

      ctx.beginPath();
      ctx.moveTo(0, pts[0]);
      pts.forEach((y, i) => ctx.lineTo((i / (pts.length - 1)) * w, y));
      ctx.lineTo(w, h); ctx.lineTo(0, h); ctx.closePath();
      ctx.fillStyle = grad; ctx.fill();

      // Line
      ctx.beginPath();
      ctx.moveTo(0, pts[0]);
      pts.forEach((y, i) => ctx.lineTo((i / (pts.length - 1)) * w, y));
      ctx.strokeStyle = color; ctx.lineWidth = 2; ctx.stroke();
    }

    // ─── Fav form AJAX (no reload) ────────────────────────────────────
    document.querySelectorAll('.fav-form').forEach(form => {
      form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn  = form.querySelector('.fav-btn');
        const star = form.querySelector('.fav-star');
        const row  = form.closest('.market-row');
        const isFav = row.dataset.fav === '1';

        try {
          const res = await fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          });
          if (!res.ok) throw new Error();

          // Toggle state
          if (isFav) {
            row.dataset.fav = '0';
            star.classList.replace('filled','empty');
            star.style.fontVariationSettings = '';
          } else {
            row.dataset.fav = '1';
            star.classList.replace('empty','filled');
            star.style.fontVariationSettings = "'FILL' 1";
          }
          // Animate
          star.style.transform = 'scale(1.4)';
          setTimeout(() => star.style.transform = '', 200);
        } catch {
          form.submit(); // fallback
        }
      });
    });

    // ─── Init sparklines ──────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('canvas.sparkline').forEach(c => {
        drawSparkline(c, parseFloat(c.dataset.change || 0));
      });
    });
  </script>
  @endpush

</x-layouts.dashboard>
