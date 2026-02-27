<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    // Afficher tous les logs avec statistiques
    public function index(Request $request)
    {
        $query = ActivityLog::query()->with('user')->latest();

        // Filtrer par utilisateur
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtrer par action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filtrer par date de début
        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }

        // Filtrer par date de fin
        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        // Recherche dans description ou IP
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        // Pagination
        $logs = $query->paginate(1000);

        // Statistiques pour les cartes
        $totalLogs = ActivityLog::count();
        $uniqueActions = ActivityLog::distinct('action')->count('action');
        $activeUsers = ActivityLog::distinct('user_id')->whereNotNull('user_id')->count('user_id');
        $lastActivity = ActivityLog::latest()->first()?->created_at->diffForHumans() ?? 'Aucune';

        // Types d'actions uniques pour le filtre
        $actionTypes = ActivityLog::distinct('action')
            ->whereNotNull('action')
            ->pluck('action')
            ->toArray();

        // Données pour le graphique (7 derniers jours)
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d/m');
            
            $count = ActivityLog::whereDate('created_at', $date)->count();
            $chartData[] = $count;
        }

        // Liste des utilisateurs pour le filtre
        $users = User::orderBy('name')->get();

        return view('activity_logs.index', compact(
            'logs', 
            'users', 
            'totalLogs', 
            'uniqueActions', 
            'activeUsers', 
            'lastActivity',
            'actionTypes',
            'chartLabels',
            'chartData'
        ));
    }

    // Récupérer les statistiques en temps réel (AJAX)
    public function stats()
    {
        return response()->json([
            'totalLogs' => ActivityLog::count(),
            'uniqueActions' => ActivityLog::distinct('action')->count('action'),
            'activeUsers' => ActivityLog::distinct('user_id')->whereNotNull('user_id')->count('user_id'),
            'lastActivity' => ActivityLog::latest()->first()?->created_at->diffForHumans() ?? 'Aucune'
        ]);
    }

    // Afficher les détails d'un log (AJAX)
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        
        return view('activity_logs.partials.details', compact('log'));
    }

    // Exporter les logs filtrés
    public function export(Request $request)
    {
        $query = ActivityLog::query()->with('user');

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->get();

        // Générer un fichier CSV
        $filename = 'logs_export_' . now()->format('Y-m-d_His') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        // En-têtes CSV
        fputcsv($handle, ['ID', 'Utilisateur', 'Action', 'Description', 'IP', 'User Agent', 'Date']);

        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->id,
                $log->user->name ?? 'Système',
                $log->action,
                $log->description,
                $log->ip_address,
                $log->user_agent,
                $log->created_at->format('d/m/Y H:i:s')
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // Suppression multiple de logs
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activity_logs,id'
        ]);

        ActivityLog::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' log(s) supprimé(s) avec succès.'
        ]);
    }

    // Supprimer un log spécifique
    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Log supprimé avec succès.');
    }

    // Supprimer tous les logs
    public function destroyAll()
    {
        ActivityLog::truncate();
        
        return redirect()->back()->with('success', 'Tous les logs ont été supprimés.');
    }
}