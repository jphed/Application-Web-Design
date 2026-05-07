@extends('layouts.tickets')
@section('title', 'Mi Panel')
@section('content')
<div class="container py-4">
 <h2 class="mb-3">Bienvenido, {{ auth()->user()->name }}</h2>
 <p class="text-muted">Aquí puedes ver y gestionar tus tickets de soporte.</p>
 
 <!-- Formulario rápido para crear ticket -->
 <div class="card mb-4">
 <div class="card-header bg-primary text-white">
 <h5 class="mb-0">Crear Nuevo Ticket Rápido</h5>
 </div>
 <div class="card-body">
 <form action="{{ route('usuario.tickets.store') }}" method="POST" enctype="multipart/form-data">
 @csrf
 <div class="row g-3">
 <div class="col-md-6">
 <label class="form-label">Departamento *</label>
 <input type="text" name="departamento" required
 class="form-control" placeholder="Ej: TI, Contabilidad, Ventas">
 </div>
 <div class="col-md-6">
 <label class="form-label">Categoría *</label>
 <select name="categoria" required class="form-select">
 <option value="">-- Selecciona --</option>
 <option value="software">Software</option>
 <option value="hardware">Hardware</option>
 <option value="comunicaciones">Comunicaciones</option>
 <option value="plataformas">Plataformas</option>
 <option value="email">Email</option>
 <option value="otro">Otro</option>
 </select>
 </div>
 <div class="col-md-6">
 <label class="form-label">Nivel de Urgencia *</label>
 <select name="nivel_urgencia" required class="form-select">
 <option value="baja">Baja</option>
 <option value="media">Media</option>
 <option value="alta">Alta</option>
 <option value="critica">Crítica</option>
 </select>
 </div>
 <div class="col-md-6">
 <label class="form-label">Descripción Corta *</label>
 <input type="text" name="descripcion_corta" required maxlength="255"
 class="form-control" placeholder="Resumen del problema">
 </div>
 <div class="col-12">
 <label class="form-label">Descripción Detallada</label>
 <textarea name="descripcion_detallada" rows="3" class="form-control"
 placeholder="Describe el problema con más detalle..."></textarea>
 </div>
 <div class="col-12">
 <label class="form-label">Adjuntar imágenes o documentos</label>
 <input type="file" name="attachments[]" multiple class="form-control"
 accept="image/*,.pdf,.doc,.docx,.txt,.xls,.xlsx">
 <small class="text-muted">Máximo 10 MB por archivo. La IA analizará las imágenes automáticamente.</small>
 </div>
 </div>
 <div class="mt-3 d-flex gap-2">
 <button type="submit" class="btn btn-primary">
 <i class="fas fa-plus"></i> Crear Ticket
 </button>
 <a href="{{ route('usuario.tickets.create') }}" class="btn btn-outline-primary">
 Formulario Completo
 </a>
 <a href="{{ route('usuario.tickets.index') }}" class="btn btn-secondary">
 Ver Mis Tickets
 </a>
 </div>
 </form>
 </div>
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
