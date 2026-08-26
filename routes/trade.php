<?php

use App\Http\Controllers\TradeChartController;
use App\Livewire\Trade\TradePage;
use Illuminate\Support\Facades\Route;

/*
 * Route de la page Trade (Page id 37 dans xendaro-fox-plan.json) - coeur du projet.
 * Module reserve au sous-agent "trade" - ne pas toucher aux autres fichiers routes/*.php.
 * Layout de reference: resources/views/components/layouts/trade.blade.php (plein ecran, sans sidebar/navbar dashboard)
 */

Route::middleware(['auth'])->group(function () {
    Route::get('/trade', TradePage::class)->name('trade');
    Route::get('/trade/chart-data/{instrument}', [TradeChartController::class, 'history'])->name('trade.chart-data');
});
