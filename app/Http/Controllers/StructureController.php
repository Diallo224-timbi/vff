<?php

namespace App\Http\Controllers;

use App\Models\structures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class StructureController extends Controller
{
    public function index(Request $request)
    {
        $query = structures::query();

        // Recherche côté serveur
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('organisme', 'like', "%{$search}%")
                ->orWhere('siege_adresse', 'like', "%{$search}%")
                ->orWhere('siege_ville', 'like', "%{$search}%")
                ->orWhere('ville', 'like', "%{$search}%")
                ->orWhere('code_postal', 'like', "%{$search}%")
                ->orWhere('categories', 'like', "%{$search}%");
        }

        $structures = $query->orderBy('organisme')->paginate(20)->withQueryString();

        return view('structures.index', compact('structures'));
    }

    public function createPDF()
    {
        return view('auth.pdf');
    }

    public function generatePDF(Request $request)
    {
        $data = $request->all();

        $request->validate([
            'organisme' => 'required|string',
            'siege_adresse' => 'required|string',
            'siege_ville' => 'required|string',
            'code_postal' => 'required|string',
            'email' => 'nullable|email',
        ]);

        $pdf = Pdf::loadView('auth.pdf', compact('data'));
        return $pdf->download('structure.pdf');
    }

    public function create()
    {
        return view('structures.create', [
            'structure' => new structures(),
            'action' => route('structures.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organisme' => 'required|string|max:255',
            'description' => 'nullable|string',
            'siege_ville' => 'required|string|max:100',
            'siege_adresse' => 'required|string|max:255',
            'siege_code_postal' => 'required|string|max:10',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'pays' => 'nullable|string|max:100',
            'adresse' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'categories' => 'nullable|string',
            'public_cible' => 'nullable|string',
            'zone' => 'nullable|string|max:100',
            'type_structure' => 'nullable|string|max:100',
            'details' => 'nullable|string',
            'hebergement' => 'nullable|string',
            'site' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:50',
            'telephone' => 'nullable|string|max:25',
            'horaires' => 'nullable|string|max:255',
        ]);

        // Si latitude ou longitude absentes, récupérer via Nominatim
        if ((!$validated['latitude'] || !$validated['longitude']) && $validated['adresse']) {
            $address = urlencode($validated['adresse']);
            $response = Http::get("https://nominatim.openstreetmap.org/search?q={$address}&format=json&limit=1");
            $data = $response->json();

            if (!empty($data[0])) {
                $validated['latitude'] = $data[0]['lat'];
                $validated['longitude'] = $data[0]['lon'];
            }
        }

        // Si pas de localisation spécifique, utiliser le siège social
        if (empty($validated['ville']) && $validated['siege_ville']) {
            $validated['ville'] = $validated['siege_ville'];
            $validated['code_postal'] = $validated['siege_code_postal'];
        }

        structures::create($validated);

        return redirect()->route('annuaire.index')
            ->with('success', 'Structure créée avec succès !');
    }

    public function edit(structures $structure)
    {
        return view('structures.edit', [
            'structure' => $structure,
            'action' => route('structures.update', $structure),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Structures $structure)
    {
        $validated = $request->validate([
            'organisme' => 'required|string|max:255',
            'description' => 'nullable|string',
            'siege_ville' => 'required|string|max:100',
            'siege_adresse' => 'required|string|max:255',
            'siege_code_postal' => 'required|string|max:10',
            'type_structure' => 'nullable|string|max:100',
            'hebergement' => 'nullable|string',
            'details' => 'nullable|string',
            'telephone' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:50',
            'site' => 'nullable|string|max:500',
            'horaires' => 'nullable|string|max:255',
            'categories' => 'nullable|string',
            'public_cible' => 'nullable|string',
            'zone' => 'nullable|string|max:100',
        ]);

        $structure->update($validated);

        return redirect()
            ->route('annuaire.index')
            ->with('success', 'Structure modifiée avec succès');
    }

    public function destroy(structures $structure)
    {
        // Vérifier s'il y a des utilisateurs liés à cette structure
        if (method_exists($structure, 'users') && $structure->users()->count() > 0) {
            return redirect()->route('structures.index')
                ->with('error', 'Impossible de supprimer cette structure : des utilisateurs y sont rattachés.');
        }

        $structure->delete();

        return redirect()->route('annuaire.index')
            ->with('success', 'Structure supprimée avec succès !');
    }

    public function map()
    {
        $structures = structures::all();
        return view('structures.map', compact('structures'));
    }
}