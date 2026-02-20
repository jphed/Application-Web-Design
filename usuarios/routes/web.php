<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registro', [UsuarioController::class, 'create'])->name('register');
Route::post('/registro', [UsuarioController::class, 'store']);
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
