{{-- Dashboard Navbar — Full Responsive --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<nav class="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm">
  <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-3">

    {{-- ── LEFT : Toggle + Logo ──────────────────────────────────── --}}
    <div class="flex items-center gap-3 shrink-0">
      <button
        id="sidebar-toggle"
        onclick="toggleSidebar()"
        class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors"
        aria-label="Menu"
      >
        <span class="material-symbols-outlined text-[24px]">menu</span>
      </button>

      <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-sm">
          <span class="text-white font-bold text-sm">PF</span>
        </div>
        <span class="font-bold text-slate-900 dark:text-white hidden sm:inline text-sm">Purprime Fox</span>
      </a>
    </div>

    {{-- ── CENTER : Search (md+) ──────────────────────────────────── --}}
    <div class="hidden md:flex flex-1 max-w-xs lg:max-w-md mx-4">
      <div class="w-full relative">
        <input
          type="text"
          placeholder="Rechercher…"
          class="w-full pl-4 pr-10 py-2 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
        />
        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined text-[18px]">search</span>
      </div>
    </div>

    {{-- ── RIGHT : Actions ──────────────────────────────────────── --}}
    <div class="flex items-center gap-1 sm:gap-2 shrink-0">

      {{-- KYC Badge (md+) --}}
      <div class="hidden md:block">
        <x-kyc-reminder-badge />
      </div>

      {{-- Notifications --}}
      <div class="relative" id="notifWrapper">
        <a
          href="{{ route('notifications.index') }}"
          id="notifBtn"
          class="relative p-2 rounded-lg {{ request()->routeIs('notifications.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400' }} transition-colors"
          aria-label="Notifications"
        >
          <span class="material-symbols-outlined text-[24px]">notifications</span>
          <span id="notifBadge" class="hidden absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </a>

        {{-- Panel — adaptatif mobile/desktop --}}
        <div
          id="notifPanel"
          role="dialog"
          aria-label="Notifications"
          class="hidden"
          style="position:fixed; z-index:999;"
        >
          {{-- Backdrop mobile --}}
          <div
            id="notifBackdrop"
            class="md:hidden fixed inset-0 bg-black/40 backdrop-blur-sm"
            onclick="closeNotifPanel()"
          ></div>

          {{-- Content --}}
          <div
            id="notifContent"
            class="
              fixed bottom-0 left-0 right-0 max-h-[85vh]
              md:absolute md:bottom-auto md:left-auto md:right-0 md:top-full md:mt-2 md:max-h-[520px]
              bg-white dark:bg-slate-900
              border-t md:border border-slate-200 dark:border-slate-700
              rounded-t-2xl md:rounded-xl
              shadow-2xl
              flex flex-col overflow-hidden
              transition-transform duration-300
            "
            style="
              width: 100%;
              max-width: 100%;
            "
          >
            {{-- Handle mobile --}}
            <div class="md:hidden flex justify-center pt-3 pb-1">
              <div class="w-10 h-1 bg-slate-300 dark:bg-slate-600 rounded-full"></div>
            </div>

            {{-- Header --}}
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 shrink-0">
              <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-[18px] text-blue-500">notifications</span>
                Notifications
                <span id="notifUnreadChip" class="hidden bg-blue-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none"></span>
              </h3>
              <div class="flex items-center gap-2">
                <button onclick="markAllNotifRead()" class="text-xs text-blue-600 dark:text-blue-400 hover:underline whitespace-nowrap font-medium">
                  Tout lu
                </button>
                <button onclick="closeNotifPanel()" class="md:hidden p-1 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                  <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
              </div>
            </div>

            {{-- List --}}
            <div id="notifList" class="overflow-y-auto flex-1 overscroll-contain"></div>

            {{-- Footer / Pagination --}}
            <div class="flex items-center justify-between px-4 py-2.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/70 shrink-0">
              <button id="notifPrevBtn" onclick="notifChangePage(-1)" disabled
                class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                <span class="material-symbols-outlined text-[15px]">chevron_left</span>Préc.
              </button>
              <span id="notifPageInfo" class="text-xs text-slate-400 dark:text-slate-500">Page 1 / 1</span>
              <button id="notifNextBtn" onclick="notifChangePage(1)" disabled
                class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                Suiv.<span class="material-symbols-outlined text-[15px]">chevron_right</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- Theme Toggle --}}
      <button
        onclick="toggleTheme()"
        class="hidden sm:flex p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors"
        aria-label="Thème"
      >
        <span class="material-symbols-outlined text-[22px] dark:hidden">dark_mode</span>
        <span class="material-symbols-outlined text-[22px] hidden dark:block">light_mode</span>
      </button>

      {{-- Profile Dropdown --}}
      <div class="relative" id="profileWrapper">
        <button
          id="profileBtn"
          onclick="toggleProfile()"
          class="flex items-center gap-1.5 p-1.5 pr-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
          aria-label="Profil"
          aria-haspopup="true"
        >
          <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-sm font-bold shrink-0">
            {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name ?? 'U', 0, 1)) }}
          </div>
          <span class="hidden sm:inline text-sm font-medium text-slate-800 dark:text-slate-200 max-w-[80px] truncate">
            {{ auth()->user()->first_name ?? auth()->user()->name }}
          </span>
          <span class="material-symbols-outlined text-[18px] text-slate-500 hidden sm:block">expand_more</span>
        </button>

        {{-- Profile Menu --}}
        <div
          id="profileMenu"
          class="hidden absolute right-0 top-full mt-2 w-52 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden z-50"
        >
          <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
            <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">
              {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ auth()->user()->email }}</p>
          </div>
          <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
            <span class="material-symbols-outlined text-[18px]">person</span>Mon Profil
          </a>
          <a href="{{ route('profile.security') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
            <span class="material-symbols-outlined text-[18px]">security</span>Sécurité
          </a>
          <a href="{{ route('kyc.show') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
            <span class="material-symbols-outlined text-[18px]">verified_user</span>KYC
          </a>
          <div class="border-t border-slate-100 dark:border-slate-700">
            <form action="{{ route('auth.logout') }}" method="POST">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                <span class="material-symbols-outlined text-[18px]">logout</span>Déconnexion
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</nav>

<style>
  /* Desktop: notification panel smart positioning */
  @media (min-width: 768px) {
    #notifContent {
      width: clamp(300px, 90vw, 420px) !important;
      max-width: min(420px, calc(100vw - 16px)) !important;
    }
  }

  /* Slide-up animation for mobile sheet */
  @keyframes slideUp {
    from { transform: translateY(100%); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: scale(0.96) translateY(-4px); }
    to   { opacity: 1; transform: scale(1)    translateY(0);    }
  }

  #notifContent.animate-sheet { animation: slideUp 0.3s cubic-bezier(.32,.72,0,1) forwards; }
  #notifContent.animate-dropdown { animation: fadeIn 0.2s ease forwards; }
  #profileMenu.animate-dropdown { animation: fadeIn 0.18s ease forwards; }
</style>

<script>
// ── THEME ───────────────────────────────────────────────────────────
function toggleTheme() {
  const html = document.documentElement;
  const isDark = html.classList.toggle('dark');
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

// ── SIDEBAR ─────────────────────────────────────────────────────────
function toggleSidebar() {
  const sidebar = document.getElementById('dashboard-sidebar');
  if (!sidebar) return;
  if (window.innerWidth < 1024) {
    sidebar.classList.toggle('mobile-open');
  } else {
    const collapsed = sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebar-collapsed', collapsed);
  }
}

// ── PROFILE DROPDOWN ────────────────────────────────────────────────
let profileOpen = false;
function toggleProfile() {
  profileOpen ? closeProfile() : openProfile();
}
function openProfile() {
  profileOpen = true;
  const menu = document.getElementById('profileMenu');
  menu.classList.remove('hidden');
  menu.classList.add('animate-dropdown');
  document.getElementById('profileBtn').setAttribute('aria-expanded', 'true');
}
function closeProfile() {
  profileOpen = false;
  const menu = document.getElementById('profileMenu');
  menu.classList.add('hidden');
  document.getElementById('profileBtn').setAttribute('aria-expanded', 'false');
}

// ── NOTIFICATIONS ───────────────────────────────────────────────────
const NOTIF_RECENT_URL   = '{{ route("notifications.recent") }}';
const NOTIF_READ_ALL_URL = '{{ route("notifications.read-all") }}';
const NOTIF_READ_URL     = (id) => `/notifications/${id}/read`;
const CSRF_TOKEN         = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

let notifOpen        = false;
let notifCurrentPage = 1;
let notifTotalPages  = 1;

function toggleNotifPanel() {
  notifOpen ? closeNotifPanel() : openNotifPanel();
}

function openNotifPanel() {
  notifOpen = true;
  notifCurrentPage = 1;

  const panel   = document.getElementById('notifPanel');
  const content = document.getElementById('notifContent');
  const btn     = document.getElementById('notifBtn');

  panel.classList.remove('hidden');
  btn.setAttribute('aria-expanded', 'true');

  const isMobile = window.innerWidth < 768;
  if (isMobile) {
    content.classList.add('animate-sheet');
    content.classList.remove('animate-dropdown');
  } else {
    content.classList.add('animate-dropdown');
    content.classList.remove('animate-sheet');
    // Positionnement intelligent : évite débordement gauche ET droite
    const btnRect = btn.getBoundingClientRect();
    const panelW  = Math.min(420, window.innerWidth - 16);
    const margin  = 8;
    const vw      = window.innerWidth;
    // Aligne à droite du bouton, mais clamp pour rester dans le viewport
    let leftPos = btnRect.right - panelW;
    leftPos = Math.max(margin, Math.min(leftPos, vw - panelW - margin));
    content.style.top   = (btnRect.bottom + 8) + 'px';
    content.style.left  = leftPos + 'px';
    content.style.right = 'auto';
    content.style.width = panelW + 'px';
  }

  loadNotifications(1);
}

function closeNotifPanel() {
  notifOpen = false;
  document.getElementById('notifPanel').classList.add('hidden');
  document.getElementById('notifBtn').setAttribute('aria-expanded', 'false');
}

function notifChangePage(delta) {
  const p = notifCurrentPage + delta;
  if (p < 1 || p > notifTotalPages) return;
  notifCurrentPage = p;
  loadNotifications(p);
}

function setNotifBadge(count) {
  const badge      = document.getElementById('notifBadge');
  const sidebarDot = document.getElementById('sidebarNotifDot');
  const chip       = document.getElementById('notifUnreadChip');

  if (count > 0) {
    badge?.classList.remove('hidden');
    sidebarDot?.classList.remove('hidden');
    if (chip) { chip.textContent = count > 99 ? '99+' : count; chip.classList.remove('hidden'); }
  } else {
    badge?.classList.add('hidden');
    sidebarDot?.classList.add('hidden');
    chip?.classList.add('hidden');
  }
}

function notifIcon(title) {
  const t = (title || '').toLowerCase();
  if (t.includes('dépôt') || t.includes('depot') || t.includes('deposit'))
    return { icon: 'arrow_downward', color: 'text-emerald-600 bg-emerald-100 dark:bg-emerald-900/30' };
  if (t.includes('retrait') || t.includes('withdraw'))
    return { icon: 'arrow_upward', color: 'text-blue-600 bg-blue-100 dark:bg-blue-900/30' };
  if (t.includes('trade') || t.includes('ordre') || t.includes('order'))
    return { icon: 'show_chart', color: 'text-purple-600 bg-purple-100 dark:bg-purple-900/30' };
  if (t.includes('kyc') || t.includes('vérif'))
    return { icon: 'verified_user', color: 'text-amber-600 bg-amber-100 dark:bg-amber-900/30' };
  if (t.includes('transfert') || t.includes('transfer'))
    return { icon: 'swap_horiz', color: 'text-indigo-600 bg-indigo-100 dark:bg-indigo-900/30' };
  return { icon: 'notifications', color: 'text-slate-600 bg-slate-100 dark:bg-slate-700' };
}

async function loadNotifications(page = 1) {
  const list = document.getElementById('notifList');
  list.innerHTML = `<div class="flex items-center justify-center gap-2 py-10 text-slate-400"><span class="material-symbols-outlined text-[22px] animate-spin">progress_activity</span><span class="text-sm">Chargement…</span></div>`;

  try {
    const res  = await fetch(NOTIF_RECENT_URL + '?page=' + page, {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
    });
    const json = await res.json();

    setNotifBadge(json.unread_count ?? 0);
    notifCurrentPage = json.page       ?? page;
    notifTotalPages  = json.total_pages ?? 1;

    const prevBtn  = document.getElementById('notifPrevBtn');
    const nextBtn  = document.getElementById('notifNextBtn');
    const pageInfo = document.getElementById('notifPageInfo');
    if (prevBtn)  prevBtn.disabled  = notifCurrentPage <= 1;
    if (nextBtn)  nextBtn.disabled  = notifCurrentPage >= notifTotalPages;
    if (pageInfo) pageInfo.textContent = `Page ${notifCurrentPage} / ${notifTotalPages}`;

    if (!json.notifications?.length) {
      list.innerHTML = `<div class="flex flex-col items-center justify-center py-12 text-slate-400 dark:text-slate-500 gap-3"><span class="material-symbols-outlined text-[44px]">notifications_off</span><span class="text-sm font-medium">Aucune notification</span></div>`;
      return;
    }

    list.innerHTML = json.notifications.map(n => {
      const { icon, color } = notifIcon(n.title);
      const unreadBar = !n.read ? '<span class="absolute left-0 top-0 bottom-0 w-0.5 bg-blue-500"></span>' : '';
      const bg        = !n.read ? 'bg-blue-50/70 dark:bg-blue-900/10' : '';
      return `
        <div class="relative flex items-start gap-3 px-4 py-3 ${bg} hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors cursor-pointer group border-b border-slate-100 dark:border-slate-800 last:border-0"
             onclick="handleNotifClick('${n.id}', ${JSON.stringify(n.url ?? null)})">
          ${unreadBar}
          <div class="shrink-0 w-9 h-9 rounded-full flex items-center justify-center ${color} mt-0.5">
            <span class="material-symbols-outlined text-[16px]">${icon}</span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-slate-900 dark:text-white leading-snug">${n.title}</p>
            ${n.message ? `<p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">${n.message}</p>` : ''}
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">${n.time}</p>
          </div>
          ${!n.read ? `<button onclick="event.stopPropagation(); markOneNotifRead('${n.id}')"
            class="shrink-0 opacity-0 group-hover:opacity-100 p-1 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-all mt-0.5"
            title="Marquer lu"><span class="material-symbols-outlined text-[14px] text-slate-500">check</span></button>` : ''}
        </div>`;
    }).join('');

  } catch {
    list.innerHTML = `<div class="flex flex-col items-center justify-center py-10 text-red-400 gap-2"><span class="material-symbols-outlined text-[36px]">error</span><span class="text-sm">Erreur de chargement</span></div>`;
  }
}

async function markAllNotifRead() {
  await fetch(NOTIF_READ_ALL_URL, { method: 'PUT', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
  setNotifBadge(0);
  loadNotifications(notifCurrentPage);
}

async function markOneNotifRead(id) {
  await fetch(NOTIF_READ_URL(id), { method: 'PUT', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
  loadNotifications(notifCurrentPage);
}

function handleNotifClick(id, url) {
  markOneNotifRead(id);
  if (url) { window.location.href = url; }
  else      { closeNotifPanel(); }
}

// Fermer au clic extérieur
document.addEventListener('click', function(e) {
  // Notifications
  const notifW = document.getElementById('notifWrapper');
  const sidebarBtn = document.getElementById('sidebarNotifBtn');
  if (notifW && !notifW.contains(e.target) && (!sidebarBtn || !sidebarBtn.contains(e.target))) {
    closeNotifPanel();
  }
  // Profile
  const profileW = document.getElementById('profileWrapper');
  if (profileW && !profileW.contains(e.target)) {
    closeProfile();
  }
});

// Init au chargement
document.addEventListener('DOMContentLoaded', async function() {
  // Theme
  const theme = localStorage.getItem('theme') || 'light';
  if (theme === 'dark') document.documentElement.classList.add('dark');

  // Sidebar collapsed state (desktop only)
  if (localStorage.getItem('sidebar-collapsed') === 'true' && window.innerWidth >= 1024) {
    document.getElementById('dashboard-sidebar')?.classList.add('collapsed');
  }

  // Badge initial
  try {
    const res  = await fetch('{{ route("notifications.unread-count") }}', { headers: { 'Accept': 'application/json' } });
    const json = await res.json();
    setNotifBadge(json.count ?? 0);
  } catch {}
});

// Escape pour fermer
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { closeNotifPanel(); closeProfile(); }
});
</script>
