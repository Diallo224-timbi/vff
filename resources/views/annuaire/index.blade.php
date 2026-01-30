@extends('base')

@section('title', 'Annuaire des structures')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Annuaire des structures</h1>

    <!-- Message succès -->
    @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4"
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"    
        >
            {{ session('success') }}
        </div>
    @elseif(session('errors'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            class="bg-red-200 text-red-800 px-4 py-2 rounded mb-4 shadow transition duration-500"
        >
            {{ session('errors') }}
        </div>
    @endif

    <div class="flex justify-between mb-4">
        <!-- Bouton ajouter structure -->
        @if(auth()->user()->role === 'admin')
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            Ajouter une structure
        </button>
        @endif

        <!-- Boutons Export CSV / PDF -->
        <div class="flex gap-2">
            <a href="{{ route('annuaire.export.pdf') }}" class="btn btn-danger">Exporter PDF</a>
        </div>
    </div>

    <!-- Recherche -->
    <form method="GET" action="{{ route('structures.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher un organisme, ville, catégorie, public cible..." 
                   class="border rounded w-full px-3 py-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Rechercher
            </button>
            @if(request('search'))
                <a href="{{ route('structures.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Effacer
                </a>
            @endif
        </div>
    </form>

    @if(request('search'))
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle"></i> 
            Résultats de la recherche pour : <strong>"{{ request('search') }}"</strong>
            ({{ $structures->total() }} résultat(s))
        </div>
    @endif

    <!-- Table avec en-tête fixe -->
    <div class="overflow-auto border rounded" style="max-height:600px;">
        <table class="table table-bordered table-striped w-full">
            <thead class="bg-gray-100 sticky top-0 z-10">
                <tr>
                    <th>Organisme</th>
                    <th>Siège Ville</th>
                    <th>Siège Adresse</th>
                    <th>Catégories</th>
                    <th>Site web</th>
                    <th>Public Cible</th>
                    <th>Zone</th>
                    <th>Type Structure</th>
                    <th>Ville (antenne)</th>
                    <th>Adresse (antenne)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($structures as $structure)
                    <tr>
                        <td><strong>{{ $structure->organisme }}</strong></td>
                        <td>{{ $structure->siege_ville ?? '-' }}</td>
                        <td>{{ $structure->siege_adresse ?? '-' }}</td>
                        <td>{{ $structure->categories ?? '-' }}</td>
                        <td>
                            @if($structure->site)
                                <a href="{{ $structure->site }}" target="_blank" 
                                   class="text-blue-600 underline site-link" 
                                   title="{{ $structure->site }}">
                                    <i class="fas fa-external-link-alt"></i>
                                    Visiter le site
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $structure->public_cible ?? '-' }}</td>
                        <td>{{ $structure->zone ?? '-' }}</td>
                        <td>{{ $structure->type_structure ?? '-' }}</td>
                        <td>{{ $structure->ville ?? '-' }}</td>
                        <td>{{ $structure->adresse ?? '-' }}</td>
                        <td>
                            <!-- Bouton Voir détails -->
                            <button class="btn btn-sm btn-outline-primary view-details-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#detailsModal"
                                    data-structure='@json($structure)'>
                                <i class="fas fa-eye"></i> Voir détails
                            </button>

                            <!-- Modifier (admin seulement) -->
                            @if(auth()->user()->role === 'admin')
                            <button class="btn btn-sm btn-warning edit-btn" 
                                    data-id="{{ $structure->id }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal">
                                <i class="fas fa-edit"></i> Modifier
                            </button>

                            <!-- Supprimer (admin seulement) -->
                            <form action="{{ route('structures.destroy', $structure) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette structure ? Attention, tous les utilisateurs rattachés seront aussi supprimés.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if($structures->isEmpty())
                    <tr>
                        <td colspan="11" class="text-center">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Aucune structure trouvée.
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $structures->links() }}
    </div>
</div>

<!-- MODAL AJOUT -->
@if(auth()->user()->role === 'admin')
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ajouter une structure</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @include('structures.form', [
            'structure' => new \App\Models\Structure,
            'action' => route('structures.store'),
            'method' => 'POST'
        ])
      </div>
    </div>
  </div>
</div>

<!-- MODAL MODIFIER -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modifier la structure</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="editModalBody">
        <!-- Formulaire chargé dynamiquement via fetch -->
      </div>
    </div>
  </div>
</div>
@endif

<!-- MODAL DETAILS - NOUVELLE VERSION -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-building me-2"></i>
                    <span id="modal-organisme"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- En-tête avec infos principales -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informations principales
                                </h6>
                                <div class="info-item mb-2">
                                    <span class="badge bg-light text-dark me-2">Siège</span>
                                    <span id="modal-siege_ville"></span> - <span id="modal-siege_adresse"></span>
                                </div>
                                <div class="info-item mb-2">
                                    <span class="badge bg-light text-dark me-2">Catégories</span>
                                    <span id="modal-categories" class="fw-bold"></span>
                                </div>
                                <div class="info-item mb-2">
                                    <span class="badge bg-light text-dark me-2">Public cible</span>
                                    <span id="modal-public_cible"></span>
                                </div>
                                <div class="info-item">
                                    <span class="badge bg-light text-dark me-2">Site web</span>
                                    <span id="modal-site"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Localisation
                                </h6>
                                <div class="info-item mb-2">
                                    <span class="badge bg-light text-dark me-2">Zone</span>
                                    <span id="modal-zone"></span>
                                </div>
                                <div class="info-item mb-2">
                                    <span class="badge bg-light text-dark me-2">Type</span>
                                    <span id="modal-type_structure"></span>
                                </div>
                                <div class="info-item mb-2">
                                    <span class="badge bg-light text-dark me-2">Ville antenne</span>
                                    <span id="modal-ville"></span>
                                </div>
                                <div class="info-item">
                                    <span class="badge bg-light text-dark me-2">Code postal</span>
                                    <span id="modal-code_postal"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description détaillée -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-file-alt me-2"></i>Description
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="modal-description" class="description-text"></div>
                    </div>
                </div>

                <!-- Adresse et hébergement -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-home me-2"></i>Adresse complète
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="modal-adresse" class="fw-bold mb-2"></div>
                                <div class="text-muted small" id="modal-coordinates"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-bed me-2"></i>Hébergement
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="modal-hebergement"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Détails spécifiques -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-list-ul me-2"></i>Détails spécifiques
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="modal-details"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .site-link {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
    }
    
    .btn-sm {
        margin-bottom: 2px;
    }
    
    .info-item {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .description-text {
        line-height: 1.6;
        white-space: pre-line;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
    
    .card {
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .badge {
        font-size: 0.75em;
        padding: 4px 8px;
    }
    
    .modal-header {
        border-bottom: 3px solid #2d6268;
    }
</style>
@endsection

@section('scripts')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
    // Edit modal
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            fetch(`/structures/${id}/edit`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('editModalBody').innerHTML = html;
                })
                .catch(err => console.error(err));
        });
    });

    // Details modal - NOUVELLE VERSION
    const viewDetailsButtons = document.querySelectorAll('.view-details-btn');
    
    viewDetailsButtons.forEach(btn => {
        btn.addEventListener('click', function(){
            // Récupérer les données JSON
            const structure = JSON.parse(this.getAttribute('data-structure'));
            
            // Remplir la modal avec un format amélioré
            document.getElementById('modal-organisme').textContent = structure.organisme || '-';
            
            // Description avec formatage
            const descriptionElement = document.getElementById('modal-description');
            if (structure.description && structure.description.trim() !== '') {
                descriptionElement.textContent = structure.description;
                descriptionElement.classList.remove('text-muted');
            } else {
                descriptionElement.textContent = 'Aucune description disponible';
                descriptionElement.classList.add('text-muted', 'fst-italic');
            }
            
            // Informations principales
            document.getElementById('modal-siege_ville').textContent = structure.siege_ville || 'Non spécifié';
            document.getElementById('modal-siege_adresse').textContent = structure.siege_adresse || 'Non spécifié';
            document.getElementById('modal-categories').textContent = structure.categories || 'Non spécifié';
            document.getElementById('modal-public_cible').textContent = structure.public_cible || 'Non spécifié';
            
            // Site web avec icône
            const siteElement = document.getElementById('modal-site');
            if (structure.site && structure.site.trim() !== '') {
                siteElement.innerHTML = `<a href="${structure.site}" target="_blank" class="text-primary fw-bold">
                    <i class="fas fa-external-link-alt me-1"></i>${structure.site}
                </a>`;
            } else {
                siteElement.textContent = 'Non disponible';
                siteElement.classList.add('text-muted');
            }
            
            // Localisation
            document.getElementById('modal-zone').textContent = structure.zone || 'Non spécifié';
            document.getElementById('modal-type_structure').textContent = structure.type_structure || 'Non spécifié';
            
            // Détails et hébergement avec formatage
            const detailsElement = document.getElementById('modal-details');
            if (structure.details && structure.details.trim() !== '') {
                detailsElement.textContent = structure.details;
                detailsElement.classList.remove('text-muted');
            } else {
                detailsElement.textContent = 'Aucun détail spécifique';
                detailsElement.classList.add('text-muted', 'fst-italic');
            }
            
            const hebergementElement = document.getElementById('modal-hebergement');
            if (structure.hebergement && structure.hebergement.trim() !== '') {
                hebergementElement.textContent = structure.hebergement;
                hebergementElement.classList.remove('text-muted');
            } else {
                hebergementElement.textContent = 'Non spécifié';
                hebergementElement.classList.add('text-muted', 'fst-italic');
            }
            
            // Adresse complète
            document.getElementById('modal-ville').textContent = structure.ville || 'Non spécifié';
            document.getElementById('modal-code_postal').textContent = structure.code_postal || 'Non spécifié';
            
            const adresseElement = document.getElementById('modal-adresse');
            if (structure.adresse && structure.adresse.trim() !== '') {
                adresseElement.textContent = structure.adresse;
                adresseElement.classList.remove('text-muted');
            } else {
                adresseElement.textContent = 'Adresse non spécifiée';
                adresseElement.classList.add('text-muted', 'fst-italic');
            }
            
            // Coordonnées GPS avec format
            const coordElement = document.getElementById('modal-coordinates');
            if (structure.latitude && structure.longitude && 
                parseFloat(structure.latitude) !== 0 && parseFloat(structure.longitude) !== 0) {
                coordElement.innerHTML = `<i class="fas fa-map-pin me-1"></i>
                    Latitude: ${structure.latitude}<br>
                    Longitude: ${structure.longitude}`;
            } else {
                coordElement.textContent = 'Coordonnées GPS non disponibles';
                coordElement.classList.add('text-muted', 'fst-italic');
            }
        });
    });
</script>
@endsection