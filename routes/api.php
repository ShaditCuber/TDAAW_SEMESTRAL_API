<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\MovimientoBodegaController;


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/registro', 'signUp');
    Route::post('/logout', 'logout');
});


// Route::middleware('jwt.verify')->group(function () {

//     Route::prefix('products')->controller(ProductController::class)->group(function () {
//         Route::post('/create', 'create');
//         Route::get('/read', 'read');
//         Route::post('/update', 'update');
//         Route::delete('/delete', 'delete');
//         Route::post('/restore', 'restore');
//     });

//     Route::prefix('warehouse')->controller(WarehouseController::class)->group(function () {
//         Route::post('/create', 'create');
//         Route::get('/read', 'read');
//         Route::post('/update', 'update');
//         Route::delete('/delete', 'delete');
//     });
   
//     Route::prefix('stock')->controller(StockController::class)->group(function () {
//         Route::post('/create', 'create');
//         Route::get('/read', 'read');
//         Route::delete('/delete', 'delete');
//         Route::get('/resume', 'resume');
//     });

//     Route::prefix('movements')->controller(MovimientoBodegaController::class)->group(function () {
//         Route::post('/create', 'create');
//         Route::get('/read', 'read');
//         Route::post('/update', 'update');
//         Route::delete('/delete', 'delete');
//     });
    

// });

Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::post('/create', 'create');
    Route::get('/read', 'read');
    Route::post('/update', 'update');
    Route::delete('/delete', 'delete');
    Route::post('/restore', 'restore');
});

Route::prefix('warehouse')->controller(WarehouseController::class)->group(function () {
    Route::post('/create', 'create');
    Route::get('/read', 'read');
    Route::post('/update', 'update');
    Route::delete('/delete', 'delete');
});

Route::prefix('stock')->controller(StockController::class)->group(function () {
    Route::post('/create', 'create');
    Route::get('/read', 'read');
    Route::delete('/delete', 'delete');
    Route::get('/resume', 'resume');
});

Route::prefix('movements')->controller(MovimientoBodegaController::class)->group(function () {
    Route::post('/create', 'create');
    Route::get('/read', 'read');
    Route::post('/update', 'update');
    Route::delete('/delete', 'delete');
});