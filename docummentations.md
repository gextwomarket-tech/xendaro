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

## 2026-08-23 - Authentification + Espace Client : Pages id 25-36, 38-42 livrées

**Périmètre** : sous-agent auth + espace client (routes/auth.php, routes/client.php, resources/views/auth+client, app/Livewire/Auth+Client). Page Trade (id 37) et vitrine hors scope, non touchées.

**Layout auth (2 colonnes)**
- `resources/views/components/layouts/auth.blade.php` : adapté de `images_design_ui/login_design.jpg` au thème 100% Dark - colonne gauche = formulaire dans une card `fond-card`, colonne droite = panneau `fond-surface` avec halos décoratifs `couleur-principale`/`couleur-secondaire` en blur + carte flottante "rejoignez des milliers de traders". Masqué en mobile (`<lg`).
- Enregistré dans le `View::composer` de `AppServiceProvider` (ajout additif de `components.layouts.dashboard` à la liste existante, sans toucher aux entrées des autres agents).

**Authentification (id 25-30)** - mécanisme OTP email unique, réutilisé partout (register, verify-email, 2FA) :
- `RegisterForm` : crée le `User` (le `Wallet` apparaît automatiquement via l'event déjà en place), gère `?ref=CODE` pour `parrain_id`, envoie un OTP 6 chiffres (`OtpCodeNotification`), connecte l'utilisateur puis redirige vers `/verification-email`.
- `LoginForm` : `Auth::validate()` + `RateLimiter` (5 tentatives/60s), puis `Auth::login()` ; si `two_factor_enabled` renvoie vers `/2fa` (flag session `needs_2fa`), sinon vers `/verification-email` ou `/espace-client`.
- `ForgotPasswordForm` / `ResetPasswordForm` : mécanisme natif Laravel `Password::sendResetLink` / `Password::reset`, aucune réponse différenciée si l'email n'existe pas (anti-énumération).
- `VerifyEmailForm` / `TwoFactorForm` : même pattern OTP (6 inputs, cooldown de renvoi 60s en Alpine.js), comparaison `hash_equals` + expiration 10 min.
- Middlewares `EnsureEmailIsVerifiedOtp` et `EnsureTwoFactorVerified` appliqués sur tout le groupe `espace-client` (jamais sur `/verification-email` et `/2fa` eux-mêmes, pour éviter la boucle de redirection).
- `LogoutController` : `POST /deconnexion` (jamais GET), déclenché uniquement depuis la modale de confirmation (voir ci-dessous).

**Bugs corrigés dans du code partagé déjà existant** (nécessaires pour débloquer mon périmètre, changements additifs uniquement) :
- `App\Models\User::$fillable` ne listait pas `otp_code`/`otp_expires_at` (colonnes pourtant déjà présentes en DB depuis les fondations) : `User::create([...'otp_code'=>...])` échouait silencieusement (mass assignment ignoré sans erreur). Ajouté les 2 clés au tableau `$fillable`.
- Ajouté 3 méthodes de relation sur `User` (`tickets()`, `kycDocuments()`, `affiliateCommissionsGagnees()`) pour mes 4 nouveaux modèles, en suivant le pattern déjà en place (`wallet()`, `tradeHistories()`...). Édition strictement additive, aucun champ/comportement existant modifié.
- `tests/Feature/ExampleTest.php` (stub par défaut) : ajouté le `use RefreshDatabase;` manquant (commenté dans le stub d'origine) - il échouait sur base vide dès qu'une vraie page a commencé à interroger la DB. Fix d'1 ligne, aucun conflit de scope.

**Espace Client (id 31-36, 38-42)** - layout Samify-inspired :
- `resources/views/components/layouts/dashboard.blade.php` : sidebar 2 états (étendue 260px/réduite 72px, toggle Alpine.js persisté en `localStorage`), drawer plein écran en mobile (`<lg`), 3 groupes de nav (TRADING/COMPTE/SUPPORT) avec pilule active `couleur-principale/15`, badges point/nombre sur Notifications (unread) et Support (tickets ouverts), mini-card profil en bas de sidebar (`<x-user-mini-card>`), navbar avec recherche + cloche notifications + `<x-user-menu-dropdown>`. Modales globales embarquées dans le layout : `edit-profile` (`<livewire:client.edit-profile-form>`) et `logout-confirm` (formulaire `POST` vers la route `logout`, email du compte affiché).
- Composants réutilisables créés : `x-user-mini-card` (avatar + point de statut vert + nom/email + bouton optionnel "Modifier le profil", conçu générique pour être réutilisé tel quel par la sidebar droite de Trade - confirmé fait par l'agent Trade) et `x-user-menu-dropdown` (Paramètres/Mon profil/Déconnexion).
- `Dashboard` (id 31) : 4 stat cards (solde réel/démo, nb trades, P&L), graphique de performance en SVG polyline calculé serveur (pas de dépendance JS/npm supplémentaire), tables compactes trades/transactions récents.
- `EditProfileForm` (id 32) : popup, upload avatar (`WithFileUploads`, disk `public`, 2 Mo max).
- `SecuritySettings` (id 33) : changement de mot de passe (`Hash::check` sur l'ancien), toggle 2FA (`x-toggle-switch`), liste des sessions actives (table `sessions`, driver database) avec terminaison individuelle (jamais la session courante).
- `TradeHistoryPage` (id 34) / `MarketsReadOnly` (id 35) : filtres + pagination 15, `MarketsReadOnly` réutilise **`MarketPriceService`** déjà créé par l'agent Trade (aucune duplication de service de prix), bouton "Trader" renvoie vers `/trade?symbole=xxx`.
- `WalletPage` + `DepositForm`/`WithdrawForm` (id 36) : **décision d'implémentation** - dépôt/retrait créent une `WalletTransaction` en statut `en_attente` ; le crédit/débit réel de `solde_reel` n'intervient qu'à la validation admin (nouveau `WalletTransactionResource` Filament avec actions `Valider`/`Refuser` déclenchant `App\Services\WalletTransactionService::approve()/reject()`). Seedé `PaymentMethodSeeder` (carte bancaire, virement bancaire, crypto USDT) car la table était vide.
- `KycUploadForm` (id 38) : upload pièce d'identité + justificatif de domicile sur disk **privé** `local` (`storage/app/private/kyc/{user_id}`, jamais exposé publiquement), statuts en_attente/valide/refusé.
- `Notifications` (id 39) : canal `database` de Laravel Notifications (migration générée), mark as read / tout marquer comme lu.
- `SupportTickets` + `TicketDetail` (id 40-41) : nouveaux modèles `Ticket`/`TicketMessage`, création transactionnelle ticket+premier message, fil de discussion avec bulles gauche/droite selon `est_admin`, `ReplyTicketForm` (rouvre le ticket si fermé), autorisation stricte par `user_id` (403 si tentative d'accès à un ticket d'un autre utilisateur, couvert par test).
- `AffiliateDashboard` (id 42) : lien `/inscription?ref=CODE` (copie presse-papiers Alpine), nouveau modèle `AffiliateCommission`, stats filleuls/commissions, liste paginée.
- `logout` (id 43) : modale de confirmation (`<x-modal name="logout-confirm">`) intégrée au layout dashboard, déclenchée depuis la sidebar ET le dropdown navbar - jamais de déconnexion instantanée.

**Filament (admin)** : resources CRUD générées et enrichies pour les 4 nouveaux modèles (`TicketResource`, `TicketMessageResource`, `KycDocumentResource`, `AffiliateCommissionResource` - selects enum, badges de statut colorés, filtres) + `WalletTransactionResource` (actions Valider/Refuser ci-dessus).

**Tests** (`tests/Feature/Auth/RegisterLoginDashboardTest.php`, `tests/Feature/Client/{DashboardPagesSmokeTest,TicketDetailTest,WalletAndKycTest}.php`) :
- Parcours complet register → OTP → dashboard, login (succès/échec), 9 pages Espace Client en 200, autorisation ticket (403 cross-user), dépôt/retrait + approbation admin (crédite bien le wallet), upload KYC.
- Suite complète du projet : **27 passed / 98 assertions** (aucune régression sur les tests Trade/vitrine déjà en place).

**Statut** : Authentification + Espace Client livrés complets (12 pages), testés bout en bout, `npm run build` OK, aucun 500 constaté.

## 2026-08-23 - Vitrine : pages id 6 à 18 (About à Contact)

**Pages construites et testées HTTP 200** (avec données réelles issues de la base, jamais de contenu statique quand un modèle est prévu par le plan) :
- `about` (id 6) : `site_identifier.about_us` + stats clés + valeurs de l'entreprise.
- `why-us` (id 19, construite avec ce lot) : arguments de confiance (sécurité, exécution, support, conformité, RGPD).
- `markets` (id 7) + `market-detail` (id 8) : `MarketController::index/show`, recherche + filtre catégorie, variation 24h simulée déterministe (hash CRC32 par instrument + jour), route model binding sur `symbole_interne`. Nouveau composant `<x-trading-chart>` (widget TradingView officiel en embed autonome lecture seule).
- `promotions` (id 9) : `Promotion::actives()` + `<x-modal>` de détail par promo.
- `affiliate-program` (id 10) : barème de commissions piloté par `config/affiliate.php` (nouveau fichier, paliers modifiables sans toucher aux vues).
- `education` (id 11) + `education-article` (id 12) : `EducationController::index/show`, recherche, filtre catégorie (`Category` type=education), ressources liées.
- `market-news` (id 13) + `news-detail` (id 14) : `NewsController::index/show`, filtre catégorie (type=news), lien vers l'instrument associé, articles liés.
- `economic-calendar` (id 15) : `EconomicEvent` paginé, filtres devise + importance (badge coloré).
- `trading-tools` (id 16) : 4 calculatrices (pip, marge, profit, conversion) via `<x-tabs>` + Alpine.js réactif, sans dépendance API externe (taux simulés alignés sur `MarketInstrumentSeeder`).
- `faq` (id 17) : `FaqContent` paginé, recherche + filtre catégorie, `<x-accordion>`.
- `contact` (id 18) : Livewire `ContactForm` (validation, throttle anti-spam `RateLimiter`, toast succès) + Mailable `ContactMessageMail` (envoi **synchrone**, volontairement sans `ShouldQueue` car aucun worker de queue n'est garanti actif pour ce MVP — un mail mis en queue non traité serait invisible) + coordonnées `site_identifier` + carte Google Maps embed. Testé via `Livewire::test()` en tinker : soumission valide → `ContactMessage` créé, zéro erreur, zéro job résiduel en base.

**Nouveaux fichiers notables** : `app/Http/Controllers/MarketController.php`, `EducationController.php`, `NewsController.php` ; `app/Livewire/Vitrine/ContactForm.php` ; `app/Mail/ContactMessageMail.php` ; `config/affiliate.php` ; `resources/views/components/trading-chart.blade.php`.

**Statut** : 18/23 pages du périmètre vitrine livrées et vérifiées. Reste : trading-tools ✅ (fait), cgv, policies, cookies, risk-disclosure, aml-policy (les 5 pages légales via `<x-legal-page>`, déjà créé).

## 2026-08-23 - Vitrine : pages légales id 20-24 + périmètre "Public / Vitrine" terminé

**Pages construites (testées HTTP 200)**
- `cgv`, `policies`, `cookies` : réutilisent `<x-legal-page>` avec `site_identifier.cvg/policies/cookies`.
- `risk-disclosure`, `aml-policy` : aucun champ dédié sur `site_identifier` pour ces deux textes légaux — utilisation d'un texte par défaut via traductions (`app.legal.risk_default` / `aml_default`), conformément à l'instruction du plan ("texte statique via traduction si non stocké en base").
- Nouveau composant `<x-cookie-consent-banner>` (Alpine.js + `localStorage` + cookie technique, boutons Accepter/Refuser) injecté une seule fois dans `components/layouts/public.blade.php` — visible sur toutes les pages vitrine, pas seulement `/cookies`.
- Lien "AML / KYC" ajouté au footer public (`public-footer.blade.php`), qui ne pointait pas encore vers `/politique-aml`.

**Vérification finale de régression** : les 24 routes du périmètre (home + 23 pages id 2-24) retestées en une passe, toutes en HTTP 200 ; pagination testée (`?page=2`) sur markets/academie/calendrier/faq ; les 6 nouvelles Filament resources admin répondent en 302 (redirection login, comportement attendu) et non en 500.

**Périmètre "Public / Vitrine" (Pages id 2 à 24 de `xendaro-fox-plan.json`) : 23/23 pages livrées, testées et commitées.** Détail des livrables : voir les entrées précédentes de ce journal (2026-08-23, 4 entrées). Composants réutilisables créés pour ce périmètre : `x-accordion`(+item), `x-tabs`, `x-legal-page`, `x-trading-chart`, `x-cookie-consent-banner` ; `x-select-filter` étendu (prop `selected`). Modèles créés : `AccountType`, `Promotion`, `EducationResource`, `NewsArticle`, `EconomicEvent`, `ContactMessage` (+ seeders FR et CRUD Filament complet). Fix transverse : `AppServiceProvider` partage désormais `$siteIdentifier` sur `vitrine.*` (pas seulement les layouts), sans quoi le contenu piloté par `site_identifier` ne s'affichait pas réellement dans les pages (masqué par l'opérateur `??`).

## 2026-08-23 - Bilan de session : les 3 sous-agents (vitrine / auth+client / trade) ont terminé

Les 3 sous-agents lancés en parallèle sur ce périmètre ont chacun terminé et commité localement leur travail, en respectant le découpage de fichiers défini pour éviter les conflits (routes/vitrine.php, routes/auth.php+client.php, routes/trade.php séparées ; chacun dans son propre dossier de vues/Livewire).

**Vérification globale post-fusion effectuée par l'orchestrateur** :
- `git status` propre (rien d'uncommitted) après les 3 agents.
- `php artisan migrate --force` : rien à migrer (toutes les migrations des 3 agents étaient déjà passées pendant leur session).
- Suite de tests complète (`php artisan test`) : **27 passed / 98 assertions**, 0 échec, ~3.6s. Couvre : inscription→OTP→dashboard, connexion, throttle, 9 pages Espace Client (200 OK), autorisation ticket (403 cross-user), dépôt/retrait/KYC, et l'intégralité du cycle de vie Trade (ouverture/clôture demo+réel, gain/perte, marge insuffisante, idempotence clôture, isolation cross-user, rendu Livewire).
- `npm run build` : OK, nouveau chunk `trade-chart-*.js` bien généré (widget TradingView de la page Trade).
- Smoke-test manuel de 19 routes publiques + 3 sous-routes "Nos services" + détail d'instrument (`/marches/AAPL`) + page légale : **100% HTTP 200**. Routes protégées (`/trade`, `/espace-client`) : **302 vers /connexion** comme attendu pour un visiteur non authentifié. `/admin/login` : 200.

**Point non couvert par les agents** : le sous-agent Vitrine n'a pas laissé de fichier de test automatisé formel dans `tests/` pour son périmètre (il a vérifié manuellement en session via curl/Livewire::test() mais rien n'est resté en régression). À prévoir en tâche de suivi : ajouter un test de fumée `tests/Feature/Vitrine/PublicPagesSmokeTest.php` couvrant les 24 routes publiques pour verrouiller la non-régression.

**Statut global du MVP à ce stade** : les 46 pages du plan ont une route + une vue fonctionnelle (vitrine, auth, espace client, trade), le panel admin Filament expose un CRUD sur tous les modèles créés (site_identifier, market_instruments, payment_methods, categories, faq_content, account_types, promotions, education_resources, news_articles, economic_events, contact_messages, tickets, ticket_messages, kyc_documents, affiliate_commissions, wallet_transactions). Reste en dette technique connue : queue non garantie active (mail de contact envoyé en synchrone), pas de vrai flux de prix externe (MarketPriceService est un simulateur documenté), pas de moteur d'exécution différée pour les ordres limit/stop (exécutés au marché pour ce MVP). Aucun push Git effectué (aucun remote configuré, en attente du repo GitHub du client).

## 2026-08-23 - Bug critique corrigé : double instance Alpine.js cassait l'hydratation Livewire

**Contexte** : test manuel du parcours auth complet (inscription → OTP → dashboard → déconnexion → connexion → changement de mot de passe → reconnexion) en conditions réelles dans le navigateur.

**Bug constaté** : le formulaire de vérification OTP (`verify-email-form.blade.php`, dont la racine porte `x-data`) ne se soumettait jamais via Livewire malgré un code correct saisi et vérifié valide côté base de données (`hash_equals` + expiration OK en tinker). Chaque clic sur "Vérifier" provoquait un rechargement de page natif (requête GET classique au lieu d'un appel AJAX `POST /livewire/update`), réinitialisant le composant (timer de renvoi remis à ~60s) sans jamais exécuter la logique métier.

**Cause racine** : `window.Livewire.all().length` renvoyait `0` sur la page, alors même que le HTML contenait bien l'attribut `wire:id` attendu. La console affichait `"Detected multiple instances of Alpine running"` en boucle. `resources/js/app.js` important et démarrant manuellement le paquet npm `alpinejs` (`Alpine.start()`) alors que **Livewire v3 embarque et démarre déjà sa propre instance d'Alpine en interne** — les deux instances entraient en conflit et empêchaient Livewire de correctement hydrater/enregistrer les composants dont la racine porte un `x-data` (mécanisme d'hydratation Alpine détourné par la mauvaise instance).

**Correctif** : suppression de l'import/démarrage manuel d'`alpinejs` dans `resources/js/app.js`, avec commentaire explicite pour ne pas réintroduire l'erreur. Rebuild Vite. Vérifié après coup : `window.Livewire.all().length === 1` sur la page de vérification, et le cycle complet fonctionne de bout en bout en conditions réelles navigateur (pas seulement en tests automatisés PHP, qui eux ne rendent pas le JS et n'auraient jamais détecté ce bug).

**Point d'attention pour la suite** : si un composant Livewire custom a besoin d'Alpine dans du JS, utiliser `window.Alpine` uniquement après l'évènement `livewire:init` (Livewire l'expose alors globalement) — ne jamais réinstaller/réimporter le paquet `alpinejs` séparément.

**Vérifications post-fix** : suite de tests complète toujours au vert (27/27, 98 assertions) - ce bug n'était détectable qu'en navigateur réel, pas par les tests HTTP/Livewire::test() existants qui n'exécutent pas de JS. Cycle auth testé manuellement de bout en bout avec succès (inscription, OTP réel lu depuis les logs mail, dashboard, déconnexion avec modale de confirmation, connexion, changement de mot de passe vérifié en base, reconnexion avec le nouveau mot de passe).

## 2026-08-23 - Refonte visuelle : actualités, FAQ, contact et pages légales

**Périmètre** : sous-agent dédié à la refonte DA (hero animé, reveal au scroll, photo-card+floating-badge, variété de sections) de 9 vues vitrine, en réutilisant exclusivement la boîte à outils déjà posée par l'orchestrateur (`x-page-hero`, `x-reveal`, `x-photo-card`, `x-floating-badge`, `x-accordion`, `x-legal-page`) : `market-news.blade.php`, `news-detail.blade.php`, `faq.blade.php`, `contact.blade.php`, `cgv.blade.php`, `policies.blade.php`, `cookies.blade.php`, `risk-disclosure.blade.php`, `aml-policy.blade.php`. Aucune route/contrôleur/modèle touché.

**market-news** : hero animé + bannière "À la une" full-bleed sur le dernier article publié (page 1 sans filtre catégorie uniquement, pour ne pas casser la pagination filtrée), filtre catégorie conservé, grille restylée en vraies cartes de blog (miniature photo, badge catégorie superposé, date).

**news-detail** : hero utilisant l'image propre de l'article (`Storage::url`) avec repli sur une image générique finance/news si absente, contenu et articles liés encapsulés dans `x-reveal`, articles liés restylés en cartes photo.

**faq** : hero support/aide, questions regroupées par catégorie (`groupBy` sur la collection paginée) et rendues en plusieurs blocs `x-accordion` distincts plutôt qu'un accordéon plat, section CTA "toujours besoin d'aide" en split image/texte (`x-photo-card` + `x-floating-badge`) avant le bandeau CTA du footer, bouton flottant support conservé.

**contact** : hero dédié, composant Livewire `vitrine.contact-form` strictement inchangé (vérifié : champs `wire:model="nom|email|sujet|message"` toujours présents après restyle), bloc coordonnées enrichi d'une photo de bureaux (`x-photo-card` + `x-floating-badge` horaires) au-dessus des `x-icon-feature` et de la carte Google Maps existante, animations `x-reveal` alternées gauche/droite.

**5 pages légales** (cgv, policies, cookies, risk-disclosure, aml-policy) : hero sobre `size="sm"` avec eyebrow "Informations légales" et image picsum dédiée par page, contenu encapsulé dans `x-reveal` autour du composant `x-legal-page` existant (contrat de props non modifié - seul le `$content` source change par page comme avant).

**Traductions** : toutes les nouvelles chaînes ajoutées sous les clés `app.news.*`, `app.faq.*`, `app.contact.*` et `app.legal.*` dans `lang/fr/app.php` (source) et `lang/en/app.php`, aucun texte visible en dur. Vérification post-fusion (les agents concurrents éditaient les mêmes fichiers de langue en parallèle) : script PHP listant les clés des deux fichiers pour confirmer que FR et EN restent strictement synchronisés après la fusion des commits.

**Vérifications** : `php -l` OK sur les 9 vues + les 2 fichiers de langue. `npm run build` OK (aucune régression asset). Smoke-test `curl` sur `/actualites`, `/actualites/{slug réel}`, `/faq`, `/contact`, `/cgv`, `/confidentialite`, `/cookies`, `/avertissement-risques`, `/politique-aml` : **100% HTTP 200**. Contenu vérifié par grep sur les marqueurs clés (bannière "À la une", badges CTA FAQ, champs du formulaire Livewire).

**Commits locaux** (pas de push, pas de remote configuré) : pages légales groupées en un commit, `market-news`+`news-detail` en un commit, `faq`+`contact` en un commit. Les fichiers de langue ont été inclus dans le commit d'un agent concurrent voisin (`141dec4`) du fait du travail en parallèle sur les mêmes fichiers partagés - contenu vérifié intact après coup, aucune perte de clé.

## 2026-08-23 - Refonte visuelle : accueil, hub services et pages institutionnelles

**Périmètre** : sous-agent dédié à la refonte DA des 7 pages vitrine les plus stratégiques, en réutilisant exclusivement la boîte à outils déjà posée par l'orchestrateur (`x-page-hero`, `x-reveal`, `x-photo-card`, `x-floating-badge`, `x-animated-counter`, `x-card-grid`/`x-card-item`, `x-icon-feature`, `x-data-table`, `x-stat-card`) : `home.blade.php`, `our-services.blade.php`, `account-types.blade.php`, `platforms.blade.php`, `trading-conditions.blade.php`, `about.blade.php`, `why-us.blade.php`. Aucune route/contrôleur/modèle touché ; `markets.blade.php` (déjà refondu par l'orchestrateur) utilisé comme référence de composition.

**home** (page la plus importante) : hero animé + 8 sections au layout volontairement différent d'une section à l'autre (jamais deux motifs identiques consécutifs) - bandeau de stats animées (`x-animated-counter`), showcase marchés en split image/texte avec `x-photo-card`+`x-floating-badge` et mini-grille de catégories, grille "pourquoi Xendaro Fox" en 4 `x-icon-feature`, bannière plein cadre plateformes, split comptes (image à droite, alternance), bloc académie en carte scindée image/texte, 3 témoignages avec avatar photo superposé au coin de la carte (pattern F), bannière teaser parrainage/promotions en fin de page (ne duplique pas le CTA global déjà présent dans le footer). Toutes les données dynamiques existantes conservées (`site_identifier`, `MarketInstrument::count()`).

**our-services** (page hub) : bannière plein cadre d'intro, puis 3 teasers alternés gauche/droite (types de comptes, plateformes, conditions de trading) chacun avec sa propre photo + floating-badge, bande de confiance (pills), contenu `site_identifier->nos_services` conservé en fin de page.

**account-types** : hero + section d'intro split image/texte, comparatif réel (`AccountType::where('est_actif', true)`) conservé intégralement (table desktop `x-data-table` + cards mobile), bannière CTA plein cadre en fin de page. Aucune donnée du modèle retirée.

**platforms** : WebTrader en split image/texte, mobile en bannière plein cadre (texte aligné à droite sur l'image), desktop en split inversé, comparatif rapide en grille 3 colonnes reprenant les 3 `x-card-item` d'origine.

**trading-conditions** : hero + intro split image/texte, filtre catégorie et tableau `MarketInstrument`-driven (pagination + `withQueryString`) strictement conservés, seule la mise en page autour a changé.

**about** : split mission/histoire avec le contenu `site_identifier->about_us` conservé, bandeau de stats animées, timeline horizontale/verticale des 4 grandes étapes de l'entreprise (2021-2026), bannière plein cadre "équipe", grille de 4 valeurs (`x-icon-feature`, contenu inchangé).

**why-us** : bannière de confiance plein cadre, grille de 5 garanties (`x-icon-feature`, contenu sécurité/exécution/support/régulation/confidentialité inchangé), section KYC en split image/texte, bloc texte sécurité/régulation/données conservé tel quel en fin de page.

**Images** : toutes en `https://picsum.photos/seed/{seed-descriptif-unique}/{w}/{h}`, un seed distinct par image (aucune réutilisation à travers les 7 pages), commentaire `TODO: remplacer par photographie sous licence Xendaro Fox avant production` en tête de chaque fichier modifié.

**Traductions** : nouvelle clé `app.home.*` complète + nouvelles sous-clés ajoutées dans `app.services.*`, `app.account_types.*`, `app.platforms.*`, `app.trading_conditions.*`, `app.about.*` et `app.why_us.*` (existants), dans `lang/fr/app.php` (source) et `lang/en/app.php`. Vérification automatisée post-fusion (agents concurrents éditant les mêmes fichiers de langue en parallèle) via un script PHP comparant les clés aplaties des deux fichiers : 0 clé manquante de part et d'autre.

**Vérifications** : `php -l` OK sur `lang/fr/app.php` et `lang/en/app.php`. `npm run build` OK à chaque étape (3 builds, aucune régression asset). Smoke-test `curl` sur `/`, `/nos-services`, `/nos-services/types-de-comptes`, `/nos-services/plateformes`, `/nos-services/conditions-de-trading`, `/a-propos`, `/securite` : **100% HTTP 200**, réponses grep-ées sans trace d'exception/erreur PHP.

**Commits locaux** (pas de push, pas de remote configuré) : accueil en un commit dédié, hub services + 3 sous-pages en un commit groupé, about + why-us en un commit groupé.

## 2026-08-23 - Refonte visuelle : marchés (détail), promotions, parrainage, outils, calendrier économique, académie

**Périmètre** : sous-agent dédié à la refonte DA de 7 pages vitrine, en réutilisant exclusivement la boîte à outils posée par l'orchestrateur (`x-page-hero`, `x-reveal`, `x-photo-card`, `x-floating-badge`, `x-animated-counter`, `x-mini-chart`, `x-card-grid`/`x-card-item`, `x-icon-feature`, `x-data-table`, `x-stat-card`, `x-tabs`) : `market-detail.blade.php`, `promotions.blade.php`, `affiliate-program.blade.php`, `trading-tools.blade.php`, `economic-calendar.blade.php`, `education.blade.php`, `education-article.blade.php`. Aucune route/contrôleur/modèle touché ; `markets.blade.php` (déjà refondu par l'orchestrateur) utilisé comme référence de composition.

**market-detail** : hero compact (`size="sm"`) avec lien retour + CTA trade intégrés, `<x-trading-chart>` conservé tel quel comme pièce centrale, section split image superposée (spread en badge flottant) + grille de caractéristiques (`x-stat-card`), bannière plein cadre CTA vers `/trade`, grille d'instruments liés (`$related` du contrôleur) en mini-graphs TradingView live (`x-mini-chart`).

**promotions** : hero + bande de stats animées (nombre d'offres actives réel via `$promotions->total()`, bonus max, délai d'activation), grille de promotions restylée en `x-photo-card`+`x-floating-badge` (image de la promo si présente, sinon placeholder par id) remplaçant les cartes plates d'origine, modales de détail par promo conservées à l'identique, bloc témoignage, bannière CTA finale. Données réelles `Promotion::actives()` inchangées.

**affiliate-program** : hero, section split image/texte présentant le programme, barème de commissions (`config('affiliate.tiers')`) en compteurs animés, étapes numérotées "comment ça marche" (3 étapes), bannière CTA plein cadre. `x-floating-button` conservé.

**trading-tools** : hero, section split image/texte d'intro, bloc calculateurs (pip/marge/profit/convertisseur) et sa logique Alpine.js `x-data` strictement inchangée (aucun composant Livewire trouvé dans ce fichier - implémentation 100% Alpine côté client), grille "pourquoi nos outils" (3 `x-icon-feature`), bannière CTA finale.

**economic-calendar** : hero, section texte/image inversée (image à droite) expliquant l'usage du calendrier, filtres + `x-data-table` sur données réelles `EconomicEvent` strictement conservés, grille d'explication des 3 niveaux d'importance (faible/moyenne/haute), bannière CTA finale.

**education** : hero, bannière plein cadre "ressource mise en avant" (générique, pas liée à une ressource précise pour rester cohérente avec pagination/filtres), bande de stats animées (nombre réel de ressources et de catégories), filtre + recherche conservés, grille de ressources restylée en `x-photo-card` (vignette réelle si `$resource->image`, sinon placeholder par id) + badge de type flottant, bloc témoignage.

**education-article** : hero utilisant l'image propre de la ressource (`$resource->image` via `Storage::url`) si présente, sinon image de repli générique académie ; contenu de l'article conservé à l'identique ; nouvelle section "à retenir" (3 `x-icon-feature` génériques) ; ressources liées (`$related` du contrôleur) restylées en `x-photo-card` ; bannière CTA finale.

**Images** : toutes en `https://picsum.photos/seed/{seed-descriptif-unique}/{w}/{h}`, un seed distinct par image statique (aucune réutilisation à travers les 7 pages) ; les images dynamiques par item (promotions, ressources académie) utilisent un seed dérivé de l'id pour rester uniques sans collision. Commentaire `TODO: remplacer par photographie sous licence Xendaro Fox avant production` en tête de chaque fichier modifié.

**Traductions** : nouvelles sous-clés ajoutées dans `app.market_detail.*`, `app.promotions.*`, `app.affiliate.*`, `app.tools.*`, `app.calendar.*`, `app.education.*` (sections existantes) dans `lang/fr/app.php` (source) et `lang/en/app.php`, en édition ciblée (`Edit`, jamais réécriture complète du fichier) pour cohabiter avec des agents concurrents éditant les mêmes fichiers partagés en parallèle (section `home` notamment).

**Vérifications** : `php -l` OK sur les 7 vues + les 2 fichiers de langue. `npm run build` OK après chaque page (7 builds, aucune régression asset). Smoke-test `curl` : `/marches/EURUSD`, `/marches/GBPUSD`, `/marches/AUDUSD`, `/marches/USDCHF` (catégories/instruments réels différents), `/promotions`, `/parrainage`, `/outils`, `/calendrier-economique`, `/calendrier-economique?devise=USD&importance=haute` (filtres), `/academie`, `/academie?search=forex` (recherche), `/academie/{slug réel}` sur 5 slugs distincts : **100% HTTP 200**. Vérification navigateur (Chrome pane) sans erreur console sur `/outils` et `/marches/EURUSD`, chargement effectif du widget TradingView.

**Commits locaux** (pas de push, pas de remote configuré) : un commit par page (7 commits), lang files inclus dans le premier commit (market-detail) car modifiés dans la même passe.

## 2026-08-23 - Bug critique corrigé : Alpine.js absent sur toutes les pages publiques sans composant Livewire

**Signalé par le client** en testant le format mobile : le bouton menu et les boutons du bandeau cookies ne répondaient plus du tout au clic ("comme désactivé").

**Diagnostic** : `window.Alpine` était `undefined` sur les pages sans aucun composant Livewire (accueil, marchés, à propos, etc.) — vérifié en navigateur réel (`window.Alpine`, `window.Livewire` tous deux `false`, aucun script `livewire.js` chargé dans le HTML). Cause : Livewire n'injecte automatiquement ses assets (script qui embarque Alpine.js en interne) que lorsqu'au moins un composant Livewire est réellement rendu dans la requête. Or la majorité des pages vitrine (dont `home`) n'embarquent aucun composant Livewire — elles n'ont donc jamais chargé Alpine, rendant tous les `x-data` inertes : menu mobile, bandeau cookies, `<x-reveal>`, `<x-page-hero>`. Ce trou existait depuis la suppression de l'import direct du paquet `alpinejs` (fix du 2026-08-23 sur le double-instance OTP) : ce fix était correct pour les pages Livewire mais a fait apparaître ce second trou sur les pages 100% statiques.

**Correctif** : ajout explicite de `@livewireStyles` (dans `<head>`) et `@livewireScripts` (avant `</body>`) dans les 3 layouts qui ne les avaient pas : `components/layouts/public.blade.php`, `components/layouts/auth.blade.php`, `components/layouts/dashboard.blade.php` (`components/layouts/trade.blade.php` les avait déjà). Ces directives forcent le chargement de Livewire+Alpine sur toute page utilisant ces layouts, qu'elle contienne ou non un composant Livewire.

**Vérification** : après rebuild, `window.Alpine` est bien défini sur `/` ; clic programmatique sur "Accepter" (bandeau cookies) écrit correctement dans `localStorage` ; toggle du menu mobile confirmé (drawer passe bien à `display: block`). Suite de tests complète toujours au vert (27/27, 98 assertions) - comme le premier bug Alpine, celui-ci était invisible aux tests HTTP/Livewire::test() qui n'exécutent pas de JS ; seul un test navigateur réel pouvait le révéler.

**Point de vigilance pour la suite** : tout nouveau layout Blade doit systématiquement inclure `@livewireStyles`/`@livewireScripts`, même s'il semble ne contenir aucun Livewire au moment de sa création — sinon toute interaction Alpine.js sur ce layout restera inerte tant qu'aucun composant Livewire n'est ajouté.

## 2026-08-23 - Refonte visuelle des 24 pages vitrine (hero animé, sections variées, images superposées)

Le client a demandé une refonte visuelle de toutes les pages "statiques" (vitrine) : plus riche en images, animée, avec des sections visuellement différentes plutôt que des grilles de cards répétitives. Il a fourni un brief de direction artistique détaillé (thème Dark existant conservé, mais accent sur : hero animé unique par page, images en cards superposées/décalées, animations au scroll sur ~95% des sections, section Marchés montrant des graphs réels plutôt qu'un tableau).

**Boîte à outils de composants créée** (`resources/views/components/`) : `x-page-hero` (hero animé, fond Ken Burns + glow + reveal), `x-reveal` (animation d'apparition au scroll via IntersectionObserver, sans dépendance au plugin `@alpinejs/intersect`), `x-photo-card` + `x-floating-badge` (le pattern "images superposées" - une card flottante chevauchant une photo-card), `x-animated-counter` (compteur animé), `x-mini-chart` (widget TradingView "Mini Symbol Overview", léger, pour affichage en grille - utilisé pour la nouvelle page Marchés).

**Footer refondu** : bandeau CTA (titre + boutons Inscription/Contact) au-dessus d'un footer pro 4 colonnes (logo+réseaux sociaux, Nos services, Légal, Contact) + ligne de copyright/avertissement risque en bas. Reste un composant unique partagé par toutes les pages statiques (`x-public-footer`), conformément à la demande.

**Page Marchés reconstruite** : au lieu du tableau de prix, affiche désormais une grille de mini-graphs TradingView live par instrument (avec recherche + filtre catégorie conservés), chaque card cliquable vers la fiche détaillée. Vérifié : 12 iframes TradingView chargés sans erreur console.

**Scrollbar de popup** : ajout d'une classe utilitaire `.scrollbar-xendaro` (fine, couleur accent) dans `app.css`, appliquée au conteneur de contenu de `<x-modal>` (désormais `flex flex-col max-h-[85vh]` avec zone scrollable interne) - demande explicite du client pour que tout popup au contenu long reste propre visuellement.

**Répartition du travail** : moi-même (fondations : boîte à outils, footer, page Marchés, scrollbar popup) + 3 sous-agents en parallèle sur des périmètres de fichiers disjoints : (1) landing/services (home, nos-services, types de comptes, plateformes, conditions de trading, à propos, sécurité), (2) contenu marché/outils (détail instrument, promotions, parrainage, outils, calendrier économique, académie+détail), (3) contenu/support/légal (actualités+détail, FAQ, contact, 5 pages légales). Aucune route/contrôleur/modèle touché - refonte de présentation uniquement, toutes les données réelles (site_identifier, MarketInstrument, AccountType, Promotion, EducationResource, NewsArticle, EconomicEvent, FaqContent, Category) conservées.

**Images** : placeholders `picsum.photos` (photographie réelle mais générique, pas de rapport thématique avec le trading) avec seed unique par image - **le client a signalé que ces images ne correspondent pas du tout au trading et va fournir son propre lot d'images à intégrer** (déposées dans un dossier dédié, à traiter dans une tâche de suivi dès réception). Chaque fichier modifié porte un commentaire `TODO: remplacer par photographie sous licence Xendaro Fox avant production` pour retrouver facilement tous les points de remplacement.

**Vérification globale** : toutes les 24 pages vitrine testées HTTP 200 par les agents + moi-même, `npm run build` propre, suite de tests 27/27 toujours au vert. Commits locaux uniquement (pas de push, pas de remote configuré).

## 2026-08-23 - Filament : couverture CRUD complète de toutes les tables SQL

Suite à la demande du client ("démarre avec l'installation de filament et la création des CRUD admin"), audit de couverture : Filament était déjà installé, 15 resources existaient (créées par les sous-agents précédents), mais 4 tables métier importantes n'avaient aucun CRUD admin : `users`, `wallets`, `trade_histories`, `site_identifiers`.

**Resources créées** :
- `UserResource` (groupe "Utilisateurs") : champ mot de passe sécurisé (`dehydrateStateUsing` + `Hash::make`, non requis en édition, jamais pré-rempli avec le hash existant - le scaffolding `--generate` par défaut affichait le hash en clair dans le formulaire, corrigé) ; champs internes `otp_code`/`otp_expires_at` retirés du formulaire (aucune valeur pour un admin de les éditer manuellement, surface d'attaque inutile).
- `WalletResource` (groupe "Trading") : soldes formatés en devise (`->money('USD')`), relation utilisateur en select recherchable.
- `TradeHistoryResource` (groupe "Trading") : champs `mode`/`sens`/`type_ordre`/`statut` convertis en `Select` avec les vraies options enum (le scaffolding générique les avait mis en simples `TextInput` libres, ce qui aurait permis de saisir n'importe quelle valeur invalide) ; instrument en relation recherchable plutôt qu'un ID numérique brut ; badges colorés (vert/rouge sur `sens`, couleur conditionnelle sur `profit_perte`) ; filtres par mode/statut/sens ; tri par défaut sur date d'ouverture décroissante.
- `ManageSiteIdentifier` : **page Filament custom** (pas une Resource classique) puisque `site_identifiers` est un singleton (une seule ligne) - formulaire à onglets (Identité/Contact/Contenus légaux) lié directement au modèle via `SiteIdentifierService`, invalide le cache du service après sauvegarde (`SiteIdentifierService::forget()`) pour que les pages vitrine reflètent immédiatement les changements. Inclut upload des logos/favicon, color pickers pour couleur_principale/secondaire, et un champ `KeyValue` pour les réseaux sociaux.

**Organisation** : ajout de groupes de navigation cohérents sur les 10 resources qui n'en avaient pas encore (`Trading`, `Contenu`, `Parametres`, `Espace Client` - en réutilisant les noms de groupes déjà choisis par les sous-agents précédents pour Ticket/KycDocument/AffiliateCommission/WalletTransaction) + icônes Heroicon spécifiques à chaque resource (le scaffolding générique donnait `heroicon-o-rectangle-stack` à toutes, peu lisible dans le menu).

**Tables volontairement exclues du CRUD admin** : `cache`, `cache_locks`, `failed_jobs`, `job_batches`, `jobs`, `migrations`, `notifications`, `password_reset_tokens`, `sessions` - tables internes du framework Laravel, aucune valeur métier, risque si modifiées manuellement (ex: purger la table `sessions` déconnecterait tous les utilisateurs).

**Vérification** : connexion admin réelle en navigateur (`marcosseko.travail@gmail.com`), les 4 nouvelles pages testées sans erreur (`/admin/manage-site-identifier` pré-rempli avec les vraies données, `/admin/users` liste + formulaire d'édition avec champ mot de passe vide confirmé, `/admin/wallets`, `/admin/trade-histories` liste + formulaire de création avec les nouveaux selects). Menu admin vérifié : 20 entrées (19 resources/pages + tableau de bord), aucune erreur. Suite de tests complète toujours 27/27.

**Couverture finale** : 19 resources/pages Filament couvrant 19 tables métier sur 19 (100% des tables non-framework). CRUD complet conforme à l'objectif initial du projet ("Les super admin pourront gérer les CRUD de toutes les tables sql de la bdd").

## 2026-08-23 - Comble la dette technique : test de régression pour le périmètre Vitrine

Point relevé dans le bilan de session du même jour : le sous-agent Vitrine avait vérifié manuellement les 24 pages (curl + Livewire::test() en session) mais n'avait laissé aucun test automatisé, contrairement aux autres périmètres (Auth, Client, Trade). Ajouté `tests/Feature/Vitrine/PublicPagesSmokeTest.php` : les 21 pages 100% statiques (`Route::view`) testées en une passe, plus 3 tests dédiés pour les pages à paramètre dynamique (`market-detail`, `education-article`, `news-detail`) avec un enregistrement réel créé en base pour chacune. Sert de filet de sécurité contre toute régression future sur ce périmètre (ex: si une prochaine modification casse un composant partagé comme `x-page-hero` ou `x-reveal`, ce test le détecterait immédiatement).

**Résultat** : suite de tests complète désormais à **31 passed (122 assertions)**, contre 27 avant. Aucune régression détectée sur le travail de refonte visuelle des 3 sous-agents.
