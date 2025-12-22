<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\UserValidatedMail;
use Illuminate\Support\Facades\Mail;



class AdminController extends Controller
{
   public function __construct()
    {
        //$this->middleware('admin');
    }   
    public function indexx()
    {
        $users=User::where('role','user')->get();
        return view('admin.users', compact('users'));
    }
    public function index()
    {
        $users = User::all();
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


    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->etatV = 'bloquer';
        $user->save();

        return redirect()->back()->with('success', 'Utilisateur bloqué avec succès.');
    }
    public function dblockUser($id)
    {
        $user = User::findOrFail($id);
        $user->etatV = 'valider';
        $user->save();
        return redirect()->back()->with('success', 'Utilisateur debloqué avec succès.');
    }
}
