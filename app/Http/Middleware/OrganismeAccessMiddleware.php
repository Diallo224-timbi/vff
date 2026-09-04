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
        // accès à tous les organismes
        if ($user->role === 'admin') {
            return $next($request);
        }
        // Récupérer l'organisme pour les autres rôles
        $organisme = $request->route('organisme');
        // Vérifier que l'organisme existe
        if (!$organisme) {
            abort(404, 'Organisme introuvable.');
        }
        // accès uniquement à son propre organisme
        if ($user->role === 'moderateur') {

            if (
                !$user->structure ||
                !$user->structure->id_organisme ||
                (int) $user->structure->id_organisme !== (int) $organisme->id
            ) {
                abort(
                    403,
                    'Vous ne pouvez consulter que votre propre organisme.'
                );
            }
            return $next($request);
        }
        // Tous les autres rôles sont refusés
        abort(
            403,
            'Vous n\'êtes pas autorisé à consulter cet organisme.'
        );
    }
}
