<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Structures;
use App\Models\Organisme;
use App\Models\User;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class AnnuaireController extends Controller
{
  public function index(Request $request)
{
    // On garde un Query Builder
    $query = Structures::with('organisme');
    $organismes = Organisme::all();
    // Recherche côté serveur
    if ($request->filled('search')) {
        $search = $request->input('search');

        $query->where(function ($q) use ($search) {
            $q->where('organisme', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('siege_ville', 'like', "%{$search}%")
              ->orWhere('siege_adresse', 'like', "%{$search}%")
              ->orWhere('categories', 'like', "%{$search}%")
              ->orWhere('public_cible', 'like', "%{$search}%")
              ->orWhere('zone', 'like', "%{$search}%")
              ->orWhere('type_structure', 'like', "%{$search}%")
              ->orWhere('details', 'like', "%{$search}%")
              ->orWhere('hebergement', 'like', "%{$search}%")
              ->orWhere('ville', 'like', "%{$search}%")
              ->orWhere('code_postal', 'like', "%{$search}%")
              ->orWhere('adresse', 'like', "%{$search}%")
              ->orWhere('site', 'like', "%{$search}%");
        });
    }
    // Pagination (toujours après la construction de la requête)
    $structures = $query->orderBy('id')->paginate(200)->withQueryString();
    return view('annuaire.index', compact('structures', 'organismes'));
}
    // Affichage de la liste groupée par siège
    public function listeGroupee()
    {
        $structures = Structures::with('organisme')->get();
        $organismes = Organisme::orderBy('nom_organisme')->get();
        
        // Regroupement par siège (ville du siège)
        $groupes = $structures->groupBy(function($item) {
            return $item->organisme->nom_organisme ?? 'Non spécifié';
        })->sortKeys();
        
        // Créer une adresse complète pour chaque structure
        foreach ($structures as $structure) {
            $adresse_complete = trim($structure->adresse ?? '');
            if ($structure->code_postal || $structure->ville) {
                $adresse_complete .= $adresse_complete ? ', ' : '';
                $adresse_complete .= trim($structure->code_postal . ' ' . $structure->ville);
            }
            $structure->adresse_complete = $adresse_complete ?: null;
        }   
        $totalStructures = $structures->count();   
        return view('annuaire.list', compact('groupes', 'totalStructures', 'structures', 'organismes'));
    }
    // Affichage des users d'une structure spécifique
    public function showByStructure()
    {
        $membres = User::all();
        // Récupérer les structures qui ont des users
        $structures = Structures::with('users')->has('users')->get();
        
        return view('annuaire.membre', compact('membres', 'structures'));
    }
    public function exportPdf()
    {
        // Récupération de toutes les structures avec leur organisme, triées
        $structures = Structures::with('organisme')->orderBy('organisme')->get();

        // Génération du PDF avec orientation paysage
        $pdf = Pdf::loadView('annuaire.pdf', compact('structures'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('annuaire_structures.pdf');
    }
}