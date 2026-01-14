<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminModerateur
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté et son rôle
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'moderateur'])) {
            abort(403, 'Accès refusé');
        }

        return $next($request);
    }
}
