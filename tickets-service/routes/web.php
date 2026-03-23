<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketWebController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GerenteController;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return match (auth()->user()->rol) {
            'admin' => redirect()->route('admin.dashboard'),
            'gerente' => redirect()->route('gerente.dashboard'),
            default => redirect()->route('usuario.dashboard'),
        };
    })->name('dashboard');

    Route::middleware(['rol:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::resource('tickets', TicketWebController::class);

        Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios.index');
        Route::get('/usuarios/{user}', [AdminController::class, 'verUsuario'])->name('usuarios.show');
        Route::patch('/usuarios/{user}/cambiar-rol', [AdminController::class, 'cambiarRol'])->name('usuarios.cambiar-rol');
        Route::delete('/usuarios/{user}', [AdminController::class, 'eliminarUsuario'])->name('usuarios.destroy');
    });

    Route::middleware(['rol:admin,gerente'])->prefix('gerente')->name('gerente.')->group(function () {
        Route::get('/dashboard', [GerenteController::class, 'dashboard'])->name('dashboard');
        Route::get('/reportes', [GerenteController::class, 'reportes'])->name('reportes');
        Route::get('/tickets', [GerenteController::class, 'verTodos'])->name('tickets.index');
    });

    Route::middleware(['rol:admin,gerente,usuario'])->prefix('mis-tickets')->name('usuario.')->group(function () {
        Route::get('/dashboard', [UsuarioController::class, 'dashboard'])->name('dashboard');
        Route::get('/', [UsuarioController::class, 'index'])->name('tickets.index');
        Route::get('/crear', [UsuarioController::class, 'create'])->name('tickets.create');
        Route::post('/', [UsuarioController::class, 'store'])->name('tickets.store');
        Route::get('/{ticket}', [UsuarioController::class, 'show'])->name('tickets.show');
    });
});

