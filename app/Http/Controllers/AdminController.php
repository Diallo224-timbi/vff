<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\UserValidatedMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserBlockedMail;
use App\Models\Structure;



class AdminController extends Controller
{
   public function __construct()
    {
        //$this->middleware('admin');
    }   
    public function indexx()
    {
        $users=User::where('role','user')->orderBy('created_at','desc')->get();
        $structures = Structure::orderBy('nom_structure')->get();
        return view('admin.users', compact('users', 'structures'));
    }
    public function index2()
    {
        $users = User::all();
        $structures = Structure::orderBy('nom_structure')->get();
       return view('admin.users',compact('users'));
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
    public function dblockUser($id)
    {
        $user = User::findOrFail($id);
        $user->etatV = 'valider';
        $user->save();
        return redirect()->back()->with('success', 'Utilisateur debloqué avec succès.');
    }
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
            'code_postal' => 'nullable|string|max:10'
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
            'code_postal' => $request->code_postal
        ]);

        return redirect()->back()->with('success', 'Utilisateur mis à jour avec succès.');  
    }
}
