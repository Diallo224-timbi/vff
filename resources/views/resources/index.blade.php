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
                <!--modal message de succes et d'erreur-->
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
                <div class="card-body p-3">
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
                                    <option value="video">Vidéos</option>
                                    <option value="document">Documents</option>
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
                                 data-type="{{ $resource->is_image ? 'image' : ($resource->is_video ? 'video' : 'document') }}"
                                 data-category="{{ $resource->category }}"
                                 data-date="{{ $resource->created_at->timestamp }}"
                                 data-downloads="{{ $resource->download_count }}"
                                 data-title="{{ strtolower($resource->title) }}">
                                <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px;">
                                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-2 px-3">
                                        <div>
                                            @if($resource->is_image)
                                                <span class="badge" style="background: #f3e8ff; color: #9333ea;">
                                                    <i class="fas fa-image me-1"></i>Image
                                                </span>
                                            @elseif($resource->is_video)
                                                <span class="badge" style="background: #fee2e2; color: #dc2626;">
                                                    <i class="fas fa-video me-1"></i>Vidéo
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
                                        @if($resource->is_image)
                                            <div class="bg-light rounded overflow-hidden" style="height: 120px; cursor: pointer;" onclick="openImageModal('{{ $resource->file_url }}', '{{ $resource->file_title }}')">
                                                    <img src="{{ Storage::url($resource->file_path) }}" alt="{{ $resource->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                </div>
                                        @elseif($resource->is_video)
                                            <div class="bg-dark rounded overflow-hidden position-relative" style="height: 120px; cursor: pointer;" onclick="openVideoModal('{{ Storage::url($resource->file_path) }}', '{{ $resource->file_title }}')">
                                                <video class="w-100 h-100" style="object-fit: cover; opacity: 0.5;">
                                                    <source src="{{ asset($resource->file_path) }}" type="video/mp4">
                                                </video>
                                                <div class="position-absolute top-50 start-50 translate-middle">
                                                    <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-play text-white"></i>
                                                    </div>
                                                </div>
                                                </div>
                                           @elseif($resource->file_type === 'pdf')
<div class="bg-light rounded d-flex align-items-center justify-content-center"
     style="height: 120px; cursor: pointer;"
     onclick="window.open('{{ Storage::url($resource->file_path) }}', '_blank')">

    <canvas id="pdf-thumb-{{ $resource->id }}" style="width:50%; height:150px;"></canvas>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

    <script>
        const url = "{{ Storage::url($resource->file_path) }}";

        const loadingTask = pdfjsLib.getDocument(url);
        loadingTask.promise.then(function(pdf) {

            pdf.getPage(1).then(function(page) {
                const canvas = document.getElementById("pdf-thumb-{{ $resource->id }}");
                const context = canvas.getContext('2d');

                const viewport = page.getViewport({ scale: 0.3 });

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
            });

        });
    </script>
</div>


    @elseif(in_array($resource->file_type, ['doc', 'docx', 'odt']))
        <div class="bg-light rounded d-flex align-items-center justify-content-center"
            style="height: 120px; cursor: pointer;"
            onclick="window.open('{{ Storage::url($resource->file_path) }}', '_blank')">

            <i class="fas fa-file-word text-primary fa-4x"></i>
        </div>

                        @elseif(in_array($resource->file_type, ['xls', 'xlsx', 'csv']))
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="height: 120px; cursor: pointer;"
                                onclick="window.open('{{ Storage::url($resource->file_path) }}', '_blank')">

                                <i class="fas fa-file-excel text-success fa-4x"></i>
                            </div>

                        @elseif(in_array($resource->file_type, ['ppt', 'pptx']))
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="height: 120px; cursor: pointer;"
                                onclick="window.open('{{ Storage::url($resource->file_path) }}', '_blank')">

                                <i class="fas fa-file-powerpoint text-warning fa-4x"></i>
                            </div>

                        @elseif($resource->file_type === 'txt')
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="height: 120px; cursor: pointer;"
                                onclick="window.open('{{ Storage::url($resource->file_path) }}', '_blank')">

                                <i class="fas fa-file-alt text-secondary fa-4x"></i>
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
                                        <div class="d-flex gap-1 justify-content-center pt-2 border-top">
                                            @if($resource->is_video)
                                                <button onclick="openVideoModal('{{ $resource->url }}', '{{ $resource->title }}')" class="btn btn-sm" style="background: #fee2e2; color: #dc2626;">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            @endif
                                            @if($resource->is_image)
                                                <button onclick="openImageModal('{{ $resource->url }}', '{{ $resource->title }}')" class="btn btn-sm" style="background: #f3e8ff; color: #9333ea;">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @endif
                                            <a href="{{ $resource->url }}" target="_blank" class="btn btn-sm" style="background: #e5e7eb; color: #4b5563;">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <a href="{{ route('resources.download', $resource) }}" class="btn btn-sm" style="background: #dbeafe; color: #2563eb;">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @if(auth()->user()->role === 'admin' || auth()->user()->id === $resource->user_id)
                                                <button onclick="openEditModal({{ $resource->id }})" class="btn btn-sm" style="background: #fef3c7; color: #d97706;">
                                                    <i class="fas fa-edit"></i>
                                                </button>
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

<!-- MODAL VIDÉO -->
<div id="videoModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-body p-0">
                <video id="modalVideo" controls class="w-100" style="max-height: 80vh;">
                    <source src="" type="video/mp4">
                </video>
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

<!-- MODAL CRÉATION/MODIFICATION -->
<div id="resourceModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header" style="background: #255156; color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="modalTitle">Ajouter une ressource</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="resourceForm" enctype="multipart/form-data" action="{{ route('resources.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="resourceId" name="id">
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" required class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea id="description" name="description" rows="3" class="form-control"></textarea>
                    </div>
                    
                    <div id="fileUploadSection" class="mb-3">
                        <label class="form-label fw-semibold">Fichier <span class="text-danger">*</span></label>
                        <input required type="file" id="file" name="file" class="form-control" accept=".pdf,.doc,.odt,.docx,.xls,.csv,.jpg,.jpeg,.png,.gif,.webm,.avi">
                        <small class="text-muted">Formats acceptés: PDF, DOC, ODT, DOCX, JPG, PNG, GIF, Max 50Mo</small>
                    </div>
                    
                    <div id="currentFileSection" class="mb-3 d-none">
                        <label class="form-label fw-semibold">Fichier actuel</label>
                        <p class="form-control-plaintext" id="currentFileName"></p>
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
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service</label>
                        <input type="text" id="service" name="service" class="form-control">
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
                    <div class="col-3">
                        <div class="p-2 border rounded">
                            <small class="text-muted">Total</small>
                            <h5 class="mb-0 text-[#255156]">{{ $resources->total() }}</h5>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 border rounded">
                            <small class="text-muted">Images</small>
                            <h5 class="mb-0 text-purple-600">{{ $stats['images'] }}</h5>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 border rounded">
                            <small class="text-muted">Vidéos</small>
                            <h5 class="mb-0 text-red-600">{{ $stats['videos'] }}</h5>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 border rounded">
                            <small class="text-muted">Docs</small>
                            <h5 class="mb-0 text-blue-600">{{ $stats['documents'] }}</h5>
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

    // Initialisation des modales
    let categoryModal, videoModal, imageModal, resourceModal, statsModal;

    document.addEventListener('DOMContentLoaded', function() {
        categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
        videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
        imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        resourceModal = new bootstrap.Modal(document.getElementById('resourceModal'));
        statsModal = new bootstrap.Modal(document.getElementById('statsModal'));
        updateCategoryCounts();
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
            grid.innerHTML = resources.map(r => `
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border shadow-sm" style="border-radius: 12px;">
                        <div class="card-header bg-transparent d-flex justify-content-between">
                            <small class="badge bg-secondary">${r.is_image ? 'Image' : (r.is_video ? 'Vidéo' : 'Document')}</small>
                            <small class="badge" style="background: #25515620; color: #255156;">${r.category === 'procedure' ? 'Procédure' : (r.category === 'outil' ? 'Outil' : (r.category === 'fiche_reflexe' ? 'Fiche réflexe' : 'Ressource'))}</small>
                        </div>
                        <div class="card-body text-center">
                            ${r.is_image ? `<img src="${r.url}" class="img-fluid rounded" style="height: 100px; object-fit: cover; cursor: pointer;" onclick="openImageModal('${r.url}', '${r.title}')">` : 
                            (r.is_video ? `<div class="bg-dark rounded d-flex align-items-center justify-content-center" style="height: 100px; cursor: pointer;" onclick="openVideoModal('${r.url}', '${r.title}')">
                                <i class="fas fa-play-circle text-white fa-3x"></i>
                            </div>` :
                            `<i class="bx bxs-file text-danger fa-4x"></i>`)}
                            <h6 class="mt-2 fw-semibold">${escapeHtml(r.title)}</h6>
                            <small class="text-muted">${r.created_at?.split('T')[0] || ''}</small>
                        </div>
                        <div class="card-footer bg-transparent d-flex justify-content-center gap-1">
                            <button class="btn btn-sm btn-outline-secondary" onclick="window.open('${r.url}', '_blank')"><i class="fas fa-eye"></i></button>
                            <a href="/resources/${r.id}/download" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></a>
                        </div>
                    </div>
                </div>
            `).join('');
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
        document.getElementById('modalTitle').textContent = 'Ajouter une ressource';
        document.getElementById('resourceForm').reset();
        document.getElementById('resourceId').value = '';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('fileUploadSection').classList.remove('d-none');
        document.getElementById('currentFileSection').classList.add('d-none');
        resourceModal.show();
    };

    window.openEditModal = function(id) {
        fetch(`/ressources/${id}/edit`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('resourceForm').action = `/resources/${data.id}`;
                document.getElementById('modalTitle').textContent = 'Modifier la ressource';
                document.getElementById('resourceId').value = data.id;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('title').value = data.title || '';
                document.getElementById('description').value = data.description || '';
                document.getElementById('category').value = data.category || 'procedure';
                document.getElementById('service').value = data.service || '';
                document.getElementById('currentFileName').textContent = data.file_name || 'Aucun fichier';
                document.getElementById('fileUploadSection').classList.add('d-none');
                document.getElementById('currentFileSection').classList.remove('d-none');
                resourceModal.show();
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors du chargement des données');
            });
    };

    window.openVideoModal = function(url, title) {
        const video = document.getElementById('modalVideo');
        const source = video.querySelector('source');
        source.src = url;
        video.load();
        videoModal.show();
    };

    window.openImageModal = function(url, title) {
        document.getElementById('modalImage').src = url;
        imageModal.show();
    };

    window.deleteResource = function(id, btn) {
        if (!confirm('Supprimer cette ressource ?')) return;
        fetch(`/ressources/${id}`, {
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

    // Soumission du formulaire avec soumission normale (pas fetch)
    document.getElementById('resourceForm').addEventListener('submit', function(e) {
        // On laisse le formulaire s'envoyer normalement
        // Pas de e.preventDefault() pour que le formulaire s'envoie normalement
        // Le serveur doit rediriger vers la page actuelle avec un message de succès
    });
    //notification pour les messages flash
    document.addEventListener('DOMContentLoaded', function() {
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
        
    });
</script>
<style>
.object-fit-cover { object-fit: cover; }
.cursor-pointer { cursor: pointer; }
.bg-\[#255156\] { background-color: #255156; }
.text-\[\#255156\] { color: #255156; }
</style>
@endsection