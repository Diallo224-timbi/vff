@extends('base')
@section('title', 'Modifier un organisme')
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
                                <i class="fas fa-edit me-2"></i>Modifier l'Organisme
                            </h1>
                            <small class="opacity-75">{{ $organisme->nom_organisme }}</small>
                        </div>
                        <i class="fas fa-building fa-lg opacity-50"></i>
                    </div>
                </div>
                <!-- Corps du formulaire compact -->
                <div class="card-body form-body">
                    <form action="{{ route('organismes.update', $organisme->id) }}" method="POST" id="organismeForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')   
                        <!-- Ligne 1 : Logo actuel + Upload + Nom -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="fas fa-image me-1" style="color: #255156;"></i>Logo actuel
                                </label>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    @if($organisme->logo_path && file_exists(storage_path('app/public/' . $organisme->logo_path)))
                                        <div class="logo-container rounded-xl d-flex align-items-center justify-content-center overflow-hidden cursor-pointer"
                                            style="width: 48px; height: 48px; background: linear-gradient(135deg, #f8fbfc, #e3e7e9);"
                                            onclick="openLogoZoom('{{ asset('storage/' . $organisme->logo_path) }}', '{{ $organisme->nom_organisme }}')">
                                            <img src="{{ asset('storage/' . $organisme->logo_path) }}"
                                                alt="Logo de {{ $organisme->nom_organisme }}"
                                                style="width: 100%; height: 100%; object-fit: contain; padding: 3px;">
                                        </div>
                                        <small class="text-muted" style="font-size: 0.75rem; color: #7fa8ac !important;">
                                            <i class="fas fa-mouse-pointer me-1"></i>Agrandir
                                        </small>
                                    @else
                                        <span class="text-muted" style="font-size: 0.85rem; color: #7fa8ac !important;">Aucun logo</span>
                                    @endif
                                </div>
                                <!-- INPUT FICHIER PLEINE LARGEUR -->
                                <div class="w-100">
                                    <input type="file" class="form-control form-control-sm w-100" id="logo" name="logo" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label for="nom" class="form-label fw-semibold mb-1">
                                    <i class="fas fa-tag me-1" style="color: #255156;"></i>Nom de l'organisme <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="nom" name="nom_organisme" 
                                    value="{{ $organisme->nom_organisme }}" placeholder="Ex: Association Culturelle" required>
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="mb-3">
                            <label for="signification" class="form-label fw-semibold mb-1">
                                <i class="fas fa-align-left me-1" style="color: #255156;"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control form-control-sm" id="signification" name="signification" rows="3" 
                                    placeholder="Décrivez la mission et les activités de l'organisme..." required>{{ $organisme->signification }}</textarea>
                        </div>
                        <!-- Adresse + Code postal sur une seule ligne -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label for="adresse" class="form-label fw-semibold mb-1">
                                    <i class="fas fa-map-marker-alt me-1" style="color: #255156;"></i>Adresse <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="adresse" name="adresse" 
                                    value="{{ $organisme->adresse }}" placeholder="Numéro et nom de rue" required>
                            </div>
                            <div class="col-md-4">
                                <label for="code_postal" class="form-label fw-semibold mb-1">
                                    <i class="fas fa-hashtag me-1" style="color: #255156;"></i>Code postal <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="code_postal" name="code_postal" 
                                    value="{{ $organisme->code_postal }}" placeholder="06000" maxlength="5" required>
                            </div>
                        </div>

                        <!-- Ligne : Ville + Site web -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-5">
                                <label for="ville" class="form-label fw-semibold mb-1">
                                    <i class="fas fa-city me-1" style="color: #255156;"></i>Ville <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="ville" name="ville" 
                                    value="{{ $organisme->ville }}" placeholder="Nice" required>
                            </div>
                            <div class="col-md-7">
                                <label for="site_web" class="form-label fw-semibold mb-1">
                                    <i class="fas fa-globe me-1" style="color: #255156;"></i>Site web
                                </label>
                                <input type="url" class="form-control form-control-sm" id="site_web" name="site_web" 
                                    value="{{ $organisme->site_web }}" placeholder="https://exemple.org">
                            </div>
                        </div>
                        <!-- Boutons d'action -->
                        <div class="d-flex gap-1 justify-content-end pt-1 border-top">
                            <small class="text-muted" style="font-size: 0.6rem;">
                                Les champs marqués d'un <span class="text-danger">*</span> sont obligatoires
                            </small>
                            <a href="{{ route('organismes.index') }}" class="btn btn-outline-secondary btn-sm px-4">
                                <i class="fas fa-times me-1"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-sm px-4 text-white" id="submitBtn" 
                                    style="background: #255156; border: none;">
                                <i class="fas fa-save me-1"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ZOOM LOGO -->
<div class="modal fade" id="logoZoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 1rem;">
            <div class="modal-body p-4 text-center">
                <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="modal"></button>
                <div class="mt-2">
                    <img id="logoZoomImage" src="" alt="Logo agrandi"
                        style="max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 0.5rem;">
                    <p id="logoZoomTitle" class="text-white mt-3 font-semibold"></p>
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
        min-height: auto;
    }
    .form-container .row {
        width: 100%;
    }

    /* Taille de base des écritures */
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

    /* Logo zoom */
    .logo-container {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .logo-container:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 16px rgba(37,81,86,0.3);
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #e8f3f2; border-radius: 10px; }
    ::-webkit-scrollbar-thumb { background: #4a8599; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #255156; }

    /* ============================================
       CORRECTION POUR L'INPUT FICHIER
       ============================================ */
    input[type="file"].form-control {
        display: block;
        width: 100%;
    }
    input[type="file"]::file-selector-button {
        padding: 0.375rem 0.75rem;
        margin-right: 0.5rem;
        border: none;
        border-radius: 4px;
        background-color: #f0f6f5;
        color: #255156;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    input[type="file"]::file-selector-button:hover {
        background-color: #dceeec;
    }

    /* ============================================
       OPTIMISATION RÉSOLUTION 1920x1080 @ 125%
       ============================================ */
    @media screen and (min-width: 1400px) and (max-width: 1600px)
       and (min-height: 800px) and (max-height: 900px) {

        .form-container {
            min-height: auto !important;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            margin-top: 0 !important;
        }

        .form-container .col-lg-8.col-xl-7 {
            max-width: 760px;
            flex: 0 0 auto;
        }

        .card-header {
            padding: 0.7rem 1rem !important;
        }
        .card-header h1.h5 {
            font-size: 1.1rem !important;
            margin: 0 !important;
        }
        .card-header .fa-lg {
            font-size: 1.2em !important;
        }

        .form-body {
            padding: 1.1rem 1.25rem !important;
        }

        .form-label {
            font-size: 0.92rem !important;
            margin-bottom: 0.2rem !important;
        }

        .form-control-sm {
            padding: 0.4rem 0.7rem !important;
            font-size: 0.9rem !important;
            height: calc(1.6em + 0.8rem) !important;
        }

        .form-control::placeholder {
            font-size: 0.88rem !important;
        }

        .mb-2 { margin-bottom: 0.55rem !important; }
        .mb-3 { margin-bottom: 0.75rem !important; }
        .mb-4 { margin-bottom: 0.9rem !important; }
        .row.g-3 { --bs-gutter-x: 0.7rem; --bs-gutter-y: 0.7rem; }

        textarea.form-control-sm {
            min-height: 70px !important;
            resize: vertical;
        }

        .btn-sm {
            padding: 0.42rem 1rem !important;
            font-size: 0.88rem !important;
        }

        .border-top.pt-3 {
            padding-top: 0.65rem !important;
            margin-top: 0.25rem !important;
        }

        .form-label i {
            font-size: 0.85rem;
        }
    }
</style>

<!-- Script pour la validation en temps réel -->
<script>
    function openLogoZoom(imageUrl, title) {
        const modal = new bootstrap.Modal(document.getElementById('logoZoomModal'));
        const img = document.getElementById('logoZoomImage');
        const titleEl = document.getElementById('logoZoomTitle');
        if (imageUrl && imageUrl !== '') {
            img.src = imageUrl;
            img.style.display = 'block';
            titleEl.textContent = title || 'Logo';
        } else {
            img.style.display = 'none';
            titleEl.textContent = 'Aucun logo disponible';
        }
        modal.show();
    }

    const codePostalInput = document.getElementById('code_postal');
    if (codePostalInput) {
        codePostalInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
        });
    }
    
    const siteWebInput = document.getElementById('site_web');
    if (siteWebInput) {
        siteWebInput.addEventListener('input', function() {
            const url = this.value;
            if (url && !url.match(/^https?:\/\/.+/)) {
                this.setCustomValidity('Veuillez saisir une URL valide commençant par http:// ou https://');
            } else {
                this.setCustomValidity('');
            }
        });
    }
    
    const form = document.getElementById('organismeForm');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enregistrement...';
            btn.disabled = true;
        });
    }
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection