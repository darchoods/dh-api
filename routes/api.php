<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'news'], function($route) {
    return require base_path('routes/api/news.php');
});
Route::group(['prefix' => 'np'], function($route) {
    return require base_path('routes/api/radio.php');
});

Route::group(['middleware' => 'auth.api'], function($route) {

    Route::group(['prefix' => 'radio'], function($route) {
        return require base_path('routes/api/radio.php');
    });
    Route::group(['prefix' => 'irc'], function($route) {
        return require base_path('routes/api/irc.php');
    });
    Route::group(['prefix' => 'qdb'], function($route) {
        return require base_path('routes/api/qdb.php');
    });

});
