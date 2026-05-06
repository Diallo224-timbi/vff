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
                        <div>
                            <button onclick="openStatsModal()" class="btn btn-sm btn-light text-[#255156]">
                                <i class="fas fa-chart-pie me-1"></i> Statistiques
                            </button>
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

                    <!-- CARTES DE FILTRES PAR CATÉGORIE -->
                    <div class="mb-3">
                        <label class="small fw-semibold text-secondary mb-2">Filtrer par catégorie</label>
                        <div class="row g-2">
                            <div class="col-6 col-md-2">
                                <div class="cursor-pointer rounded-lg p-2 text-center border" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" onclick="openCategoryModal('all', 'Toutes les ressources')">
                                    <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                        <i class="fas fa-folder-open text-secondary fa-lg mb-1"></i>
                                        <p class="fw-semibold text-secondary mb-0 small">Toutes</p>
                                        <small class="text-secondary" id="countAll">0</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="cursor-pointer rounded-lg p-2 text-center border" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" onclick="openCategoryModal('procedure', 'Procédures')">
                                    <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                        <i class="fas fa-tasks text-primary fa-lg mb-1"></i>
                                        <p class="fw-semibold text-primary mb-0 small">Procédures</p>
                                        <small class="text-primary" id="countProcedure">0</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="cursor-pointer rounded-lg p-2 text-center border" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" onclick="openCategoryModal('outil', 'Outils')">
                                    <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                        <i class="fas fa-tools text-success fa-lg mb-1"></i>
                                        <p class="fw-semibold text-success mb-0 small">Outils</p>
                                        <small class="text-success" id="countOutil">0</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="cursor-pointer rounded-lg p-2 text-center border" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" onclick="openCategoryModal('fiche_reflexe', 'Fiches réflexes')">
                                    <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                        <i class="fas fa-lightbulb text-warning fa-lg mb-1"></i>
                                        <p class="fw-semibold text-warning mb-0 small">Fiches réflexes</p>
                                        <small class="text-warning" id="countFiche">0</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="cursor-pointer rounded-lg p-2 text-center border" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" onclick="openCategoryModal('ressource', 'Ressources')">
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
                                    <a class="btn w-100 py-2 btn-light text-[#255156]" style="background: #255156; color: white; border-radius: 10px;" href="#">
                                        <i class="fa fa-upload me-1"></i> Shéma
                                    </a>
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

                    <!-- GRILLE DES RESSOURCES -->
                    <div class="mt-3">
                        <div class="row g-3" id="resourcesGrid">
                            @forelse($resources as $resource)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 resource-card"
                                 data-id="{{ $resource->id }}"
                                 data-type="{{ $resource->is_link ? 'link' : ($resource->is_image ? 'image' : 'document') }}"
                                 data-category="{{ $resource->category }}"
                                 data-date="{{ $resource->created_at->timestamp }}"
                                 data-downloads="{{ $resource->download_count }}"
                                 data-title="{{ strtolower($resource->title) }}">
                                <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px;">
                                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-2 px-3">
                                        <div>
                                            @if($resource->is_link)
                                                <span class="badge" style="background: #e0f2fe; color: #0284c7;">
                                                    <i class="fas fa-link me-1"></i>Lien
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
                                        </div>
                                        <div>
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
                                    </div>  
                      
                                    <div class="card-body p-2 text-center">
                                        @if($resource->is_link)
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="height: 120px; cursor: pointer;"
                                                 onclick="window.open('{{ $resource->link_url }}', '_blank')">
                                                <div class="text-center">
                                                    <i class="fas fa-globe text-info fa-4x mb-2"></i>
                                                    <p class="small text-muted mb-0">Lien externe</p>
                                                </div>
                                            </div>
                                        @elseif($resource->is_image)
                                            <div class="bg-light rounded overflow-hidden" style="height: 120px; cursor: pointer;" onclick="openImageModal('{{ $resource->file_url }}', '{{ $resource->title }}')">
                                                <img src="{{ Storage::url($resource->file_path) }}" alt="{{ $resource->title }}" class="w-100 h-100" style="object-fit: cover;">
                                            </div>
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="height: 120px; cursor: pointer;"
                                                 onclick="window.open('{{ Storage::url($resource->file_path) }}', '_blank')">
                                                
                                                @php
                                                    $extension = strtolower($resource->file_type);
                                                @endphp
                                                
                                                @if(in_array($extension, ['pdf']))
                                                    <i class="bx bxs-file-pdf text-danger fa-4x"></i>
                                                @elseif(in_array($extension, ['doc', 'docx', 'odt']))
                                                    <i class="fas fa-file-word text-primary fa-4x"></i>
                                                @elseif(in_array($extension, ['xls', 'xlsx', 'csv']))
                                                    <i class="fas fa-file-excel text-success fa-4x"></i>
                                                @elseif(in_array($extension, ['ppt', 'pptx']))
                                                    <i class="fas fa-file-powerpoint text-warning fa-4x"></i>
                                                @elseif(in_array($extension, ['txt']))
                                                    <i class="fas fa-file-alt text-secondary fa-4x"></i>
                                                @elseif(in_array($extension, ['mp4', 'webm', 'avi', 'mov']))
                                                    <i class="fas fa-file-video text-danger fa-4x"></i>
                                                @else
                                                    <i class="fas fa-file text-secondary fa-4x"></i>
                                                @endif
                                                
                                                <div class="position-absolute bottom-0 end-0 bg-white rounded px-1 small" style="font-size: 10px;">
                                                    {{ strtoupper($extension) }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>                                    
                                    <div class="card-body pt-0 pb-2 px-3">
                                        <h6 class="fw-semibold mb-1 text-truncate" title="{{ $resource->title }}">{{ $resource->title }}</h6>
                                        @if($resource->description)
                                            <p class="small text-muted mb-2 text-truncate">{{ $resource->description }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between small text-muted mb-2">
                                            <span><i class="fas fa-download me-1"></i> {{ $resource->download_count }}</span>
                                            <span><i class="fas fa-calendar me-1"></i> {{ $resource->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="small text-muted mb-2">
                                            <i class="fas fa-user me-1"></i> 
                                            <strong>{{ $resource->user ? $resource->user->name : 'Utilisateur inconnu' }}</strong>
                                        </div>
                                        <div class="d-flex gap-1 justify-content-center pt-2 border-top">
                                            @if($resource->is_link)
                                                <a href="{{ $resource->link_url }}" target="_blank" class="btn btn-sm" style="background: #e0f2fe; color: #0284c7;">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif
                                            @if($resource->is_image)
                                                <button onclick="openImageModal('{{ $resource->file_url }}', '{{ $resource->title }}')" class="btn btn-sm" style="background: #f3e8ff; color: #9333ea;">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @endif
                                            @if(!$resource->is_link)
                                                <a href="{{ Storage::url($resource->file_path) }}" target="_blank" class="btn btn-sm" style="background: #e5e7eb; color: #4b5563;">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                                <a href="{{ route('resources.download', $resource) }}" class="btn btn-sm" style="background: #dbeafe; color: #2563eb;">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->role === 'admin' || auth()->user()->id === $resource->user_id)
                                                <button onclick="deleteResource({{ $resource->id }}, this)" class="btn btn-sm" style="background: #fee2e2; color: #dc2626;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune ressource disponible</p>
                                    <button onclick="openCreateModal()" class="btn" style="background: #255156; color: white;">
                                        <i class="fas fa-upload me-1"></i>Ajouter la première ressource
                                    </button>
                                </div>
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

<!-- MODALE DES RESSOURCES PAR CATÉGORIE -->
<div id="categoryModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header" style="background: #255156; color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="categoryModalTitle">
                    <i class="fas fa-folder-open me-2"></i>Catégorie
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="modalSearchInput" class="form-control border-start-0" 
                               placeholder="Rechercher dans cette catégorie...">
                    </div>
                </div>
                <div class="row g-3" id="modalResourcesGrid"></div>
                <div id="modalNoResults" class="text-center py-5 d-none">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune ressource dans cette catégorie</p>
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
                    
                    <!-- Choix du type de ressource -->
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
                    
                    <!-- Section upload de fichier -->
                    <div id="fileUploadSection" class="mb-3">
                        <label class="form-label fw-semibold">Fichier <span class="text-danger">*</span></label>
                        <input type="file" id="file" name="file" required class="form-control" accept=".pdf,.doc,.odt,.docx,.xls,.csv,.jpg,.jpeg,.png,.gif,.mp4,.webm,.avi">
                        <small class="text-muted">Formats acceptés: PDF, DOC, ODT, DOCX, JPG, PNG, GIF, MP4, Max 50Mo</small>
                    </div>
                    
                    <!-- Section lien externe -->
                    <div id="linkSection" class="mb-3 d-none">
                        <label class="form-label fw-semibold">URL du lien <span class="text-danger">*</span></label>
                        <input type="url" id="linkUrl" name="link_url" class="form-control" placeholder="https://exemple.com/document">
                        <small class="text-muted">Entrez l'URL complète du lien externe (YouTube, article, site web...)</small>
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

<!-- MODAL STATISTIQUES -->
<div id="statsModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header" style="background: #255156; color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title"><i class="fas fa-chart-pie me-2"></i>Statistiques</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-2 border rounded">
                            <small class="text-muted">Total</small>
                            <h5 class="mb-0 text-[#255156]" id="statTotal">0</h5>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 border rounded">
                            <small class="text-muted">Fichiers</small>
                            <h5 class="mb-0 text-purple-600" id="statFiles">0</h5>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 border rounded">
                            <small class="text-muted">Liens</small>
                            <h5 class="mb-0 text-blue-600" id="statLinks">0</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Données des ressources
const allResources = @json($resources->items());

// Variable pour stocker le type de ressource sélectionné (file ou link)
let selectedResourceType = 'file';

// Sélection du type de ressource
window.selectResourceType = function(type) {
    selectedResourceType = type;
    
    const btnFile = document.getElementById('btnFileType');
    const btnLink = document.getElementById('btnLinkType');
    const fileSection = document.getElementById('fileUploadSection');
    const linkSection = document.getElementById('linkSection');
    
    if (type === 'file') {
        btnFile.classList.add('active');
        btnLink.classList.remove('active');
        fileSection.classList.remove('d-none');
        linkSection.classList.add('d-none');
        document.getElementById('file').required = true;
        document.getElementById('linkUrl').required = false;
    } else {
        btnFile.classList.remove('active');
        btnLink.classList.add('active');
        fileSection.classList.add('d-none');
        linkSection.classList.remove('d-none');
        document.getElementById('file').required = false;
        document.getElementById('linkUrl').required = true;
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
    document.getElementById('countAll').textContent = counts.all;
    document.getElementById('countProcedure').textContent = counts.procedure;
    document.getElementById('countOutil').textContent = counts.outil;
    document.getElementById('countFiche').textContent = counts.fiche_reflexe;
    document.getElementById('countRessource').textContent = counts.ressource;
}

// Mise à jour des statistiques
function updateStats() {
    let fileCount = allResources.filter(r => !r.is_link).length;
    let linkCount = allResources.filter(r => r.is_link).length;
    document.getElementById('statTotal').textContent = allResources.length;
    document.getElementById('statFiles').textContent = fileCount;
    document.getElementById('statLinks').textContent = linkCount;
}

// Initialisation des modales
let categoryModal, imageModal, resourceModal, statsModal;

document.addEventListener('DOMContentLoaded', function() {
    categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
    imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    resourceModal = new bootstrap.Modal(document.getElementById('resourceModal'));
    statsModal = new bootstrap.Modal(document.getElementById('statsModal'));
    updateCategoryCounts();
    updateStats();
    
    // Recherche et filtrage
    const searchInput = document.getElementById('searchInput');
    const filterType = document.getElementById('filterType');
    
    function filterResources() {
        const searchTerm = searchInput.value.toLowerCase();
        const typeFilter = filterType.value;
        
        document.querySelectorAll('.resource-card').forEach(card => {
            const title = card.dataset.title || '';
            const type = card.dataset.type || '';
            let show = true;
            
            if (searchTerm && !title.includes(searchTerm)) {
                show = false;
            }
            
            if (typeFilter && type !== typeFilter) {
                show = false;
            }
            card.style.display = show ? '' : 'none';
        });
    } 
    searchInput.addEventListener('input', filterResources);
    filterType.addEventListener('change', filterResources);
});

window.openCategoryModal = function(category, title) {
    let filtered = category === 'all' ? [...allResources] : allResources.filter(r => r.category === category);
    document.getElementById('categoryModalTitle').innerHTML = `<i class="fas fa-folder-open me-2"></i>${title} (${filtered.length})`;
    
    const grid = document.getElementById('modalResourcesGrid');
    const noResults = document.getElementById('modalNoResults');
    
    function renderResources(resources) {
        if (resources.length === 0) {
            grid.innerHTML = '';
            noResults.classList.remove('d-none');
            return;
        }
        noResults.classList.add('d-none');
        
        grid.innerHTML = resources.map(r => {
            let contentHtml = '';
            
            // Déterminer l'URL correcte
            let itemUrl = '';
            if (r.is_link) {
                itemUrl = r.link_url;
            } else if (r.file_url) {
                itemUrl = r.file_url;
            } else if (r.url) {
                itemUrl = r.url;
            } else {
                itemUrl = '#';
            }
            
            // Récupérer l'extension du fichier
            const fileType = r.file_type || '';
            const extension = fileType.toLowerCase();
            
            // Contenu selon le type
            if (r.is_link) {
                contentHtml = `
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px; cursor: pointer;" onclick="window.open('${r.link_url}', '_blank')">
                        <div class="text-center">
                            <i class="fas fa-globe text-info fa-4x mb-2"></i>
                            <p class="small text-muted mb-0">Lien externe</p>
                        </div>
                    </div>
                `;
            } else if (r.is_image) {
                const imgUrl = r.file_url || r.url;
                contentHtml = `
                    <div class="bg-light rounded overflow-hidden" style="height: 120px; cursor: pointer;" onclick="openImageModal('${imgUrl}', '${escapeHtml(r.title)}')">
                        <img src="${imgUrl}" alt="${escapeHtml(r.title)}" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                `;
            } else {
                let iconHtml = 'fa-file';
                let iconColor = 'text-secondary';
                
                if (extension === 'pdf') {
                    iconHtml = 'bx bxs-file-pdf';
                    iconColor = 'text-danger';
                } else if (['doc', 'docx', 'odt'].includes(extension)) {
                    iconHtml = 'fa-file-word';
                    iconColor = 'text-primary';
                } else if (['xls', 'xlsx', 'csv'].includes(extension)) {
                    iconHtml = 'fa-file-excel';
                    iconColor = 'text-success';
                } else if (['ppt', 'pptx'].includes(extension)) {
                    iconHtml = 'fa-file-powerpoint';
                    iconColor = 'text-warning';
                } else if (['mp4', 'webm', 'avi', 'mov'].includes(extension)) {
                    iconHtml = 'fa-file-video';
                    iconColor = 'text-danger';
                } else if (extension === 'txt') {
                    iconHtml = 'fa-file-alt';
                    iconColor = 'text-secondary';
                }
                
                contentHtml = `
                    <div class="bg-light rounded d-flex align-items-center justify-content-center position-relative" style="height: 120px; cursor: pointer;" onclick="window.open('${itemUrl}', '_blank')">
                        <i class="fas ${iconHtml} ${iconColor} fa-4x"></i>
                        <div class="position-absolute bottom-0 end-0 bg-white rounded px-1 small" style="font-size: 10px;">
                            ${extension.toUpperCase()}
                        </div>
                    </div>
                `;
            }
            
            // Nom de l'utilisateur
            let userName = 'Utilisateur inconnu';
            if (r.user) {
                userName = r.user.name || r.user.prenom || 'Utilisateur';
            }
            
            return `
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border shadow-sm" style="border-radius: 12px;">
                        <div class="card-header bg-transparent d-flex justify-content-between">
                            <small class="badge" style="background: #25515620; color: #255156;">${r.category === 'procedure' ? 'Procédure' : (r.category === 'outil' ? 'Outil' : (r.category === 'fiche_reflexe' ? 'Fiche réflexe' : 'Ressource'))}</small>
                        </div>
                        <div class="card-body text-center">
                            ${contentHtml}
                            <h6 class="mt-2 fw-semibold text-truncate" title="${escapeHtml(r.title)}">${escapeHtml(r.title)}</h6>
                            ${r.description ? `<p class="small text-muted mb-2 text-truncate">${escapeHtml(r.description)}</p>` : ''}
                            <div class="d-flex justify-content-between small text-muted mt-2">
                                <span><i class="fas fa-download me-1"></i> ${r.download_count || 0}</span>
                                <span><i class="fas fa-calendar me-1"></i> ${r.created_at ? r.created_at.split('T')[0] : ''}</span>
                            </div>
                            <div class="small text-muted mt-1">
                                <i class="fas fa-user me-1"></i> 
                                <strong>${escapeHtml(userName)}</strong>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent d-flex justify-content-center gap-1">
                            <button class="btn btn-sm btn-outline-secondary" onclick="window.open('${r.is_link ? r.link_url : itemUrl}', '_blank')"><i class="fas fa-eye"></i></button>
                            ${!r.is_link ? `<a href="/resources/${r.id}/download" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></a>` : ''}
                        </div>
                        
                    </div>
                </div>
            `;
        }).join('');
    }
    
    renderResources(filtered);
    
    const searchInput = document.getElementById('modalSearchInput');
    searchInput.value = '';
    searchInput.oninput = function() {
        const term = this.value.toLowerCase();
        const filtered2 = filtered.filter(r => r.title.toLowerCase().includes(term) || (r.description && r.description.toLowerCase().includes(term)));
        renderResources(filtered2);
    };
    
    categoryModal.show();
};

window.openCreateModal = function() {
    document.getElementById('resourceForm').reset();
    document.getElementById('fileUploadSection').classList.remove('d-none');
    document.getElementById('linkSection').classList.add('d-none');
    document.getElementById('btnFileType').classList.add('active');
    document.getElementById('btnLinkType').classList.remove('active');
    selectedResourceType = 'file';
    document.getElementById('file').required = true;
    document.getElementById('linkUrl').required = false;
    resourceModal.show();
};

window.openImageModal = function(url, title) {
    document.getElementById('modalImage').src = url;
    imageModal.show();
};

window.deleteResource = function(id, btn) {
    if (!confirm('Supprimer cette ressource ?')) return;
    fetch(`/resources/${id}`, {
        method: 'DELETE',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
            'Accept': 'application/json'
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
    statsModal.show(); 
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

// Validation avant soumission
document.getElementById('resourceForm').addEventListener('submit', function(e) {
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
</script>

<style>
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
@endsection