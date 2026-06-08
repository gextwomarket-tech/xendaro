<x-layouts.dashboard>
  <x-slot name="title">Support & Tickets</x-slot>
  <x-slot name="subtitle">Besoin d'aide ? Notre équipe est là pour vous.</x-slot>

  @push('styles')
  <style>
    /* Chat */
    .chat-bubble-user  { background:#2563eb; color:#fff; border-radius:18px 18px 4px 18px; }
    .chat-bubble-agent { background:#f1f5f9; color:#1e293b; border-radius:18px 18px 18px 4px; }
    #chatMessages::-webkit-scrollbar { width:4px; }
    #chatMessages::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:2px; }
    @keyframes spin-refresh { to { transform:rotate(360deg); } }
    .spinning { animation:spin-refresh .6s linear infinite; display:inline-block; }

    /* Tabs */
    .tab-nav { display:flex; gap:4px; background:#f1f5f9; border-radius:10px; padding:4px; margin-bottom:24px; }
    .tab-btn { flex:1; padding:8px 16px; border-radius:7px; font-size:13px; font-weight:600; color:#64748b; background:transparent; border:none; cursor:pointer; transition:all .15s; text-align:center; }
    .tab-btn.active { background:#fff; color:#0f172a; box-shadow:0 1px 4px rgba(0,0,0,.08); }
    .tab-pane { display:none; }
    .tab-pane.active { display:block; }

    /* Tickets */
    .ticket-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:16px 20px; transition:box-shadow .2s; display:flex; align-items:center; gap:16px; }
    .ticket-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.07); }
    .badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:99px; font-size:11px; font-weight:700; }
    .b-open    { background:#dcfce7; color:#16a34a; }
    .b-closed  { background:#f1f5f9; color:#94a3b8; }
    .b-pending { background:#fef9c3; color:#ca8a04; }
    .b-high    { background:#fee2e2; color:#dc2626; }
    .b-medium  { background:#fef9c3; color:#ca8a04; }
    .b-low     { background:#f1f5f9; color:#64748b; }

    /* Form */
    .form-label { display:block; font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.04em; margin-bottom:6px; }
    .form-input { width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#0f172a; outline:none; transition:border-color .2s; }
    .form-input:focus { border-color:#547A95; box-shadow:0 0 0 3px rgba(84,122,149,.1); }
    .btn-primary { padding:10px 24px; background:#0f172a; color:#fff; border-radius:8px; font-size:13px; font-weight:600; border:none; cursor:pointer; }
    .btn-primary:hover { background:#1e293b; }

    /* FAQ accordion */
    .faq-item { border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; margin-bottom:8px; }
    .faq-q { width:100%; text-align:left; padding:14px 16px; font-size:14px; font-weight:600; color:#0f172a; background:#fff; border:none; cursor:pointer; display:flex; justify-content:space-between; align-items:center; }
    .faq-q:hover { background:#f8fafc; }
    .faq-a { display:none; padding:12px 16px 16px; font-size:13px; color:#64748b; line-height:1.6; border-top:1px solid #f1f5f9; }
    .faq-a.open { display:block; }
  </style>
  @endpush

  @isset($ticket)
  {{-- ══════════════════ VUE CHAT DU TICKET ══════════════════ --}}
  <div class="mb-4">
    <a href="{{ route('tickets.index') }}" class="inline-flex items-center gap-1 text-slate-500 hover:text-slate-800 text-sm font-semibold transition-colors">
      <span class="material-symbols-outlined text-base">arrow_back</span> Retour aux tickets
    </a>
  </div>

  <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    {{-- En-tête ticket --}}
    <div class="px-6 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
      <div>
        <div class="flex flex-wrap items-center gap-2">
          <h2 class="font-bold text-slate-900 text-lg">#{{ $ticket->id }} — {{ $ticket->subject }}</h2>
          <span class="badge {{ $ticket->status === 'open' ? 'b-open' : ($ticket->status === 'pending' ? 'b-pending' : 'b-closed') }}">
            {{ ucfirst($ticket->status ?? 'open') }}
          </span>
          @if($ticket->priority ?? false)
            <span class="badge {{ $ticket->priority === 'high' ? 'b-high' : ($ticket->priority === 'medium' ? 'b-medium' : 'b-low') }}">
              {{ ucfirst($ticket->priority) }}
            </span>
          @endif
        </div>
        <p class="text-xs text-slate-400 mt-1">
          Catégorie : {{ $ticket->category ?? 'Général' }} &nbsp;·&nbsp;
          Créé le {{ $ticket->created_at?->format('d/m/Y H:i') }}
          @if($ticket->last_replied_at)
            &nbsp;·&nbsp; Dernière réponse {{ $ticket->last_replied_at->diffForHumans() }}
          @endif
        </p>
      </div>
      <div class="flex gap-2 flex-wrap">
        <button id="refreshBtn" onclick="refreshChat()" class="inline-flex items-center gap-1 px-3 py-2 rounded-lg border border-slate-200 text-slate-500 text-sm font-semibold hover:bg-slate-50 transition-colors">
          <span class="material-symbols-outlined text-base" id="refreshIcon">refresh</span>
          Actualiser
        </button>
        @if($ticket->status !== 'closed')
          <form action="{{ route('tickets.close', $ticket->id) }}" method="POST" class="inline">
            @csrf @method('PUT')
            <button class="inline-flex items-center gap-1 px-3 py-2 rounded-lg border border-slate-200 text-slate-500 text-sm font-semibold hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors">
              <span class="material-symbols-outlined text-base">check_circle</span>
              Fermer
            </button>
          </form>
        @else
          <form action="{{ route('tickets.reopen', $ticket->id) }}" method="POST" class="inline">
            @csrf @method('PUT')
            <button class="inline-flex items-center gap-1 px-3 py-2 rounded-lg border border-green-200 text-green-600 text-sm font-semibold hover:bg-green-50 transition-colors">
              <span class="material-symbols-outlined text-base">restart_alt</span>
              Réouvrir
            </button>
          </form>
        @endif
      </div>
    </div>

    {{-- Zone chat --}}
    <div id="chatMessages" class="px-6 py-6 space-y-5" style="min-height:320px;max-height:520px;overflow-y:auto;">
      @forelse($replies ?? [] as $reply)
        <div class="flex {{ $reply->is_admin_reply ? 'justify-start' : 'justify-end' }} items-end gap-3">
          @if($reply->is_admin_reply)
            <div class="w-8 h-8 rounded-full bg-blue-100 flex-shrink-0 flex items-center justify-center">
              <span class="material-symbols-outlined text-blue-600 text-sm">support_agent</span>
            </div>
          @endif
          <div class="max-w-[72%]">
            <div class="px-4 py-3 text-sm whitespace-pre-wrap break-words leading-relaxed
                        {{ $reply->is_admin_reply ? 'chat-bubble-agent' : 'chat-bubble-user' }}">
              {{ $reply->message }}
            </div>
            <p class="text-xs text-slate-400 mt-1 {{ $reply->is_admin_reply ? 'text-left' : 'text-right' }}">
              {{ $reply->is_admin_reply ? 'Support' : 'Vous' }} · {{ $reply->created_at?->diffForHumans() }}
            </p>
          </div>
          @if(!$reply->is_admin_reply)
            <div class="w-8 h-8 rounded-full bg-blue-600 flex-shrink-0 flex items-center justify-center">
              <span class="text-white text-xs font-bold">
                {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name ?? 'U', 0, 1)) }}
              </span>
            </div>
          @endif
        </div>
      @empty
        <div class="flex flex-col items-center justify-center py-10 text-slate-400">
          <span class="material-symbols-outlined text-4xl mb-2">chat_bubble_outline</span>
          <p class="text-sm">Aucun message. Commencez la conversation ci-dessous.</p>
        </div>
      @endforelse
    </div>

    {{-- Zone réponse --}}
    @if(($ticket->status ?? '') !== 'closed')
      <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
        @if(session('success'))
          <div class="mb-3 px-3 py-2 bg-green-50 border border-green-200 rounded-lg text-green-700 text-xs font-semibold">
            ✓ {{ session('success') }}
          </div>
        @endif
        <form action="{{ route('tickets.reply', $ticket->id) }}" method="POST" id="replyForm">
          @csrf
          <div class="flex gap-3 items-end">
            <div class="flex-1">
              <textarea name="message" id="replyInput" rows="2" required minlength="5"
                        class="form-input resize-none text-sm"
                        placeholder="Écrivez votre message… (Ctrl+Entrée pour envoyer)"
                        onkeydown="if(event.key==='Enter'&&(event.ctrlKey||event.metaKey)){event.preventDefault();document.getElementById('replyForm').submit();}">{{ old('message') }}</textarea>
              @error('message')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn-primary h-10 px-4 flex items-center gap-1 text-sm whitespace-nowrap">
              <span class="material-symbols-outlined text-base">send</span>
              Envoyer
            </button>
          </div>
        </form>
      </div>
    @else
      <div class="border-t border-slate-100 bg-slate-50 px-6 py-4 text-center text-slate-500 text-sm">
        <span class="material-symbols-outlined align-middle text-base mr-1">lock</span>
        Ticket fermé — réouvrez-le pour continuer la discussion.
      </div>
    @endif
  </div>

  @push('scripts')
  <script>
    // Auto-scroll to bottom
    (function() {
      const c = document.getElementById('chatMessages');
      if (c) c.scrollTop = c.scrollHeight;
    })();

    // Refresh with animation
    function refreshChat() {
      const icon = document.getElementById('refreshIcon');
      const btn  = document.getElementById('refreshBtn');
      if (icon) icon.classList.add('spinning');
      if (btn)  btn.disabled = true;
      setTimeout(() => window.location.reload(), 500);
    }

    // Auto-refresh every 20s if ticket is open (smart diff)
    @if(($ticket->status ?? '') === 'open')
    let _lastCount = {{ count($replies ?? []) }};
    setInterval(function() {
      fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
        .then(r => r.text())
        .then(html => {
          const doc  = (new DOMParser()).parseFromString(html, 'text/html');
          const newC = doc.getElementById('chatMessages');
          const oldC = document.getElementById('chatMessages');
          if (!newC || !oldC) return;
          const msgs = newC.querySelectorAll('.max-w-\\[72\\%\\]').length;
          if (msgs > _lastCount) {
            _lastCount = msgs;
            const wasBottom = oldC.scrollHeight - oldC.scrollTop <= oldC.clientHeight + 40;
            oldC.innerHTML  = newC.innerHTML;
            if (wasBottom) oldC.scrollTop = oldC.scrollHeight;
          }
        }).catch(() => {});
    }, 20000);
    @endif
  </script>
  @endpush

  @else
  {{-- ══════════════════ VUE LISTE & FORMULAIRE ══════════════════ --}}

  {{-- Tabs --}}
  <div class="tab-nav">
    <button class="tab-btn active" onclick="switchTab('tickets', this)">🎫 Mes Tickets</button>
    <button class="tab-btn" onclick="switchTab('new', this)">✏️ Nouveau Ticket</button>
    <button class="tab-btn" onclick="switchTab('faq', this)">❓ FAQ</button>
  </div>

  {{-- ══ MES TICKETS ══ --}}
  <div id="tab-tickets" class="tab-pane active">
    @if(session('success'))
      <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-semibold">
        ✓ {{ session('success') }}
      </div>
    @endif

    @if($tickets->count() > 0)
      <div class="space-y-3">
        @foreach($tickets as $ticket)
          <a href="{{ route('tickets.show', $ticket->id) }}" class="ticket-card block hover:no-underline">
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 flex-wrap mb-1">
                <span class="font-semibold text-slate-900 truncate">#{{ $ticket->id }} — {{ $ticket->subject }}</span>
                <span class="badge {{ $ticket->status === 'open' ? 'b-open' : ($ticket->status === 'pending' ? 'b-pending' : 'b-closed') }}">
                  {{ ucfirst($ticket->status ?? 'open') }}
                </span>
                @if($ticket->priority ?? false)
                  <span class="badge {{ $ticket->priority === 'high' ? 'b-high' : ($ticket->priority === 'medium' ? 'b-medium' : 'b-low') }}">
                    {{ ucfirst($ticket->priority) }}
                  </span>
                @endif
              </div>
              <div class="text-xs text-slate-400 flex gap-4 flex-wrap">
                <span>{{ $ticket->category ?? 'Général' }}</span>
                <span>Créé le {{ $ticket->created_at?->format('d/m/Y H:i') }}</span>
                @if($ticket->updated_at != $ticket->created_at)
                  <span>Mis à jour {{ $ticket->updated_at?->diffForHumans() }}</span>
                @endif
              </div>
            </div>
            <span class="material-symbols-outlined text-slate-300">chevron_right</span>
          </a>
        @endforeach
      </div>
      @if($tickets->hasPages())
        <div class="mt-4">{{ $tickets->links() }}</div>
      @endif
    @else
      <div class="text-center py-16">
        <span class="material-symbols-outlined text-5xl text-slate-300 block mb-3">support_agent</span>
        <p class="text-slate-500 font-medium">Aucun ticket pour l'instant.</p>
        <p class="text-slate-400 text-sm mb-4">Cliquez sur "Nouveau Ticket" pour nous contacter.</p>
        <button onclick="switchTab('new', document.querySelectorAll('.tab-btn')[1])" class="btn-primary text-sm">
          ✏️ Créer un ticket
        </button>
      </div>
    @endif
  </div>

  {{-- ══ NOUVEAU TICKET ══ --}}
  <div id="tab-new" class="tab-pane">
    <div class="bg-white border border-slate-200 rounded-2xl p-8 max-w-2xl">
      <h3 class="font-bold text-slate-900 text-lg mb-6">Créer un nouveau ticket</h3>
      <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
          <div>
            <label class="form-label">Sujet <span class="text-red-500">*</span></label>
            <input name="subject" type="text" class="form-input" placeholder="Décrivez votre problème en une ligne…" required value="{{ old('subject') }}">
            @error('subject') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="form-label">Catégorie <span class="text-red-500">*</span></label>
              <select name="category" class="form-input" required>
                <option value="">Choisir…</option>
                <option value="technique" {{ old('category') === 'technique' ? 'selected' : '' }}>Technique</option>
                <option value="financier" {{ old('category') === 'financier' ? 'selected' : '' }}>Financier</option>
                <option value="kyc"       {{ old('category') === 'kyc' ? 'selected' : '' }}>KYC / Vérification</option>
                <option value="trading"   {{ old('category') === 'trading' ? 'selected' : '' }}>Trading</option>
                <option value="autre"     {{ old('category') === 'autre' ? 'selected' : '' }}>Autre</option>
              </select>
            </div>
            <div>
              <label class="form-label">Priorité</label>
              <select name="priority" class="form-input">
                <option value="low">Faible</option>
                <option value="medium" selected>Normale</option>
                <option value="high">Haute</option>
              </select>
            </div>
          </div>
          <div>
            <label class="form-label">Description <span class="text-red-500">*</span></label>
            <textarea name="message" rows="5" class="form-input" required
                      placeholder="Décrivez votre problème en détail. Plus c'est précis, plus nous pouvons vous aider rapidement.">{{ old('message') }}</textarea>
            @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>
        <div class="flex gap-3 mt-6">
          <button type="submit" class="btn-primary">📤 Soumettre le ticket</button>
          <button type="reset" class="px-5 py-2 border border-slate-200 rounded-lg text-slate-600 text-sm font-semibold hover:bg-slate-50">
            Réinitialiser
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- ══ FAQ ══ --}}
  <div id="tab-faq" class="tab-pane">
    <div class="max-w-3xl">
      <div class="mb-4">
        <input type="text" id="faqSearch" onkeyup="filterFaq()" placeholder="🔍 Rechercher dans la FAQ…"
               class="form-input" style="max-width:400px">
      </div>

      <div id="faqList">
        @foreach([
          ['Dépôt & Retrait', [
            ['Comment déposer des fonds ?', 'Rendez-vous dans la section Portefeuille, choisissez votre méthode de dépôt (carte, virement, crypto) et suivez les instructions. Le délai de traitement varie selon la méthode (instantané pour crypto, 1-3 jours pour virement).'],
            ['Quel est le montant minimum de retrait ?', 'Le retrait minimum est de $10. Les retraits sont traités sous 24-48h ouvrées après validation.'],
            ['Mes fonds sont-ils en sécurité ?', 'Oui. Vos fonds sont ségrégués dans des comptes bancaires dédiés. Nous sommes régulés et audités régulièrement.'],
          ]],
          ['Trading', [
            ['Qu\'est-ce qu\'un lot en trading ?', 'Un lot standard représente 100 000 unités de la devise de base. Vous pouvez trader en mini-lots (0.1) ou micro-lots (0.01) pour des positions plus petites.'],
            ['Comment fonctionne le compte démo ?', 'Le compte démo dispose de $10 000 virtuels. Il simule les conditions réelles du marché sans risque financier. Idéal pour apprendre avant de trader en réel.'],
            ['Qu\'est-ce que le margin call ?', 'Un margin call survient quand votre marge libre descend sous le niveau requis. La plateforme fermera automatiquement vos positions pour protéger votre solde.'],
          ]],
          ['KYC & Sécurité', [
            ['Pourquoi dois-je vérifier mon identité ?', 'La vérification KYC est obligatoire pour se conformer aux réglementations anti-blanchiment (AML) et pour protéger nos utilisateurs.'],
            ['Quels documents sont acceptés ?', 'Carte nationale d\'identité, passeport ou permis de conduire en cours de validité. Les documents doivent être lisibles et non expirés.'],
            ['Comment activer la 2FA ?', 'Dans votre profil, section Sécurité, cliquez sur "Activer 2FA". Scannez le QR code avec Google Authenticator ou Authy et entrez le code de confirmation.'],
          ]],
          ['Compte & Technique', [
            ['J\'ai oublié mon mot de passe, que faire ?', 'Utilisez le lien "Mot de passe oublié" sur la page de connexion. Un email de réinitialisation sera envoyé à votre adresse enregistrée.'],
            ['Comment changer mon email ?', 'Contactez notre support via un ticket. Le changement d\'email nécessite une vérification d\'identité supplémentaire.'],
            ['L\'application ne fonctionne pas, que faire ?', 'Videz le cache de votre navigateur, désactivez les extensions, puis réessayez. Si le problème persiste, créez un ticket en précisant votre navigateur et système d\'exploitation.'],
          ]],
        ] as [$cat, $questions])
        <div class="faq-category mb-5">
          <h3 class="font-bold text-slate-700 text-sm uppercase tracking-wider mb-3">{{ $cat }}</h3>
          @foreach($questions as [$q, $a])
          <div class="faq-item faq-entry">
            <button class="faq-q" onclick="toggleFaq(this)">
              <span class="faq-text">{{ $q }}</span>
              <span class="material-symbols-outlined text-slate-400">expand_more</span>
            </button>
            <div class="faq-a">{{ $a }}</div>
          </div>
          @endforeach
        </div>
        @endforeach
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    function switchTab(name, btn) {
      document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.getElementById('tab-' + name)?.classList.add('active');
      btn?.classList.add('active');
    }

    function toggleFaq(btn) {
      const answer = btn.nextElementSibling;
      const icon   = btn.querySelector('.material-symbols-outlined');
      answer.classList.toggle('open');
      icon.textContent = answer.classList.contains('open') ? 'expand_less' : 'expand_more';
    }

    function filterFaq() {
      const q = document.getElementById('faqSearch').value.toLowerCase();
      document.querySelectorAll('.faq-entry').forEach(item => {
        const text = item.querySelector('.faq-text')?.textContent.toLowerCase() ?? '';
        item.style.display = text.includes(q) ? '' : 'none';
      });
    }

    // Ouvrir tab "Nouveau" si erreurs de validation
    @if($errors->any())
    switchTab('new', document.querySelectorAll('.tab-btn')[1]);
    @endif
  </script>
  @endpush

  @endisset {{-- fin @isset($ticket) --}}

</x-layouts.dashboard>
