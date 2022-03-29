<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/user/login',[ App\Http\Controllers\UserController::class ,'login']);
Route::get('/user/secret',[App\Http\Controllers\UserController::class,'checkAuth'])->middleware('auth:api');
Route::get('/user/logout',[App\Http\Controllers\UserController::class,'logout'])->middleware('auth:api');
