@props(['title' => null])
@php
    // Layout dedie a la page Trade (xendaro-fox-plan.json > Page id 37, layout_plein_ecran):
    // PLEIN ECRAN, sans la sidebar ni la navbar de l'espace client (components.layouts.dashboard).
    // Independant du layout public/auth/dashboard - ne reutilise ni ne modifie ces layouts.
    // Usage: <x-layouts.trade>{{ $slot }}</x-layouts.trade> (voir components.layouts.public pour le patron suivi).
    $siteIdentifier = $siteIdentifier ?? \App\Services\SiteIdentifierService::current();
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteIdentifier->nom_plateforme ?? 'Xendaro Fox' }} - {{ $title ?? __('app.trade.page_title') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/trade-chart.js'])
    @livewireStyles
</head>
<body class="bg-fond-principal text-texte-principal font-sans antialiased h-screen overflow-hidden flex flex-col">
    {{--
        x-cloak ne doit JAMAIS etre pose sur <body> lui-meme: Alpine ne le retire pas de facon fiable
        sur l'element racine du tree-walk (contrairement aux elements x-show a l'interieur d'un x-data),
        ce qui laisse la regle CSS [x-cloak]{display:none!important} bloquer TOUTE la page en display:none
        indefiniment - page Trade entierement invisible/inutilisable malgre un rendu HTML correct.
        x-cloak reste legitime sur des elements x-show individuels (voir <x-modal>), jamais sur body/html.
    --}}

    {{-- Barre superieure minimale et independante, propre a la page Trade (PAS le header/navbar dashboard) --}}
    <header class="flex items-center justify-between gap-3 px-4 h-12 border-b border-bordure-subtile bg-fond-surface shrink-0">
        <x-logo size="sm" />
        <a
            href="{{ url('/espace-client') }}"
            class="inline-flex items-center gap-1.5 text-xs text-texte-secondaire hover:text-texte-principal transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('app.trade.back_to_dashboard') }}
        </a>
    </header>

    <main class="flex-1 min-h-0">
        {{ $slot }}
    </main>

    <x-toast-container />

    @livewireScripts
</body>
</html>
