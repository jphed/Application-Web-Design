<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrikiFestController;

Route::get('/', [FrikiFestController::class, 'inicio'])->name('inicio');
Route::get('/comics', [FrikiFestController::class, 'comics'])->name('comics');
Route::get('/anime', [FrikiFestController::class, 'anime'])->name('anime');
Route::get('/videojuegos', [FrikiFestController::class, 'videojuegos'])->name('videojuegos');
Route::get('/contacto', [FrikiFestController::class, 'contacto'])->name('contacto');
Route::post('/contacto', [FrikiFestController::class, 'enviarMensaje'])->name('contacto.enviar');
