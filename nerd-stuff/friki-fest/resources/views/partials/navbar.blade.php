<nav>
    <span class="logo">🎮 FrikiFest</span>
    {{-- Asignamos la clase "activo" si la ruta actual coincide --}}
    <a href="{{ route('inicio') }}"
       class="{{ request()->routeIs('inicio') ? 'activo' : '' }}">
        Inicio
    </a>
    <a href="{{ route('comics') }}"
       class="{{ request()->routeIs('comics') ? 'activo' : '' }}">
        Cómics
    </a>
    <a href="{{ route('anime') }}"
       class="{{ request()->routeIs('anime') ? 'activo' : '' }}">
        Anime
    </a>
    <a href="{{ route('videojuegos') }}"
       class="{{ request()->routeIs('videojuegos') ? 'activo' : '' }}">
        Videojuegos
    </a>
    <a href="{{ route('contacto') }}"
       class="{{ request()->routeIs('contacto') ? 'activo' : '' }}">
        Contacto
    </a>
</nav>
