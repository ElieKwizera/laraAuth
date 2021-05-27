<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function()
{
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::apiResource('products', ProductsController::class);

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::get('/products/search/{name}', [ProductsController::class, 'searchByName']);
    Route::get('/auth/getme', [AuthController::class, 'getUser']);
});