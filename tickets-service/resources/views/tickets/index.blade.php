@extends('layouts.tickets')
@section('title', 'Todos los Tickets')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
 <h2 class="fw-bold"> Tickets de Soporte</h2>
 <span class="text-muted">Total: {{ $tickets->count() }}</span>
</div>
@if($tickets->isEmpty())
 <div class="alert alert-info text-center">
 No hay tickets. <a href="{{ route('admin.tickets.create') }}">Crea el
primero.</a>
 </div>
@else
<table class="table table-hover bg-white shadow-sm rounded">
 <thead class="table-dark">
 <tr>
 <th># Reporte</th><th>Cliente</th><th>Depto.</th>
 <th>Categoría</th><th>Urgencia</th><th>Status</th>
 <th>Técnico</th><th>Acciones</th>
 </tr>
 </thead>
 <tbody>
 @foreach($tickets as $ticket)
 <tr>
 <td><code>{{ $ticket->numero_reporte }}</code></td>
 <td>{{ $ticket->cliente_nombre }}</td>
 <td>{{ $ticket->departamento }}</td>
 <td>{{ ucfirst($ticket->categoria) }}</td>
 <td>
 @php
 $colorMap = ['baja'=>'success','media'=>'info','alta'=>'warning','critica'=>'danger'];
 $color = $colorMap[$ticket->nivel_urgencia] ?? 'secondary';
 @endphp
 <span class="badge bg-{{ $color }}">{{ ucfirst($ticket->nivel_urgencia) }}</span>
 </td>
 <td>
 <span class="badge badge-{{ $ticket->status }}">
 {{ ucfirst(str_replace('_',' ',$ticket->status)) }}
 </span>
 </td>
 <td>{{ $ticket->tecnico_asignado ?? '-' }}</td>
 <td>
 <a href="{{ route('admin.tickets.show',$ticket) }}"
 class="btn btn-sm btn-outline-primary">Ver</a>
 <a href="{{ route('admin.tickets.edit',$ticket) }}"
 class="btn btn-sm btn-outline-warning">Editar</a>
 @if(auth()->user()->rol == 'admin' || auth()->user()->rol == 'gerente')
 <form action="{{ route('admin.tickets.close', $ticket) }}" method="POST" class="d-inline">
     @csrf
     @method('PATCH')
     <button type="submit" class="btn btn-sm btn-outline-success">Cerrar</button>
 </form>
 @endif
 <form action="{{ route('admin.tickets.destroy',$ticket) }}"
method="POST"
 class="d-inline"
onsubmit="return confirm('¿Eliminar?')">
 @csrf @method('DELETE')
 <button class="btn btn-sm btn-outline-danger">Eliminar</button>
 </form>
 </td>
 </tr>
 @endforeach
 </tbody>
</table>
@endif
@endsection
