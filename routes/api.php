<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',[UserController::class,'create']);

Route::prefix('user')->group(function () {
    Route::get('/getUsers',[UserController::class,'index']);
});


Route::prefix('product')->group(function () {
    Route::get('/getProducts',[ProductController::class,'index']);
    Route::get('/getProduct/{id}',[ProductController::class,'show']);
    Route::put('/updateProduct/{id}',[ProductController::class,'update']);
    Route::delete('/deleteProduct/{id}',[ProductController::class,'destroy']);
    Route::post('/addProduct',[ProductController::class,'create']);
    Route::get('/getProductByName/{name}',[ProductController::class,'getProductByName']);
});


Route::prefix('ship')->group(function () {
    Route::get('/getShips',[ShipController::class,'index']);
    Route::get('/getShip/{id}',[ShipController::class,'show']);
    Route::post('/addShip',[ShipController::class,'create']);
    Route::put('/updateShip/{id}',[ShipController::class,'update']);
    Route::delete('/deleteShip/{id}',[ShipController::class,'destroy']);
});

