<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Puprime Fox - Trading & Investment Platform</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&amp;family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-high": "#232b2c",
                        "surface-variant": "#2e3637",
                        "outline": "#849495",
                        "outline-variant": "#3a494b",
                        "surface-bright": "#333b3b",
                        "on-secondary-container": "#500050",
                        "on-tertiary-fixed-variant": "#474646",
                        "on-error": "#690005",
                        "primary": "#e1fdff",
                        "primary-container": "#00f2ff",
                        "on-background": "#dce4e4",
                        "secondary-container": "#fe00fe",
                        "primary-fixed": "#74f5ff",
                        "surface-tint": "#00dbe7",
                        "secondary-fixed-dim": "#ffabf3",
                        "secondary-fixed": "#ffd7f5",
                        "primary-fixed-dim": "#00dbe7",
                        "tertiary-container": "#dddad9",
                        "on-primary-container": "#006a71",
                        "background": "#0d1515",
                        "tertiary-fixed": "#e5e2e1",
                        "on-primary-fixed-variant": "#004f54",
                        "on-tertiary": "#313030",
                        "inverse-on-surface": "#2a3232",
                        "on-primary": "#00363a",
                        "error": "#ffb4ab",
                        "on-secondary-fixed": "#380038",
                        "inverse-primary": "#00696f",
                        "surface-container-lowest": "#080f10",
                        "on-error-container": "#ffdad6",
                        "on-secondary-fixed-variant": "#810081",
                        "on-primary-fixed": "#002022",
                        "on-tertiary-container": "#615f5f",
                        "surface-container-highest": "#2e3637",
                        "on-surface": "#dce4e4",
                        "tertiary-fixed-dim": "#c9c6c5",
                        "error-container": "#93000a",
                        "on-tertiary-fixed": "#1c1b1b",
                        "surface": "#0d1515",
                        "on-surface-variant": "#b9cacb",
                        "surface-dim": "#0d1515",
                        "surface-container": "#192122",
                        "on-secondary": "#5b005b",
                        "tertiary": "#faf6f6",
                        "secondary": "#ffabf3",
                        "surface-container-low": "#151d1e",
                        "inverse-surface": "#dce4e4"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "20px",
                        "gutter": "24px",
                        "unit": "4px",
                        "column-gap": "32px",
                        "margin-desktop": "64px"
                    },
                    "fontFamily": {
                        "body-lg": ["Roboto"],
                        "headline-md": ["Poppins"],
                        "data-mono": ["Roboto"],
                        "headline-lg-mobile": ["Poppins"],
                        "body-md": ["Roboto"],
                        "headline-xl": ["Poppins"],
                        "label-caps": ["Poppins"],
                        "headline-lg": ["Poppins"]
                    },
                    "fontSize": {
                        "body-lg": ["18px", { "lineHeight": "1.6", "fontWeight": "400" }],
                        "headline-md": ["24px", { "lineHeight": "1.4", "fontWeight": "600" }],
                        "data-mono": ["14px", { "lineHeight": "1.2", "letterSpacing": "0.05em", "fontWeight": "500" }],
                        "headline-lg-mobile": ["32px", { "lineHeight": "1.2", "fontWeight": "700" }],
                        "body-md": ["16px", { "lineHeight": "1.5", "fontWeight": "400" }],
                        "headline-xl": ["64px", { "lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "800" }],
                        "label-caps": ["12px", { "lineHeight": "1.0", "letterSpacing": "0.1em", "fontWeight": "700" }],
                        "headline-lg": ["40px", { "lineHeight": "1.2", "letterSpacing": "-0.01em", "fontWeight": "700" }]
                    }
                }
            }
        }
    </script>
<style>
        .clip-polygon { clip-path: polygon(10% 0, 100% 0, 90% 100%, 0% 100%); }
        .clip-hexagon { clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%); }
        .clip-diamond { clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%); }
        .clip-triangle { clip-path: polygon(50% 0%, 0% 100%, 100% 100%); }
        .clip-octagon { clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%); }
        .glass-panel { background: rgba(20, 20, 20, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(0, 219, 231, 0.1); }
        .glass-panel:hover { border-color: rgba(0, 219, 231, 1); box-shadow: 0 0 10px rgba(0, 219, 231, 0.5); }
        .btn-glow { transition: all 0.3s ease; }
        .btn-glow:hover { box-shadow: 0 0 15px rgba(0, 219, 231, 0.8); }
        .modal-hidden { display: none; }
        .modal-visible { display: flex; }

        /* Google Translate masqué par défaut */
        .goog-te-banner-frame { display: none !important; }
        body { top: 0 !important; }
        .skiptranslate { display: none !important; }
        #google_translate_element { display: none !important; }
        #google_translate_element.active { display: block !important; }

        /* TradingView custom dark theme */
        .tradingview-widget-container {
            background: #0d1515 !important;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .tradingview-widget-container iframe {
            background: #0d1515 !important;
        }

        /* Mobile fixes */
        @media (max-width: 768px) {
            .hero-title { font-size: 36px !important; line-height: 1.1 !important; }
            .hero-subtitle { font-size: 16px !important; }
            .section-title { font-size: 28px !important; line-height: 1.2 !important; }
            .mobile-stack { flex-direction: column !important; }
            .mobile-full { width: 100% !important; }
            .mobile-text-center { text-align: center !important; }
            .mobile-p-4 { padding: 16px !important; }
            .mobile-gap-2 { gap: 8px !important; }
            .mobile-clip-none { clip-path: none !important; }
            .mobile-hidden { display: none !important; }
            .tradingview-widget-container { height: 300px !important; }
            .mobile-grid-1 { grid-template-columns: 1fr !important; }
        }

        @media (max-width: 640px) {
            .px-margin-mobile { padding-left: 16px !important; padding-right: 16px !important; }
            .px-margin-desktop { padding-left: 16px !important; padding-right: 16px !important; }
            .max-w-7xl { max-width: 100%; }
            section img { max-width: 100%; height: auto; }
            .gap-column-gap { gap: 16px !important; }
            .grid-cols-3 { grid-template-columns: 1fr !important; }
            .grid-cols-4 { grid-template-columns: 1fr !important; }
        }

        @media (max-width: 480px) {
            .px-margin-mobile { padding-left: 12px !important; padding-right: 12px !important; }
            .px-margin-desktop { padding-left: 12px !important; padding-right: 12px !important; }
            section { padding-top: 12px !important; padding-bottom: 12px !important; }
            .py-16 { padding-top: 12px !important; padding-bottom: 12px !important; }
            .py-24 { padding-top: 16px !important; padding-bottom: 16px !important; }
            .gap-column-gap { gap: 12px !important; }
            .hero-title { font-size: 28px !important; }
            .section-title { font-size: 24px !important; }
            h3 { font-size: 18px !important; }
            .btn-glow { padding: 12px 20px !important; font-size: 11px !important; }
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md antialiased overflow-x-hidden selection:bg-primary-container selection:text-on-primary-container">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-surface/70 backdrop-blur-xl border-b border-primary/20 shadow-[0_0_15px_rgba(0,219,231,0.1)] flex justify-between items-center px-4 md:px-16 h-20">
<div class="font-headline-md text-headline-md font-bold text-primary tracking-tighter">Puprime Fox</div>
<div class="hidden md:flex gap-4 items-center">
<div class="flex gap-6 items-center mr-4">
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors hover:bg-primary/10 px-3 py-2" data-modal="why-us">Pourquoi Nous</button>
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors hover:bg-primary/10 px-3 py-2" data-modal="platforms">Plateformes</button>
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors hover:bg-primary/10 px-3 py-2" data-modal="markets">Marchés</button>
<a class="font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors hover:bg-primary/10 px-3 py-2" href="#education">Éducation</a>
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors hover:bg-primary/10 px-3 py-2" data-modal="about">À Propos</button>
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors hover:bg-primary/10 px-3 py-2" data-modal="faq">FAQ</button>
</div>
<div class="flex items-center gap-2 border-l border-primary/20 pl-4 mr-4">
<button id="lang-en" class="font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors px-1">EN</button>
<span class="text-primary/30 text-xs">|</span>
<button id="lang-fr" class="font-label-caps text-label-caps text-primary px-1 font-bold">FR</button>
</div>
<!-- Google Translate - caché par défaut -->
<div id="google_translate_element"></div>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en', 
    includedLanguages: 'en,fr',
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
  }, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<a href="/login" class="bg-primary text-on-primary font-label-caps text-label-caps px-6 py-3 clip-polygon btn-glow hover:bg-primary-container uppercase">Connexion</a>
<a href="/register" class="bg-primary text-on-primary font-label-caps text-label-caps px-6 py-3 clip-polygon btn-glow hover:bg-primary-container uppercase">S'inscrire</a>
</div>
<!-- Mobile Menu Button -->
<button id="mobile-menu-btn" class="md:hidden flex items-center text-primary">
<span class="material-symbols-outlined text-2xl">menu</span>
</button>
</nav>
<!-- Mobile Menu -->
<div id="mobile-menu" class="fixed top-20 left-0 w-full bg-surface/95 backdrop-blur-xl border-b border-primary/20 md:hidden modal-hidden flex-col py-4 px-4 z-40">
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors py-2 text-left" data-modal="why-us">Pourquoi Nous</button>
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors py-2 text-left" data-modal="platforms">Plateformes</button>
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors py-2 text-left" data-modal="markets">Marchés</button>
<a class="font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors py-2" href="#education">Éducation</a>
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors py-2 text-left" data-modal="about">À Propos</button>
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors py-2 text-left" data-modal="faq">FAQ</button>
<div class="border-t border-primary/20 mt-4 pt-4 flex flex-col gap-2">
<a href="/login" class="bg-primary text-on-primary font-label-caps text-label-caps px-6 py-2 text-center uppercase">Connexion</a>
<a href="/register" class="bg-primary text-on-primary font-label-caps text-label-caps px-6 py-2 text-center uppercase">S'inscrire</a>
</div>
</div>
<main class="pt-20">
<!-- 1. Hero Section -->
<section class="h-auto md:h-[500px] max-w-7xl mx-auto px-4 md:px-16 flex flex-col md:flex-row items-center gap-8 mt-12 mb-12">
<div class="flex-1 w-full">
<h1 class="hero-title font-headline-xl text-headline-xl text-primary mb-6">Trade Global Markets</h1>
<p class="hero-subtitle font-body-lg text-body-lg text-on-surface-variant mb-8">Access Forex, Commodities, Stocks & More. Low Spreads. Reliable Execution. 24/7 Trading.</p>
<div class="flex flex-col md:flex-row gap-4">
<a href="/login" class="bg-transparent border-2 border-primary text-primary font-label-caps text-label-caps px-8 py-4 clip-polygon hover:bg-primary/10 transition-all uppercase text-center">Connexion</a>
<a href="/register" class="bg-primary text-on-primary font-label-caps text-label-caps px-8 py-4 clip-polygon btn-glow uppercase text-center">Démarrer Gratuitement</a>
</div>
</div>
<div class="flex-1 h-full relative w-full">
<div class="absolute inset-0 bg-gradient-to-r from-background to-transparent z-10 w-24"></div>
<img alt="Professional trading dashboard" class="w-full h-full object-cover clip-polygon opacity-80 border border-primary/30" src="https://puprime-fox.it.com/66520.jpg"/>
</div>
</section>

<!-- 2. Market Pulse - TradingView Custom Dark -->
<section class="w-full py-16 bg-surface-container/30">
<div class="max-w-7xl mx-auto px-4 md:px-16">
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
<div>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Ultra-Low Latency Execution</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-6">Our infrastructure ensures your orders are executed within milliseconds. Trade with confidence on volatile markets without slippage.</p>
<a href="/register" class="bg-primary text-on-primary font-label-caps text-label-caps px-8 py-3 clip-polygon btn-glow inline-block uppercase">Start Trading Now</a>
</div>
<div class="glass-panel p-4 w-full">
<h3 class="font-label-caps text-label-caps text-primary mb-4 border-b border-primary/20 pb-2">Live Market Data</h3>
<!-- TradingView Widget Custom Dark -->
<div class="tradingview-widget-container w-full" style="height: 450px;">
  <div class="tradingview-widget-container__widget w-full h-full"></div>
  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async>
  {
    "showChart": true,
    "locale": "en",
    "largeChartUrl": "",
    "isTransparent": true,
    "colorTheme": "dark",
    "width": "100%",
    "height": "100%",
    "symbols": [
      {"s": "FX_IDC:EURUSD", "d": "EUR/USD"},
      {"s": "BITSTAMP:BTCUSD", "d": "Bitcoin"},
      {"s": "TVC:GOLD", "d": "Gold"},
      {"s": "TVC:USOIL", "d": "Crude Oil"},
      {"s": "INDEX:GSPC", "d": "S&P 500"}
    ],
    "showSymbolLogo": true,
    "showFloatingTooltip": true,
    "plotLineColorGrowing": "#00f2ff",
    "plotLineColorFalling": "#ff6b6b",
    "gridLineColor": "rgba(0, 219, 231, 0.05)",
    "scaleFontColor": "#849495",
    "belowLineFillColorGrowing": "rgba(0, 242, 255, 0.1)",
    "belowLineFillColorFalling": "rgba(255, 107, 107, 0.1)",
    "symbolActiveColor": "rgba(0, 242, 255, 0.15)"
  }
  </script>
</div>
<p class="font-body-md text-body-md text-on-surface-variant text-sm text-center mt-2">Real-time quotes powered by TradingView</p>
</div>
</div>
</div>
</section>

<!-- 3. Copy Trading -->
<section class="max-w-7xl mx-auto px-4 md:px-16 py-16 grid grid-cols-1 md:grid-cols-2 gap-8 items-center bg-surface-container/50">
<div>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Copy Trading Platform</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-6">Replicate strategies from top-performing traders automatically. Filter by risk metrics, drawdown, and historical performance.</p>
<a href="/register" class="bg-primary text-on-primary font-label-caps text-label-caps px-8 py-3 clip-polygon btn-glow inline-block uppercase">Try Copy Trading</a>
</div>
<div class="relative flex justify-center">
<img alt="Trading dashboard" class="w-full max-w-[400px] h-auto object-cover clip-hexagon border-2 border-primary/40 glass-panel" src="https://puprime-fox.it.com/885.jpg"/>
</div>
</section>

<!-- 4. Intelligent Accounts -->
<section class="max-w-7xl mx-auto px-4 md:px-16 py-16 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
<div class="relative flex justify-center order-2 md:order-1">
<img alt="Trading platform" class="w-full max-w-[400px] h-auto object-cover clip-diamond border-2 border-secondary-container/40" src="https://puprime-fox.it.com/66520.jpg"/>
</div>
<div class="order-1 md:order-2">
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-6">Account Types for Every Trader</h2>
<div class="space-y-6">
<div class="glass-panel p-4 border-l-4 border-l-primary">
<h4 class="font-headline-md text-headline-md text-on-surface">Standard Account</h4>
<p class="font-body-md text-body-md text-on-surface-variant text-sm mt-2">Minimum deposit $100. Perfect for beginners. Access to Forex, Commodities and basic trading tools.</p>
</div>
<div class="glass-panel p-4 border-l-4 border-l-secondary-container">
<h4 class="font-headline-md text-headline-md text-on-surface">Pro Account</h4>
<p class="font-body-md text-body-md text-on-surface-variant text-sm mt-2">Minimum deposit $1,000. Lower spreads. Advanced API access. Priority customer support.</p>
</div>
</div>
</div>
</section>

<!-- 5. Fortified Security -->
<section id="security" class="max-w-7xl mx-auto px-4 md:px-16 py-16 grid grid-cols-1 md:grid-cols-2 gap-8 items-center bg-surface-container/50">
<div>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Bank-Grade Security</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-6">Your funds are protected by multiple layers of security. Multi-signature cold storage. SSL encryption. Real-time fraud detection.</p>
<a href="/register" class="bg-primary text-on-primary font-label-caps text-label-caps px-8 py-3 clip-polygon btn-glow inline-block uppercase">Trade Safely</a>
</div>
<div class="flex justify-center items-center h-[300px]">
<div class="w-[200px] h-[200px] bg-primary/10 clip-triangle flex items-center justify-center border border-primary/50 relative">
<span class="material-symbols-outlined text-[80px] text-primary" style="font-variation-settings: 'FILL' 1;">shield</span>
<div class="absolute inset-0 bg-primary/20 blur-xl rounded-full mix-blend-screen"></div>
</div>
</div>
</section>

<!-- 6. Trading Academy -->
<section id="education" class="max-w-7xl mx-auto px-4 md:px-16 py-16 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
<div class="relative flex justify-center">
<img alt="Professional trader" class="w-full max-w-[400px] h-auto object-cover clip-octagon border-2 border-primary/30 opacity-70" src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=500&h=500&fit=crop"/>
<div class="absolute inset-0 flex items-center justify-center">
<span class="material-symbols-outlined text-[64px] text-primary drop-shadow-[0_0_10px_rgba(0,219,231,0.8)] cursor-pointer hover:scale-110 transition-transform">play_circle</span>
</div>
</div>
<div>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Free Trading Education</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-6">Master the markets with our comprehensive educational resources. From beginner basics to advanced strategies.</p>
<a href="/register" class="bg-primary text-on-primary font-label-caps text-label-caps px-8 py-3 clip-polygon btn-glow inline-block uppercase">Learn Now</a>
</div>
</section>

<!-- 7. Trading Platforms -->
<section class="max-w-7xl mx-auto px-4 md:px-16 py-16">
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<div class="glass-panel p-8">
<div class="mb-4 flex justify-center">
<span class="material-symbols-outlined text-[60px] text-primary">assessment</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface text-center mb-3">MetaTrader 5</h3>
<p class="font-body-md text-body-md text-on-surface-variant text-center mb-6">Advanced charting tools. Sophisticated algorithms. Premium analytics.</p>
<a href="/register" class="bg-primary text-on-primary font-label-caps text-label-caps px-6 py-2 clip-polygon btn-glow text-center block w-full uppercase">Télécharger MT5</a>
</div>
<div class="glass-panel p-8">
<div class="mb-4 flex justify-center">
<span class="material-symbols-outlined text-[60px] text-primary">trending_up</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface text-center mb-3">MetaTrader 4</h3>
<p class="font-body-md text-body-md text-on-surface-variant text-center mb-6">Reliable. Proven. Trusted by millions. The gold standard for forex trading.</p>
<a href="/register" class="bg-primary text-on-primary font-label-caps text-label-caps px-6 py-2 clip-polygon btn-glow text-center block w-full uppercase">Télécharger MT4</a>
</div>
<div class="glass-panel p-8">
<div class="mb-4 flex justify-center">
<span class="material-symbols-outlined text-[60px] text-primary">dashboard</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface text-center mb-3">Puprime Trader</h3>
<p class="font-body-md text-body-md text-on-surface-variant text-center mb-6">Our proprietary platform. Sleek interface. Optimized for speed.</p>
<a href="/register" class="bg-primary text-on-primary font-label-caps text-label-caps px-6 py-2 clip-polygon btn-glow text-center block w-full uppercase">Accéder à la Plateforme</a>
</div>
</div>
</section>

<!-- 8. Getting Started Section -->
<section class="max-w-7xl mx-auto px-4 md:px-16 py-24 bg-surface-container/30">
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-12 text-center">Commencez en 4 Étapes Simples</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
<div class="relative text-center">
<div class="flex items-center justify-center h-20 mb-4">
<div class="w-20 h-20 bg-gradient-to-br from-primary to-primary-container clip-polygon flex items-center justify-center">
<span class="font-headline-md text-headline-md text-on-primary font-bold">1</span>
</div>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Créer un Compte</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Inscrivez-vous gratuitement en moins de 5 minutes.</p>
</div>
<div class="relative text-center">
<div class="flex items-center justify-center h-20 mb-4">
<div class="w-20 h-20 bg-gradient-to-br from-primary to-primary-container clip-polygon flex items-center justify-center">
<span class="font-headline-md text-headline-md text-on-primary font-bold">2</span>
</div>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Vérifier l'Identité</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Téléchargez vos documents pour la vérification KYC.</p>
</div>
<div class="relative text-center">
<div class="flex items-center justify-center h-20 mb-4">
<div class="w-20 h-20 bg-gradient-to-br from-primary to-primary-container clip-polygon flex items-center justify-center">
<span class="font-headline-md text-headline-md text-on-primary font-bold">3</span>
</div>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Effectuer un Dépôt</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Déposez des fonds via virement, carte ou portefeuille numérique.</p>
</div>
<div class="relative text-center">
<div class="flex items-center justify-center h-20 mb-4">
<div class="w-20 h-20 bg-gradient-to-br from-primary to-primary-container clip-polygon flex items-center justify-center">
<span class="font-headline-md text-headline-md text-on-primary font-bold">4</span>
</div>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Commencer à Trader</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Accédez à nos plateformes et commencez à trader.</p>
</div>
</div>
<div class="mt-12 text-center">
<a href="/register" class="bg-primary text-on-primary font-headline-md text-headline-md px-12 py-6 clip-polygon btn-glow uppercase tracking-wide inline-block">S'inscrire Maintenant</a>
</div>
</section>

<!-- SECTION RÉINTÉGRÉE : CTA Final -->
<section class="max-w-7xl mx-auto px-4 md:px-16 py-16 text-center">
<h2 class="section-title font-headline-xl text-headline-xl text-primary mb-8 tracking-tighter uppercase">Start Trading with Confidence</h2>
<div class="flex flex-col md:flex-row gap-6 justify-center items-center">
<a href="/register" class="bg-primary text-on-primary font-headline-md text-headline-md px-12 py-6 clip-polygon btn-glow uppercase tracking-wide inline-block">Open Your Account</a>
<a href="/register?demo=1" class="bg-transparent border-2 border-secondary-container text-secondary-container font-headline-md text-headline-md px-12 py-6 clip-polygon hover:bg-secondary-container/10 transition-all uppercase tracking-wide inline-block">Try Demo Account</a>
</div>
</section>

<!-- 9. Support Hub -->
<section id="products" class="max-w-7xl mx-auto px-4 md:px-16 py-16 grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-primary/20">
<div>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-8">Frequently Asked Questions</h2>
<div class="space-y-4">
<div class="glass-panel p-4 border-l-2 border-primary cursor-pointer hover:bg-surface-bright/50 modal-faq-item" data-modal="faq-detail">
<div class="flex justify-between items-center">
<span class="font-headline-md text-body-lg text-on-surface">What is the minimum deposit?</span>
<span class="material-symbols-outlined text-primary">expand_more</span>
</div>
</div>
<div class="glass-panel p-4 border-l-2 border-primary cursor-pointer hover:bg-surface-bright/50 modal-faq-item" data-modal="faq-detail">
<div class="flex justify-between items-center">
<span class="font-headline-md text-body-lg text-on-surface">How do I withdraw my profits?</span>
<span class="material-symbols-outlined text-primary">expand_more</span>
</div>
</div>
<div class="glass-panel p-4 border-l-2 border-primary cursor-pointer hover:bg-surface-bright/50 modal-faq-item" data-modal="faq-detail">
<div class="flex justify-between items-center">
<span class="font-headline-md text-body-lg text-on-surface">What are the trading hours?</span>
<span class="material-symbols-outlined text-primary">expand_more</span>
</div>
</div>
<div class="glass-panel p-4 border-l-2 border-primary cursor-pointer hover:bg-surface-bright/50 modal-faq-item" data-modal="faq-detail">
<div class="flex justify-between items-center">
<span class="font-headline-md text-body-lg text-on-surface">Is my account safe?</span>
<span class="material-symbols-outlined text-primary">expand_more</span>
</div>
</div>
</div>
</div>
<div>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-8">Get in Touch</h2>
<form id="contact-form" class="space-y-6 glass-panel p-8">
@csrf
<div>
<input class="w-full bg-surface-container-lowest border-b border-primary/30 text-on-surface font-data-mono text-data-mono p-3 focus:outline-none focus:border-primary focus:ring-0 placeholder-on-surface-variant/50" placeholder="Your Email" type="email" name="email" required/>
</div>
<div>
<textarea class="w-full bg-surface-container-lowest border-b border-primary/30 text-on-surface font-body-md p-3 focus:outline-none focus:border-primary focus:ring-0 placeholder-on-surface-variant/50" placeholder="Your Message" rows="4" name="message" required></textarea>
</div>
<button class="bg-primary text-on-primary font-label-caps text-label-caps px-8 py-3 clip-polygon btn-glow w-full uppercase" type="submit">Send Message</button>
</form>
<!-- Toast Notifications -->
<div id="toast-success" class="hidden fixed bottom-6 right-6 bg-secondary-container text-on-secondary px-6 py-3 rounded-lg shadow-lg font-label-caps text-label-caps animate-pulse">
✓ Message sent successfully!
</div>
<div id="toast-error" class="hidden fixed bottom-6 right-6 bg-error text-on-error px-6 py-3 rounded-lg shadow-lg font-label-caps text-label-caps">
✗ Error sending message
</div>
<div id="toast-loading" class="hidden fixed bottom-6 right-6 bg-primary text-on-primary px-6 py-3 rounded-lg shadow-lg font-label-caps text-label-caps flex items-center gap-2">
<span class="inline-block animate-spin">⏳</span> Sending...
</div>
</div>
</section>
</main>
<!-- Footer -->
<footer class="bg-surface-container-lowest w-full py-12 px-4 md:px-16 border-t border-outline-variant">
<div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-7xl mx-auto">
<div>
<div class="font-headline-md text-headline-md text-primary mb-4">Puprime Fox</div>
<p class="font-body-md text-body-md text-on-surface-variant text-sm">© 2026 Puprime Fox. Trading CFDs involves high risk.</p>
</div>
<div class="col-span-3 flex flex-col md:flex-row justify-end gap-8 items-start md:items-center text-sm">
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-all text-left md:text-center" data-modal="privacy">Privacy Policy</button>
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-all text-left md:text-center" data-modal="terms">Terms & Conditions</button>
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-all text-left md:text-center" data-modal="risk">Risk Disclosure</button>
<button class="modal-trigger font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-all text-left md:text-center" data-modal="cookies">Cookie Policy</button>
</div>
</div>
</footer>

<!-- MODALS -->
<!-- Why Us Modal -->
<div id="modal-why-us" class="modal-hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] items-center justify-center p-4 modal">
<div class="glass-panel max-w-2xl w-full max-h-[80vh] overflow-y-auto p-8 relative">
<button class="close-modal absolute top-4 right-4 text-primary hover:text-primary-container" data-modal="why-us">
<span class="material-symbols-outlined">close</span>
</button>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Pourquoi Choisir Puprime Fox</h2>
<div class="space-y-4">
<div class="flex gap-4">
<span class="material-symbols-outlined text-primary text-2xl flex-shrink-0">check_circle</span>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-1">Ultra-Faible Latence</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Infrastructure colocalisée. Exécution en microsecondes.</p>
</div>
</div>
<div class="flex gap-4">
<span class="material-symbols-outlined text-primary text-2xl flex-shrink-0">check_circle</span>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-1">Spreads Compétitifs</h3>
<p class="font-body-md text-body-md text-on-surface-variant">À partir de 0.0 pips sur les paires majeures.</p>
</div>
</div>
<div class="flex gap-4">
<span class="material-symbols-outlined text-primary text-2xl flex-shrink-0">check_circle</span>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-1">Sécurité de Banque</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Multi-signature cold storage. SSL 256-bit.</p>
</div>
</div>
<div class="flex gap-4">
<span class="material-symbols-outlined text-primary text-2xl flex-shrink-0">check_circle</span>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-1">Support 24/7</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Équipe multilingue. Réponse en moins de 5 minutes.</p>
</div>
</div>
<div class="flex gap-4">
<span class="material-symbols-outlined text-primary text-2xl flex-shrink-0">check_circle</span>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-1">Éducation Gratuite</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Webinaires hebdomadaires. Tutoriels vidéo.</p>
</div>
</div>
<div class="flex gap-4">
<span class="material-symbols-outlined text-primary text-2xl flex-shrink-0">check_circle</span>
<div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-1">Copy Trading</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Répliquez les meilleurs traders. Commencez avec $250.</p>
</div>
</div>
</div>
<a href="/register" class="bg-primary text-on-primary font-label-caps text-label-caps px-8 py-3 clip-polygon btn-glow w-full text-center block mt-8 uppercase">Rejoindre Maintenant</a>
</div>
</div>

<!-- Platforms Modal -->
<div id="modal-platforms" class="modal-hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] items-center justify-center p-4 modal">
<div class="glass-panel max-w-2xl w-full max-h-[80vh] overflow-y-auto p-8 relative">
<button class="close-modal absolute top-4 right-4 text-primary hover:text-primary-container" data-modal="platforms">
<span class="material-symbols-outlined">close</span>
</button>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Nos Plateformes de Trading</h2>
<div class="space-y-6">
<div class="border-b border-primary/20 pb-4">
<div class="flex justify-between items-center mb-2">
<h3 class="font-headline-md text-headline-md text-on-surface">MetaTrader 5</h3>
<span class="text-primary font-label-caps text-label-caps">Avancé</span>
</div>
<p class="font-body-md text-body-md text-on-surface-variant mb-3">La plateforme la plus puissante pour l'analyse technique avancée.</p>
<ul class="list-disc list-inside text-on-surface-variant text-sm space-y-1">
<li>38 paires de devises</li>
<li>Matière première spot</li>
<li>Futures</li>
<li>Scripts personnalisés</li>
<li>Backtesting avancé</li>
</ul>
</div>
<div class="border-b border-primary/20 pb-4">
<div class="flex justify-between items-center mb-2">
<h3 class="font-headline-md text-headline-md text-on-surface">MetaTrader 4</h3>
<span class="text-primary font-label-caps text-label-caps">Classique</span>
</div>
<p class="font-body-md text-body-md text-on-surface-variant mb-3">L'étalon-or du trading forex. Stable. Fiable.</p>
<ul class="list-disc list-inside text-on-surface-variant text-sm space-y-1">
<li>Expert Advisors (EAs)</li>
<li>50+ indicateurs built-in</li>
<li>Trading automatisé</li>
<li>Support mobile</li>
</ul>
</div>
<div class="pb-4">
<div class="flex justify-between items-center mb-2">
<h3 class="font-headline-md text-headline-md text-on-surface">Puprime Trader</h3>
<span class="text-primary font-label-caps text-label-caps">Propriétaire</span>
</div>
<p class="font-body-md text-body-md text-on-surface-variant mb-3">Notre plateforme innovante. Interface épurée.</p>
<ul class="list-disc list-inside text-on-surface-variant text-sm space-y-1">
<li>Interface intuitive</li>
<li>Exécution ultra-rapide</li>
<li>Copy Trading intégré</li>
<li>Mobile & Desktop</li>
</ul>
</div>
</div>
</div>
</div>

<!-- Markets Modal -->
<div id="modal-markets" class="modal-hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] items-center justify-center p-4 modal">
<div class="glass-panel max-w-2xl w-full max-h-[80vh] overflow-y-auto p-8 relative">
<button class="close-modal absolute top-4 right-4 text-primary hover:text-primary-container" data-modal="markets">
<span class="material-symbols-outlined">close</span>
</button>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Marchés Disponibles</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
<div>
<h3 class="font-headline-md text-headline-md text-primary mb-3">Forex</h3>
<p class="font-body-md text-body-md text-on-surface-variant text-sm mb-2">Principales paires (EUR/USD, GBP/USD...). Spreads ultra-bas.</p>
</div>
<div>
<h3 class="font-headline-md text-headline-md text-primary mb-3">Indices</h3>
<p class="font-body-md text-body-md text-on-surface-variant text-sm mb-2">S&P 500, NASDAQ, DAX, CAC40, Nikkei.</p>
</div>
<div>
<h3 class="font-headline-md text-headline-md text-primary mb-3">Matières Premières</h3>
<p class="font-body-md text-body-md text-on-surface-variant text-sm mb-2">Or, Argent, Pétrole, Gaz naturel.</p>
</div>
<div>
<h3 class="font-headline-md text-headline-md text-primary mb-3">Actions</h3>
<p class="font-body-md text-body-md text-on-surface-variant text-sm mb-2">1,000+ actions. Google, Apple, Tesla, Amazon.</p>
</div>
<div>
<h3 class="font-headline-md text-headline-md text-primary mb-3">Crypto-Monnaies</h3>
<p class="font-body-md text-body-md text-on-surface-variant text-sm mb-2">Bitcoin, Ethereum, Litecoin. Trading 24/7.</p>
</div>
<div>
<h3 class="font-headline-md text-headline-md text-primary mb-3">Obligations</h3>
<p class="font-body-md text-body-md text-on-surface-variant text-sm mb-2">Obligations d'État. Rendements fixes.</p>
</div>
</div>
<p class="font-body-md text-body-md text-on-surface-variant mt-6 text-center">Plus de 1,000 instruments tradables.</p>
</div>
</div>

<!-- About Modal -->
<div id="modal-about" class="modal-hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] items-center justify-center p-4 modal">
<div class="glass-panel max-w-2xl w-full max-h-[80vh] overflow-y-auto p-8 relative">
<button class="close-modal absolute top-4 right-4 text-primary hover:text-primary-container" data-modal="about">
<span class="material-symbols-outlined">close</span>
</button>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">About Puprime Fox</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">Puprime Fox is a trusted online trading platform serving traders worldwide.</p>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">Founded with a commitment to transparency and reliability, we offer:</p>
<ul class="list-disc list-inside space-y-2 text-on-surface-variant mb-4">
<li>Ultra-low latency execution</li>
<li>Competitive spreads from 0.0 pips</li>
<li>Copy trading from professional traders</li>
<li>24/7 customer support</li>
<li>Bank-grade security</li>
</ul>
<p class="font-body-md text-body-md text-on-surface-variant">Trade with confidence on the platform trusted by 450,000+ traders globally.</p>
</div>
</div>

<!-- Privacy Modal -->
<div id="modal-privacy" class="modal-hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] items-center justify-center p-4 modal">
<div class="glass-panel max-w-2xl w-full max-h-[80vh] overflow-y-auto p-8 relative">
<button class="close-modal absolute top-4 right-4 text-primary hover:text-primary-container" data-modal="privacy">
<span class="material-symbols-outlined">close</span>
</button>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Privacy Policy</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4"><strong>Last Updated: May 2026</strong></p>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Information We Collect</h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">We collect personal information necessary to provide our trading services.</p>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">How We Use Your Data</h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">Your data is used to process transactions and provide customer support.</p>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Data Security</h3>
<p class="font-body-md text-body-md text-on-surface-variant">We employ SSL encryption and multi-layer security measures.</p>
</div>
</div>

<!-- Terms Modal -->
<div id="modal-terms" class="modal-hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] items-center justify-center p-4 modal">
<div class="glass-panel max-w-2xl w-full max-h-[80vh] overflow-y-auto p-8 relative">
<button class="close-modal absolute top-4 right-4 text-primary hover:text-primary-container" data-modal="terms">
<span class="material-symbols-outlined">close</span>
</button>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Terms & Conditions</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4"><strong>Last Updated: May 2026</strong></p>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">1. Account Creation</h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">You must be at least 18 years old to use our platform.</p>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">2. Trading Responsibility</h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">All trading decisions are yours. We are not responsible for your losses.</p>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">3. Fees and Commissions</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Check our pricing page for current spreads and fees.</p>
</div>
</div>

<!-- Risk Disclosure Modal -->
<div id="modal-risk" class="modal-hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] items-center justify-center p-4 modal">
<div class="glass-panel max-w-2xl w-full max-h-[80vh] overflow-y-auto p-8 relative">
<button class="close-modal absolute top-4 right-4 text-primary hover:text-primary-container" data-modal="risk">
<span class="material-symbols-outlined">close</span>
</button>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Risk Disclosure</h2>
<p class="font-body-md text-body-md text-error font-bold mb-4">⚠️ WARNING: Trading CFDs involves substantial risk of loss</p>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">CFD trading is highly risky. You may lose more than your initial investment.</p>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Key Risks:</h3>
<ul class="list-disc list-inside space-y-2 text-on-surface-variant mb-4">
<li>Leverage amplifies both profits and losses</li>
<li>Market volatility can cause rapid losses</li>
<li>Past performance doesn't guarantee future results</li>
</ul>
<p class="font-body-md text-body-md text-on-surface-variant">Please read our full risk disclosure before trading.</p>
</div>
</div>

<!-- Cookies Modal -->
<div id="modal-cookies" class="modal-hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] items-center justify-center p-4 modal">
<div class="glass-panel max-w-2xl w-full max-h-[80vh] overflow-y-auto p-8 relative">
<button class="close-modal absolute top-4 right-4 text-primary hover:text-primary-container" data-modal="cookies">
<span class="material-symbols-outlined">close</span>
</button>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Cookie Policy</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4"><strong>Last Updated: May 2026</strong></p>
<p class="font-body-md text-body-md text-on-surface-variant mb-4">We use cookies to enhance your browsing experience.</p>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Types of Cookies:</h3>
<ul class="list-disc list-inside space-y-2 text-on-surface-variant mb-4">
<li><strong>Essential:</strong> Required for platform functionality</li>
<li><strong>Performance:</strong> Help us understand how you use our platform</li>
<li><strong>Marketing:</strong> Used for targeted advertising</li>
</ul>
<p class="font-body-md text-body-md text-on-surface-variant">You can control cookie settings in your browser preferences.</p>
</div>
</div>

<!-- FAQ Detail Modal -->
<div id="modal-faq-detail" class="modal-hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] items-center justify-center p-4 modal">
<div class="glass-panel max-w-2xl w-full max-h-[80vh] overflow-y-auto p-8 relative">
<button class="close-modal absolute top-4 right-4 text-primary hover:text-primary-container" data-modal="faq-detail">
<span class="material-symbols-outlined">close</span>
</button>
<h2 class="section-title font-headline-lg text-headline-lg text-primary mb-4">Frequently Asked Questions</h2>
<div class="space-y-6">
<div class="pb-4 border-b border-primary/20">
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">What is the minimum deposit?</h3>
<p class="font-body-md text-body-md text-on-surface-variant">The minimum deposit is $100 for Standard accounts and $1,000 for Pro accounts.</p>
</div>
<div class="pb-4 border-b border-primary/20">
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">How do I withdraw my profits?</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Withdrawals are processed within 1-3 business days. Minimum withdrawal is $50.</p>
</div>
<div class="pb-4 border-b border-primary/20">
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">What are the trading hours?</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Forex: 24/5. Stocks: market hours. Crypto: 24/7.</p>
</div>
<div class="pb-4 border-b border-primary/20">
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Is my account safe?</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Yes. Military-grade SSL, 2FA, and segregated funds up to $500,000.</p>
</div>
<div class="pb-4 border-b border-primary/20">
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Which platforms should I use?</h3>
<p class="font-body-md text-body-md text-on-surface-variant">MT5 for advanced, MT4 for forex, Puprime Trader for speed.</p>
</div>
<div class="pb-4 border-b border-primary/20">
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Can I use copy trading?</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Yes. Start with $250. Adjust or stop copying anytime.</p>
</div>
<div class="pb-4 border-b border-primary/20">
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">What are the spreads?</h3>
<p class="font-body-md text-body-md text-on-surface-variant">From 0.0 pips on major pairs (Pro). From 1.0 pips (Standard).</p>
</div>
<div class="pb-4">
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Do you offer a demo account?</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Yes. $100,000 virtual funds. No credit card required.</p>
</div>
</div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Mobile menu toggle
  const mobileMenuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', function() {
      mobileMenu.classList.toggle('modal-hidden');
      mobileMenu.classList.toggle('modal-visible');
    });
  }

  // Modal triggers
  document.querySelectorAll('.modal-trigger').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const modalId = this.getAttribute('data-modal');
      const modal = document.getElementById('modal-' + modalId);
      if (modal) {
        modal.classList.remove('modal-hidden');
        modal.classList.add('modal-visible');
        if (mobileMenu) {
          mobileMenu.classList.add('modal-hidden');
          mobileMenu.classList.remove('modal-visible');
        }
      }
    });
  });

  // Close modals
  document.querySelectorAll('.close-modal').forEach(btn => {
    btn.addEventListener('click', function() {
      const modalId = this.getAttribute('data-modal');
      const modal = document.getElementById('modal-' + modalId);
      if (modal) {
        modal.classList.add('modal-hidden');
        modal.classList.remove('modal-visible');
      }
    });
  });

  // Close modal when clicking outside
  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
      if (e.target === this) {
        this.classList.add('modal-hidden');
        this.classList.remove('modal-visible');
      }
    });
  });

  // FAQ items click handler
  document.querySelectorAll('.modal-faq-item').forEach(item => {
    item.addEventListener('click', function() {
      const modal = document.getElementById('modal-faq-detail');
      if (modal) {
        modal.classList.remove('modal-hidden');
        modal.classList.add('modal-visible');
      }
    });
  });

  // Language Toggle - Google Translate (masqué par défaut)
  const langEnBtn = document.getElementById('lang-en');
  const langFrBtn = document.getElementById('lang-fr');
  const googleTranslateEl = document.getElementById('google_translate_element');
  let translateVisible = false;
  
  function toggleTranslate() {
    translateVisible = !translateVisible;
    if (translateVisible) {
      googleTranslateEl.classList.add('active');
      googleTranslateEl.style.cssText = 'display:block !important; position:fixed; top:80px; right:16px; z-index:9999; background:#0d1515; border:1px solid rgba(0,219,231,0.3); border-radius:8px; padding:8px;';
    } else {
      googleTranslateEl.classList.remove('active');
      googleTranslateEl.style.cssText = 'display:none !important;';
    }
  }
  
  if (langEnBtn && langFrBtn) {
    langEnBtn.addEventListener('click', function() {
      toggleTranslate();
      const googleTranslateCombo = document.querySelector('.goog-te-combo');
      if (googleTranslateCombo) {
        googleTranslateCombo.value = 'en';
        googleTranslateCombo.dispatchEvent(new Event('change'));
      }
      langEnBtn.classList.remove('text-on-surface-variant');
      langEnBtn.classList.add('text-primary', 'font-bold');
      langFrBtn.classList.add('text-on-surface-variant');
      langFrBtn.classList.remove('text-primary', 'font-bold');
    });
    
    langFrBtn.addEventListener('click', function() {
      toggleTranslate();
      const googleTranslateCombo = document.querySelector('.goog-te-combo');
      if (googleTranslateCombo) {
        googleTranslateCombo.value = 'fr';
        googleTranslateCombo.dispatchEvent(new Event('change'));
      }
      langFrBtn.classList.remove('text-on-surface-variant');
      langFrBtn.classList.add('text-primary', 'font-bold');
      langEnBtn.classList.add('text-on-surface-variant');
      langEnBtn.classList.remove('text-primary', 'font-bold');
    });
  }

  // Contact Form AJAX Submission
  const contactForm = document.getElementById('contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const toastLoading = document.getElementById('toast-loading');
      const toastSuccess = document.getElementById('toast-success');
      const toastError = document.getElementById('toast-error');
      
      toastLoading.classList.remove('hidden');
      toastSuccess.classList.add('hidden');
      toastError.classList.add('hidden');
      
      try {
        const response = await fetch('/api/contact', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
          },
          body: formData
        });

        const data = await response.json();
        
        toastLoading.classList.add('hidden');
        
        if (response.ok) {
          toastSuccess.classList.remove('hidden');
          contactForm.reset();
          setTimeout(() => { toastSuccess.classList.add('hidden'); }, 3000);
        } else {
          toastError.classList.remove('hidden');
          setTimeout(() => { toastError.classList.add('hidden'); }, 3000);
        }
      } catch (error) {
        console.error('Error:', error);
        toastLoading.classList.add('hidden');
        toastError.classList.remove('hidden');
        setTimeout(() => { toastError.classList.add('hidden'); }, 3000);
      }
    });
  }
});
</script>
</body></html>