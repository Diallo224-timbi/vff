@extends('base')

@section('title', 'Cartographie des structures')

@section('content')
<div class="container-fluid px-0">
    <div class="container mx-auto px-4 py-6">
        <!-- Section de contrôle -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
            <!-- Filtres -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-5 h-full">
                    <h3 class="text-lg font-bold text-[#255156] mb-4 flex items-center">
                        <i class="fas fa-filter mr-2"></i>
                        Filtres
                    </h3>
                    
                    <!-- Recherche globale -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Recherche</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="mapSearch" 
                                   placeholder="Nom, ville, responsable..."
                                   class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3] focus:border-[#8bbdc3] focus:outline-none">
                        </div>
                    </div>

                    <!-- Filtre par type -->
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-semibold text-gray-700">Type de structure</label>
                            <div class="flex space-x-2">
                                <button type="button" onclick="checkAll('.type-filter')" 
                                        class="text-xs text-[#255156] hover:text-[#1d4144] font-medium px-2 py-1 rounded hover:bg-gray-100">
                                    Tout cocher
                                </button>
                                <button type="button" onclick="uncheckAll('.type-filter')" 
                                        class="text-xs text-gray-600 hover:text-gray-800 font-medium px-2 py-1 rounded hover:bg-gray-100">
                                    Tout décocher
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-2 type-filter-container">
                            @php
                                $types = $structures->pluck('type_structure')->unique()->filter()->sort();
                            @endphp
                            @foreach($types as $type)
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded">
                                    <input type="checkbox" 
                                           value="{{ $type }}" 
                                           class="type-filter mr-3 rounded text-[#255156] focus:ring-[#255156]"
                                           checked>
                                    <span class="text-sm">{{ $type ?: 'Non spécifié' }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Filtre par catégorie -->
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-semibold text-gray-700">Catégorie d'intervention</label>
                            <div class="flex space-x-2">
                                <button type="button" onclick="checkAll('.category-filter')" 
                                        class="text-xs text-[#255156] hover:text-[#1d4144] font-medium px-2 py-1 rounded hover:bg-gray-100">
                                    Tout cocher
                                </button>
                                <button type="button" onclick="uncheckAll('.category-filter')" 
                                        class="text-xs text-gray-600 hover:text-gray-800 font-medium px-2 py-1 rounded hover:bg-gray-100">
                                    Tout décocher
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-2 category-filter-container">
                            @php
                                $categories = $structures
                                    ->pluck('categories')
                                    ->filter()
                                    ->map(fn($item) => array_map('trim', explode(',', $item)))
                                    ->flatten()
                                    ->unique()
                                    ->sort()
                                    ->values();
                            @endphp


                            @foreach($categories as $category)
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded">
                                    <input type="checkbox" 
                                           value="{{ $category }}" 
                                           class="category-filter mr-3 rounded text-[#255156] focus:ring-[#255156]"
                                           checked>
                                    <span class="text-sm">{{ $category ?: 'Non spécifié' }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-sm text-gray-600 mb-1">Structures visibles:</div>
                        <div class="flex justify-between items-center">
                            <span id="visibleCount" class="text-lg font-bold text-[#255156]">{{ $structures->count() }}</span>
                            <span class="text-sm text-gray-500">/ {{ $structures->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte et panneau de détails -->
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-150px)]">
                    <!-- Carte -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 h-full">
                            <div id="map" class="w-full h-full rounded-lg overflow-hidden"></div>
                        </div>
                    </div>

                    <!-- Panneau de détails -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 h-full flex flex-col">
                            <!-- En-tête du panneau -->
                            <div class="bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white p-4">
                                <h3 class="text-lg font-bold flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Détails de la structure
                                </h3>
                                <p class="text-sm text-white/80 mt-1" id="selectionStatus">
                                    Cliquez sur un point de la carte
                                </p>
                            </div>

                            <!-- Contenu du panneau avec cadre fixe et scroll -->
                            <div class="flex-1 overflow-hidden flex flex-col">
                                <div id="detailsPanelContent" class="flex-1 overflow-y-auto p-4">
                                    <!-- Message par défaut -->
                                    <div id="defaultMessage" class="text-center py-12">
                                        <i class="fas fa-map-marker-alt text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500">Sélectionnez une structure sur la carte pour voir ses détails</p>
                                    </div>

                                    <!-- Les détails seront injectés ici dynamiquement -->
                                    <div id="structureDetails" class="hidden"></div>
                                </div>

                                <!-- Actions fixées en bas -->
                                <div class="border-t p-3 bg-gray-50 shrink-0">
                                    <button id="resetViewBtn" 
                                            class="w-full bg-white border border-[#255156] text-[#255156] hover:bg-[#255156] hover:text-white py-2 px-4 rounded-lg transition-all duration-200 font-medium flex items-center justify-center">
                                        <i class="fas fa-sync-alt mr-2"></i>
                                        Réinitialiser la vue
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet CSS et JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Variables globales pour les markers
    let markers = [];
    let currentSelectedMarker = null;
    let zoomTimeout = null;
    
    // Définir les limites de la Côte d'Azur
    const coteAzurBounds = L.latLngBounds(
        L.latLng(43.2, 6.2),  // Sud-ouest
        L.latLng(44.0, 7.8)   // Nord-est
    );
    
    // Initialisation de la carte centrée sur la Côte d'Azur
    const map = L.map('map', {
        center: [43.6, 7.0],
        zoom: 9,
        maxBounds: coteAzurBounds,
        maxBoundsViscosity: 1.0
    });

    // Tuile OSM avec style clair
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);

    // Données des structures
    const structures = @json($structures);

    // Fonctions pour gérer les cases à cocher
    window.checkAll = function(selector) {
        document.querySelectorAll(selector).forEach(checkbox => {
            checkbox.checked = true;
        });
        filterMarkers();
    };

    window.uncheckAll = function(selector) {
        document.querySelectorAll(selector).forEach(checkbox => {
            checkbox.checked = false;
        });
        filterMarkers();
    };

    // Fonction pour zoomer sur une localisation
    window.zoomToLocation = function(lat, lon, ville) {
        map.setView([lat, lon], 13);
        
        // Mettre en surbrillance tous les boutons de localisation
        document.querySelectorAll('.location-btn').forEach(btn => {
            btn.classList.remove('ring-2', 'ring-offset-1');
            btn.classList.remove('bg-white');
            btn.classList.add('bg-gradient-to-r');
        });
        
        // Mettre en surbrillance le bouton cliqué
        const activeBtn = Array.from(document.querySelectorAll('.location-btn')).find(btn => 
            btn.textContent.includes(ville) || 
            btn.textContent.includes(ville.substring(0, 3))
        );
        
        if (activeBtn) {
            activeBtn.classList.remove('bg-gradient-to-r');
            activeBtn.classList.add('bg-white', 'ring-2', 'ring-offset-1');
            
        
        }
    };

    // Fonction pour déterminer la couleur selon le type
    function getColorByType(type) {
        if (!type) return '#6b7280';
        
        const typeLower = type.toLowerCase();
        if (typeLower.includes('siège') || typeLower.includes('siege')) return '#3b82f6';
        if (typeLower.includes('antenne')) return '#10b981';
        if (typeLower.includes('association')) return '#ef4444';
        if (typeLower.includes('public') || typeLower.includes('institution')) return '#8b5cf6';
        return '#f59e0b';
    }

    // Fonction pour créer l'icône personnalisée
    function createCustomIcon(color) {
        return L.divIcon({
            className: 'custom-marker',
            html: `<div style="background-color: ${color}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
    }

    // Fonction pour créer l'icône sélectionnée
    function createSelectedIcon() {
        return L.divIcon({
            className: 'selected-marker',
            html: `<div style="background-color: #dc2626; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 3px #fca5a5, 0 2px 10px rgba(0,0,0,0.4); cursor: pointer; animation: pulse 1.5s infinite;"></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });
    }

    // Fonction pour afficher les détails d'une structure
    function showStructureDetails(structure) {
        console.log('Affichage des détails pour:', structure);
        
        // Préparer les liens
        const googleMapsLink = structure.latitude && structure.longitude ? 
            `https://www.google.com/maps?q=${structure.latitude},${structure.longitude}&z=15` : '';
        
        const phoneLink = structure.telephone ? 
            `tel:${structure.telephone.replace(/\s/g, '')}` : '';
        
        const emailLink = structure.email ? 
            `mailto:${structure.email}` : '';
        
        const detailsHtml = `
            <div class="space-y-4 animate-fade-in">
                <!-- En-tête -->
                <div class="border-b pb-3">
                    <h4 class="text-xl font-bold text-[#255156] mb-2">${structure.organisme || 'Structure'}</h4>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white" 
                              style="background-color: ${getColorByType(structure.type_structure)}">
                            ${structure.type_structure || 'Non spécifié'}
                        </span>
                        ${structure.categories ? `
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                ${structure.categories}
                            </span>
                        ` : ''}
                    </div>
                </div>

                <!-- Localisation -->
                <div>
                    <h5 class="font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        Localisation
                    </h5>
                    <div class="space-y-1 text-sm">
                        ${structure.adresse ? `<p><strong>Adresse:</strong> ${structure.adresse}</p>` : ''}
                        <p><strong>Ville:</strong> ${structure.ville || 'Non spécifié'} ${structure.code_postal || ''}</p>
                        ${structure.zone ? `<p><strong>Zone:</strong> ${structure.zone}</p>` : ''}
                        ${structure.latitude && structure.longitude ? `
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-crosshairs mr-1"></i>
                                Coordonnées: ${parseFloat(structure.latitude).toFixed(6)}, ${parseFloat(structure.longitude).toFixed(6)}
                            </p>
                        ` : ''}
                        ${googleMapsLink ? `
                            <div class="mt-2">
                                <a href="${googleMapsLink}" target="_blank" 
                                   class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded text-sm font-medium transition-colors">
                                    <i class="fab fa-google text-blue-500"></i>
                                    Voir sur Google Maps
                                </a>
                            </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Contact -->
                <div>
                    <h5 class="font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-address-card text-blue-500 mr-2"></i>
                        Contact
                    </h5>
                    <div class="space-y-2 text-sm">
                        ${structure.telephone ? `
                            <div class="flex items-center gap-2 contact-item p-2 rounded hover:bg-gray-50">
                                <i class="fas fa-phone text-gray-400 w-4"></i>
                                <div>
                                    <strong>Téléphone:</strong>
                                    <a href="${phoneLink}" class="block text-[#255156] hover:underline">
                                        ${structure.telephone}
                                    </a>
                                </div>
                            </div>
                        ` : ''}
                        
                        ${structure.email ? `
                            <div class="flex items-center gap-2 contact-item p-2 rounded hover:bg-gray-50">
                                <i class="fas fa-envelope text-gray-400 w-4"></i>
                                <div>
                                    <strong>Email:</strong>
                                    <a href="${emailLink}" class="block text-[#255156] hover:underline break-all">
                                        ${structure.email}
                                    </a>
                                </div>
                            </div>
                        ` : ''}
                        
                        ${structure.site ? `
                            <div class="flex items-center gap-2 contact-item p-2 rounded hover:bg-gray-50">
                                <i class="fas fa-globe text-gray-400 w-4"></i>
                                <div>
                                    <strong>Site web:</strong>
                                    <a href="${structure.site}" target="_blank" class="block text-[#255156] hover:underline break-all">
                                        ${structure.site}
                                    </a>
                                </div>
                            </div>
                        ` : ''}
                        
                        ${structure.contact ? `
                            <div class="flex items-center gap-2 p-2">
                                <i class="fas fa-user text-gray-400 w-4"></i>
                                <div>
                                    <strong>Contact:</strong>
                                    <p>${structure.contact}</p>
                                </div>
                            </div>
                        ` : ''}
                        
                        ${structure.responsable ? `
                            <div class="flex items-center gap-2 p-2">
                                <i class="fas fa-user-tie text-gray-400 w-4"></i>
                                <div>
                                    <strong>Responsable:</strong>
                                    <p>${structure.responsable}</p>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div>
                    <h5 class="font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>
                        Informations
                    </h5>
                    <div class="space-y-1 text-sm">
                        ${structure.public_cible ? `<p><strong>Public cible:</strong> ${structure.public_cible}</p>` : ''}
                        ${structure.description ? `
                            <div>
                                <strong>Description:</strong>
                                <p class="mt-1 text-gray-600 bg-gray-50 p-2 rounded text-sm">${structure.description}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-3 border-t">
                    <div class="flex flex-col gap-2">
                        ${structure.latitude && structure.longitude ? `
                            <button onclick="zoomToStructure(${structure.latitude}, ${structure.longitude})" 
                                    class="w-full bg-[#255156] text-white py-2 px-3 rounded text-sm font-medium hover:bg-[#1d4144] transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-search-plus"></i>
                                Zoomer sur la carte
                            </button>
                        ` : ''}
                        ${googleMapsLink ? `
                            <a href="${googleMapsLink}" target="_blank"
                               class="w-full bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded text-sm font-medium hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                                <i class="fab fa-google"></i>
                                Ouvrir dans Google Maps
                            </a>
                        ` : ''}
                        <a href="/structures/${structure.id}" 
                           class="w-full bg-white border border-[#255156] text-[#255156] py-2 px-3 rounded text-sm font-medium hover:bg-[#255156]/5 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-external-link-alt"></i>
                            Page détaillée
                        </a>
                    </div>
                </div>
            </div>
        `;

        // Mettre à jour le panneau
        const detailsContainer = document.getElementById('structureDetails');
        const defaultMessage = document.getElementById('defaultMessage');
        const selectionStatus = document.getElementById('selectionStatus');
        const detailsPanelContent = document.getElementById('detailsPanelContent');

        detailsContainer.innerHTML = detailsHtml;
        detailsContainer.classList.remove('hidden');
        defaultMessage.classList.add('hidden');
        selectionStatus.textContent = 'Structure sélectionnée';
        
        // Scroll vers le haut
        detailsPanelContent.scrollTop = 0;
    }

    // Fonction pour zoomer sur une structure
    window.zoomToStructure = function(lat, lon) {
        map.setView([lat, lon], 14);
        
        // Réinitialiser la mise en surbrillance des boutons de localisation
        document.querySelectorAll('.location-btn').forEach(btn => {
            btn.classList.remove('ring-2', 'ring-offset-1', 'bg-white');
            btn.classList.add('bg-gradient-to-r');
        });
    };

    // Fonction pour ajouter un marker
    function addMarker(structure) {
        if (structure.latitude && structure.longitude) {
            const color = getColorByType(structure.type_structure);
            const icon = createCustomIcon(color);
            
            const marker = L.marker([structure.latitude, structure.longitude], { icon })
                .addTo(map);
            
            // Popup simplifiée
            marker.bindPopup(`
                <div class="p-2">
                    <strong class="text-[#255156]">${structure.organisme}</strong><br>
                    <small class="text-gray-600">${structure.ville || ''}</small>
                </div>
            `);

            // Événement de clic pour afficher les détails
            marker.on('click', function(e) {
                // Réinitialiser le style des autres markers
                if (currentSelectedMarker && currentSelectedMarker !== marker) {
                    const originalColor = currentSelectedMarker.originalColor;
                    currentSelectedMarker.marker.setIcon(createCustomIcon(originalColor));
                }

                // Mettre en surbrillance le marker sélectionné
                marker.setIcon(createSelectedIcon());
                currentSelectedMarker = {
                    marker: marker,
                    structure: structure,
                    originalColor: color
                };

                // Afficher les détails
                showStructureDetails(structure);

                // Ouvrir le popup
                marker.openPopup();
                
                // Zoom sur le marker sélectionné
                map.setView([structure.latitude, structure.longitude], Math.max(map.getZoom(), 13));
                
                // Réinitialiser la mise en surbrillance des boutons de localisation
                document.querySelectorAll('.location-btn').forEach(btn => {
                    btn.classList.remove('ring-2', 'ring-offset-1', 'bg-white');
                    btn.classList.add('bg-gradient-to-r');
                });
            });

            markers.push({
                marker: marker,
                structure: structure,
                originalColor: color
            });
        }
    }

    // Ajouter tous les markers
    structures.forEach(addMarker);

    // Fonction pour zoomer automatiquement sur les markers visibles
    function zoomToVisibleMarkers() {
        const visibleMarkers = markers.filter(({ marker }) => map.hasLayer(marker));
        
        if (visibleMarkers.length === 0) {
            // Si aucun marker visible, revenir à la vue globale
            map.setView([43.6, 7.0], 9);
            return;
        }
        
        if (visibleMarkers.length === 1) {
            // Si un seul marker visible, zoomer dessus
            const marker = visibleMarkers[0].marker;
            const latlng = marker.getLatLng();
            map.setView([latlng.lat, latlng.lng], 14);
        } else {
            // Si plusieurs markers visibles, ajuster la vue pour tous les voir
            const group = new L.featureGroup(visibleMarkers.map(m => m.marker));
            const bounds = group.getBounds();
            
            // Ajouter un padding pour que les markers ne soient pas collés aux bords
            const paddedBounds = bounds.pad(0.1);
            
            // Vérifier si les bounds sont valides
            if (paddedBounds.isValid()) {
                map.fitBounds(paddedBounds, {
                    animate: true,
                    duration: 0.5,
                    padding: [50, 50], // Padding en pixels
                    maxZoom: 15 // Zoom maximum pour le fitBounds
                });
            }
        }
        
        // Réinitialiser la mise en surbrillance des boutons de localisation
        document.querySelectorAll('.location-btn').forEach(btn => {
            btn.classList.remove('ring-2', 'ring-offset-1', 'bg-white');
            btn.classList.add('bg-gradient-to-r');
        });
    }

    // Fonction pour filtrer les markers avec zoom automatique
    function filterMarkers() {
    const searchText = document.getElementById('mapSearch').value.toLowerCase();
    const typeFilters = Array.from(document.querySelectorAll('.type-filter:checked')).map(cb => cb.value);
    const categoryFilters = Array.from(document.querySelectorAll('.category-filter:checked')).map(cb => cb.value);

    let visibleCount = 0;

    markers.forEach(({ marker, structure }) => {
        const matchesSearch = !searchText || 
            (structure.organisme && structure.organisme.toLowerCase().includes(searchText)) ||
            (structure.ville && structure.ville.toLowerCase().includes(searchText)) ||
            (structure.responsable && structure.responsable.toLowerCase().includes(searchText)) ||
            (structure.adresse && structure.adresse.toLowerCase().includes(searchText));

        const matchesType = typeFilters.length === 0 || 
            typeFilters.includes(structure.type_structure) ||
            (!structure.type_structure && typeFilters.includes(''));

        const matchesCategory = categoryFilters.length === 0 || 
            (structure.categories && structure.categories
                .split(',')
                .map(c => c.trim())
                .some(c => categoryFilters.includes(c))
            ) || (!structure.categories && categoryFilters.includes(''));

        const isVisible = matchesSearch && matchesType && matchesCategory;

        if (isVisible) {
            marker.addTo(map);
            visibleCount++;
        } else {
            map.removeLayer(marker);

            if (currentSelectedMarker && currentSelectedMarker.marker === marker) {
                resetDetailsPanel();
            }
        }
    });


        
        document.getElementById('visibleCount').textContent = visibleCount;
        
        // Zoom automatique sur les markers visibles avec un délai pour éviter trop d'animations
        clearTimeout(zoomTimeout);
        zoomTimeout = setTimeout(zoomToVisibleMarkers, 200);
    }

    // Fonction pour réinitialiser le panneau de détails
    function resetDetailsPanel() {
        const detailsContainer = document.getElementById('structureDetails');
        const defaultMessage = document.getElementById('defaultMessage');
        const selectionStatus = document.getElementById('selectionStatus');

        detailsContainer.innerHTML = '';
        detailsContainer.classList.add('hidden');
        defaultMessage.classList.remove('hidden');
        selectionStatus.textContent = 'Cliquez sur un point de la carte';
        
        // Réinitialiser le marker sélectionné
        if (currentSelectedMarker) {
            currentSelectedMarker.marker.setIcon(createCustomIcon(currentSelectedMarker.originalColor));
            currentSelectedMarker.marker.closePopup();
            currentSelectedMarker = null;
        }
        
        // Réinitialiser la mise en surbrillance des boutons de localisation
        document.querySelectorAll('.location-btn').forEach(btn => {
            btn.classList.remove('ring-2', 'ring-offset-1', 'bg-white');
            btn.classList.add('bg-gradient-to-r');
        });
    }

    // Écouteurs d'événements pour les filtres avec debounce
    let filterTimeout;
    document.getElementById('mapSearch').addEventListener('input', () => {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(filterMarkers, 300);
    });
    
    document.querySelectorAll('.type-filter, .category-filter').forEach(filter => {
        filter.addEventListener('change', () => {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(filterMarkers, 300);
        });
    });

    // Réinitialiser la vue
    document.getElementById('resetViewBtn').addEventListener('click', () => {
        map.setView([43.6, 7.0], 9);
        document.getElementById('mapSearch').value = '';
        document.querySelectorAll('.type-filter, .category-filter').forEach(cb => cb.checked = true);
        filterMarkers();
        resetDetailsPanel();
    });

    // Initialiser le filtre
    filterMarkers();

    // Fonction pour ajuster la hauteur du panneau
    function adjustDetailsPanelHeight() {
        const panelContent = document.getElementById('detailsPanelContent');
        const headerHeight = document.querySelector('.lg\\:col-span-1 .bg-gradient-to-r').offsetHeight;
        const actionsHeight = document.querySelector('.lg\\:col-span-1 .border-t').offsetHeight;
        const windowHeight = window.innerHeight;
        const panelElement = document.querySelector('.lg\\:col-span-1');
        
        if (panelElement) {
            const panelOffset = panelElement.getBoundingClientRect().top;
            
            const availableHeight = windowHeight - panelOffset - headerHeight - actionsHeight - 32; // 32px pour le padding
            panelContent.style.maxHeight = `${Math.max(availableHeight, 200)}px`;
        }
    }

    // Ajuster la hauteur du panneau
    window.addEventListener('load', adjustDetailsPanelHeight);
    window.addEventListener('resize', adjustDetailsPanelHeight);
    map.on('resize', adjustDetailsPanelHeight);

    // Redimensionnement responsive
    window.addEventListener('resize', () => {
        map.invalidateSize();
        setTimeout(adjustDetailsPanelHeight, 100);
    });
});
</script>

<style>
.custom-marker {
    background: none !important;
    border: none !important;
}

.selected-marker {
    background: none !important;
    border: none !important;
}

#map {
    min-height: 500px;
}

#detailsPanelContent {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

#detailsPanelContent::-webkit-scrollbar {
    width: 8px;
}

#detailsPanelContent::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

#detailsPanelContent::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 4px;
    border: 2px solid #f1f5f9;
}

#detailsPanelContent::-webkit-scrollbar-thumb:hover {
    background-color: #94a3b8;
}

.leaflet-popup-content {
    margin: 8px 12px !important;
}

.leaflet-popup-content p {
    margin: 0 0 5px !important;
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4), 0 2px 10px rgba(0,0,0,0.4);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(220, 38, 38, 0), 0 2px 10px rgba(0,0,0,0.4);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0), 0 2px 10px rgba(0,0,0,0.4);
    }
}

.break-all {
    word-break: break-all;
}

button, a {
    transition: all 0.2s ease;
}

.contact-item {
    transition: background-color 0.2s ease;
}

.contact-item:hover {
    background-color: #f8fafc;
}

.filter-button:hover {
    transform: translateY(-1px);
}

.location-btn {
    transition: all 0.2s ease;
}

.location-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

@media (max-width: 1024px) {
    .lg\:col-span-1 {
        height: auto;
        max-height: 70vh;
    }
    
    #detailsPanelContent {
        max-height: 50vh !important;
    }
    
    .grid.grid-cols-2 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .location-btn {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
    }
}
</style>
@endsection