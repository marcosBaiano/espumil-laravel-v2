<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Liquido\ParadaLiquidoController;

Route::prefix('liquido')->name('liquido.')->group(function () {

    Route::get('/paradas', [ParadaLiquidoController::class, 'index'])
        ->name('paradas.index');

    Route::get('/paradas/create', [ParadaLiquidoController::class, 'create'])
        ->name('paradas.create');

    Route::post('/paradas', [ParadaLiquidoController::class, 'store'])
        ->name('paradas.store');

});
