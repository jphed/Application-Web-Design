<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketWebController extends Controller
{
    // GET /tickets
    public function index()
    {
        $tickets = Ticket::orderBy('fecha_reporte', 'desc')->get();

        return view('tickets.index', compact('tickets'));
    }

    // GET /tickets/create
    public function create()
    {
        return view('tickets.create');
    }

    // POST /tickets
    public function store(Request $request)
    {
        Ticket::create($request->all());

        return $this->redirectToIndex()
            ->with('success', 'Ticket creado exitosamente.');
    }

    // GET /tickets/{ticket}
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    // GET /tickets/{ticket}/edit
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    // PUT /tickets/{ticket}
    public function update(Request $request, Ticket $ticket)
    {
        $ticket->update($request->all());

        return $this->redirectToIndex()
            ->with('success', 'Ticket actualizado correctamente.');
    }

    // DELETE /tickets/{ticket}
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return $this->redirectToIndex()
            ->with('success', 'Ticket eliminado.');
    }

    private function redirectToIndex()
    {
        $routePrefix = request()->route()->getPrefix();

        if (str_starts_with($routePrefix, 'admin')) {
            return redirect()->route('admin.tickets.index');
        }

        return redirect()->route('usuario.tickets.index');
    }
}
