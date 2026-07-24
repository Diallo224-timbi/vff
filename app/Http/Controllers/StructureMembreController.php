<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Structures;
use App\Models\User;

class StructureMembreController extends Controller
{
      public function show($structureId)
    {
        $structure = Structures::findOrFail($structureId);
        $membres = User::where('id_structure', $structure->id)->get();
       // pagination
       $membreslink = User::where('id_structure', $structure->id)->paginate(10);
        return view('annuaire.membre_structure', compact('membres', 'structure', 'membreslink'));
    }
}
