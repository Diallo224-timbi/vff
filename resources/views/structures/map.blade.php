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

<!-- MODAL DÉTAILS COMPLET -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-2xl overflow-hidden rounded-2xl">
            <!-- Header avec gradient et logo -->
            <div class="bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white p-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <!-- Logo container -->
                        <div class="relative">
                            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg overflow-hidden">
                                <div id="modal-logo-placeholder" class="flex items-center justify-center">
                                    <i class="fas fa-building text-white text-4xl"></i>
                                </div>
                                <img id="modal-logo-img" src="" alt="Logo" class="w-full h-full object-cover hidden">
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white"></div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold" id="modal-organisme">-</h3>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium" id="modal-type-badge">-</span>
                                <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium" id="modal-hebergement-badge">-</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors" data-bs-dismiss="modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Body avec toutes les informations -->
            <div class="modal-body bg-gray-50 p-6 max-h-[70vh] overflow-y-auto">
                <!-- Grille d'informations principale -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Colonne gauche -->
                    <div class="space-y-4">
                        <!-- Informations générales -->
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                            <h4 class="text-[#255156] font-semibold mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                Informations générales
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                                    <span class="text-gray-500 text-sm">Organisme</span>
                                    <span class="text-gray-800 font-medium text-right" id="modal-organisme-text">-</span>
                                </div>
                                <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                                    <span class="text-gray-500 text-sm">Type</span>
                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium" id="modal-type_structure">-</span>
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
                                    <span class="text-gray-800 text-right" id="modal-zone">-</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-gray-500 text-sm">Site web</span>
                                    <span id="modal-site" class="text-right">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                            <h4 class="text-[#255156] font-semibold mb-3 flex items-center gap-2">
                                <i class="fas fa-align-left"></i>
                                Description
                            </h4>
                            <p class="text-gray-700 text-sm leading-relaxed" id="modal-description">-</p>
                        </div>

                        <!-- Détails spécifiques -->
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                            <h4 class="text-[#255156] font-semibold mb-3 flex items-center gap-2">
                                <i class="fas fa-list-ul"></i>
                                Détails spécifiques
                            </h4>
                            <p class="text-gray-700 text-sm" id="modal-details">-</p>
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="space-y-4">
                        <!-- Localisation -->
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                            <h4 class="text-[#255156] font-semibold mb-4 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt"></i>
                                Localisation
                            </h4>
                            
                            <!-- Siège social -->
                            <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-landmark text-blue-600"></i>
                                    <span class="font-semibold text-blue-700 text-sm">SIÈGE SOCIAL</span>
                                </div>
                                <div class="space-y-1 text-sm">
                                    <p><span class="text-gray-500">Ville :</span> <span class="font-medium" id="modal-siege_ville">-</span></p>
                                    <p><span class="text-gray-500">Adresse :</span> <span id="modal-siege_adresse">-</span></p>
                                </div>
                            </div>
                            
                            <!-- Antenne locale -->
                            <div class="p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-map-pin text-green-600"></i>
                                    <span class="font-semibold text-green-700 text-sm">ANTENNE LOCALE</span>
                                </div>
                                <div class="space-y-1 text-sm">
                                    <p><span class="text-gray-500">Ville :</span> <span class="font-medium" id="modal-ville">-</span> <span id="modal-code_postal" class="text-gray-500"></span></p>
                                    <p><span class="text-gray-500">Adresse :</span> <span id="modal-adresse">-</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                            <h4 class="text-[#255156] font-semibold mb-4 flex items-center gap-2">
                                <i class="fas fa-address-card"></i>
                                Contact
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
                                    <i class="fas fa-phone text-green-500 w-5"></i>
                                    <div>
                                        <div class="text-xs text-gray-500">Téléphone</div>
                                        <div id="modal-telephone" class="font-medium">-</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
                                    <i class="fas fa-envelope text-blue-500 w-5"></i>
                                    <div>
                                        <div class="text-xs text-gray-500">Email</div>
                                        <div id="modal-email" class="font-medium break-all">-</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
                                    <i class="fas fa-user text-purple-500 w-5"></i>
                                    <div>
                                        <div class="text-xs text-gray-500">Personne de contact</div>
                                        <div id="modal-contact" class="font-medium">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Horaires -->
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100" id="horaires-container" style="display: none;">
                            <h4 class="text-[#255156] font-semibold mb-3 flex items-center gap-2">
                                <i class="fas fa-clock"></i>
                                Horaires
                            </h4>
                            <p class="text-gray-700 text-sm" id="modal-horaires">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-white p-4 border-t border-gray-200">
                <div class="flex justify-end gap-3 w-full">
                        <i class="">dernier mise à jour: <span id="modal-created_at" class="">-</span></i>
                    <button type="button" 
                            class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition-colors flex items-center gap-2"
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
    // Variables globales
    let markers = [];
    let currentSelectedMarker = null;
    let map;
    let currentStructure = null;
    
    // Limites
    const coteAzurBounds = L.latLngBounds(
        L.latLng(43.2, 6.2),
        L.latLng(44.0, 7.8)
    );
    
    // Initialisation carte
    map = L.map('map', {
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

    // Fonction d'échappement HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Fonction pour échapper les caractères spéciaux pour JSON dans un attribut HTML
    function escapeJsonForAttribute(obj) {
        return JSON.stringify(obj).replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        }).replace(/'/g, '&#39;');
    }

    // Fonction pour formater les catégories en badges
    function formatCategoriesBadges(categories) {
        if (!categories) return '<span class="text-gray-400 text-xs">Non spécifié</span>';
        const cats = categories.split(',').map(c => c.trim()).filter(c => c);
        return cats.map(cat => 
            `<span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs">${escapeHtml(cat)}</span>`
        ).join('');
    }

    // Fonction pour formater les publics en badges
    function formatPublicBadges(publics) {
        if (!publics) return '<span class="text-gray-400 text-xs">Non spécifié</span>';
        const pubs = publics.split(',').map(p => p.trim()).filter(p => p);
        return pubs.map(pub => 
            `<span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs">${escapeHtml(pub)}</span>`
        ).join('');
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

    // Fonction pour afficher les détails complets dans le modal
    function showFullDetailsModal(structure) {
        currentStructure = structure;
        const logoUrl = structure.logo ? `{{ asset('storage') }}/${structure.logo}` : null;
        
        // Logo
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
        
        // En-tête
        document.getElementById('modal-organisme').textContent = structure.organisme || 'Structure sans nom';
        document.getElementById('modal-type-badge').textContent = structure.type_structure || 'Type non spécifié';
        document.getElementById('modal-hebergement-badge').textContent = structure.hebergement === 'oui' ? '🏠 Avec hébergement' : (structure.hebergement === 'non' ? '❌ Sans hébergement' : 'Hébergement non spécifié');
        
        // Informations générales
        document.getElementById('modal-organisme-text').textContent = structure.organisme || '-';
        document.getElementById('modal-type_structure').innerHTML = `<span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs" style="background-color: ${getColorByType(structure.type_structure)}20; color: ${getColorByType(structure.type_structure)}">${escapeHtml(structure.type_structure || 'Non spécifié')}</span>`;
        document.getElementById('modal-categories-list').innerHTML = formatCategoriesBadges(structure.categories);
        document.getElementById('modal-public-list').innerHTML = formatPublicBadges(structure.public_cible);
        document.getElementById('modal-zone').textContent = structure.zone || 'Non spécifié';
        
        // Site web
        const siteElement = document.getElementById('modal-site');
        if (structure.site && structure.site.trim() !== '') {
            siteElement.innerHTML = `<a href="${structure.site}" target="_blank" class="text-[#255156] hover:underline break-all">${escapeHtml(structure.site)} <i class="fas fa-external-link-alt text-xs ml-1"></i></a>`;
        } else {
            siteElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
        }
        
        // Description
        const descriptionElement = document.getElementById('modal-description');
        if (structure.description && structure.description.trim() !== '') {
            descriptionElement.textContent = structure.description;
            descriptionElement.classList.remove('text-gray-400', 'italic');
        } else {
            descriptionElement.textContent = 'Aucune description disponible';
            descriptionElement.classList.add('text-gray-400', 'italic');
        }
        
        // Détails spécifiques
        const detailsElement = document.getElementById('modal-details');
        if (structure.details && structure.details.trim() !== '') {
            detailsElement.textContent = structure.details;
            detailsElement.classList.remove('text-gray-400', 'italic');
        } else {
            detailsElement.textContent = 'Aucun détail spécifique';
            detailsElement.classList.add('text-gray-400', 'italic');
        }
        
        // Localisation - Siège social
        document.getElementById('modal-siege_ville').textContent = structure.siege_ville || 'Non spécifié';
        document.getElementById('modal-siege_adresse').textContent = structure.siege_adresse || 'Non spécifiée';
        
        // Localisation - Antenne locale
        document.getElementById('modal-ville').textContent = structure.ville || 'Non spécifié';
        document.getElementById('modal-code_postal').textContent = structure.code_postal ? `(${structure.code_postal})` : '';
        document.getElementById('modal-adresse').textContent = structure.adresse || 'Non spécifiée';
        
        // Contact
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
        
        // Horaires
        const horairesContainer = document.getElementById('horaires-container');
        const horairesElement = document.getElementById('modal-horaires');
        if (structure.horaires && structure.horaires.trim() !== '') {
            horairesElement.textContent = structure.horaires;
            horairesContainer.style.display = 'block';
        } else {
            horairesContainer.style.display = 'none';
        }
        
        // Date de mise à jour
        const dateElement = document.getElementById('modal-created_at');
        if (structure.updated_at) {
            const date = new Date(structure.updated_at);
            dateElement.textContent = date.toLocaleDateString('fr-FR', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } else if (structure.created_at) {
            const date = new Date(structure.created_at);
            dateElement.textContent = date.toLocaleDateString('fr-FR', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } else {
            dateElement.textContent = '-';
        }
        
        /* Bouton itinéraire
        const itineraireBtn = document.getElementById('modal-itineraire-btn');
        if (structure.latitude && structure.longitude) {
            itineraireBtn.onclick = () => {
                window.open(`https://www.google.com/maps/dir/?api=1&destination=${structure.latitude},${structure.longitude}`, '_blank');
            };
            itineraireBtn.disabled = false;
            itineraireBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            itineraireBtn.onclick = () => {
                alert('Coordonnées GPS non disponibles pour cette structure');
            };
            itineraireBtn.disabled = true;
            itineraireBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }*/
    }

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

    // Fonction pour créer le contenu de l'infobulle (popup) - Utilisation d'une approche sécurisée
    function createPopupContent(structure) {
        const logoUrl = structure.logo ? `{{ asset('storage') }}/${structure.logo}` : null;
        const color = getColorByType(structure.type_structure);
        
        // Échapper les données pour le JSON dans l'attribut data-structure
        const safeStructureJson = escapeJsonForAttribute(structure);
        
        return `
            <div class="popup-content" style="min-width: 260px; max-width: 300px;">
                <!-- En-tête avec logo -->
                <div class="flex items-center gap-3 border-b pb-2 mb-2">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center flex-shrink-0">
                        ${logoUrl ? `
                            <img src="${logoUrl}" alt="Logo" class="w-full h-full object-contain">
                        ` : `
                            <i class="fas fa-building text-gray-400 text-lg"></i>
                        `}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-sm text-[#255156] truncate" title="${escapeHtml(structure.organisme || 'Structure')}">
                            ${escapeHtml(structure.organisme || 'Structure')}
                        </h4>
                        <span class="inline-block px-2 py-0.5 text-[10px] rounded-full text-white mt-1" 
                              style="background-color: ${color};">
                            ${escapeHtml(structure.type_structure || 'Non spécifié')}
                        </span>
                    </div>
                </div>

                <!-- Informations rapides -->
                <div class="space-y-1.5 text-xs">
                    <p class="flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 w-4"></i>
                        <span class="text-gray-700 truncate">${escapeHtml(structure.ville || 'Ville non spécifiée')}</span>
                    </p>
                    
                    ${structure.telephone ? `
                        <p class="flex items-center">
                            <i class="fas fa-phone text-[#255156] w-4"></i>
                            <span class="text-gray-700">${escapeHtml(structure.telephone)}</span>
                        </p>
                    ` : ''}
                    
                    ${structure.categories ? `
                        <p class="flex items-start mt-1">
                            <i class="fas fa-tag text-gray-500 w-4 mt-0.5"></i>
                            <span class="text-gray-600 text-[10px] leading-relaxed">${escapeHtml(structure.categories.split(',').slice(0, 2).join(', '))}${structure.categories.split(',').length > 2 ? '...' : ''}</span>
                        </p>
                    ` : ''}
                </div>

                <!-- Boutons d'action -->
                <div class="mt-3 space-y-1">
                    <button class="view-details-btn w-full text-xs bg-[#255156] hover:bg-[#1d4144] text-white px-3 py-1.5 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-1"
                        data-structure='${safeStructureJson}'>
                        <i class="fas fa-info-circle mr-1"></i>
                        Voir tous les détails
                    </button>
                    ${structure.latitude && structure.longitude ? `
                        <a href="https://www.google.com/maps/search/?api=1&query=${structure.latitude},${structure.longitude}" target="_blank" 
                           class="flex items-center justify-center gap-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg font-medium transition-all duration-200">
                            <i class="fas fa-directions mr-1"></i>
                            Itinéraire
                        </a>
                    ` : ''}
                </div>
            </div>
        `;
    }

    // Afficher détails dans le panneau latéral
    function showStructureDetails(structure) {
        const logoUrl = structure.logo ? `{{ asset('storage') }}/${structure.logo}` : null;
        const color = getColorByType(structure.type_structure);
        
        // Échapper les données pour le JSON dans l'attribut data-structure
        const safeStructureJson = escapeJsonForAttribute(structure);
        
        const detailsHtml = `
            <div class="space-y-4">
                <!-- En-tête avec logo -->
                <div class="flex items-center gap-3 border-b pb-3">
                    <div class="w-14 h-14 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl overflow-hidden border border-gray-200 flex items-center justify-center shadow-sm flex-shrink-0">
                        ${logoUrl ? `
                            <img src="${logoUrl}" alt="Logo" class="w-full h-full object-contain">
                        ` : `
                            <i class="fas fa-building text-gray-400 text-2xl"></i>
                        `}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-[#255156] truncate" title="${escapeHtml(structure.organisme || 'Structure')}">
                            ${escapeHtml(structure.organisme || 'Structure')}
                        </h4>
                        <span class="inline-block px-2 py-0.5 text-[10px] rounded-full text-white mt-1" 
                              style="background-color: ${color}">
                            ${escapeHtml(structure.type_structure || 'Non spécifié')}
                        </span>
                    </div>
                </div>
                
                <!-- Localisation -->
                <div class="bg-blue-50 p-3 rounded-lg">
                    <h5 class="text-xs font-semibold text-blue-800 mb-2 flex items-center">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        Localisation
                    </h5>
                    <div class="text-xs space-y-1">
                        <p class="text-gray-700"><span class="font-medium">Ville:</span> ${escapeHtml(structure.ville || 'Non spécifié')} ${structure.code_postal ? '('+escapeHtml(structure.code_postal)+')' : ''}</p>
                        ${structure.adresse ? `<p class="text-gray-700"><span class="font-medium">Adresse:</span> ${escapeHtml(structure.adresse)}</p>` : ''}
                        ${structure.zone ? `<p class="text-gray-700"><span class="font-medium">Zone:</span> ${escapeHtml(structure.zone)}</p>` : ''}
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
                                    ${escapeHtml(structure.telephone)}
                                </a>
                            </div>
                        ` : ''}
                        ${structure.email ? `
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-[#255156] w-5"></i>
                                <a href="mailto:${structure.email}" class="text-gray-700 hover:text-[#255156] break-all text-xs">
                                    ${escapeHtml(structure.email)}
                                </a>
                            </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Catégories -->
                ${structure.categories ? `
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <h5 class="text-xs font-semibold text-purple-800 mb-2">Catégories</h5>
                        <div class="flex flex-wrap gap-1">
                            ${structure.categories.split(',').map(cat => 
                                `<span class="inline-block px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs">${escapeHtml(cat.trim())}</span>`
                            ).join('')}
                        </div>
                    </div>
                ` : ''}

                <!-- Bouton pour voir tous les détails -->
                <button class="view-details-btn w-full bg-[#255156] text-white py-2 rounded-lg text-xs hover:bg-[#1d4144] transition-colors flex items-center justify-center gap-2"
                    data-structure='${safeStructureJson}'>
                    <i class="fas fa-info-circle"></i>
                    Voir tous les détails
                </button>

                <!-- Bouton zoom -->
                ${structure.latitude && structure.longitude ? `
                    <button onclick="window.zoomToStructure(${structure.latitude}, ${structure.longitude})" 
                            class="w-full bg-gray-200 text-gray-800 py-2 rounded-lg text-xs hover:bg-gray-300 transition-colors flex items-center justify-center gap-2">
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
    };

    // Ajouter marker
    function addMarker(structure) {
        if (!structure.latitude || !structure.longitude) return;
        
        const color = getColorByType(structure.type_structure);
        const icon = createCustomIcon(color);
        
        const marker = L.marker([structure.latitude, structure.longitude], { icon }).addTo(map);
        
        const popupContent = createPopupContent(structure);
        marker.bindPopup(popupContent, {
            maxWidth: 320,
            minWidth: 260,
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

    // Filtrer les marqueurs
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

            const matchType = typeFilters.length === 0 || (structure.type_structure && typeFilters.includes(structure.type_structure));
            
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
        document.getElementById('totalStructures').textContent = markers.length;
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
        map.closePopup();
    });

    // Gestionnaire global pour les boutons de détails
    document.addEventListener('click', function(e) {
        const viewDetailsBtn = e.target.closest('.view-details-btn');
        if (viewDetailsBtn) {
            e.preventDefault();
            e.stopPropagation();
            const structureData = viewDetailsBtn.getAttribute('data-structure');
            try {
                // Remplacer les entités HTML avant le parsing
                const decodedData = structureData.replace(/&#39;/g, "'").replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
                const structure = JSON.parse(decodedData);
                showFullDetailsModal(structure);
                const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
                modal.show();
            } catch (error) {
                console.error('Erreur lors du parsing des données:', error);
                console.log('Données reçues:', structureData);
            }
        }
    });

    filterMarkers();
});
</script>

<style>
/* Styles identiques à avant... */
.custom-marker, .selected-marker {
    background: none !important;
    border: none !important;
}

.marker-pin {
    width: 30px;
    height: 30px;
    border-radius: 50% 50% 50% 0;
    background: #0f3168;
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

.custom-popup .leaflet-popup-content-wrapper {
    border-radius: 12px;
    padding: 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    overflow: hidden;
}

.custom-popup .leaflet-popup-content {
    margin: 0;
    padding: 12px;
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

input[type="checkbox"] {
    accent-color: #255156;
}

.modal-content {
    border: none;
    border-radius: 16px;
    overflow: hidden;
}

.modal-footer {
    border-top: 1px solid #e5e7eb;
}

.btn-close-white {
    filter: brightness(0) invert(1);
}
</style>
@endsection