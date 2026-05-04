@extends('base')
@section('title', 'Modifier un organisme')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Carte principale avec animation -->
            <div class="card border-0 shadow-lg animate__animated animate__fadeInUp" style="border-radius: 20px; overflow: hidden;">
                
                <!-- En-tête avec dégradé personnalisé -->
                <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #255156 0%, #255156 100%); border: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-edit fa-2x me-2"></i>
                            <h1 class="d-inline-block mb-0 fw-bold">Modifier l'Organisme</h1>
                            <p class="mt-2 mb-0 opacity-75">Modifiez les informations ci-dessous</p>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-building fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <!-- Corps du formulaire -->
                <div class="card-body p-5">
                    <form action="{{ route('organismes.update', $organisme->id) }}" method="POST" id="organismeForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Champ Nom avec icône -->
                        <div class="mb-4 form-group-animate">
                            <label for="nom" class="form-label fw-semibold mb-2">
                                <i class="fas fa-building me-2" style="color: #255156;"></i>Nom de l'organisme
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-tag text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" id="nom" name="nom_organisme" 
                                       value="{{ $organisme->nom_organisme }}" placeholder="Ex: Association Culturelle" required>
                            </div>
                            <div class="invalid-feedback">Veuillez saisir le nom de l'organisme</div>
                        </div>

                        <!-- Champ Description avec éditeur amélioré -->
                        <div class="mb-4 form-group-animate">
                            <label for="signification" class="form-label fw-semibold mb-2">
                                <i class="fas fa-align-left me-2" style="color: #255156;"></i>Description
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light align-items-start pt-3">
                                    <i class="fas fa-file-alt text-muted"></i>
                                </span>
                                <textarea class="form-control" id="signification" name="signification" rows="4" 
                                          placeholder="Décrivez la mission et les activités de l'organisme..." required>{{ $organisme->signification }}</textarea>
                            </div>
                        </div>

                        <!-- Adresse -->
                        <div class="mb-4 form-group-animate">
                            <label for="adresse" class="form-label fw-semibold mb-2">
                                <i class="fas fa-location-dot me-2" style="color: #255156;"></i>Adresse
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" id="adresse" name="adresse" 
                                       value="{{ $organisme->adresse }}" placeholder="Numéro et nom de rue" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Code Postal -->
                            <div class="col-md-4 mb-4 form-group-animate">
                                <label for="code_postal" class="form-label fw-semibold mb-2">
                                    <i class="fas fa-mail-bulk me-2" style="color: #255156;"></i>Code postal
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-hashtag text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="code_postal" name="code_postal" 
                                           value="{{ $organisme->code_postal }}" placeholder="75001" maxlength="5" required>
                                </div>
                            </div>

                            <!-- Ville -->
                            <div class="col-md-8 mb-4 form-group-animate">
                                <label for="ville" class="form-label fw-semibold mb-2">
                                    <i class="fas fa-city me-2" style="color: #255156;"></i>Ville
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-map-pin text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="ville" name="ville" 
                                           value="{{ $organisme->ville }}" placeholder="Paris" required>
                                </div>
                            </div>
                        </div>

                        <!-- Site Web -->
                        <div class="mb-4 form-group-animate">
                            <label for="site_web" class="form-label fw-semibold mb-2">
                                <i class="fas fa-globe me-2" style="color: #255156;"></i>Site web
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-link text-muted"></i>
                                </span>
                                <input type="url" class="form-control border-start-0 ps-0" id="site_web" name="site_web" 
                                       value="{{ $organisme->site_web }}" placeholder="https://exemple.org">
                            </div>
                            <small class="text-muted mt-1"><i class="fas fa-info-circle"></i> Optionnel - Format URL valide recommandé</small>
                        </div>

                        <!-- Séparateur -->
                        <hr class="my-4" style="border-top: 2px dashed #e0e0e0;">

                        <!-- Boutons d'action -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('organismes.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn" style="background: linear-gradient(135deg, #255156 0%, #255156 100%); border: none;">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles CSS supplémentaires pour les animations -->
<style>
    /* Animation d'apparition des champs */
    .form-group-animate {
        opacity: 0;
        transform: translateY(20px);
        animation: slideInUp 0.5s forwards;
    }
    
    @keyframes slideInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Délais d'animation pour chaque champ */
    .form-group-animate:nth-child(1) { animation-delay: 0.1s; }
    .form-group-animate:nth-child(2) { animation-delay: 0.2s; }
    .form-group-animate:nth-child(3) { animation-delay: 0.3s; }
    .form-group-animate:nth-child(4) { animation-delay: 0.4s; }
    .form-group-animate:nth-child(5) { animation-delay: 0.5s; }
    .form-group-animate:nth-child(6) { animation-delay: 0.6s; }
    
    /* Effet focus sur les inputs */
    .form-control:focus {
        border-color: #255156;
        box-shadow: 0 0 0 0.2rem rgba(37, 81, 86, 0.25);
    }
    
    /* Effet hover sur le bouton */
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(37, 81, 86, 0.4);
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #1d3f43 0%, #1d3f43 100%) !important;
    }
    
    /* Animation du curseur dans les inputs */
    .form-control {
        transition: all 0.3s ease;
    }
    
    /* Style des input group au focus */
    .input-group:focus-within .input-group-text {
        border-color: #255156;
        background-color: #f8f9fa;
    }
    
    /* Card hover effect */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15) !important;
    }
    
    /* Animation du bouton au clic */
    .btn-primary:active {
        transform: translateY(0px) !important;
    }
    
    /* Style pour les champs pré-remplis */
    .form-control {
        background-color: #fff;
    }
    
    /* Badge d'information modification */
    .info-badge {
        background: linear-gradient(135deg, #255156 0%, #1d3f43 100%);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: white;
        display: inline-block;
        margin-bottom: 15px;
    }
</style>

<!-- Script pour la validation en temps réel -->
<script>
    // Validation du code postal (uniquement chiffres, 5 caractères)
    const codePostalInput = document.getElementById('code_postal');
    if(codePostalInput) {
        codePostalInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
        });
    }
    
    // Validation URL en temps réel
    const siteWebInput = document.getElementById('site_web');
    if(siteWebInput) {
        siteWebInput.addEventListener('input', function(e) {
            const url = this.value;
            if(url && !url.match(/^https?:\/\/.+/)) {
                this.setCustomValidity('Veuillez saisir une URL valide commençant par http:// ou https://');
            } else {
                this.setCustomValidity('');
            }
        });
    }
    
    // Animation au submit
    const form = document.getElementById('organismeForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if(form && submitBtn) {
        form.addEventListener('submit', function(e) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement en cours...';
            submitBtn.disabled = true;
        });
    }
    
    // Effet de focus sur les inputs
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.parentElement.classList.add('focused');
        });
        
        // Animation légère au chargement des valeurs existantes
        setTimeout(() => {
            input.style.backgroundColor = '#fffef7';
            setTimeout(() => {
                input.style.backgroundColor = '';
            }, 500);
        }, 500);
    });
    
    // Notification de modification (optionnel)
    console.log('Formulaire de modification prêt - Organisation: {{ $organisme->nom_organisme }}');
</script>

<!-- Ajouter Font Awesome si pas déjà présent dans base -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection