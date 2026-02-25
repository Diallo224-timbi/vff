<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::latest()->paginate(15);
        
        // Récupérer toutes les ressources pour les statistiques
        $allResources = Resource::all();
        
        // Types de fichiers
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $videoTypes = ['mp4', 'webm', 'avi', 'mov', 'mkv'];
        
        // Compter avec filter()
        $stats = [
            'images' => $allResources->filter(fn($r) => in_array($r->file_type, $imageTypes))->count(),
            'videos' => $allResources->filter(fn($r) => in_array($r->file_type, $videoTypes))->count(),
            'documents' => $allResources->filter(fn($r) => !in_array($r->file_type, $imageTypes) && !in_array($r->file_type, $videoTypes))->count(),
            'categories' => [
                'procedure' => $allResources->where('category', 'procedure')->count(),
                'outil' => $allResources->where('category', 'outil')->count(),
                'fiche_reflexe' => $allResources->where('category', 'fiche_reflexe')->count(),
                'ressource' => $allResources->where('category', 'ressource')->count(),
            ]
        ];
        
        return view('resources.index', compact('resources', 'stats'));
    }

    public function store(Request $request)
    {
        // Vérifier si c'est une requête AJAX
        $isAjax = $request->ajax() || $request->wantsJson();
        
        // Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:20480|mimes:jpg,jpeg,png,gif,webp,mp4,webm,pdf,doc,docx,xls,xlsx,ppt,pptx,txt',
            'category' => 'required|string',
            'theme' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Upload du fichier
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                // Déterminer le type
                $extension = strtolower($file->getClientOriginalExtension());
                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                $isVideo = in_array($extension, ['mp4', 'webm', 'avi', 'mov', 'mkv']);
                
                // Générer un nom unique
                $fileName = time() . '_' . uniqid() . '.' . $extension;
                
                // Stocker le fichier
                $path = $file->storeAs('resources', $fileName, 'public');
                
                // Créer la ressource
                $resource = Resource::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'file_type' => $extension,
                    'file_icon' => $this->getFileIcon($extension),
                    'is_image' => $isImage,
                    'is_video' => $isVideo,
                    'category' => $request->category,
                    'theme' => $request->theme,
                    'service' => $request->service,
                    'user_id' => auth()->id(),
                    'download_count' => 0,
                ]);

                if ($isAjax) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Ressource ajoutée avec succès',
                        'resource' => $resource
                    ]);
                }

                return redirect()->route('resources.index')
                    ->with('success', 'Ressource ajoutée avec succès');
            }

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun fichier uploadé'
                ], 400);
            }

            return redirect()->back()
                ->with('error', 'Aucun fichier uploadé')
                ->withInput();

        } catch (\Exception $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'upload: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'upload: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $isAjax = $request->ajax() || $request->wantsJson();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'theme' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,gif,webp,mp4,webm,pdf,doc,docx,xls,xlsx,ppt,pptx,txt'
        ]);

        if ($validator->fails()) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $resource = Resource::findOrFail($id);
            
            // Mise à jour des champs
            $resource->title = $request->title;
            $resource->description = $request->description;
            $resource->category = $request->category;
            $resource->theme = $request->theme;
            $resource->service = $request->service;

            // Si nouveau fichier uploadé
            if ($request->hasFile('file')) {
                // Supprimer l'ancien fichier
                if ($resource->file_path) {
                    Storage::disk('public')->delete($resource->file_path);
                }
                
                $file = $request->file('file');
                $extension = strtolower($file->getClientOriginalExtension());
                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                $isVideo = in_array($extension, ['mp4', 'webm', 'avi', 'mov', 'mkv']);
                
                $fileName = time() . '_' . uniqid() . '.' . $extension;
                $path = $file->storeAs('resources', $fileName, 'public');
                
                $resource->file_name = $file->getClientOriginalName();
                $resource->file_path = $path;
                $resource->file_size = $file->getSize();
                $resource->file_type = $extension;
                $resource->file_icon = $this->getFileIcon($extension);
                $resource->is_image = $isImage;
                $resource->is_video = $isVideo;
            }

            $resource->save();

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ressource modifiée avec succès',
                    'resource' => $resource
                ]);
            }

            return redirect()->route('resources.index')
                ->with('success', 'Ressource modifiée avec succès');

        } catch (\Exception $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la modification: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $resource = Resource::findOrFail($id);
            
            // Supprimer le fichier
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }
            
            $resource->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ressource supprimée avec succès'
                ]);
            }

            return redirect()->route('resources.index')
                ->with('success', 'Ressource supprimée avec succès');

        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $resource = Resource::findOrFail($id);
            
            // Vérification des permissions
            if (auth()->user()->role !== 'admin' && auth()->user()->id !== $resource->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas la permission de modifier cette ressource'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'id' => $resource->id,
                'title' => $resource->title,
                'description' => $resource->description,
                'category' => $resource->category,
                'theme' => $resource->theme,
                'service' => $resource->service,
                'file_name' => $resource->file_name,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ressource non trouvée'
            ], 404);
        }
    }

    public function download($id)
    {
        try {
            $resource = Resource::findOrFail($id);
            
            // Incrémenter le compteur
            $resource->increment('download_count');
            
            // Vérifier si le fichier existe
            if (!Storage::disk('public')->exists($resource->file_path)) {
                return redirect()->back()->with('error', 'Fichier non trouvé');
            }
            
            // Télécharger le fichier
            return Storage::disk('public')->download($resource->file_path, $resource->file_name);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du téléchargement');
        }
    }

    private function getFileIcon($extension)
    {
        $icons = [
            'pdf' => 'fa-file-pdf',
            'doc' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'xls' => 'fa-file-excel',
            'xlsx' => 'fa-file-excel',
            'ppt' => 'fa-file-powerpoint',
            'pptx' => 'fa-file-powerpoint',
            'jpg' => 'fa-file-image',
            'jpeg' => 'fa-file-image',
            'png' => 'fa-file-image',
            'gif' => 'fa-file-image',
            'mp4' => 'fa-file-video',
            'webm' => 'fa-file-video',
            'txt' => 'fa-file-alt',
        ];

        return $icons[$extension] ?? 'fa-file';
    }
}