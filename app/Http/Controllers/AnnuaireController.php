<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\structures;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StructuresExport;
use Barryvdh\DomPDF\Facade\Pdf;
class AnnuaireController extends Controller
{
    public function index(Request $request)
    {
        $query = structures::query();

        // Recherche côté serveur
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('organisme', 'like', "%{$search}%")
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
        }

        // Pagination
        $structures = $query->orderBy('organisme')->paginate(50)->withQueryString();

        return view('annuaire.index', compact('structures'));
    }
// Affichage de la liste groupée par siège
    public function listeGroupee()
    {
        $structures = Structures::orderBy('siege_ville')->get();
        
        // Regroupement par siège (ville du siège)
        $groupes = $structures->groupBy(function($item) {
            return $item->organisme ?? 'Non spécifié';
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
        
        return view('annuaire.list', compact('groupes', 'totalStructures'));
    }

    public function exportCsv()
    {
        return Excel::download(new StructuresExport, 'annuaire_structures.csv');
    }
   public function exportPdf()
    {
        // Récupération de toutes les structures, triées
        $structures = structures::orderBy('organisme')->get();

        // Génération du PDF avec orientation paysage
        $pdf = Pdf::loadView('annuaire.pdf', compact('structures'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('annuaire_structures.pdf');
    }
}
