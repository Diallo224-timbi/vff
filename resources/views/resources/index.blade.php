@extends('base')

@section('title', 'Espace documentaire')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Carte principale -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <!-- En-tête -->
                <div class="card-header text-white py-3" style="background: #145f68; border: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-folder-open me-2"></i>
                            <h4 class="d-inline-block mb-0 fw-bold">Espace documentaire</h4>
                            <div class="alert alert-light mt-3 shadow-sm border-0">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <strong>Rappel :</strong>
                                Partagez des ressources professionnelles dans un esprit de bienveillance et de collaboration,
                                tout en respectant les dispositions de la charte de la plateforme.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
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

                    <!-- FILTRES PAR CATÉGORIE -->
                    <div class="mb-4">
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
                                    <a href="{{ route('resources.trash') }}" class="btn w-100 py-2" style="background: #255156; color: white; border-radius: 10px;">
                                        <i class="fa fa-trash me-1"></i> Corbeille
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Barre de recherche -->
                    <div class="mb-4">
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

                    <!-- GRILLE DE CARTES - 5 par ligne -->
                    <div class="mt-3">
                        <div class="row g-3" id="resourcesGrid">
                            @forelse($resources as $resource)
                            <div class="col-md-6 col-lg-2 col-xl-2 resource-card"
                                 data-id="{{ $resource->id }}"
                                 data-type="{{ $resource->is_link ? 'link' : ($resource->is_image ? 'image' : 'document') }}"
                                 data-category="{{ $resource->category }}"
                                 data-date="{{ $resource->created_at->timestamp }}"
                                 data-downloads="{{ $resource->download_count }}"
                                 data-title="{{ strtolower($resource->title) }}">
                                <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; overflow: hidden; transition: transform 0.2s;">
                                    <!-- Zone image / icône -->
                                    <div style="height: 140px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; position: relative;">
                                        @if($resource->is_image)
                                            <img src="{{ Storage::url($resource->file_path) }}" alt="{{ $resource->title }}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openImageModal('{{ $resource->file_url }}', '{{ $resource->title }}')">
                                        @elseif($resource->is_link)
                                            <i class="fas fa-link" style="font-size: 3.5rem; color: #0d6efd;"></i>
                                        @else
                                            @php
                                                $extension = strtolower($resource->file_type);
                                            @endphp
                                            @if(in_array($extension, ['pdf']))
                                                <i class="bx bxs-file-pdf text-danger" style="font-size: 3.5rem;"></i>
                                            @elseif(in_array($extension, ['doc', 'docx', 'odt']))
                                                <i class="fas fa-file-word text-primary" style="font-size: 3.5rem;"></i>
                                            @elseif(in_array($extension, ['xls', 'xlsx', 'csv']))
                                                <i class="fas fa-file-excel text-success" style="font-size: 3.5rem;"></i>
                                            @elseif(in_array($extension, ['ppt', 'pptx']))
                                                <i class="fas fa-file-powerpoint text-warning" style="font-size: 3.5rem;"></i>
                                            @elseif(in_array($extension, ['txt']))
                                                <i class="fas fa-file-alt text-secondary" style="font-size: 3.5rem;"></i>
                                            @elseif(in_array($extension, ['webm', 'avi', 'mov']))
                                                <i class="fas fa-file-video text-danger" style="font-size: 3.5rem;"></i>
                                            @else
                                                <i class="fas fa-file text-secondary" style="font-size: 3.5rem;"></i>
                                            @endif
                                        @endif

                                        <!-- Badges superposés -->
                                        <div style="position: absolute; top: 8px; right: 8px; display: flex; flex-direction: column; gap: 4px;">
                                            @if($resource->is_link)
                                                <span class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 0.65rem;">
                                                    <i class="fas fa-link"></i> Lien
                                                </span>
                                            @elseif($resource->is_image)
                                                <span class="badge" style="background: #f3e8ff; color: #9333ea; font-size: 0.65rem;">
                                                    <i class="fas fa-image me-1"></i>Image
                                                </span>
                                            @else
                                                <span class="badge" style="background: #dbeafe; color: #2563eb; font-size: 0.65rem;">
                                                    <i class="fas fa-file me-1"></i>{{ strtoupper($resource->file_type) }}
                                                </span>
                                            @endif

                                            @if($resource->category == 'procedure')
                                                <span class="badge" style="background: #dbeafe; color: #2563eb; font-size: 0.65rem;">Procédure</span>
                                            @elseif($resource->category == 'outil')
                                                <span class="badge" style="background: #dcfce7; color: #16a34a; font-size: 0.65rem;">Outil</span>
                                            @elseif($resource->category == 'fiche_reflexe')
                                                <span class="badge" style="background: #fef3c7; color: #d97706; font-size: 0.65rem;">Fiche réflexe</span>
                                            @else
                                                <span class="badge" style="background: #f3e8ff; color: #9333ea; font-size: 0.65rem;">Ressource</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Corps de la carte -->
                                    <div class="card-body p-2">
                                        <h6 class="card-title fw-semibold mb-1 small" title="{{ $resource->title }}">{{ Str::limit($resource->title, 25) }}</h6>
                                        @if($resource->description)
                                            <p class="card-text small text-muted mb-1">{{ Str::limit($resource->description, 50) }}</p>
                                        @endif
                                        <div class="d-flex flex-wrap gap-1 small text-muted" style="font-size: 0.7rem;">
                                            @if(!$resource->is_link)
                                                <span><i class="fas fa-download me-1"></i> {{ $resource->download_count }}</span>
                                            @endif
                                            <span><i class="fas fa-calendar me-1"></i> {{ $resource->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    <!-- Pied de carte avec actions -->
                                    <div class="card-footer bg-transparent border-0 pt-0 pb-2">
                                        <div class="d-flex gap-1 justify-content-end flex-wrap">
                                            @if($resource->is_link)
                                                <a href="{{ $resource->link_url }}" target="_blank" class="btn btn-sm" style="background: #e0f2fe; color: #0284c7; padding: 2px 6px; font-size: 0.7rem;" title="Ouvrir le lien">
                                                    <i class="fas fa-link"></i>
                                                </a>
                                            @endif

                                            @if($resource->is_image)
                                                <button onclick="openImageModal('{{ $resource->file_url }}', '{{ $resource->title }}')" class="btn btn-sm" style="background: #f3e8ff; color: #9333ea; padding: 2px 6px; font-size: 0.7rem;" title="Voir l'image">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @endif

                                            @if(!$resource->is_link)
                                                <a href="{{ Storage::url($resource->file_path) }}" target="_blank" class="btn btn-sm" style="background: #e5e7eb; color: #4b5563; padding: 2px 6px; font-size: 0.7rem;" title="Ouvrir le fichier">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                                <a href="{{ route('resources.download', $resource) }}" class="btn btn-sm" style="background: #dbeafe; color: #2563eb; padding: 2px 6px; font-size: 0.7rem;" title="Télécharger">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif

                                            @if(auth()->user()->role === 'admin' || auth()->user()->id === $resource->user_id)
                                                <a href="{{ route('resources.edit', $resource) }}" class="btn btn-sm" style="background: #c7d2fe; color: #3730a3; padding: 2px 6px; font-size: 0.7rem;" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="deleteResource({{ $resource->id }}, this)" class="btn btn-sm" style="background: #fee2e2; color: #dc2626; padding: 2px 6px; font-size: 0.7rem;" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune ressource disponible</p>
                                <button onclick="openCreateModal()" class="btn" style="background: #255156; color: white;">
                                    <i class="fas fa-upload me-1"></i>Ajouter la première ressource
                                </button>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    <!-- Pagination -->
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
    /* Effet hover sur les cartes */
    .resource-card .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .resource-card .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
    }

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

    .object-fit-cover { object-fit: cover; }
    .cursor-pointer { cursor: pointer; }
    .btn-group .btn.active {
        background-color: #255156;
        color: white;
        border-color: #255156;
    }

    /* Ajustement pour les très petits écrans */
    @media (max-width: 576px) {
        .resource-card .card-body {
            padding: 0.5rem !important;
        }
        .resource-card .card-footer {
            padding: 0.25rem 0.5rem !important;
        }
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
            clearCategoryFilter();
        } else {
            currentCategoryFilter = category;
            document.querySelectorAll('.filter-category').forEach(el => {
                el.classList.toggle('active', el.getAttribute('data-category') === category);
            });
            const filterLabel = document.getElementById('filterLabel');
            if (filterLabel) filterLabel.innerHTML = `<i class="fas fa-filter me-2"></i>Filtre actif : ${label}`;
            const activeFilter = document.getElementById('activeFilter');
            if (activeFilter) activeFilter.classList.remove('d-none');
            filterResourcesByCategory();
        }
    };

    window.clearCategoryFilter = function() {
        currentCategoryFilter = null;
        document.querySelectorAll('.filter-category').forEach(el => el.classList.remove('active'));
        const activeFilter = document.getElementById('activeFilter');
        if (activeFilter) activeFilter.classList.add('d-none');
        filterResourcesByCategory();
    };

    function filterResourcesByCategory() {
        const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const typeFilter = document.getElementById('filterType')?.value || '';

        document.querySelectorAll('.resource-card').forEach(card => {
            const category = card.dataset.category || '';
            const title = card.dataset.title || '';
            const type = card.dataset.type || '';
            let show = true;

            if (currentCategoryFilter && currentCategoryFilter !== 'all' && category !== currentCategoryFilter) show = false;
            if (show && searchTerm && !title.includes(searchTerm)) show = false;
            if (show && typeFilter && type !== typeFilter) show = false;

            card.style.display = show ? '' : 'none';
        });
    }

    window.selectResourceType = function(type) {
        selectedResourceType = type;
        const btnFile = document.getElementById('btnFileType');
        const btnLink = document.getElementById('btnLinkType');
        const fileSection = document.getElementById('fileUploadSection');
        const linkSection = document.getElementById('linkSection');
        const fileInput = document.getElementById('file');
        const linkUrlInput = document.getElementById('linkUrl');

        if (type === 'file') {
            btnFile?.classList.add('active');
            btnLink?.classList.remove('active');
            fileSection?.classList.remove('d-none');
            linkSection?.classList.add('d-none');
            if (fileInput) fileInput.required = true;
            if (linkUrlInput) linkUrlInput.required = false;
        } else {
            btnFile?.classList.remove('active');
            btnLink?.classList.add('active');
            fileSection?.classList.add('d-none');
            linkSection?.classList.remove('d-none');
            if (fileInput) fileInput.required = false;
            if (linkUrlInput) linkUrlInput.required = true;
        }
    };

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

    let imageModal, resourceModal;

    document.addEventListener('DOMContentLoaded', function() {
        const imageModalEl = document.getElementById('imageModal');
        const resourceModalEl = document.getElementById('resourceModal');

        if (imageModalEl) imageModal = new bootstrap.Modal(imageModalEl);
        if (resourceModalEl) resourceModal = new bootstrap.Modal(resourceModalEl);

        updateCategoryCounts();

        const searchInput = document.getElementById('searchInput');
        const filterType = document.getElementById('filterType');

        if (searchInput && filterType) {
            searchInput.addEventListener('input', filterResourcesByCategory);
            filterType.addEventListener('change', filterResourcesByCategory);
        }
    });

    window.openCreateModal = function() {
        document.getElementById('resourceForm').reset();
        document.getElementById('fileUploadSection').classList.remove('d-none');
        document.getElementById('linkSection').classList.add('d-none');
        document.getElementById('btnFileType').classList.add('active');
        document.getElementById('btnLinkType').classList.remove('active');
        selectedResourceType = 'file';
        document.getElementById('file').required = true;
        document.getElementById('linkUrl').required = false;
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

    document.getElementById('resourceForm')?.addEventListener('submit', function(e) {
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
@endsection