@extends('base')
@section('title', 'Ajouter un organisme')
@section('content')

<div class="container form-container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Carte principale -->
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                
                <!-- En-tête sobre -->
                <div class="card-header text-white py-3 px-4" style="background: #255156; border: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="h5 mb-0 fw-bold">
                                <i class="fas fa-building me-2"></i>Ajouter un Organisme
                            </h1>
                        </div>
                        <i class="fas fa-hand-holding-heart fa-lg opacity-50"></i>
                    </div>
                </div>

                <!-- Corps du formulaire compact -->
                <div class="card-body form-body">
                    <form action="{{ route('organismes.store') }}" method="POST" id="organismeForm" enctype="multipart/form-data">
                        @csrf
                    
                        <!-- Ligne 1 : Logo + Nom -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-5">
                                <label for="logo" class="form-label fw-semibold mb-1">
                                    <i class="fas fa-image me-1" style="color: #255156;"></i>Logo
                                </label>
                                <input type="file" class="form-control form-control-sm" id="logo" name="logo" accept="image/*">
                            </div>
                            <div class="col-md-7">
                                <label for="nom" class="form-label fw-semibold mb-1">
                                    <i class="fas fa-tag me-1" style="color: #255156;"></i>Nom de l'organisme *
                                </label>
                                <input type="text" class="form-control form-control-sm" id="nom" name="nom_organisme" 
                                       placeholder="Ex: Association Culturelle" required>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="signification" class="form-label fw-semibold mb-1">
                                <i class="fas fa-align-left me-1" style="color: #255156;"></i>Description *
                            </label>
                            <textarea class="form-control form-control-sm" id="signification" name="signification" rows="3" 
                                      placeholder="Décrivez la mission et les activités de l'organisme..." required></textarea>
                        </div>

                        <!-- Adresse -->
                        <div class="mb-3">
                            <label for="adresse" class="form-label fw-semibold mb-1">
                                <i class="fas fa-map-marker-alt me-1" style="color: #255156;"></i>Adresse *
                            </label>
                            <input type="text" class="form-control form-control-sm" id="adresse" name="adresse" 
                                   placeholder="Numéro et nom de rue" required>
                        </div>

                        <!-- Ligne : Code postal + Ville + Site web -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label for="code_postal" class="form-label fw-semibold mb-1">
                                    <i class="fas fa-hashtag me-1" style="color: #255156;"></i>Code postal *
                                </label>
                                <input type="text" class="form-control form-control-sm" id="code_postal" name="code_postal" 
                                       placeholder="06000" maxlength="5" required>
                            </div>
                            <div class="col-md-4">
                                <label for="ville" class="form-label fw-semibold mb-1">
                                    <i class="fas fa-city me-1" style="color: #255156;"></i>Ville *
                                </label>
                                <input type="text" class="form-control form-control-sm" id="ville" name="ville" 
                                       placeholder="Nice" required>
                            </div>
                            <div class="col-md-5">
                                <label for="site_web" class="form-label fw-semibold mb-1">
                                    <i class="fas fa-globe me-1" style="color: #255156;"></i>Site web
                                </label>
                                <input type="url" class="form-control form-control-sm" id="site_web" name="site_web" 
                                       placeholder="https://exemple.org">
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('organismes.index') }}" class="btn btn-outline-secondary btn-sm px-4">
                                <i class="fas fa-times me-1"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-sm px-4 text-white" id="submitBtn" 
                                    style="background: #255156; border: none;">
                                <i class="fas fa-save me-1"></i>Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles CSS -->
<style>
    /* ============================================
       BASE
       ============================================ */
    .form-container {
        min-height: calc(100vh - 70px);
        display: flex;
        align-items: center;
    }
    .form-container .row {
        width: 100%;
    }

    /* Taille de base des écritures (légèrement augmentée) */
    .form-label {
        font-size: 0.95rem;
        color: #333;
    }
    .form-control-sm {
        font-size: 0.92rem;
    }
    .form-control::placeholder {
        font-size: 0.9rem;
        color: #adb5bd;
    }

    /* Focus */
    .form-control:focus {
        border-color: #255156;
        box-shadow: 0 0 0 0.15rem rgba(37, 81, 86, 0.15);
    }

    /* Hover bouton principal */
    #submitBtn:hover {
        background: #1a3a3f !important;
        transition: background 0.2s ease;
    }

    /* ============================================
       OPTIMISATION RÉSOLUTION 1920x1080 @ 125%
       Viewport CSS cible : ~1536 x 864
       ============================================ */
    @media screen and (min-width: 1400px) and (max-width: 1600px)
       and (min-height: 800px) and (max-height: 900px) {

        /* Container centré verticalement */
        .form-container {
            min-height: calc(100vh - 60px) !important;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            margin-top: 0 !important;
        }

        /* Carte : largeur optimale */
        .form-container .col-lg-8.col-xl-7 {
            max-width: 760px;
            flex: 0 0 auto;
        }

        /* En-tête : lisible mais compact */
        .card-header {
            padding: 0.7rem 1rem !important;
        }
        .card-header h1.h5 {
            font-size: 1.1rem !important;      /* AVANT: 0.92rem */
            margin: 0 !important;
        }
        .card-header .fa-lg {
            font-size: 1.2em !important;
        }

        /* Corps : padding confortable */
        .form-body {
            padding: 1.1rem 1.25rem !important; /* AVANT: 0.85rem */
        }

        /* Labels : taille augmentée */
        .form-label {
            font-size: 0.92rem !important;      /* AVANT: 0.75rem */
            margin-bottom: 0.2rem !important;
        }

        /* Champs : taille augmentée */
        .form-control-sm {
            padding: 0.4rem 0.7rem !important;  /* AVANT: 0.28rem */
            font-size: 0.9rem !important;       /* AVANT: 0.8rem */
            height: calc(1.6em + 0.8rem) !important;
        }

        /* Placeholder : taille augmentée */
        .form-control::placeholder {
            font-size: 0.88rem !important;
        }

        /* Espacements verticaux : légèrement augmentés mais contenus */
        .mb-2 { margin-bottom: 0.55rem !important; }
        .mb-3 { margin-bottom: 0.75rem !important; }   /* AVANT: 0.45-0.6rem */
        .mb-4 { margin-bottom: 0.9rem !important; }
        .row.g-3 { --bs-gutter-x: 0.7rem; --bs-gutter-y: 0.7rem; }

        /* Textarea : plus confortable */
        textarea.form-control-sm {
            min-height: 70px !important;               /* AVANT: 50px */
            resize: vertical;
        }

        /* Boutons : taille augmentée */
        .btn-sm {
            padding: 0.42rem 1rem !important;          /* AVANT: 0.3rem */
            font-size: 0.88rem !important;             /* AVANT: 0.78rem */
        }

        /* Séparateur boutons */
        .border-top.pt-3 {
            padding-top: 0.65rem !important;
            margin-top: 0.25rem !important;
        }

        /* Icônes inline dans les labels */
        .form-label i {
            font-size: 0.85rem;
        }
    }
</style>

<!-- Script pour la validation en temps réel -->
<script>
    document.getElementById('code_postal').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
    });
    
    document.getElementById('site_web').addEventListener('input', function() {
        const url = this.value;
        if (url && !url.match(/^https?:\/\/.+/)) {
            this.setCustomValidity('Veuillez saisir une URL valide commençant par http:// ou https://');
        } else {
            this.setCustomValidity('');
        }
    });
    
    document.getElementById('organismeForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Ajout...';
        btn.disabled = true;
    });
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection