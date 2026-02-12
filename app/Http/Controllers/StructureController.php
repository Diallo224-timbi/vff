<?php

namespace App\Http\Controllers;

use App\Models\structures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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

        return view('annuaire.index', compact('structures'));
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
            // ✅ NOUVEAU : Validation du logo
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,svg|max:2048', // 2Mo max
        ]);

        // ✅ NOUVEAU : Gestion de l'upload du logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        // Si latitude ou longitude absentes, récupérer via Nominatim
        if ((!$validated['latitude'] || !$validated['longitude']) && $validated['adresse']) {
            $address = urlencode($validated['adresse']);
            $response = Http::withHeaders([
                'User-Agent' => 'AnnuaireSocial/1.0',
            ])->get("https://nominatim.openstreetmap.org/search", [
                'q' => $validated['adresse'],
                'format' => 'json',
                'limit' => 1,
            ]);
            
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

    public function update(Request $request, structures $structure)
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
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'pays' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            // ✅ NOUVEAU : Validation du logo en modification
            'logo' => 'sometimes|file|mimes:jpg,jpeg,png,svg|max:2048',
            // ✅ NOUVEAU : Détection suppression logo
            'remove_logo' => 'nullable|in:1',
        ]);

        // ✅ NOUVEAU : Gestion de la SUPPRESSION du logo
        if ($request->has('remove_logo') && $request->remove_logo == '1') {
            // Supprimer l'ancien fichier physique
            if ($structure->logo) {
                Storage::disk('public')->delete($structure->logo);
            }
            // Mettre à null en base
            $validated['logo'] = null;
        }

        // ✅ NOUVEAU : Gestion du NOUVEAU logo (écrase l'ancien)
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo s'il existe
            if ($structure->logo) {
                Storage::disk('public')->delete($structure->logo);
            }
            
            // Stocker le nouveau
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $structure->update($validated);

        return redirect()
            ->route('annuaire.index')
            ->with('success', 'Structure modifiée avec succès');
    }

    public function destroy(structures $structure)
    {
        // ✅ NOUVEAU : Supprimer le logo physique avant de supprimer la structure
        if ($structure->logo) {
            Storage::disk('public')->delete($structure->logo);
        }

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