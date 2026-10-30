@extends('base')
@section('title', 'Modifier la ressource')
@section('content')
<div class="modal fade" id="resourceEditModal" tabindex="-1" aria-labelledby="resourceEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable resource-edit-dialog">
        <form class="modal-content resource-edit-modal-content" action="{{ route('resources.update', $resource) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header text-white resource-edit-modal-header">
                <h1 class="modal-title fs-5" id="resourceEditModalLabel">
                    <i class="fas fa-edit me-2" aria-hidden="true"></i>Modifier la ressource
                </h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body resource-edit-modal-body">
                    <div class="row g-2">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                            <input type="text" 
                                name="title" 
                                value="{{ old('title', $resource->title) }}" 
                                required 
                                class="form-control @error('title') is-invalid @enderror">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> 
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Description</label>
                            <input type="text"
                                   name="description"
                                   value="{{ old('description', $resource->description) }}"
                                   class="form-control @error('description') is-invalid @enderror">
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                            <select name="category" required class="form-select @error('category') is-invalid @enderror">
                                <option value="">Sélectionner une catégorie</option>
                                <option value="guides_etudes" {{ old('category', $resource->category) == 'guides_etudes' ? 'selected' : '' }}>Guides & Études violences faites aux femmes</option>
                                <option value="affiches_flyers" {{ old('category', $resource->category) == 'affiches_flyers' ? 'selected' : '' }}>Affiches et flyers des partenaires</option>
                                <option value="reseaux" {{ old('category', $resource->category) == 'reseaux' ? 'selected' : '' }}>Réseaux violences conjugales et VIF du département</option>
                                <option value="sensibilisation" {{ old('category', $resource->category) == 'sensibilisation' ? 'selected' : '' }}>Catalogues de sensibilisations & formations des partenaires</option>
                                <option value="outils" {{ old('category', $resource->category) == 'outils' ? 'selected' : '' }}>Outils</option>
                                <option value="conventions" {{ old('category', $resource->category) == 'conventions' ? 'selected' : '' }}>Conventions, protocoles & dispositif</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6" id="editSubCategoryContainer">
                            <label class="form-label fw-semibold">Sous-catégorie <span class="text-danger">*</span></label>
                            <select id="editSubCategory" name="sub_category" class="form-select">
                                <option value="">Aucune</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="resource-type-heading">
                                <label class="form-label fw-semibold mb-0">Type de ressource <span class="text-danger">*</span></label>
                                @if(!$resource->is_link && $resource->file_path)
                                    <span class="resource-current-file" title="{{ $resource->file_name }}">
                                        <i class="fas fa-paperclip" aria-hidden="true"></i>
                                        <strong>Fichier actuel :</strong>
                                        <a href="{{ Storage::url($resource->file_path) }}" target="_blank" rel="noopener noreferrer">
                                            {{ $resource->file_name }}
                                        </a>
                                    </span>
                                @endif
                            </div>
                            <div class="btn-group w-100" role="group">
                                <button type="button" id="editBtnFileType" class="btn btn-outline-primary {{ !$resource->is_link ? 'active' : '' }}" onclick="selectEditResourceType('file')">
                                    <i class="fas fa-upload me-1"></i> Fichier
                                </button>
                                <button type="button" id="editBtnLinkType" class="btn btn-outline-primary {{ $resource->is_link ? 'active' : '' }}" onclick="selectEditResourceType('link')">
                                    <i class="fas fa-link me-1"></i> Lien externe
                                </button>
                            </div>
                        </div>
                        <!-- Section Fichier -->
                        <div id="editFileUploadSection" class="col-12 {{ $resource->is_link ? 'd-none' : '' }}">
                            <label class="form-label fw-semibold">
                                {{ !$resource->is_link && $resource->file_path ? 'Remplacer le fichier' : 'Fichier' }}
                                @if(!$resource->file_path) <span class="text-danger">*</span> @endif
                            </label>
                            <input type="file" id="editFile" name="file" class="form-control" accept=".pdf,.doc,.odt,.docx,.xls,.csv,.jpg,.jpeg,.png,.gif,.webm,.avi">
                            <small class="text-muted">Formats acceptés: PDF, DOC, ODT, DOCX, JPG, PNG, GIF, Max 50Mo</small>
                        </div>
                        <!-- Section Lien -->
                        <div id="editLinkSection" class="col-12 col-md-6 {{ !$resource->is_link ? 'd-none' : '' }}">
                            <label class="form-label fw-semibold">URL du lien <span class="text-danger">*</span></label>
                            <input type="url" id="edit_link_url" name="link_url" class="form-control" placeholder="https://exemple.com/document" value="{{ old('link_url', $resource->link_url) }}">
                            <small class="text-muted">Entrez l'URL complète du lien externe</small>
                        </div>
                        @if(auth()->user()->role === 'admin')
                        <div class="col-12 form-check">
                            <input type="checkbox" class="form-check-input" id="editImportant" name="important" value="1" {{ old('important', $resource->important) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="editImportant">Marquer comme ressource importante</label>
                        </div>
                        @endif                    
                    </div>
                </div>
                <div class="modal-footer resource-edit-modal-footer">
                    <a href="{{ route('resources.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1" aria-hidden="true"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary resource-edit-submit">
                        <i class="fas fa-save me-1" aria-hidden="true"></i>Mettre à jour
                    </button>
            </div>
        </form>
    </div>
</div>
<script>
// Map des sous-catégories (reprise exacte du formulaire d'ajout)
const subCategoriesMap = {
    'guides_etudes': [
        { value: 'national', label: 'National' },
        { value: 'departemental', label: 'Départemental' },
        { value: 'autres', label: 'Autres' }
    ],
    'affiches_flyers': [
        { value: 'victimes', label: 'Victimes' },
        { value: 'auteurs', label: 'Auteurs' },
        { value: 'autres', label: 'Autres' }
    ],
    'reseaux': [
        { value: 'guides', label: 'Guides' },
        { value: 'kit_creation', label: 'Kit création réseau' },
        { value: 'autres', label: 'Autres' }
    ],
    'outils': [
        { value: 'coordination', label: 'Coordination acteurs' },
        { value: 'prevention', label: 'Prévention & sensibilisation' },
        { value: 'autres', label: 'Autres' }
    ],
    'conventions': [
        { value: 'victimes', label: 'Victimes' },
        { value: 'auteurs', label: 'Auteurs' },
        { value: 'autres', label: 'Autres' }
    ]
};

// Sélection du type actuel pour l'édition
let selectedEditResourceType = '{{ $resource->is_link ? "link" : "file" }}';

function selectEditResourceType(type) {
    selectedEditResourceType = type;
    const btnFile = document.getElementById('editBtnFileType');
    const btnLink = document.getElementById('editBtnLinkType');
    const fileSection = document.getElementById('editFileUploadSection');
    const linkSection = document.getElementById('editLinkSection');
    const fileInput = document.getElementById('editFile');
    const linkUrlInput = document.getElementById('edit_link_url');
    if (type === 'file') {
        btnFile?.classList.add('active');
        btnLink?.classList.remove('active');
        fileSection?.classList.remove('d-none');
        linkSection?.classList.add('d-none');
        if (fileInput) fileInput.required = false; // Non requis s'il y a déjà un fichier
        if (linkUrlInput) linkUrlInput.required = false;
    } else {
        btnFile?.classList.remove('active');
        btnLink?.classList.add('active');
        fileSection?.classList.add('d-none');
        linkSection?.classList.remove('d-none');
        if (fileInput) fileInput.required = false;
        if (linkUrlInput) linkUrlInput.required = true;
    }
}

// Fonction pour mettre à jour les sous-catégories comme dans l'ajout
function updateEditSubCategories(category) {
    const subSelect = document.getElementById('editSubCategory');
    subSelect.innerHTML = '<option value="">Aucune</option>';
    
    if (category && subCategoriesMap[category]) {
        // On garde la sélection de la sous-catégorie existante
        const currentSubCat = '{{ old('sub_category', $resource->sub_category) }}';
        
        subCategoriesMap[category].forEach(sub => {
            const option = document.createElement('option');
            option.value = sub.value;
            option.textContent = sub.label;
            if (sub.value === currentSubCat) {
                option.selected = true;
            }
            subSelect.appendChild(option);
        });
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    const editModalElement = document.getElementById('resourceEditModal');
    if (editModalElement) {
        document.body.appendChild(editModalElement);
        const editModal = bootstrap.Modal.getOrCreateInstance(editModalElement);
        editModalElement.addEventListener('hidden.bs.modal', function() {
            window.location.assign(@json(route('resources.index')));
        }, { once: true });
        editModal.show();
    }

    const categorySelect = document.querySelector('select[name="category"]');
    if (categorySelect) {
        const currentCategory = categorySelect.value;

        if (currentCategory) {
            updateEditSubCategories(currentCategory);
        }

        categorySelect.addEventListener('change', function() {
            updateEditSubCategories(this.value);
        });
    }
});
</script>

<style>
.resource-edit-modal-content {
    border: 0;
    border-radius: 0.9rem;
    overflow: hidden;
}

.resource-edit-modal-header {
    padding: 0.85rem 1rem;
    background: #255156;
}

.resource-edit-modal-body {
    padding: 1rem;
}

.resource-edit-modal-body .form-label {
    margin-bottom: 0.25rem;
}

.resource-type-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
    min-width: 0;
}

.resource-current-file {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    min-width: 0;
    color: #0369a1;
    font-size: 0.75rem;
    white-space: nowrap;
}

.resource-current-file a {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.resource-edit-modal-body .form-control,
.resource-edit-modal-body .form-select {
    padding-top: 0.4rem;
    padding-bottom: 0.4rem;
}

.resource-edit-modal-body small {
    font-size: 0.75rem;
}

.resource-edit-modal-footer {
    padding: 0.75rem 1rem;
}

.resource-edit-submit {
    background: #255156;
    border-color: #255156;
}

.resource-edit-submit:hover,
.resource-edit-submit:focus {
    background: #1d4145;
    border-color: #1d4145;
}

@media (max-width: 575.98px) {
    .resource-edit-dialog {
        margin: 0.5rem;
    }

    .resource-edit-modal-body {
        padding: 0.75rem;
    }

    .resource-type-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .resource-current-file {
        max-width: 100%;
    }
}

.btn-group .btn.active {
    background-color: #255156 !important;
    color: white !important;
    border-color: #255156 !important;
    box-shadow: none !important;
}
.btn-group .btn-outline-primary {
    border-color: #dee2e6;
    color: #495057;
}
.btn-group .btn-outline-primary:hover:not(.active) {
    background-color: #f8f9fa;
    border-color: #ced4da;
    color: #212529;
}
.btn-group .btn-outline-primary.active:hover {
    background-color: #255156 !important;
    color: white !important;
}
</style>
@endsection