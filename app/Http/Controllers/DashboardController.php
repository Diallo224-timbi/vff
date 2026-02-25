<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Structures;
use App\Models\Resource;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== STATISTIQUES UTILISATEURS =====
        $totalUsers = User::count();
        $validatedUsers = User::where('etatV', 'valider')->count();
        $pendingUsers = User::where('etatV', 'attente')->count();
        $admins = User::where('role', 'admin')->count();
        $moderateurs = User::where('role', 'moderateur')->count();
        $usersCount = User::where('role', 'user')->count();

        // ===== STATISTIQUES STRUCTURES =====
        $totalStructures = Structures::count();
        $typesCount = Structures::whereNotNull('type_structure')->distinct('type_structure')->count('type_structure');
        $villesCount = Structures::whereNotNull('ville')->distinct('ville')->count('ville');
        
        // Types de structures pour le graphique
        $typeLabels = Structures::whereNotNull('type_structure')
            ->distinct('type_structure')
            ->pluck('type_structure')
            ->take(5)
            ->toArray();
            
        $typeData = [];
        foreach($typeLabels as $type) {
            $typeData[] = Structures::where('type_structure', $type)->count();
        }

        // ===== STATISTIQUES DOCUMENTS =====
        $totalDocuments = Resource::count();
        $totalDownloads = Resource::sum('download_count');
        
        // Types de fichiers
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $videoTypes = ['mp4', 'webm', 'avi', 'mov', 'mkv'];
        
        $stats = [
            'images' => Resource::whereIn('file_type', $imageTypes)->count(),
            'videos' => Resource::whereIn('file_type', $videoTypes)->count(),
            'documents' => Resource::whereNotIn('file_type', array_merge($imageTypes, $videoTypes))->count(),
            'categories' => [
                'procedure' => Resource::where('category', 'procedure')->count(),
                'outil' => Resource::where('category', 'outil')->count(),
                'fiche_reflexe' => Resource::where('category', 'fiche_reflexe')->count(),
                'ressource' => Resource::where('category', 'ressource')->count(),
            ]
        ];

        // ===== STATISTIQUES LOGS =====
        $totalConnexions = ActivityLog::where('action', 'login')->count();
        $connexionsJour = ActivityLog::where('action', 'login')->whereDate('created_at', today())->count();

        // ===== ACTIVITÉ DES 7 DERNIERS JOURS =====
        $activityLabels = [];
        $activityConnexions = [];
        $activityCreations = [];
        $activityUpdates = [];
        $activityDeletes = [];

        for($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $activityLabels[] = $date->format('D d/m');
            
            $activityConnexions[] = ActivityLog::where('action', 'login')
                ->whereDate('created_at', $date)->count();
            
            $activityCreations[] = ActivityLog::where('action', 'create')
                ->whereDate('created_at', $date)->count();
            
            $activityUpdates[] = ActivityLog::where('action', 'update')
                ->whereDate('created_at', $date)->count();
            
            $activityDeletes[] = ActivityLog::where('action', 'delete')
                ->whereDate('created_at', $date)->count();
        }

        // ===== ÉLÉMENTS RÉCENTS =====
        $recentUsers = User::latest()->take(5)->get();
        $recentDocuments = Resource::latest()->take(5)->get();
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalUsers',
            'validatedUsers',
            'pendingUsers',
            'admins',
            'moderateurs',
            'usersCount',
            'totalStructures',
            'typesCount',
            'villesCount',
            'typeLabels',
            'typeData',
            'totalDocuments',
            'totalDownloads',
            'stats',
            'totalConnexions',
            'connexionsJour',
            'activityLabels',
            'activityConnexions',
            'activityCreations',
            'activityUpdates',
            'activityDeletes',
            'recentUsers',
            'recentDocuments',
            'recentLogs'
        ));
    }
}