<?php
// app/Http/Controllers/SchemaController.php

namespace App\Http\Controllers;

use App\Models\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SchemaController extends Controller
{
    /**
     * Affiche la liste des schémas
     */
    public function index()
    {
        $schemas = Schema::with('user')->orderBy('created_at', 'desc')->get();
        
        // Comptage par GT
        $schemasByGt = [];
        $gtList = ['GT1', 'GT2', 'GT3', 'GT4', 'GT5', 'GT6'];
        
        foreach ($gtList as $gt) {
            $schemasByGt[$gt] = Schema::where('category', $gt)->count();
        }
        
        return view('schemas.index', [
            'schemas' => $schemas,
            'schemasByGt' => $schemasByGt,
            'totalSchemas' => $schemas->count()
        ]);
    }

    /**
     * Enregistre un nouveau schéma
     */
    public function store(Request $request)
    {
        // Validation
        $rules = [
            'category' => 'required|string',
            'sub_category' => 'nullable|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
        
        if ($request->type === 'link') {
            $rules['link_url'] = 'required|url';
        } else {
            $rules['file'] = 'required|file|max:20480';
        }
        
        $validated = $request->validate($rules);

        // Création du schéma
        $schema = new Schema();
        $schema->user_id = auth()->id();
        $schema->category = $request->category;
        $schema->sub_category = $request->sub_category;
        $schema->title = $request->title;
        $schema->description = $request->description;
        $schema->is_link = false;
        $schema->is_image = false;

        // Gestion selon le type
        if ($request->type === 'link') {
            $schema->is_link = true;
            $schema->data = json_encode(['link_url' => $request->link_url]);
        } else {
            // Gestion du fichier
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $extension = strtolower($file->getClientOriginalExtension());
                $filename = time() . '_' . Str::slug($request->title) . '.' . $extension;
                $path = $file->storeAs('schemas', $filename, 'public');
                
                $schema->file_path = $path;
                $schema->file_name = $filename;
                $schema->file_type = $extension;
                
                // Vérifier si c'est une image
                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
                $schema->is_image = in_array($extension, $imageExtensions);
            }
        }

        $schema->save();

        return redirect()->route('schemas.index')
            ->with('success', 'Compte rendu ajouté avec succès');
    }

    /**
     * Supprime un schéma
     */
    public function destroy($id)
    {
        try {
            $schema = Schema::findOrFail($id);
            
            // Vérification des permissions
            if (auth()->user()->role !== 'admin' && auth()->user()->id !== $schema->user_id) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vous n\'êtes pas autorisé à supprimer ce document'
                ], 403);
            }

            // Suppression du fichier physique
            if ($schema->file_path) {
                Storage::disk('public')->delete($schema->file_path);
            }

            $schema->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Compte rendu supprimé avec succès'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Document non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filtrer les schémas par GT
     */
    public function filterByGt(Request $request, $gt = null)
    {
        if (!$gt) {
            $gt = $request->input('gt');
        }
        
        if (!$gt) {
            return response()->json([
                'success' => false,
                'message' => 'GT non spécifié'
            ], 400);
        }

        $schemas = Schema::where('category', $gt)
            ->orWhere('sub_category', $gt)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schemas
        ]);
    }

    /**
     * Obtenir les compteurs pour tous les GT
     */
    public function getCounts()
    {
        $counts = [
            'GT1' => Schema::where('category', 'GT1')->count(),
            'GT2' => Schema::where('category', 'GT2')->count(),
            'GT3' => Schema::where('category', 'GT3')->count(),
            'GT4' => Schema::where('category', 'GT4')->count(),
            'GT5' => Schema::where('category', 'GT5')->count(),
            'GT6' => Schema::where('category', 'GT6')->count(),
            'SGT1' => Schema::where('sub_category', 'SGT1')->count(),
            'SGT2' => Schema::where('sub_category', 'SGT2')->count(),
            'SGT3' => Schema::where('sub_category', 'SGT3')->count(),
            'SGT4' => Schema::where('sub_category', 'SGT4')->count(),
        ];

        return response()->json($counts);
    }

    /**
     * Affiche les détails d'un schéma
     */
    public function show($id)
    {
        $schema = Schema::with('user')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $schema
        ]);
    }
}