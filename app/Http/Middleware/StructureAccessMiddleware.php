<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StructureAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $structure = $request->route('structure');

        // Admin : accès à toutes les structures
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Responsable de structure :
        // uniquement sa propre structure
        if ($user->role === 'moderateur_classique') {

            if ((int) $user->id_structure !== (int) $structure->id) {
                abort(403, 'Vous ne pouvez gérer que votre propre structure.');
            }

            return $next($request);
        }

        // Responsable d'organisme :
        // toutes les structures appartenant à son organisme
        if ($user->role === 'moderateur') {

            if (
                !$user->structure ||
                !$structure->organisme ||
                $user->structure->id_organisme !== $structure->id_organisme
            ) {
                abort(403, 'Vous ne pouvez gérer que les structures de votre organisme.');
            }

            return $next($request);
        }

        abort(403, 'Vous n\'êtes pas autorisé à gérer cette structure.');
    }
}