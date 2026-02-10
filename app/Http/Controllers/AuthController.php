<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\welcomEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;
use Illuminate\Validation\ValidationException;
use App\Models\Structures;



class AuthController extends Controller
{
    // fonction pour renvoyer l'utilisateur vers la page de connexion
    public function showSignUp(){
        //Vérifier si l'utilisateur est déjà authentifié
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        //si non, afficher la vue de connexion
        return view('auth.register');
    }
    // fonction pour renvoyer l'utilisateur vers la page d'inscription
    public function showFormLogin(){
        // verifier si l'utilisateur est déjà authentifié
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        //si non, afficher la vue d'inscription
        return view('auth.login');
    }
    // fonction pour gérer la soumission du formulaire d'inscription
    public function login(Request $request)
    {
        // 1️⃣ Validation du formulaire
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2️⃣ Récupérer l'utilisateur par email
        $user = User::where('email', $request->email)->first();

        // 3️⃣ Vérifier si l'utilisateur existe
        if (!$user) {
            return back()
                ->withErrors(['email' => 'Identifiants invalides'])
                ->withInput();
    }

    // 4️⃣ Vérifier si le compte est validé
    if (!$user->isValidated()) {
        return back()
            ->withErrors(['email' => 'Votre compte n’est pas encore validé.'])
            ->withInput();
    }

    // 5️⃣ Tentative de connexion
    if (Auth::attempt($request->only('email', 'password'))) {
        return redirect()->route('dashboard');
    }

    // 6️⃣ Si le mot de passe est incorrect
        return back()
            ->withErrors(['email' => 'Identifiants invalides'])
            ->withInput();
    }
    // fonction pour gérer la soumission du formulaire d'inscription

    public function showRegistrationForm()
    {
        $Structures = Structures::orderBy('organisme')->get();
        return view('auth.register', compact('Structures'));
    }
    public function signUp(Request $request){
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'id_Structures' => 'nullable|exists:Structures,id',
            'chart' => 'required|boolean',
        ],[
            'confirmEmail.same' => 'L\'adresse e-mail de confirmation ne correspond pas.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'chart' => 'Vous devez accepter la charte pour vous inscrire.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'id_Structures' => $request->id_Structures,
            'chart' => $request->chart,
        ]);

        // Nettoyer les sessions liées à la vérification email
        session()->forget(['email_verification_code', 'email_to_verify', 'email_sent', 'code_verified']);   
        Mail::to($user->email)->send(new welcomEmail($user));

        return back()->with('success', 'Inscription réussie. Vérifiez votre email pour confirmer votre compte.');

    } catch (ValidationException $e) {
        // Redirige avec les erreurs de validation
        return back()->withErrors($e->errors());
    } catch (\Exception $e) {
        return back()->withErrors(['success' => 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.']);
    }
}
   
    // fonction pour déconnecter l'utilisateur
    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }

    // fonction pour envoyer le code de vérification par email
    public function sendVerificationCode(Request $request)
{
    $request->validate([
        'email' => 'required|email|unique:users,email',
    ]);

    $code = rand(100000, 999999);
    session(['email_verification_code' => $code, 'email_to_verify' => $request->email, 'email_sent' => true]);

    Mail::to($request->email)->send(new EmailVerificationMail($code));

    return redirect()->back()->with('success', 'Code envoyé par email !');
}

   
    // fonction pour vérifier le code de vérification
    public function verifyCode(Request $request)
{
    $request->validate([
        'code' => 'required|numeric',
    ]);

    $codeSession = session('email_verification_code');

    if ($request->code == $codeSession) {
        // Code correct → étape 3
        session(['code_verified' => true]);
        return redirect()->back()->with('success', 'Email validé ✅');
    } else {
        // Code incorrect → rester à l'étape 2
        return redirect()->back()->with('code_error', 'Code incorrect ❌');
    }
}


}
