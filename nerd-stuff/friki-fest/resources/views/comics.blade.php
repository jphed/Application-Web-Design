@extends('layouts.app')

@section('titulo', 'Cómics')

{{-- @section + @parent: añade contenido sin borrar el del layout --}}
@section('alertas')
    @parent
    <div class="card" style="border-color:#dc2626; margin-bottom:1rem">
        ⚠️ Zona de spoilers. ¡Leer bajo tu propio riesgo!
    </div>
@endsection

@section('contenido')
    <h1>📚 Universos de Cómics</h1>
    
    @foreach($comics as $comic)
        {{-- @continue(condición): salta los cómics inactivos --}}
        @continue(!$comic['activo'])
        
        <div class="card">
            <h3>{{ $comic['nombre'] }}</h3>
            
            @if($comic['universo'] === 'Marvel')
                <span class="badge badge-rojo">🕷️ Marvel</span>
            @elseif($comic['universo'] === 'DC')
                <span class="badge badge-azul">🦇 DC Comics</span>
            @else
                <span class="badge">📖 Independiente</span>
            @endif
            
            {{-- {!! !!}: muestra HTML sin escapar (solo datos de confianza) --}}
            <p>{!! "Creado en <strong>" . $comic['anio'] . "</strong>" !!}</p>
        </div>
    @endforeach
@endsection
