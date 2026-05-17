<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index'])->name('eventos.index');
Route::post('/eventos', [EventController::class, 'store'])->name('eventos.store');
Route::put('/eventos/{event}', [EventController::class, 'update'])->name('eventos.update');
