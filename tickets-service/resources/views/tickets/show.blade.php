@extends('layouts.tickets')
@section('title', 'Ticket ' . $ticket->numero_reporte)
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card shadow-sm">
 <div class="card-header bg-primary text-white d-flex
 justify-content-between align-items-center">
 <h5 class="mb-0"> {{ $ticket->numero_reporte }}</h5>
 <span class="badge bg-light text-dark">
 {{ ucfirst(str_replace('_',' ',$ticket->status)) }}
 </span>
 </div>
 <div class="card-body">
 <div class="row g-3">
 <div class="col-md-6">
 <p class="mb-1 text-muted small">Cliente</p>
 <strong>{{ $ticket->cliente_nombre }}</strong>
 </div>
 <div class="col-md-6">
 <p class="mb-1 text-muted small">Email</p>
 <strong>{{ $ticket->cliente_email ?? '-' }}</strong>
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
 <p class="mb-1 text-muted small">Técnico Asignado</p>
 <strong>{{ $ticket->tecnico_asignado ?? '-' }}</strong>
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
 @if($ticket->comentarios_tecnico)
 <div class="col-12">
 <p class="mb-1 text-muted small">Comentarios del Técnico</p>
 <p>{{ $ticket->comentarios_tecnico }}</p>
 </div>
 @endif
 <div class="col-md-4">
 <p class="mb-1 text-muted small">Fecha Reporte</p>
 <strong>{{ $ticket->fecha_reporte?->format('d/m/Y H:i')
}}</strong>
 </div>
 <div class="col-md-4">
 <p class="mb-1 text-muted small">Fecha Promesa</p>
 <strong>{{ $ticket->fecha_promesa?->format('d/m/Y H:i') ?? '-'
}}</strong>
 </div>
 <div class="col-md-4">
 <p class="mb-1 text-muted small">Fecha Resolución</p>
 <strong>{{ $ticket->fecha_resolucion?->format('d/m/Y H:i') ?? '-'
}}</strong>
 </div>
 </div>
 @if($ticket->attachments && $ticket->attachments->count() > 0)
 <div class="col-12 mt-4">
 <h5 class="mb-3">Adjuntos del ticket</h5>
 <div class="row">
 @foreach($ticket->attachments as $attachment)
 <div class="col-md-3 mb-3">
 @if(str_starts_with($attachment->mime_type, 'image/'))
 <a href="{{ Storage::url($attachment->file_path) }}" target="_blank">
 <img src="{{ Storage::url($attachment->file_path) }}"
 class="img-fluid rounded shadow-sm" style="max-height: 180px; object-fit: cover;">
 </a>
 @else
 <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
 class="btn btn-outline-primary d-block text-truncate">
 {{ $attachment->original_name }}
 </a>
 @endif
 <small class="text-muted d-block mt-1">{{ $attachment->original_name }}</small>
 </div>
 @endforeach
 </div>
 </div>
 @endif
 @if($ticket->attachments->count() == 0)
 <div class="col-12 mt-4">
 <div class="alert alert-info">
 <i class="fas fa-info-circle"></i> Este ticket no tiene archivos adjuntos.
 </div>
 </div>
 @endif
 @if($ticket->ai_analysis)
 <div class="col-12 mt-4">
 <div class="card border-info">
 <div class="card-header bg-info text-white">
 <h5 class="mb-0">
 <i class="fas fa-robot mr-2"></i>Análisis de Inteligencia Artificial
 @if(isset($ticket->ai_analysis['demo_mode']) && $ticket->ai_analysis['demo_mode'])
 @if(isset($ticket->ai_analysis['metadata_mode']) && $ticket->ai_analysis['metadata_mode'])
 <span class="badge bg-secondary ms-2">ANÁLISIS POR METADATOS</span>
 @else
 <span class="badge bg-info ms-2">ANÁLISIS VISUAL</span>
 @endif
 @endif
 </h5>
 </div>
 <div class="card-body">
 @if(is_array($ticket->ai_analysis))
 @if(isset($ticket->ai_analysis['descripcion_tecnica']))
 <div class="mb-3">
 <h6 class="text-info">Descripción Técnica</h6>
 <p class="mb-2">{{ $ticket->ai_analysis['descripcion_tecnica'] }}</p>
 </div>
 @endif
 
 @if(isset($ticket->ai_analysis['posibles_causas']) && !empty($ticket->ai_analysis['posibles_causas']))
 <div class="mb-3">
 <h6 class="text-warning">Posibles Causas</h6>
 <ul class="list-unstyled mb-2">
 @foreach($ticket->ai_analysis['posibles_causas'] as $causa)
 <li><i class="fas fa-exclamation-triangle text-warning mr-2"></i>{{ $causa }}</li>
 @endforeach
 </ul>
 </div>
 @endif
 
 <div class="row">
 @if(isset($ticket->ai_analysis['categoria_sugerida']))
 <div class="col-md-6 mb-3">
 <h6 class="text-primary">Categoría Sugerida</h6>
 <span class="badge bg-primary">{{ ucfirst($ticket->ai_analysis['categoria_sugerida']) }}</span>
 </div>
 @endif
 
 @if(isset($ticket->ai_analysis['nivel_urgencia_sugerido']))
 <div class="col-md-6 mb-3">
 <h6 class="text-danger">Nivel de Urgencia Sugerido</h6>
 <span class="badge 
 @if($ticket->ai_analysis['nivel_urgencia_sugerido'] == 'critica') bg-danger
 @elseif($ticket->ai_analysis['nivel_urgencia_sugerido'] == 'alta') bg-warning
 @elseif($ticket->ai_analysis['nivel_urgencia_sugerido'] == 'media') bg-info
 @else bg-secondary @endif">
 {{ ucfirst($ticket->ai_analysis['nivel_urgencia_sugerido']) }}
 </span>
 </div>
 @endif
 </div>
 
 @if(isset($ticket->ai_analysis['resumen_ejecutivo']))
 <div class="mt-3">
 <h6 class="text-success">Resumen Ejecutivo</h6>
 <div class="alert alert-info">
 <small>{{ $ticket->ai_analysis['resumen_ejecutivo'] }}</small>
 </div>
 </div>
 @endif
 @else
 <p class="text-muted">{{ $ticket->ai_analysis }}</p>
 @endif
 </div>
 </div>
 </div>
 @endif
 @if(!$ticket->ai_analysis && $ticket->attachments->count() > 0)
 <div class="col-12 mt-4">
 <div class="alert alert-warning">
 <i class="fas fa-exclamation-triangle"></i> Este ticket tiene archivos adjuntos pero no se ha generado análisis de IA. 
 @if($ticket->attachments()->where('type', 'image')->count() > 0)
 Los archivos de imagen pueden ser analizados editando el ticket.
 @else
 No hay imágenes para analizar (solo documentos).
 @endif
 </div>
 </div>
 @endif
 </div>
 <div class="card-footer d-flex gap-2">
 <a href="{{ route('admin.tickets.edit',$ticket) }}"
 class="btn btn-warning">Editar</a>
 <a href="{{ route('admin.tickets.index') }}"
 class="btn btn-secondary">Volver</a>
 <form action="{{ route('admin.tickets.destroy',$ticket) }}" method="POST"
 class="ms-auto" onsubmit="return confirm('¿Eliminar?')">
 @csrf @method('DELETE')
 <button class="btn btn-danger">Eliminar</button>
 </form>
 </div>
</div>
</div>
</div>
@endsection
