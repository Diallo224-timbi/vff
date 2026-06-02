@extends('base')
@section('title', 'Espace des organismes')
@section('content')

<div class="container mt-5">
    <!-- En-tête avec animation -->
    <div class="row mb-4 animate__animated animate__fadeInDown">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-5 fw-bold mb-2" style="color: #255156;">
                        <i class="fas fa-building me-3"></i>Espace des Organismes
                    </h1>
                    <p class="text-muted lead">Gérez l'ensemble des organismes partenaires</p>
                </div>
                @if(Auth::user()->role === 'admin')
                    <div>
                        <a href="{{ route('organismes.create') }}" class="btn btn-primary btn-lg shadow-sm" 
                        style="background: linear-gradient(135deg, #255156 0%, #1d3f43 100%); border: none; border-radius: 50px; padding: 12px 30px;">
                            <i class="fas fa-plus-circle me-2"></i>Ajouter un Organisme
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistiques + Filtres -->
    <div class="row mb-4 animate__animated animate__fadeInUp">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="flex-grow-1">
                            <div class="input-group" style="border-radius: 50px; overflow: hidden;">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="search" 
                                       placeholder="Rechercher par nom, ville ou description..." style="box-shadow: none;">
                                <button class="btn btn-light" id="clearSearch" type="button">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <select id="sortBy" class="form-select" style="border-radius: 50px;">
                                <option value="name">Trier par nom</option>
                                <option value="city">Trier par ville</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #255156 0%, #1d3f43 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
                        </div>
                        <div class="text-end">
                            <small>Organismes enregistrés</small>
                            <h2 class="mb-0 fw-bold" id="organismCount">{{ $organismes->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes horizontales -->
    <div class="row" id="cardsContainer">
        @forelse($organismes as $organisme)
        <div class="col-12 mb-3 organism-card" data-name="{{ strtolower($organisme->nom_organisme) }}" 
             data-city="{{ strtolower($organisme->ville) }}" 
             data-description="{{ strtolower($organisme->signification) }}">
            <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 15px; overflow: hidden; transition: all 0.3s ease;">
                <div class="row g-0">
                    <!-- Section Logo (colonne de gauche) -->
                    <div class="col-md-3 col-lg-2 d-flex align-items-center justify-content-center p-3" 
                         style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        @if($organisme->logo_path)
                            <div class="logo-container" onclick="openLogoModal('{{ asset('storage/' . $organisme->logo_path) }}', '{{ $organisme->nom_organisme }}')">
                                <img src="{{ asset('storage/' . $organisme->logo_path) }}" 
                                     alt="Logo de {{ $organisme->nom_organisme }}" 
                                     class="logo-img">
                                <div class="logo-overlay">
                                    <i class="fas fa-eye"></i>
                                    <span>Zoom</span>
                                </div>
                            </div>
                        @else
                            <div class="no-logo">
                                <i class="fas fa-building fa-4x" style="color: #255156;"></i>
                                <p class="small text-muted mt-2 mb-0">Aucun logo</p>
                            </div>
                        @endif
                    </div>

                    <!-- Section Contenu (colonne du milieu) -->
                    <div class="col-md-7 col-lg-8">
                        <div class="card-body h-100">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="card-title mb-2" style="color: #255156; font-weight: 700;">
                                        {{ $organisme->nom_organisme }}
                                    </h3>
                                    <div class="d-flex flex-wrap gap-3 mb-2">
                                        <span class="badge" style="background: rgba(10, 157, 173, 0.2); color: #255156;">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $organisme->ville }}
                                        </span>
                                        <span class="badge" style="background: rgba(10, 157, 173, 0.2); color: #255156;">
                                            <i class="fas fa-mail-bulk me-1"></i>{{ $organisme->code_postal }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-quote-left me-1" style="color: #0a9dad; font-size: 12px;"></i>
                                {{ Str::limit($organisme->signification, 150) }}
                            </p>
                            
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-location-dot me-1" style="color: #0a9dad;"></i>
                                        <strong>Adresse :</strong> {{ $organisme->adresse }}
                                    </small>
                                </div>
                                @if($organisme->site_web)
                                <div class="col-md-6">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-globe me-1" style="color: #0a9dad;"></i>
                                        <strong>Site web :</strong> 
                                        <a href="{{ $organisme->site_web }}" target="_blank" class="text-decoration-none">
                                            {{ Str::limit($organisme->site_web, 40) }}
                                        </a>
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Section Actions (colonne de droite) -->
                    <div class="col-md-2 col-lg-2 d-flex flex-column justify-content-center align-items-center p-3" 
                         style="background: #f8f9fa; border-left: 1px solid #e9ecef;">
                        <div class="d-flex flex-column gap-2 w-100">
                            <a href="{{ route('organismes.show', $organisme->id) }}" class="btn btn-sm btn-info w-100" 
                               style="border-radius: 10px; background: #0a9dad; border: none; color: white;">
                                <i class="fas fa-eye me-1"></i> Voir
                            </a>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('organismes.edit', $organisme->id) }}" class="btn btn-sm btn-warning w-100" 
                                   style="border-radius: 10px;">
                                    <i class="fas fa-edit me-1"></i> Modifier
                                </a>
                                <form action="{{ route('organismes.destroy', $organisme->id) }}" method="POST" class="delete-form w-100">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger w-100 delete-btn" 
                                            style="border-radius: 10px;">
                                        <i class="fas fa-trash-alt me-1"></i> Supprimer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                <p class="lead text-muted">Aucun organisme trouvé</p>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('organismes.create') }}" class="btn btn-primary mt-2" 
                       style="background: #255156; border: none; border-radius: 50px;">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter le premier organisme
                    </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- MODAL POUR L'AGRANDISSEMENT DU LOGO -->
<div id="logoModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark" style="border-radius: 15px;">
            <div class="modal-body p-0 text-center">
                <img id="modalLogo" src="" alt="" class="img-fluid" style="border-radius: 15px; max-height: 80vh; width: auto;">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>

<!-- Styles CSS -->
<style>
    /* Animation des cartes */
    .organism-card {
        transition: all 0.3s ease;
    }
    
    .organism-card .card {
        transition: all 0.3s ease;
    }
    
    .organism-card .card:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;
    }
    
    /* Conteneur du logo horizontal */
    .logo-container {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }
    
    .logo-img {
        max-width: 90%;
        max-height: 90%;
        width: auto;
        height: auto;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    
    .logo-container:hover {
        transform: scale(1.05);
        border-color: #255156;
        box-shadow: 0 4px 12px rgba(37, 81, 86, 0.2);
    }
    
    .logo-container:hover .logo-img {
        transform: scale(1.05);
    }
    
    /* Overlay au survol */
    .logo-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(37, 81, 86, 0.9);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: all 0.3s ease;
        color: white;
        border-radius: 12px;
    }
    
    .logo-overlay i {
        font-size: 24px;
        margin-bottom: 5px;
    }
    
    .logo-overlay span {
        font-size: 12px;
    }
    
    .logo-container:hover .logo-overlay {
        opacity: 1;
    }
    
    /* Style pour absence de logo */
    .no-logo {
        text-align: center;
        padding: 15px;
    }
    
    /* Badges personnalisés */
    .badge {
        padding: 8px 12px;
        font-weight: 500;
        border-radius: 8px;
    }
    
    /* Animation d'entrée */
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
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate__fadeInDown {
        animation: slideInLeft 0.6s ease-out;
    }
    
    .animate__fadeInUp {
        animation: fadeInUp 0.6s ease-out;
    }
    
    /* Responsive pour mobile */
    @media (max-width: 768px) {
        .logo-container {
            width: 80px;
            height: 80px;
        }
        
        .logo-overlay span {
            display: none;
        }
        
        .logo-overlay i {
            font-size: 18px;
            margin-bottom: 0;
        }
        
        .organism-card .card-body {
            padding: 1rem;
        }
        
        .btn-sm {
            font-size: 12px;
            padding: 6px 10px;
        }
    }
    
    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
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
    
    /* Animation de filtrage */
    .fade-out {
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.3s ease;
    }
    
    .fade-in {
        opacity: 1;
        transform: translateX(0);
        transition: all 0.3s ease;
    }
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let logoModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        const logoModalEl = document.getElementById('logoModal');
        if (logoModalEl) {
            logoModal = new bootstrap.Modal(logoModalEl);
        }
        
        initializeSearch();
        initializeSorting();
    });
    
    // Fonction pour ouvrir la modal du logo
    window.openLogoModal = function(imageUrl, organismeName) {
        const modalImage = document.getElementById('modalLogo');
        if (modalImage) {
            modalImage.src = imageUrl;
            modalImage.alt = organismeName;
        }
        if (logoModal) {
            logoModal.show();
        }
    };
    
    // Initialisation de la recherche
    function initializeSearch() {
        const searchInput = document.getElementById('search');
        const clearSearchBtn = document.getElementById('clearSearch');
        
        function filterCards() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.organism-card');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const city = card.getAttribute('data-city');
                const description = card.getAttribute('data-description');
                
                if (searchTerm === '' || name.includes(searchTerm) || city.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = '';
                    card.classList.remove('fade-out');
                    card.classList.add('fade-in');
                    visibleCount++;
                } else {
                    card.classList.add('fade-out');
                    setTimeout(() => {
                        if (card.classList.contains('fade-out')) {
                            card.style.display = 'none';
                        }
                    }, 300);
                }
            });
            
            // Mise à jour du compteur
            document.getElementById('organismCount').textContent = visibleCount;
            
            // Gestion du message "aucun résultat"
            const noResultDiv = document.getElementById('noResultMessage');
            if (visibleCount === 0 && cards.length > 0) {
                if (!noResultDiv) {
                    const container = document.getElementById('cardsContainer');
                    const div = document.createElement('div');
                    div.id = 'noResultMessage';
                    div.className = 'col-12';
                    div.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3 d-block"></i>
                            <p class="lead text-muted">Aucun organisme ne correspond à votre recherche</p>
                            <button class="btn btn-primary btn-sm" id="resetSearchBtn" style="background: #255156; border: none; border-radius: 50px;">
                                <i class="fas fa-undo me-2"></i>Réinitialiser
                            </button>
                        </div>
                    `;
                    container.appendChild(div);
                    document.getElementById('resetSearchBtn')?.addEventListener('click', () => {
                        searchInput.value = '';
                        filterCards();
                    });
                }
            } else if (noResultDiv) {
                noResultDiv.remove();
            }
        }
        
        searchInput.addEventListener('input', filterCards);
        clearSearchBtn.addEventListener('click', () => {
            searchInput.value = '';
            filterCards();
            searchInput.focus();
        });
    }
    
    // Initialisation du tri
    function initializeSorting() {
        const sortSelect = document.getElementById('sortBy');
        const container = document.getElementById('cardsContainer');
        
        function sortCards() {
            const cards = Array.from(document.querySelectorAll('.organism-card'));
            const sortBy = sortSelect.value;
            
            cards.sort((a, b) => {
                if (sortBy === 'name') {
                    const nameA = a.getAttribute('data-name');
                    const nameB = b.getAttribute('data-name');
                    return nameA.localeCompare(nameB);
                } else if (sortBy === 'city') {
                    const cityA = a.getAttribute('data-city');
                    const cityB = b.getAttribute('data-city');
                    return cityA.localeCompare(cityB);
                }
                return 0;
            });
            
            // Réorganiser les cartes
            cards.forEach(card => {
                card.style.animation = 'none';
                container.appendChild(card);
                setTimeout(() => {
                    card.style.animation = '';
                }, 10);
            });
        }
        
        sortSelect.addEventListener('change', sortCards);
    }
    
    // Confirmation de suppression
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');
            
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#255156',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                background: '#fff',
                backdrop: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    
    // Messages flash
    @if(session('success'))
        Swal.fire({
            title: 'Succès !',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#255156',
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            title: 'Erreur !',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonColor: '#255156',
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection