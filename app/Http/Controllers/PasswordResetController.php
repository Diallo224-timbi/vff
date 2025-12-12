<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    // Formulaire "mot de passe oublié"
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Envoi du lien
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                ? back()->with('success', 'Un lien de réinitialisation a été envoyé à votre email.')
                : back()->withErrors(['email' => 'Cet email est introuvable.']);
    }

    // Formulaire "nouveau mot de passe"
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Enregistrer le nouveau mot de passe
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('success', 'Mot de passe réinitialisé avec succès !')
                : back()->withErrors(['email' => 'Erreur. Le lien n’est plus valide.']);
    }
}
?>