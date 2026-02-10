<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\UserValidatedMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserBlockedMail;
use App\Models\structures;


class AdminController extends Controller
{
   public function __construct()
    {
        //$this->middleware('admin');
    }   
    public function indexx()
{
    $authUser = auth()->user();

    $query = User::query()
        ->whereIn('role', ['user', 'moderateur'])
        ->orderBy('created_at', 'desc');

    // 👉 Si c'est un modérateur, on limite à SA structure
    if ($authUser->role === 'moderateur') {
        $query->where('id_structure', $authUser->id_structure);
    }

    $users = $query->get();

    $structures = Structures::orderBy('organisme')->get();

    return view('admin.users', compact('users', 'structures'));
}

    //methode pour filtrer les utilisateurs par structure
    public function getAllUsersByStructure($structureId)
    {
        $users = User::where('id_structure', $structureId)
                     ->whereIn('role', ['user', 'moderateur', 'admin'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        $structures = Structures::orderBy('organisme')->get();
        return view('admin.users', compact('users', 'structures'));
    }
    public function index2()
    {
        $users = User::all();
        $structures = Structures::orderBy('organisme')->get();
       return view('admin.users',compact('users', 'structures'));
       console.log($users);
    }

    public function validatedUser($id)
    {
        $user = User::findOrFail($id);

    // Mise à jour
        $user->update(['etatV' => 'valider']);
        Mail::to($user->email)->send(new UserValidatedMail($user));

        return redirect()->back()->with('success', 'Utilisateur validé avec succès.');
    }


    public function blockUser(Request $request, $id)
    {
        // Validation du motif
        $request->validate([
            'reason' => 'required|string|min:5',
        ]);

        $user = User::findOrFail($id);

        // Vérifier si le compte est déjà validé
        if ($user->etatV !== 'valider') {
            return back()->withErrors(['error' => 'Impossible de bloquer un compte non validé.']);
        }

        // Mettre à jour l'état et le motif
        $user->etatV = 'bloqué';
        $user->block_reason = $request->reason; // Assure-toi que cette colonne existe dans la table users
        $user->save();

        // Envoyer le mail avec le motif
        Mail::to($user->email)->send(new UserBlockedMail($user));

        return back()->with('success', 'Utilisateur bloqué avec succès.');
    }
    //methode de debloquage des utilisateurs
    public function dblockUser($id)
    {
        $user = User::findOrFail($id);
        $user->etatV = 'valider';
        $user->save();
        return redirect()->back()->with('success', 'Utilisateur debloqué avec succès.');
    }
    //methode de suppression des utilisateurs
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }
    //methode de modification des utilisateurs
    public function updateUser(Request $request, $id){
        $user = User::findOrFail($id);

        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'id_structure' => 'nullable|exists:structure,id',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'role' => 'required|string|in:user,moderateur,admin'
        ]);

        // Mise à jour des informations de l'utilisateur
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'prenom' => $request->prenom,
            'id_structure' => $request->id_structure,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'role' => $request->role
        ]);

        return redirect()->back()->with('success', 'Utilisateur mis à jour avec succès.');  
    }
}
