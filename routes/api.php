<?php

use App\Http\Controllers\V1\QuoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'qdb'], function() {
    // Route::group(['prefix' => 'search'], function() {
    //     Route::post('byId', [ReadController::class, 'postFindById']);
    // });
    // Route::get('channels', [ReadController::class, 'getChannels']);
    Route::post('random', [QuoteController::class, 'findRandom']);
    Route::post('create', [QuoteController::class, 'create']);
    // Route::post('delete', [ModifyController::class, 'postDeleteById']);
});
