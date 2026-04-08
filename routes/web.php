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
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');
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
    //route pours supprimer un utilisateur
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    //route pour filtrer les utilisateurs par structure
    
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

// Routes pour la gestion des catégories du forum

Route::middleware(['auth'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});
// Route pour réagir à un fil de discussion
//Route::post('/forum/{thread}/react', [ThreadController::class, 'react'])->name('forum.react');

// Routes pour la gestion des structures
Route::middleware('auth')->group(function () {
    Route::get('/structures', [StructureController::class, 'index'])->name('structures.index');
    Route::get('/structures/create', [StructureController::class, 'create'])->name('structures.create');
    Route::post('/structures', [StructureController::class, 'store'])->name('structures.store');
    Route::get('/structures/{structure}/edit', [StructureController::class, 'edit'])->name('structures.edit');
    Route::put('/structures/{structure}', [StructureController::class, 'update'])->name('structures.update');
    Route::delete('/structures/{structure}', [StructureController::class, 'destroy'])->name('structures.destroy');
    Route::get('/structures/map', [StructureController::class, 'map'])->name('structures.map');
    // Route pour afficher le details d'une structure dans la carte
    Route::get('/structures/{structure}/details', [StructureController::class, 'details'])->name('annuaire.details');
});

// Routes pour l'annuaire
Route::middleware('auth')->group(function () {
     
        Route::get('/annuaire', [AnnuaireController::class, 'index'])->name('annuaire.index');
        // route pour afficher les membres d'une structure spécifique
        Route::get('/annuaire/structure', [AnnuaireController::class, 'showByStructure'])->name('annuaire.membre');
        Route::get('/annuaire/export/csv', [AnnuaireController::class, 'exportCsv'])->name('annuaire.export.csv');
        Route::get('/annuaire/export/pdf', [AnnuaireController::class, 'exportPdf'])->name('annuaire.export.pdf');
    });

// Routes pour les logs d'activité

Route::middleware(['auth'])->prefix('activity-logs')->name('activity_logs.')->group(function () {
    Route::get('/', [ActivityLogController::class, 'index'])->name('index');
    Route::get('/stats', [ActivityLogController::class, 'stats'])->name('stats');
    Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
    Route::delete('/bulk-destroy', [ActivityLogController::class, 'bulkDestroy'])->name('bulkDestroy');
    Route::delete('/destroy-all', [ActivityLogController::class, 'destroyAll'])->name('destroyAll');
    Route::get('/{id}', [ActivityLogController::class, 'show'])->name('show');
    Route::delete('/{id}', [ActivityLogController::class, 'destroy'])->name('destroy');
});


// Routes pour la gestion des ressources

Route::middleware(['auth'])->group(function () {
    
    // ===== ROUTES RESSOURCES =====
    // Liste des ressources (page principale)
    Route::get('/ressources', [ResourceController::class, 'index'])->name('resources.index');
    
    // Formulaire de création
    Route::get('/ressources/creer', [ResourceController::class, 'create'])->name('resources.create');
    
    // Enregistrement d'une nouvelle ressource
    Route::post('/ressources', [ResourceController::class, 'store'])->name('resources.store');
    
    // Formulaire d'édition
    Route::get('/ressources/{resource}/modifier', [ResourceController::class, 'edit'])->name('resources.edit');
    
    // Mise à jour
    Route::put('/ressources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
    
    // Suppression
    Route::delete('/ressources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');
    
    // Téléchargement
    Route::get('/ressources/{resource}/telecharger', [ResourceController::class, 'download'])->name('resources.download');
    // Route pour la mise à jour en masse (ex: via AJAX)
    Route::post('/resources/batch-update', [ResourceController::class, 'batchUpdate'])->name('resources.batch-update');
    Route::get('/ressources/{resource}/edit', [ResourceController::class, 'edit'])->name('resources.edit');
});

// Route de test pour vérifier (à supprimer après test)
Route::get('/test-routes', function() {
    return [
        'resources.index' => route('resources.index', [], false),
        'resources.create' => route('resources.create', [], false),
        'resources.store' => route('resources.store', [], false),
    ];
});



//route pour events
Route::middleware(['auth'])->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    
    // ROUTE SPÉCIFIQUE - À PLACER AVANT LES ROUTES AVEC PARAMÈTRES
    Route::get('/events/calendrier', [EventController::class, 'calendrier'])->name('events.calendrier'); 
    
    // Routes avec paramètre {event} - À PLACER APRÈS
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    // Routes d'inscription (avec paramètre aussi)
    Route::post('/events/{event}/inscrire', [EventController::class, 'inscrire'])->name('events.inscrire'); 
    Route::post('/events/{event}/desinscrire', [EventController::class, 'desinscrire'])->name('events.desinscrire');
    Route::delete('/events/{event}/desinscrire', [EventController::class, 'desinscrire'])->name('events.desinscrire');
});


// Routes pour le formulaire d'inscription avec génération de PDF

Route::get('/formulaire/inscription', [StructureController::class, 'createPDF'])->name('auth.create');
Route::post('/formulaire/inscription/pdf', [StructureController::class, 'generatePDF'])->name('auth.pdf');










