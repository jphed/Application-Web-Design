@extends('layouts.app')

@section('titulo', 'Contacto')

@section('contenido')
    <h1>📧 ¡Mándanos tu rollo geek!</h1>
    
    {{-- Muestra el mensaje de éxito si existe en la sesión --}}
    @if(session('success'))
        <div class="alerta-exito">{{ session('success') }}</div>
    @endif
    
    <div class="card">
        <form method="POST" action="{{ route('contacto.enviar') }}">
            {{-- @csrf: directiva obligatoria en formularios POST --}}
            @csrf
            
            <label>Tu nombre friki:</label><br>
            {{-- old('nombre'): repopula el campo si hay error de validación --}}
            <input type="text" name="nombre" class="input"
                   value="{{ old('nombre') }}"
                   placeholder="Ej: Luke Skywalker">
            {{-- @error: muestra el mensaje si el campo "nombre" falló --}}
            @error('nombre')
                <div class="alerta-error">{{ $message }}</div>
            @enderror
            
            <label>Email galáctico:</label><br>
            <input type="email" name="email" class="input"
                   value="{{ old('email') }}"
                   placeholder="friki@galaxia.com">
            @error('email')
                <div class="alerta-error">{{ $message }}</div>
            @enderror
            
            <label>Tu mensaje:</label><br>
            <textarea name="mensaje" class="input" rows="4"
                      placeholder="¿Qué opinas del final de Evangelion?">{{ old('mensaje') }}</textarea>
            @error('mensaje')
                <div class="alerta-error">{{ $message }}</div>
            @enderror
            
            <button type="submit" class="btn">🚀 Enviar mensaje</button>
        </form>
    </div>
@endsection
