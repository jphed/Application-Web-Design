<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index() {
        // Obtenemos todos los eventos de la DB
        return Inertia::render('FrikiIndex', [
            'eventos' => Event::latest()->get()
        ]);
    }

    public function store(Request $request) {
        // Validación a prueba de errores
        //dd($request->all());
        $data = $request->validate([
            'nombre' => 'required|min:3',
            'categoria' => 'required',
            'fecha' => 'required|date',
            'descripcion' => 'required|min:10',
        ]);
        Event::create($data);
        return redirect()->back()->with('success', '¡Evento registrado con éxito, nakama!');
    }
}
