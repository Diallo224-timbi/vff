@extends('base')
@section('title', 'Détail d\'un organisme')
@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Carte principale -->
            <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 16px; overflow: hidden;">
                
                <!-- En-tête avec dégradé personnalisé - TAILLE RÉDUITE -->
                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #255156 0%, #1d3f43 100%); border: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-building fa-lg me-2"></i>
                            <h4 class="d-inline-block mb-0 fw-bold">Détails de l'Organisme</h4>
                            <p class="mt-1 mb-0 opacity-75 small">Informations complètes et contacts</p>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-info-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <!-- Corps de la carte -->
                <div class="card-body p-0">
                    
                    <!-- Section en-tête avec nom et icône - TAILLE RÉDUITE -->
                    <div class="p-3 border-bottom" style="background: linear-gradient(135deg, rgba(37,81,86,0.05) 0%, rgba(29,63,67,0.02) 100%);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="flex-shrink-0">
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #255156 0%, #1d3f43 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-building fa-lg text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fw-bold" style="color: #255156;">{{ $organisme->nom_organisme }}</h5>
                                <small class="text-muted">
                                    <i class="fas fa-id-card me-1"></i> ID: {{ $organisme->id }}
                                </small>
                            </div>
                            <div>
                                <span class="badge px-2 py-1" style="background: #255156; border-radius: 50px; font-size: 11px;">
                                    <i class="fas fa-check-circle me-1"></i> Actif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Informations détaillées - TAILLE RÉDUITE -->
                    <div class="p-3">
                        <div class="row g-3">
                            
                            <!-- Description -->
                            <div class="col-12">
                                <div class="p-2" style="background: #f8f9fa; border-radius: 10px; border-left: 3px solid #255156;">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-align-left" style="color: #255156;"></i>
                                        </div>
                                        <div>
                                            <small class="fw-semibold mb-1 d-block" style="color: #255156;">Description</small>
                                            <p class="mb-0 text-secondary small">{{ $organisme->signification }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Localisation -->
                            <div class="col-md-6">
                                <div class="p-2 h-100" style="background: #f8f9fa; border-radius: 10px;">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-location-dot" style="color: #255156;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="fw-semibold mb-2 d-block" style="color: #255156;">Adresse postale</small>
                                            <p class="mb-1 text-secondary small">
                                                <i class="fas fa-map-marker-alt me-1" style="color: #255156;"></i>
                                                {{ $organisme->adresse }}
                                            </p>
                                            <p class="mb-0 text-secondary small">
                                                <i class="fas fa-envelope me-1" style="color: #255156;"></i>
                                                {{ $organisme->code_postal }} {{ $organisme->ville }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact & Web -->
                            <div class="col-md-6">
                                <div class="p-2 h-100" style="background: #f8f9fa; border-radius: 10px;">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-globe" style="color: #255156;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="fw-semibold mb-2 d-block" style="color: #255156;">Site web</small>
                                            @if($organisme->site_web)
                                                <a href="{{ $organisme->site_web }}" target="_blank" class="text-decoration-none small" style="color: #255156;">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    {{ Str::limit($organisme->site_web, 40) }}
                                                </a>
                                            @else
                                                <p class="mb-0 text-muted small">
                                                    <i class="fas fa-minus-circle me-1"></i>
                                                    Non renseigné
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations supplémentaires - TAILLE RÉDUITE -->
                            <div class="col-12">
                                <div class="p-2" style="background: linear-gradient(135deg, rgba(37,81,86,0.05) 0%, rgba(29,63,67,0.02) 100%); border-radius: 10px;">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <i class="fas fa-calendar-alt me-1" style="color: #255156;"></i>
                                            <span class="text-secondary small">Créé le : </span>
                                            <strong class="small">{{ $organisme->created_at ? $organisme->created_at->format('d/m/Y') : 'N/A' }}</strong>
                                        </div>
                                        <div>
                                            <i class="fas fa-edit me-1" style="color: #255156;"></i>
                                            <span class="text-secondary small">Modifié le : </span>
                                            <strong class="small">{{ $organisme->updated_at ? $organisme->updated_at->format('d/m/Y') : 'N/A' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions - TAILLE RÉDUITE -->
                    <div class="card-footer bg-white p-3 border-top">
                        <div class="d-flex gap-2 justify-content-between align-items-center flex-wrap">
                            <a href="{{ route('organismes.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="fas fa-arrow-left me-1"></i>Retour
                            </a>
                            <div class="d-flex gap-2">
                                <a href="{{ route('organismes.edit', $organisme->id) }}" class="btn btn-warning btn-sm px-3" style="border-radius: 8px;">
                                    <i class="fas fa-edit me-1"></i>Modifier
                                </a>
                                <form action="{{ route('organismes.destroy', $organisme->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm px-3 delete-btn" style="border-radius: 8px;">
                                        <i class="fas fa-trash-alt me-1"></i>Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte de localisation - TAILLE RÉDUITE -->
            <div class="card border-0 shadow-sm mt-3 animate__animated animate__fadeInUp" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body p-0">
                    <div style="height: 150px; background: linear-gradient(135deg, #255156 0%, #1d3f43 100%); position: relative; overflow: hidden;">
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
                            <i class="fas fa-map-marked-alt fa-2x mb-1 opacity-75"></i>
                            <p class="mb-0 small">Carte de localisation</p>
                            <small class="small">{{ $organisme->adresse }}, {{ $organisme->code_postal }} {{ $organisme->ville }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles CSS réduits -->
<style>
    /* Animations simplifiées */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate__fadeInDown {
        animation: slideInLeft 0.4s ease-out;
    }
    
    .animate__fadeInUp {
        animation: fadeInUp 0.4s ease-out;
    }
    
    /* Effet hover simplifié */
    .btn {
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    /* Scrollbar plus discret */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #255156;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #1d3f43;
    }
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Confirmation de suppression avec SweetAlert2
    const deleteBtn = document.querySelector('.delete-btn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');
            
            Swal.fire({
                title: 'Supprimer l\'organisme',
                text: "Êtes-vous sûr de vouloir supprimer cet organisme ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#255156',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
    
    // Messages flash
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: "{{ session('success') }}",
            confirmButtonColor: '#255156',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: "{{ session('error') }}",
            confirmButtonColor: '#255156'
        });
    @endif
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection