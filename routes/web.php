<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;




Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Routes pour l'authentification
Route::get('/register',[AuthController::class, 'showSignUp'])->name('register');
// 
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
// afficher le formulaire d'inscription
Route::post('/register',[AuthController::class, 'signUp'])->name('registration.register');
// gérer la soumission du formulaire d'inscription
Route::get('/login',[AuthController::class, 'showFormLogin'])->name('login');
// afficher le formulaire de connexion
Route::post('/login',[AuthController::class, 'login'])->name('login.submit');
// gérer la soumission du formulaire de connexion
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// Password Reset
Route::get('forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');

Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

// Route pour envoyer le code de vérification
Route::post('/send-verification-code', [AuthController::class, 'sendVerificationCode'])
     ->name('sendVerificationCode');

// Route pour vérifier le code de vérification
Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('verifyCode');


// Route pour le tableau de bord admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    //Route::post('/admin/users/{id}/validate', [AdminController::class, 'validatedUser'])->name('admin.users.validate');
    //Route::post('/admin/users/{id}/block', [AdminController::class, 'blockUser'])->name('admin.users.block');
});

// Aroute pour le tableau de bord admin
Route::get('/admin/users', [AdminController::class, 'indexx'])->name('admin.users');
Route::post('/admin/users/{id}/validate', [AdminController::class, 'validatedUser'])->name('admin.users.validate');
Route::post('/admin/users/{id}/block', [AdminController::class, 'blockUser'])->name('admin.users.block');

// Route pour le profil utilisateur
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// route pour la charte

Route::get('/charte', function () {
    return view('auth.charte');
})->name('charte');





