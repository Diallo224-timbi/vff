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
        $resources = Resource::latest()->paginate(8);   
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
    $isAjax = $request->ajax() || $request->wantsJson();

    // 50 Mo max (Laravel attend KB => 50 * 1024 = 51200 KB)
    $maxSize = 51200;

    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'file' => 'required|file|max:' . $maxSize . '|mimes:jpg,jpeg,png,gif,webp,webm,pdf,doc,odt,docx,xls,xlsx,csv,ppt,pptx,txt',
        'category' => 'required|string',
        'service' => 'nullable|string|max:255',
        'description' => 'nullable|string',
    ], [
        'file.max' => 'Le fichier dépasse la taille autorisée (50 Mo maximum).',
        'file.mimes' => 'Format de fichier non autorisé.',
        'title.required' => 'Le titre est obligatoire.',
        'file.required' => 'Veuillez sélectionner un fichier.',
        'category.required' => 'La catégorie est obligatoire.',
    ]);

    if ($validator->fails()) {
        if ($isAjax) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Veuillez corriger les erreurs du formulaire.');
    }

    try {
        if (!$request->hasFile('file')) {
            return $isAjax
                ? response()->json(['success' => false, 'message' => 'Aucun fichier envoyé'], 400)
                : redirect()->back()->with('error', 'Aucun fichier envoyé')->withInput();
        }

        $file = $request->file('file');

        // sécurité supplémentaire (taille réelle en bytes)
        if ($file->getSize() > ($maxSize * 1024)) {
            return $isAjax
                ? response()->json(['success' => false, 'message' => 'Fichier trop volumineux'], 413)
                : redirect()->back()->with('error', 'Fichier trop volumineux (max 50 Mo)')->withInput();
        }

        $extension = strtolower($file->getClientOriginalExtension());

        $isImage = in_array($extension, ['jpg','jpeg','png','gif','webp','svg']);
        $isVideo = in_array($extension, ['mp4','webm','avi','mov','mkv']);

        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $path = $file->storeAs('resources', $fileName, 'public');

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
            'service' => $request->service,
            'user_id' => auth()->id(),
            'download_count' => 0,
        ]);

        return $isAjax
            ? response()->json([
                'success' => true,
                'message' => 'Ressource ajoutée avec succès',
                'resource' => $resource
            ])
            : redirect()->route('resources.index')
                ->with('success', 'Ressource ajoutée avec succès');

    } catch (\Exception $e) {
        return $isAjax
            ? response()->json([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ], 500)
            : redirect()->back()
                ->with('error', 'Erreur lors de l\'ajout : ' . $e->getMessage())
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
            'file' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,gif,webp,mp4,webm,pdf,doc,odt,docx,xls,xlsx,ppt,pptx,txt'
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
            'odt' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'xls' => 'fa-file-excel',
            'xlsx' => 'fa-file-excel',
            'csv' => 'fa-file-csv',
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