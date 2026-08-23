<?php

namespace Tests\Feature;

use App\Livewire\Trade\OpenPositions;
use App\Livewire\Trade\OrderForm;
use App\Livewire\Trade\TradePage;
use App\Livewire\Trade\Watchlist;
use App\Models\MarketInstrument;
use App\Models\TradeHistory;
use App\Models\User;
use App\Services\TradingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

/**
 * Tests du cycle de vie complet d'un trade (ouverture + cloture), voir xendaro-fox-plan.json >
 * Page id 37 "trade" > fonctionnalite "integration_donnees": "aucune fonctionnalite de la page
 * Trade ne doit etre livree sans test couvrant au minimum le happy path d'ouverture et de
 * cloture de position."
 *
 * Ces tests exercent directement App\Services\TradingService (la logique metier), sans passer
 * par les composants Livewire ni HTTP: /trade exige une authentification web (redirection 302
 * vers /connexion sinon), donc le happy path est verifie au niveau service, la couche la plus
 * fiable et la plus rapide a tester pour ce coeur de projet.
 */
class TradeFlowTest extends TestCase
{
    use RefreshDatabase;

    private function creerInstrument(array $overrides = []): MarketInstrument
    {
        return MarketInstrument::create(array_merge([
            'nom' => 'Euro / Dollar US',
            'symbole_interne' => 'EURUSD',
            'categorie' => 'forex',
            'symbole_provider_externe' => 'FX:EURUSD',
            'provider' => 'tradingview',
            'spread' => 0.0001,
            'levier_max' => 100,
            'prix_reference' => 1.1000,
            'est_actif' => true,
        ], $overrides));
    }

    public function test_un_nouvel_utilisateur_recoit_automatiquement_un_wallet_avec_10000_en_demo(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->wallet);
        $this->assertEquals(10000.0, (float) $user->wallet->solde_demo);
        $this->assertEquals(0.0, (float) $user->wallet->solde_reel);
    }

    public function test_ouverture_dune_position_demo_cree_une_ligne_trade_history_ouverte_sans_debiter_le_solde(): void
    {
        $user = User::factory()->create();
        $instrument = $this->creerInstrument();
        $soldeInitial = (float) $user->wallet->solde_demo;

        $trade = TradingService::openPosition(
            user: $user,
            instrument: $instrument,
            mode: 'demo',
            sens: 'buy',
            volume: 1.0,
        );

        $this->assertDatabaseHas('trade_histories', [
            'id' => $trade->id,
            'user_id' => $user->id,
            'market_instrument_id' => $instrument->id,
            'mode' => 'demo',
            'sens' => 'buy',
            'statut' => 'ouvert',
        ]);

        $this->assertEquals('ouvert', $trade->statut);
        $this->assertNotNull($trade->ouvert_le);
        $this->assertNull($trade->cloture_le);
        $this->assertNull($trade->profit_perte);
        $this->assertGreaterThan(0, (float) $trade->prix_ouverture);

        // Formule de marge documentee dans TradingService: (volume * prix * 100) / levier_max.
        $margeAttendue = round((1.0 * (float) $trade->prix_ouverture * 100) / $instrument->levier_max, 2);
        $this->assertEqualsWithDelta($margeAttendue, (float) $trade->marge_utilisee, 0.01);

        // Decision de modelisation MVP: le solde brut n'est jamais decremente a l'ouverture,
        // seule marge_utilisee trace la marge (voir doc de TradingService::openPosition).
        $user->wallet->refresh();
        $this->assertEquals($soldeInitial, (float) $user->wallet->solde_demo);
    }

    public function test_cloture_dune_position_gagnante_met_a_jour_la_ligne_et_credite_le_wallet(): void
    {
        $user = User::factory()->create();
        $instrument = $this->creerInstrument(['prix_reference' => 100.0, 'levier_max' => 100]);
        $soldeInitial = (float) $user->wallet->solde_demo;

        $trade = TradingService::openPosition(
            user: $user,
            instrument: $instrument,
            mode: 'demo',
            sens: 'buy',
            volume: 2.0,
        );

        $prixOuverture = (float) $trade->prix_ouverture;
        $prixCloture = $prixOuverture + 5; // hausse de 5 => gain pour un achat (buy)

        $tradeCloture = TradingService::closePosition($trade, $prixCloture);

        $profitAttendu = round(($prixCloture - $prixOuverture) * 2.0, 2);

        $this->assertEquals('cloture', $tradeCloture->statut);
        $this->assertNotNull($tradeCloture->cloture_le);
        $this->assertEqualsWithDelta($prixCloture, (float) $tradeCloture->prix_cloture, 0.00001);
        $this->assertEqualsWithDelta($profitAttendu, (float) $tradeCloture->profit_perte, 0.01);
        $this->assertGreaterThan(0, $profitAttendu, 'Le scenario de test doit produire un gain pour verifier le credit du wallet.');

        $this->assertDatabaseHas('trade_histories', [
            'id' => $trade->id,
            'statut' => 'cloture',
        ]);

        $user->wallet->refresh();
        $this->assertEqualsWithDelta($soldeInitial + $profitAttendu, (float) $user->wallet->solde_demo, 0.01);
    }

    public function test_cloture_dune_position_perdante_debite_le_wallet_du_montant_de_la_perte(): void
    {
        $user = User::factory()->create();
        $instrument = $this->creerInstrument(['prix_reference' => 100.0, 'levier_max' => 100]);
        $soldeInitial = (float) $user->wallet->solde_demo;

        $trade = TradingService::openPosition(
            user: $user,
            instrument: $instrument,
            mode: 'demo',
            sens: 'buy',
            volume: 1.0,
        );

        $prixOuverture = (float) $trade->prix_ouverture;
        $prixCloture = $prixOuverture - 3; // baisse => perte pour un achat (buy)

        $tradeCloture = TradingService::closePosition($trade, $prixCloture);

        $perteAttendue = round(($prixCloture - $prixOuverture) * 1.0, 2);

        $this->assertLessThan(0, $perteAttendue, 'Le scenario de test doit produire une perte pour verifier le debit du wallet.');
        $this->assertEqualsWithDelta($perteAttendue, (float) $tradeCloture->profit_perte, 0.01);

        $user->wallet->refresh();
        $this->assertEqualsWithDelta($soldeInitial + $perteAttendue, (float) $user->wallet->solde_demo, 0.01);
    }

    public function test_cloture_dune_position_sell_calcule_le_profit_dans_le_bon_sens(): void
    {
        $user = User::factory()->create();
        $instrument = $this->creerInstrument(['prix_reference' => 100.0, 'levier_max' => 100]);

        $trade = TradingService::openPosition(
            user: $user,
            instrument: $instrument,
            mode: 'demo',
            sens: 'sell',
            volume: 1.0,
        );

        $prixOuverture = (float) $trade->prix_ouverture;
        $prixCloture = $prixOuverture - 4; // baisse => gain pour une vente (sell)

        $tradeCloture = TradingService::closePosition($trade, $prixCloture);

        $profitAttendu = round(($prixOuverture - $prixCloture) * 1.0, 2);

        $this->assertGreaterThan(0, $profitAttendu);
        $this->assertEqualsWithDelta($profitAttendu, (float) $tradeCloture->profit_perte, 0.01);
    }

    public function test_ouverture_refusee_si_la_marge_libre_est_insuffisante(): void
    {
        $user = User::factory()->create(); // solde_demo = 10000 par defaut
        // Levier tres faible + prix eleve => marge largement superieure au solde disponible.
        $instrument = $this->creerInstrument(['prix_reference' => 100000.0, 'levier_max' => 1]);

        try {
            TradingService::openPosition(
                user: $user,
                instrument: $instrument,
                mode: 'demo',
                sens: 'buy',
                volume: 10.0,
            );
            $this->fail('Une RuntimeException aurait du etre levee pour marge libre insuffisante.');
        } catch (RuntimeException $e) {
            $this->assertNotEmpty($e->getMessage());
        }

        $this->assertDatabaseCount('trade_histories', 0);
    }

    public function test_cloturer_une_position_deja_cloturee_est_sans_effet_idempotent(): void
    {
        $user = User::factory()->create();
        $instrument = $this->creerInstrument();

        $trade = TradingService::openPosition($user, $instrument, 'demo', 'buy', 1.0);
        $premiereCloture = TradingService::closePosition($trade, (float) $trade->prix_ouverture + 1);

        $soldeApresPremiereCloture = (float) $user->wallet->fresh()->solde_demo;

        $secondeCloture = TradingService::closePosition($premiereCloture->fresh(), (float) $trade->prix_ouverture + 999);

        $this->assertEquals(
            (float) $premiereCloture->profit_perte,
            (float) $secondeCloture->profit_perte,
            'Une position deja cloturee ne doit pas etre recalculee.'
        );
        $this->assertEqualsWithDelta($soldeApresPremiereCloture, (float) $user->wallet->fresh()->solde_demo, 0.01);
    }

    public function test_la_route_trade_redirige_vers_la_connexion_si_non_authentifie(): void
    {
        $response = $this->get('/trade');

        $response->assertRedirect();
        $response->assertStatus(302);
    }

    public function test_la_page_trade_se_rend_sans_erreur_pour_un_utilisateur_authentifie(): void
    {
        $user = User::factory()->create();
        $this->creerInstrument();

        $response = $this->actingAs($user)->get('/trade');

        $response->assertOk();
        $response->assertSee($user->email);
    }

    public function test_la_page_trade_se_rend_meme_sans_aucun_instrument_actif(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/trade');

        $response->assertOk();
    }

    // ------------------------------------------------------------------
    // Tests des composants Livewire eux-memes (mount/render/actions/events),
    // au-dela de la logique metier deja couverte via TradingService ci-dessus.
    // ------------------------------------------------------------------

    public function test_livewire_trade_page_monte_avec_le_mode_demo_par_defaut_et_un_instrument_actif(): void
    {
        $user = User::factory()->create();
        $instrument = $this->creerInstrument();

        Livewire::actingAs($user)
            ->test(TradePage::class)
            ->assertSet('modeActif', 'demo')
            ->assertSet('activeInstrumentId', $instrument->id);
    }

    public function test_livewire_order_form_ouvre_une_position_et_dispatch_trade_opened(): void
    {
        $user = User::factory()->create();
        $instrument = $this->creerInstrument();

        Livewire::actingAs($user)
            ->test(OrderForm::class, ['instrumentId' => $instrument->id, 'modeActif' => 'demo'])
            ->set('volume', 1.0)
            ->call('placerOrdre', 'buy')
            ->assertDispatched('trade-opened')
            ->assertDispatched('toast');

        $this->assertDatabaseHas('trade_histories', [
            'user_id' => $user->id,
            'market_instrument_id' => $instrument->id,
            'sens' => 'buy',
            'statut' => 'ouvert',
        ]);
    }

    public function test_livewire_order_form_toggle_demo_reel_diffuse_levenement_mode_changed(): void
    {
        $user = User::factory()->create();
        $instrument = $this->creerInstrument();

        Livewire::actingAs($user)
            ->test(OrderForm::class, ['instrumentId' => $instrument->id, 'modeActif' => 'demo'])
            ->set('modeReel', true)
            ->assertDispatched('mode-changed', mode: 'reel')
            ->assertSet('modeActif', 'reel');
    }

    public function test_livewire_watchlist_selectionner_un_instrument_dispatch_symbol_selected(): void
    {
        $user = User::factory()->create();
        $instrument = $this->creerInstrument();

        Livewire::actingAs($user)
            ->test(Watchlist::class)
            ->call('selectInstrument', $instrument->id)
            ->assertDispatched('symbol-selected', instrumentId: $instrument->id)
            ->assertSet('activeInstrumentId', $instrument->id);
    }

    public function test_livewire_open_positions_cloture_une_position_et_dispatch_trade_closed(): void
    {
        $user = User::factory()->create();
        $instrument = $this->creerInstrument(['prix_reference' => 100.0, 'levier_max' => 100]);

        $trade = TradingService::openPosition($user, $instrument, 'demo', 'buy', 1.0);

        Livewire::actingAs($user)
            ->test(OpenPositions::class, ['modeActif' => 'demo'])
            ->call('closePosition', $trade->id)
            ->assertDispatched('trade-closed')
            ->assertDispatched('toast');

        $this->assertEquals('cloture', TradeHistory::find($trade->id)->statut);
    }

    public function test_livewire_open_positions_ne_peut_pas_cloturer_la_position_dun_autre_utilisateur(): void
    {
        $proprietaire = User::factory()->create();
        $intrus = User::factory()->create();
        $instrument = $this->creerInstrument();

        $trade = TradingService::openPosition($proprietaire, $instrument, 'demo', 'buy', 1.0);

        // La requete TradeHistory::where('user_id', auth()->id())->findOrFail() du composant
        // ne trouve aucune ligne pour l'intrus: ModelNotFoundException levee, position intacte.
        try {
            Livewire::actingAs($intrus)
                ->test(OpenPositions::class, ['modeActif' => 'demo'])
                ->call('closePosition', $trade->id);

            $this->fail('Une ModelNotFoundException aurait du etre levee.');
        } catch (ModelNotFoundException $e) {
            $this->assertTrue(true);
        }

        $this->assertEquals('ouvert', TradeHistory::find($trade->id)->statut);
    }
}
