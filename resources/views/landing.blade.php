<!DOCTYPE html>
<html class="dark" lang="fr" style="">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>XFT - Xendaro Fox Trading | Excellence Institutionnelle</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&amp;family=Inter:wght@300;400;500;600&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-fixed-dim": "#ffba20",
                        "primary-fixed-dim": "#c3c6d3",
                        "on-secondary-container": "#f9f7ff",
                        "on-error-container": "#ffdad6",
                        "on-tertiary-fixed-variant": "#5e4200",
                        "inverse-surface": "#e0e3e5",
                        "on-secondary-fixed": "#001849",
                        "on-tertiary": "#412d00",
                        "background": "#101415",
                        "on-primary": "#2c303a",
                        "on-primary-container": "#777b87",
                        "surface-variant": "#323537",
                        "surface-bright": "#363a3b",
                        "error-container": "#93000a",
                        "tertiary-fixed": "#ffdea8",
                        "on-secondary-fixed-variant": "#003fa4",
                        "secondary-fixed": "#dae1ff",
                        "inverse-on-surface": "#2d3133",
                        "surface-container-highest": "#323537",
                        "tertiary-container": "#160c00",
                        "surface": "#101415",
                        "on-primary-fixed-variant": "#434751",
                        "error": "#ffb4ab",
                        "on-tertiary-container": "#a17300",
                        "on-error": "#690005",
                        "secondary": "#b3c5ff",
                        "surface-dim": "#101415",
                        "outline": "#909096",
                        "primary-container": "#0a0e17",
                        "inverse-primary": "#5a5e69",
                        "surface-tint": "#c3c6d3",
                        "surface-container-lowest": "#0b0f10",
                        "on-surface-variant": "#c6c6cc",
                        "primary-fixed": "#dfe2ef",
                        "on-background": "#e0e3e5",
                        "secondary-container": "#0266ff",
                        "on-surface": "#e0e3e5",
                        "surface-container-low": "#191c1e",
                        "surface-container": "#1d2022",
                        "on-secondary": "#002b75",
                        "primary": "#c3c3d3",
                        "on-tertiary-fixed": "#271900",
                        "outline-variant": "#45464b",
                        "secondary-fixed-dim": "#b3c5ff",
                        "surface-container-high": "#272a2c",
                        "tertiary": "#ffba20",
                        "on-primary-fixed": "#181b25"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "base": "8px",
                        "margin-mobile": "20px",
                        "section-gap": "120px",
                        "margin-desktop": "80px",
                        "gutter": "24px",
                        "container-max": "1280px"
                    },
                    "fontFamily": {
                        "headline-md": ["Montserrat"],
                        "body-md": ["Inter"],
                        "label-sm": ["JetBrains Mono"],
                        "body-lg": ["Inter"],
                        "button": ["Montserrat"],
                        "display-lg": ["Montserrat"],
                        "display-lg-mobile": ["Montserrat"]
                    },
                    "fontSize": {
                        "headline-md": ["32px", {"lineHeight": "1.3", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "label-sm": ["12px", {"lineHeight": "1.0", "letterSpacing": "0.05em", "fontWeight": "500"}],
                        "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "button": ["14px", {"lineHeight": "1.0", "letterSpacing": "0.02em", "fontWeight": "600"}],
                        "display-lg": ["64px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "display-lg-mobile": ["40px", {"lineHeight": "1.2", "fontWeight": "700"}]
                    }
                }
            }
        }
    </script>
<style>
        body {
            background-color: #101415;
            color: #e0e3e5;
            scroll-behavior: smooth;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .glass-card:hover {
            border-color: #ffba20;
            transform: translateY(-4px);
        }

        .glow-button-primary {
            box-shadow: 0 0 15px rgba(255, 186, 32, 0.3);
            transition: all 0.3s ease;
        }

        .glow-button-primary:hover {
            box-shadow: 0 0 25px rgba(255, 186, 32, 0.5);
            transform: scale(1.02);
        }

        .modal-overlay {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            display: none;
        }

        .modal-active {
            display: flex !important;
            animation: fadeIn 0.3s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #101415; }
        ::-webkit-scrollbar-thumb { background: #323537; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #ffba20; }
    </style>
</head>
<body class="font-body-md text-body-md overflow-x-hidden">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-xl border-b border-white/10 shadow-2xl shadow-black/50">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop flex justify-between items-center h-20">
<a class="flex items-center gap-3" href="/">
<span class="font-headline-md text-[24px] font-bold text-tertiary tracking-tighter">XFT 🦊</span>
</a>
<div class="hidden lg:flex items-center gap-8">
<a class="text-tertiary border-b-2 border-tertiary pb-1 font-button text-button" href="{{ route('home') }}">Accueil</a>
<button class="text-on-surface/70 hover:text-tertiary transition-colors duration-300 font-button text-button" onclick="openModal('services')">Services</button>
<button class="text-on-surface/70 hover:text-tertiary transition-colors duration-300 font-button text-button" onclick="openModal('produits')">Produits</button>
<a class="text-on-surface/70 hover:text-tertiary transition-colors duration-300 font-button text-button" href="#markets">Marchés</a>
<button class="text-on-surface/70 hover:text-tertiary transition-colors duration-300 font-button text-button" onclick="openModal('about')">À propos</button>
<button class="text-on-surface/70 hover:text-tertiary transition-colors duration-300 font-button text-button" onclick="openModal('contact')">Contact</button>
</div>
<div class="flex items-center gap-4">
<a href="{{ route('auth.login') }}" class="hidden sm:block text-on-surface/70 font-button text-button hover:text-white transition-colors">Login</a>
<a href="{{ route('register') }}" class="bg-tertiary text-on-tertiary px-6 py-3 rounded-full font-button text-button glow-button-primary active:scale-95 transition-transform">Ouvrir un compte</a>
</div>
</div>
</nav>

<!-- Section 1: Hero -->
<header class="relative py-20 lg:py-32 overflow-hidden flex items-center pt-40">
<div class="relative z-20 max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop w-full">
<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
    <div class="order-2 lg:order-1">
        <span class="inline-block px-4 py-1 rounded-full bg-tertiary/10 border border-tertiary/30 text-tertiary font-label-sm text-label-sm uppercase tracking-widest mb-6">Broker de Confiance</span>
        <h1 class="font-display-lg-mobile md:font-display-lg text-4xl md:text-6xl text-white mb-6 leading-tight">
            L'excellence du <span class="text-tertiary">trading institutionnel</span> à votre portée.
        </h1>
        <p class="text-body-lg text-on-surface-variant mb-10 max-w-xl">
            Accédez à une technologie de pointe, une liquidité profonde et une exécution ultra-rapide sur plus de 500 actifs mondiaux.
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('register') }}" class="bg-tertiary text-on-tertiary px-8 py-4 rounded-full font-button text-button glow-button-primary text-lg text-center">Ouvrir un compte</a>
            <a href="#markets" class="glass-panel text-white px-8 py-4 rounded-full font-button text-button hover:bg-white/10 transition-colors border border-white/20 text-center">Explorer les marchés</a>
        </div>
    </div>
    <div class="order-1 lg:order-2 relative">
        <div class="absolute -inset-4 bg-tertiary/10 blur-[100px] rounded-full"></div>
        <div class="relative z-10 rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
            <img src="/66520.jpg" alt="Trading Platform" class="w-full h-auto object-cover">
        </div>
    </div>
</div>
</div>
</header>

<!-- Section 2: Nos Services -->
<section class="py-section-gap relative">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter items-center">
<div class="order-2 lg:order-1 relative">
<div class="absolute -inset-4 bg-tertiary/5 blur-3xl rounded-full"></div>
<img alt="XFT Services" class="relative z-10 w-full max-w-md mx-auto drop-shadow-2xl rounded-xl" src="/885.jpg">
</div>
<div class="order-1 lg:order-2">
<h2 class="font-headline-md text-headline-md text-white mb-6">Des services conçus pour la performance</h2>
<p class="text-body-lg text-on-surface-variant mb-8">
Notre infrastructure est bâtie sur des piliers de robustesse et de rapidité, offrant aux traders particuliers les mêmes outils que les fonds spéculatifs.
</p>
<div class="space-y-6">
<div class="flex gap-4 items-start">
<div class="p-3 bg-secondary-container/20 rounded-xl">
<span class="material-symbols-outlined text-tertiary">bolt</span>
</div>
<div>
<h4 class="font-bold text-white mb-1">Exécution Ultra-Rapide</h4>
<p class="text-on-surface-variant">Latence minimale grâce à nos serveurs situés dans les centres de données premium.</p>
</div>
</div>
<div class="flex gap-4 items-start">
<div class="p-3 bg-secondary-container/20 rounded-xl">
<span class="material-symbols-outlined text-tertiary">water_drop</span>
</div>
<div>
<h4 class="font-bold text-white mb-1">Liquidité Institutionnelle</h4>
<p class="text-on-surface-variant">Accès direct aux fournisseurs de liquidité de rang 1 pour des spreads ultra-compétitifs.</p>
</div>
</div>
<div class="flex gap-4 items-start">
<div class="p-3 bg-secondary-container/20 rounded-xl">
<span class="material-symbols-outlined text-tertiary">support_agent</span>
</div>
<div>
<h4 class="font-bold text-white mb-1">Support Expert 24/7</h4>
<p class="text-on-surface-variant">Une équipe dédiée de professionnels de la finance pour vous accompagner à chaque étape.</p>
</div>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- Section 3: Nos Produits -->
<section class="py-section-gap bg-surface-container-lowest/50">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter items-center">
<div>
<h2 class="font-headline-md text-headline-md text-white mb-6">Diversifiez votre portefeuille avec XFT</h2>
<p class="text-body-lg text-on-surface-variant mb-10">
Négociez une large gamme d'instruments financiers sur une seule plateforme intuitive, disponible sur desktop et mobile.
</p>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
<div class="glass-card p-6 rounded-2xl">
<span class="material-symbols-outlined text-tertiary mb-4" style="font-size: 40px;">currency_exchange</span>
<h3 class="text-xl font-bold mb-2">Forex</h3>
<p class="text-sm text-on-surface-variant">60+ paires de devises avec des spreads à partir de 0.0 pips.</p>
</div>
<div class="glass-card p-6 rounded-2xl">
<span class="material-symbols-outlined text-tertiary mb-4" style="font-size: 40px;">query_stats</span>
<h3 class="text-xl font-bold mb-2">Indices</h3>
<p class="text-sm text-on-surface-variant">Tradez les bourses mondiales (S&amp;P 500, DAX 40, CAC 40) sans commission.</p>
</div>
<div class="glass-card p-6 rounded-2xl">
<span class="material-symbols-outlined text-tertiary mb-4" style="font-size: 40px;">currency_bitcoin</span>
<h3 class="text-xl font-bold mb-2">Cryptos</h3>
<p class="text-sm text-on-surface-variant">Bitcoin, Ethereum et plus, avec un effet de levier sécurisé 24/7.</p>
</div>
<div class="glass-card p-6 rounded-2xl">
<span class="material-symbols-outlined text-tertiary mb-4" style="font-size: 40px;">inventory_2</span>
<h3 class="text-xl font-bold mb-2">Matières</h3>
<p class="text-sm text-on-surface-variant">Or, Argent, Pétrole et Gaz avec des marges réduites.</p>
</div>
</div>
</div>
<div class="relative mt-12 lg:mt-0">
<div class="absolute -inset-4 bg-secondary-container/10 blur-[100px] rounded-full"></div>
<img alt="Mobile Trading App" class="relative z-10 rounded-3xl shadow-2xl border border-white/10 mx-auto" src="https://images.unsplash.com/photo-1556656793-08538906a9f8?w=400&h=500&fit=crop">
</div>
</div>
</div>
</section>

<!-- Section 4: Marchés (TradingView) -->
<section class="py-section-gap" id="markets">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop mb-12 text-center">
<h2 class="font-headline-md text-headline-md text-white mb-4">Suivez les Marchés en Temps Réel</h2>
<p class="text-on-surface-variant max-w-2xl mx-auto">Consultez les dernières tendances et analysez les opportunités sur les marchés mondiaux grâce à nos outils d'analyse intégrés.</p>
</div>
<div class="w-full px-margin-mobile md:px-margin-desktop">
<div class="glass-panel p-4 rounded-3xl h-[600px] w-full overflow-hidden">
<iframe scrolling="no" allowtransparency="true" frameborder="0" style="user-select: none; box-sizing: border-box; display: block; height: 100%; width: 100%;" src="https://www.tradingview-widget.com/embed-widget/market-overview/?locale=fr#%7B%22colorTheme%22%3A%22dark%22%2C%22dateRange%22%3A%2212M%22%2C%22showChart%22%3Atrue%2C%22isTransparent%22%3Atrue%2C%22showSymbolLogo%22%3Atrue%2C%22width%22%3A%22100%25%22%2C%22height%22%3A%22100%25%22%7D" title="market overview TradingView widget" lang="en"></iframe>
</div>
</div>
</section>

<!-- Section 5: Pourquoi Nous -->
<section class="py-section-gap">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
<div>
<img alt="Security Icon" class="w-64 h-auto mb-8 drop-shadow-[0_0_30px_rgba(255,186,32,0.2)] rounded-xl" src="https://images.unsplash.com/photo-1563986768609-9f905b1a31fa?w=400&h=400&fit=crop">
<h2 class="font-headline-md text-headline-md text-white mb-6">Pourquoi choisir XFT ?</h2>
<p class="text-body-lg text-on-surface-variant mb-12">
Nous redéfinissons les standards de l'industrie pour offrir une expérience de trading transparente, sécurisée et technologiquement avancée.
</p>
<div class="space-y-8">
<div class="flex gap-6">
<div class="flex-shrink-0 w-12 h-12 glass-panel rounded-full flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined">security</span>
</div>
<div>
<h3 class="text-xl font-bold text-white mb-2">Sécurité des Fonds</h3>
<p class="text-on-surface-variant">Comptes ségrégés dans des banques de niveau 1 et cryptage SSL 256 bits pour toutes les transactions.</p>
</div>
</div>
<div class="flex gap-6">
<div class="flex-shrink-0 w-12 h-12 glass-panel rounded-full flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined">analytics</span>
</div>
<div>
<h3 class="text-xl font-bold text-white mb-2">Analyse Prédictive</h3>
<p class="text-on-surface-variant">Outils d'IA intégrés pour vous aider à détecter les tendances du marché avant qu'elles ne se produisent.</p>
</div>
</div>
<div class="flex gap-6">
<div class="flex-shrink-0 w-12 h-12 glass-panel rounded-full flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined">speed</span>
</div>
<div>
<h3 class="text-xl font-bold text-white mb-2">Zéro Slippage</h3>
<p class="text-on-surface-variant">Notre moteur d'appariement ultra-performant garantit une exécution au prix demandé ou meilleur.</p>
</div>
</div>
</div>
</div>
<div class="grid grid-cols-2 gap-4">
<div class="space-y-4 pt-12">
<div class="glass-card p-8 rounded-3xl text-center">
<div class="text-4xl font-black text-tertiary mb-2">0.0</div>
<div class="text-sm font-label-sm uppercase tracking-widest text-on-surface/60">Pips Spread</div>
</div>
<div class="glass-card p-8 rounded-3xl text-center">
<div class="text-4xl font-black text-tertiary mb-2">30ms</div>
<div class="text-sm font-label-sm uppercase tracking-widest text-on-surface/60">Exécution</div>
</div>
</div>
<div class="space-y-4">
<div class="glass-card p-8 rounded-3xl text-center">
<div class="text-4xl font-black text-tertiary mb-2">500+</div>
<div class="text-sm font-label-sm uppercase tracking-widest text-on-surface/60">Instruments</div>
</div>
<div class="glass-card p-8 rounded-3xl text-center">
<div class="text-4xl font-black text-tertiary mb-2">1:500</div>
<div class="text-sm font-label-sm uppercase tracking-widest text-on-surface/60">Levier Max</div>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- Section 6: Statistiques -->
<section class="relative py-section-gap overflow-hidden">
<div class="absolute inset-0 z-0">
<div class="absolute inset-0 bg-background/80 z-10"></div>
<div class="w-full h-full bg-cover bg-fixed bg-center" style="background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1200&h=600&fit=crop')"></div>
</div>
<div class="relative z-20 max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter text-center">
<div class="p-8">
<div class="text-5xl md:text-6xl font-black text-white mb-4">500k+</div>
<div class="text-xl text-tertiary font-bold mb-2">Traders Actifs</div>
<p class="text-on-surface-variant">Une communauté mondiale en pleine croissance.</p>
</div>
<div class="p-8 border-y md:border-y-0 md:border-x border-white/10">
<div class="text-5xl md:text-6xl font-black text-white mb-4">$10B+</div>
<div class="text-xl text-tertiary font-bold mb-2">Volume Mensuel</div>
<div class="text-on-surface-variant">Une liquidité robuste pour tous vos trades.</div>
</div>
<div class="p-8">
<div class="text-5xl md:text-6xl font-black text-white mb-4">24/7</div>
<div class="text-xl text-tertiary font-bold mb-2">Support Client</div>
<div class="text-on-surface-variant">Toujours là quand vous en avez besoin.</div>
</div>
</div>
</div>
</section>

<!-- Section 7: Témoignages -->
<section class="py-section-gap">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="text-center mb-16">
<h2 class="font-headline-md text-headline-md text-white mb-4">La confiance de nos traders</h2>
<p class="text-on-surface-variant max-w-2xl mx-auto">Découvrez pourquoi des milliers de professionnels choisissent XFT pour leurs opérations quotidiennes.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
<div class="glass-card p-8 rounded-3xl flex flex-col">
<div class="flex text-tertiary mb-6">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-body-md italic mb-8 flex-grow text-on-surface-variant">"L'exécution est bluffante. Je trade principalement les indices et les spreads chez XFT sont les plus bas que j'ai pu trouver."</p>
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-full overflow-hidden border border-tertiary">
<img alt="Trader Portrait" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop">
</div>
<div>
<div class="font-bold text-white">Marc Aubert</div>
<div class="text-xs text-on-surface/60">Trader de Futures</div>
</div>
</div>
</div>
<div class="glass-card p-8 rounded-3xl border-tertiary/40 flex flex-col scale-105 bg-white/[0.05]">
<div class="flex text-tertiary mb-6">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-body-md italic mb-8 flex-grow text-white">"La plateforme mobile est d'une fluidité incroyable. Je peux gérer mes positions en déplacement avec une précision totale."</p>
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-full overflow-hidden border border-tertiary">
<img alt="Trader Portrait" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop">
</div>
<div>
<div class="font-bold text-white">Elena Rossi</div>
<div class="text-xs text-on-surface/60">Analyste Forex</div>
</div>
</div>
</div>
<div class="glass-card p-8 rounded-3xl flex flex-col">
<div class="flex text-tertiary mb-6">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-body-md italic mb-8 flex-grow text-on-surface-variant">"Le support client est très réactif. Ils m'ont aidé à configurer mon API pour mon bot de trading en quelques minutes."</p>
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-full overflow-hidden border border-tertiary">
<img alt="Trader Portrait" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop">
</div>
<div>
<div class="font-bold text-white">Thomas Wagner</div>
<div class="text-xs text-on-surface/60">Algorithmic Trader</div>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- Section 8: Contact -->
<section class="py-section-gap relative">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
<div class="glass-panel rounded-[40px] overflow-hidden flex flex-col lg:flex-row">
<div class="p-8 md:p-16 flex-1">
<h2 class="font-headline-md text-headline-md text-white mb-8">Parlons de votre futur trading</h2>
<form class="space-y-6">
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label class="block text-sm font-bold text-on-surface/70 mb-2">Prénom</label>
<input class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 focus:border-tertiary focus:ring-0 transition-all outline-none" placeholder="Jean" type="text">
</div>
<div>
<label class="block text-sm font-bold text-on-surface/70 mb-2">Nom</label>
<input class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 focus:border-tertiary focus:ring-0 transition-all outline-none" placeholder="Dupont" type="text">
</div>
</div>
<div>
<label class="block text-sm font-bold text-on-surface/70 mb-2">Email Professionnel</label>
<input class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 focus:border-tertiary focus:ring-0 transition-all outline-none" placeholder="jean@entreprise.com" type="email">
</div>
<div>
<label class="block text-sm font-bold text-on-surface/70 mb-2">Votre Message</label>
<textarea class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 focus:border-tertiary focus:ring-0 transition-all outline-none" placeholder="Comment pouvons-nous vous aider ?" rows="4"></textarea>
</div>
<button class="w-full bg-tertiary text-on-tertiary py-5 rounded-xl font-bold glow-button-primary uppercase tracking-widest text-sm" type="submit">Envoyer le message</button>
</form>
</div>
<div class="w-full lg:w-[450px] relative min-h-[400px] bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?w=450&h=500&fit=crop')">
<div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent"></div>
<div class="absolute bottom-12 left-12 right-12 text-white">
<div class="space-y-6">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-tertiary">location_on</span>
<div>
<div class="font-bold">Londres, Royaume-Uni</div>
<div class="text-sm opacity-70">12 Appold Street, EC2A 2AW</div>
</div>
</div>
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-tertiary">phone</span>
<div>
<div class="font-bold">+44 20 7123 4567</div>
<div class="text-sm opacity-70">Lundi - Vendredi, 8h - 20h</div>
</div>
</div>
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-tertiary">mail</span>
<div>
<div class="font-bold">support@xft.com</div>
<div class="text-sm opacity-70">Réponse en moins de 2h</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- Footer -->
<footer class="bg-surface-container-lowest border-t border-white/5">
<div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-20">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
<div class="col-span-1 lg:col-span-2">
<div class="flex items-center gap-3 mb-8">
<span class="font-headline-md text-[24px] font-bold text-tertiary tracking-tighter">XFT 🦊</span>
</div>
<p class="text-on-surface-variant max-w-sm mb-8">
Xendaro Fox Trading (XFT) est un leader mondial du courtage en ligne, offrant des solutions de trading multi-actifs aux investisseurs institutionnels et particuliers.
</p>
<div class="flex gap-4">
<a class="w-10 h-10 glass-panel rounded-full flex items-center justify-center hover:text-tertiary transition-all" href="#"><span class="material-symbols-outlined">public</span></a>
<a class="w-10 h-10 glass-panel rounded-full flex items-center justify-center hover:text-tertiary transition-all" href="#"><span class="material-symbols-outlined">alternate_email</span></a>
<a class="w-10 h-10 glass-panel rounded-full flex items-center justify-center hover:text-tertiary transition-all" href="#"><span class="material-symbols-outlined">share</span></a>
</div>
</div>
<div>
<h4 class="font-bold text-white mb-6">Liens Rapides</h4>
<ul class="space-y-4 text-on-surface-variant">
<li><a class="hover:text-tertiary transition-colors" href="{{ route('home') }}">Accueil</a></li>
<li><button class="hover:text-tertiary transition-colors" onclick="openModal('services')">Nos Services</button></li>
<li><button class="hover:text-tertiary transition-colors" onclick="openModal('produits')">Produits</button></li>
<li><a class="hover:text-tertiary transition-colors" href="#markets">Marchés</a></li>
<li><a class="hover:text-tertiary transition-colors" href="{{ route('auth.login') }}">Connexion</a></li>
<li><a class="hover:text-tertiary transition-colors" href="{{ route('register') }}">Ouvrir un compte</a></li>
</ul>
</div>
<div>
<h4 class="font-bold text-white mb-6">Légal</h4>
<ul class="space-y-4 text-on-surface-variant">
<li><a class="hover:text-tertiary transition-colors" href="{{ route('conditions') }}">Conditions d'utilisation</a></li>
<li><a class="hover:text-tertiary transition-colors" href="{{ route('policies') }}">Cookies Policies</a></li>
<li><button class="hover:text-tertiary transition-colors" onclick="openModal('risk')">Avertissement sur les risques</button></li>
<li><a class="hover:text-tertiary transition-colors" href="{{ route('about') }}">Qui sommes-nous ?</a></li>
</ul>
</div>
</div>
<div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
<p class="text-sm text-on-surface/50 text-center md:text-left">
© 2026 XFT Xendaro Fox Trading. All rights reserved. Precise execution, institutional liquidity.
</p>
<div class="flex items-center gap-6">
<span class="text-sm">Paiements acceptés:</span>
<span>💳 🪙 🏦</span>
</div>
</div>
<div class="mt-12 p-6 glass-panel rounded-2xl text-[11px] text-on-surface/40 leading-relaxed uppercase tracking-wider">
AVERTISSEMENT SUR LES RISQUES : Le trading de produits financiers comporte un niveau de risque élevé pour votre capital et n'est pas adapté à tous les investisseurs. Le trading avec effet de levier peut entraîner des pertes supérieures à votre dépôt initial. Veuillez vous assurer que vous comprenez parfaitement les risques encourus.
</div>
</div>
</footer>

<!-- POPUP MODAL SYSTEM -->
<div class="modal-overlay fixed inset-0 z-[100] hidden items-center justify-center p-4" id="globalModal">
<div class="relative w-full max-w-[95%] md:max-w-[70%] max-h-[90vh] glass-panel rounded-[32px] overflow-hidden shadow-2xl flex flex-col border border-white/20">
<div class="relative h-48 md:h-64 flex-shrink-0" id="modalHero">
<div class="absolute inset-0 bg-cover bg-center" id="modalHeroImg"></div>
<div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-surface-container-lowest"></div>
<button class="absolute top-6 right-6 w-10 h-10 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-tertiary transition-colors z-30" onclick="closeModal()">
<span class="material-symbols-outlined">close</span>
</button>
<div class="absolute bottom-6 left-8 z-20">
<h2 class="font-headline-md text-headline-md text-white drop-shadow-lg" id="modalTitle">Modal Title</h2>
</div>
</div>
<div class="p-8 md:p-12 overflow-y-auto flex-grow bg-surface-container-lowest text-on-surface-variant" id="modalBody">
</div>
</div>
</div>

<script>
const modalData = {
    'services': {
        title: 'Nos Services Premium',
        'img': '/885.jpg',
        content: `
            <div class="space-y-6">
                <p class="text-body-lg text-white font-medium">L'infrastructure XFT est conçue pour l'élite.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-6 glass-card rounded-2xl">
                        <h4 class="text-tertiary font-bold mb-3 flex items-center gap-2"><span class="material-symbols-outlined">speed</span> Exécution STP/ECN</h4>
                        <p>Nous offrons une exécution ultra-rapide sans bureau d'intervention. Vos ordres sont envoyés directement à nos fournisseurs de liquidité mondiaux.</p>
                    </div>
                    <div class="p-6 glass-card rounded-2xl">
                        <h4 class="text-tertiary font-bold mb-3 flex items-center gap-2"><span class="material-symbols-outlined">database</span> API Trading</h4>
                        <p>Connectez vos propres algorithmes via notre protocole FIX API 4.4 pour bénéficier d'une latence ultra-faible.</p>
                    </div>
                    <div class="p-6 glass-card rounded-2xl">
                        <h4 class="text-tertiary font-bold mb-3 flex items-center gap-2"><span class="material-symbols-outlined">account_balance</span> Gestion de Patrimoine</h4>
                        <p>Des services de conseil personnalisés pour les investisseurs institutionnels et les Family Offices.</p>
                    </div>
                    <div class="p-6 glass-card rounded-2xl">
                        <h4 class="text-tertiary font-bold mb-3 flex items-center gap-2"><span class="material-symbols-outlined">school</span> XFT Academy</h4>
                        <p>Accédez à des webinaires exclusifs et à des analyses quotidiennes de marché réalisées par nos experts.</p>
                    </div>
                </div>
            </div>
        `
    },
    'produits': {
        title: 'Nos Instruments de Trading',
        img: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&h=400&fit=crop',
        content: `
            <div class="space-y-6">
                <p class="text-body-lg text-white font-medium">Un accès global, une interface unique.</p>
                <div class="space-y-4">
                    <div class="p-4 border-l-4 border-tertiary bg-white/5">
                        <h4 class="font-bold text-white">Forex (60+ paires)</h4>
                        <p>Spreads serrés sur les majeures comme l'EUR/USD à partir de 0.0 pips.</p>
                    </div>
                    <div class="p-4 border-l-4 border-tertiary bg-white/5">
                        <h4 class="font-bold text-white">Actions & Indices</h4>
                        <p>Négociez des actions US et Européennes avec des frais transparents.</p>
                    </div>
                    <div class="p-4 border-l-4 border-tertiary bg-white/5">
                        <h4 class="font-bold text-white">Matières Premières</h4>
                        <p>Or, Argent et Energies avec un levier flexible.</p>
                    </div>
                </div>
            </div>
        `
    },
    'about': {
        title: 'Qui sommes-nous ?',
        img: 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=400&fit=crop',
        content: `
            <div class="space-y-6 max-w-3xl">
                <h4 class="text-xl font-bold text-white">L'histoire de Xendaro Fox Trading</h4>
                <p>Fondée en 2018 par une équipe de vétérans de la City, XFT est née d'une vision simple : démocratiser l'accès aux outils de trading institutionnel.</p>
                <p>Le nom "Xendaro Fox" symbolise l'intelligence, la ruse et l'agilité nécessaires pour naviguer dans les complexités des marchés financiers mondiaux.</p>
                <div class="grid grid-cols-3 gap-4 pt-6">
                    <div class="text-center">
                        <div class="text-tertiary text-2xl font-bold">15+</div>
                        <div class="text-xs uppercase">Bureaux Mondiaux</div>
                    </div>
                    <div class="text-center">
                        <div class="text-tertiary text-2xl font-bold">250+</div>
                        <div class="text-xs uppercase">Employés</div>
                    </div>
                    <div class="text-center">
                        <div class="text-tertiary text-2xl font-bold">Regulated</div>
                        <div class="text-xs uppercase">FCA & CySEC</div>
                    </div>
                </div>
            </div>
        `
    },
    'contact': {
        title: 'Contactez Nos Experts',
        img: 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=400&fit=crop',
        content: `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-6">
                    <h4 class="text-xl font-bold text-white">Support Technique 24/7</h4>
                    <p>Pour toute question urgente concernant votre compte ou une position ouverte, notre équipe est disponible en permanence.</p>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <span class="material-symbols-outlined text-tertiary">phone_in_talk</span>
                            <span>+33 1 70 32 00 00</span>
                        </div>
                        <div class="flex gap-4">
                            <span class="material-symbols-outlined text-tertiary">support</span>
                            <span>chat en direct via plateforme</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <h4 class="text-xl font-bold text-white">Partenariats</h4>
                    <p>Devenez un IB ou un partenaire d'affiliation et bénéficiez d'un schéma de rémunération compétitif.</p>
                    <a href="#" class="inline-block text-tertiary font-bold hover:underline">En savoir plus sur les partenariats →</a>
                </div>
            </div>
        `
    },
    'conditions': {
        title: 'Conditions d\'utilisation',
        img: 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&h=400&fit=crop',
        content: `
            <div class="prose prose-invert max-w-none text-sm space-y-4">
                <h4 class="text-white">1. Acceptation des termes</h4>
                <p>En accédant au site XFT, vous acceptez d'être lié par les présentes conditions. Le trading comporte des risques significatifs.</p>
                <h4 class="text-white">2. Services fournis</h4>
                <p>XFT fournit des services d'exécution uniquement. Nous ne donnons aucun conseil en investissement personnalisé.</p>
                <h4 class="text-white">3. Éligibilité</h4>
                <p>Vous devez avoir plus de 18 ans et résider dans une juridiction où nos services sont autorisés.</p>
            </div>
        `
    },
    'cookies': {
        title: 'Cookies Policies',
        img: 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&h=400&fit=crop',
        content: `
            <div class="space-y-4">
                <p>Nous utilisons des cookies pour améliorer votre expérience de trading et assurer la sécurité de vos sessions.</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Cookies Essentiels :</strong> Nécessaires au fonctionnement de la plateforme.</li>
                    <li><strong>Cookies Analytiques :</strong> Nous aident à comprendre comment vous utilisez notre site.</li>
                    <li><strong>Cookies de Performance :</strong> Optimisent la vitesse de chargement des graphiques.</li>
                </ul>
            </div>
        `
    },
    'risk': {
        title: 'Avertissement sur les risques',
        img: 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&h=400&fit=crop',
        content: `
            <div class="space-y-4 text-sm">
                <p class="text-error font-bold">⚠️ Le trading avec levier comporte un risque élevé</p>
                <p>Le trading de devises et de CFD comporte un niveau de risque élevé et peut ne pas être adapté à tous les investisseurs. Les pertes peuvent être supérieures au capital investi.</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Vous pouvez perdre plus que votre dépôt initial</li>
                    <li>L'effet de levier amplifie les gains ET les pertes</li>
                    <li>Le trading ne convient pas à tous</li>
                </ul>
            </div>
        `
    }
};

function openModal(type) {
    const data = modalData[type];
    if (!data) return;

    document.getElementById('modalTitle').innerText = data.title;
    document.getElementById('modalHeroImg').style.backgroundImage = `url('${data.img}')`;
    document.getElementById('modalBody').innerHTML = data.content;
    
    const modal = document.getElementById('globalModal');
    modal.classList.add('modal-active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('globalModal');
    modal.classList.remove('modal-active');
    document.body.style.overflow = '';
}

window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
});

document.getElementById('globalModal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('globalModal')) closeModal();
});
</script>

</body>
</html>
