<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('products.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'precio' => ['required', 'numeric'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
        ]);

        Producto::create($validated);

        return redirect()->route('products.show');
    }

    public function show()
    {
        $productos = Producto::with('categoria')->orderBy('id', 'desc')->get();

        return view('products.show', compact('productos'));
    }
}
