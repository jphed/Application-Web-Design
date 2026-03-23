@extends('layouts.tickets')
@section('title', 'Gestión de Usuarios')
@section('content')
<div class="container py-4">
 <h2 class="mb-4">Usuarios del Sistema</h2>
 <div class="table-responsive">
 <table class="table table-bordered table-hover">
 <thead class="table-dark">
 <tr>
 <th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th>
 </tr>
 </thead>
 <tbody>
 @foreach($usuarios as $usuario)
 <tr>
 <td>{{ $usuario->name }}</td>
 <td>{{ $usuario->email }}</td>
 <td>
 <span class="badge bg-secondary">{{ ucfirst($usuario->rol) }}</span>
 </td>
 <td>
 <form method="POST" action="{{ route('admin.usuarios.cambiar-rol', $usuario) }}" class="d-inline">
 @csrf
 <select name="rol" class="form-select form-select-sm d-inline-block w-auto me-2">
 @foreach(['admin','gerente','usuario'] as $rol)
 <option value="{{ $rol }}" {{ $usuario->rol === $rol ? 'selected' : '' }}>
 {{ ucfirst($rol) }}
 </option>
 @endforeach
 </select>
 <button type="submit" class="btn btn-sm btn-primary">Cambiar</button>
 </form>
 <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}" class="d-inline ms-2" onsubmit="return confirm('¿Eliminar usuario?')">
 @csrf @method('DELETE')
 <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
 </form>
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
</div>
@endsection
