<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrikiFestController extends Controller
{
    public function inicio()
    {
        $noticias = [
            ['titulo' => '¡Nueva temporada de Chainsaw Man!', 'tipo' => 'anime', 'caliente' => true],
            ['titulo' => 'Spider-Man: Las Mejores Portadas', 'tipo' => 'comics', 'caliente' => false],
            ['titulo' => 'GTA VI: Todo lo que sabemos', 'tipo' => 'videojuegos','caliente' => true],
            ['titulo' => 'Attack on Titan: Explicación final','tipo' => 'anime', 'caliente' => false],
        ];
        return view('inicio', compact('noticias'));
    }

    public function comics()
    {
        $comics = [
            ['nombre' => 'Spider-Man', 'universo' => 'Marvel', 'anio' => 1962, 'activo' => true],
            ['nombre' => 'Batman', 'universo' => 'DC', 'anio' => 1939, 'activo' => true],
            ['nombre' => 'Deadpool', 'universo' => 'Marvel', 'anio' => 1991, 'activo' => true],
            ['nombre' => 'The Flash', 'universo' => 'DC', 'anio' => 1940, 'activo' => false],
        ];
        return view('comics', compact('comics'));
    }

    public function anime()
    {
        $series = []; // Array vacío para demostrar @forelse/@empty
        return view('anime', compact('series'));
    }

    public function videojuegos()
    {
        $juegos = [
            ['nombre' => 'Elden Ring', 'genero' => 'RPG', 'calificacion' => 10],
            ['nombre' => 'Celeste', 'genero' => 'Platformer','calificacion' => 9],
            ['nombre' => 'Among Us', 'genero' => 'Social', 'calificacion' => 7],
            ['nombre' => 'Cyberpunk 2077', 'genero' => 'RPG', 'calificacion' => 8],
        ];
        return view('videojuegos', compact('juegos'));
    }

    public function contacto()
    {
        return view('contacto');
    }

    public function enviarMensaje(Request $request)
    {
        $request->validate([
            'nombre' => 'required|min:3',
            'email' => 'required|email',
            'mensaje' => 'required|min:10',
        ]);
        return back()->with('success', '¡Mensaje enviado!');
    }
}
