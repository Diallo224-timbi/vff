<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Structures;

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user();
        $structures = Structures::all();
        return view('profile.show', compact('user', 'structures'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
      

        // Validation des données
        $request->validate([
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'id_structure' => 'nullable|exists:structure,id',
        ]);
        // Mise à jour des informations de l'utilisateur
        $user->update([
            
            'email' => $request->email,
            'phone' => $request->phone,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'id_structure' => $request->id_structure,
        ]);
        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès.');
    }   
}
