<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StructureController extends Controller
{
    public function index(Request $request)
{
    $query = Structure::query();

    // Recherche côté serveur
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where('nom_structure', 'like', "%{$search}%")
              ->orWhere('adresse', 'like', "%{$search}%")
              ->orWhere('ville', 'like', "%{$search}%")
              ->orWhere('code_postal', 'like', "%{$search}%")
              ->orWhere('contact', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('responsable', 'like', "%{$search}%");
    }

    $structures = $query->orderBy('nom_structure')->paginate(20)->withQueryString();

    return view('structures.index', compact('structures'));
}

    public function create()
    {
        return view('structures.create', [
            'structure' => new Structure(),
            'action' => route('structures.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'nom_structure' => 'required|string|max:255',
            'description' => 'nullable|string',
            'adresse' => 'required|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'pays' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'responsable' => 'nullable|string|max:255',
        ]);

        // Si latitude ou longitude absentes, récupérer via Nominatim
        if (!$validated['latitude'] || !$validated['longitude']) {
            $address = urlencode($validated['adresse']);
            $response = Http::get("https://nominatim.openstreetmap.org/search?q={$address}&format=json&limit=1");
            $data = $response->json();

            if (!empty($data[0])) {
                $validated['latitude'] = $data[0]['lat'];
                $validated['longitude'] = $data[0]['lon'];
            }
        }

        Structure::create($validated);

        return redirect()->route('structures.index')->with('success', 'Structure créée avec succès !');
    }

    public function edit(Structure $structure)
    {
        return view('structures.edit', [
            'structure' => $structure,
            'action' => route('structures.update', $structure),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Structure $structure)
    {
        $validated = $request->validate([
            'nom_structure' => 'required|string|max:255',
            'description' => 'nullable|string',
            'adresse' => 'required|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'pays' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'responsable' => 'nullable|string|max:255',
        ]);

        // Si latitude ou longitude absentes, récupérer via Nominatim
        if (!$validated['latitude'] || !$validated['longitude']) {
            $address = urlencode($validated['adresse']);
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
    public function destroy(Structure $structure)
    {
        // Vérifier s'il y a des utilisateurs liés à cette structure
        if ($structure->users()->count() > 0) {
            // Rediriger avec un message d'erreur
            return redirect()->route('structures.index')
                ->with('errors', 'Impossible de supprimer cette structure : des utilisateurs y sont rattachés.');
        }

        // Si aucun utilisateur n'est rattaché, on peut supprimer la structure
        $structure->delete();

        return redirect()->route('structures.index')
            ->with('success', 'Structure supprimée avec succès !');
    }

    public function map()
    {
        $structures = Structure::all();
        return view('structures.map', compact('structures'));
    }
}
