@extends('layouts.tickets')
@section('title', 'Mi Panel')
@section('content')
<div class="container py-4">
 <h2 class="mb-3">Bienvenido, {{ auth()->user()->name }}</h2>
 <p class="text-muted">Aquí puedes ver y gestionar tus tickets de soporte.</p>
 <div class="d-flex gap-2 mb-4">
 <a href="{{ route('usuario.tickets.create') }}" class="btn btn-primary">
 + Nuevo Ticket
 </a>
 <a href="{{ route('usuario.tickets.index') }}" class="btn btn-outline-primary">
 Ver Mis Tickets
 </a>
 </div>
 <h5>Últimos tickets</h5>
 <table class="table table-bordered">
 <thead class="table-light">
 <tr>
 <th>Número</th><th>Descripción</th><th>Estado</th>
 </tr>
 </thead>
 <tbody>
 @forelse ($misTickets as $ticket)
 <tr>
 <td>{{ $ticket->numero_reporte }}</td>
 <td>{{ $ticket->descripcion_corta }}</td>
 <td><span class="badge bg-secondary">{{ $ticket->status }}</span></td>
 </tr>
 @empty
 <tr><td colspan="3" class="text-center">No tienes tickets aún.</td></tr>
 @endforelse
 </tbody>
 </table>
</div>
@endsection
