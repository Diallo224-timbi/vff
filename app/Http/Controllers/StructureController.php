<?php

namespace App\Http\Controllers;

use App\Models\structures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf; // si tu utilises barryvdh/laravel-dompdf

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
            'siege_ville' => 'nullable|string|max:255',
            'siege_adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'pays' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'categories' => 'nullable|string|max:255',
            'public_cible' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:255',
            'type_structure' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'hebergement' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
        ]);

        // Si latitude ou longitude absentes, récupérer via Nominatim
        if (!$validated['latitude'] || !$validated['longitude']) {
            $address = urlencode($validated['siege_adresse'] . ', ' . $validated['siege_ville']);
            $response = Http::get("https://nominatim.openstreetmap.org/search?q={$address}&format=json&limit=1");
            $data = $response->json();

            if (!empty($data[0])) {
                $validated['latitude'] = $data[0]['lat'];
                $validated['longitude'] = $data[0]['lon'];
            }
        }

        structures::create($validated);

        return redirect()->route('structures.index')->with('success', 'Structure créée avec succès !');
    }

    public function edit(structures $structure)
    {
        return view('structures.edit', [
            'structure' => $structure,
            'action' => route('structures.update', $structure),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, structures $structure)
    {
        $validated = $request->validate([
            'organisme' => 'required|string|max:255',
            'description' => 'nullable|string',
            'siege_ville' => 'nullable|string|max:255',
            'siege_adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'pays' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'categories' => 'nullable|string|max:255',
            'public_cible' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:255',
            'type_structure' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'hebergement' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
        ]);

        if (!$validated['latitude'] || !$validated['longitude']) {
            $address = urlencode($validated['siege_adresse'] . ', ' . $validated['siege_ville']);
            $response = Http::get("https://nominatim.openstreetmap.org/search?q={$address}&format=json&limit=1");
            $data = $response->json();

            if (!empty($data[0])) {
                $validated['latitude'] = $data[0]['lat'];
                $validated['longitude'] = $data[0]['lon'];
            }
        }

        $structure->update($validated);

        return redirect()->route('structures.index')->with('success', 'Structure modifiée avec succès !');
    }

    public function destroy(structures $structure)
    {
        // Vérifier s'il y a des utilisateurs liés à cette structure (si relation définie)
        if (method_exists($structure, 'users') && $structure->users()->count() > 0) {
            return redirect()->route('structures.index')
                ->with('errors', 'Impossible de supprimer cette structure : des utilisateurs y sont rattachés.');
        }

        $structure->delete();

        return redirect()->route('structures.index')->with('success', 'Structure supprimée avec succès !');
    }

    public function map()
    {
        $structures = structures::all();
        return view('structures.map', compact('structures'));
    }
}
