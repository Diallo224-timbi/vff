<form action="{{ $action }}" method="POST" autocomplete="off">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    {{-- Informations principales --}}
    <div class="row mb-3">
        <!-- Nom de la structure -->
        <div class="col-md-4">
            <label class="form-label">Nom de la structure <span class="text-danger">*</span></label>
            <input type="text" name="organisme" class="form-control"
                value="{{ old('organisme', $structure->organisme ?? '') }}" required>
        </div>

        <!-- Type de structure -->
        <div class="col-md-4">
            <label class="form-label">Type de structure</label>
            <select name="type_structure" class="form-select">
                <option value="siège social" {{ old('type_structure', $structure->type_structure ?? '') == 'siège social' ? 'selected' : '' }}>Siège social</option>
                <option value="antenne" {{ old('type_structure', $structure->type_structure ?? '') == 'antenne' ? 'selected' : '' }}>Antenne</option>
            </select>
        </div>

        <!-- Hébergement -->
        <div class="col-md-4">
            <label class="form-label">Hébergement</label>
            <select name="hebergement" class="form-select">
                <option value="oui" {{ old('hebergement', $structure->hebergement ?? '') == 'oui' ? 'selected' : '' }}>Oui</option>
                <option value="non" {{ old('hebergement', $structure->hebergement ?? '') == 'non' ? 'selected' : '' }}>Non</option>
            </select>
        </div>
    </div>

    {{-- Description --}}
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $structure->description ?? '') }}</textarea>
    </div>

    {{-- Détails spécifiques --}}
    <div class="mb-3">
        <label class="form-label">Détails spécifiques</label>
        <textarea name="details" class="form-control" rows="2">{{ old('details', $structure->details ?? '') }}</textarea>
        <div class="form-text">Ex: permanences organisées, services spécifiques...</div>
    </div>

    {{-- Coordonnées --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control"
                value="{{ old('telephone', $structure->telephone ?? '') }}" placeholder="Ex: 01 23 45 67 89">
        </div>
        
        <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                value="{{ old('email', $structure->email ?? '') }}" placeholder="Ex: contact@structure.fr">
        </div>

        <div class="col-md-4">
            <label class="form-label">Site web</label>
            <input type="url" name="site" class="form-control"
                value="{{ old('site', $structure->site ?? '') }}" placeholder="https://www.example.com">
        </div>
    </div>

    {{-- Horaires --}}
    <div class="mb-3">
        <label class="form-label">Horaires d'ouverture</label>
        <input type="text" name="horaires" class="form-control"
            value="{{ old('horaires', $structure->horaires ?? '') }}" placeholder="Ex: Lun-Ven 9h-18h">
    </div>

    {{-- Catégorisation --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Catégories</label>
            <input type="text" name="categories" class="form-control"
                value="{{ old('categories', $structure->categories ?? '') }}" placeholder="Ex: social, formation, psychologique">
        </div>

        <div class="col-md-4">
            <label class="form-label">Publics cibles</label>
            <input type="text" name="public_cible" class="form-control"
                value="{{ old('public_cible', $structure->public_cible ?? '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Zone d'intervention</label>
            <input type="text" name="zone" class="form-control"
                value="{{ old('zone', $structure->zone ?? '') }}">
        </div>
    </div>

    {{-- Adresse du siège social --}}
    <fieldset class="mb-4 border p-3 rounded">
        <legend class="h6 fw-bold mb-3">Siége social</legend>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Adresse du siège <span class="text-danger">*</span></label>
                <input type="text" name="siege_adresse" id="siege_adresse" class="form-control"
                    value="{{ old('siege_adresse', $structure->siege_adresse ?? '') }}" required>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Ville <span class="text-danger">*</span></label>
                <input type="text" name="siege_ville" id="siege_ville" class="form-control"
                    value="{{ old('siege_ville', $structure->siege_ville ?? '') }}" required>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Code postal <span class="text-danger">*</span></label>
                <input type="text" name="siege_code_postal" id="siege_code_postal" class="form-control"
                    value="{{ old('siege_code_postal', $structure->siege_code_postal ?? '') }}" required>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Pays</label>
                <input type="text" name="pays" id="pays" class="form-control"
                    value="{{ old('pays', $structure->pays ?? 'France') }}" placeholder="France">
            </div>
        </div>
        
        <div class="mb-3">
            <button type="button" id="geocodeSiegeBtn" class="btn btn-outline-primary">
                <i class="fas fa-map-marker-alt"></i> Géocoder le siège social
            </button>
            <div class="form-text">Le géocodage remplira automatiquement les coordonnées GPS</div>
        </div>
    </fieldset>

    {{-- Adresse de la structure --}}
    <fieldset class="mb-4 border p-3 rounded">
        <legend class="h6 fw-bold mb-3">
            Localisation de la structure
            <small class="text-muted ms-2">(Peut être différente du siège social)</small>
        </legend>
        
        {{-- Adresse --}}
        <div class="mb-3 position-relative">
            <label class="form-label">Adresse complète</label>
            @if($method === 'PUT')
                {{-- Mode modification : champ en lecture seule --}}
                <div class="input-group">
                    <input type="text"
                           name="adresse"
                           id="autocomplete"
                           class="form-control bg-light"
                           value="{{ old('adresse', $structure->adresse ?? '') }}"
                           placeholder="Adresse non modifiable"
                           
                           >
                    <button type="button" class="btn btn-outline-secondary" disabled title="Géocodage non disponible en modification">
                        <i class="fas fa-lock"></i>
                    </button>
                </div>
                <div class="form-text text-warning">
                    <i class="fas fa-info-circle"></i> L'adresse de localisation n'est pas modifiable en mode édition.
                </div>
            @else
                {{-- Mode création : champ éditable avec géocodage --}}
                <div class="input-group">
                    <input type="text"
                           name="adresse"
                           id="autocomplete"
                           class="form-control"
                           value="{{ old('adresse', $structure->adresse ?? '') }}"
                           placeholder="Commencez à taper l'adresse…">
                    <button type="button" id="geocodeBtn" class="btn btn-outline-primary" title="Géocoder cette adresse">
                        <i class="fas fa-map-marker-alt"></i>
                    </button>
                </div>
                <div class="form-text">
                    <i class="fas fa-info-circle"></i> Tapez une adresse ou utilisez le bouton <i class="fas fa-map-marker-alt"></i> pour géocoder
                </div>
                <div id="suggestions"
                     class="border bg-white w-100 shadow-sm mt-1 rounded"
                     style="position:absolute; z-index:1000; display:none; max-height:200px; overflow-y:auto;"></div>
            @endif
        </div>

        {{-- Ville / CP --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Ville</label>
                @if($method === 'PUT')
                    <input type="text" name="ville" id="locality" class="form-control bg-light"
                           value="{{ old('ville', $structure->ville ?? '') }}" readonly>
                @else
                    <input type="text" name="ville" id="locality" class="form-control"
                           value="{{ old('ville', $structure->ville ?? '') }}">
                @endif
            </div>

            <div class="col-md-4">
                <label class="form-label">Code postal</label>
                @if($method === 'PUT')
                    <input type="text" name="code_postal" id="postal_code" class="form-control bg-light"
                           value="{{ old('code_postal', $structure->code_postal ?? '') }}" readonly>
                @else
                    <input type="text" name="code_postal" id="postal_code" class="form-control"
                           value="{{ old('code_postal', $structure->code_postal ?? '') }}">
                @endif
            </div>
        </div>

        {{-- Latitude / Longitude --}}
        <input type="hidden" name="latitude" id="latitude"
               value="{{ old('latitude', $structure->latitude ?? '') }}">
        <input type="hidden" name="longitude" id="longitude"
               value="{{ old('longitude', $structure->longitude ?? '') }}">

        {{-- Coordonnées affichées --}}
        <div class="mb-3">
            <div class="alert 
                @if($method === 'PUT') alert-secondary @else alert-info @endif 
                p-2 mb-0">
                <small class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-map-pin me-1"></i>
                        Coordonnées GPS: 
                        <span id="coordinates-display">
                            @if(isset($structure) && $structure->latitude && $structure->longitude)
                                {{ $structure->latitude }}, {{ $structure->longitude }}
                            @else
                                Non géocodé
                            @endif
                        </span>
                    </span>
                    @if($method !== 'PUT')
                        <button type="button" id="clearLocationBtn" class="btn btn-sm btn-outline-danger" title="Vider les coordonnées">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </small>
            </div>
        </div>
    </fieldset>

    <div class="mt-4">
        <button type="submit" class="btn btn-success ">
            <i class="fas fa-save"></i> 
            @if($method === 'PUT')
                Mettre à jour
            @else
                Enregistrer
            @endif
        </button>
        <a href="{{ route('structures.index') }}" class="btn btn-secondary">Annuler</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isEditMode = '{{ $method }}' === 'PUT';
    
    if (!isEditMode) {
        // === MODE CRÉATION ===
        const input = document.getElementById('autocomplete');
        const suggestions = document.getElementById('suggestions');
        const geocodeBtn = document.getElementById('geocodeBtn');
        const clearLocationBtn = document.getElementById('clearLocationBtn');
        const coordinatesDisplay = document.getElementById('coordinates-display');
        const geocodeSiegeBtn = document.getElementById('geocodeSiegeBtn');
        
        const lat = document.getElementById('latitude');
        const lon = document.getElementById('longitude');
        const city = document.getElementById('locality');
        const postal = document.getElementById('postal_code');
        
        // Champs du siège social
        const siegeAdresse = document.getElementById('siege_adresse');
        const siegeVille = document.getElementById('siege_ville');
        const siegeCodePostal = document.getElementById('siege_code_postal');
        const pays = document.getElementById('pays');

        let debounce;
        let originalAddress = input.value || '';

        // Fonction pour vider les champs de localisation
        function clearLocationFields() {
            lat.value = '';
            lon.value = '';
            city.value = '';
            postal.value = '';
            input.value = '';
            updateCoordinatesDisplay();
            originalAddress = '';
        }

        // Mettre à jour l'affichage des coordonnées
        function updateCoordinatesDisplay() {
            if (lat.value && lon.value) {
                coordinatesDisplay.textContent = `${lat.value}, ${lon.value}`;
                coordinatesDisplay.className = 'text-success fw-bold';
            } else {
                coordinatesDisplay.textContent = 'Non géocodé';
                coordinatesDisplay.className = 'text-danger';
            }
        }

        // Initialiser l'affichage des coordonnées
        updateCoordinatesDisplay();

        // Fonction générique pour géocoder
        async function geocodeAddress(address, targetLat, targetLon, targetCity, targetPostal, targetInput = null, showAlert = true) {
            if (!address || address.length < 3) {
                if (showAlert) {
                    showAlertMessage('Veuillez entrer une adresse valide (au moins 3 caractères)', 'warning');
                }
                return false;
            }
            
            try {
                const res = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=1&q=${encodeURIComponent(address)}`
                );
                
                if (!res.ok) throw new Error(`Erreur HTTP: ${res.status}`);
                
                const data = await res.json();
                
                if (!data.length) {
                    if (showAlert) {
                        showAlertMessage('Aucun résultat trouvé pour cette adresse', 'warning');
                    }
                    return false;
                }
                
                const place = data[0];
                
                if (targetLat) targetLat.value = place.lat;
                if (targetLon) targetLon.value = place.lon;
                if (targetCity) targetCity.value = place.address.city || place.address.town || place.address.village || '';
                if (targetPostal) targetPostal.value = place.address.postcode || '';
                if (targetInput) targetInput.value = place.display_name;
                
                if (showAlert) {
                    showAlertMessage('Adresse géocodée avec succès !', 'success');
                }
                
                return true;
            } catch (error) {
                console.error('Erreur de géocodage:', error);
                if (showAlert) {
                    showAlertMessage('Erreur lors du géocodage. Veuillez réessayer.', 'danger');
                }
                return false;
            }
        }

        // Fonction pour afficher des alertes
        function showAlertMessage(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }

        // Géocodage du siège social
        geocodeSiegeBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            const address = `${siegeAdresse.value}, ${siegeVille.value}, ${siegeCodePostal.value}, ${pays.value}`;
            await geocodeAddress(address, lat, lon, city, postal, input, true);
        });

        // Écouteur pour le bouton de géocodage principal
        geocodeBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            await geocodeAddress(input.value, lat, lon, city, postal, input, true);
        });

        // Écouteur pour le bouton de suppression
        clearLocationBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Voulez-vous vraiment vider les coordonnées GPS ?')) {
                clearLocationFields();
                showAlertMessage('Coordonnées vidées', 'info');
            }
        });

        // Auto-complétion
        input.addEventListener('input', function() {
            const query = input.value.trim();

            if (query === '') {
                suggestions.style.display = 'none';
                originalAddress = '';
                return;
            }

            if (query.length < 3) {
                suggestions.style.display = 'none';
                return;
            }

            clearTimeout(debounce);
            debounce = setTimeout(async () => {
                try {
                    const res = await fetch(
                        `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=5&q=${encodeURIComponent(query)}`
                    );
                    
                    const data = await res.json();
                    
                    if (!data.length) {
                        suggestions.style.display = 'none';
                        return;
                    }

                    suggestions.innerHTML = data.map(place => `
                        <div class="p-2 border-bottom suggestion-item"
                             style="cursor:pointer"
                             data-lat="${place.lat}"
                             data-lon="${place.lon}"
                             data-city="${place.address.city || place.address.town || place.address.village || ''}"
                             data-postal="${place.address.postcode || ''}"
                             data-display="${place.display_name}">
                            <div class="fw-bold">${place.display_name.split(',')[0]}</div>
                            <small class="text-muted">${place.display_name.split(',').slice(1, 3).join(',').trim()}</small>
                            <br>
                            <small class="text-primary">
                                <i class="fas fa-map-pin"></i> ${place.lat}, ${place.lon}
                            </small>
                        </div>
                    `).join('');

                    suggestions.style.display = 'block';

                    // Ajouter les écouteurs aux suggestions
                    document.querySelectorAll('.suggestion-item').forEach(item => {
                        item.addEventListener('click', function() {
                            input.value = this.dataset.display;
                            lat.value = this.dataset.lat;
                            lon.value = this.dataset.lon;
                            city.value = this.dataset.city;
                            postal.value = this.dataset.postal;
                            
                            originalAddress = this.dataset.display;
                            suggestions.style.display = 'none';
                            updateCoordinatesDisplay();
                            showAlertMessage('Adresse sélectionnée', 'success');
                        });
                        
                        item.addEventListener('mouseenter', function() {
                            this.style.backgroundColor = '#f8f9fa';
                        });
                        item.addEventListener('mouseleave', function() {
                            this.style.backgroundColor = '';
                        });
                    });

                } catch (error) {
                    console.error('Erreur de recherche:', error);
                    suggestions.style.display = 'none';
                }
            }, 300);
        });

        // Cacher les suggestions au clic extérieur
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.style.display = 'none';
            }
        });

        // Géocoder avec Enter
        input.addEventListener('keypress', async function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                await geocodeAddress(input.value, lat, lon, city, postal, input, true);
            }
        });

    } else {
        // === MODE MODIFICATION ===
        const coordinatesDisplay = document.getElementById('coordinates-display');
        const lat = document.getElementById('latitude');
        const lon = document.getElementById('longitude');
        
        function updateCoordinatesDisplay() {
            if (lat.value && lon.value) {
                coordinatesDisplay.textContent = `${lat.value}, ${lon.value}`;
                coordinatesDisplay.className = 'text-success fw-bold';
            } else {
                coordinatesDisplay.textContent = 'Non géocodé';
                coordinatesDisplay.className = 'text-danger';
            }
        }
        
        updateCoordinatesDisplay();
    }
});
</script>

<style>
.suggestion-item:hover {
    background-color: #f8f9fa !important;
}
.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.form-control.bg-light {
    background-color: #f8f9fa !important;
    cursor: not-allowed;
}
fieldset {
    background-color: #f9f9f9;
}
</style>