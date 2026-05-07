@extends('layouts.app')

@section('titulo', 'Inicio — Últimas Noticias')

@section('contenido')
    <h1>📰 Últimas Noticias Friki</h1>
    <p>Bienvenido al portal definitivo de cómics, anime y videojuegos.</p>
    
    {{-- @isset verifica que $noticias esté definida antes de usarla --}}
    @isset($noticias)
        @forelse($noticias as $noticia)
            <div class="card">
                {{-- $loop->iteration: número de vuelta actual (1, 2, 3...) --}}
                <small style="color:#a78bfa">#{{ $loop->iteration }}</small>
                
                {{-- $loop->first es true solo en la primera iteración --}}
                @if($loop->first)
                    <span class="badge badge-rojo">⭐ DESTACADA</span>
                @endif
                
                <h3>{{ $noticia['titulo'] }}</h3>
                
                {{-- @switch/@case: selección múltiple según el tipo --}}
                @switch($noticia['tipo'])
                    @case('anime')
                        <span class="badge badge-azul">🌸 Anime</span>
                        @break
                    @case('comics')
                        <span class="badge badge-rojo">📚 Cómics</span>
                        @break
                    @case('videojuegos')
                        <span class="badge badge-verde">🎮 Videojuegos</span>
                        @break
                    @default
                        <span class="badge">📰 General</span>
                @endswitch
                
                @if($noticia['caliente'])
                    <span class="badge badge-rojo">🔥 TRENDING</span>
                @endif
                
                {{-- $loop->last es true solo en la última iteración --}}
                @if($loop->last)
                    <p style="color:#a78bfa;font-size:.8rem">— Fin de las noticias —</p>
                @endif
            </div>
        @empty
            {{-- Este bloque se ejecuta si $noticias está vacío --}}
            <div class="card">
                <p>📭 No hay noticias. ¡Vuelve pronto!</p>
            </div>
        @endforelse
    @endisset
@endsection
