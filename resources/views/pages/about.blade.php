<x-layouts.app>
  <x-slot name="title">À Propos</x-slot>

  <div class="max-w-4xl mx-auto py-16">
    <h1 class="text-4xl font-bold mb-6 text-slate-900 dark:text-white">À Propos de Moon Trade</h1>
    
    <div class="prose dark:prose-invert max-w-none">
      <p class="text-lg text-slate-700 dark:text-slate-300 mb-6">
        Moon Trade est une plateforme institutionnelle de gestion de richesses combinant la liquidité de qualité bancaire avec l'élégance architecturale moderne.
      </p>

      <h2 class="text-2xl font-bold mt-8 mb-4">Notre Mission</h2>
      <p class="text-slate-700 dark:text-slate-300 mb-6">
        Fournir aux traders professionnels et aux institutions une infrastructure de trading de haute performance avec une exécution zéro latence.
      </p>

      <h2 class="text-2xl font-bold mt-8 mb-4">Nos Valeurs</h2>
      <ul class="list-disc list-inside space-y-2 text-slate-700 dark:text-slate-300 mb-6">
        <li>Précision et fiabilité</li>
        <li>Innovation technologique</li>
        <li>Transparence totale</li>
        <li>Support client d'excellence</li>
      </ul>

      <h2 class="text-2xl font-bold mt-8 mb-4">Chiffres Clés</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 my-6">
        <div class="bg-slate-100 dark:bg-slate-800 p-4 rounded-lg text-center">
          <div class="text-3xl font-bold text-primary">$40B+</div>
          <div class="text-sm text-slate-600 dark:text-slate-400">Volume Mensuel</div>
        </div>
        <div class="bg-slate-100 dark:bg-slate-800 p-4 rounded-lg text-center">
          <div class="text-3xl font-bold text-primary">&lt;5ms</div>
          <div class="text-sm text-slate-600 dark:text-slate-400">Latence</div>
        </div>
        <div class="bg-slate-100 dark:bg-slate-800 p-4 rounded-lg text-center">
          <div class="text-3xl font-bold text-primary">99.99%</div>
          <div class="text-sm text-slate-600 dark:text-slate-400">Disponibilité</div>
        </div>
        <div class="bg-slate-100 dark:bg-slate-800 p-4 rounded-lg text-center">
          <div class="text-3xl font-bold text-primary">150+</div>
          <div class="text-sm text-slate-600 dark:text-slate-400">Partenaires</div>
        </div>
      </div>
    </div>

    <div class="mt-12">
      <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary-container transition-colors">
        ← Retour à l'accueil
      </a>
    </div>
  </div>
</x-layouts.app>
