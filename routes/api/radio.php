<?php

use App\Http\Controllers\V1\NP\RadioController;
use Illuminate\Support\Facades\Route;

$route->get('ds', [RadioController::class, 'web']);
$route->post('ds', [RadioController::class, 'run']);
