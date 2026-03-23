@extends('layouts.tickets')
@section('title', 'Nuevo Ticket')
@section('content')
<div class="container py-4">
 <h2 class="mb-4">Crear Nuevo Ticket</h2>
 <div class="row justify-content-center">
 <div class="col-lg-8">
 <div class="card shadow-sm">
 <div class="card-header bg-success text-white">
 <h5 class="mb-0">Nuevo Ticket</h5>
 </div>
 <div class="card-body">
 <form action="{{ route('usuario.tickets.store') }}" method="POST">
 @csrf
 <div class="row g-3">
 <div class="col-md-6">
 <label class="form-label fw-semibold">Departamento *</label>
 <input type="text" name="departamento" class="form-control" required>
 </div>
 <div class="col-md-6">
 <label class="form-label fw-semibold">Categoría *</label>
 <select name="categoria" class="form-select" required>
 <option value="">-- Selecciona --</option>
 @foreach(['software','hardware','comunicaciones','plataformas','email','otro'] as $cat)
 <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
 @endforeach
 </select>
 </div>
 <div class="col-md-6">
 <label class="form-label fw-semibold">Nivel de Urgencia *</label>
 <select name="nivel_urgencia" class="form-select" required>
 @foreach(['baja','media','alta','critica'] as $nivel)
 <option value="{{ $nivel }}">{{ ucfirst($nivel) }}</option>
 @endforeach
 </select>
 </div>
 <div class="col-12">
 <label class="form-label fw-semibold">Descripción Corta *</label>
 <input type="text" name="descripcion_corta" class="form-control" maxlength="255" required>
 </div>
 <div class="col-12">
 <label class="form-label fw-semibold">Descripción Detallada</label>
 <textarea name="descripcion_detallada" class="form-control" rows="3"></textarea>
 </div>
 </div>
 <div class="mt-4 d-flex gap-2">
 <button type="submit" class="btn btn-success">Guardar Ticket</button>
 <a href="{{ route('usuario.tickets.index') }}" class="btn btn-secondary">Cancelar</a>
 </div>
 </form>
 </div>
 </div>
 </div>
 </div>
</div>
@endsection
