<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Dashboard' }} - Purprime Fox</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Manrope', sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white">
    
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <main class="pt-16 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg flex items-center gap-2">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="ml-auto">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-lg flex items-center gap-2">
                    <span class="material-symbols-outlined">error</span>
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="ml-auto">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 rounded-lg flex items-center gap-2">
                    <span class="material-symbols-outlined">info</span>
                    <span>{{ session('info') }}</span>
                    <button onclick="this.parentElement.style.display='none'" class="ml-auto">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endif

            <!-- Page Content -->
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    @include('components.footer')

    @stack('scripts')
</body>
</html>
