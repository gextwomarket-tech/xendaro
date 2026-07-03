<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'Dashboard' }} - Purprime Fox</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Restore theme before page renders to avoid flash -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Manrope', sans-serif;
        }
        
        /* Mobile: Sidebar as overlay */
        @media (max-width: 1023px) {
            #dashboard-sidebar {
                position: fixed;
                left: 0;
                top: 64px;
                bottom: 80px;
                width: 256px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 40;
            }
            
            #dashboard-sidebar.mobile-open {
                transform: translateX(0);
            }
            
            #dashboard-sidebar::after {
                content: '';
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
                z-index: -1;
            }
            
            #dashboard-sidebar.mobile-open::after {
                opacity: 1;
                visibility: visible;
            }
            
            main {
                margin-left: 0 !important;
            }
        }
        
        /* Desktop: Sidebar as fixed sidebar */
        @media (min-width: 1024px) {
            #dashboard-sidebar {
                position: fixed;
                left: 0;
                top: 64px;
                bottom: 80px;
                width: 256px;
                transition: width 0.3s ease;
            }
            
            #dashboard-sidebar.collapsed {
                width: 80px !important;
            }
            
            main {
                margin-left: 256px;
                transition: margin-left 0.3s ease;
            }
            
            main.sidebar-collapsed {
                margin-left: 80px;
            }
        }
    </style>
    @stack('styles')
    <style>
        .toast {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            background: rgb(15, 23, 42);
            border: 1px solid rgb(30, 41, 59);
            border-radius: 8px;
            padding: 16px 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 50;
            display: none;
            animation: slideDown 0.3s ease;
            color: white;
        }
        .toast.show { display: flex; }
        .toast.light-mode {
            background: rgb(30, 41, 59);
            border-color: rgb(50, 65, 90);
            color: rgb(200, 210, 220);
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white">
    
    <!-- Navbar -->
    @include('components.dashboard-navbar')

    <!-- Sidebar -->
    @include('components.dashboard-sidebar')

    <!-- Main Content -->
    <main class="pt-24 pb-20 px-6 md:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg flex items-center gap-2 animate-fadeIn">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="ml-auto">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-lg flex items-center gap-2 animate-fadeIn">
                    <span class="material-symbols-outlined">error</span>
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="ml-auto">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 rounded-lg flex items-center gap-2 animate-fadeIn">
                    <span class="material-symbols-outlined">info</span>
                    <span>{{ session('info') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="ml-auto">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endif

            <!-- Page Title -->
            @if (isset($title))
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">{{ $title }}</h1>
                    @if (isset($subtitle))
                        <p class="text-slate-600 dark:text-slate-400">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif

            <!-- Page Content -->
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    @include('components.dashboard-footer')

    <!-- KYC Warning Modal -->
    @include('components.kyc-warning-modal')

    <!-- Loading Toast -->
    <div id="loadingToast" class="toast">
        <div class="flex items-center gap-3">
            <div class="w-5 h-5 border-2 border-blue-400 border-t-transparent rounded-full animate-spin"></div>
            <span class="font-medium">En cours de chargement...</span>
        </div>
    </div>

    <script>
        function updateToastTheme() {
            const toast = document.getElementById('loadingToast');
            if (document.documentElement.classList.contains('dark')) {
                toast.classList.remove('light-mode');
            } else {
                toast.classList.add('light-mode');
            }
        }

        function showLoadingToast() {
            updateToastTheme();
            const toast = document.getElementById('loadingToast');
            if (toast) toast.classList.add('show');
            setTimeout(hideLoadingToast, 4000);
        }

        function hideLoadingToast() {
            const toast = document.getElementById('loadingToast');
            if (toast) toast.classList.remove('show');
        }

        // Observer pour changements de thème
        const observer = new MutationObserver(updateToastTheme);
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

        // Initialiser au chargement
        updateToastTheme();

        // Rafraîchir la page si le modal KYC n'est pas affichée (user doit être vérifié)
        document.addEventListener('DOMContentLoaded', function() {
            const kycModal = document.getElementById('kyc-modal');
            // Si le modal n'existe pas, l'utilisateur est vérifié - rafraîchir la page pour appliquer les changements
            if (!kycModal) {
                // Vérifier le statut KYC actuel et rafraîchir si nécessaire
                fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => response.text())
                    .catch(() => {}); // Silencieusement échouer
            }
        });
    </script>

    <script>
        const sidebar = document.getElementById('dashboard-sidebar');
        const main = document.querySelector('main');
        const isMobile = () => window.innerWidth < 1024;
        
        // Sync sidebar state with main margin (desktop only)
        function updateMainMargin() {
            if (!isMobile()) {
                if (sidebar.classList.contains('collapsed')) {
                    main.classList.add('sidebar-collapsed');
                } else {
                    main.classList.remove('sidebar-collapsed');
                }
            }
        }
        
        // Close sidebar on mobile when link is clicked
        document.querySelectorAll('#dashboard-sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (isMobile()) {
                    sidebar.classList.remove('mobile-open');
                }
            });
        });
        
        // Close sidebar when clicking outside (mobile)
        document.addEventListener('click', (e) => {
            if (isMobile() && sidebar.classList.contains('mobile-open')) {
                if (!sidebar.contains(e.target) && !document.getElementById('sidebar-toggle').contains(e.target)) {
                    sidebar.classList.remove('mobile-open');
                }
            }
        });
        
        // Update on load
        updateMainMargin();
        
        // Observe sidebar changes
        const sidebarObserver = new MutationObserver(updateMainMargin);
        sidebarObserver.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (!isMobile()) {
                sidebar.classList.remove('mobile-open');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
