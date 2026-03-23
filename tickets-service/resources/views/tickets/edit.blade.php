@extends('layouts.tickets')
@section('title', 'Editar ' . $ticket->numero_reporte)
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card shadow-sm">
 <div class="card-header bg-warning">
 <h5 class="mb-0"> Editar: {{ $ticket->numero_reporte }}</h5>
 </div>
 <div class="card-body">
 <form action="{{ route('tickets.update',$ticket) }}" method="POST">
 @csrf
 @method('PUT')
 <div class="row g-3">
 <div class="col-md-6">
 <label class="form-label fw-semibold">N° Reporte</label>
 <input type="text" class="form-control"
 value="{{ $ticket->numero_reporte }}" disabled>
 </div>
 <div class="col-md-6">
 <label class="form-label fw-semibold">Nombre del
Cliente</label>
 <input type="text" name="cliente_nombre" class="form-control"
 value="{{ $ticket->cliente_nombre }}">
 </div>
 <div class="col-md-6">
 <label class="form-label fw-semibold">Email</label>
 <input type="email" name="cliente_email" class="form-control"
 value="{{ $ticket->cliente_email }}">
 </div>
 <div class="col-md-6">
 <label class="form-label fw-semibold">Departamento</label>
 <input type="text" name="departamento" class="form-control"
 value="{{ $ticket->departamento }}">
 </div>
 <div class="col-md-6">
 <label class="form-label fw-semibold">Categoría</label>
 <select name="categoria" class="form-select">
 @foreach(['software','hardware','comunicaciones',
 'plataformas','email','otro'] as $cat)
 <option value="{{ $cat }}"
 {{ $ticket->categoria===$cat?'selected':'' }}>
{{ ucfirst($cat) }}
 </option>
 @endforeach
 </select>
 </div>
 <div class="col-md-6">
 <label class="form-label fw-semibold">Urgencia</label>
 <select name="nivel_urgencia" class="form-select">
 @foreach(['baja','media','alta','critica'] as $nivel)
 <option value="{{ $nivel }}"
 {{ $ticket->nivel_urgencia===$nivel?'selected':''
}}>
 {{ ucfirst($nivel) }}
 </option>
 @endforeach
 </select>
 </div>
 <div class="col-12">
 <label class="form-label fw-semibold">Descripción
Corta</label>
 <input type="text" name="descripcion_corta" class="form-control"
 value="{{ $ticket->descripcion_corta }}">
 </div>
 <div class="col-12">
 <label class="form-label fw-semibold">Descripción
Detallada</label>
 <textarea name="descripcion_detallada" class="form-control"
rows="3">{{ $ticket->descripcion_detallada }}</textarea>
 </div>
 <div class="col-md-6">
 <label class="form-label fw-semibold">Técnico Asignado</label>
 <input type="text" name="tecnico_asignado" class="form-control"
 value="{{ $ticket->tecnico_asignado }}">
 </div>
 <div class="col-md-6">
 <label class="form-label fw-semibold">Status</label>
 <select name="status" class="form-select">
 @foreach(['pendiente','en_curso','en_espera',
 'cancelada','finalizada'] as $st)
 <option value="{{ $st }}"
 {{ $ticket->status===$st?'selected':'' }}>
{{ ucfirst(str_replace('_',' ',$st)) }}
 </option>
 @endforeach
 </select>
 </div>
 <div class="col-md-6">
 <label class="form-label fw-semibold">Fecha Promesa</label>
 <input type="datetime-local" name="fecha_promesa" class="form-control"
 value="{{ $ticket->fecha_promesa?->format('Y-m-d\TH:i')
}}">
 </div>
 <div class="col-md-6">
 <label class="form-label fw-semibold">Fecha Resolución</label>
 <input type="datetime-local" name="fecha_resolucion"
class="form-control"
 value="{{ $ticket->fecha_resolucion?->format('Y-m-d\TH:i') }}">
 </div>
 <div class="col-12">
 <label class="form-label fw-semibold">Comentarios
Técnico</label>
 <textarea name="comentarios_tecnico" class="form-control"
rows="3">{{ $ticket->comentarios_tecnico }}</textarea>
 </div>
 </div>
 <div class="mt-4 d-flex gap-2">
 <button type="submit" class="btn btn-warning">Actualizar
Ticket</button>
 <a href="{{ route('tickets.show',$ticket) }}"
 class="btn btn-secondary">Cancelar</a>
 </div>
 </form>
 </div>
</div>
</div>
</div>
@endsection
