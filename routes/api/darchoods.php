<?php

use Illuminate\Support\Facades\Route;

$route->post('login', [AuthController::class, 'login']);

// $route->group(['middleware' => ''])
