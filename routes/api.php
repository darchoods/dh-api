<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'darchoods'], function($route) {
    return require base_path('routes/api/darchoods.php');
});
Route::group(['prefix' => 'qdb', 'middleware' => 'auth.api'], function($route) {
    return require base_path('routes/api/qdb.php');
});
Route::group(['prefix' => 'irc', 'middleware' => 'auth.api'], function($route) {
    return require base_path('routes/api/irc.php');
});
