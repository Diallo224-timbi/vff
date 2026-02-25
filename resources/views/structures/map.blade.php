@extends('base')

@section('title', 'Cartographie des structures')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- HEADER avec boutons d'action -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold text-gray-800">
            <i class="fas fa-map-marked-alt text-[#255156] mr-2"></i>
            Cartographie des structures
        </h1>
        <div class="flex items-center gap-2">
            <span class="text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-600">{{ auth()->user()->role }}</span>
            <div class="w-8 h-8 rounded-full bg-[#255156] flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <!-- STATISTIQUES SIMPLES -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-4">
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-xs text-gray-500 mb-1">Total structures</div>
            <div class="text-xl font-semibold text-[#255156]" id="totalStructures">{{ $structures->count() }}</div>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-xs text-gray-500 mb-1">Structures visibles</div>
            <div class="text-xl font-semibold text-green-600" id="visibleCount">{{ $structures->count() }}</div>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-xs text-gray-500 mb-1">Types différents</div>
            <div class="text-xl font-semibold text-blue-600">{{ $structures->pluck('type_structure')->unique()->filter()->count() }}</div>
        </div>
    </div>

    <!-- SECTION PRINCIPALE -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <!-- FILTRES (colonne de gauche) -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                <h3 class="font-medium text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-filter text-[#255156] mr-2"></i>
                    Filtres
                </h3>
                
                <!-- Recherche -->
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Recherche</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="mapSearch" 
                               placeholder="Nom, ville..."
                               class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-[#255156] focus:outline-none">
                    </div>
                </div>

                <!-- Type de structure -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-medium text-gray-700">Type de structure</label>
                        <div class="flex gap-2">
                            <button onclick="checkAll('.type-filter')" class="text-[10px] bg-[#255156] text-white px-2 py-0.5 rounded hover:bg-[#1d4144]">Tous</button>
                            <button onclick="uncheckAll('.type-filter')" class="text-[10px] bg-gray-200 text-gray-700 px-2 py-0.5 rounded hover:bg-gray-300">Aucun</button>
                        </div>
                    </div>
                    <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1 border border-gray-100 rounded p-2">
                        @php
                            $types = $structures->pluck('type_structure')->unique()->filter()->sort();
                        @endphp
                        @foreach($types as $type)
                            <label class="flex items-center cursor-pointer text-xs hover:bg-gray-50 p-1 rounded">
                                <input type="checkbox" value="{{ $type }}" class="type-filter mr-2 rounded text-[#255156]" checked>
                                <span class="truncate">{{ $type }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Catégorie -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-medium text-gray-700">Catégorie</label>
                        <div class="flex gap-2">
                            <button onclick="checkAll('.category-filter')" class="text-[10px] bg-[#255156] text-white px-2 py-0.5 rounded hover:bg-[#1d4144]">Tous</button>
                            <button onclick="uncheckAll('.category-filter')" class="text-[10px] bg-gray-200 text-gray-700 px-2 py-0.5 rounded hover:bg-gray-300">Aucun</button>
                        </div>
                    </div>
                    <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1 border border-gray-100 rounded p-2">
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
                            <label class="flex items-center cursor-pointer text-xs hover:bg-gray-50 p-1 rounded">
                                <input type="checkbox" value="{{ $category }}" class="category-filter mr-2 rounded text-[#255156]" checked>
                                <span class="truncate">{{ $category }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Bouton réinitialiser -->
                <button id="resetViewBtn" 
                        class="w-full bg-[#255156] hover:bg-[#1d4144] text-white py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition-colors">
                    <i class="fas fa-sync-alt"></i>
                    Réinitialiser
                </button>
            </div>
        </div>

        <!-- CARTE (colonne centrale) -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden" style="height: 650px;">
                <div id="map" class="w-full h-full"></div>
            </div>
        </div>

        <!-- PANNEAU DÉTAILS (colonne de droite) -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm h-[650px] flex flex-col">
                <!-- En-tête -->
                <div class="bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white px-4 py-3 rounded-t-lg">
                    <h3 class="font-medium flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Détails de la structure
                    </h3>
                </div>

                <!-- Contenu défilant -->
                <div class="flex-1 overflow-y-auto p-4" id="detailsPanelContent">
                    <div id="defaultMessage" class="text-center py-12">
                        <i class="fas fa-map-marker-alt text-5xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-500">Cliquez sur un point de la carte</p>
                        <p class="text-xs text-gray-400 mt-1">pour voir les détails</p>
                    </div>
                    <div id="structureDetails" class="hidden"></div>
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
    // Variables globales
    let markers = [];
    let currentSelectedMarker = null;
    let zoomTimeout = null;
    
    // Limites
    const coteAzurBounds = L.latLngBounds(
        L.latLng(43.2, 6.2),
        L.latLng(44.0, 7.8)
    );
    
    // Initialisation carte
    const map = L.map('map', {
        center: [43.6, 7.0],
        zoom: 9,
        maxBounds: coteAzurBounds,
        maxBoundsViscosity: 1.0
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 18,
    }).addTo(map);

    // Données
    const structures = @json($structures);

    // Fonctions check/uncheck
    window.checkAll = function(selector) {
        document.querySelectorAll(selector).forEach(cb => cb.checked = true);
        filterMarkers();
    };
    
    window.uncheckAll = function(selector) {
        document.querySelectorAll(selector).forEach(cb => cb.checked = false);
        filterMarkers();
    };

    // Icône de géolocalisation
    function createCustomIcon(color) {
        return L.divIcon({
            className: 'custom-marker',
            html: `
                <div class="marker-pin" style="background-color: ${color};">
                    <i class="fas fa-map-pin" style="color: white; font-size: 14px;"></i>
                </div>
                <div class="marker-pulse" style="border-color: ${color};"></div>
            `,
            iconSize: [30, 42],
            iconAnchor: [15, 42],
            popupAnchor: [0, -30]
        });
    }

    function createSelectedIcon() {
        return L.divIcon({
            className: 'selected-marker',
            html: `
                <div class="marker-pin selected" style="background-color: #dc2626;">
                    <i class="fas fa-map-pin" style="color: white; font-size: 16px;"></i>
                </div>
                <div class="marker-pulse selected" style="border-color: #dc2626;"></div>
            `,
            iconSize: [36, 50],
            iconAnchor: [18, 50],
            popupAnchor: [0, -35]
        });
    }

    // Couleur selon type
    function getColorByType(type) {
        if (!type) return '#6b7280';
        const t = type.toLowerCase();
        if (t.includes('siége') || t.includes('siege')) return '#3b82f6';
        if (t.includes('antenne')) return '#10b981';
        if (t.includes('association')) return '#ef4444';
        if (t.includes('santé') || t.includes('droit')) return '#8b5cf6';
        return '#f59e0b';
    }

    // Fonction pour créer le contenu de l'infobulle (popup)
    function createPopupContent(structure) {
        const logoUrl = structure.logo ? `{{ asset('storage') }}/${structure.logo}` : null;
        const color = getColorByType(structure.type_structure);
        
        return `
            <div class="popup-content" style="min-width: 250px; max-width: 280px;">
                <!-- En-tête avec logo -->
                <div class="flex items-center gap-2 border-b pb-2 mb-2">
                    <div class="w-8 h-8 bg-gray-100 rounded overflow-hidden flex items-center justify-center">
                        ${logoUrl ? `
                            <img src="${logoUrl}" alt="Logo" class="w-full h-full object-contain">
                        ` : `
                            <i class="fas fa-building text-gray-400 text-sm"></i>
                        `}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm text-[#255156]">${structure.organisme || 'Structure'}</h4>
                        <span class="inline-block px-2 py-0.5 text-[9px] rounded-full text-white" 
                              style="background-color: ${color};">
                            ${structure.type_structure || 'Non spécifié'}
                        </span>
                    </div>
                </div>

                <!-- Informations rapides -->
                <div class="space-y-1.5 text-xs">
                    <p class="flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 w-4"></i>
                        <span class="text-gray-700">${structure.ville || 'Ville non spécifiée'}</span>
                    </p>
                    
                    ${structure.telephone ? `
                        <p class="flex items-center">
                            <i class="fas fa-phone text-[#255156] w-4"></i>
                            <span class="text-gray-700">${structure.telephone}</span>
                        </p>
                    ` : ''}
                    
                    ${structure.email ? `
                        <p class="flex items-center">
                            <i class="fas fa-envelope text-[#255156] w-4"></i>
                            <span class="text-gray-700 truncate">${structure.email.substring(0, 25)}...</span>
                        </p>
                    ` : ''}
                    
                    ${structure.categories ? `
                        <p class="flex items-start mt-1">
                            <i class="fas fa-tag text-gray-500 w-4 mt-0.5"></i>
                            <span class="text-gray-600 text-[9px]">${structure.categories.split(',').slice(0, 2).join(', ')}${structure.categories.split(',').length > 2 ? '...' : ''}</span>
                        </p>
                    ` : ''}
                </div>

                <!-- Lien pour voir plus -->
                <div class="mt-2 pt-1 border-t border-gray-100 text-center">
                    <span class="text-[9px] text-[#255156] font-medium">
                        <i class="fas fa-info-circle mr-1"></i>
                        Cliquez pour plus de détails
                    </span>
                </div>
            </div>
        `;
    }

    // Afficher détails (panneau latéral)
    function showStructureDetails(structure) {
        const logoUrl = structure.logo ? `{{ asset('storage') }}/${structure.logo}` : null;
        
        const detailsHtml = `
            <div class="space-y-4">
                <!-- En-tête avec logo -->
                <div class="flex items-center gap-3 border-b pb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg overflow-hidden border-2 border-gray-200 flex items-center justify-center shadow-sm">
                        ${logoUrl ? `
                            <img src="${logoUrl}" alt="Logo" class="w-full h-full object-contain">
                        ` : `
                            <i class="fas fa-building text-gray-400 text-xl"></i>
                        `}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-[#255156]">${structure.organisme || 'Structure'}</h4>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="inline-block px-2 py-0.5 text-[10px] rounded-full text-white font-medium" 
                                  style="background-color: ${getColorByType(structure.type_structure)}">
                                ${structure.type_structure || 'Non spécifié'}
                            </span>
                            ${structure.categories ? `
                                <span class="inline-block px-3 py-1 bg-gray-200 text-gray-900 rounded-lg text-[12px] font-semibold shadow-sm">
                                    ${structure.categories.split(',').length} cat.
                                    <h3 class="">
                                        ${structure.categories}
                                    </h3>
                                </span>
                            ` : ''}
                        </div>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="bg-blue-50 p-3 rounded-lg">
                    <h5 class="text-xs font-semibold text-blue-800 mb-2 flex items-center">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        Localisation
                    </h5>
                    <div class="text-xs space-y-1">
                        ${structure.adresse ? `<p class="text-gray-700"><span class="font-medium">Adresse:</span> ${structure.adresse}</p>` : ''}
                        <p class="text-gray-700"><span class="font-medium">Ville:</span> ${structure.ville || 'Non spécifié'} ${structure.code_postal ? '('+structure.code_postal+')' : ''}</p>
                        ${structure.zone ? `<p class="text-gray-700"><span class="font-medium">Zone:</span> ${structure.zone}</p>` : ''}
                    </div>
                </div>

                <!-- Contact -->
                <div class="bg-gray-50 p-3 rounded-lg">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-address-card mr-1"></i>
                        Contact
                    </h5>
                    <div class="text-xs space-y-2">
                        ${structure.telephone ? `
                            <div class="flex items-center">
                                <i class="fas fa-phone text-[#255156] w-5"></i>
                                <a href="tel:${structure.telephone.replace(/\s/g,'')}" class="text-gray-700 hover:text-[#255156]">
                                    ${structure.telephone}
                                </a>
                            </div>
                        ` : ''}
                        ${structure.email ? `
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-[#255156] w-5"></i>
                                <a href="mailto:${structure.email}" class="text-gray-700 hover:text-[#255156] break-all">
                                    ${structure.email}
                                </a>
                            </div>
                        ` : ''}
                        ${structure.site ? `
                            <div class="flex items-center">
                                <i class="fas fa-globe text-[#255156] w-5"></i>
                                <a href="${structure.site}" target="_blank" class="text-gray-700 hover:text-[#255156] break-all">
                                    ${structure.site.substring(0, 30)}...
                                </a>
                            </div>
                        ` : ''}
                        ${structure.responsable ? `
                            <div class="flex items-center">
                                <i class="fas fa-user-tie text-[#255156] w-5"></i>
                                <span class="text-gray-700">${structure.responsable}</span>
                            </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Description -->
                ${structure.description ? `
                    <div class="text-xs">
                        <h5 class="font-medium text-gray-700 mb-1">Description:</h5>
                        <p 
                            class="text-gray-800 bg-gray-100 p-3 rounded-md text-sm"
                            title="${structure.description}">
                            ${structure.description.length > 100 
                                ? structure.description.slice(0, 100) + "..." 
                                : structure.description
                            }
                        </p>
                    </div>
                ` : ''}

                <!-- Bouton zoom -->
                ${structure.latitude && structure.longitude ? `
                    <button onclick="zoomToStructure(${structure.latitude}, ${structure.longitude})" 
                            class="w-full mt-3 bg-[#255156] text-white py-2 rounded-lg text-sm hover:bg-[#1d4144] transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-search-plus"></i>
                        Centrer sur la carte
                    </button>
                ` : ''}
            </div>
        `;

        document.getElementById('structureDetails').innerHTML = detailsHtml;
        document.getElementById('structureDetails').classList.remove('hidden');
        document.getElementById('defaultMessage').classList.add('hidden');
        
        // Scroll en haut du panneau
        document.getElementById('detailsPanelContent').scrollTop = 0;
    }

    window.zoomToStructure = function(lat, lon) {
        map.setView([lat, lon], 15);
        
        // Réinitialiser la sélection
        if (currentSelectedMarker) {
            currentSelectedMarker.marker.setIcon(createCustomIcon(currentSelectedMarker.originalColor));
        }
    };

    // Ajouter marker avec la nouvelle icône et popup
    function addMarker(structure) {
        if (!structure.latitude || !structure.longitude) return;
        
        const color = getColorByType(structure.type_structure);
        const icon = createCustomIcon(color);
        
        const marker = L.marker([structure.latitude, structure.longitude], { icon }).addTo(map);
        
        // 🟢 AJOUT DE L'INFOBULLE (POPUP)
        marker.bindPopup(createPopupContent(structure), {
            maxWidth: 300,
            minWidth: 250,
            className: 'custom-popup'
        });
        
        marker.on('click', function() {
            // Ouvrir le popup
            marker.openPopup();
            
            // Mettre à jour la sélection
            if (currentSelectedMarker) {
                currentSelectedMarker.marker.setIcon(createCustomIcon(currentSelectedMarker.originalColor));
            }
            marker.setIcon(createSelectedIcon());
            currentSelectedMarker = { marker, structure, originalColor: color };
            
            // Afficher les détails dans le panneau latéral
            showStructureDetails(structure);
            
            // Animation de zoom léger
            map.setView([structure.latitude, structure.longitude], Math.max(map.getZoom(), 13));
        });

        markers.push({ marker, structure, originalColor: color });
    }

    structures.forEach(addMarker);

    // Filtrer
    function filterMarkers() {
        const search = document.getElementById('mapSearch').value.toLowerCase();
        const typeFilters = Array.from(document.querySelectorAll('.type-filter:checked')).map(cb => cb.value);
        const categoryFilters = Array.from(document.querySelectorAll('.category-filter:checked')).map(cb => cb.value);

        let visibleCount = 0;

        markers.forEach(({ marker, structure }) => {
            const matchSearch = !search || 
                (structure.organisme && structure.organisme.toLowerCase().includes(search)) ||
                (structure.ville && structure.ville.toLowerCase().includes(search)) ||
                (structure.responsable && structure.responsable.toLowerCase().includes(search));

            const matchType = typeFilters.length === 0 || typeFilters.includes(structure.type_structure);
            
            const matchCategory = categoryFilters.length === 0 || 
                (structure.categories && structure.categories.split(',').map(c => c.trim()).some(c => categoryFilters.includes(c)));

            const visible = matchSearch && matchType && matchCategory;

            if (visible) {
                if (!map.hasLayer(marker)) marker.addTo(map);
                visibleCount++;
            } else {
                if (map.hasLayer(marker)) map.removeLayer(marker);
                if (currentSelectedMarker?.marker === marker) {
                    document.getElementById('structureDetails').classList.add('hidden');
                    document.getElementById('defaultMessage').classList.remove('hidden');
                    currentSelectedMarker = null;
                }
            }
        });

        document.getElementById('visibleCount').textContent = visibleCount;
    }

    // Événements
    document.getElementById('mapSearch').addEventListener('input', filterMarkers);
    document.querySelectorAll('.type-filter, .category-filter').forEach(cb => {
        cb.addEventListener('change', filterMarkers);
    });

    // Réinitialiser
    document.getElementById('resetViewBtn').addEventListener('click', () => {
        map.setView([43.6, 7.0], 9);
        document.getElementById('mapSearch').value = '';
        document.querySelectorAll('.type-filter, .category-filter').forEach(cb => cb.checked = true);
        filterMarkers();
        
        if (currentSelectedMarker) {
            currentSelectedMarker.marker.setIcon(createCustomIcon(currentSelectedMarker.originalColor));
            currentSelectedMarker = null;
        }
        document.getElementById('structureDetails').classList.add('hidden');
        document.getElementById('defaultMessage').classList.remove('hidden');
        
        // Fermer tous les popups
        map.closePopup();
    });

    filterMarkers();
});
</script>

<style>
.custom-marker, .selected-marker {
    background: none !important;
    border: none !important;
}

.marker-pin {
    width: 30px;
    height: 30px;
    border-radius: 50% 50% 50% 0;
    background: #3b82f6;
    position: absolute;
    transform: rotate(-45deg);
    left: 50%;
    top: 50%;
    margin: -15px 0 0 -15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.marker-pin i {
    transform: rotate(45deg);
    margin-top: 2px;
}

.marker-pin.selected {
    width: 36px;
    height: 36px;
    margin: -18px 0 0 -18px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.4);
}

.marker-pulse {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 40px;
    height: 40px;
    margin-left: -20px;
    margin-top: -20px;
    border-radius: 50%;
    border: 2px solid rgba(59, 130, 246, 0.4);
    animation: pulse 1.5s infinite;
    pointer-events: none;
}

.marker-pulse.selected {
    width: 50px;
    height: 50px;
    margin-left: -25px;
    margin-top: -25px;
    border: 3px solid rgba(220, 38, 38, 0.3);
}

@keyframes pulse {
    0% {
        transform: scale(0.8);
        opacity: 0.8;
    }
    70% {
        transform: scale(1.5);
        opacity: 0;
    }
    100% {
        transform: scale(0.8);
        opacity: 0;
    }
}

/* Style pour le popup */
.custom-popup .leaflet-popup-content-wrapper {
    border-radius: 10px;
    padding: 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    overflow: hidden;
}

.custom-popup .leaflet-popup-content {
    margin: 0;
    padding: 12px;
}

.custom-popup .leaflet-popup-tip {
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.popup-content {
    font-family: system-ui, -apple-system, sans-serif;
}

#map {
    min-height: 650px;
}

#detailsPanelContent {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

#detailsPanelContent::-webkit-scrollbar {
    width: 6px;
}

#detailsPanelContent::-webkit-scrollbar-track {
    background: #f1f5f9;
}

#detailsPanelContent::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 3px;
}

#detailsPanelContent::-webkit-scrollbar-thumb:hover {
    background-color: #94a3b8;
}

/* Style pour les cases à cocher */
input[type="checkbox"] {
    accent-color: #255156;
}

/* Animation de survol pour les marqueurs */
.custom-marker:hover .marker-pin,
.selected-marker:hover .marker-pin {
    transform: rotate(-45deg) scale(1.1);
    transition: transform 0.2s ease;
}
</style>
@endsection