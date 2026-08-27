@props(['title' => null])
{{--
    Layout Authentification (Pages id 25 a 30), 2 colonnes responsive.
    Reference visuelle: images_design_ui/login_design.jpg, adaptee au theme 100% Dark de Xendaro Fox
    (fond clair -> fond_principal/fond_card, panneau sombre conserve tel quel dans son esprit).
    Colonne gauche: formulaire dans une card (slot par defaut).
    Colonne droite: grand panneau arrondi de branding + carte flottante, masque en mobile (<lg).
--}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteIdentifier->nom_plateforme ?? 'Xendaro Fox' }}{{ $title ? ' - '.$title : '' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-fond-principal text-texte-principal font-sans antialiased min-h-screen">

    <x-public-navbar :site-identifier="$siteIdentifier ?? null" />

    <div class="flex flex-col lg:flex-row lg:p-4 lg:gap-4">

        {{-- Colonne gauche: formulaire --}}
        <div class="flex-1 flex flex-col">
            <div class="flex-1 flex items-center justify-center px-6 py-10">
                <div class="w-full max-w-md">
                    <div class="rounded-lg bg-fond-card border border-bordure-subtile p-6 sm:p-8 shadow-2xl">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Colonne droite: panneau de branding (empile sous le formulaire en mobile, cote a cote en desktop) --}}
        <div class="flex w-full lg:w-[46%] xl:w-[42%] shrink-0 px-6 pb-6 lg:px-0 lg:pb-0">
            <div class="relative w-full min-h-[420px] lg:min-h-0 rounded-lg overflow-hidden border border-bordure-subtile p-10 flex flex-col justify-between">
                {{-- Image de fond, attenuee par la couleur de base (fond-surface) posee par-dessus en semi-transparent --}}
                <img src="/images/trading/trading-06.jpg" alt="" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-fond-surface/80"></div>

                {{-- Halo decoratif --}}
                <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-couleur-principale/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 -left-16 w-96 h-96 rounded-full bg-couleur-secondaire/10 blur-3xl"></div>

                <div class="relative z-10">
                    <p class="text-sm font-medium text-couleur-principale">{{ $siteIdentifier->nom_plateforme ?? 'Xendaro Fox' }}</p>
                    <h2 class="mt-4 font-display text-3xl font-bold text-texte-principal leading-tight">
                        {{ __('app.auth.welcome_title') }}
                    </h2>
                    <p class="mt-4 text-texte-secondaire max-w-sm">
                        {{ $siteIdentifier->slogan ?? __('app.auth.welcome_subtitle') }}
                    </p>
                </div>

                {{-- Carte flottante temoignage/stat --}}
                <div class="relative z-10 mt-10 rounded-lg bg-fond-card border border-bordure-subtile p-6 shadow-xl">
                    <p class="font-display text-lg font-semibold text-texte-principal">
                        {{ __('app.auth.floating_card_title') }}
                    </p>
                    <p class="mt-2 text-sm text-texte-secondaire">
                        {{ __('app.auth.floating_card_text') }}
                    </p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex -space-x-2">
                            <span class="w-8 h-8 rounded-full bg-couleur-principale/20 border-2 border-fond-card"></span>
                            <span class="w-8 h-8 rounded-full bg-couleur-secondaire/20 border-2 border-fond-card"></span>
                            <span class="w-8 h-8 rounded-full bg-succes/20 border-2 border-fond-card flex items-center justify-center text-[10px] font-semibold text-succes">+2</span>
                        </div>
                        <span class="text-xs text-texte-secondaire">{{ __('app.auth.floating_card_note') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-toast-container />

    @livewireScripts
</body>
</html>
