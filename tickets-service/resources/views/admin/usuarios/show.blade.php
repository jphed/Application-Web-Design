@extends('layouts.tickets')
@section('title', 'Detalle de Usuario')
@section('content')
<div class="container py-4">
 <h2 class="mb-4">Detalle de Usuario</h2>
 <div class="card">
 <div class="card-body">
 <p><strong>Nombre:</strong> {{ $user->name }}</p>
 <p><strong>Email:</strong> {{ $user->email }}</p>
 <p><strong>Rol:</strong> <span class="badge bg-secondary">{{ ucfirst($user->rol) }}</span></p>
 </div>
 </div>
 <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
