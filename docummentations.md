# Documentation - Xendaro Fox

Ce fichier documente, en résumé, les actions effectuées sur le projet (voir règle `bonnes_pratiques_dev` de `xendaro-fox-plan.json` : un sous-agent dédié tient ce fichier à jour à chaque groupe de tâches complété).

## 2026-08-23 - Fondations du projet

**Scaffold & environnement**
- Projet Laravel 12 scaffoldé (composer create-project), Git initialisé (commits locaux uniquement — aucun remote configuré, aucun push effectué).
- Base MySQL `xendaro_fox` créée sur XAMPP (MariaDB 10.4.32). `.env` configuré (APP_NAME=Xendaro Fox, APP_LOCALE=fr, DB_CONNECTION=mysql).
- Livewire 3, Filament 3 (panel admin sur `/admin`) et Alpine.js installés. Premier super admin créé : `marcosseko.travail@gmail.com`.
- Tailwind CSS v4 (config CSS-first dans `resources/css/app.css`) avec les tokens du thème Dark de `xendaro-fox-plan.json` (couleur-principale, couleur-secondaire, fond-principal, succes/danger, fonts Inter + Sora via Google Fonts).

**Internationalisation**
- `lang/fr` et `lang/en` publiés (validation, auth, passwords, pagination traduits en FR) + `lang/{fr,en}/app.php` pour les chaînes custom de l'app.
- `resources/js/lang/{fr,en}.js` exposés via `window.i18n` (règle : aucun texte JS codé en dur).

**Modèles & migrations transverses** (créés en priorité car référencés par de nombreuses pages)
- `SiteIdentifier` (singleton, tous les champs de branding/contenu légal) + `SiteIdentifierService` (cache) + seeder.
- `MarketInstrument` (relié à un provider externe type TradingView via `symbole_provider_externe`) + seeder avec 15 instruments (forex, crypto, or/métaux, indices, actions).
- `Wallet` (solde_reel/solde_demo par user, créé automatiquement à la création d'un `User` via un event `created` sur le modèle, avec génération du `referral_code`).
- `TradeHistory` (coeur de la page Trade), `WalletTransaction`, `PaymentMethod`, `Category` (générique multi-usage), `FaqContent`.
- Colonnes ajoutées à `users` : `phone`, `avatar_path`, `referral_code`, `parrain_id`, `otp_code`, `otp_expires_at`, `two_factor_enabled`.
- Resources Filament générées (CRUD admin) pour `MarketInstrument`, `PaymentMethod`, `Category`, `FaqContent`.

**Layout & composants réutilisables (Dark theme)**
- `resources/views/components/layouts/public.blade.php` (layout public, partage `$siteIdentifier` via View Composer global).
- Composants : `x-logo` (logo CSS/HTML), `x-public-navbar`, `x-public-footer`, `x-toast-container`, `x-stat-card`, `x-card-grid`, `x-card-item`, `x-icon-feature`, `x-floating-button`.
- Page d'accueil (`vitrine/home.blade.php`) branchée et testée (HTTP 200, contenu vérifié).

**Routes**
- `routes/web.php` éclaté en `vitrine.php` / `auth.php` / `client.php` / `trade.php` pour limiter les conflits entre sous-agents travaillant en parallèle. Chaque module ne doit modifier que son propre fichier.

**Statut** : fondations posées et vérifiées (serveur `php artisan serve` + build Vite OK). Reste à construire : le détail des 46 pages listées dans `xendaro-fox-plan.json` (Pages > fonctionnalites > taches). Des sous-agents autonomes prennent le relais par module (vitrine / auth + espace client / trade / Filament) en se référant obligatoirement à `xendaro-fox-plan.json` comme feuille de route.

## 2026-08-23 - Vitrine : fondations back-office + hub "Nos services" (pages id 2-5)

**Périmètre** : sous-agent vitrine (Pages id 2 à 24 de `xendaro-fox-plan.json`).

**Nouveaux modèles/migrations/seeders**
- `AccountType`, `Promotion`, `EducationResource`, `NewsArticle`, `EconomicEvent`, `ContactMessage` (migrations + modèles Eloquent, casts et relations).
- Seeders FR réalistes pour chacun + `CategorySeeder` (type=faq/education/news) et `FaqContentSeeder` (7 questions), tous enregistrés dans `DatabaseSeeder`.
- Resources Filament générées (`--generate`) pour CRUD admin complet sur les 6 nouveaux modèles. Point notable : `make:filament-resource EducationResource --generate` entrait en collision de nommage avec le modèle du même nom (Filament tronque le suffixe "Resource"), générant une classe cassée référençant un modèle `Education` inexistant. Corrigé manuellement : classe renommée `EducationResourceResource` (pages `Pages/*EducationResource.php`), référence explicite vers `App\Models\EducationResource`.

**Composants réutilisables créés**
- `x-accordion` + `x-accordion-item` (Alpine.js, sans dépendance au plugin `@alpinejs/collapse` non installé — transition opacité/translate à la place).
- `x-tabs` (Alpine.js, onglets génériques `[key => label]`, contenu fourni par l'appelant via `x-show="activeTab === 'key'"`).
- `x-legal-page` (titre + contenu texte/HTML, détecte automatiquement texte brut vs HTML pour éviter le double-échappement).
- `x-select-filter` : ajout d'un prop `selected` (rétro-compatible) pour les filtres en formulaire GET classique (non-Livewire), afin de conserver la sélection après rechargement de page.

**Fix infrastructure**
- `app/Providers/AppServiceProvider.php` : le `View::composer` qui partage `$siteIdentifier` était limité aux layouts (`components.layouts.*`). Ce partage ne se propage PAS automatiquement au scope de la vue appelante (ex: `vitrine.home`) — seul l'usage avec `??` masquait le problème (l'opérateur de coalescence null supprime aussi le warning "undefined variable", donnant l'illusion que ça fonctionnait alors que c'est le texte de fallback qui s'affichait). Corrigé en ajoutant le wildcard `vitrine.*` à la liste des vues composées, ce qui rend `$siteIdentifier` réellement disponible (valeurs BDD, pas juste le fallback) dans toutes les pages vitrine.

**Pages construites (testées HTTP 200)**
- `our-services` (id 2) : hub avec cards vers les 3 sous-pages + contenu `site_identifier.nos_services`.
- `account-types` (id 3) : tableau comparatif `<x-data-table>` (desktop) + cards (mobile), données `AccountType` (4 comptes seedés : Standard, ECN, VIP, Islamique).
- `platforms` (id 4) : cards statiques WebTrader/Mobile/Desktop + CTA vers `/trade`.
- `trading-conditions` (id 5) : tableau paginé des `MarketInstrument` actifs avec filtre catégorie (GET + `x-select-filter`).

**Statut** : 4/23 pages vitrine du périmètre livrées et vérifiées. Reste : about, markets, market-detail, promotions, affiliate-program, education(+détail), market-news(+détail), economic-calendar, trading-tools, faq, contact, why-us, cgv, policies, cookies, risk-disclosure, aml-policy.
