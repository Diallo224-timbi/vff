<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Structure;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StructuresExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AnnuaireController extends Controller
{
    public function index()
    {
        $structures = Structure::all();
        return view('annuaire.index', compact('structures'));
    }

    public function exportCsv()
    {
        return Excel::download(new StructuresExport, 'annuaire_structures.csv');
    }

    public function exportPdf()
    {
        $structures = Structure::all();
        $pdf = Pdf::loadView('annuaire.pdf', compact('structures'));
        return $pdf->download('annuaire_structures.pdf');
    }
    public function members_count()
    {
        return $this->members()->count();
    }
}
