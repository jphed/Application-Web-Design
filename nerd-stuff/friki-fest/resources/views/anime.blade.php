@extends('layouts.app')

@section('titulo', 'Anime')

@section('contenido')
    <h1>🌸 Series de Anime</h1>
    
    {{-- Como $series está vacío, se ejecuta el bloque @empty --}}
    @forelse($series as $serie)
        <div class="card">
            <h3>{{ $serie['nombre'] }}</h3>
        </div>
    @empty
        <div class="card" style="text-align:center; padding:2rem">
            <h2>😢 No hay series cargadas aún</h2>
            <p>El editor está de vacaciones en Akihabara...</p>
            
            {{-- @unless es lo opuesto de @if. Aquí: "si NO hay series" --}}
            @unless(count($series) > 0)
                <p style="color:#a78bfa">¡Vuelve pronto para ver el contenido!</p>
            @endunless
        </div>
    @endforelse
@endsection
