<?php

use Illuminate\Support\Facades\Route;

/*
 * Routes des pages vitrines (Pages id 1 a 24 dans xendaro-fox-plan.json).
 * Module reserve aux sous-agents "vitrine" - ne pas toucher aux autres fichiers routes/*.php.
 */

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::view('/nos-services', 'vitrine.our-services')->name('our-services');
Route::view('/nos-services/types-de-comptes', 'vitrine.account-types')->name('account-types');
Route::view('/nos-services/plateformes', 'vitrine.platforms')->name('platforms');
Route::view('/nos-services/conditions-de-trading', 'vitrine.trading-conditions')->name('trading-conditions');

Route::view('/a-propos', 'vitrine.about')->name('about');
Route::view('/securite', 'vitrine.why-us')->name('why-us');

Route::get('/marches', [App\Http\Controllers\MarketController::class, 'index'])->name('markets');
Route::get('/marches/{instrument:symbole_interne}', [App\Http\Controllers\MarketController::class, 'show'])->name('market-detail');
Route::view('/promotions', 'vitrine.promotions')->name('promotions');
Route::view('/parrainage', 'vitrine.affiliate-program')->name('affiliate-program');

Route::get('/academie', [App\Http\Controllers\EducationController::class, 'index'])->name('education');
Route::get('/academie/{resource:slug}', [App\Http\Controllers\EducationController::class, 'show'])->name('education-article');

Route::get('/actualites', [App\Http\Controllers\NewsController::class, 'index'])->name('market-news');
Route::get('/actualites/{article:slug}', [App\Http\Controllers\NewsController::class, 'show'])->name('news-detail');

Route::view('/calendrier-economique', 'vitrine.economic-calendar')->name('economic-calendar');
Route::view('/outils', 'vitrine.trading-tools')->name('trading-tools');

Route::view('/faq', 'vitrine.faq')->name('faq');
Route::view('/contact', 'vitrine.contact')->name('contact');

Route::view('/cgv', 'vitrine.cgv')->name('cgv');
Route::view('/confidentialite', 'vitrine.policies')->name('policies');
Route::view('/cookies', 'vitrine.cookies')->name('cookies');
Route::view('/avertissement-risques', 'vitrine.risk-disclosure')->name('risk-disclosure');
Route::view('/politique-aml', 'vitrine.aml-policy')->name('aml-policy');
