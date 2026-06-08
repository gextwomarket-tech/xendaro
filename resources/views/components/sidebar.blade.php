{{-- Dashboard Sidebar --}}
<aside id="sidebar" class="fixed top-0 left-0 h-full w-56 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 z-40 flex flex-col transition-transform duration-200 ease-in-out" style="padding-top: 52px;">

  {{-- Navigation principale --}}
  <nav class="flex-1 overflow-y-auto px-3 py-4">

    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 px-2 mb-2">Général</p>

    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
              {{ request()->routeIs('dashboard') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">dashboard</span>
      Dashboard
    </a>

    <a href="{{ route('wallet.index') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
              {{ request()->routeIs('wallet.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
      Wallet
    </a>

    <a href="{{ route('markets.show') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
              {{ request()->routeIs('markets.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">candlestick_chart</span>
      Markets
    </a>

    <a href="{{ route('trade.index') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
              {{ request()->routeIs('trade.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">trending_up</span>
      Trade
    </a>

    <a href="{{ route('transactions.index') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
              {{ request()->routeIs('transactions.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">history</span>
      Historique
    </a>

    <a href="{{ route('analytics.index') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
              {{ request()->routeIs('analytics.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">bar_chart_4_bars</span>
      Analytics
    </a>

    <div class="my-3 border-t border-slate-100 dark:border-slate-800"></div>

    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 px-2 mb-2">Compte</p>

    <a href="{{ route('profile.show') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
              {{ request()->routeIs('profile.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">account_circle</span>
      Profil
    </a>

    <a href="{{ route('referral.index') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
              {{ request()->routeIs('referral.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">person_add</span>
      Parrainage
    </a>

    <a href="{{ route('tickets.index') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
              {{ request()->routeIs('tickets.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">support_agent</span>
      Support
    </a>

  </nav>

  {{-- Profil utilisateur en bas --}}
  <div class="border-t border-slate-100 dark:border-slate-800 p-3">
    <div class="flex items-center gap-2 px-2 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer">
      <div class="w-7 h-7 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
        @if(auth()->user()->avatar)
          <img src="{{ auth()->user()->avatar }}" alt="" class="w-7 h-7 rounded-full object-cover">
        @else
          <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1)) }}</span>
        @endif
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
        <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
      </div>
    </div>
    <form method="POST" action="{{ route('auth.logout') }}" class="mt-1">
      @csrf
      <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
        <span class="material-symbols-outlined text-[20px]">logout</span>
        Déconnexion
      </button>
    </form>
  </div>

</aside>

{{-- Overlay mobile --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/30 z-30 hidden md:hidden" onclick="toggleSidebar()"></div>
