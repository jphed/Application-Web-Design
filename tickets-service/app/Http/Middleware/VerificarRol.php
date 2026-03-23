<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    /**
     * $rolesPermitidos puede ser un string "admin"
     * o varios roles separados por coma: "admin,gerente"
     */
    public function handle(Request $request, Closure $next, string ...$rolesPermitidos): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión.');
        }

        $rolUsuario = auth()->user()->rol;

        if (!in_array($rolUsuario, $rolesPermitidos, true)) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
