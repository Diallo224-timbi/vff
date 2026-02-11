<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\AnnuaireController;



Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Routes pour l'authentification pour l'inscription et la connexion
Route::get('/register',[AuthController::class, 'showSignUp'])->name('register');
// route pour afficher le formulaire d'inscription
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
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'indexx'])->name('admin.users');
    Route::post('/admin/users/{id}/validate', [AdminController::class, 'validatedUser'])->name('admin.users.validate');
    Route::post('/admin/users/{id}/block', [AdminController::class, 'blockUser'])->name('admin.users.block');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
});

// Route pour filtrer les utilisateurs par structure
Route::middleware(['auth'])->group(function () {
    Route::get('/annuaire/list', [AnnuaireController::class, 'listeGroupee'])
        ->name('annuaire.list');
    
    // Autres routes qui nécessitent une connexion...
});

/* route pour le tableau de bord admin
Route::get('/admin/users', [AdminController::class, 'indexx'])->name('admin.users');
Route::post('/admin/users/{id}/validate', [AdminController::class, 'validatedUser'])->name('admin.users.validate');
Route::post('/admin/users/{id}/block', [AdminController::class, 'blockUser'])->name('admin.users.block');
*/
// route pour modifier les utilisateurs


// Route pour le profil utilisateur
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// route pour la charte

Route::get('/charte', function () {
    return view('auth.charte');
})->name('charte');

// Routes pour le forum et les commentaires

Route::middleware(['auth'])->group(function () {
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/{thread}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::post('/forum/{thread}/comment', [CommentController::class, 'store'])->name('comment.store');

    Route::prefix('forum')->name('forum.')->group(function () {
        Route::get('/{thread}/edit', [ForumController::class, 'edit'])->name('edit');      // <--- EDIT
        Route::put('/{thread}', [ForumController::class, 'update'])->name('update');       // <--- UPDATE
        Route::delete('/{thread}', [ForumController::class, 'destroy'])->name('destroy');  // <--- DELETE
        Route::post('/{thread}/react', [ForumController::class, 'react'])->name('react');
    });

    //commentaire routes
    Route::prefix('comment')->name('comment.')->group(function () {
        Route::get('/{comment}/edit', [CommentController::class, 'edit'])->name('edit');         // <--- EDIT
        Route::put('/{comment}', [CommentController::class, 'update'])->name('update');        // <--- UPDATE
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');   // <--- DELETE
        Route::post('/{comment}/react', [CommentController::class, 'react'])->name('react');
    }); 
});


// Routes pour la gestion des catégories

Route::middleware(['auth'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});
// Route pour réagir à un fil de discussion
Route::post('/forum/{thread}/react', [ThreadController::class, 'react'])->name('forum.react');

// Routes pour la gestion des structures
Route::middleware('auth')->group(function () {
    Route::get('/structures', [StructureController::class, 'index'])->name('structures.index');
    Route::get('/structures/create', [StructureController::class, 'create'])->name('structures.create');
    Route::post('/structures', [StructureController::class, 'store'])->name('structures.store');
    Route::get('/structures/{structure}/edit', [StructureController::class, 'edit'])->name('structures.edit');
    Route::put('/structures/{structure}', [StructureController::class, 'update'])->name('structures.update');
    Route::delete('/structures/{structure}', [StructureController::class, 'destroy'])->name('structures.destroy');
    Route::get('/structures/map', [StructureController::class, 'map'])->name('structures.map');

});

// Routes pour l'annuaire
Route::middleware('auth')->group(function () {
     
        Route::get('/annuaire', [AnnuaireController::class, 'index'])->name('annuaire.index');
        Route::get('/annuaire/export/csv', [AnnuaireController::class, 'exportCsv'])->name('annuaire.export.csv');
        Route::get('/annuaire/export/pdf', [AnnuaireController::class, 'exportPdf'])->name('annuaire.export.pdf');
    });

Route::get('/formulaire/inscription', [StructureController::class, 'createPDF'])->name('auth.create');
Route::post('/formulaire/inscription/pdf', [StructureController::class, 'generatePDF'])->name('auth.pdf');










