<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController; // ← Cambia si tu controlador se llama diferente
// Ruta principal (opcional, pero buena práctica)
Route::get('/', function () {
 return redirect()->route('products.index');
});
// Ruta de recurso completa para productos
Route::resource('products', ProductController::class);