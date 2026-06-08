<x-layouts.app>
  <x-slot name="title">Conditions de Trading</x-slot>

  <div class="max-w-4xl mx-auto py-16">
    <h1 class="text-4xl font-bold mb-2 text-slate-900 dark:text-white">Conditions de Trading</h1>
    <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Dernière mise à jour: Mai 2026</p>
    
    <div class="prose dark:prose-invert max-w-none space-y-6">
      <div>
        <h2 class="text-2xl font-bold mt-0 mb-4">1. Exécution des Ordres</h2>
        <p class="text-slate-700 dark:text-slate-300">
          Moon Trade exécute les ordres au meilleur prix disponible selon nos procédures de gestion des ordres. Nous nous efforçons de fournir une exécution de haute qualité avec la latence la plus faible.
        </p>
      </div>

      <div>
        <h2 class="text-2xl font-bold mt-0 mb-4">2. Niveaux de Spread</h2>
        <p class="text-slate-700 dark:text-slate-300">
          Les spreads varient en fonction des conditions du marché. Nos spreads typiques sont parmi les plus compétitifs de l'industrie.
        </p>
      </div>

      <div>
        <h2 class="text-2xl font-bold mt-0 mb-4">3. Levier et Marge</h2>
        <p class="text-slate-700 dark:text-slate-300">
          Le trading sur marge comporte un risque élevé. Vous êtes entièrement responsable de la gestion de votre compte et du respect des exigences de marge.
        </p>
      </div>

      <div>
        <h2 class="text-2xl font-bold mt-0 mb-4">4. Risques du Trading</h2>
        <p class="text-slate-700 dark:text-slate-300">
          Le trading sur les marchés financiers comporte un risque substantiel de perte. Vous devez comprendre les risques avant de trader.
        </p>
      </div>

      <div>
        <h2 class="text-2xl font-bold mt-0 mb-4">5. Stop-Loss et Take-Profit</h2>
        <p class="text-slate-700 dark:text-slate-300">
          Les ordres stop-loss et take-profit ne garantissent pas une exécution au prix spécifié. Les conditions du marché peuvent faire exécuter votre ordre à un prix différent.
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
