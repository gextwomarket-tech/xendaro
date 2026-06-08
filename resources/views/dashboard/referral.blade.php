<x-layouts.dashboard>
  <x-slot name="title">Programme de Parrainage</x-slot>
  <x-slot name="subtitle">Invitez vos amis et gagnez des commissions sur leurs trades</x-slot>

  @push('styles')
  <style>
    .ref-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:24px; }
    .kpi-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px; margin-bottom:24px; }
    .kpi-box { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:18px 20px; text-align:center; }
    .kpi-val { font-size:28px; font-weight:800; color:#0f172a; }
    .kpi-lbl { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#94a3b8; margin-top:4px; }
    .share-btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; transition:opacity .15s; }
    .share-btn:hover { opacity:.85; }
    .ref-table th { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#94a3b8; padding:10px 14px; text-align:left; }
    .ref-table td { padding:12px 14px; font-size:13px; color:#334155; border-top:1px solid #f1f5f9; }
    .badge { display:inline-flex; align-items:center; padding:2px 10px; border-radius:99px; font-size:11px; font-weight:700; }
    .b-active { background:#dcfce7; color:#16a34a; }
    .b-pending { background:#fef9c3; color:#ca8a04; }
    .b-inactive { background:#f1f5f9; color:#94a3b8; }
    .copy-btn { transition:all .2s; }
    .copy-btn.copied { background:#16a34a !important; color:#fff !important; }
  </style>
  @endpush

  {{-- Lien de parrainage --}}
  <div class="ref-card mb-6">
    <div class="flex flex-col md:flex-row md:items-center gap-6">
      <div class="flex-1">
        <h2 class="font-bold text-slate-900 text-lg mb-1">🔗 Votre lien de parrainage</h2>
        <p class="text-sm text-slate-500 mb-4">Partagez ce lien unique. Vous recevez une commission sur chaque trader actif que vous recrutez.</p>
        <div class="flex items-center gap-2">
          <input id="refUrl" type="text" readonly value="{{ $referralUrl }}"
            class="flex-1 px-4 py-2 border border-slate-200 rounded-lg font-mono text-sm bg-slate-50 text-slate-700 outline-none" />
          <button id="copyBtn" onclick="copyLink()"
            class="copy-btn px-4 py-2 bg-slate-900 text-white text-sm font-semibold rounded-lg">
            Copier
          </button>
        </div>
        <p class="mt-2 text-xs text-slate-400">Code : <strong>{{ $user->referral_code }}</strong></p>
      </div>
      <div class="text-center">
        <canvas id="qrCanvas" width="120" height="120"></canvas>
        <div class="mt-1">
          <button onclick="downloadQR()" class="text-xs text-blue-500 hover:underline">Télécharger QR</button>
        </div>
      </div>
    </div>

    {{-- Boutons partage --}}
    <div class="flex flex-wrap gap-2 mt-5 pt-5 border-t border-slate-100">
      <a href="https://wa.me/?text={{ urlencode('Rejoignez-moi sur Brocker et commencez à trader ! ' . $referralUrl) }}" target="_blank"
         class="share-btn" style="background:#25D366;color:#fff">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        WhatsApp
      </a>
      <a href="https://t.me/share/url?url={{ urlencode($referralUrl) }}&text={{ urlencode('Rejoignez Brocker et tradez comme un pro !') }}" target="_blank"
         class="share-btn" style="background:#0088cc;color:#fff">
        Telegram
      </a>
      <a href="https://twitter.com/intent/tweet?text={{ urlencode('Je trade sur Brocker ! Rejoins-moi avec mon lien : ' . $referralUrl) }}" target="_blank"
         class="share-btn" style="background:#1DA1F2;color:#fff">
        Twitter / X
      </a>
      <a href="mailto:?subject=Invitation Brocker&body={{ urlencode('Salut ! Rejoins-moi sur Brocker pour trader. Mon lien : ' . $referralUrl) }}"
         class="share-btn" style="background:#64748b;color:#fff">
        Email
      </a>
    </div>
  </div>

  {{-- KPIs --}}
  <div class="kpi-grid">
    <div class="kpi-box">
      <div class="kpi-val">{{ $totalReferees }}</div>
      <div class="kpi-lbl">Total filleuls</div>
    </div>
    <div class="kpi-box">
      <div class="kpi-val" style="color:#16a34a">{{ $activeReferees }}</div>
      <div class="kpi-lbl">Filleuls actifs</div>
    </div>
    <div class="kpi-box">
      <div class="kpi-val" style="color:#ca8a04">{{ $pendingReferees }}</div>
      <div class="kpi-lbl">En attente</div>
    </div>
    <div class="kpi-box">
      <div class="kpi-val" style="color:#547A95">${{ number_format($totalCommissions, 2) }}</div>
      <div class="kpi-lbl">Commissions totales</div>
    </div>
    <div class="kpi-box border-2 border-green-200">
      <div class="kpi-val" style="color:#16a34a">${{ number_format($availableCommissions, 2) }}</div>
      <div class="kpi-lbl">Disponibles</div>
      <form method="POST" action="{{ route('referral.withdraw') }}" class="mt-3">
        @csrf
        <input type="hidden" name="amount" value="{{ $availableCommissions }}">
        <button type="submit" class="text-xs px-3 py-1 bg-green-600 text-white rounded-lg font-semibold" {{ $availableCommissions <= 0 ? 'disabled' : '' }}>
          Retirer →
        </button>
      </form>
    </div>
  </div>

  {{-- Graphique commissions + Tableau filleuls --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="ref-card">
      <h3 class="font-bold text-slate-900 mb-4">📊 Commissions mensuelles</h3>
      @if($commissionChart->count() > 0)
        <canvas id="commChart" height="140"></canvas>
      @else
        <div class="py-10 text-center text-slate-400 text-sm">Aucune donnée disponible</div>
      @endif
    </div>

    <div class="ref-card">
      <h3 class="font-bold text-slate-900 mb-4">👥 Vos filleuls</h3>
      <div class="overflow-x-auto">
        <table class="w-full ref-table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Date</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            @forelse($referees as $ref)
              <tr>
                <td class="font-semibold text-slate-900">
                  {{ substr($ref->first_name ?? 'U', 0, 1) }}***
                </td>
                <td class="text-slate-400">{{ $ref->created_at->format('d/m/Y') }}</td>
                <td>
                  <span class="badge {{ $ref->status === 'active' ? 'b-active' : ($ref->status === 'pending' ? 'b-pending' : 'b-inactive') }}">
                    {{ ucfirst($ref->status ?? 'pending') }}
                  </span>
                </td>
              </tr>
            @empty
              <tr><td colspan="3" class="py-8 text-center text-slate-400">Aucun filleul pour l'instant.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($referees->hasPages())
        <div class="mt-4">{{ $referees->links() }}</div>
      @endif
    </div>
  </div>

  {{-- Règles du programme --}}
  <div class="ref-card">
    <h3 class="font-bold text-slate-900 mb-4">📋 Règles du programme</h3>
    <div class="space-y-2" id="rulesAccordion">
      @foreach([
        ['Conditions d\'éligibilité', 'Votre filleul doit s\'inscrire via votre lien, compléter son KYC et effectuer son premier dépôt pour activer la commission.'],
        ['Taux de commission', 'Vous recevez 10% sur les frais de trading de votre filleul, crédités automatiquement après chaque trade clôturé.'],
        ['Paliers de bonus', 'Bronze (1-5 filleuls) : 10% | Silver (6-20 filleuls) : 15% | Gold (21+ filleuls) : 20%'],
        ['Durée de tracking', 'Le cookie de parrainage est valide 30 jours. Si votre filleul s\'inscrit dans ce délai, vous êtes crédité.'],
        ['Retrait des commissions', 'Minimum $10 pour retirer. Les commissions sont transférées directement dans votre portefeuille.'],
      ] as [$q, $a])
      <div class="border border-slate-200 rounded-lg overflow-hidden">
        <button onclick="this.nextElementSibling.classList.toggle('hidden');this.querySelector('span').textContent=this.querySelector('span').textContent==='+' ? '−' : '+'"
          class="w-full flex items-center justify-between px-4 py-3 font-semibold text-sm text-slate-800 hover:bg-slate-50 transition-colors">
          {{ $q }} <span>+</span>
        </button>
        <div class="hidden px-4 pb-4 text-sm text-slate-600">{{ $a }}</div>
      </div>
      @endforeach
    </div>
  </div>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script>
    // ── QR Code ────────────────────────────────────────────────────
    const canvas = document.getElementById('qrCanvas');
    if (canvas && typeof QRCode !== 'undefined') {
      QRCode.toCanvas(canvas, '{{ $referralUrl }}', { width: 120, margin: 1 });
    }

    function copyLink() {
      navigator.clipboard.writeText('{{ $referralUrl }}').then(() => {
        const btn = document.getElementById('copyBtn');
        btn.textContent = '✓ Copié !';
        btn.classList.add('copied');
        setTimeout(() => { btn.textContent = 'Copier'; btn.classList.remove('copied'); }, 2000);
      });
    }

    function downloadQR() {
      const a = document.createElement('a');
      a.download = 'mon-qr-parrainage.png';
      a.href = document.getElementById('qrCanvas').toDataURL();
      a.click();
    }

    // ── Commission chart ───────────────────────────────────────────
    @if($commissionChart->count() > 0)
    new Chart(document.getElementById('commChart')?.getContext('2d'), {
      type: 'bar',
      data: {
        labels: @json($commissionChart->pluck('month')),
        datasets: [{ data: @json($commissionChart->pluck('total')), label: 'Commissions ($)',
          backgroundColor: '#547A95', borderRadius: 6 }]
      },
      options: { responsive: true, plugins: { legend: { display: false } },
        scales: { y: { ticks: { callback: v => '$' + v }, grid: { color: '#f1f5f9' } } } }
    });
    @endif

    @if(session('success'))
    setTimeout(() => {
      const t = document.createElement('div');
      t.style.cssText = 'position:fixed;top:20px;right:20px;background:#16a34a;color:#fff;padding:14px 20px;border-radius:10px;font-size:14px;font-weight:600;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,.15)';
      t.textContent = '✓ {{ session("success") }}';
      document.body.appendChild(t);
      setTimeout(() => t.remove(), 4000);
    }, 100);
    @endif
  </script>
  @endpush

</x-layouts.dashboard>
