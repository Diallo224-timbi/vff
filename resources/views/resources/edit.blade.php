@extends('base')

@section('title', 'Modifier la ressource')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header text-white py-3" style="background: #255156; border: none; border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title mb-0">
                        <i class="fas fa-edit me-2"></i> Modifier la ressource
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('resources.update', $resource) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
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
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" 
                                      rows="3" 
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $resource->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type de ressource <span class="text-danger">*</span></label>
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
                        <div id="editFileUploadSection" class="mb-3 {{ $resource->is_link ? 'd-none' : '' }}">
                            @if(!$resource->is_link && $resource->file_path)
                                <div class="alert alert-info mb-3 border-0" style="background: #f0f9ff; color: #0369a1;">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                                        <div>
                                            <i class="fas fa-paperclip me-2 text-primary"></i>
                                            <strong>Fichier actuel :</strong> 
                                            <a href="{{ Storage::url($resource->file_path) }}" target="_blank" class="text-decoration-underline fw-semibold text-primary">
                                                {{ $resource->file_name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <label class="form-label fw-semibold">
                                {{ !$resource->is_link && $resource->file_path ? 'Remplacer le fichier' : 'Fichier' }}
                                @if(!$resource->file_path) <span class="text-danger">*</span> @endif
                            </label>
                            <input type="file" id="editFile" name="file" class="form-control" accept=".pdf,.doc,.odt,.docx,.xls,.csv,.jpg,.jpeg,.png,.gif,.webm,.avi">
                            <small class="text-muted">Formats acceptés: PDF, DOC, ODT, DOCX, JPG, PNG, GIF, Max 50Mo</small>
                        </div>

                        <!-- Section Lien -->
                        <div id="editLinkSection" class="mb-3 {{ !$resource->is_link ? 'd-none' : '' }}">
                            <label class="form-label fw-semibold">URL du lien <span class="text-danger">*</span></label>
                            <input type="url" id="edit_link_url" name="link_url" class="form-control" placeholder="https://exemple.com/document" value="{{ old('link_url', $resource->link_url) }}">
                            <small class="text-muted">Entrez l'URL complète du lien externe</small>
                        </div>

                        <div class="mb-3">
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

                        <div class="mb-3" id="editSubCategoryContainer">
                            <label class="form-label fw-semibold">Sous-catégorie</label>
                            <select id="editSubCategory" name="sub_category" class="form-select">
                                <option value="">Aucune</option>
                            </select>
                        </div>

                        @if(auth()->user()->role === 'admin')
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="editImportant" name="important" value="1" {{ old('important', $resource->important) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="editImportant">Marquer comme ressource importante</label>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('resources.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn" style="background: #255156; color: white;">
                                <i class="fas fa-save me-1"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Map des sous-catégories (reprise exacte du formulaire d'ajout)
const subCategoriesMap = {
    'guides_etudes': [
        { value: 'national', label: 'National' },
        { value: 'departemental', label: 'Départemental' }
    ],
    'affiches_flyers': [
        { value: 'victimes', label: 'Victimes' },
        { value: 'auteurs', label: 'Auteurs' }
    ],
    'reseaux': [
        { value: 'guides', label: 'Guides' },
        { value: 'kit_creation', label: 'Kit création réseau' }
    ],
    'outils': [
        { value: 'coordination', label: 'Coordination acteurs' },
        { value: 'prevention', label: 'Prévention & sensibilisation' }
    ],
    'conventions': [
        { value: 'victimes', label: 'Victimes' },
        { value: 'auteurs', label: 'Auteurs' }
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
    const categorySelect = document.querySelector('select[name="category"]');
    const currentCategory = categorySelect.value;
    
    if (currentCategory) {
        updateEditSubCategories(currentCategory);
    }

    // Événement au changement de catégorie
    categorySelect.addEventListener('change', function() {
        updateEditSubCategories(this.value);
    });
});
</script>

<style>
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