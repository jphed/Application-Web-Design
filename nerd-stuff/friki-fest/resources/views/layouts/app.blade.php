<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- @yield con valor por defecto: si la vista no define "titulo", usa "Inicio" --}}
    <title>FrikiFest | @yield('titulo', 'Inicio')</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0;
        background: #0d0d1a; color: #e0e0ff; }
        nav { background: #1a1a2e; padding: 1rem 2rem; display: flex;
        gap: 1.5rem; align-items: center;
        border-bottom: 2px solid #7c3aed; }
        nav a { color: #a78bfa; text-decoration: none;
        padding: .4rem .8rem; border-radius: 6px; transition: all .2s; }
        nav a:hover, nav a.activo { background: #7c3aed; color: white; }
        nav a.activo { font-weight: bold; }
        nav .logo { font-size: 1.4rem; font-weight: bold;
        color: #c4b5fd; margin-right: auto; }
        main { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
        footer { background: #1a1a2e; text-align: center; padding: 1rem;
        margin-top: 3rem; border-top: 2px solid #7c3aed; color: #a78bfa; }
        .card { background: #1a1a2e; border: 1px solid #7c3aed;
        border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
        .badge { display: inline-block; padding: .2rem .6rem;
        border-radius: 4px; font-size: .8rem; }
        .badge-rojo { background: #dc2626; color: white; }
        .badge-azul { background: #2563eb; color: white; }
        .badge-verde { background: #16a34a; color: white; }
        .btn { padding: .5rem 1.2rem; border: none; border-radius: 6px;
        cursor: pointer; background: #7c3aed; color: white; }
        .input { width: 100%; padding: .5rem; margin-bottom: .8rem;
        border-radius: 6px; border: 1px solid #7c3aed;
        background: #0d0d1a; color: #e0e0ff; box-sizing: border-box; }
        .alerta-error { color: #f87171; font-size: .85rem; margin-bottom: .5rem; }
        .alerta-exito { background: #16a34a; padding: .8rem;
        border-radius: 6px; margin-bottom: 1rem; }
        .estrellas { color: gold; font-size: 1.2rem; }
    </style>
    {{-- @yield extra: para que las vistas puedan añadir sus propios estilos --}}
    @yield('estilos')
</head>
<body>
    {{-- @include inserta el partial de la barra de navegación --}}
    @include('partials.navbar')
    <main>
        {{-- @section/@show: define una alerta con contenido por defecto.
        Las vistas hijas pueden usar @parent para conservar esto --}}
        @section('alertas')
            {{-- Sin contenido por defecto aquí --}}
        @show
        {{-- @yield principal: el contenido único de cada vista --}}
        @yield('contenido')
    </main>
    {{-- @include inserta el partial del pie de página --}}
    @include('partials.footer')
    {{-- @yield para scripts adicionales por vista --}}
    @yield('scripts')
</body>
</html>
