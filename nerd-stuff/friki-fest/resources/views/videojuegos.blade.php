@extends('layouts.app')

@section('titulo', 'Videojuegos')

{{-- Añadimos estilos solo para esta vista --}}
@section('estilos')
    <style> .estrellas { color: gold; font-size: 1.2rem; } </style>
@endsection

@section('contenido')
    <h1>🎮 Mis Videojuegos Favoritos</h1>
    
    @foreach($juegos as $juego)
        <div class="card">
            {{-- $loop->index empieza en 0, por eso sumamos 1 --}}
            <small style="color:#a78bfa">Juego #{{ $loop->index + 1 }}</small>
            <h3>{{ $juego['nombre'] }}</h3>
            <p>Género: {{ $juego['genero'] }}</p>
            
            <div class="estrellas">
                {{-- @for: ciclo clásico para pintar estrellas --}}
                @for($i = 1; $i <= 5; $i++)
                    {{ $i <= intval($juego['calificacion'] / 2) ? '⭐' : '☆' }}
                @endfor
                <small>({{ $juego['calificacion'] }}/10)</small>
            </div>
            
            {{-- @break(condición): detiene el loop si la nota es 10 --}}
            {{-- y no es el primer juego (para evitar cortar al inicio) --}}
            @break($juego['calificacion'] === 10 && !$loop->first)
        </div>
    @endforeach
@endsection
