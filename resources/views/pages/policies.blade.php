<x-layouts.app>
  <x-slot name="title">Politiques</x-slot>

  <div class="max-w-4xl mx-auto py-16">
    <h1 class="text-4xl font-bold mb-2 text-slate-900 dark:text-white">Politiques Purprime Fox</h1>
    <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Dernière mise à jour: Mai 2026</p>
    
    <div class="prose dark:prose-invert max-w-none space-y-6">
      <div>
        <h2 class="text-2xl font-bold mt-0 mb-4">1. Politique de Remboursement</h2>
        <p class="text-slate-700 dark:text-slate-300">
          Les remboursements doivent être demandés dans un délai de 14 jours suivant votre achat. Contactez notre équipe d'assistance pour initier le processus.
        </p>
      </div>

      <div>
        <h2 class="text-2xl font-bold mt-0 mb-4">2. Politique de Réclamation</h2>
        <p class="text-slate-700 dark:text-slate-300">
          Toute réclamation concernant une transaction doit être déposée dans les 30 jours. Nous enquêterons sur votre réclamation et vous répondrons dans les 10 jours.
        </p>
      </div>

      <div>
        <h2 class="text-2xl font-bold mt-0 mb-4">3. Politique d'Annulation</h2>
        <p class="text-slate-700 dark:text-slate-300">
          Les abonnements peuvent être annulés à tout moment sans frais supplémentaires. L'accès cessera à la fin de la période de facturation actuelle.
        </p>
      </div>

      <div>
        <h2 class="text-2xl font-bold mt-0 mb-4">4. Politique Anti-Fraude</h2>
        <p class="text-slate-700 dark:text-slate-300">
          Nous avons mis en place des mesures de sécurité strictes pour prévenir la fraude. Toute activité suspecte sera immédiatement signalée aux autorités compétentes.
        </p>
      </div>

      <div>
        <h2 class="text-2xl font-bold mt-0 mb-4">5. Politique d'Accès</h2>
        <p class="text-slate-700 dark:text-slate-300">
          Purprime Fox se réserve le droit de suspendre ou de fermer les comptes qui violent nos conditions d'utilisation.
        </p>
      </div>
    </div>

    <div class="mt-12">
      <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary-container transition-colors">
        ← Retour à l'accueil
      </a>
    </div>
  </div>
</x-layouts.app>
