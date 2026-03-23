<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function dashboard()
    {
        $misTickets = Ticket::where('cliente_email', auth()->user()->email)
            ->orderBy('fecha_reporte', 'desc')
            ->take(5)->get();

        return view('usuario.dashboard', compact('misTickets'));
    }

    public function index()
    {
        $tickets = Ticket::where('cliente_email', auth()->user()->email)
            ->orderBy('fecha_reporte', 'desc')->get();

        return view('usuario.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('usuario.tickets.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'descripcion_corta' => 'required|max:255',
            'categoria' => 'required|in:software,hardware,comunicaciones,plataformas,email,otro',
            'nivel_urgencia' => 'required|in:baja,media,alta,critica',
            'descripcion_detallada' => 'nullable',
            'departamento' => 'required|max:100',
        ]);

        $datos['numero_reporte'] = 'TKT-' . date('Y') . '-' . str_pad(Ticket::count() + 1, 4, '0', STR_PAD_LEFT);
        $datos['cliente_nombre'] = auth()->user()->name;
        $datos['cliente_email'] = auth()->user()->email;
        $datos['fecha_reporte'] = now();
        $datos['status'] = 'pendiente';

        Ticket::create($datos);

        return redirect()->route('usuario.tickets.index')
            ->with('success', 'Ticket creado exitosamente.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->cliente_email !== auth()->user()->email) {
            abort(403, 'No tienes acceso a este ticket.');
        }

        return view('usuario.tickets.show', compact('ticket'));
    }
}
