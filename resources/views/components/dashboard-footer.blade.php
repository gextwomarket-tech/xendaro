<!-- Dashboard Footer Léger -->
<footer class="fixed bottom-0 left-0 right-0 h-20 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 z-30">
  <div class="h-full px-6 flex items-center justify-between">
    <!-- Left: Copyright -->
    <div class="text-xs text-slate-500 dark:text-slate-400">
      © 2024 Purprime Fox. Tous droits réservés.
    </div>

    <!-- Center: Quick Stats (optional) -->
    <div class="hidden md:flex items-center gap-8 text-xs">
      <div class="text-center">
        <div class="text-slate-500 dark:text-slate-400">Solde</div>
        <div class="font-semibold text-slate-900 dark:text-white">$12,450</div>
      </div>
      <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
      <div class="text-center">
        <div class="text-slate-500 dark:text-slate-400">Positions</div>
        <div class="font-semibold text-slate-900 dark:text-white">8 ouvertes</div>
      </div>
      <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
      <div class="text-center">
        <div class="text-slate-500 dark:text-slate-400">P&L</div>
        <div class="font-semibold text-green-600 dark:text-green-400">+$2,105</div>
      </div>
    </div>

    <!-- Right: Quick Links -->
    <div class="flex items-center gap-4">
      <a href="{{ route('faqs') }}" class="text-xs text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-1">
        <span class="material-symbols-outlined text-[16px]">help</span>
        <span class="hidden sm:inline">Aide</span>
      </a>
      <a href="{{ route('contact.post') }}" class="text-xs text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-1">
        <span class="material-symbols-outlined text-[16px]">mail</span>
        <span class="hidden sm:inline">Contact</span>
      </a>
      <a href="{{ route('home') }}" target="_blank" class="text-xs text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-1">
        <span class="material-symbols-outlined text-[16px]">language</span>
        <span class="hidden sm:inline">Site</span>
      </a>
    </div>
  </div>
</footer>

<!-- Spacer pour éviter que le contenu soit caché derrière le footer -->
<div class="h-20"></div>
