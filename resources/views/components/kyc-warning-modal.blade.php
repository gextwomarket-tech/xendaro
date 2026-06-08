<!-- KYC Warning Modal — popup informatif non-bloquant -->
@php
  $user = auth()->user();
  // Afficher si KYC non vérifié ET session flash présente (une seule fois par navigation)
  $showKycPopup = $user && $user->kyc_status !== 'verified' && session('kyc_popup');
@endphp

@if ($showKycPopup)
<div id="kyc-modal" class="fixed inset-0 z-50 overflow-y-auto">
  <!-- Backdrop -->
  <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeKycModal()"></div>

  <!-- Modal Content -->
  <div class="flex items-end sm:items-center justify-center min-h-screen px-4 pt-4 pb-20 sm:p-0">
    <div class="relative bg-white dark:bg-slate-800 rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-md">
      <!-- Close Button -->
      <button 
        onclick="closeKycModal()" 
        class="absolute top-4 right-4 text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-400"
      >
        <span class="material-symbols-outlined text-[24px]">close</span>
      </button>

      <!-- Modal Body -->
      <div class="px-4 py-6 sm:px-6">
        <!-- Icon -->
        <div class="flex justify-center mb-4">
          <div class="w-14 h-14 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
            <span class="material-symbols-outlined text-3xl text-amber-600 dark:text-amber-400">warning</span>
          </div>
        </div>

        <!-- Title -->
        <h3 class="text-center text-lg font-bold text-slate-900 dark:text-white mb-2">
          Vérification KYC Requise
        </h3>

        <!-- Description -->
        <p class="text-center text-sm text-slate-600 dark:text-slate-400 mb-4">
          Vous devez compléter la vérification de votre identité pour accéder à cette fonctionnalité.
        </p>

        <!-- Timeline -->
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3 mb-6">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-[20px]">schedule</span>
            <div>
              <p class="text-xs text-amber-700 dark:text-amber-300 font-semibold">Délai de vérification:</p>
              <p class="text-sm text-amber-900 dark:text-amber-200 font-bold">1 à 24 heures</p>
            </div>
          </div>
        </div>

        <!-- What's included -->
        <div class="mb-6">
          <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Vous aurez besoin de:</p>
          <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
            <li class="flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px] text-blue-500">check_circle</span>
              Pièce d'identité valide
            </li>
            <li class="flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px] text-blue-500">check_circle</span>
              Selfie avec votre pièce d'identité
            </li>
            <li class="flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px] text-blue-500">check_circle</span>
              Informations personnelles
            </li>
          </ul>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3">
          <button 
            onclick="closeKycModal()" 
            class="flex-1 px-4 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
          >
            Annuler
          </button>
          <a 
            href="{{ route('kyc.show') }}" 
            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2"
          >
            <span class="material-symbols-outlined text-[20px]">verified_user</span>
            Vérifier maintenant
          </a>
        </div>

        <!-- Footer note -->
        <p class="text-xs text-slate-500 dark:text-slate-500 text-center mt-4">
          Vos données sont sécurisées et conformes aux normes KYC.
        </p>
      </div>
    </div>
  </div>
</div>

<script>
  function closeKycModal() {
    const m = document.getElementById('kyc-modal');
    if (m) { m.style.opacity = '0'; m.style.transition = 'opacity .25s'; setTimeout(() => m.remove(), 260); }
    document.body.style.overflow = 'auto';
  }

  // Fermer avec Escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeKycModal();
  });

  // Afficher automatiquement au chargement (popup non-bloquant)
  document.addEventListener('DOMContentLoaded', function() {
    const m = document.getElementById('kyc-modal');
    if (m) {
      m.style.opacity = '0';
      m.style.transition = 'opacity .3s';
      document.body.style.overflow = 'hidden';
      requestAnimationFrame(() => { m.style.opacity = '1'; });
      // Fermeture auto après 12s si l'utilisateur ne fait rien
      setTimeout(closeKycModal, 12000);
    }
  });
</script>
@endif
