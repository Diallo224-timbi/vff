@extends('base')

@section('title', 'Modifier la ressource')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header text-white py-3" style="background: #255156; border: none;">
                    <h5 class="modal-title">
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
                                      rows="4" 
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $resource->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <!-- Section fichier -->
                        <div id="editFileUploadSection" class="mb-3 {{ $resource->is_link ? 'd-none' : '' }}">
                            @if(!$resource->is_link && $resource->file_path)
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Fichier actuel :</strong> {{ $resource->file_name }}
                                    <small class="text-muted d-block">Vous devez supprimer le document actuel pour en téléverser un nouveau.</small>
                                </div>
                            @endif
                            
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                            <select name="category" required class="form-select @error('category') is-invalid @enderror">
                                <option value="procedure" {{ old('category', $resource->category) == 'procedure' ? 'selected' : '' }}>Procédure</option>
                                <option value="outil" {{ old('category', $resource->category) == 'outil' ? 'selected' : '' }}>Outil</option>
                                <option value="fiche_reflexe" {{ old('category', $resource->category) == 'fiche_reflexe' ? 'selected' : '' }}>Fiche réflexe</option>
                                <option value="ressource" {{ old('category', $resource->category) == 'ressource' ? 'selected' : '' }}>Ressource</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
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
let selectedEditResourceType = '{{ $resource->is_link ? "link" : "file" }}';

function selectEditResourceType(type) {
    selectedEditResourceType = type;
    
    const btnFile = document.getElementById('editBtnFileType');
    const btnLink = document.getElementById('editBtnLinkType');
    const fileSection = document.getElementById('editFileUploadSection');
    const linkSection = document.getElementById('editLinkSection');
    const linkUrlInput = document.getElementById('edit_link_url');
    
    if (type === 'file') {
        if (btnFile) {
            btnFile.classList.add('active');
            btnFile.style.backgroundColor = '#255156';
            btnFile.style.color = 'white';
            btnFile.style.borderColor = '#255156';
        }
        if (btnLink) {
            btnLink.classList.remove('active');
            btnLink.style.backgroundColor = '';
            btnLink.style.color = '';
            btnLink.style.borderColor = '';
        }
        if (fileSection) fileSection.classList.remove('d-none');
        if (linkSection) linkSection.classList.add('d-none');
        if (linkUrlInput) linkUrlInput.required = false;
    } else {
        if (btnLink) {
            btnLink.classList.add('active');
            btnLink.style.backgroundColor = '#255156';
            btnLink.style.color = 'white';
            btnLink.style.borderColor = '#255156';
        }
        if (btnFile) {
            btnFile.classList.remove('active');
            btnFile.style.backgroundColor = '';
            btnFile.style.color = '';
            btnFile.style.borderColor = '';
        }
        if (fileSection) fileSection.classList.add('d-none');
        if (linkSection) linkSection.classList.remove('d-none');
        if (linkUrlInput) linkUrlInput.required = true;
    }
}
</script>

<style>
.btn-group .btn.active {
    background-color: #255156 !important;
    color: white !important;
    border-color: #255156 !important;
}
</style>
@endsection