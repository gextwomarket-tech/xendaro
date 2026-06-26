<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Dashboard' }} - Xendaro Fox</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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


            {{-- ⚠ KYC Reminder Toast (non-bloquant) --}}
            @if (session('kyc_reminder'))
            <div id="kyc-reminder-toast"
                 style="position:fixed;top:80px;right:20px;z-index:9999;max-width:380px;width:calc(100vw - 40px);
                        background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%);
                        border:1.5px solid rgba(255,165,0,.5);border-radius:12px;
                        padding:14px 16px;box-shadow:0 8px 32px rgba(255,140,0,.2);
                        display:flex;align-items:flex-start;gap:12px;
                        animation:kycSlideIn .4s cubic-bezier(.175,.885,.32,1.275) both;">
                <span style="font-size:1.4rem;flex-shrink:0;line-height:1">🔐</span>
                <div style="flex:1;min-width:0">
                    <div style="font-size:.8rem;font-weight:700;color:#FF8C00;margin-bottom:3px">Vérification KYC requise</div>
                    <div style="font-size:.72rem;color:rgba(255,255,255,.7);line-height:1.4">
                        Complétez votre KYC pour débloquer toutes les fonctionnalités de la plateforme.
                    </div>
                    <a href="{{ route('kyc.show') }}"
                       style="display:inline-block;margin-top:8px;padding:5px 12px;
                              background:rgba(255,140,0,.2);border:1px solid rgba(255,140,0,.5);
                              border-radius:6px;font-size:.7rem;font-weight:600;color:#FF8C00;
                              text-decoration:none;transition:all .2s"
                       onmouseover="this.style.background='rgba(255,140,0,.35)'" onmouseout="this.style.background='rgba(255,140,0,.2)'">
                        Vérifier maintenant →
                    </a>
                </div>
                <button onclick="document.getElementById('kyc-reminder-toast').remove()"
                        style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,.4);
                               font-size:1.1rem;line-height:1;flex-shrink:0;padding:0">&times;</button>
            </div>
            <style>
                @keyframes kycSlideIn {
                    from { opacity:0; transform:translateX(30px) scale(.95) }
                    to   { opacity:1; transform:translateX(0) scale(1) }
                }
                @media (max-width:480px) {
                    #kyc-reminder-toast { right:10px !important; top:65px !important; }
                }
            </style>
            <script>
                setTimeout(function(){
                    var t = document.getElementById('kyc-reminder-toast');
                    if(t){ t.style.transition='opacity .4s ease';t.style.opacity='0';setTimeout(function(){t.remove()},400); }
                }, 6000);
            </script>
            @endif

            {{-- Page Content --}}
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    @include('components.footer')

    @stack('scripts')
</body>
</html>
