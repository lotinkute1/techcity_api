<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\discountController;
use App\Http\Controllers\discountDetailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;
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

Route::post('/register', [UserController::class, 'create']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    // user api
    Route::prefix('user')->group(function () {
        Route::get('/getUsers', [UserController::class, 'index']);
        Route::get('/getUserById/{id}', [UserController::class, 'show']);
        Route::put('/updateUser/{id}', [UserController::class, 'update']);
        Route::get('/userFilter', [UserController::class, 'userFilter']);

        Route::middleware(['checkrole'])->group(function () {
            Route::delete('/deleteUser/{id}', [UserController::class, 'destroy']);
        });
    });
    // category api
    Route::prefix('category')->group(function () {
        Route::get('/getCategories', [CategoryController::class, 'index']);
        Route::get('/getCategoriesByName/{name}', [CategoryController::class, 'getCategoriesByName']);
        Route::get('/getCategoryById/{id}', [CategoryController::class, 'show']);
        Route::post('/addCategory', [CategoryController::class, 'addCategory']);
        Route::middleware(['checkrole'])->group(function () {
            Route::delete('/deleteCategoryById/{id}', [CategoryController::class, 'destroy']);
            Route::Put('/updateCategory/{id}', [CategoryController::class, 'update']);
        });
    });
});

// product api
Route::prefix('product')->group(function () {
    Route::get('/getProducts', [ProductController::class, 'index']);
    Route::get('/getProduct/{id}', [ProductController::class, 'show']);
    Route::put('/updateProduct/{id}', [ProductController::class, 'update']);
    Route::get('/productFilter', [ProductController::class, 'productFilter']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::middleware(['checkrole'])->group(function () {
            Route::post('/addProduct', [ProductController::class, 'create']);
            Route::delete('/deleteProduct/{id}', [ProductController::class, 'destroy']);
        });
    });
});
// ship api
Route::middleware('auth:sanctum')->group(function () {
Route::prefix('ship')->group(function () {
    Route::get('/getShips', [ShipController::class, 'index']);
    Route::get('/getShip/{id}', [ShipController::class, 'show']);
    Route::post('/addShip', [ShipController::class, 'create']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::middleware(['checkrole'])->group(function () {
            Route::put('/updateShip/{id}', [ShipController::class, 'update']);
            Route::delete('/deleteShip/{id}', [ShipController::class, 'destroy']);
        });
    });
});
});

// discount api
Route::middleware('auth:sanctum', 'checkrole')->prefix('discount')->group(function () {
    Route::get('/getDiscounts', [discountController::class, 'index']);
    Route::get('/getDiscount/{id}', [discountController::class, 'show']);
    Route::post('/addDiscount', [discountController::class, 'create']);
    Route::put('/updateDiscount/{id}', [discountController::class, 'updateDiscount']);
    Route::delete('/deleteDiscount/{id}', [discountController::class, 'destroy']);
    Route::get('/findDiscountByName/{name}', [discountController::class, 'findByName']);
    // discount Detail api
    Route::get('/getDiscountsDetail', [discountDetailController::class, 'index']);
    Route::get('/getDiscountDetail/{id}', [discountDetailController::class, 'show']);
    Route::post('/addDiscountDetail', [discountDetailController::class, 'create']);
    Route::put('/updateDiscountDetail/{id}', [discountDetailController::class, 'update']);
    Route::delete('/deleteDiscountDetail/{id}', [discountDetailController::class, 'destroy']);
});
// order api
Route::middleware('auth:sanctum')->prefix('order')->group(function () {
    Route::get('/getOrders', [OrderController::class, 'index']);
    Route::get('/getOrder/{id}', [OrderController::class, 'show']);
    Route::post('/addOrder', [OrderController::class, 'create']);
    Route::put('/updateOrder/{id}', [OrderController::class, 'update']);
    Route::delete('/deleteOrder/{id}', [OrderController::class, 'destroy']);



    // order api
    Route::get('/getOrdersDetail', [OrderDetailController::class, 'index']);
    Route::get('/getOrderDetail/{id}', [OrderDetailController::class, 'show']);
    Route::post('/addOrderDetail', [OrderDetailController::class, 'create']);
    Route::put('/updateOrderDetail/{id}', [OrderDetailController::class, 'update']);
    Route::delete('/deleteOrderDetail/{id}', [OrderDetailController::class, 'destroy']);
});


//rating api
Route::prefix('rating')->group(function () {
    Route::get('/getRatings', [RatingController::class, 'index']);
    Route::post('/addRating', [RatingController::class, 'create']);
    Route::get('/getRatingById/{id}', [RatingController::class, 'show']);
    Route::put('/updateRating/{id}', [RatingController::class, 'update']);
    Route::delete('/deleteRating/{id}', [RatingController::class, 'destroy']);
});


