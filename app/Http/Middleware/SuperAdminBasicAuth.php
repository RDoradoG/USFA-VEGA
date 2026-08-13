<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $username = $request->getUser();
        $password = $request->getPassword();

        // Validar usuario y contraseña
        if (!$username || !$password || !Auth::attempt([
            'email' => $username,
            'password' => $password,
        ])) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.',
            ], 401, [
                'WWW-Authenticate' => 'Basic',
            ]);
        }

        $user = Auth::user();

        // Verificar rol
        if ($user->rol !== 'SUPERADMIN') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para acceder a este recurso.',
            ], 403);
        }

        return $next($request);
    }
}