<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\UserValidatedMail;
use App\Mail\UserBlockedMail;
use App\Models\Structures;
use App\Models\ActivityLog;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{

    public function __construct()
    {
       //$this->middleware("auth");
    }
// Récupère les structures d'un organisme spécifique avec groupeby
    
    // Liste des utilisateurs (tous ou filtrés selon rôle)
    public function indexx()
    {
        $authUser = auth()->user();

        $query = User::query()
            ->whereIn('role', ['user', 'moderateur', 'admin'])
            ->orderBy('created_at', 'desc');

        // Si modérateur, limiter à sa structure
        if ($authUser->role === 'moderateur') {
            $query->where('id_structure', $authUser->id_structure);
        }

        $users = $query->get();
        $structures = Structures::orderBy('organisme')->get();

        return view('admin.users', compact('users', 'structures'));
    }

    // Filtrer les utilisateurs par structure
    public function getAllUsersByStructure($structureId)
    {
        $users = User::where('id_structure', $structureId)
                     ->whereIn('role', ['user', 'moderateur', 'admin'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        $structures = Structures::orderBy('organisme')->get();

        return view('admin.users', compact('users', 'structures'));
    }

    // Validation d'un utilisateur
    public function validatedUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['etatV' => 'valider']);

        Mail::to($user->email)->send(new UserValidatedMail($user));

        $currentUser = auth()->user();
        ActivityLog::log(
            'Validation utilisateur',
            "Utilisateur validé: {$user->name} par {$currentUser->name} (Rôle: {$currentUser->role})",
            $currentUser->id
        );

        return redirect()->back()->with('success', 'Utilisateur validé avec succès.');
    }
// supprimer un utilisateur
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        $user->delete();

        $currentUser = auth()->user();
        ActivityLog::log(
            'Suppression utilisateur',
            "Utilisateur supprimé: {$userName} par {$currentUser->name}",
            $currentUser->id
        );

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }
    // Blocage d'un utilisateur
    public function blockUser(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|min:5',
        ]);

        $user = User::findOrFail($id);

        if ($user->etatV !== 'valider') {
            return back()->withErrors(['error' => 'Impossible de bloquer un compte non validé.']);
        }

        $user->etatV = 'bloqué';
        $user->block_reason = $request->reason;
        $user->save();

        Mail::to($user->email)->send(new UserBlockedMail($user));

        $currentUser = auth()->user();
        ActivityLog::log(
            'Blocage utilisateur',
            "Utilisateur bloqué: {$user->name} par {$currentUser->name} (Rôle: {$currentUser->role})",
            $currentUser->id
        );

        return back()->with('success', 'Utilisateur bloqué avec succès.');
    }

    // Déblocage d'un utilisateur
    public function dblockUser($id)
    {
        $user = User::findOrFail($id);
        $user->etatV = 'valider';
        $user->block_reason = null;
        $user->save();

        $currentUser = auth()->user();
        ActivityLog::log(
            'Déblocage utilisateur',
            "Utilisateur débloqué: {$user->name} par {$currentUser->name} (Rôle: {$currentUser->role})",
            $currentUser->id
        );

        return redirect()->back()->with('success', 'Utilisateur débloqué avec succès.');
    }

    // Suppression d'un utilisateur
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        $user->delete();

        $currentUser = auth()->user();
        ActivityLog::log(
            'Suppression utilisateur',
            "Utilisateur supprimé: {$userName} par {$currentUser->name}",
            $currentUser->id
        );

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    // Modification d'un utilisateur
    public function updateUser(Request $request, $id)
    {
        $userToUpdate = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userToUpdate->id,
            'phone' => 'nullable|string|max:20',
            'id_structure' => 'nullable|exists:structure,id',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'role' => 'required|string|in:user,moderateur,admin',
            
        ]);

        $userToUpdate->update([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'phone' => $request->phone,
            'id_structure' => $request->id_structure,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'role' => $request->role
        ]);

        $currentUser = auth()->user();
        ActivityLog::log(
            'Modification d\'un utilisateur',
            "Utilisateur modifié: {$userToUpdate->name} par {$currentUser->name}",
            $currentUser->id
        );

        return redirect()->back()->with('success', 'Utilisateur mis à jour avec succès.');
    }
}