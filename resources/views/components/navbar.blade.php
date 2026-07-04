<!-- Dashboard Navbar -->
<nav class="fixed top-0 w-full z-50 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      
      <!-- Logo & Brand -->
      <div class="flex items-center gap-8">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
          <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path d="M6 4h12c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M9 9h6M9 13h4" stroke="white" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <span class="text-xl font-bold text-slate-900 dark:text-white">Xendaro Trade</span>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex gap-1">
          <a href="{{ route('dashboard') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-primary bg-slate-100 dark:bg-slate-800' : 'text-slate-600 dark:text-slate-400' }} rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-[18px] inline mr-2">dashboard</span>
            Dashboard
          </a>
          <a href="{{ route('wallet.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('wallet.*') ? 'text-primary bg-slate-100 dark:bg-slate-800' : 'text-slate-600 dark:text-slate-400' }} rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-[18px] inline mr-2">account_balance_wallet</span>
            Wallet
          </a>
          <a href="{{ route('markets.show') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('markets.*') ? 'text-primary bg-slate-100 dark:bg-slate-800' : 'text-slate-600 dark:text-slate-400' }} rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-[18px] inline mr-2">candlestick_chart</span>
            Markets
          </a>
          <a href="{{ route('trade.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('trade.*') ? 'text-primary bg-slate-100 dark:bg-slate-800' : 'text-slate-600 dark:text-slate-400' }} rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-[18px] inline mr-2">trending_up</span>
            Trade
          </a>
          <a href="{{ route('transactions.index') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('transactions.*') ? 'text-primary bg-slate-100 dark:bg-slate-800' : 'text-slate-600 dark:text-slate-400' }} rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-[18px] inline mr-2">history</span>
            History
          </a>
        </div>
      </div>

      <!-- Right Side: Notifications, Profile Dropdown -->
      <div class="flex items-center gap-4">
        
        <!-- Notifications -->
        <div class="relative group hidden sm:block">
          <button class="relative p-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>
        </div>

        <!-- Profile Dropdown -->
        <div class="relative group">
          <button class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <img src="https://via.placeholder.com/32" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full" />
            <span class="hidden md:block text-sm font-medium text-slate-900 dark:text-white">{{ auth()->user()->first_name }}</span>
            <span class="material-symbols-outlined text-slate-400">expand_more</span>
          </button>

          <!-- Dropdown Menu -->
          <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-t-lg">
              <span class="material-symbols-outlined text-[18px] inline mr-2">account_circle</span>
              Profile
            </a>
            <a href="{{ route('profile.security') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
              <span class="material-symbols-outlined text-[18px] inline mr-2">security</span>
              Security
            </a>
            <a href="{{ route('referral.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
              <span class="material-symbols-outlined text-[18px] inline mr-2">person_add</span>
              Referral
            </a>
            <hr class="border-slate-200 dark:border-slate-700 my-1" />
            <form method="POST" action="{{ route('auth.logout') }}" class="block">
              @csrf
              <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-b-lg">
                <span class="material-symbols-outlined text-[18px] inline mr-2">logout</span>
                Logout
              </button>
            </form>
          </div>
        </div>

        <!-- Mobile Menu Button -->
        <button class="md:hidden p-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
          <span class="material-symbols-outlined">menu</span>
        </button>
      </div>
    </div>
  </div>
</nav>
