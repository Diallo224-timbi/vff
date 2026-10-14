<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganismeAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier que l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // L'administrateur peut accéder à tous les organismes
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Seul le responsable d'organisme (moderateur) est autorisé
        if ($user->role !== 'moderateur') {
            abort(
                403,
                'Accès réservé à l’administrateur et au responsable d’organisme.'
            );
        }

        // Pour les routes des structures, vérifier l'organisme
        // dans le contrôleur, notamment lors de la création.
        if ($request->is('structures') || $request->is('structures/*')) {
            return $next($request);
        }

        // Pour les routes avec un organisme existant
        $organisme = $request->route('organisme')
            ?? $request->route('id');

        if (!$organisme) {
            abort(404, 'Organisme introuvable.');
        }

        // Résoudre l'identifiant de l'organisme
        $organismeId = is_object($organisme)
            ? $organisme->id
            : (int) $organisme;

        if (
            !$user->structure ||
            (int) $user->structure->id_organisme !== $organismeId
        ) {
            abort(
                403,
                'Vous ne pouvez consulter que votre propre organisme.'
            );
        }

        return $next($request);
    }
}
