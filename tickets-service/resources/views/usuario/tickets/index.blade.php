@extends('layouts.tickets')
@section('title', 'Mis Tickets')
@section('content')
<div class="container py-4">
 <h2 class="mb-3">Mis Tickets</h2>
 <table class="table table-bordered table-hover">
 <thead class="table-dark">
 <tr>
 <th>#</th><th>Descripción</th><th>Estado</th><th>Urgencia</th><th>Acciones</th>
 </tr>
 </thead>
 <tbody>
 @foreach($tickets as $ticket)
 <tr>
 <td><code>{{ $ticket->numero_reporte }}</code></td>
 <td>{{ $ticket->descripcion_corta }}</td>
 <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span></td>
 <td><span class="badge bg-warning">{{ ucfirst($ticket->nivel_urgencia) }}</span></td>
 <td>
 <a href="{{ route('usuario.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Ver</a>
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
</div>
@endsection
