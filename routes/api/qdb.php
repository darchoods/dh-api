<?php

use App\Http\Controllers\V1\QDB\QuoteController;
use Illuminate\Support\Facades\Route;

// $route->group(['prefix' => 'search'], function() {
//     $route->post('byId', [ReadController::class, 'postFindById']);
// });
// $route->get('channels', [ReadController::class, 'getChannels']);
$route->post('random', [QuoteController::class, 'findRandom']);
$route->post('create', [QuoteController::class, 'create']);
// $route->post('delete', [ModifyController::class, 'postDeleteById']);

