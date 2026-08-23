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
