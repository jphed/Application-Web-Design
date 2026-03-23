<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuoteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('quotes')->group(function () {
    Route::get('/', [QuoteController::class, 'index']);
    Route::post('/', [QuoteController::class, 'store']);
    Route::put('/{id}', [QuoteController::class, 'update']);
    Route::delete('/{id}', [QuoteController::class, 'destroy']);
});
