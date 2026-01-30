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
        $structures = $query->orderBy('organisme')->paginate(10)->withQueryString();

        return view('annuaire.index', compact('structures'));
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
