@extends('base')
@section('title', 'Ajouter un organisme')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Carte principale avec animation -->
            <div class="card border-0 shadow-lg animate__animated animate__fadeInUp" style="border-radius: 20px; overflow: hidden;">
                
                <!-- En-tête avec dégradé -->
                <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #255156 0%, #255156 100%); border: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-building fa-2x me-2"></i>
                            <h1 class="d-inline-block mb-0 fw-bold">Ajouter un Organisme</h1>
                            <p class="mt-2 mb-0 opacity-75">Remplissez les informations ci-dessous</p>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-hand-holding-heart fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <!-- Corps du formulaire -->
                <div class="card-body p-5">
                    <form action="{{ route('organismes.store') }}" method="POST" id="organismeForm">
                        @csrf
                        
                        <!-- Champ Nom avec icône -->
                        <div class="mb-4 form-group-animate">
                            <label for="nom" class="form-label fw-semibold mb-2">
                                <i class="fas fa-building me-2 text-primary"></i>Nom de l'organisme
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-tag text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" id="nom" name="nom_organisme" 
                                       placeholder="Ex: Association Culturelle" required>
                            </div>
                            <div class="invalid-feedback">Veuillez saisir le nom de l'organisme</div>
                        </div>

                        <!-- Champ Description avec éditeur amélioré -->
                        <div class="mb-4 form-group-animate">
                            <label for="signification" class="form-label fw-semibold mb-2">
                                <i class="fas fa-align-left me-2 text-primary"></i>Description
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light align-items-start pt-3">
                                    <i class="fas fa-file-alt text-muted"></i>
                                </span>
                                <textarea class="form-control" id="signification" name="signification" rows="4" 
                                          placeholder="Décrivez la mission et les activités de l'organisme..." required></textarea>
                            </div>
                        </div>

                        <!-- Adresse avec autocomplétion (dynamique) -->
                        <div class="mb-4 form-group-animate">
                            <label for="adresse" class="form-label fw-semibold mb-2">
                                <i class="fas fa-location-dot me-2 text-primary"></i>Adresse
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" id="adresse" name="adresse" 
                                       placeholder="Numéro et nom de rue" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Code Postal -->
                            <div class="col-md-4 mb-4 form-group-animate">
                                <label for="code_postal" class="form-label fw-semibold mb-2">
                                    <i class="fas fa-mail-bulk me-2 text-primary"></i>Code postal
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-hashtag text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="code_postal" name="code_postal" 
                                           placeholder="06000" maxlength="5" required>
                                </div>
                            </div>

                            <!-- Ville -->
                            <div class="col-md-8 mb-4 form-group-animate">
                                <label for="ville" class="form-label fw-semibold mb-2">
                                    <i class="fas fa-city me-2 text-primary"></i>Ville
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-map-pin text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="ville" name="ville" 
                                           placeholder="Nice" required>
                                </div>
                            </div>
                        </div>

                        <!-- Site Web avec validation en temps réel -->
                        <div class="mb-4 form-group-animate">
                            <label for="site_web" class="form-label fw-semibold mb-2">
                                <i class="fas fa-globe me-2 text-primary"></i>Site web
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-link text-muted"></i>
                                </span>
                                <input type="url" class="form-control border-start-0 ps-0" id="site_web" name="site_web" 
                                       placeholder="https://exemple.org">
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
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn" style="background: linear-gradient(135deg, #255160 0%, #106f8f 100%); border: none;">
                                <i class="fas fa-save me-2"></i>Ajouter l'organisme
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
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    /* Effet hover sur le bouton */
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }
    
    /* Animation du curseur dans les inputs */
    .form-control {
        transition: all 0.3s ease;
    }
    
    /* Style des input group au focus */
    .input-group:focus-within .input-group-text {
        border-color: #255156;
        background-color: #255170;
    }
    
    /* Card hover effect */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15) !important;
    }
</style>

<!-- Script pour la validation en temps réel -->
<script>
    // Validation du code postal (uniquement chiffres, 5 caractères)
    document.getElementById('code_postal').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
    });
    
    // Validation URL en temps réel
    document.getElementById('site_web').addEventListener('input', function(e) {
        const url = this.value;
        if(url && !url.match(/^https?:\/\/.+/)) {
            this.setCustomValidity('Veuillez saisir une URL valide commençant par http:// ou https://');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Animation au submit
    document.getElementById('organismeForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Ajout en cours...';
        btn.disabled = true;
    });
    
    // Effet de placeholder flottant simplifié
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.parentElement.classList.add('focused');
        });
    });
</script>

<!-- Ajouter Font Awesome si pas déjà présent dans base -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection