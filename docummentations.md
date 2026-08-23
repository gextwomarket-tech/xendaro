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
