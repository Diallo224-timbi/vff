@extends('base')

@section('title', 'Liste groupée des structures')

@section('content')
<div class="container mx-auto px-2 py-4">
    <!-- Message succès avec padding réduit -->
    @if(session('success'))
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg mb-3 shadow-lg border-l-4 border-white"
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)">
            <div class="flex items-center text-sm">
                <i class="fas fa-check-circle text-lg mr-2"></i>
                <div>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- En-tête et navigation avec espacement réduit -->
    <div class="flex flex-wrap items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('annuaire.index') }}" 
               class="btn-secondary-custom flex items-center gap-1 px-4 py-2 rounded-lg font-semibold text-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                <i class="fas fa-arrow-left text-xs"></i>
                Retour
            </a>
            <h1 class="text-xl font-bold text-[#255156]">
                <i class="fas fa-building mr-1"></i>
                Structures par siège
            </h1>
        </div>
        <div class="text-xs text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg">
            <i class="fas fa-info-circle mr-1 text-[#255156]"></i>
            <span>{{ $totalStructures }} structures - {{ count($groupes) }} sièges</span>
        </div>
    </div>

    <!-- SECTION RECHERCHE ET FILTRES COMPACTE -->
    <div class="bg-white p-2 rounded-lg shadow-sm mb-3 border border-gray-100">
        <div class="flex flex-wrap items-center gap-2">
            <!-- Recherche globale - plus compacte -->
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="searchGroupes" 
                           class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:border-[#8bbdc3] focus:ring-1 focus:ring-[#8bbdc3]/30 outline-none transition-all" 
                           placeholder="Rechercher siège ou structure...">
                </div>
            </div>
            
            <!-- Filtre par organisme - dropdown compact -->
            <div class="w-48">
                <div class="relative">
                    <i class="fas fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    <select id="filterOrganisme" 
                            class="w-full pl-8 pr-8 py-1.5 text-xs border border-gray-200 rounded-lg focus:border-[#8bbdc3] focus:ring-1 focus:ring-[#8bbdc3]/30 outline-none appearance-none bg-white">
                        <option value="">Tous les organismes</option>
                        @php
                            $organismes = collect();
                            foreach($groupes as $structures) {
                                foreach($structures as $structure) {
                                    if($structure->organisme) {
                                        $organismes->push($structure->organisme);
                                    }
                                }
                            }
                            $organismes = $organismes->unique()->sort();
                        @endphp
                        @foreach($organismes as $organisme)
                            <option value="{{ $organisme }}">{{ Str::limit($organisme, 30) }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[10px]"></i>
                </div>
            </div>
            
            
            <!-- Bouton réinitialiser - compact -->
            <button id="resetFilters" 
                    class="flex items-center gap-1 px-3 py-1.5 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors whitespace-nowrap">
                <i class="fas fa-undo-alt text-[10px]"></i>
                Réinitialiser
            </button>
        </div>
        
        <!-- Indicateurs de filtres actifs (caché par défaut) -->
        <div id="activeFilters" class="hidden mt-2 flex flex-wrap gap-1">
            <span class="text-[10px] text-gray-500 mr-1">Filtres actifs:</span>
            <div id="filterBadges" class="flex flex-wrap gap-1"></div>
        </div>
    </div>

    <!-- Liste groupée par siège avec espacement réduit -->
    <div class="space-y-3" id="groupesContainer">
        @forelse($groupes as $siege => $structures)
            <div class="groupe-card bg-white rounded-lg shadow border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300"
                 data-siege="{{ $siege }}">
                <!-- En-tête du siège plus compact -->
                <div class="bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white p-3 cursor-pointer groupe-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="bg-white/20 p-1.5 rounded-lg">
                                <i class="fas fa-map-marker-alt text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold">{{ $siege ?: 'Siège non spécifié' }}</h2>
                                <p class="text-xs text-white/80">
                                    <i class="fas fa-building mr-1"></i>
                                    <span class="structures-count">{{ $structures->count() }}</span> structure(s)
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs font-semibold max-w-[350px] truncate"
                            title="{{ $structures->first()->siege_adresse .' - '. $structures->first()->siege_ville ?? 'Adresse non spécifiée' }}">
                                        {{ $structures->first()->siege_adresse .' - '. $structures->first()->siege_ville ?? 'Adresse non spécifiée' }}
                                    </span>
                            <button class="toggle-group text-white hover:scale-110 transition-transform">
                                <i class="fas fa-chevron-up text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Liste des structures -->
                <div class="structures-list p-3 bg-gray-50">     
                    <!-- Barre de recherche spécifique (si > 5) -->
                    @if($structures->count() > 5)
                    <div class="mb-3 px-1">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" 
                                   class="search-antenne form-control-professional pl-8 py-1.5 text-xs w-full bg-white border border-gray-200 focus:border-[#8bbdc3] rounded-lg" 
                                   placeholder="Rechercher dans {{ $structures->count() }} antennes..."
                                   data-siege-id="siege-{{ $loop->index }}">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded-full">
                                {{ $structures->count() }}
                            </span>
                        </div>
                    </div>
                    @endif

                    <!-- Grille des cartes avec gap réduit -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3" 
                         id="siege-{{ $loop->index }}-grid">
                        @foreach($structures as $structure)
                            <div class="structure-card bg-white rounded-lg border border-gray-200 p-3 hover:border-[#8bbdc3] hover:shadow-sm transition-all duration-200 antenne-card"
                                 data-organisme="{{ strtolower($structure->organisme ?? '') }}"
                                 data-categorie="{{ strtolower($structure->categories ?? '') }}"
                                 data-ville="{{ strtolower($structure->ville ?? '') }}"
                                 data-search="{{ strtolower($structure->organisme . ' ' . ($structure->ville ?: '') . ' ' . ($structure->adresse ?: '') . ' ' . ($structure->categories ?: '')) }}"
                                 data-siege-id="siege-{{ $loop->parent->index }}">
                                
                                <!-- En-tête de la carte plus compact -->
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-start gap-1.5">
                                        <div class="bg-[#255156]/10 p-1.5 rounded-lg">
                                            <i class="fas fa-building text-[#255156] text-xs"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-800 text-sm line-clamp-2 organisme-nom" title="{{ $structure->organisme }}">
                                                {{ $structure->organisme }}
                                            </h3>
                                            <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-[#8bbdc3]/20 text-[#255156] text-xs rounded-full categorie-badge">
                                                {{ Str::limit($structure->categories ?? 'Non catégorisé', 20) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informations essentielles compactes -->
                                <div class="space-y-1.5 text-xs">
                                    <!-- Adresse -->
                                    <div class="flex items-start gap-1.5 text-gray-600">
                                        <i class="fas fa-map-marker-alt mt-0.5 text-gray-400 w-3 text-xs"></i>
                                        <span class="flex-1 line-clamp-1 text-xs" title="{{ $structure->adresse }}, {{ $structure->code_postal }} {{ $structure->ville }}">
                                            {{ Str::limit($structure->adresse ?? 'Adresse non spécifiée', 25) }}
                                            @if($structure->code_postal || $structure->ville)
                                                , {{ $structure->code_postal }} {{ Str::limit($structure->ville, 15) }}
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Téléphone -->
                                    <div class="flex items-center gap-1.5 text-gray-600">
                                        <i class="fas fa-phone text-gray-400 w-3 text-xs"></i>
                                        @if($structure->telephone)
                                            <a href="tel:{{ $structure->telephone }}" 
                                               class="text-[#255156] hover:text-[#8bbdc3] font-medium hover:underline text-xs">
                                                {{ Str::limit($structure->telephone, 15) }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Non disponible</span>
                                        @endif
                                    </div>

                                    <!-- Email -->
                                    <div class="flex items-center gap-1.5 text-gray-600">
                                        <i class="fas fa-envelope text-gray-400 w-3 text-xs"></i>
                                        @if($structure->email)
                                            <a href="mailto:{{ $structure->email }}" 
                                               class="text-[#255156] hover:text-[#8bbdc3] font-medium hover:underline truncate text-xs">
                                                {{ Str::limit($structure->email, 20) }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Non disponible</span>
                                        @endif
                                    </div>

                                    <!-- Badges compacts -->
                                    <div class="flex flex-wrap gap-1 mt-1 pt-1 border-t border-gray-100">
                                        @if($structure->type_structure)
                                            <span class="text-xs px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                                {{ Str::limit($structure->type_structure, 12) }}
                                            </span>
                                        @endif
                                        @if($structure->zone)
                                            <span class="text-xs px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                                {{ Str::limit($structure->zone, 12) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Bouton détails compact -->
                                <div class="mt-2 pt-1.5 border-t border-gray-100 flex justify-end">
                                    <button class="view-details-btn text-xs bg-[#255156]/10 hover:bg-[#255156] text-[#255156] hover:text-white px-2 py-1 rounded-lg font-medium transition-all duration-200 flex items-center gap-1"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailsModal"
                                            data-structure='@json($structure)'>
                                        <i class="fas fa-eye text-xs"></i>
                                        Détails
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Message aucun résultat compact -->
                    <div id="no-antenne-result-{{ $loop->index }}" 
                         class="hidden flex flex-col items-center justify-center py-4 text-gray-500">
                        <i class="fas fa-map-marker-alt text-2xl mb-1 opacity-30"></i>
                        <p class="text-xs font-medium">Aucune antenne trouvée</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="flex flex-col items-center justify-center text-gray-500">
                    <i class="fas fa-building text-4xl mb-3 opacity-20"></i>
                    <p class="text-base font-medium mb-1">Aucune structure trouvée</p>
                    <p class="text-xs text-gray-400">Il n'y a pas encore de structures dans l'annuaire</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal détails -->
@include('annuaire.details')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. GESTION DES GROUPES - TOGGLE
    document.querySelectorAll('.groupe-header').forEach(header => {
        header.addEventListener('click', function(e) {
            if (e.target.closest('.toggle-group')) return;
            
            const groupe = this.closest('.groupe-card');
            const structuresList = groupe.querySelector('.structures-list');
            const toggleIcon = groupe.querySelector('.toggle-group i');
            
            structuresList.classList.toggle('hidden');
            
            if (structuresList.classList.contains('hidden')) {
                toggleIcon.classList.remove('fa-chevron-up');
                toggleIcon.classList.add('fa-chevron-down');
            } else {
                toggleIcon.classList.remove('fa-chevron-down');
                toggleIcon.classList.add('fa-chevron-up');
            }
        });
    });

    // Gestion du bouton toggle spécifique
    document.querySelectorAll('.toggle-group').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const groupe = this.closest('.groupe-card');
            const structuresList = groupe.querySelector('.structures-list');
            const toggleIcon = this.querySelector('i');
            
            structuresList.classList.toggle('hidden');
            
            if (structuresList.classList.contains('hidden')) {
                toggleIcon.classList.remove('fa-chevron-up');
                toggleIcon.classList.add('fa-chevron-down');
            } else {
                toggleIcon.classList.remove('fa-chevron-down');
                toggleIcon.classList.add('fa-chevron-up');
            }
        });
    });

    // 2. FONCTION DE FILTRAGE GLOBAL
    function filterStructures() {
        const searchQuery = document.getElementById('searchGroupes')?.value.toLowerCase().trim() || '';
        const filterOrganisme = document.getElementById('filterOrganisme')?.value.toLowerCase().trim() || '';
        const filterCategorie = document.getElementById('filterCategorie')?.value.toLowerCase().trim() || '';
        
        const groupeCards = document.querySelectorAll('.groupe-card');
        let totalVisibleStructures = 0;
        let activeFilters = [];
        
        if (filterOrganisme) activeFilters.push('Organisme');
        if (filterCategorie) activeFilters.push('Catégorie');
        if (searchQuery) activeFilters.push('Recherche');
        
        // Afficher les badges de filtres actifs
        const activeFiltersDiv = document.getElementById('activeFilters');
        const filterBadges = document.getElementById('filterBadges');
        
        if (activeFilters.length > 0) {
            activeFiltersDiv.classList.remove('hidden');
            filterBadges.innerHTML = '';
            
            if (filterOrganisme) {
                filterBadges.innerHTML += `<span class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#255156]/10 text-[#255156] text-[10px] rounded-full">
                    <i class="fas fa-filter text-[8px]"></i> Organisme: ${document.getElementById('filterOrganisme').value}
                </span>`;
            }
            if (filterCategorie) {
                filterBadges.innerHTML += `<span class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#255156]/10 text-[#255156] text-[10px] rounded-full">
                    <i class="fas fa-tag text-[8px]"></i> Catégorie: ${document.getElementById('filterCategorie').value}
                </span>`;
            }
        } else {
            activeFiltersDiv.classList.add('hidden');
        }
        
        groupeCards.forEach(card => {
            const siegeNom = card.querySelector('.groupe-header h2').textContent.toLowerCase();
            const structuresCards = card.querySelectorAll('.structure-card');
            let hasVisibleStructure = false;
            let visibleInSiege = 0;
            
            structuresCards.forEach(structure => {
                const organisme = structure.dataset.organisme || '';
                const categorie = structure.dataset.categorie || '';
                const structureText = structure.textContent.toLowerCase();
                
                // Vérifier tous les filtres
                const matchesSearch = searchQuery === '' || structureText.includes(searchQuery) || siegeNom.includes(searchQuery);
                const matchesOrganisme = filterOrganisme === '' || organisme.includes(filterOrganisme);
                const matchesCategorie = filterCategorie === '' || categorie.includes(filterCategorie);
                
                const matches = matchesSearch && matchesOrganisme && matchesCategorie;
                
                structure.style.display = matches ? '' : 'none';
                if (matches) {
                    hasVisibleStructure = true;
                    visibleInSiege++;
                }
            });
            
            // Mettre à jour le compteur de structures dans l'en-tête
            const countSpan = card.querySelector('.structures-count');
            if (countSpan) {
                countSpan.textContent = visibleInSiege;
            }
            
            // Masquer/afficher le groupe
            if (searchQuery === '' && filterOrganisme === '' && filterCategorie === '') {
                card.style.display = '';
                totalVisibleStructures += structuresCards.length;
            } else {
                card.style.display = hasVisibleStructure ? '' : 'none';
                totalVisibleStructures += visibleInSiege;
            }
        });
        
        updateResultCount(totalVisibleStructures);
    }

    // 3. RECHERCHE SPÉCIFIQUE PAR ANTENNE
    function filterAntennes(siegeId, query) {
        const grid = document.getElementById(siegeId + '-grid');
        if (!grid) return;
        
        const antennes = grid.querySelectorAll('.antenne-card');
        const noResultMsg = document.getElementById('no-antenne-result-' + siegeId.split('-')[1]);
        let visibleCount = 0;
        
        antennes.forEach(antenne => {
            const searchData = antenne.dataset.search || '';
            const matches = query === '' || searchData.includes(query.toLowerCase());
            antenne.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });
        
        if (noResultMsg) {
            if (visibleCount === 0 && query !== '') {
                noResultMsg.classList.remove('hidden');
            } else {
                noResultMsg.classList.add('hidden');
            }
        }
        
        return visibleCount;
    }

    // 4. INITIALISATION DES ÉCOUTEURS D'ÉVÉNEMENTS
    // Recherche globale
    const searchInput = document.getElementById('searchGroupes');
    if (searchInput) {
        searchInput.addEventListener('input', filterStructures);
    }
    
    // Filtres déroulants
    const filterOrganisme = document.getElementById('filterOrganisme');
    const filterCategorie = document.getElementById('filterCategorie');
    
    if (filterOrganisme) filterOrganisme.addEventListener('change', filterStructures);
    if (filterCategorie) filterCategorie.addEventListener('change', filterStructures);
    
    // Bouton réinitialiser
    const resetBtn = document.getElementById('resetFilters');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (filterOrganisme) filterOrganisme.value = '';
            if (filterCategorie) filterCategorie.value = '';
            filterStructures();
            
            // Réinitialiser les recherches d'antennes
            document.querySelectorAll('.search-antenne').forEach(input => {
                input.value = '';
                const siegeId = input.dataset.siegeId;
                filterAntennes(siegeId, '');
            });
        });
    }

    // Recherches d'antennes
    document.querySelectorAll('.search-antenne').forEach(input => {
        input.addEventListener('input', function(e) {
            const query = this.value.toLowerCase().trim();
            const siegeId = this.dataset.siegeId;
            filterAntennes(siegeId, query);
        });
    });

    // 5. FONCTION DE MISE À JOUR DU COMPTEUR GLOBAL
    function updateResultCount(visibleCount) {
        const totalStructures = document.querySelectorAll('.structure-card').length;
        const visibleGroupes = Array.from(document.querySelectorAll('.groupe-card'))
            .filter(el => el.style.display !== 'none').length;
        
        const countDisplay = document.querySelector('.text-sm.text-gray-600.bg-gray-50 span');
        if (countDisplay) {
            if (visibleCount < totalStructures) {
                countDisplay.textContent = `${visibleCount} structures - ${visibleGroupes} sièges (filtrés)`;
            } else {
                countDisplay.textContent = `{{ $totalStructures }} structures - ${visibleGroupes} sièges`;
            }
        }
    }

    // 6. MODAL DÉTAILS
    const viewDetailsButtons = document.querySelectorAll('.view-details-btn');
    viewDetailsButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const structure = JSON.parse(this.dataset.structure);
            
            document.getElementById('modal-organisme').textContent = structure.organisme || '-';
            document.getElementById('modal-organisme-text').textContent = structure.organisme || '-';
            document.getElementById('modal-categories').textContent = structure.categories || 'Non spécifié';
            document.getElementById('modal-type_structure').textContent = structure.type_structure || 'Non spécifié';
            document.getElementById('modal-public_cible').textContent = structure.public_cible || 'Non spécifié';
            document.getElementById('modal-zone').textContent = structure.zone || 'Non spécifié';
            
            const siteElement = document.getElementById('modal-site');
            if (structure.site) {
                siteElement.innerHTML = `<a href="${structure.site}" target="_blank" class="text-[#255156] hover:text-[#8bbdc3]">${structure.site}</a>`;
            } else {
                siteElement.innerHTML = '<span class="text-gray-400">Non disponible</span>';
            }
            
            document.getElementById('modal-siege_ville').textContent = structure.siege_ville || 'Non spécifié';
            document.getElementById('modal-siege_adresse').textContent = structure.siege_adresse || 'Non spécifié';
            document.getElementById('modal-ville').textContent = structure.ville || 'Non spécifié';
            document.getElementById('modal-code_postal').textContent = structure.code_postal || 'Non spécifié';
            document.getElementById('modal-adresse').textContent = structure.adresse || 'Non spécifié';
            document.getElementById('modal-telephone').textContent = structure.telephone || 'Non disponible';
            document.getElementById('modal-email').textContent = structure.email || 'Non disponible';
            document.getElementById('modal-contact').textContent = structure.contact || 'Non spécifié';
            document.getElementById('modal-description').textContent = structure.description || 'Aucune description disponible';
            document.getElementById('modal-hebergement').textContent = structure.hebergement || 'Non spécifié';
            document.getElementById('modal-details').textContent = structure.details || 'Aucun détail spécifique';
        });
    });

    // Initialisation du compteur
    updateResultCount(document.querySelectorAll('.structure-card').length);
});
</script>

<style>
/* Styles spécifiques à la liste groupée */
.hidden {
    display: none;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.structure-card {
    transition: all 0.2s ease-in-out;
}

.groupe-header {
    transition: all 0.2s ease;
}

.groupe-header:hover {
    background: linear-gradient(135deg, #1a3a3e, #4b7479) !important;
}

/* Animation d'entrée */
.groupe-card {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Style pour les selects */
select {
    cursor: pointer;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="%23999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>');
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 12px;
    padding-right: 28px !important;
}

/* Badge de filtre actif */
.bg-\[\\#255156\]\/10 {
    transition: all 0.2s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .groupe-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .groupe-header .flex.items-center.justify-between {
        flex-direction: column;
        width: 100%;
    }
    
    .flex.flex-wrap.items-center.gap-2 {
        flex-direction: column;
        align-items: stretch;
    }
    
    .flex-1.min-w-\[200px\],
    .w-48,
    .w-40 {
        width: 100%;
    }
}
</style>
@endsection