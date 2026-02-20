<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function create()
    {
        return view('registro');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Usuario::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/usuarios')->with('success', 'Usuario registrado exitosamente');
    }

    public function index()
    {
        $usuarios = Usuario::all();
        return view('lista', compact('usuarios'));
    }
}
