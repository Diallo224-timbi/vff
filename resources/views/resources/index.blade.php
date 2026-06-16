@extends('base')

@section('title', 'Espace documentaire')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Carte principale -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">  
                <!-- En-tête -->
                <div class="card-header text-white py-3" style="background: #255156; border: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-folder-open me-2"></i>
                            <h4 class="d-inline-block mb-0 fw-bold">Espace documentaire</h4>
                            <p class="mt-1 mb-0 opacity-75 small">Toutes les ressources professionnelles</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <!-- Indicateur de filtre actif -->
                    <div id="activeFilter" class="mb-3 d-none">
                        <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                            <i class="fas fa-filter me-2"></i>
                            <span id="filterLabel">Filtre actif : </span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="clearCategoryFilter()"></button>
                        </div>
                    </div>
                    <!-- CARTES DE FILTRES PAR CATÉGORIE -->
                    <div class="mb-3">
                        <label class="small fw-semibold text-secondary mb-2">Filtrer par catégorie</label>
                        <div class="row g-2">
                            <div class="col-6 col-md-2">
                                <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="all" onclick="filterByCategory('all', 'Toutes les ressources')">
                                    <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                        <i class="fas fa-folder-open text-secondary fa-lg mb-1"></i>
                                        <p class="fw-semibold text-secondary mb-0 small">Toutes</p>
                                        <small class="text-secondary" id="countAll">0</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="procedure" onclick="filterByCategory('procedure', 'Procédures')">
                                    <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                        <i class="fas fa-tasks text-primary fa-lg mb-1"></i>
                                        <p class="fw-semibold text-primary mb-0 small">Procédures</p>
                                        <small class="text-primary" id="countProcedure">0</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="outil" onclick="filterByCategory('outil', 'Outils')">
                                    <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                        <i class="fas fa-tools text-success fa-lg mb-1"></i>
                                        <p class="fw-semibold text-success mb-0 small">Outils</p>
                                        <small class="text-success" id="countOutil">0</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="fiche_reflexe" onclick="filterByCategory('fiche_reflexe', 'Fiches réflexes')">
                                    <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                        <i class="fas fa-lightbulb text-warning fa-lg mb-1"></i>
                                        <p class="fw-semibold text-warning mb-0 small">Fiches réflexes</p>
                                        <small class="text-warning" id="countFiche">0</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="ressource" onclick="filterByCategory('ressource', 'Ressources')">
                                    <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                        <i class="fas fa-database text-info fa-lg mb-1"></i>
                                        <p class="fw-semibold text-info mb-0 small">Ressources</p>
                                        <small class="text-info" id="countRessource">0</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="rounded-lg p-2 text-center">
                                    <button onclick="openCreateModal()" class="btn w-100 py-2" style="background: #255156; color: white; border-radius: 10px;">
                                        <i class="fas fa-upload me-1"></i> Ajouter
                                    </button>
                                </div>
                                @if(auth()->user()->role === 'admin')
                                <div class="mt-2">
                                    <a href="{{ route('resources.trash') }}" class="btn w-100 py-2" style="background: #255156; color: white; border-radius: 10px;" href="#">
                                        <i class="fa fa-upload me-1"></i> Corbeille
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Barre de recherche -->
                    <div class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <div class="input-group" style="border-radius: 10px; overflow: hidden;">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" id="searchInput" class="form-control border-start-0" 
                                           placeholder="Rechercher par titre ou description...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select id="filterType" class="form-select">
                                    <option value="">Tous les types</option>
                                    <option value="image">Images</option>
                                    <option value="document">Documents</option>
                                    <option value="link">Liens externes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- LISTE HORIZONTALE DES RESSOURCES -->
                    <div class="mt-3">
                        <div class="vertical-list" id="resourcesGrid">
                            @forelse($resources as $resource)
                            <div class="horizontal-resource-card resource-card mb-3"
                                 data-id="{{ $resource->id }}"
                                 data-type="{{ $resource->is_link ? 'link' : ($resource->is_image ? 'image' : 'document') }}"
                                 data-category="{{ $resource->category }}"
                                 data-date="{{ $resource->created_at->timestamp }}"
                                 data-downloads="{{ $resource->download_count }}"
                                 data-title="{{ strtolower($resource->title) }}">
                                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <!-- Logo/Icone à gauche -->
                                            <div class="col-auto">
                                                <div class="resource-icon-wrapper" style="width: 70px; height: 70px;">
                                                    @if($resource->is_link)
                                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100" style="background: #e0f2fe !important;">
                                                            <i class="fas fa-link" style="color: #0d6efd;"></i>
                                                        </div>
                                                    @elseif($resource->is_image)
                                                        <div class="rounded-circle overflow-hidden w-100 h-100" style="cursor: pointer;" onclick="openImageModal('{{ $resource->file_url }}', '{{ $resource->title }}')">
                                                            <img src="{{ Storage::url($resource->file_path) }}" alt="{{ $resource->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                        </div>
                                                    @else
                                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                                            @php
                                                                $extension = strtolower($resource->file_type);
                                                            @endphp
                                                            @if(in_array($extension, ['pdf']))
                                                                <i class="bx bxs-file-pdf text-danger fa-2x"></i>
                                                            @elseif(in_array($extension, ['doc', 'docx', 'odt']))
                                                                <i class="fas fa-file-word text-primary fa-2x"></i>
                                                            @elseif(in_array($extension, ['xls', 'xlsx', 'csv']))
                                                                <i class="fas fa-file-excel text-success fa-2x"></i>
                                                            @elseif(in_array($extension, ['ppt', 'pptx']))
                                                                <i class="fas fa-file-powerpoint text-warning fa-2x"></i>
                                                            @elseif(in_array($extension, ['txt']))
                                                                <i class="fas fa-file-alt text-secondary fa-2x"></i>
                                                            @elseif(in_array($extension, ['webm', 'avi', 'mov']))
                                                                <i class="fas fa-file-video text-danger fa-2x"></i>
                                                            @else
                                                                <i class="fas fa-file text-secondary fa-2x"></i>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>  
                                            <!-- Titre et Description au centre -->
                                            <div class="col">
                                                <div class="resource-info">
                                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                                        <h6 class="fw-semibold mb-0" title="{{ $resource->title }}">{{ $resource->title }}</h6>
                                                        @if($resource->is_link)
                                                            <span class="badge" style="background: #e0f2fe; color: #0284c7;">
                                                                <i class="fas fa-link"></i>Lien
                                                            </span>
                                                        @elseif($resource->is_image)
                                                            <span class="badge" style="background: #f3e8ff; color: #9333ea;">
                                                                <i class="fas fa-image me-1"></i>Image
                                                            </span>
                                                        @else
                                                            <span class="badge" style="background: #dbeafe; color: #2563eb;">
                                                                <i class="fas fa-file me-1"></i>{{ strtoupper($resource->file_type) }}
                                                            </span>
                                                        @endif
                                                        
                                                        @if($resource->category == 'procedure')
                                                            <span class="badge" style="background: #dbeafe; color: #2563eb;">Procédure</span>
                                                        @elseif($resource->category == 'outil')
                                                            <span class="badge" style="background: #dcfce7; color: #16a34a;">Outil</span>
                                                        @elseif($resource->category == 'fiche_reflexe')
                                                            <span class="badge" style="background: #fef3c7; color: #d97706;">Fiche réflexe</span>
                                                        @else
                                                            <span class="badge" style="background: #f3e8ff; color: #9333ea;">Ressource</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($resource->description)
                                                        <p class="small text-muted mb-1">{{ $resource->description }}</p>
                                                    @endif
                                                    
                                                    <div class="d-flex gap-3 small text-muted">
                                                        @if(!$resource->is_link)
                                                             <span><i class="fas fa-link"></i> {{ $resource->file_type }}</span>
                                                       
                                                        <span><i class="fas fa-download me-1"></i> {{ $resource->download_count }} téléchargements</span>
                                                        @endif
                                                        <span><i class="fas fa-calendar me-1"></i> {{ $resource->created_at->format('d/m/Y') }}</span>
                                                        <span><i class="fas fa-user me-1"></i> {{ $resource->user ? $resource->user->name : 'Utilisateur inconnu' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Boutons à droite -->
                                            <div class="col-auto">
                                                <div class="d-flex gap-2">
                                                    @if($resource->is_link)
                                                        <a href="{{ $resource->link_url }}" target="_blank" class="btn btn-sm" style="background: #e0f2fe; color: #0284c7;" title="Ouvrir le lien">
                                                          <i class="fas fa-link"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if($resource->is_image)
                                                        <button onclick="openImageModal('{{ $resource->file_url }}', '{{ $resource->title }}')" class="btn btn-sm" style="background: #f3e8ff; color: #9333ea;" title="Voir l'image">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    @if(!$resource->is_link)
                                                        <a href="{{ Storage::url($resource->file_path) }}" target="_blank" class="btn btn-sm" style="background: #e5e7eb; color: #4b5563;" title="Ouvrir le fichier">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                        <a href="{{ route('resources.download', $resource) }}" class="btn btn-sm" style="background: #dbeafe; color: #2563eb;" title="Télécharger">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif   
                                                    @if(auth()->user()->role === 'admin' || auth()->user()->id === $resource->user_id)
                                                        <a href="{{ route('resources.edit', $resource) }}" class="btn btn-sm" style="background: #c7d2fe; color: #3730a3;" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button onclick="deleteResource({{ $resource->id }}, this)" class="btn btn-sm" style="background: #fee2e2; color: #dc2626;" title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune ressource disponible</p>
                                <button onclick="openCreateModal()" class="btn" style="background: #255156; color: white;">
                                    <i class="fas fa-upload me-1"></i>Ajouter la première ressource
                                </button>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="mt-4">
                        {{ $resources->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL IMAGE -->
<div id="imageModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-body p-0 text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
            </div>
        </div>
    </div>
</div>
<!-- MODAL CRÉATION -->
<div id="resourceModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header" style="background: #255156; color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title">Ajouter une ressource</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="resourceForm" enctype="multipart/form-data" method="POST" action="{{ route('resources.store') }}">
                <div class="modal-body">
                    @csrf 
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" required class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea id="description" name="description" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Type de ressource <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group">
                            <button type="button" id="btnFileType" class="btn btn-outline-primary active" onclick="selectResourceType('file')">
                                <i class="fas fa-upload me-1"></i> Fichier
                            </button>
                            <button type="button" id="btnLinkType" class="btn btn-outline-primary" onclick="selectResourceType('link')">
                                <i class="fas fa-link me-1"></i> Lien externe
                            </button>
                        </div>
                    </div>
                    <div id="fileUploadSection" class="mb-3">
                        <label class="form-label fw-semibold">Fichier <span class="text-danger">*</span></label>
                        <input type="file" id="file" name="file" class="form-control" accept=".pdf,.doc,.odt,.docx,.xls,.csv,.jpg,.jpeg,.png,.gif,.webm,.avi">
                        <small class="text-muted">Formats acceptés: PDF, DOC, ODT, DOCX, JPG, PNG, GIF, Max 50Mo</small>
                    </div>
                    
                    <div id="linkSection" class="mb-3 d-none">
                        <label class="form-label fw-semibold">URL du lien <span class="text-danger">*</span></label>
                        <input type="url" id="linkUrl" name="link_url" class="form-control" placeholder="https://exemple.com/document">
                        <small class="text-muted">Entrez l'URL complète du lien externe</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                        <select id="category" name="category" required class="form-select">
                            <option value="">Sélectionner une catégorie</option>
                            <option value="procedure">Procédure</option>
                            <option value="outil">Outil</option>
                            <option value="fiche_reflexe">Fiche réflexe</option>
                            <option value="ressource">Ressource</option>
                        </select>
                    </div>
                    <!-- checkbox pour document important à l'admin -->
                    @if(auth()->user()->role === 'admin')
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="important" name="important" value="1">
                        <label class="form-check-label fw-semibold" for="important">Marquer comme ressource importante</label>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn" style="background: #255156; color: white;">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
/* Style pour les cartes horizontales */
.horizontal-resource-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.horizontal-resource-card:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.resource-icon-wrapper {
    border-radius: 12px;
    overflow: hidden;
}

.resource-info {
    flex: 1;
}

/* Style pour les filtres actifs */
.filter-category.active {
    border: 2px solid #255156 !important;
    background-color: #f0f7f7 !important;
    transform: scale(1.02);
    transition: all 0.2s ease;
}

.filter-category {
    transition: all 0.2s ease;
}

.filter-category:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Style responsive pour mobile */
@media (max-width: 768px) {
    .horizontal-resource-card .row {
        flex-direction: column;
        text-align: center;
    }
    
    .horizontal-resource-card .col-auto {
        margin-bottom: 1rem;
    }
    
    .horizontal-resource-card .col-auto:last-child {
        margin-top: 1rem;
        margin-bottom: 0;
    }
    
    .resource-info .d-flex {
        justify-content: center;
    }
}

.object-fit-cover { object-fit: cover; }
.cursor-pointer { cursor: pointer; }
.bg-\[#255156\] { background-color: #255156; }
.text-\[#255156\] { color: #255156; }
.btn-group .btn.active {
    background-color: #255156;
    color: white;
    border-color: #255156;
}
</style>

<script>
// Données des ressources
const allResources = @json($resources->items());

let selectedResourceType = 'file';
let currentCategoryFilter = null;

// Fonction pour filtrer par catégorie
window.filterByCategory = function(category, label) {
    if (currentCategoryFilter === category) {
        // Si on clique sur le même filtre, on le désactive
        clearCategoryFilter();
    } else {
        // Active le filtre
        currentCategoryFilter = category;
        
        // Met à jour l'affichage des filtres actifs
        document.querySelectorAll('.filter-category').forEach(el => {
            if (el.getAttribute('data-category') === category) {
                el.classList.add('active');
            } else {
                el.classList.remove('active');
            }
        });
        
        // Affiche l'indicateur de filtre
        const filterLabel = document.getElementById('filterLabel');
        if (filterLabel) {
            filterLabel.innerHTML = `<i class="fas fa-filter me-2"></i>Filtre actif : ${label}`;
        }
        const activeFilter = document.getElementById('activeFilter');
        if (activeFilter) {
            activeFilter.classList.remove('d-none');
        }
        
        // Filtre les ressources
        filterResourcesByCategory();
    }
};

// Fonction pour effacer le filtre catégorie
window.clearCategoryFilter = function() {
    currentCategoryFilter = null;
    
    // Enlève la classe active de tous les filtres
    document.querySelectorAll('.filter-category').forEach(el => {
        el.classList.remove('active');
    });
    
    // Cache l'indicateur de filtre
    const activeFilter = document.getElementById('activeFilter');
    if (activeFilter) {
        activeFilter.classList.add('d-none');
    }
    
    // Affiche toutes les ressources
    filterResourcesByCategory();
};

// Fonction pour filtrer les ressources par catégorie
function filterResourcesByCategory() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const typeFilter = document.getElementById('filterType')?.value || '';
    
    document.querySelectorAll('.resource-card').forEach(card => {
        const category = card.dataset.category || '';
        const title = card.dataset.title || '';
        const type = card.dataset.type || '';
        let show = true;
        
        // Filtre par catégorie
        if (currentCategoryFilter && currentCategoryFilter !== 'all' && category !== currentCategoryFilter) {
            show = false;
        }
        
        // Filtre par recherche
        if (show && searchTerm && !title.includes(searchTerm)) {
            show = false;
        }
        
        // Filtre par type
        if (show && typeFilter && type !== typeFilter) {
            show = false;
        }
        
        card.style.display = show ? '' : 'none';
    });
}

// Sélection du type de ressource
window.selectResourceType = function(type) {
    selectedResourceType = type;
    
    const btnFile = document.getElementById('btnFileType');
    const btnLink = document.getElementById('btnLinkType');
    const fileSection = document.getElementById('fileUploadSection');
    const linkSection = document.getElementById('linkSection');
    const fileInput = document.getElementById('file');
    const linkUrlInput = document.getElementById('linkUrl');
    
    if (type === 'file') {
        if (btnFile) btnFile.classList.add('active');
        if (btnLink) btnLink.classList.remove('active');
        if (fileSection) fileSection.classList.remove('d-none');
        if (linkSection) linkSection.classList.add('d-none');
        if (fileInput) fileInput.required = true;
        if (linkUrlInput) linkUrlInput.required = false;
    } else {
        if (btnFile) btnFile.classList.remove('active');
        if (btnLink) btnLink.classList.add('active');
        if (fileSection) fileSection.classList.add('d-none');
        if (linkSection) linkSection.classList.remove('d-none');
        if (fileInput) fileInput.required = false;
        if (linkUrlInput) linkUrlInput.required = true;
    }
};

// Comptage par catégorie
function updateCategoryCounts() {
    let counts = { all: allResources.length, procedure: 0, outil: 0, fiche_reflexe: 0, ressource: 0 };
    allResources.forEach(r => {
        if (r.category === 'procedure') counts.procedure++;
        else if (r.category === 'outil') counts.outil++;
        else if (r.category === 'fiche_reflexe') counts.fiche_reflexe++;
        else if (r.category === 'ressource') counts.ressource++;
    });
    
    const countAll = document.getElementById('countAll');
    const countProcedure = document.getElementById('countProcedure');
    const countOutil = document.getElementById('countOutil');
    const countFiche = document.getElementById('countFiche');
    const countRessource = document.getElementById('countRessource');
    
    if (countAll) countAll.textContent = counts.all;
    if (countProcedure) countProcedure.textContent = counts.procedure;
    if (countOutil) countOutil.textContent = counts.outil;
    if (countFiche) countFiche.textContent = counts.fiche_reflexe;
    if (countRessource) countRessource.textContent = counts.ressource;
}

// Mise à jour des statistiques
function updateStats() {
    let fileCount = allResources.filter(r => !r.is_link).length;
    let linkCount = allResources.filter(r => r.is_link).length;
    const statTotal = document.getElementById('statTotal');
    const statFiles = document.getElementById('statFiles');
    const statLinks = document.getElementById('statLinks');
    
    if (statTotal) statTotal.textContent = allResources.length;
    if (statFiles) statFiles.textContent = fileCount;
    if (statLinks) statLinks.textContent = linkCount;
}

let imageModal, resourceModal, statsModal;

document.addEventListener('DOMContentLoaded', function() {
    const imageModalEl = document.getElementById('imageModal');
    const resourceModalEl = document.getElementById('resourceModal');
    const statsModalEl = document.getElementById('statsModal');
    
    if (imageModalEl) imageModal = new bootstrap.Modal(imageModalEl);
    if (resourceModalEl) resourceModal = new bootstrap.Modal(resourceModalEl);
    if (statsModalEl) statsModal = new bootstrap.Modal(statsModalEl);
    
    updateCategoryCounts();
    updateStats();
    
    const searchInput = document.getElementById('searchInput');
    const filterType = document.getElementById('filterType');
    
    if (searchInput && filterType) {
        function filterResources() {
            filterResourcesByCategory();
        }
        
        searchInput.addEventListener('input', filterResources);
        filterType.addEventListener('change', filterResources);
    }
});

window.openCreateModal = function() {
    const form = document.getElementById('resourceForm');
    if (form) form.reset();
    
    const fileSection = document.getElementById('fileUploadSection');
    const linkSection = document.getElementById('linkSection');
    const btnFile = document.getElementById('btnFileType');
    const btnLink = document.getElementById('btnLinkType');
    const fileInput = document.getElementById('file');
    const linkUrlInput = document.getElementById('linkUrl');
    
    if (fileSection) fileSection.classList.remove('d-none');
    if (linkSection) linkSection.classList.add('d-none');
    if (btnFile) btnFile.classList.add('active');
    if (btnLink) btnLink.classList.remove('active');
    
    selectedResourceType = 'file';
    
    if (fileInput) fileInput.required = true;
    if (linkUrlInput) linkUrlInput.required = false;
    
    if (resourceModal) resourceModal.show();
};

window.openImageModal = function(url, title) {
    const modalImage = document.getElementById('modalImage');
    if (modalImage) {
        modalImage.src = url;
        modalImage.alt = title;
    }
    if (imageModal) imageModal.show();
};

window.deleteResource = function(id, btn) {
    if (!confirm('Supprimer cette ressource ?')) return;
    fetch(`/ressources/${id}`, {
        method: 'DELETE',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then(r => r.json()).then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Erreur lors de la suppression');
        }
    }).catch(error => {
        console.error('Erreur:', error);
        alert('Erreur de connexion');
    });
};

window.openStatsModal = function() { 
    if (statsModal) statsModal.show(); 
};

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

const resourceForm = document.getElementById('resourceForm');
if (resourceForm) {
    resourceForm.addEventListener('submit', function(e) {
        if (selectedResourceType === 'file') {
            const fileInput = document.getElementById('file');
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner un fichier');
                return false;
            }
        } else {
            const linkUrl = document.getElementById('linkUrl');
            if (!linkUrl.value.trim()) {
                e.preventDefault();
                alert('Veuillez entrer une URL valide');
                return false;
            }
        }
    });
}
</script>
@endsection