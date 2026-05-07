<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index'])->name('eventos.index');
Route::post('/eventos', [EventController::class, 'store'])->name('eventos.store');
