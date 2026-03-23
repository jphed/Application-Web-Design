@extends('layouts.tickets')
@section('title', 'Todos los Tickets')
@section('content')
<div class="container py-4">
 <h2 class="mb-4">Todos los Tickets</h2>
 <table class="table table-bordered table-hover">
 <thead class="table-dark">
 <tr>
 <th>#</th><th>Cliente</th><th>Descripción</th><th>Estado</th><th>Urgencia</th>
 </tr>
 </thead>
 <tbody>
 @foreach($tickets as $ticket)
 <tr>
 <td><code>{{ $ticket->numero_reporte }}</code></td>
 <td>{{ $ticket->cliente_nombre }}</td>
 <td>{{ $ticket->descripcion_corta }}</td>
 <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span></td>
 <td><span class="badge bg-warning">{{ ucfirst($ticket->nivel_urgencia) }}</span></td>
 </tr>
 @endforeach
 </tbody>
 </table>
</div>
@endsection
