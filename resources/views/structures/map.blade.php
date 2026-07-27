@extends('base')

@section('title', 'Cartographie des structures - Alpes-Maritimes (06)')

@section('content')
<!-- CONTENEUR PRINCIPAL : Responsive (Scroll fluide sur mobile / 100% Hauteur fixe sans scroll sur PC) -->
<div class="w-full flex flex-col p-2.5 sm:p-3 bg-gray-50/50 min-h-screen lg:min-h-0 lg:h-[calc(100vh-80px)] lg:max-h-[calc(100vh-80px)] overflow-y-auto lg:overflow-hidden">

    <!-- 1. HEADER (Réponsif flex-wrap) -->
    <div class="flex-shrink-0 flex flex-wrap justify-between items-center gap-2 mb-2">
        <h1 class="text-base sm:text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-map-marked-alt text-[#255156]"></i>
            Cartographie des structures <span class="text-xs sm:text-sm font-normal text-gray-500 hidden sm:inline">· Alpes-Maritimes (06)</span>
        </h1>
        <div class="flex items-center gap-2">
            <span class="text-xs bg-gray-100 px-2.5 py-1 rounded-full text-gray-600 font-medium">{{ auth()->user()->role }}</span>
            <div class="w-8 h-8 rounded-full bg-[#255156] flex items-center justify-center text-white font-bold text-sm shadow-xs">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
    <!-- 2. STATISTIQUES SIMPLES (1 col mobile / 3 cols tablette & PC) -->
    <div class="flex-shrink-0 grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-2.5 mb-2">
        <div class="bg-white px-3.5 py-2 rounded-xl border border-gray-200 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-xs font-medium text-gray-500">Total structures</div>
                <div class="text-lg font-bold text-[#255156]" id="totalStructures">{{ $structures->count() }}</div>
            </div>
            <div class="w-8 h-8 rounded-lg bg-[#255156]/10 flex items-center justify-center text-[#255156]">
                <i class="fas fa-building text-sm"></i>
            </div>
        </div>
        <div class="bg-white px-3.5 py-2 rounded-xl border border-gray-200 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-xs font-medium text-gray-500">Structures visibles</div>
                <div class="text-lg font-bold text-green-800" id="visibleCount">{{ $structures->count() }}</div>
            </div>
            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                <i class="fas fa-eye text-sm"></i>
            </div>
        </div>
        <div class="bg-white px-3.5 py-2 rounded-xl border border-gray-200 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-xs font-medium text-gray-500">Total organismes</div>
                <div class="text-lg font-bold text-blue-600">{{ $structures->pluck('id_organisme')->unique()->filter()->count() }}</div>
            </div>
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="fas fa-sitemap text-sm"></i>
            </div>
        </div>
    </div>

    <!-- 3. BANDEAU DE FILTRES HORIZONTAL (Adaptatif mobile / desktop) -->
    <div class="flex-shrink-0 bg-white border border-gray-200 rounded-xl shadow-2xs p-2 mb-2">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-2.5 justify-between">
            
            <!-- Recherche rapide -->
            <div class="w-full lg:w-64 flex-shrink-0">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="mapSearch" 
                           placeholder="Nom, ville, responsable..."
                           class="w-full pl-9 pr-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-[#255156] focus:outline-none">
                </div>
            </div>

            <!-- Catégories & Boutons TOUS / AUCUN -->
            <div class="flex-1 min-w-0 flex flex-col sm:flex-row items-start sm:items-center gap-2">
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <span class="text-sm font-bold text-gray-700 flex items-center gap-1">
                        <i class="fas fa-filter text-[#255156]"></i> Catégories:
                    </span>
                    <button type="button" onclick="checkAll('.category-filter')" class="text-xs bg-[#255156] hover:bg-[#1d4144] text-white px-2.5 py-1 rounded-md font-semibold cursor-pointer transition-colors shadow-2xs">
                        Tous
                    </button>
                    <button type="button" onclick="uncheckAll('.category-filter')" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 px-2.5 py-1 rounded-md font-semibold cursor-pointer transition-colors shadow-2xs">
                        Aucun
                    </button>
                </div>

                <!-- Puces défilables -->
                <div class="flex items-center gap-2 overflow-x-auto py-1 px-2 scrollbar-thin border border-gray-200 rounded-lg bg-gray-50/50 w-full flex-1">
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
                        <label class="inline-flex items-center flex-shrink-0 cursor-pointer text-sm bg-white border border-gray-200 px-2.5 py-1 rounded-md hover:bg-gray-50 transition-colors shadow-2xs font-medium">
                            <input type="checkbox" value="{{ $category }}" class="category-filter mr-2 rounded text-[#255156] focus:ring-0 w-4 h-4" checked>
                            <span class="truncate max-w-[170px] text-gray-800">{{ $category }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Bouton Réinitialiser -->
            <div class="flex-shrink-0">
                <button id="resetViewBtn" 
                        class="w-full lg:w-auto bg-[#255156] hover:bg-[#1d4144] text-white px-3.5 py-1.5 rounded-lg text-sm font-semibold flex items-center justify-center gap-1.5 transition-colors shadow-2xs">
                    <i class="fas fa-sync-alt text-xs"></i>
                    <span>Réinitialiser</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 4. SECTION PRINCIPALE : CARTE À GAUCHE (3/4) & DÉTAILS À DROITE (1/4) -->
    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-4 gap-2.5 overflow-hidden">

        <!-- CARTE ALPES-MARITIMES À GAUCHE (3/4 sur bureau, pleine hauteur sur mobile) -->
        <div class="lg:col-span-3 h-[420px] lg:h-full min-h-0">
            <div class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden h-full w-full relative">
                <div id="map" class="w-full h-full"></div>
            </div>
        </div>

        <!-- PANNEAU DÉTAILS À DROITE (1/4 sur bureau, empilé sous la carte sur mobile) -->
        <div class="lg:col-span-1 h-[420px] lg:h-full min-h-0">
            <div class="bg-white border border-gray-200 rounded-xl shadow-2xs h-full flex flex-col overflow-hidden">
                <!-- En-tête -->
                <div class="bg-gradient-to-r from-[#255156] to-[#3a757b] text-white px-4 py-2.5 flex-shrink-0">
                    <h3 class="font-bold text-sm flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Détails de la structure
                    </h3>
                </div>
                <!-- Contenu défilant text-sm -->
                <div class="flex-1 min-h-0 overflow-y-auto p-3.5" id="detailsPanelContent">
                    <div id="defaultMessage" class="text-center py-12">
                        <div class="w-12 h-12 bg-[#255156]/10 text-[#255156] rounded-full flex items-center justify-center mx-auto mb-2.5">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-800">Cliquez sur un point de la carte</p>
                        <p class="text-xs text-gray-500 mt-1">pour afficher les informations détaillées</p>
                    </div>
                    <div id="structureDetails" class="hidden"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL DÉTAILS COMPLET -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered custom-modal-width">
        <div class="modal-content border-0 shadow-2xl overflow-hidden rounded-2xl">
            <!-- En-tête du modal -->
            <div class="bg-gradient-to-r from-[#f0f6f5] to-[#e8f3f2] border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-md overflow-hidden border border-gray-200">
                                <div id="modal-logo-placeholder" class="flex items-center justify-center">
                                    <i class="fas fa-building text-[#255156] text-3xl"></i>
                                </div>
                                <img id="modal-logo-img" src="" alt="Logo" class="w-full h-full object-contain hidden">
                            </div> 
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-[#255156]" id="modal-organisme">-</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2.5 py-0.5 bg-[#255156]/10 text-[#255156] rounded-full text-xs font-medium" id="modal-type-badge">-</span>
                                <span class="px-2.5 py-0.5 bg-[#255156]/10 text-[#255156] rounded-full text-xs font-medium" id="modal-hebergement-badge">-</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="text-gray-500 hover:bg-gray-100 rounded-lg p-2 transition-colors" data-bs-dismiss="modal">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            <!-- Body du modal -->
            <div class="modal-body bg-gray-50 p-4 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-3">
                    <div class="space-y-3">
                        <div class="bg-white rounded-xl shadow-xs p-4 border border-gray-100">
                            <h4 class="text-[#255156] font-semibold mb-3 text-sm flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                Informations générales
                            </h4>
                            <div class="space-y-2.5">
                                <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                                    <span class="text-gray-500 text-sm">Structure</span>
                                    <span class="text-gray-800 font-medium text-sm text-right" id="modal-organisme-text">-</span>
                                </div>     
                                <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                                    <span class="text-gray-500 text-sm">Catégories</span>
                                    <div class="flex flex-wrap gap-1 justify-end" id="modal-categories-list"></div>
                                </div>
                                <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                                    <span class="text-gray-500 text-sm">Public cible</span>
                                    <div class="flex flex-wrap gap-1 justify-end" id="modal-public-list"></div>
                                </div>
                                <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                                    <span class="text-gray-500 text-sm">Zone d'intervention</span>
                                    <span class="text-gray-800 text-sm text-right" id="modal-zone">-</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-gray-500 text-sm">Site web</span>
                                    <span id="modal-site" class="text-sm text-right">-</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-xs p-4 border border-gray-100">
                            <h4 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                                <i class="fas fa-align-left"></i>
                                Description
                            </h4>
                            <p class="text-gray-700 text-sm leading-relaxed" id="modal-description">-</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-xs p-4 border border-gray-100">
                            <h4 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                                <i class="fas fa-list-ul"></i>
                                Détails spécifiques
                            </h4>
                            <p class="text-gray-700 text-sm" id="modal-details">-</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-white rounded-xl shadow-xs p-4 border border-gray-100">
                            <h4 class="text-[#255156] font-semibold mb-3 text-sm flex items-center gap-2">
                                <i class="fas fa-map-marker-alt"></i>
                                Localisation
                            </h4>
                            <div class="mb-2.5 p-2.5 bg-blue-50 rounded-lg">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <i class="fas fa-landmark text-[#206a72] text-xs"></i>
                                    <span class="font-semibold text-[#206a72] text-xs">SIÈGE SOCIAL</span>
                                </div>
                                <div class="space-y-1 text-sm">
                                    <p><span class="text-gray-500">Ville :</span> <span class="font-medium" id="modal-siege_ville">-</span></p>
                                    <p><span class="text-gray-500">Adresse :</span> <span id="modal-siege_adresse">-</span></p>
                                </div>
                            </div>   
                            <div class="p-2.5 bg-green-50 rounded-lg">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <i class="fas fa-map-pin text-green-600 text-xs"></i>
                                    <span class="font-semibold text-green-700 text-xs">STRUCTURE</span>
                                </div>
                                <div class="space-y-1 text-sm">
                                    <p><span class="text-gray-500">Ville :</span> <span class="font-medium" id="modal-ville">-</span> <span id="modal-code_postal" class="text-gray-500"></span></p>
                                    <p><span class="text-gray-500">Adresse :</span> <span id="modal-adresse">-</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-xs p-4 border border-gray-100">
                            <h4 class="text-[#255156] font-semibold mb-3 text-sm flex items-center gap-2">
                                <i class="fas fa-address-card"></i>
                                Contact
                            </h4>
                            <div class="space-y-2.5">
                                <div class="flex items-center gap-2.5 p-2 bg-gray-50 rounded-lg">
                                    <i class="fas fa-phone text-green-500 text-sm w-4"></i>
                                    <div>
                                        <div class="text-xs text-gray-500">Téléphone</div>
                                        <div id="modal-telephone" class="font-medium text-sm">-</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2.5 p-2 bg-gray-50 rounded-lg">
                                    <i class="fas fa-envelope text-blue-500 text-sm w-4"></i>
                                    <div>
                                        <div class="text-xs text-gray-500">Email</div>
                                        <div id="modal-email" class="font-medium text-sm break-all">-</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2.5 p-2 bg-gray-50 rounded-lg">
                                    <i class="fas fa-user text-[#255160] text-sm w-4"></i>
                                    <div>
                                        <div class="text-xs text-gray-500">Personne de contact</div>
                                        <div id="modal-contact" class="font-medium text-sm">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-xs p-4 border border-gray-100" id="horaires-container" style="display: none;">
                            <h4 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                                <i class="fas fa-clock"></i>
                                Horaires
                            </h4>
                            <p class="text-gray-700 text-sm" id="modal-horaires">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-white p-3 border-t border-gray-200">
                <div class="flex justify-end gap-3 w-full">
                    <i class="text-xs text-gray-500 my-auto">Dernière mise à jour: <span id="modal-created_at">-</span></i>
                    <button type="button" 
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-colors flex items-center gap-1.5"
                            data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        Fermer
                    </button>   
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
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', () => {
    let markers = [];
    let currentSelectedMarker = null;
    let map;
    let currentStructure = null;
    
    // Cadrage précis sur le département 06 (Alpes-Maritimes)
    const alpesMaritimesBounds = L.latLngBounds(
        L.latLng(43.35, 6.55),
        L.latLng(44.40, 7.80)
    );
    
    map = L.map('map', {
        center: [43.85, 7.10],
        zoom: 9.5,
        maxBounds: alpesMaritimesBounds,
        maxBoundsViscosity: 0.8
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 18,
    }).addTo(map);

    const resizeObserver = new ResizeObserver(() => {
        if (map) {
            map.invalidateSize();
        }
    });
    const mapElement = document.getElementById('map');
    if (mapElement) {
        resizeObserver.observe(mapElement);
    }

    const structures = @json($structures);

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function escapeJsonForAttribute(obj) {
        return JSON.stringify(obj).replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        }).replace(/'/g, '&#39;');
    }

    function formatCategoriesBadges(categories) {
        if (!categories) return '<span class="text-gray-400 text-xs">Non spécifié</span>';
        const cats = categories.split(',').map(c => c.trim()).filter(c => c);
        return cats.map(cat => 
            `<span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium">${escapeHtml(cat)}</span>`
        ).join('');
    }

    function formatPublicBadges(publics) {
        if (!publics) return '<span class="text-gray-400 text-xs">Non spécifié</span>';
        const pubs = publics.split(',').map(p => p.trim()).filter(p => p);
        return pubs.map(pub => 
            `<span class="inline-block px-2 py-0.5 bg-green-100 text-green-700 rounded-lg text-xs font-medium">${escapeHtml(pub)}</span>`
        ).join('');
    }

    function getColorByType(type) {
        return '#10b981';
    }

    function showFullDetailsModal(structure) {
        currentStructure = structure;
        
        const logoPath = structure.organisme?.logo_path;
        const logoUrl = logoPath ? `/storage/${logoPath}` : null;
        
        const modalLogoImg = document.getElementById('modal-logo-img');
        const modalLogoPlaceholder = document.getElementById('modal-logo-placeholder');
        
        if (logoUrl) {
            modalLogoImg.src = logoUrl;
            modalLogoImg.classList.remove('hidden');
            modalLogoPlaceholder.classList.add('hidden');
        } else {
            modalLogoImg.classList.add('hidden');
            modalLogoPlaceholder.classList.remove('hidden');
        }
        
        document.getElementById('modal-organisme').textContent = structure.organisme?.nom_organisme || 'Structure sans nom';
        document.getElementById('modal-type-badge').textContent = structure.organisme?.ville || ' ';
        document.getElementById('modal-organisme-text').textContent = (structure.organisme?.nom_organisme || '') + ' ' + (structure.ville || '');
        document.getElementById('modal-categories-list').innerHTML = formatCategoriesBadges(structure.categories);
        document.getElementById('modal-public-list').innerHTML = formatPublicBadges(structure.public_cible);
        document.getElementById('modal-zone').textContent = structure.zone || 'Non spécifié';
        
        const siteElement = document.getElementById('modal-site');
        if (structure.organisme?.site_web && structure.organisme.site_web.trim() !== '') {
            const url = structure.organisme.site_web.trim();
            siteElement.innerHTML = `
                <a href="${url}" target="_blank" class="text-[#255156] hover:underline break-all">
                ${escapeHtml(url)} <i class="fas fa-external-link-alt text-xs ml-1"></i>
                </a>`;
        } else {
            siteElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
        }

        const descriptionElement = document.getElementById('modal-description');
        if (structure.description && structure.description.trim() !== '') {
            descriptionElement.textContent = structure.description;
            descriptionElement.classList.remove('text-gray-400', 'italic');
        } else {
            descriptionElement.textContent = 'Aucune description disponible';
            descriptionElement.classList.add('text-gray-400', 'italic');
        }
        
        const detailsElement = document.getElementById('modal-details');
        if (structure.details && structure.details.trim() !== '') {
            detailsElement.textContent = structure.details;
            detailsElement.classList.remove('text-gray-400', 'italic');
        } else {
            detailsElement.textContent = 'Aucun détail spécifique';
            detailsElement.classList.add('text-gray-400', 'italic');
        }
        
        document.getElementById('modal-siege_ville').textContent = structure.organisme?.ville || 'Non spécifié';
        document.getElementById('modal-siege_adresse').textContent = structure.organisme?.adresse || 'Non spécifiée';
        
        document.getElementById('modal-ville').textContent = structure.ville || 'Non spécifié';
        document.getElementById('modal-code_postal').textContent = structure.code_postal ? `(${structure.code_postal})` : '';
        document.getElementById('modal-adresse').textContent = structure.adresse || 'Non spécifiée';
        
        const telephoneElement = document.getElementById('modal-telephone');
        if (structure.telephone && structure.telephone.trim() !== '') {
            telephoneElement.innerHTML = `<a href="tel:${structure.telephone.replace(/\s/g, '')}" class="text-[#255156] hover:underline">${escapeHtml(structure.telephone)}</a>`;
        } else {
            telephoneElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
        }
        
        const emailElement = document.getElementById('modal-email');
        if (structure.email && structure.email.trim() !== '') {
            emailElement.innerHTML = `<a href="mailto:${structure.email}" class="text-[#255156] hover:underline break-all">${escapeHtml(structure.email)}</a>`;
        } else {
            emailElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
        }
        
        document.getElementById('modal-contact').textContent = structure.contact || 'Non spécifié';
        
        const horairesContainer = document.getElementById('horaires-container');
        const horairesElement = document.getElementById('modal-horaires');
        if (structure.horaires && structure.horaires.trim() !== '') {
            horairesElement.textContent = structure.horaires;
            horairesContainer.style.display = 'block';
        } else {
            horairesContainer.style.display = 'none';
        }
        
        const dateElement = document.getElementById('modal-created_at');
        if (structure.updated_at) {
            const date = new Date(structure.updated_at);
            dateElement.textContent = date.toLocaleDateString('fr-FR', {
                day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
            });
        } else if (structure.created_at) {
            const date = new Date(structure.created_at);
            dateElement.textContent = date.toLocaleDateString('fr-FR', {
                day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
            });
        } else {
            dateElement.textContent = '-';
        }
    }

    window.checkAll = function(selector) {
        document.querySelectorAll(selector).forEach(cb => cb.checked = true);
        filterMarkers();
    };
    
    window.uncheckAll = function(selector) {
        document.querySelectorAll(selector).forEach(cb => cb.checked = false);
        filterMarkers();
    };

    function createCustomIcon(color) {
        return L.divIcon({
            className: 'custom-marker',
            html: `
                <div class="marker-pin" style="background-color: ${color};">
                    <i class="fas fa-map-pin" style="color: white; font-size: 13px;"></i>
                </div>
                <div class="marker-pulse" style="border-color: ${color};"></div>
            `,
            iconSize: [28, 40],
            iconAnchor: [14, 40],
            popupAnchor: [0, -28]
        });
    }

    function createSelectedIcon() {
        return L.divIcon({
            className: 'selected-marker',
            html: `
                <div class="marker-pin selected" style="background-color: #dc2626;">
                    <i class="fas fa-map-pin" style="color: white; font-size: 15px;"></i>
                </div>
                <div class="marker-pulse selected" style="border-color: #dc2626;"></div>
            `,
            iconSize: [34, 48],
            iconAnchor: [17, 48],
            popupAnchor: [0, -33]
        });
    }

    function createPopupContent(structure) {
        const logoPath = structure.organisme?.logo_path;
        const logoUrl = logoPath ? `/storage/${logoPath}` : null;
        const safeStructureJson = escapeJsonForAttribute(structure);
        
        return `
            <div class="popup-content" style="min-width: 260px; max-width: 300px;">
                <div class="flex items-center gap-2.5 border-b pb-2 mb-2">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center flex-shrink-0">
                        ${logoUrl ? `<img src="${logoUrl}" alt="Logo" class="w-full h-full object-contain">` : `<i class="fas fa-building text-gray-400 text-lg"></i>`}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-sm text-[#255156] truncate" title="${escapeHtml(structure.organisme?.nom_organisme || 'Structure')}">
                            ${escapeHtml(structure.organisme?.nom_organisme || 'Structure')}
                        </h4>
                    </div>
                </div>
                <div class="space-y-1.5 text-xs">
                    <p class="flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 w-4"></i>
                        <span class="text-gray-700 truncate font-medium">${escapeHtml((structure.ville || '') + ' (' + (structure.code_postal || '') + ')')}</span>
                    </p> 
                    ${structure.telephone ? `
                        <p class="flex items-center">
                            <i class="fas fa-phone text-[#255156] w-4"></i>
                            <span class="text-gray-700 font-medium">${escapeHtml(structure.telephone)}</span>
                        </p>
                    ` : ''} 
                    ${structure.categories ? `
                        <p class="flex items-start mt-1">
                            <i class="fas fa-tag text-gray-500 w-4 mt-0.5"></i>
                            <span class="text-gray-600 text-xs leading-relaxed">${escapeHtml(structure.categories.split(',').slice(0, 2).join(', '))}${structure.categories.split(',').length > 2 ? '...' : ''}</span>
                        </p>
                    ` : ''}
                </div>
                <div class="mt-3 space-y-1.5">
                    <button class="view-details-btn w-full text-xs bg-[#255156] hover:bg-[#1d4144] text-white px-3 py-1.5 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center gap-1"
                        data-structure='${safeStructureJson}'>
                        <i class="fas fa-info-circle mr-1"></i>
                        Voir tous les détails
                    </button>
                    ${structure.latitude && structure.longitude ? `
                        <a href="https://www.google.com/maps/search/?api=1&query=${structure.latitude},${structure.longitude}" target="_blank" 
                           class="flex items-center justify-center gap-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg font-semibold transition-all duration-200">
                            <i class="fas fa-directions mr-1"></i>
                            Itinéraire
                        </a>
                    ` : ''}
                </div>
            </div>
        `;
    }

    // PANNEAU DE DÉTAILS À DROITE (ÉCRITURE TAILLE TABLEAU text-sm / 14px)
    function showStructureDetails(structure) {
        const logoPath = structure.organisme?.logo_path;
        const logoUrl = logoPath ? `/storage/${logoPath}` : null;
        const safeStructureJson = escapeJsonForAttribute(structure);
        
        const detailsHtml = `
            <div class="space-y-3.5 text-sm">
                <!-- Organisme & Logo -->
                <div class="flex items-center gap-3 border-b border-gray-200 pb-3">
                    <div class="w-11 h-11 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl overflow-hidden border border-gray-200 flex items-center justify-center shadow-2xs flex-shrink-0">
                        ${logoUrl ? `<img src="${logoUrl}" alt="Logo" class="w-full h-full object-contain">` : `<i class="fas fa-building text-gray-400 text-xl"></i>`}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-[#255156] text-sm leading-snug truncate" title="${escapeHtml(structure.organisme?.nom_organisme || 'Structure')}">
                            ${escapeHtml(structure.organisme?.nom_organisme || 'Structure')}
                        </h4>
                    </div>
                </div>   

                <!-- Localisation -->
                <div class="bg-blue-50/80 border border-blue-100 p-3 rounded-xl">
                    <h5 class="text-xs uppercase tracking-wider font-bold text-blue-900 mb-1.5 flex items-center gap-1.5">
                        <i class="fas fa-map-marker-alt text-blue-700"></i>
                        Localisation
                    </h5>
                    <div class="text-sm space-y-1 text-gray-800">
                        <p><span class="text-gray-500 font-normal">Ville:</span> <span class="font-semibold">${escapeHtml(structure.ville || 'Non spécifié')}</span> ${structure.code_postal ? '<span class="text-gray-600 font-normal">('+escapeHtml(structure.code_postal)+')</span>' : ''}</p>
                        ${structure.adresse ? `<p><span class="text-gray-500 font-normal">Adresse:</span> <span class="font-medium">${escapeHtml(structure.adresse)}</span></p>` : ''}
                        ${structure.zone ? `<p><span class="text-gray-500 font-normal">Zone:</span> <span class="font-medium">${escapeHtml(structure.zone)}</span></p>` : ''}
                    </div>
                </div>

                <!-- Contact -->
                <div class="bg-gray-50 border border-gray-200 p-3 rounded-xl">
                    <h5 class="text-xs uppercase tracking-wider font-bold text-gray-700 mb-2 flex items-center gap-1.5">
                        <i class="fas fa-address-card text-gray-500"></i>
                        Contact
                    </h5>
                    <div class="text-sm space-y-2">
                        ${structure.telephone ? `
                            <div class="flex items-center gap-2">
                                <i class="fas fa-phone text-[#255156] w-4"></i>
                                <a href="tel:${structure.telephone.replace(/\s/g,'')}" class="text-gray-800 font-semibold hover:text-[#255156] transition-colors">
                                    ${escapeHtml(structure.telephone)}
                                </a>
                            </div>
                        ` : ''}
                        ${structure.email ? `
                            <div class="flex items-center gap-2">
                                <i class="fas fa-envelope text-[#255156] w-4"></i>
                                <a href="mailto:${structure.email}" class="text-gray-800 font-medium hover:text-[#255156] break-all transition-colors">
                                    ${escapeHtml(structure.email)}
                                </a>
                            </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Catégories -->
                ${structure.categories ? `
                    <div class="bg-purple-50/80 border border-purple-100 p-3 rounded-xl">
                        <h5 class="text-xs uppercase tracking-wider font-bold text-purple-900 mb-2">Catégories</h5>
                        <div class="flex flex-wrap gap-1.5">
                            ${structure.categories.split(',').map(cat => 
                                `<span class="inline-block px-2.5 py-1 bg-purple-100 text-purple-800 rounded-lg text-xs font-semibold">${escapeHtml(cat.trim())}</span>`
                            ).join('')}
                        </div>
                    </div>
                ` : ''}

                <!-- Boutons d'action -->
                <button class="view-details-btn w-full bg-[#255156] text-white py-2 px-3 rounded-xl text-sm hover:bg-[#1d4144] transition-colors flex items-center justify-center gap-2 font-semibold shadow-2xs"
                    data-structure='${safeStructureJson}'>
                    <i class="fas fa-info-circle"></i>
                    Voir tous les détails
                </button>

                ${structure.latitude && structure.longitude ? `
                    <button onclick="window.zoomToStructure(${structure.latitude}, ${structure.longitude})" 
                            class="w-full bg-gray-100 text-gray-800 py-2 px-3 rounded-xl text-sm hover:bg-gray-200 transition-colors flex items-center justify-center gap-2 font-semibold">
                        <i class="fas fa-search-plus"></i>
                        Centrer sur la carte
                    </button>
                ` : ''}
            </div>
        `;

        document.getElementById('structureDetails').innerHTML = detailsHtml;
        document.getElementById('structureDetails').classList.remove('hidden');
        document.getElementById('defaultMessage').classList.add('hidden');
        document.getElementById('detailsPanelContent').scrollTop = 0;
    }

    window.zoomToStructure = function(lat, lon) {
        map.setView([lat, lon], 15);
    };

    function addMarker(structure) {
        if (!structure.latitude || !structure.longitude) return;
        
        const color = getColorByType(structure.type_structure);
        const icon = createCustomIcon(color);
        
        const marker = L.marker([structure.latitude, structure.longitude], { icon }).addTo(map);
        
        const popupContent = createPopupContent(structure);
        marker.bindPopup(popupContent, {
            maxWidth: 300,
            minWidth: 250,
            className: 'custom-popup'
        });
        
        marker.on('click', function() {
            marker.openPopup();
            
            if (currentSelectedMarker) {
                currentSelectedMarker.marker.setIcon(createCustomIcon(currentSelectedMarker.originalColor));
            }
            marker.setIcon(createSelectedIcon());
            currentSelectedMarker = { marker, structure, originalColor: color };
            
            showStructureDetails(structure);
            map.setView([structure.latitude, structure.longitude], Math.max(map.getZoom(), 13));
        });

        markers.push({ marker, structure, originalColor: color });
    }

    structures.forEach(addMarker);

    function filterMarkers() {
        const search = document.getElementById('mapSearch').value.toLowerCase();
        const categoryFilters = Array.from(document.querySelectorAll('.category-filter:checked')).map(cb => cb.value);

        let visibleCount = 0;

        markers.forEach(({ marker, structure }) => {
            const matchSearch = !search || 
                (structure.organisme?.nom_organisme && structure.organisme?.nom_organisme.toLowerCase().includes(search)) ||
                (structure.ville && structure.ville.toLowerCase().includes(search)) ||
                (structure.responsable && structure.responsable.toLowerCase().includes(search));

            const matchCategory = categoryFilters.length === 0 || 
                (structure.categories && structure.categories.split(',').map(c => c.trim()).some(c => categoryFilters.includes(c)));

            const visible = matchSearch && matchCategory;

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
        document.getElementById('totalStructures').textContent = markers.length;
    }

    document.getElementById('mapSearch').addEventListener('input', filterMarkers);
    document.querySelectorAll('.category-filter').forEach(cb => {
        cb.addEventListener('change', filterMarkers);
    });

    document.getElementById('resetViewBtn').addEventListener('click', () => {
        map.setView([43.85, 7.10], 9.5);
        document.getElementById('mapSearch').value = '';
        document.querySelectorAll('.category-filter').forEach(cb => cb.checked = true);
        filterMarkers();
        
        if (currentSelectedMarker) {
            currentSelectedMarker.marker.setIcon(createCustomIcon(currentSelectedMarker.originalColor));
            currentSelectedMarker = null;
        }
        document.getElementById('structureDetails').classList.add('hidden');
        document.getElementById('defaultMessage').classList.remove('hidden');
        map.closePopup();
    });

    document.addEventListener('click', function(e) {
        const viewDetailsBtn = e.target.closest('.view-details-btn');
        if (viewDetailsBtn) {
            e.preventDefault();
            e.stopPropagation();
            const structureData = viewDetailsBtn.getAttribute('data-structure');
            try {
                const decodedData = structureData.replace(/&#39;/g, "'").replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
                const structure = JSON.parse(decodedData);
                showFullDetailsModal(structure);
                const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
                modal.show();
            } catch (error) {
                console.error('Erreur lors du parsing des données:', error);
            }
        }
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
    width: 26px;
    height: 26px;
    border-radius: 50% 50% 50% 0;
    background: #0f3168;
    position: absolute;
    transform: rotate(-45deg);
    left: 50%;
    top: 50%;
    margin: -13px 0 0 -13px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.marker-pin i {
    transform: rotate(45deg);
    margin-top: 1px;
}

.marker-pin.selected {
    width: 32px;
    height: 32px;
    margin: -16px 0 0 -16px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.4);
}

.marker-pulse {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 36px;
    height: 36px;
    margin-left: -18px;
    margin-top: -18px;
    border-radius: 50%;
    border: 2px solid rgba(59, 130, 246, 0.4);
    animation: pulse 1.5s infinite;
    pointer-events: none;
}

.marker-pulse.selected {
    width: 44px;
    height: 44px;
    margin-left: -22px;
    margin-top: -22px;
    border: 3px solid rgba(9, 82, 76, 0.75);
}

@keyframes pulse {
    0% { transform: scale(0.8); opacity: 0.8; }
    70% { transform: scale(1.5); opacity: 0; }
    100% { transform: scale(0.8); opacity: 0; }
}

.custom-popup .leaflet-popup-content-wrapper {
    border-radius: 12px;
    padding: 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    overflow: hidden;
}

.custom-popup .leaflet-popup-content {
    margin: 0;
    padding: 10px;
}

#map {
    width: 100%;
    height: 100%;
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

.scrollbar-thin {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f8fafc;
}

.scrollbar-thin::-webkit-scrollbar {
    height: 4px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f8fafc;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 2px;
}

input[type="checkbox"] {
    accent-color: #255156;
}

.custom-modal-width {
    max-width: 900px;
}
</style>
@endsection