<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organisme;
class OrganismeController extends Controller
{
    //fonction pour afficher la liste des organismes
    public function index()
    {
        $organismes = Organisme::all();
        return view('organismes.index', compact('organismes'));
    }
    //fonction pour afficher le formulaire de création d'un organisme
    public function create()
    {
        return view('organismes.create');
    }
    //fonction pour enregistrer un nouvel organisme
    public function store(Request $request)
    {
        $request->validate([
            'nom_organisme' => 'required',
            'signification' => 'required',
            'adresse' => 'required',
            'code_postal' => 'required',
            'ville' => 'required',
            'site_web' => 'required',
        ]);
        Organisme::create($request->all());
        return redirect()->route('organismes.index')->with('success', 'Organisme créé avec succès.');
    } 
    //fonction pour afficher le formulaire de modification d'un organisme
    public function edit($id)
    {
        $organisme = Organisme::find($id);
        return view('organismes.edit', compact('organisme'));
    }
    //fonction pour mettre à jour un organisme
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_organisme' => 'required',
            'signification' => 'required',
            'adresse' => 'required',
            'code_postal' => 'required',
            'ville' => 'required',
            'site_web' => 'required',
        ]);
        $organisme = Organisme::find($id);
        $organisme->update($request->all());
        return redirect()->route('organismes.index')->with('success', 'Organisme mis à jour avec succès.');
    } 
    //fonction pour supprimer un organisme
    public function destroy($id)
    {
        $organisme = Organisme::find($id);
        $organisme->delete();
        return redirect()->route('organismes.index')->with('success', 'Organisme supprimé avec succès.');
    }
    //fonction pour afficher les détails d'un organisme
    public function show($id)
    {
        $organisme = Organisme::find($id);
        return view('organismes.show', compact('organisme'));
    }
    //fonction pour afficher les structures d'un organisme
    public function structures($id)
    {
        $organisme = Organisme::find($id);
        $structures = $organisme->structures;
        return view('organismes.structures', compact('organisme', 'structures'));
    } 
}
