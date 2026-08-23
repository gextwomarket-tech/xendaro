@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteIdentifier->nom_plateforme ?? 'Xendaro Fox' }}{{ $title ? ' - '.$title : '' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{--
        @livewireStyles/@livewireScripts explicites: sur les pages publiques sans aucun
        composant Livewire (home, markets, etc.), Livewire n'injecte ses assets QUE si un
        composant Livewire est reellement rendu dans la requete - sinon son JS (qui embarque
        Alpine.js) ne charge jamais, cassant tous les x-data (menu mobile, bandeau cookies,
        animations x-reveal/x-page-hero). Ces directives forcent le chargement partout.
    --}}
    @livewireStyles
</head>
<body class="bg-fond-principal text-texte-principal font-sans min-h-screen flex flex-col">

    <x-public-navbar :site-identifier="$siteIdentifier ?? null" />

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-public-footer :site-identifier="$siteIdentifier ?? null" />

    <x-toast-container />

    <x-cookie-consent-banner />

    @livewireScripts
</body>
</html>
