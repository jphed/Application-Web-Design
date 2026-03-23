<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Sistema de Tickets - @yield('title', 'Inicio')</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <style>
 body { background-color: #f0f2f5; }
 .navbar { background-color: #1a1a2e; }
 .badge-pendiente { background-color: #6c757d; }
 .badge-en_curso { background-color: #0d6efd; }
 .badge-en_espera { background-color: #ffc107; color:#000; }
 .badge-cancelada { background-color: #dc3545; }
 .badge-finalizada { background-color: #198754; }
 </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark px-4 py-3 mb-4">
 <div class="container-fluid">
 <a class="navbar-brand fw-bold fs-5" href="{{ route('dashboard') }}">Sistema de Tickets</a>
 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
 <span class="navbar-toggler-icon"></span>
 </button>
 <div class="collapse navbar-collapse" id="navbarNav">
 <div class="navbar-nav ms-auto">
 @auth
 @if(auth()->user()->rol === 'admin')
 <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
 <a class="nav-link" href="{{ route('admin.tickets.index') }}">Tickets</a>
 <a class="nav-link" href="{{ route('admin.usuarios.index') }}">Usuarios</a>
 @endif
 @if(auth()->user()->rol === 'gerente')
 <a class="nav-link" href="{{ route('gerente.dashboard') }}">Dashboard</a>
 <a class="nav-link" href="{{ route('gerente.reportes') }}">Reportes</a>
 <a class="nav-link" href="{{ route('gerente.tickets.index') }}">Tickets</a>
 @endif
 @if(auth()->user()->rol === 'usuario')
 <a class="nav-link" href="{{ route('usuario.dashboard') }}">Mi Panel</a>
 <a class="nav-link" href="{{ route('usuario.tickets.index') }}">Mis Tickets</a>
 <a class="nav-link" href="{{ route('usuario.tickets.create') }}">Nuevo Ticket</a>
 @endif
 <span class="nav-link text-light">
 {{ auth()->user()->name }}
 <span class="badge bg-secondary ms-1">{{ auth()->user()->rol }}</span>
 </span>
 <form method="POST" action="{{ route('logout') }}" class="d-inline">
 @csrf
 <button type="submit" class="btn btn-sm btn-outline-light ms-2">Salir</button>
 </form>
 @endauth
 </div>
 </div>
 </div>
</nav>
@if(session('success'))
<div class="alert alert-success alert-dismissible m-3">
 {{ session('success') }}
 <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible m-3">
 {{ session('error') }}
 <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
<div class="container">
 @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
