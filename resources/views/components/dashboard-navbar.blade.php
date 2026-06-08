<!-- Dashboard Navbar -->
<nav class="fixed top-0 left-0 right-0 z-40 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm">
  <div class="h-16 px-6 flex items-center justify-between">
    <!-- Left: Logo + Toggle Button -->
    <div class="flex items-center gap-4">
      <!-- Sidebar Toggle Button -->
      <button 
        id="sidebar-toggle" 
        class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors"
        onclick="toggleSidebar()"
      >
        <span class="material-symbols-outlined text-[24px]">menu</span>
      </button>
      
      <!-- Logo -->
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
          <span class="text-white font-bold text-sm">PF</span>
        </div>
        <span class="font-bold text-slate-900 dark:text-white hidden sm:inline">Purprime Fox</span>
      </div>
    </div>

    <!-- Center: Search (optional) -->
    <div class="hidden md:flex flex-1 max-w-md mx-8">
      <div class="w-full relative">
        <input 
          type="text" 
          placeholder="Rechercher..." 
          class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 material-symbols-outlined text-[20px]">search</span>
      </div>
    </div>

    <!-- Right: Notifications + Profile -->
    <div class="flex items-center gap-4">
      <!-- Notifications -->
      <button class="relative p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors">
        <span class="material-symbols-outlined text-[24px]">Notifications</span>
        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
      </button>

      <!-- Theme Toggle -->
      <button 
        class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors"
        onclick="toggleTheme()"
      >
        <span class="material-symbols-outlined text-[24px] dark:hidden">dark_mode</span>
        <span class="material-symbols-outlined text-[24px] hidden dark:block">light_mode</span>
      </button>

      <!-- Profile Dropdown -->
      <div class="relative group">
        <button class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-sm font-bold">
            {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
          </div>
          <span class="hidden sm:inline text-sm font-medium text-slate-900 dark:text-white">{{ auth()->user()->first_name }}</span>
          <span class="material-symbols-outlined text-[18px] text-slate-600 dark:text-slate-400">expand_more</span>
        </button>

        <!-- Dropdown Menu -->
        <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
          <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
            <span class="material-symbols-outlined text-[20px]">person</span>
            <span>Mon Profil</span>
          </a>
          <a href="{{ route('profile.security') }}" class="flex items-center gap-3 px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
            <span class="material-symbols-outlined text-[20px]">security</span>
            <span>Sécurité</span>
          </a>
          <hr class="border-slate-200 dark:border-slate-700">
          <form action="{{ route('auth.logout') }}" method="POST" class="block">
            @csrf
            <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
              <span class="material-symbols-outlined text-[20px]">logout</span>
              <span>Déconnexion</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</nav>

<script>
function toggleTheme() {
  const html = document.documentElement;
  if (html.classList.contains('dark')) {
    html.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  } else {
    html.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  }
}

function toggleSidebar() {
  const sidebar = document.getElementById('dashboard-sidebar');
  if (!sidebar) return; // Safety check - sidebar may not exist on all pages
  
  const isMobile = window.innerWidth < 1024;
  
  if (isMobile) {
    sidebar.classList.toggle('mobile-open');
  } else {
    const isCollapsed = sidebar.classList.contains('collapsed');
    if (isCollapsed) {
      sidebar.classList.remove('collapsed');
      localStorage.setItem('sidebar-collapsed', 'false');
    } else {
      sidebar.classList.add('collapsed');
      localStorage.setItem('sidebar-collapsed', 'true');
    }
  }
}

// Restore theme preference
document.addEventListener('DOMContentLoaded', function() {
  const theme = localStorage.getItem('theme') || 'light';
  const sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
  
  if (theme === 'dark') {
    document.documentElement.classList.add('dark');
  }
  
  if (sidebarCollapsed && window.innerWidth >= 1024) {
    document.getElementById('dashboard-sidebar')?.classList.add('collapsed');
  }
});
</script>
