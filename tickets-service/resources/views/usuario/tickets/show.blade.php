@extends('layouts.tickets')
@section('title', 'Ticket ' . $ticket->numero_reporte)
@section('content')
<div class="container py-4">
 <div class="row justify-content-center">
 <div class="col-lg-8">
 <div class="card shadow-sm">
 <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
 <h5 class="mb-0">{{ $ticket->numero_reporte }}</h5>
 <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
 </div>
 <div class="card-body">
 <div class="row g-3">
 <div class="col-md-6">
 <p class="mb-1 text-muted small">Cliente</p>
 <strong>{{ $ticket->cliente_nombre }}</strong>
 </div>
 <div class="col-md-6">
 <p class="mb-1 text-muted small">Email</p>
 <strong>{{ $ticket->cliente_email }}</strong>
 </div>
 <div class="col-md-6">
 <p class="mb-1 text-muted small">Departamento</p>
 <strong>{{ $ticket->departamento }}</strong>
 </div>
 <div class="col-md-6">
 <p class="mb-1 text-muted small">Categoría</p>
 <strong>{{ ucfirst($ticket->categoria) }}</strong>
 </div>
 <div class="col-md-6">
 <p class="mb-1 text-muted small">Urgencia</p>
 <strong>{{ ucfirst($ticket->nivel_urgencia) }}</strong>
 </div>
 <div class="col-md-6">
 <p class="mb-1 text-muted small">Fecha Reporte</p>
 <strong>{{ $ticket->fecha_reporte?->format('d/m/Y H:i') }}</strong>
 </div>
 <div class="col-12">
 <p class="mb-1 text-muted small">Descripción Corta</p>
 <strong>{{ $ticket->descripcion_corta }}</strong>
 </div>
 @if($ticket->descripcion_detallada)
 <div class="col-12">
 <p class="mb-1 text-muted small">Descripción Detallada</p>
 <p>{{ $ticket->descripcion_detallada }}</p>
 </div>
 @endif
 </div>
 </div>
 <div class="card-footer">
 <a href="{{ route('usuario.tickets.index') }}" class="btn btn-secondary">Volver</a>
 </div>
 </div>
 </div>
 </div>
</div>
@endsection
