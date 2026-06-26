<!-- Dashboard Sidebar -->
<aside 
  id="dashboard-sidebar"
  class="fixed left-0 top-16 bottom-20 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 overflow-y-auto transition-all duration-300 z-30 collapsed:w-20"
>
  <!-- Sidebar Content -->
  <div class="p-4 space-y-2">
    
    <!-- Dashboard Section -->
    <div class="mb-6">
      <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-3 block mb-3 collapsed:hidden">Menu Principal</span>

      <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors group">
        <span class="material-symbols-outlined text-[24px] flex-shrink-0 group-hover:scale-110 transition-transform">dashboard</span>
        <span class="text-sm font-medium whitespace-nowrap collapsed:hidden">Dashboard</span>
      </a>

      {{-- Badge statut KYC sous le lien Dashboard --}}
      @php $kycStatus = auth()->user()?->kyc_status; @endphp
      @if($kycStatus !== 'verified')
        <a href="{{ route('kyc.show') }}" class="collapsed:hidden mx-3 mt-1 mb-2 flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition-colors
          {{ $kycStatus === 'pending'
              ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800'
              : 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800' }}">
          <span class="material-symbols-outlined text-[16px]">{{ $kycStatus === 'pending' ? 'schedule' : 'warning' }}</span>
          <span>{{ $kycStatus === 'pending' ? 'KYC en attente' : 'KYC requis' }}</span>
          <span class="ml-auto w-2 h-2 rounded-full {{ $kycStatus === 'pending' ? 'bg-amber-500' : 'bg-red-500' }} animate-pulse"></span>
        </a>
      @endif

      <!-- Notifications -->
      <a
        href="{{ route('notifications.index') }}"
        id="sidebarNotifBtn"
        class="w-full flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('notifications.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors group"
      >
        <span class="relative flex-shrink-0">
          <span class="material-symbols-outlined text-[24px] group-hover:scale-110 transition-transform block">notifications</span>
          <span id="sidebarNotifDot" class="hidden absolute -top-0.5 -right-0.5 w-2 h-2 bg-red-500 rounded-full"></span>
        </span>
        <span class="text-sm font-medium whitespace-nowrap collapsed:hidden flex items-center gap-2">
          Notifications
          <span id="sidebarNotifCount" class="hidden bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">0</span>
        </span>
      </a>
    </div>

    <!-- Trading Section -->
    <div class="mb-6">
      <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-3 block mb-3 collapsed:hidden">Trading</span>
      
      <a href="{{ route('trade.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('trade.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors group">
        <span class="material-symbols-outlined text-[24px] flex-shrink-0 group-hover:scale-110 transition-transform">candlestick_chart</span>
        <span class="text-sm font-medium whitespace-nowrap collapsed:hidden">Trade</span>
      </a>

      <a href="{{ route('trades.history') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('trades.history') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors group">
        <span class="material-symbols-outlined text-[24px] flex-shrink-0 group-hover:scale-110 transition-transform">history</span>
        <span class="text-sm font-medium whitespace-nowrap collapsed:hidden">Historique</span>
      </a>

      <a href="{{ route('markets.show') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('markets.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors group">
        <span class="material-symbols-outlined text-[24px] flex-shrink-0 group-hover:scale-110 transition-transform">show_chart</span>
        <span class="text-sm font-medium whitespace-nowrap collapsed:hidden">Marchés</span>
      </a>
    </div>

    <!-- Wallet Section -->
    <div class="mb-6">
      <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-3 block mb-3 collapsed:hidden">Finances</span>
      
      <a href="{{ route('wallet.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('wallet.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors group">
        <span class="material-symbols-outlined text-[24px] flex-shrink-0 group-hover:scale-110 transition-transform">wallet</span>
        <span class="text-sm font-medium whitespace-nowrap collapsed:hidden">Portefeuille</span>
      </a>

      <a href="{{ route('referral.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('referral.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors group">
        <span class="material-symbols-outlined text-[24px] flex-shrink-0 group-hover:scale-110 transition-transform">people</span>
        <span class="text-sm font-medium whitespace-nowrap collapsed:hidden">Parrainages</span>
      </a>
    </div>

    <!-- Account Section -->
    <div class="mb-6">
      <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-3 block mb-3 collapsed:hidden">Compte</span>
      
      <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('profile.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors group">
        <span class="material-symbols-outlined text-[24px] flex-shrink-0 group-hover:scale-110 transition-transform">person</span>
        <span class="text-sm font-medium whitespace-nowrap collapsed:hidden">Profil</span>
      </a>

      <a href="{{ route('tickets.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('tickets.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors group">
        <span class="material-symbols-outlined text-[24px] flex-shrink-0 group-hover:scale-110 transition-transform">support_agent</span>
        <span class="text-sm font-medium whitespace-nowrap collapsed:hidden">Support</span>
      </a>

      <a href="{{ route('analytics.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg {{ request()->routeIs('analytics.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors group">
        <span class="material-symbols-outlined text-[24px] flex-shrink-0 group-hover:scale-110 transition-transform">analytics</span>
        <span class="text-sm font-medium whitespace-nowrap collapsed:hidden">Analytiques</span>
      </a>
    </div>
  </div>
</aside>

<style>
  #dashboard-sidebar.collapsed {
    width: 80px;
  }
  
  #dashboard-sidebar.collapsed .collapsed\:hidden {
    display: none;
  }
</style>
