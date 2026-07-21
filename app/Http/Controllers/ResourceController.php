<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\ActivityLog;
use App\Models\User;
use App\Mail\ImportantResourceMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Schema;

class ResourceController extends Controller
{
    /**
     * Afficher la liste des ressources (hors corbeille)
     */
    public function index()
    {
        $resources = Resource::latest()->paginate(12);
        
        // Récupérer toutes les ressources non supprimées pour les statistiques
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
        
         $schemas = Schema::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('resources.index', compact('resources', 'stats', 'schemas'));
    }

    /**
     * Afficher la corbeille
     */
    public function trash()
    {
        $resources = Resource::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);
        return view('resources.trash', compact('resources'));
    }

    /**
     * Ajouter une nouvelle ressource
     */
   public function store(Request $request)
    {
    $isAjax = $request->ajax() || $request->wantsJson();
    $maxSize = 51200; // 50 Mo en KB
    // Validation
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'file' => 'nullable|file|max:' . $maxSize . '|mimes:jpg,jpeg,png,gif,webp,webm,pdf,doc,odt,docx,xls,xlsx,csv,ppt,pptx,txt',
        'link_url' => 'nullable|url',
        'category' => 'required|string',
        'description' => 'nullable|string',
    ], [
        'file.max' => 'Le fichier dépasse la taille autorisée (50 Mo maximum).',
        'file.mimes' => 'Format de fichier non autorisé.',
        'title.required' => 'Le titre est obligatoire.',
        'category.required' => 'La catégorie est obligatoire.',
    ]);

    if ($validator->fails()) {
        return $isAjax
            ? response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422)
            : redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs du formulaire.');
    }

    try {
        // Obligation : fichier OU lien
        if (!$request->hasFile('file') && !$request->filled('link_url')) {
            return $isAjax
                ? response()->json([
                    'success' => false,
                    'message' => 'Ajoutez un fichier ou un lien'
                ], 422)
                : redirect()->back()
                    ->with('error', 'Ajoutez un fichier ou un lien')
                    ->withInput();
        }

        // =========================
        // VÉRIFICATION DES DOUBLONS
        // =========================
        $file_name = null;
        $link_url = null;
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
        } elseif ($request->filled('link_url')) {
            $link_url = $request->link_url;
        }
        
        // Vérifier si un document similaire existe déjà
        $existingResource = Resource::findSimilar(
            $request->title,
            $request->category,
            $file_name,
            $link_url
        );
        
        if ($existingResource) {
            $errorMessage = 'Un document similaire existe déjà : "' . $existingResource->title . '" dans la catégorie "' . $existingResource->category . '"';
            
            if ($existingResource->file_name) {
                $errorMessage .= ' avec le fichier "' . $existingResource->file_name . '"';
            } elseif ($existingResource->link_url) {
                $errorMessage .= ' avec le lien "' . $existingResource->link_url . '"';
            }
            
            return $isAjax
                ? response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'existing_resource' => $existingResource
                ], 409) // 409 Conflict
                : redirect()->back()
                    ->with('error', $errorMessage)
                    ->withInput();
        }
        // =========================
        // GESTION FICHIER
        // =========================
        $file = $request->file('file');

        $path = null;
        $fileName = null;
        $fileSize = null;
        $extension = null;
        $isImage = false;
        $isVideo = false;

        if ($file) {
            $fileSize = $file->getSize();

            if ($fileSize > ($maxSize * 1024)) {
                return $isAjax
                    ? response()->json(['success' => false, 'message' => 'Fichier trop volumineux'], 413)
                    : redirect()->back()
                        ->with('error', 'Fichier trop volumineux (max 50 Mo)')
                        ->withInput();
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $isImage = in_array($extension, ['jpg','jpeg','png','gif','webp','svg']);
            $isVideo = in_array($extension, ['mp4','webm','avi','mov','mkv']);

            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $path = $file->storeAs('resources', $fileName, 'public');
        }
        // =========================
        // CREATION RESOURCE
        // =========================
        $resource = Resource::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_name' => $file ? $file->getClientOriginalName() : null,
            'file_path' => $path,
            'file_size' => $fileSize,
            'file_type' => $extension,
            'file_icon' => $extension ? $this->getFileIcon($extension) : 'fas fa-link',
            'is_image' => $isImage,
            'is_video' => $isVideo,
            'category' => $request->category,
            'user_id' => auth()->id(),
            'link_url' => $request->link_url,
            'download_count' => 0,
            'important' => $request->has('important') ? true : false,
        ]);
        if ($resource->important) {
            // Envoyer une notification aux utilisateurs (ex: email)
            $usersToNotify = User::where('notification', true)->pluck('email');
            foreach ($usersToNotify as $email) {
                Mail::to($email)->send(new ImportantResourceMail($resource));
            }

        }
        ActivityLog::log(
            'Création de ressource',
            'Ressource créée: ' . $resource->title, auth()->id()
        );

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

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        try {
            $resource = Resource::findOrFail($id);
            return view('resources.edit', compact('resource'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ressource non trouvée');
        }
    }

    /**
     * Mettre à jour une ressource
     */
    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'file' => 'nullable|file|max:51200|mimes:jpg,jpeg,png,gif,webp,webm,pdf,doc,odt,docx,xls,xlsx,csv,ppt,pptx,txt',
            'link_url' => 'nullable|url',
            'important' => 'nullable|boolean'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs du formulaire.');
        }
        
        try {
            $resource->title = $request->title;
            $resource->description = $request->description;
            $resource->category = $request->category;
            $resource->important = $request->has('important') ? true : false;
            // Gestion du fichier ou lien
            if ($request->hasFile('file')) {
                // Supprimer l'ancien fichier
                if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
                    Storage::disk('public')->delete($resource->file_path);
                }
                
                $file = $request->file('file');
                $extension = strtolower($file->getClientOriginalExtension());
                $fileName = time() . '_' . uniqid() . '.' . $extension;
                $path = $file->storeAs('resources', $fileName, 'public');
                
                $resource->file_path = $path;
                $resource->file_name = $file->getClientOriginalName();
                $resource->file_size = $file->getSize();
                $resource->file_type = $extension;
                $resource->file_icon = $this->getFileIcon($extension);
                $resource->is_image = in_array($extension, ['jpg','jpeg','png','gif','webp','svg']);
                $resource->is_video = in_array($extension, ['mp4','webm','avi','mov','mkv']);
                $resource->is_link = $request->filled('link_url');
                $resource->link_url = null;
            }
            
            if ($request->filled('link_url')) {
                $resource->link_url = $request->link_url;
                $resource->is_link = true;
                $resource->is_image = false;
                $resource->is_video = false;
            }
            
            $resource->save();
            
            ActivityLog::log('Modification de ressource', 'Ressource modifiée: ' . $resource->title, auth()->id());
            
            return redirect()->route('resources.index')
                ->with('success', 'Ressource modifiée avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $resource = Resource::findOrFail($id);
            $resource->delete(); // Soft delete - met à la corbeille

            ActivityLog::log('Suppression de ressource', 'Ressource déplacée vers la corbeille: ' . $resource->title, auth()->id());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ressource déplacée vers la corbeille'
                ]);
            }
            
            return redirect()->route('resources.index')
                ->with('success', 'Ressource déplacée vers la corbeille');

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

    /**
     * Restaurer une ressource depuis la corbeille
     */
    public function restore($id)
    {
        try {
            $resource = Resource::onlyTrashed()->findOrFail($id);
            $resource->restore();
            
            ActivityLog::log('Restauration de ressource', 'Ressource restaurée: ' . $resource->title, auth()->id());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ressource restaurée avec succès'
                ]);
            }
            
            return redirect()->route('resources.trash')->with('success', 'Ressource restaurée avec succès');
            
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la restauration: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la restauration: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer définitivement une ressource
     */
    public function forceDelete($id)
    {
        try {
            $resource = Resource::onlyTrashed()->findOrFail($id);
            
            // Supprimer le fichier physique
            if (!$resource->is_link && $resource->file_path) {
                if (Storage::disk('public')->exists($resource->file_path)) {
                    Storage::disk('public')->delete($resource->file_path);
                }
            }
            
            $title = $resource->title;
            $resource->forceDelete();
            
            ActivityLog::log('Suppression définitive', 'Ressource supprimée définitivement: ' . $title, auth()->id());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ressource supprimée définitivement'
                ]);
            }
            
            return redirect()->route('resources.trash')->with('success', 'Ressource supprimée définitivement');
            
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression définitive: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la suppression définitive: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger une ressource
     */
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

    /**
     * Obtenir l'icône du fichier
     */
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
            'webp' => 'fa-file-image',
            'svg' => 'fa-file-image',
            'mp4' => 'fa-file-video',
            'webm' => 'fa-file-video',
            'avi' => 'fa-file-video',
            'mov' => 'fa-file-video',
            'mkv' => 'fa-file-video',
            'txt' => 'fa-file-alt',
        ];

        return $icons[$extension] ?? 'fa-file';
    }

    //vider la corbeille
    public function emptyTrash()
    {
        try {
            $trashedResources = Resource::onlyTrashed()->get();
            
            foreach ($trashedResources as $resource) {
                // Supprimer le fichier physique
                if (!$resource->is_link && $resource->file_path) {
                    if (Storage::disk('public')->exists($resource->file_path)) {
                        Storage::disk('public')->delete($resource->file_path);
                    }
                }
                $title = $resource->title;
                $resource->forceDelete();
                
                ActivityLog::log('Suppression définitive', 'Ressource supprimée définitivement: ' . $title, auth()->id());
            }
            
            return redirect()->route('resources.trash')->with('success', 'Corbeille vidée avec succès');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la vidange de la corbeille: ' . $e->getMessage());
        }
    }
}