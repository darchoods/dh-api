<?php

use App\Http\Controllers\V1\News\NewsController;
use Illuminate\Support\Facades\Route;

$route->post('all', [NewsController::class, 'getAll']);
$route->post('{id}', [NewsController::class, 'getNewsById'])->where(['id' => '[0-9]+']);
