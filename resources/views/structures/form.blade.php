
    <div class="form-compact">
        <form action="{{ $action }}" method="POST" autocomplete="off">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif

            <!-- LIGNE 1: INFOS PRINCIPALES -->
            <div class="row mb-2">
                <div class="col-md-4">
                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="organisme" class="form-control" value="{{ old('organisme', $structure->organisme ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select name="type_structure" class="form-select">
                        <option value="siège social" {{ old('type_structure', $structure->type_structure ?? '') == 'siège social' ? 'selected' : '' }}>Siège</option>
                        <option value="antenne" {{ old('type_structure', $structure->type_structure ?? '') == 'antenne' ? 'selected' : '' }}>Antenne</option>
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

            <!-- LIGNE 4: HORAIRES + CATÉGORIES -->
            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label">Horaires</label>
                    <input type="text" name="horaires" class="form-control" value="{{ old('horaires', $structure->horaires ?? '') }}" placeholder="Lun-Ven 9h-18h">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Catégories</label>
                    <input type="text" name="categories" class="form-control" value="{{ old('categories', $structure->categories ?? '') }}" placeholder="social, formation">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Publics cibles</label>
                    <input type="text" name="public_cible" class="form-control" value="{{ old('public_cible', $structure->public_cible ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Zone d'intervention</label>
                    <input type="text" name="zone" class="form-control" value="{{ old('zone', $structure->zone ?? '') }}">
                </div>
            </div>

            <!-- BLOC SIÈGE SOCIAL -->
            <fieldset>
                <legend class="h6 fw-bold">🏢 SIÈGE SOCIAL</legend>
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
                        <label class="form-label">CP <span class="text-danger">*</span></label>
                        <input type="text" name="siege_code_postal" id="siege_code_postal" class="form-control" value="{{ old('siege_code_postal', $structure->siege_code_postal ?? '') }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Pays</label>
                        <input type="text" name="pays" id="pays" class="form-control" value="{{ old('pays', $structure->pays ?? 'France') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" id="geocodeSiegeBtn" class="btn btn-outline-primary w-100" style="height: 32px; padding: 0;">
                            <i class="fas fa-map-marker-alt"></i>
                        </button>
                    </div>
                </div>
            </fieldset>

            <!-- BLOC LOCALISATION STRUCTURE -->
            <fieldset>
                <legend class="h6 fw-bold d-flex align-items-center">
                    <span>📍 LOCALISATION STRUCTURE</span>
                    @if($method === 'PUT')
                        <span class="ms-3 badge bg-warning text-dark geocode-toggle" id="toggleEditMode" style="cursor: pointer; font-size: 0.7rem;">
                            <i class="fas fa-lock me-1"></i> Mode édition désactivé
                        </span>
                    @endif
                </legend>
                
                <!-- MODE MODIFICATION AVEC POSSIBILITÉ DE DÉVERROUILLER -->
                <div id="locationFieldsContainer">
                    @if($method === 'PUT')
                        <!-- Mode modification avec toggle -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Adresse</label>
                                <div class="input-group">
                                    <input type="text" name="adresse" id="autocomplete" 
                                           class="form-control" 
                                           value="{{ old('adresse', $structure->adresse ?? '') }}" 
                                           readonly>
                                    <button type="button" id="geocodeBtn" class="btn btn-outline-primary" disabled title="Déverrouillez d'abord le mode édition">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ville</label>
                                <input type="text" name="ville" id="locality" class="form-control" value="{{ old('ville', $structure->ville ?? '') }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Code postal</label>
                                <input type="text" name="code_postal" id="postal_code" class="form-control" value="{{ old('code_postal', $structure->code_postal ?? '') }}" readonly>
                            </div>
                        </div>
                        
                        <!-- Boutons de contrôle du mode édition -->
                        <div class="mb-2 p-2 bg-light rounded d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    <span id="editModeStatus">Les champs d'adresse sont verrouillés</span>
                                </small>
                            </div>
                            <div>
                               
                                <button type="button" id="disableEditBtn" class="btn btn-sm btn-secondary d-none">
                                    <i class="fas fa-lock"></i> Verrouiller
                                </button>
                                <button type="button" id="resetLocationBtn" class="btn btn-sm btn-outline-danger d-none">
                                    <i class="fas fa-undo"></i> Restaurer
                                </button>
                            </div>
                        </div>
                        
                        <!-- Suggestions (cachées par défaut en mode édition) -->
                        <div id="suggestions" class="border bg-white shadow-sm mt-1 rounded" style="position:absolute; z-index:1000; display:none; max-height:150px; overflow-y:auto; width:90%;"></div>
                    @else
                        <!-- MODE CRÉATION (inchangé) -->
                        <div class="position-relative mb-2">
                            <div class="row">
                                <div class="col-md-7">
                                    <label class="form-label">Adresse</label>
                                    <div class="input-group">
                                        <input type="text" name="adresse" id="autocomplete" class="form-control" value="{{ old('adresse', $structure->adresse ?? '') }}" placeholder="Tapez l'adresse...">
                                        <button type="button" id="geocodeBtn" class="btn btn-outline-primary">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                    </div>
                                    <div id="suggestions" class="border bg-white shadow-sm mt-1 rounded" style="position:absolute; z-index:1000; display:none; max-height:150px; overflow-y:auto; width:90%;"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Ville</label>
                                    <input type="text" name="ville" id="locality" class="form-control" value="{{ old('ville', $structure->ville ?? '') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">CP</label>
                                    <input type="text" name="code_postal" id="postal_code" class="form-control" value="{{ old('code_postal', $structure->code_postal ?? '') }}">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- HIDDEN LAT/LON + COORDONNÉES -->
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
                        <button type="button" id="clearLocationBtn" class="btn btn-sm btn-outline-danger d-none" title="Vider">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button type="button" id="saveLocationBtn" class="btn btn-sm btn-success d-none" title="Enregistrer">
                            <i class="fas fa-save"></i>
                        </button>
                    @else
                        <button type="button" id="clearLocationBtn" class="btn btn-sm btn-outline-danger" title="Vider">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </div>
                
                @if($method === 'PUT')
                <!-- Message de confirmation pour la sauvegarde -->
                <div id="saveConfirmation" class="mt-1" style="display: none;">
                    <div class="alert alert-success p-1 mb-0" style="font-size:0.7rem;">
                        <i class="fas fa-check-circle"></i> N'oubliez pas de soumettre le formulaire pour enregistrer les modifications GPS
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
        /* STYLE COMPACT - TOUT EN UN SEUL ÉCRAN */
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
        /* Ajustements ultra-compacts */
        .row {
            margin-left: -5px;
            margin-right: -5px;
        }
        .col-md-4, .col-md-3, .col-md-2 {
            padding-left: 5px;
            padding-right: 5px;
        }
        .mb-3, .mb-4 {
            margin-bottom: 8px !important;
        }
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
        .btn {
            padding: 5px 12px;
            font-size: 0.8rem;
            border-radius: 8px;
        }
        .btn-sm {
            padding: 2px 8px;
            font-size: 0.7rem;
        }
        .form-text {
            font-size: 0.65rem;
            margin-top: 2px;
            color: #718096;
        }
        .alert {
            padding: 6px 10px;
            margin-bottom: 0;
            border-radius: 8px;
            font-size: 0.75rem;
        }
        .text-danger { font-size: 0.7rem; }
        .border { border-width: 1px; }
        .rounded { border-radius: 8px !important; }
        #suggestions { position: absolute; max-height: 150px; font-size: 0.75rem; }
        /* Barre de scroll fine */
        .form-compact::-webkit-scrollbar { width: 4px; }
        .form-compact::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
        /* Style pour le mode édition */
        .edit-mode-active .form-control:not([readonly]) {
            background-color: #fff3cd !important;
            border-color: #ffc107 !important;
        }
        .geocode-toggle {
            cursor: pointer;
            transition: all 0.2s;
        }
        .geocode-toggle.active {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }
        /* Ajustements responsifs */
        @media (max-height: 700px) {
            .form-compact { max-height: 96vh; padding: 10px; }
            .form-label { font-size: 0.7rem; }
            .form-control, .form-select { font-size: 0.75rem; height: 30px; }
        }
    </style>
    <!-- SCRIPT COMPACTÉ AVEC SUPPORT DU MODE ÉDITION -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isEditMode = '{{ $method }}' === 'PUT';
            
            // Éléments communs
            const lat = document.getElementById('latitude');
            const lon = document.getElementById('longitude');
            const display = document.getElementById('coordinates-display');
            const input = document.getElementById('autocomplete');
            const city = document.getElementById('locality');
            const postal = document.getElementById('postal_code');
            const suggestions = document.getElementById('suggestions');
            const geocodeBtn = document.getElementById('geocodeBtn');
            const clearBtn = document.getElementById('clearLocationBtn');
            const siegeBtn = document.getElementById('geocodeSiegeBtn');
            
            // Éléments spécifiques au mode édition
            let enableEditBtn, disableEditBtn, resetLocationBtn, saveLocationBtn, editModeStatus, toggleEditMode, originalValues;
            
            if (isEditMode) {
                enableEditBtn = document.getElementById('enableEditBtn');
                disableEditBtn = document.getElementById('disableEditBtn');
                resetLocationBtn = document.getElementById('resetLocationBtn');
                saveLocationBtn = document.getElementById('saveLocationBtn');
                editModeStatus = document.getElementById('editModeStatus');
                toggleEditMode = document.getElementById('toggleEditMode');
                
                // Sauvegarder les valeurs originales
                originalValues = {
                    adresse: input?.value || '',
                    ville: city?.value || '',
                    code_postal: postal?.value || '',
                    latitude: lat?.value || '',
                    longitude: lon?.value || ''
                };
            }
            
            function updateCoordinatesDisplay() {
                if (lat?.value && lon?.value) {
                    display.textContent = `${parseFloat(lat.value).toFixed(6)}, ${parseFloat(lon.value).toFixed(6)}`;
                    display.className = 'text-success fw-bold';
                } else {
                    display.textContent = 'Non géocodé';
                    display.className = 'text-danger';
                }
            }

            // Fonction de géocodage améliorée
            async function geocodeAddress(address, targetLat, targetLon, targetCity, targetPostal, targetInput = null) {
                if (!address || address.length < 3) {
                    alert('Veuillez entrer une adresse valide (au moins 3 caractères)');
                    return false;
                }
                
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=1&q=${encodeURIComponent(address)}`);
                    const data = await res.json();
                    
                    if (!data.length) {
                        alert('Aucun résultat trouvé pour cette adresse');
                        return false;
                    }
                    
                    const place = data[0];
                    
                    if (targetLat) targetLat.value = place.lat;
                    if (targetLon) targetLon.value = place.lon;
                    if (targetCity) targetCity.value = place.address.city || place.address.town || place.address.village || '';
                    if (targetPostal) targetPostal.value = place.address.postcode || '';
                    if (targetInput) targetInput.value = place.display_name;
                    
                    updateCoordinatesDisplay();
                    
                    if (isEditMode) {
                        document.getElementById('saveConfirmation').style.display = 'block';
                        setTimeout(() => {
                            document.getElementById('saveConfirmation').style.display = 'none';
                        }, 3000);
                    }
                    
                    return true;
                } catch (e) {
                    console.error(e);
                    alert('Erreur lors du géocodage. Veuillez réessayer.');
                    return false;
                }
            }

            updateCoordinatesDisplay();

            // Activation du mode édition pour PUT
            if (isEditMode && enableEditBtn) {
                enableEditBtn.addEventListener('click', function() {
                    // Déverrouiller les champs
                    input.readOnly = false;
                    city.readOnly = false;
                    postal.readOnly = false;
                    geocodeBtn.disabled = false;
                    clearBtn.classList.remove('d-none');
                    saveLocationBtn.classList.remove('d-none');
                    resetLocationBtn.classList.remove('d-none');
                    
                    // UI updates
                    input.classList.remove('bg-light');
                    city.classList.remove('bg-light');
                    postal.classList.remove('bg-light');
                    input.classList.add('bg-white');
                    city.classList.add('bg-white');
                    postal.classList.add('bg-white');
                    
                    enableEditBtn.classList.add('d-none');
                    disableEditBtn.classList.remove('d-none');
                    toggleEditMode.innerHTML = '<i class="fas fa-unlock me-1"></i> Mode édition activé';
                    toggleEditMode.classList.remove('bg-warning');
                    toggleEditMode.classList.add('bg-success', 'text-white');
                    editModeStatus.textContent = 'Mode édition activé - Vous pouvez modifier l\'adresse';
                    
                    // Ajouter la classe pour le style
                    document.querySelector('.form-compact').classList.add('edit-mode-active');
                });
                
                disableEditBtn.addEventListener('click', function() {
                    // Restaurer l'état verrouillé sans restaurer les valeurs
                    input.readOnly = true;
                    city.readOnly = true;
                    postal.readOnly = true;
                    geocodeBtn.disabled = true;
                    clearBtn.classList.add('d-none');
                    saveLocationBtn.classList.add('d-none');
                    resetLocationBtn.classList.add('d-none');
                    
                    // UI updates
                    input.classList.add('bg-light');
                    city.classList.add('bg-light');
                    postal.classList.add('bg-light');
                    input.classList.remove('bg-white');
                    city.classList.remove('bg-white');
                    postal.classList.remove('bg-white');
                    
                    enableEditBtn.classList.remove('d-none');
                    disableEditBtn.classList.add('d-none');
                    toggleEditMode.innerHTML = '<i class="fas fa-lock me-1"></i> Mode édition désactivé';
                    toggleEditMode.classList.add('bg-warning', 'text-dark');
                    toggleEditMode.classList.remove('bg-success', 'text-white');
                    editModeStatus.textContent = 'Les champs d\'adresse sont verrouillés';
                    
                    document.querySelector('.form-compact').classList.remove('edit-mode-active');
                });
                
                resetLocationBtn.addEventListener('click', function() {
                    // Restaurer les valeurs originales
                    input.value = originalValues.adresse;
                    city.value = originalValues.ville;
                    postal.value = originalValues.code_postal;
                    lat.value = originalValues.latitude;
                    lon.value = originalValues.longitude;
                    
                    updateCoordinatesDisplay();
                    
                    // Désactiver le mode édition
                    disableEditBtn.click();
                });
                
                saveLocationBtn.addEventListener('click', function() {
                    // Sauvegarder les nouvelles valeurs comme originales
                    originalValues = {
                        adresse: input.value,
                        ville: city.value,
                        code_postal: postal.value,
                        latitude: lat.value,
                        longitude: lon.value
                    };
                    
                    alert('Modifications GPS enregistrées ! Pensez à soumettre le formulaire.');
                });
            }

            // Géocodage siège social (disponible dans tous les modes si les champs sont déverrouillés)
            if (siegeBtn) {
                siegeBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const adr = document.getElementById('siege_adresse')?.value || '';
                    const vil = document.getElementById('siege_ville')?.value || '';
                    const cp = document.getElementById('siege_code_postal')?.value || '';
                    const pay = document.getElementById('pays')?.value || 'France';
                    
                    if (!isEditMode || (isEditMode && !input.readOnly)) {
                        await geocodeAddress(`${adr}, ${vil}, ${cp}, ${pay}`, lat, lon, city, postal, input);
                    } else {
                        alert('Veuillez d\'abord déverrouiller le mode édition');
                    }
                });
            }

            // Géocodage principal
            if (geocodeBtn) {
                geocodeBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    if (!isEditMode || (isEditMode && !input.readOnly)) {
                        await geocodeAddress(input.value, lat, lon, city, postal, input);
                    } else {
                        alert('Veuillez d\'abord déverrouiller le mode édition');
                    }
                });
            }

            // Effacer les coordonnées
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    if (!isEditMode || (isEditMode && !input.readOnly)) {
                        if (confirm('Voulez-vous vraiment vider les coordonnées GPS ?')) {
                            lat.value = ''; 
                            lon.value = ''; 
                            city.value = ''; 
                            postal.value = ''; 
                            input.value = '';
                            updateCoordinatesDisplay();
                        }
                    }
                });
            }

            // Auto-complétion (uniquement si le champ est éditable)
            if (input && suggestions) {
                let debounce;
                input.addEventListener('input', function() {
                    // Ne fonctionne que si le champ n'est pas en lecture seule
                    if (input.readOnly) return;
                    
                    const query = input.value.trim();
                    if (query.length < 3) { 
                        suggestions.style.display = 'none'; 
                        return; 
                    }
                    
                    clearTimeout(debounce);
                    debounce = setTimeout(async () => {
                        try {
                            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=5&q=${encodeURIComponent(query)}`);
                            const data = await res.json();
                            
                            if (!data.length) { 
                                suggestions.style.display = 'none'; 
                                return; 
                            }
                            
                            suggestions.innerHTML = data.map(p => `
                                <div class="p-1 border-bottom suggestion-item" style="cursor:pointer; font-size:0.75rem;" 
                                     data-lat="${p.lat}" data-lon="${p.lon}"
                                     data-city="${p.address.city || p.address.town || p.address.village || ''}"
                                     data-postal="${p.address.postcode || ''}"
                                     data-display="${p.display_name}">
                                    <div class="fw-bold">${p.display_name.split(',')[0]}</div>
                                    <small>${p.lat.slice(0,7)}, ${p.lon.slice(0,7)}</small>
                                </div>
                            `).join('');
                            
                            suggestions.style.display = 'block';
                            
                            document.querySelectorAll('.suggestion-item').forEach(el => {
                                el.addEventListener('click', function() {
                                    input.value = this.dataset.display;
                                    lat.value = this.dataset.lat;
                                    lon.value = this.dataset.lon;
                                    city.value = this.dataset.city;
                                    postal.value = this.dataset.postal;
                                    suggestions.style.display = 'none';
                                    updateCoordinatesDisplay();
                                });
                            });
                        } catch (e) { 
                            suggestions.style.display = 'none'; 
                        }
                    }, 300);
                });

                input.addEventListener('keypress', async function(e) {
                    if (e.key === 'Enter') { 
                        e.preventDefault(); 
                        if (!input.readOnly) {
                            await geocodeAddress(input.value, lat, lon, city, postal, input); 
                        }
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!input?.contains(e.target) && !suggestions?.contains(e.target)) {
                        suggestions.style.display = 'none';
                    }
                });
            }
        });
    </script>
