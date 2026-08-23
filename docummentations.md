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

## 2026-08-23 - Trade (Page id 37) : coeur du projet, livré complet

**Périmètre** : sous-agent trade, exclusivement la page `/trade` (id 37) - les 14 fonctionnalités du plan.

**Décision de modélisation MARGE / SOLDE (a lire avant de toucher a `App\Services\TradingService`)**
- **A l'ouverture** d'une position, le solde brut du wallet (`solde_reel`/`solde_demo`) n'est **jamais** débité. On calcule une marge requise (`marge = volume * prix_actuel * 100 / levier_max`, `100` = taille de contrat simplifiée pour ce MVP au lieu des 100 000 unités d'un vrai lot forex, gardée lisible avec le solde démo par défaut de 10 000 $) et on vérifie seulement qu'elle est inférieure à la "marge libre" (solde - somme des `marge_utilisee` des positions déjà ouvertes du même mode). La marge est ensuite simplement enregistrée dans `trade_history.marge_utilisee` : elle est **tracée**, pas **bloquée physiquement**.
- **A la clôture**, on met à jour la ligne (`prix_cloture`, `profit_perte` via `TradeHistory::calculerProfitFlottant()`, `statut='cloture'`, `cloture_le=now()`) puis on crédite/débite le wallet actif du montant exact de `profit_perte`. Comme la marge n'a jamais été soustraite à l'ouverture, il n'y a rien à "restituer" - une seule écriture comptable par cycle de vie complet.
- Le solde affiché reste donc toujours "l'argent réellement disponible avant P&L" (= Balance MT5) ; `AccountSummaryWidget` recalcule à la volée la vue "Equity/Marge" attendue par un trader (équité = solde + P&L flottant, marge libre = équité - marge utilisée, niveau de marge = équité / marge utilisée * 100) sans jamais avoir touché au solde stocké. Ce choix est le plus simple et le moins sujet aux bugs pour un MVP (pas de risque d'oubli de "restitution" de marge, pas de double-décompte). Documenté en détail en commentaire de classe dans `app/Services/TradingService.php`.
- Ordres en attente (`buy_limit`/`sell_limit`/`buy_stop`/`sell_stop`) : le schéma `trade_histories` ne comporte que 2 statuts (`ouvert`/`cloture`), aucun statut "en attente". Ces types sont donc exécutés immédiatement comme un ordre au marché pour ce MVP (le `type_ordre` est conservé à titre informatif/UI) ; un vrai moteur d'exécution différée est hors scope MVP.

**Nouveaux services**
- `App\Services\MarketPriceService` : simule un flux de prix "live" à partir de `MarketInstrument::prix_reference`, fluctuation pseudo-aléatoire déterministe ±0.05% (hash CRC32 symbole+seconde courante, sans toucher à l'état aléatoire global PHP). Fournit `currentPrice()` et `bidAsk()`. Clairement documenté comme simulation MVP, remplaçable plus tard par un vrai flux.
- `App\Services\TradingService` : `calculerMarge()`, `margeUtiliseePour()`, `margeLibrePour()`, `openPosition()`, `closePosition()` - toute la logique métier d'ouverture/clôture (voir décision de modélisation ci-dessus).

**Layout dédié**
- `resources/views/components/layouts/trade.blade.php` : composant anonyme plein écran (`<x-layouts.trade>`), indépendant du layout dashboard (pas de sidebar/navbar client), juste une barre supérieure minimale (logo + lien retour `/espace-client`).

**Composants Livewire** (`app/Livewire/Trade/*`, vues dans `resources/views/livewire/trade/*`)
- `TradePage` (racine, route `/trade`) : détient `$modeActif`/`$activeInstrumentId`, persistés en session, diffusés aux enfants via les événements Livewire globaux `mode-changed` et `symbol-selected` (bus d'événements page-wide, pattern suggéré par le plan).
- `Watchlist` (recherche + filtre catégorie + prix/variation simulés, clic = `symbol-selected`, icône trade rapide = ouvre `QuickTradeModal`). Volontairement **non paginée** (à la différence de l'historique) : la fonctionnalité watchlist du plan ne demande que recherche/filtre, et une pagination casserait l'usage "surveiller toute sa liste d'un coup d'œil" avec seulement ~15 instruments actifs.
- `ChartPanel` : widget TradingView (`resources/js/trade-chart.js`, script officiel `s3.tradingview.com/tv.js`, wrapper `window.XendaroTradeChart`) dans un conteneur `wire:ignore`, toolbar timeframe (M1..MN) + type de graphique (bougies/ligne/barres), indicateurs (MA/RSI/MACD/Bollinger) et outils de dessin natifs du widget (config seulement, pas de dev custom). `x-trading-chart` (composant partagé prévu par la page id 8 "market-detail", hors scope de cet agent) n'existait pas encore au moment du build : le rendu est resté auto-suffisant dans `ChartPanel` pour ne pas bloquer ni risquer un conflit de fichier ; extractible plus tard si besoin.
- `OrderForm` : volume/SL/TP/type d'ordre + boutons Buy (vert)/Sell (rouge) + toggle démo/réel. Réutilisé tel quel (même classe, 2 instances) sous le graph et dans `QuickTradeModal` (`variant='main'|'main-mobile'|'quick'`) - aucune duplication de formulaire, conformément à l'instruction du plan.
- `QuickTradeModal` : dialog flottant **sans overlay**, réutilise `<x-modal :overlay="false">` déjà prévu pour cet usage exact.
- `OpenPositions` : positions ouvertes, P&L flottant live (`wire:poll.3s`), fermeture rapide avec `wire:confirm`, scoping strict par `user_id` (vérifié par test : un utilisateur ne peut pas clôturer la position d'un autre).
- `TradeHistoryPanel` : historique clôturé, **paginé** (`WithPagination`, `paginate(10)`) via `<x-data-table>`, conformément à `bonnes_pratiques_dev`.
- `ProfileCard` : réutilise `<x-user-mini-card>` (créé entretemps par l'agent Auth/Client) + 2 soldes + toggle démo/réel synchronisé avec celui d'`OrderForm`.
- `AccountSummaryWidget` : Solde/Équité/Marge utilisée/Marge libre/Niveau de marge (formules détaillées ci-dessus).
- `PriceTicker` : Bid/Ask/Spread, direction (hausse/baisse) calculée **côté serveur** (propriété persistée entre deux `wire:poll`) plutôt que côté client Alpine, pour éviter toute fragilité liée aux morphs DOM du polling.
- Mobile (`<768px`) : réutilise `<x-tabs>` (créé entretemps par l'agent vitrine) pour 3 onglets Graph/Watchlist/Positions, à la place de la grille 3 colonnes desktop.

**Tests** (`tests/Feature/TradeFlowTest.php`, PHPUnit + `RefreshDatabase`, 16 tests / 80 assertions)
- Cycle de vie complet ouverture/clôture (gain, perte, sens buy/sell), non-débit du solde à l'ouverture, formule de marge, idempotence de la clôture, refus si marge libre insuffisante.
- Rendu HTTP complet de `/trade` (200 avec et sans instrument actif, 302 si non authentifié) - exerce tout l'arbre Blade/Livewire réel.
- Tests au niveau composant Livewire (`Livewire::test()`) : mount de `TradePage`, `OrderForm::placerOrdre()` + événement `trade-opened`, toggle démo/réel + `mode-changed`, `Watchlist::selectInstrument()` + `symbol-selected`, `OpenPositions::closePosition()` + `trade-closed`, et isolation stricte par utilisateur.
- Tous les tests passent (`php artisan test --filter=TradeFlowTest`), ainsi que la suite complète du projet (seul échec pré-existant et hors scope : `tests/Feature/ExampleTest.php`, stub par défaut sans `RefreshDatabase`, non lié à Trade).

**Autres fichiers touchés (dans mon périmètre)**
- `routes/trade.php` : route `/trade` branchée sur `App\Livewire\Trade\TradePage` (middleware `auth`).
- `vite.config.js` : ajout de l'entrée `resources/js/trade-chart.js`.
- `lang/fr/app.php` et `lang/en/app.php` : section `trade` complète (labels formulaire, catégories, types d'ordre/graphique, résumé de compte, erreurs métier).
- `npm run build` exécuté avec succès (chunk `trade-chart-*.js` généré).

**Statut** : page Trade livrée complète, testée, buildée. Prête pour test client.
