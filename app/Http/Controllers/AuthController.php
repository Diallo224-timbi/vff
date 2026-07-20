<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Mail\OrganismeNewUserEmail;
use App\Mail\StructureNewUserEmail;
use App\Models\Organisme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\welcomEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;
use Illuminate\Validation\ValidationException;
use App\Models\Structures;
use App\Models\ActivityLog;
use App\Mail\AdminNewUserEmail;


class AuthController extends Controller
{
    // fonction pour renvoyer l'utilisateur vers la page de connexion
    public function showSignUp(){
        //Vérifier si l'utilisateur est déjà authentifié
        if(Auth::check()){
            if(auth()->user()->role === 'admin'){
                return redirect()->route('dashboard');
            }
            else 
            return redirect()->route('dashboardUser');
        }
        //si non, afficher la vue de connexion
        return view('auth.register');
    }
    // fonction pour renvoyer l'utilisateur vers la page d'inscription
    public function showFormLogin(){
        // verifier si l'utilisateur est déjà authentifié
        if(Auth::check()){
           if(auth()->user()->role === 'admin'){
                return redirect()->route('dashboard');
            }
            else 
            return redirect()->route('dashboardUser');
        }
        //si non, afficher la vue d'inscription
        return view('auth.login');
    }
    // fonction pour gérer la soumission du formulaire d'inscription
    public function login(Request $request)
    {
    //  Validation du formulaire
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        //  Récupérer l'utilisateur par email
        $user = User::where('email', $request->email)->first();
        //  Vérifier si l'utilisateur existe
        if (!$user) {
            return back()
                ->withErrors(['email' => 'Identifiants invalides'])
                ->withInput();
        }
        // Vérifier si le compte est validé
        if (!$user->isValidated()) {
            return back()
                ->withErrors(['email' => 'Votre compte n’est pas encore validé.'])
                ->withInput();
        }
        //  Tentative de connexion
        if (Auth::attempt($request->only('email', 'password'))) {
            // Utilisateur connecté avec succès
            $user = Auth::user(); // IMPORTANT : récupérer après Auth::attempt
            // Mettre à jour la date de dernière connexion
            $user->update(['last_login_at' => now()]);
            // Créer le log de connexion
            ActivityLog::log('Connexion', 'Utilisateur connecté: ' . $user->name, $user->id);
                return redirect()->route('dashboardUser');  
        }
        //  Mot de passe incorrect
        return back()
            ->withErrors(['email' => 'Identifiants invalides'])
            ->withInput();
    }
    // fonction pour gérer la soumission du formulaire d'inscription
   public function showRegistrationForm(Request $request)
    {
        $structures = Structures::all()->sortBy('id_organisme');
        // Récupérer tous les organismes distincts (structures parents)
        $organismes = Organisme::all()->sortBy('nom_organisme');
        return view('auth.register', compact('organismes', 'structures', ));
    }
    public function signUp(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:users,email',
                'confirmEmail' => 'required|same:email',
                'password' => 'required|string|min:6',
                'adresse' => 'required|string|max:255',
                'ville' => 'required|string|max:255',
                'code_postal' => 'required|string|max:10',
                'id_structure' => 'nullable|exists:structure,id',
                'chart' => 'required|boolean',
                'niveau' => 'required|integer|min:0',
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
                'confirmEmail' => $request->confirmEmail,
                'password' => Hash::make($request->password),
                'adresse' => $request->adresse,
                'ville' => $request->ville,
                'code_postal' => $request->code_postal,
                'id_structure' => $request->id_structure,
                'chart' => $request->chart,
                'niveau' => $request->niveau,
            ]);
            // Créer le log de l'inscription
            ActivityLog::logUserCreation($user);
            // Nettoyer les sessions liées à la vérification email
            session()->forget(['email_verification_code', 'email_to_verify', 'email_sent', 'code_verified']);   
            // Envoyer l'email de bienvenue
            Mail::to($user->email)->send(new welcomEmail($user));
           // Récupérer les administrateurs
            $admins = User::where('role', 'admin')->get();
            // Envoyer un email à chaque administrateur
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new AdminNewUserEmail($user));
            }
          // Récupérer la structure choisie
            $structure = Structures::find($user->id_structure);
            // Responsable de la structure
            $respStructure = User::where('id_structure', $user->id_structure)
                ->where('role', 'moderateur_classique')
                ->first();
            // Responsable(s) de l'organisme
            $respOrganismes = User::where('role', 'moderateur')
                ->whereHas('structure', function ($query) use ($structure) {
                    $query->where('id_organisme', $structure->id_organisme);
                })
                ->get();
            // Notification responsable structure
            if ($respStructure && $respStructure->email !== $user->email) {
                Mail::to($respStructure->email)
                    ->send(new StructureNewUserEmail($user));
            }
            // Notification responsables organisme
            foreach ($respOrganismes as $respOrganisme) {
                if ($respOrganisme->email !== $user->email) {
                    Mail::to($respOrganisme->email)
                        ->send(new OrganismeNewUserEmail($user));
                }
            }
            return back()->with('success', 'Inscription réussie. Vérifiez votre email.');
        } catch (ValidationException $e) {
            // Redirige avec les erreurs de validation
            return back()->withErrors($e->errors());
        } 
    }
   
    // fonction pour déconnecter l'utilisateur
    public function logout(){
        // Enregistrer le log de déconnexion avant de se déconnecter
         $user = Auth::user();
        ActivityLog::log('Déconnexion', 'Utilisateur déconnecté', $user->id);
        // Déconnecter l'utilisateur
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
            return redirect()->back()->with('success', 'Email validé ');
        } else {
            // Code incorrect → rester à l'étape 2
            return redirect()->back()->with('code_error', 'Code incorrect ');
        }
    }
}
