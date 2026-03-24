<div class="form-compact">
    <form action="{{ $action }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        @if($method === 'PUT')
            @method('PUT')
        @endif  
        
        <!-- LIGNE 0: LOGO STRUCTURE -->
        <div class="row mb-3 align-items-end">
            <div class="col-md-12">
                <div class="d-flex align-items-center gap-3">
                    <div class="logo-preview-container" id="logoPreviewContainer" 
                         style="width: 80px; height: 80px; background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                        @if(isset($structure) && $structure->logo)
                            <img src="{{ Storage::url($structure->logo) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                        @else
                            <i class="fas fa-building" style="font-size: 2rem; color: #cbd5e0;"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-label mb-1">
                            <i class="fas fa-image me-1"></i>Logo de la structure
                            <span class="text-muted ms-1" style="font-size: 0.65rem;">(PNG, JPG, SVG - max 2Mo)</span>
                        </label>
                        <div class="d-flex gap-2">
                            <div class="position-relative">
                                <input type="file" name="logo" id="logoUpload" class="form-control" 
                                       accept="image/png,image/jpeg,image/svg+xml,image/jpg"
                                       style="width: 250px; padding: 4px 8px; height: 32px;"
                                       onchange="previewLogo(this)">
                                <div id="logoLoadingSpinner" class="position-absolute top-0 end-0 mt-1 me-1 d-none">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Chargement...</span>
                                    </div>
                                </div>
                            </div>
                            @if (@$method == 'POST' )
                                 <button type="button" id="removeLogoBtn" class="btn btn-sm btn-outline-danger {{ !isset($structure) || !$structure->logo ? 'd-none' : '' }}" onclick="removeLogo()">
                                <i class="bx bx-x" title="retirer le logo"></i> Retirer
                            </button>
                            @endif
                            <small id="logoHelp" class="text-muted align-self-center d-none">
                                <i class="bx bx-check-circle text-success"></i> Logo chargé avec succès
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LIGNE 1: INFOS PRINCIPALES -->
        <div class="row mb-2">
            <div class="col-md-4">
                <label class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" name="organisme" class="form-control" value="{{ old('organisme', $structure->organisme ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Type</label>
                <select name="type_structure" class="form-select">
                    <option value="Siège social" {{ old('type_structure', $structure->type_structure ?? '') == 'siège social' ? 'selected' : '' }}>Siège social</option>
                    <option value="Antenne" {{ old('type_structure', $structure->type_structure ?? '') == 'antenne' ? 'selected' : '' }}>Antenne</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Hébergement</label>
                <select name="hebergement" class="form-select">
                    <option value="oui" {{ old('hebergement', $structure->hebergement ?? '') == 'oui' ? 'selected' : '' }}>Oui</option>
                    <option value="non" {{ old('hebergement', $structure->hebergement ?? '') == 'non' ? 'selected' : '' }}>Non</option>
                </select>
            </div>
        </div>
        
        <!-- LIGNE 2: DESCRIPTION + DÉTAILS -->
        <div class="row mb-2">
            <div class="col-md-6">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="1">{{ old('description', $structure->description ?? '') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Détails spécifiques</label>
                <textarea name="details" class="form-control" rows="1">{{ old('details', $structure->details ?? '') }}</textarea>
                <div class="form-text">Ex: permanences...</div>
            </div>
        </div>

        <!-- LIGNE 3: COORDONNÉES -->
        <div class="row mb-2">
            <div class="col-md-4">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $structure->telephone ?? '') }}" placeholder="01 23 45 67 89">
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $structure->email ?? '') }}" placeholder="contact@structure.fr">
            </div>
            <div class="col-md-4">
                <label class="form-label">Site web</label>
                <input type="url" name="site" class="form-control" value="{{ old('site', $structure->site ?? '') }}" placeholder="https://">
            </div>
        </div>

        <!-- LIGNE 4: HORAIRES + CATÉGORIES + PUBLICS + ZONE -->
        <div class="row mb-2">
            <div class="col-md-3">
                <label class="form-label">Horaires</label>
                <input type="text" name="horaires" class="form-control" value="{{ old('horaires', $structure->horaires ?? '') }}" placeholder="Lun-Ven 9h-18h">
            </div>
            
            <!-- CATÉGORIES AVEC ICÔNE POUR EFFACER -->
            <div class="col-md-3">
                <label class="form-label">Catégories</label>
                <div class="d-flex gap-1">
                    <select id="categoriesSelect" class="form-select form-select-sm grow">
                        <option value="">-- Choisir --</option>
                        <option value="droit généraliste">Droit généraliste</option>   
                        <option value="droit notarial">Droit notarial</option>
                        <option value="formation">Formation</option>
                        <option value="global">Global</option>
                        <option value="hébergement">Hébergement</option>
                        <option value="insertion professionnelle">Insertion professionnelle</option>
                        <option value="juridique">Juridique</option>
                        <option value="psychologique">Psychologique</option>
                        <option value="santé">Santé</option>
                        <option value="social">Social</option>
                        <option value="autres">Autres</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addCategory()">Ajouter</button>
                </div>
                <div class="position-relative mt-1">
                    <textarea name="categories" id="categoriesTextarea" class="form-control" rows="1" readonly>{{ old('categories', $structure->categories ?? '') }}</textarea>
                    <!-- effacer le contenu du textarea --> 
                    <button type="button" class="btn-clear-textarea position-absolute top-50 end-0 translate-middle-y me-2" 
                            onclick="clearTextarea('categoriesTextarea')" 
                            style="background: none; border: none; color: #dc3545; cursor: pointer; z-index: 10; display: {{ old('categories', $structure->categories ?? '') ? 'block' : 'none' }};">
                        <i class="bx bx-x" title="vider le contenu"></i>
                    </button>
                </div>
            </div>

            <!-- PUBLICS CIBLES AVEC ICÔNE POUR EFFACER -->
            <div class="col-md-3">
                <label class="form-label">Publics cibles</label>
                <div class="d-flex gap-1">
                    <select id="publicSelect" class="form-select form-select-sm grow">
                        <option value="">-- Choisir --</option>
                        <option value="victimes">Victimes</option>
                        <option value="auteurs">Auteurs</option>
                        <option value="mineurs">Mineurs</option>
                        <option value="majeurs">Majeurs</option>
                        <option value="tous">Tous publics</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addPublic()">Ajouter</button>
                </div>
                <div class="position-relative mt-1">
                    <textarea name="public_cible" id="publicTextarea" class="form-control" rows="1" readonly>{{ old('public_cible', $structure->public_cible ?? '') }}</textarea>
                    <button type="button" class="btn-clear-textarea position-absolute top-50 end-0 translate-middle-y me-2" 
                            onclick="clearTextarea('publicTextarea')" 
                            style="background: none; border: none; color: #dc3545; cursor: pointer; z-index: 10; display: {{ old('public_cible', $structure->public_cible ?? '') ? 'block' : 'none' }};">
                        <i class="bx bx-x" title="vider le contenu"></i>
                    </button>
                    
                </div>
            </div>
            
            <!-- ZONE D'INTERVENTION -->
            <div class="col-md-3">
                <label class="form-label">Zone d'intervention</label>
                <input type="text" name="zone" class="form-control" value="{{ old('zone', $structure->zone ?? '') }}">
            </div>
        </div>

        <!-- BLOC SIÈGE SOCIAL -->
        <fieldset>
            <legend class="h6 fw-bold"><i class="fas fa-building me-2"></i> SIÈGE SOCIAL</legend>
            <div class="row mb-2">
                <div class="col-md-4">
                    <label class="form-label">Adresse <span class="text-danger">*</span></label>
                    <input type="text" name="siege_adresse" id="siege_adresse" class="form-control" value="{{ old('siege_adresse', $structure->siege_adresse ?? '') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ville <span class="text-danger">*</span></label>
                    <input type="text" name="siege_ville" id="siege_ville" class="form-control" value="{{ old('siege_ville', $structure->siege_ville ?? '') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Code postal <span class="text-danger">*</span></label>
                    <input type="text" name="siege_code_postal" id="siege_code_postal" class="form-control" value="{{ old('siege_code_postal', $structure->siege_code_postal ?? '') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Pays</label>
                    <input type="text" name="pays" id="pays" class="form-control" value="{{ old('pays', $structure->pays ?? 'France') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" id="geocodeSiegeBtn" class="btn btn-outline-primary w-100" style="height: 32px; padding: 0;" onclick="geocodeSiege()">
                        <i class="bx bx-map" title="géocoder"></i>
                    </button>
                </div>
            </div>
        </fieldset>

        <!-- BLOC LOCALISATION STRUCTURE (REFONDU) -->
        <fieldset>
            <legend class="h6 fw-bold d-flex align-items-center">
                <span><i class="fas fa-map-marker-alt me-2"></i> LOCALISATION STRUCTURE</span>
                @if($method === 'PUT')
                    <span class="ms-3 badge bg-warning text-dark" id="toggleEditMode" style="cursor: pointer; font-size: 0.7rem;" onclick="toggleEditMode()">
                        <i class="fas fa-lock me-1"></i> Mode édition désactivé
                    </span>
                @endif
            </legend>
            
            <div id="locationFieldsContainer">
                <!-- Lignes d'adresse comme pour le siège social -->
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Adresse <span class="text-danger">*</span></label>
                        <input type="text" name="adresse" id="structure_adresse" class="form-control" 
                               value="{{ old('adresse', $structure->adresse ?? '') }}" 
                               {{ $method === 'PUT' ? 'readonly' : '' }}
                               required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ville <span class="text-danger">*</span></label>
                        <input type="text" name="ville" id="structure_ville" class="form-control" 
                               value="{{ old('ville', $structure->ville ?? '') }}"
                               {{ $method === 'PUT' ? 'readonly' : '' }}
                               required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Code postal <span class="text-danger">*</span></label>
                        <input type="text" name="code_postal" id="structure_code_postal" class="form-control" 
                               value="{{ old('code_postal', $structure->code_postal ?? '') }}"
                               {{ $method === 'PUT' ? 'readonly' : '' }}
                               required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Pays</label>
                        <input type="text" name="structure_pays" id="structure_pays" class="form-control" 
                               value="{{ old('structure_pays', $structure->structure_pays ?? 'France') }}"
                               {{ $method === 'PUT' ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" id="geocodeStructureBtn" class="btn btn-outline-primary w-100" style="height: 32px; padding: 0;" 
                                onclick="geocodeStructure()" {{ $method === 'PUT' ? 'disabled' : '' }}>
                            <i class="bx bx-map" title="géocoder"></i>
                        </button>
                    </div>
                </div>
                
                @if($method === 'PUT')
                <div class="mb-2 p-2 bg-light rounded d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            <span id="editModeStatus">Les champs d'adresse sont verrouillés</span>
                        </small>
                    </div>
                    <div>
                        <button type="button" id="enableEditBtn" class="btn btn-sm btn-primary" onclick="enableEditMode()">
                            <i class="fas fa-unlock"></i> Déverrouiller
                        </button>
                        <button type="button" id="disableEditBtn" class="btn btn-sm btn-secondary d-none" onclick="disableEditMode()">
                            <i class="fas fa-lock"></i> Verrouiller
                        </button>
                        <button type="button" id="resetLocationBtn" class="btn btn-sm btn-outline-danger d-none" onclick="resetLocation()">
                            <i class="fas fa-undo"></i> Restaurer
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $structure->latitude ?? '') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $structure->longitude ?? '') }}">
            
            <div class="d-flex justify-content-between align-items-center">
                <div class="alert @if($method === 'PUT') alert-warning @else alert-info @endif p-1 mb-0 flex-grow-1 me-2" style="font-size:0.7rem;" id="gpsAlert">
                    <i class="fas fa-map-pin me-1"></i>
                    GPS: <span id="coordinates-display">
                        @if(isset($structure) && $structure->latitude && $structure->longitude)
                            {{ $structure->latitude }}, {{ $structure->longitude }}
                        @else
                            Non géocodé
                        @endif
                    </span>
                </div>
                @if($method === 'PUT')
                    <button type="button" id="clearLocationBtn" class="btn btn-sm btn-outline-danger d-none" onclick="clearLocation()" title="Vider">
                        <i class="bx bx-trash"></i>
                    </button>
                    <button type="button" id="saveLocationBtn" class="btn btn-sm btn-success d-none" onclick="saveLocation()" title="Enregistrer">
                        <i class="bx bx-save"></i>
                    </button>
                @else
                    <button type="button" id="clearLocationBtn" class="btn btn-sm btn-outline-danger" onclick="clearLocation()" title="Vider">
                        <i class="bx bx-trash"></i>
                    </button>
                @endif
            </div>
            
            @if($method === 'PUT')
            <div id="saveConfirmation" class="mt-1" style="display: none;">
                <div class="alert alert-success p-1 mb-0" style="font-size:0.7rem;">
                    <i class="bx bx-check-circle"></i> N'oubliez pas de soumettre le formulaire pour enregistrer les modifications GPS
                </div>
            </div>
            @endif
        </fieldset>

        <!-- BOUTONS D'ACTION -->
        <div class="d-flex justify-content-between mt-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> 
                @if($method === 'PUT')
                    Mettre à jour
                @else
                    Enregistrer
                @endif
            </button>
            <a href="{{ route('structures.index') }}" class="btn btn-secondary">
                Annuler
            </a>
        </div>
    </form>
</div>

<style>
    * { box-sizing: border-box; }
    
    .form-compact {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        padding: 15px;
        max-width: 1300px;
        width: 100%;
        max-height: 98vh;
        overflow-y: auto;
    }
    
    .row { margin-left: -5px; margin-right: -5px; }
    
    .col-md-4, .col-md-3, .col-md-2, .col-md-6, .col-md-7 {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    .mb-3, .mb-4 { margin-bottom: 8px !important; }
    
    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #495057;
        margin-bottom: 2px;
    }
    
    .form-control, .form-select, .input-group-text {
        font-size: 0.8rem;
        padding: 4px 8px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    textarea.form-control {
        height: auto;
        min-height: 45px;
        resize: none;
        padding-right: 30px;
    }
    
    .btn-clear-textarea {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.9);
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .btn-clear-textarea:hover {
        transform: scale(1.1);
        background: #fff;
    }
    
    .btn-clear-textarea i {
        font-size: 1rem;
    }
    
    fieldset {
        background: #f8fafc;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 10px !important;
        margin-bottom: 10px !important;
    }
    
    legend {
        font-size: 0.8rem;
        width: auto;
        padding: 0 10px;
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        margin-bottom: 8px;
        font-weight: 600;
        color: #4a5568;
    }
    
    .btn { padding: 5px 12px; font-size: 0.8rem; border-radius: 8px; }
    .btn-sm { padding: 2px 8px; font-size: 0.7rem; }
    .form-text { font-size: 0.65rem; margin-top: 2px; color: #718096; }
    .alert { padding: 6px 10px; margin-bottom: 0; border-radius: 8px; font-size: 0.75rem; }
    .text-danger { font-size: 0.7rem; }
    .border { border-width: 1px; }
    .rounded { border-radius: 8px !important; }
    
    .form-compact::-webkit-scrollbar { width: 4px; }
    .form-compact::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
    
    .logo-preview-container { transition: all 0.3s ease; }
    .logo-preview-container:hover { border-color: #4299e1; background: #ebf8ff; }
    
    #logoUpload { cursor: pointer; transition: all 0.2s; }
    #logoUpload:hover { border-color: #4299e1; background: #f7fafc; }
    
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .spinner-border { animation: spin 0.75s linear infinite; }
    
    @media (max-height: 700px) {
        .form-compact { max-height: 96vh; padding: 10px; }
        .form-label { font-size: 0.7rem; }
        .form-control, .form-select { font-size: 0.75rem; height: 30px; }
        .logo-preview-container { width: 70px; height: 70px; }
    }
</style>

<script>
// Variables globales pour le mode édition
let originalValues = {};

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Sauvegarder les valeurs originales pour le mode édition
    @if($method === 'PUT')
    originalValues = {
        adresse: document.getElementById('structure_adresse')?.value || '',
        ville: document.getElementById('structure_ville')?.value || '',
        code_postal: document.getElementById('structure_code_postal')?.value || '',
        pays: document.getElementById('structure_pays')?.value || '',
        latitude: document.getElementById('latitude')?.value || '',
        longitude: document.getElementById('longitude')?.value || ''
    };
    @endif
    
    // Gérer l'affichage des icônes d'effacement
    updateClearIconsVisibility();
    
    // Écouter les changements dans les textarea pour afficher/masquer les icônes
    const categoriesTextarea = document.getElementById('categoriesTextarea');
    const publicTextarea = document.getElementById('publicTextarea');
    
    if (categoriesTextarea) {
        categoriesTextarea.addEventListener('input', function() {
            const clearIcon = this.parentElement.querySelector('.btn-clear-textarea');
            if (clearIcon) {
                clearIcon.style.display = this.value.trim() ? 'flex' : 'none';
            }
        });
    }
    
    if (publicTextarea) {
        publicTextarea.addEventListener('input', function() {
            const clearIcon = this.parentElement.querySelector('.btn-clear-textarea');
            if (clearIcon) {
                clearIcon.style.display = this.value.trim() ? 'flex' : 'none';
            }
        });
    }
});

// Fonction pour mettre à jour la visibilité des icônes
function updateClearIconsVisibility() {
    const categoriesTextarea = document.getElementById('categoriesTextarea');
    const publicTextarea = document.getElementById('publicTextarea');
    
    if (categoriesTextarea) {
        const clearIcon = categoriesTextarea.parentElement.querySelector('.btn-clear-textarea');
        if (clearIcon) {
            clearIcon.style.display = categoriesTextarea.value.trim() ? 'flex' : 'none';
        }
    }
    
    if (publicTextarea) {
        const clearIcon = publicTextarea.parentElement.querySelector('.btn-clear-textarea');
        if (clearIcon) {
            clearIcon.style.display = publicTextarea.value.trim() ? 'flex' : 'none';
        }
    }
}

// Fonction pour effacer un textarea
function clearTextarea(textareaId) {
    const textarea = document.getElementById(textareaId);
    if (textarea) {
        textarea.value = '';
        const clearIcon = textarea.parentElement.querySelector('.btn-clear-textarea');
        if (clearIcon) {
            clearIcon.style.display = 'none';
        }
        
        // Déclencher l'événement input pour les éventuels listeners
        const event = new Event('input', { bubbles: true });
        textarea.dispatchEvent(event);
    }
}

// ==================== CATÉGORIES ====================
function addCategory() {
    const select = document.getElementById('categoriesSelect');
    const textarea = document.getElementById('categoriesTextarea');
    
    if (!select || !textarea) {
        alert("Erreur: éléments non trouvés");
        return;
    }
    
    const value = select.value;
    if (!value) {
        alert("Veuillez sélectionner une catégorie");
        return;
    }
    
    let current = textarea.value.trim();
    let items = current ? current.split(',').map(i => i.trim()) : [];
    
    if (items.includes(value)) {
        alert("Cette catégorie est déjà ajoutée");
        return;
    }
    
    items.push(value);
    textarea.value = items.join(', ');
    select.value = '';
    
    // Afficher l'icône d'effacement
    const clearIcon = textarea.parentElement.querySelector('.btn-clear-textarea');
    if (clearIcon) {
        clearIcon.style.display = 'flex';
    }
}

// ==================== PUBLICS ====================
function addPublic() {
    const select = document.getElementById('publicSelect');
    const textarea = document.getElementById('publicTextarea');
    
    if (!select || !textarea) {
        alert("Erreur: éléments non trouvés");
        return;
    }
    
    const value = select.value;
    if (!value) {
        alert("Veuillez sélectionner un public");
        return;
    }
    
    let current = textarea.value.trim();
    let items = current ? current.split(',').map(i => i.trim()) : [];
    
    if (items.includes(value)) {
        alert("Ce public est déjà ajouté");
        return;
    }
    
    items.push(value);
    textarea.value = items.join(', ');
    select.value = '';
    
    // Afficher l'icône d'effacement
    const clearIcon = textarea.parentElement.querySelector('.btn-clear-textarea');
    if (clearIcon) {
        clearIcon.style.display = 'flex';
    }
}

// ==================== LOGO ====================
function previewLogo(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Vérifications
    const validTypes = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/jpg'];
    if (!validTypes.includes(file.type)) {
        alert('Format non supporté. Utilisez PNG, JPG ou SVG.');
        input.value = '';
        return;
    }
    
    if (file.size > 2 * 1024 * 1024) {
        alert('Fichier trop volumineux (max 2Mo)');
        input.value = '';
        return;
    }
    
    // Afficher le spinner
    document.getElementById('logoLoadingSpinner').classList.remove('d-none');
    document.getElementById('logoHelp').classList.remove('d-none');
    document.getElementById('logoHelp').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';
    
    // Prévisualisation
    setTimeout(function() {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreviewContainer').innerHTML = 
                `<img src="${e.target.result}" alt="Logo" style="width:100%; height:100%; object-fit:contain;">`;
            document.getElementById('logoLoadingSpinner').classList.add('d-none');
            document.getElementById('logoHelp').innerHTML = '<i class="fas fa-check-circle text-success"></i> Logo chargé';
            document.getElementById('removeLogoBtn').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }, 500);
}

function removeLogo() {
    if (confirm('Voulez-vous retirer le logo ?')) {
        document.getElementById('logoPreviewContainer').innerHTML = 
            '<i class="fas fa-building" style="font-size:2rem;color:#cbd5e0;"></i>';
        document.getElementById('logoUpload').value = '';
        document.getElementById('logoHelp').classList.add('d-none');
        document.getElementById('removeLogoBtn').classList.add('d-none');
        
        // Ajouter un champ caché pour indiquer la suppression
        let hiddenInput = document.querySelector('input[name="remove_logo"]');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'remove_logo';
            hiddenInput.value = '1';
            document.querySelector('form').appendChild(hiddenInput);
        } else {
            hiddenInput.value = '1';
        }
    }
}

// ==================== GÉOCODAGE ====================
async function geocodeAddress(query, callback) {
    if (!query || query.length < 5) {
        alert("Adresse trop courte (minimum 5 caractères)");
        return;
    }
    
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=1&q=${encodeURIComponent(query)}`
        );
        const data = await response.json();
        
        if (data && data.length > 0) {
            callback(data[0]);
        } else {
            alert("Adresse non trouvée");
        }
    } catch (error) {
        console.error('Erreur géocodage:', error);
        alert("Erreur lors du géocodage");
    }
}

function updateLocationFields(place, isStructure = true) {
    const lat = document.getElementById('latitude');
    const lon = document.getElementById('longitude');
    const display = document.getElementById('coordinates-display');
    
    if (lat) lat.value = place.lat;
    if (lon) lon.value = place.lon;
    
    if (isStructure) {
        // Mettre à jour les champs de la structure
        const address = document.getElementById('structure_adresse');
        const city = document.getElementById('structure_ville');
        const postal = document.getElementById('structure_code_postal');
        const pays = document.getElementById('structure_pays');
        
        if (address) address.value = place.display_name;
        if (city) city.value = place.address?.city || place.address?.town || place.address?.village || '';
        if (postal) postal.value = place.address?.postcode || '';
        if (pays) pays.value = place.address?.country || 'France';
    } else {
        // Mettre à jour les champs du siège social
        const address = document.getElementById('siege_adresse');
        const city = document.getElementById('siege_ville');
        const postal = document.getElementById('siege_code_postal');
        const pays = document.getElementById('pays');
        
        if (address) address.value = place.display_name;
        if (city) city.value = place.address?.city || place.address?.town || place.address?.village || '';
        if (postal) postal.value = place.address?.postcode || '';
        if (pays) pays.value = place.address?.country || 'France';
    }
    
    if (display) {
        display.textContent = place.lat + ', ' + place.lon;
    }
    
    // Afficher la confirmation pour le mode PUT
    @if($method === 'PUT')
    const saveConfirmation = document.getElementById('saveConfirmation');
    if (saveConfirmation) {
        saveConfirmation.style.display = 'block';
        setTimeout(() => saveConfirmation.style.display = 'none', 3000);
    }
    @endif
}

function geocodeStructure() {
    const adresse = document.getElementById('structure_adresse')?.value || '';
    const ville = document.getElementById('structure_ville')?.value || '';
    const cp = document.getElementById('structure_code_postal')?.value || '';
    const pays = document.getElementById('structure_pays')?.value || 'France';
    
    const fullAddress = `${adresse}, ${ville}, ${cp}, ${pays}`.replace(/, ,/g, ',');
    geocodeAddress(fullAddress, (place) => updateLocationFields(place, true));
}

function geocodeSiege() {
    const adresse = document.getElementById('siege_adresse')?.value || '';
    const ville = document.getElementById('siege_ville')?.value || '';
    const cp = document.getElementById('siege_code_postal')?.value || '';
    const pays = document.getElementById('pays')?.value || 'France';
    
    const fullAddress = `${adresse}, ${ville}, ${cp}, ${pays}`.replace(/, ,/g, ',');
    geocodeAddress(fullAddress, (place) => updateLocationFields(place, false));
}

// ==================== MODE ÉDITION (PUT) ====================
@if($method === 'PUT')
function toggleEditMode() {
    const isLocked = document.getElementById('structure_adresse').readOnly;
    if (isLocked) {
        enableEditMode();
    } else {
        disableEditMode();
    }
}

function enableEditMode() {
    // Activer les champs de la structure
    document.getElementById('structure_adresse').readOnly = false;
    document.getElementById('structure_ville').readOnly = false;
    document.getElementById('structure_code_postal').readOnly = false;
    document.getElementById('structure_pays').readOnly = false;
    document.getElementById('geocodeStructureBtn').disabled = false;
    
    // Gérer les boutons
    document.getElementById('enableEditBtn').classList.add('d-none');
    document.getElementById('disableEditBtn').classList.remove('d-none');
    document.getElementById('resetLocationBtn').classList.remove('d-none');
    document.getElementById('clearLocationBtn').classList.remove('d-none');
    document.getElementById('saveLocationBtn').classList.remove('d-none');
    
    // Mettre à jour les messages
    document.getElementById('editModeStatus').textContent = 'Mode édition activé';
    document.getElementById('toggleEditMode').innerHTML = '<i class="fas fa-unlock me-1"></i> Mode édition activé';
    document.getElementById('toggleEditMode').classList.remove('bg-warning');
    document.getElementById('toggleEditMode').classList.add('bg-success', 'text-white');
}

function disableEditMode() {
    // Désactiver les champs de la structure
    document.getElementById('structure_adresse').readOnly = true;
    document.getElementById('structure_ville').readOnly = true;
    document.getElementById('structure_code_postal').readOnly = true;
    document.getElementById('structure_pays').readOnly = true;
    document.getElementById('geocodeStructureBtn').disabled = true;
    
    // Gérer les boutons
    document.getElementById('enableEditBtn').classList.remove('d-none');
    document.getElementById('disableEditBtn').classList.add('d-none');
    document.getElementById('resetLocationBtn').classList.add('d-none');
    document.getElementById('clearLocationBtn').classList.add('d-none');
    document.getElementById('saveLocationBtn').classList.add('d-none');
    
    // Mettre à jour les messages
    document.getElementById('editModeStatus').textContent = 'Les champs d\'adresse sont verrouillés';
    document.getElementById('toggleEditMode').innerHTML = '<i class="fas fa-lock me-1"></i> Mode édition désactivé';
    document.getElementById('toggleEditMode').classList.remove('bg-success', 'text-white');
    document.getElementById('toggleEditMode').classList.add('bg-warning', 'text-dark');
}

function resetLocation() {
    document.getElementById('structure_adresse').value = originalValues.adresse;
    document.getElementById('structure_ville').value = originalValues.ville;
    document.getElementById('structure_code_postal').value = originalValues.code_postal;
    document.getElementById('structure_pays').value = originalValues.pays;
    document.getElementById('latitude').value = originalValues.latitude;
    document.getElementById('longitude').value = originalValues.longitude;
    
    const display = document.getElementById('coordinates-display');
    if (originalValues.latitude && originalValues.longitude) {
        display.textContent = originalValues.latitude + ', ' + originalValues.longitude;
    } else {
        display.textContent = 'Non géocodé';
    }
    
    disableEditMode();
}

function saveLocation() {
    originalValues = {
        adresse: document.getElementById('structure_adresse').value,
        ville: document.getElementById('structure_ville').value,
        code_postal: document.getElementById('structure_code_postal').value,
        pays: document.getElementById('structure_pays').value,
        latitude: document.getElementById('latitude').value,
        longitude: document.getElementById('longitude').value
    };
    
    alert('Modifications enregistrées ! Pensez à soumettre le formulaire.');
    disableEditMode();
}

function clearLocation() {
    if (confirm('Supprimer les coordonnées GPS ?')) {
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('coordinates-display').textContent = 'Non géocodé';
    }
}
@endif
</script>