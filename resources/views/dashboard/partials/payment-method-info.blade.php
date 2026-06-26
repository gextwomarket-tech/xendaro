{{-- Carte d'information sur le moyen de paiement sélectionné --}}
{{-- $prefix: 'deposit' ou 'withdraw' — utilisé pour générer des IDs uniques --}}
<div id="{{ $prefix }}PaymentInfo" class="hidden bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/10 border border-blue-200 dark:border-blue-800 rounded-xl p-4 space-y-3">

  <!-- En-tête -->
  <div class="flex items-center justify-between gap-2">
    <div class="flex items-center gap-2 min-w-0">
      <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-[20px] shrink-0">account_balance_wallet</span>
      <span id="{{ $prefix }}PayLabel" class="text-sm font-semibold text-slate-900 dark:text-white truncate">—</span>
    </div>
    <span id="{{ $prefix }}PayTypeBadge" class="shrink-0 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">—</span>
  </div>

  <!-- Instructions -->
  <div id="{{ $prefix }}PayInstructionsRow" class="hidden">
    <p id="{{ $prefix }}PayInstructions" class="text-sm text-blue-800 dark:text-blue-300 whitespace-pre-wrap"></p>
  </div>

  <!-- Adresse -->
  <div id="{{ $prefix }}PayAddressRow" class="hidden">
    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Adresse</label>
    <div class="flex items-center gap-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2">
      <span id="{{ $prefix }}PayAddressValue" class="flex-1 text-sm font-mono text-slate-800 dark:text-slate-200 break-all"></span>
      <button type="button"
        class="shrink-0 p-1.5 rounded-md text-slate-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors"
        onclick="copyToClipboard(document.getElementById('{{ $prefix }}PayAddressValue').textContent.trim(), this)"
        title="Copier l'adresse">
        <span class="material-symbols-outlined text-[18px]">content_copy</span>
      </button>
    </div>
  </div>

  <!-- Numéro de compte / téléphone -->
  <div id="{{ $prefix }}PayNumeroRow" class="hidden">
    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Numéro de compte</label>
    <div class="flex items-center gap-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2">
      <span id="{{ $prefix }}PayNumeroValue" class="flex-1 text-sm font-mono text-slate-800 dark:text-slate-200 break-all"></span>
      <button type="button"
        class="shrink-0 p-1.5 rounded-md text-slate-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors"
        onclick="copyToClipboard(document.getElementById('{{ $prefix }}PayNumeroValue').textContent.trim(), this)"
        title="Copier le numéro">
        <span class="material-symbols-outlined text-[18px]">content_copy</span>
      </button>
    </div>
  </div>

  <!-- QR code -->
  <div id="{{ $prefix }}PayQrRow" class="hidden flex flex-col items-center gap-2 py-1">
    <div id="{{ $prefix }}PayQrCanvas" class="bg-white p-2 rounded-lg border border-slate-200 dark:border-slate-700"></div>
    <p class="text-xs text-slate-500 dark:text-slate-400">Scannez le QR code</p>
  </div>

  <!-- Autres informations (details) -->
  <div id="{{ $prefix }}PayDetailsRow" class="hidden border-t border-blue-200/60 dark:border-blue-800/60 pt-2">
    <button type="button"
      class="w-full flex items-center justify-between text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors"
      onclick="togglePayDetails('{{ $prefix }}')">
      <span>Autres informations</span>
      <span class="material-symbols-outlined text-[18px]" id="{{ $prefix }}PayDetailsIcon">expand_more</span>
    </button>
    <ul id="{{ $prefix }}PayDetailsList" class="hidden mt-2 space-y-1 text-sm"></ul>
  </div>

</div>
