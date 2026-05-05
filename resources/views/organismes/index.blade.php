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
                <div>
                    <a href="{{ route('organismes.create') }}" class="btn btn-primary btn-lg shadow-sm" 
                       style="background: linear-gradient(135deg, #255156 0%, #1d3f43 100%); border: none; border-radius: 50px; padding: 12px 30px;">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter un Organisme
                    </a>
                </div>
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
                                       placeholder="Rechercher par nom ou ville..." style="box-shadow: none;">
                                <button class="btn btn-light" id="clearSearch" type="button">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <span class="badge fs-6 px-3 py-2" style="background: #255156; border-radius: 50px;">
                                <i class="fas fa-database me-1"></i> Total: {{ $organismes->count() }}
                            </span>
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
                            <h2 class="mb-0 fw-bold">{{ $organismes->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des organismes -->
    <div class="card border-0 shadow-lg animate__animated animate__fadeInUp" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="min-width: 1000px;">
                    <thead style="background: linear-gradient(135deg, #0a9dad 0%, #1d3f43 100%);">
                        <tr>
                            <th class="text-black py-3 px-4"><i class="fas fa-hashtag me-1"></i> ID</th>
                            <th class="text-black py-3"><i class="fas fa-building me-1"></i> Nom</th>
                            <th class="text-black py-3"><i class="fas fa-align-left me-1"></i> Description</th>
                            <th class="text-black py-3"><i class="fas fa-building me-1"></i> Structures rattachées</th>
                            <th class="text-black py-3"><i class="fas fa-location-dot me-1"></i> Adresse</th>
                            <th class="text-black py-3"><i class="fas fa-mail-bulk me-1"></i> Code postal</th>
                            <th class="text-black py-3"><i class="fas fa-city me-1"></i> Ville</th>
                            <th class="text-black py-3"><i class="fas fa-globe me-1"></i> Site web</th>
                            <th class="text-black py-3 text-center"><i class="fas fa-cogs me-1"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($organismes as $organisme)
                        <tr class="align-middle animate-row" style="transition: all 0.3s ease;">
                            <td class="px-4 fw-bold" style="color: #255156;">{{ $organisme->id }}</td>
                            <td>
                                <strong>{{ $organisme->nom_organisme }}</strong>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $organisme->signification }}">
                                    {{ Str::limit($organisme->signification, 50) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ count($organisme->structures) }} {{ Str::plural('structure', count($organisme->structures)) }}
                                </span>
                            </td> 
                            <td>{{ $organisme->adresse }}</td>
                            <td>{{ $organisme->code_postal }}</td>
                            <td>
                                <i class="fas fa-map-pin me-1" style="color: #255156;"></i>
                                {{ $organisme->ville }}
                            </td>
                            <td>
                                @if($organisme->site_web)
                                    <a href="{{ $organisme->site_web }}" target="_blank" class="btn btn-sm btn-outline-primary" 
                                       style="border-radius: 50px; border-color: #255156; color: #255156;">
                                        <i class="fas fa-external-link-alt me-1"></i> Visiter
                                    </a>
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('organismes.show', $organisme->id) }}" class="btn btn-sm btn-info mx-1" 
                                       style="border-radius: 10px;" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('organismes.edit', $organisme->id) }}" class="btn btn-sm btn-warning mx-1" 
                                       style="border-radius: 10px;" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('organismes.destroy', $organisme->id) }}" method="POST" class="d-inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mx-1 delete-btn" 
                                                style="border-radius: 10px;" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <p class="lead text-muted">Aucun organisme trouvé</p>
                                <a href="{{ route('organismes.create') }}" class="btn btn-primary mt-2" 
                                   style="background: #255156; border: none;">
                                    <i class="fas fa-plus-circle me-2"></i>Ajouter le premier organisme
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de notification -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-4">
                <i class="fas fa-check-circle fa-4x mb-3" id="modalIcon" style="color: #255156;"></i>
                <h5 class="mb-3" id="modalTitle">Succès</h5>
                <p id="modalMessage"></p>
                <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal" 
                        style="background: #255156; border: none; border-radius: 50px;">
                    <i class="fas fa-check me-2"></i>Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Styles CSS supplémentaires -->
<style>
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
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
    
    /* Animation des lignes du tableau */
    .animate-row {
        animation: fadeInUp 0.3s ease-out;
        animation-fill-mode: backwards;
    }
    
    /* Effet hover sur les lignes */
    .table tbody tr:hover {
        background-color: rgba(37, 81, 86, 0.05);
        transform: scale(1.01);
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    /* Style des boutons dans le tableau */
    .btn-group .btn {
        transition: all 0.3s ease;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-2px);
    }
    
    /* Animation de la barre de recherche */
    #search:focus {
        border-color: #255156;
        box-shadow: 0 0 0 0.2rem rgba(37, 81, 86, 0.25);
    }
    
    /* Style du scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #255156;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #1d3f43;
    }
</style>

<!-- Scripts améliorés -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    document.querySelectorAll('.animate-row').forEach((row, index) => {
        row.style.animationDelay = `${index * 0.03}s`;
    });
    
    // Fonction de recherche améliorée
    const searchInput = document.getElementById('search');
    const clearSearchBtn = document.getElementById('clearSearch');
    const tableRows = document.querySelectorAll('#tableBody tr');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            if (row.cells.length < 6) return;
            
            const name = row.cells[1]?.textContent.toLowerCase() || '';
            const city = row.cells[5]?.textContent.toLowerCase() || '';
            
            if (searchTerm === '' || name.includes(searchTerm) || city.includes(searchTerm)) {
                row.style.display = '';
                row.style.animation = 'fadeInUp 0.3s ease-out';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Afficher un message si aucun résultat
        const noResultRow = document.getElementById('noResultRow');
        if (visibleCount === 0 && tableRows.length > 0) {
            if (!noResultRow) {
                const tbody = document.getElementById('tableBody');
                const tr = document.createElement('tr');
                tr.id = 'noResultRow';
                tr.innerHTML = `
                    <td colspan="8" class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3 d-block"></i>
                        <p class="lead text-muted">Aucun organisme ne correspond à votre recherche</p>
                        <button class="btn btn-primary btn-sm" id="resetSearchBtn" style="background: #255156; border: none;">
                            <i class="fas fa-undo me-2"></i>Réinitialiser
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
                document.getElementById('resetSearchBtn')?.addEventListener('click', () => {
                    searchInput.value = '';
                    filterTable();
                });
            }
        } else if (noResultRow) {
            noResultRow.remove();
        }
    }   
    searchInput.addEventListener('input', filterTable);
    clearSearchBtn.addEventListener('click', () => {
        searchInput.value = '';
        filterTable();
        searchInput.focus();
    });  
    // Confirmation de suppression avec SweetAlert2
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
    
    // Notification pour les messages flash
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
    
    // Compteur de résultats visibles
    const updateResultCount = () => {
        const visible = document.querySelectorAll('#tableBody tr:not([style*="display: none"])').length;
        const total = document.querySelectorAll('#tableBody tr').length;
        const resultText = document.querySelector('#resultCount');
        if (resultText) {
            resultText.textContent = `${visible} résultat(s) sur ${total}`;
        }
    };
    
    // Effet de chargement initial
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.animate-row').forEach(row => {
            row.style.opacity = '0';
            setTimeout(() => {
                row.style.opacity = '1';
            }, 100);
        });
    });
    
    // Tooltips automatiques
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

<!-- Ajouter Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection