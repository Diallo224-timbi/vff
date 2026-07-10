@extends('base')

@section('title', 'Annuaire des membres')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #E9F7F6 0%, #d4ecea 100%);">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- En-tête avec compteur animé -->
        <div class="flex flex-col sm:flex-row items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl" style="background: linear-gradient(135deg, #255156, #3a7378); box-shadow: 0 4px 12px rgba(37,81,86,0.3);">
                    <i class='bx bx-group' style="font-size: 2rem; color: white;"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold" style="color: #255156;">Annuaire des membres</h1>
                    <p class="text-sm" style="color: #4a7a7f;">Gestion et consultation des membres</p>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-3 sm:mt-0">
                <div class="flex items-center gap-3 px-5 py-2.5 rounded-lg" style="background: white; border: 1px solid #dceeec; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <i class='bx bx-user-check' style="color: #255156; font-size: 1.3rem;"></i>
                    <span class="text-base font-semibold" style="color: #255156;" id="resultsCount">0</span>
                    <span class="text-sm" style="color: #4a7a7f;">membres</span>
                </div>
                <button onclick="resetAllFilters()" id="resetAllBtn" 
                        class="px-4 py-2.5 rounded-lg text-sm font-medium transition-all hidden"
                        style="background: #e8f3f2; color: #255156; border: 1px solid #dceeec;">
                    <i class='bx bx-reset'></i> Réinitialiser
                </button>
            </div>
        </div>

        <!-- Filtres -->
        <div class="rounded-xl shadow-sm p-5 mb-6" style="background: white; border: 1px solid #dceeec; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Recherche -->
                <div class="flex-1 relative">
                    <i class='bx bx-search' style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #7fa8ac; font-size: 1.3rem;"></i>
                    <input type="text" id="search" placeholder="Rechercher par nom, email, fonction, structure, ville..."
                           class="w-full pl-12 pr-4 py-3 rounded-lg text-base focus:outline-none focus:ring-2 transition-all"
                           style="border: 1px solid #dceeec; background: #f8fcfc; color: #255156; font-size: 1rem;"
                           onfocus="this.style.borderColor='#2d6268'; this.style.boxShadow='0 0 0 4px rgba(45,98,104,0.08)';"
                           onblur="this.style.borderColor='#dceeec'; this.style.boxShadow='none';">
                </div>

                <!-- Filtre Ville -->
                <div class="lg:w-60 relative">
                    <i class='bx bx-map' style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #7fa8ac; font-size: 1.3rem;"></i>
                    <select id="cityFilter"
                            class="w-full pl-12 pr-4 py-3 rounded-lg text-base focus:outline-none focus:ring-2 transition-all appearance-none cursor-pointer"
                            style="border: 1px solid #dceeec; background: #f8fcfc; color: #255156; font-size: 1rem;"
                            onfocus="this.style.borderColor='#2d6268'; this.style.boxShadow='0 0 0 4px rgba(45,98,104,0.08)';"
                            onblur="this.style.borderColor='#dceeec'; this.style.boxShadow='none';">
                        <option value="">Toutes les villes</option>
                        @php
                            $villesUniques = collect();
                            foreach($membres as $membre) {
                                if($membre->structure && $membre->structure->ville) {
                                    $ville = trim($membre->structure->ville);
                                    if(!empty($ville) && !$villesUniques->has($ville)) {
                                        $villesUniques->put($ville, $ville);
                                    }
                                }
                            }
                            $villesUniques = $villesUniques->sort();
                        @endphp
                        @foreach($villesUniques as $ville)
                            <option value="{{ $ville }}">
                                {{ $ville }}
                            </option>
                        @endforeach
                    </select>
                    <i class='bx bx-chevron-down' style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #7fa8ac; font-size: 1.3rem; pointer-events: none;"></i>
                </div>

                <!-- Tri -->
                <div class="lg:w-48 relative">
                    <i class='bx bx-sort-alt-2' style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #7fa8ac; font-size: 1.3rem;"></i>
                    <select id="sortFilter"
                            class="w-full pl-12 pr-4 py-3 rounded-lg text-base focus:outline-none focus:ring-2 transition-all appearance-none cursor-pointer"
                            style="border: 1px solid #dceeec; background: #f8fcfc; color: #255156; font-size: 1rem;"
                            onfocus="this.style.borderColor='#2d6268'; this.style.boxShadow='0 0 0 4px rgba(45,98,104,0.08)';"
                            onblur="this.style.borderColor='#dceeec'; this.style.boxShadow='none';">
                        <option value="name_asc">Nom (A-Z)</option>
                        <option value="name_desc">Nom (Z-A)</option>
                        <option value="city">Ville</option>
                    </select>
                </div>
            </div>
            
            <!-- Filtres actifs -->
            <div id="activeFilters" class="flex flex-wrap gap-2 mt-3 min-h-[32px]"></div>
        </div>

        <!-- Grille des membres - 4 colonnes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="members-container">
            @foreach($membres as $membre)
                <div class="member-card group rounded-2xl p-0 transition-all duration-300 cursor-pointer"
                     style="background: white; border: 1px solid #e8f3f2; box-shadow: 0 2px 12px rgba(0,0,0,0.04);"
                     data-city="{{ strtolower($membre->structure->ville ?? '') }}"
                     data-name="{{ strtolower($membre->prenom . ' ' . $membre->name) }}"
                     data-email="{{ strtolower($membre->email) }}"
                     data-structure="{{ strtolower($membre->structure->organisme->nom_organisme ?? '') }}"
                     >
                    
                    <!-- Bandeau supérieur -->
                    <div class="h-2 rounded-t-2xl" style="background: linear-gradient(90deg, #255156, #4a8599, #2d6268); background-size: 200% 100%;"></div>
                    
                    <div class="p-4">
                        <!-- En-tête avec avatar -->
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-lg font-bold flex-shrink-0 transition-transform group-hover:scale-110 group-hover:rotate-3"
                                 style="background: linear-gradient(135deg, #255156, #4a8599); color: white; box-shadow: 0 4px 16px rgba(37,81,86,0.25);">
                                {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-base font-bold truncate" style="color: #1a3c40;">
                                    {{ $membre->prenom }} {{ $membre->name }}
                                </h2>
                                <p class="text-xs truncate flex items-center gap-1" style="color: #4a7a7f;">
                                    <i class='bx bx-briefcase-alt-2' style="font-size: 0.7rem;"></i>
                                    <span>{{ $membre->fonction ?? 'Fonction non renseignée' }}</span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Informations de contact -->
                        <div class="space-y-2 text-xs" style="color: #3d6f74;">
                            <div class="flex items-center gap-2 p-2 rounded-lg transition-colors" style="background: #f8fcfc;">
                                <i class='bx bx-envelope' style="color: #4a8599; font-size: 1rem;"></i>
                                <span class="member-email truncate text-xs">{{ $membre->email }}</span>
                            </div>
                            <div class="flex items-center gap-2 p-2 rounded-lg transition-colors" style="background: #f8fcfc;">
                                <i class='bx bx-phone' style="color: #4a8599; font-size: 1rem;"></i>
                                <span class="member-phone text-xs">{{ $membre->phone ?? 'Non renseigné' }}</span>
                            </div>
                            <div class="flex items-center gap-2 p-2 rounded-lg transition-colors" style="background: #f8fcfc;">
                                <i class='bx bx-building' style="color: #4a8599; font-size: 1rem;"></i>
                                <span class="member-structure font-medium text-xs truncate" style="color: #255156;">
                                    {{ $membre->structure->organisme->nom_organisme ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 p-2 rounded-lg transition-colors" style="background: #f8fcfc;">
                                <i class='bx bx-map' style="color: #4a8599; font-size: 1rem;"></i>
                                <span class="member-city text-xs" style="color: #255156; font-weight: 500;">
                                    {{ $membre->structure->ville ?? 'Ville non renseignée' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Footer avec badges -->
                        <div class="mt-3 pt-2 flex items-center justify-between border-t" style="border-color: #f0f6f5;">
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                      style="background: #e8f3f2; color: #255156;">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1" style="background: #4a8599; animation: pulse 2s infinite;"></span>
                                    Actif
                                </span>
                                @if($membre->structure && $membre->structure->ville)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs"
                                      style="background: #e8f3f2; color: #4a7a7f;">
                                    <i class='bx bx-map' style="font-size: 0.6rem; margin-right: 2px;"></i>
                                    {{ $membre->structure->ville }}
                                </span>
                                @endif
                            </div>
                            <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:translate-x-0 translate-x-2">
                                <span class="inline-flex items-center text-xs font-medium" style="color: #255156;">
                                    Voir
                                    <i class='bx bx-right-arrow-alt ml-1'></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Aucun résultat -->
        <div id="no-results" class="hidden text-center py-12">
            <div class="rounded-2xl p-8 max-w-md mx-auto" style="background: white; border: 1px solid #e8f3f2; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
                <i class='bx bx-user-x' style="font-size: 4rem; color: #dceeec;"></i>
                <h3 class="text-xl font-semibold mt-3" style="color: #255156;">Aucun membre trouvé</h3>
                <p class="text-sm mt-1" style="color: #7fa8ac;">Aucun membre ne correspond à vos critères de recherche</p>
                <button onclick="resetAllFilters()" 
                        class="mt-4 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors"
                        style="background: #255156; color: white;"
                        onmouseover="this.style.background='#1d4145'"
                        onmouseout="this.style.background='#255156'">
                    <i class='bx bx-reset'></i> Réinitialiser les filtres
                </button>
            </div>
        </div>

        <!-- Pagination -->
        @if(method_exists($membres, 'links'))
            <div class="mt-6">
                {{ $membres->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    // Éléments DOM
    const searchInput = document.getElementById('search');
    const cityFilter = document.getElementById('cityFilter');
    const sortFilter = document.getElementById('sortFilter');
    const memberCards = document.querySelectorAll('.member-card');
    const noResultsDiv = document.getElementById('no-results');
    const activeFiltersDiv = document.getElementById('activeFilters');
    const resultsCountSpan = document.getElementById('resultsCount');
    const resetAllBtn = document.getElementById('resetAllBtn');

    let currentFilters = {
        search: '',
        city: '',
        sort: 'name_asc'
    };

    let searchTimeout = null;

    // Fonction de tri
    function sortMembers(sortType) {
        const container = document.getElementById('members-container');
        const cards = Array.from(memberCards);
        
        cards.sort((a, b) => {
            const nameA = a.dataset.name || '';
            const nameB = b.dataset.name || '';
            const cityA = a.dataset.city || '';
            const cityB = b.dataset.city || '';

            switch(sortType) {
                case 'name_asc':
                    return nameA.localeCompare(nameB);
                case 'name_desc':
                    return nameB.localeCompare(nameA);
                case 'city':
                    return cityA.localeCompare(cityB);
                default:
                    return 0;
            }
        });

        cards.forEach(card => container.appendChild(card));
    }

    // Mise à jour des badges de filtres
    function updateActiveFilters() {
        activeFiltersDiv.innerHTML = '';
        let hasFilters = false;

        if (currentFilters.city) {
            const selectedOption = cityFilter.options[cityFilter.selectedIndex];
            addFilterBadge('📍 ' + selectedOption.text, () => {
                cityFilter.value = '';
                currentFilters.city = '';
                applyFilters();
            });
            hasFilters = true;
        }

        if (currentFilters.search) {
            addFilterBadge('🔍 "' + currentFilters.search + '"', () => {
                searchInput.value = '';
                currentFilters.search = '';
                applyFilters();
            });
            hasFilters = true;
        }

        resetAllBtn.classList.toggle('hidden', !hasFilters);
    }

    function addFilterBadge(text, onRemove) {
        const badge = document.createElement('span');
        badge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium transition-all';
        badge.style.cssText = 'background: #e8f3f2; color: #255156; border: 1px solid #dceeec;';
        badge.innerHTML = `
            ${text}
            <button class="hover:scale-125 transition-transform" style="background: none; border: none; cursor: pointer; padding: 0 2px; color: #4a7a7f;">
                <i class='bx bx-x'></i>
            </button>
        `;
        badge.querySelector('button').addEventListener('click', onRemove);
        activeFiltersDiv.appendChild(badge);
    }

    // Application des filtres avec animation
    function applyFilters() {
        currentFilters.search = searchInput.value.toLowerCase().trim();
        currentFilters.city = cityFilter.value;
        currentFilters.sort = sortFilter.value;

        let visibleCount = 0;
        const searchTerms = currentFilters.search.split(/\s+/).filter(t => t.length > 0);

        memberCards.forEach(card => {
            const name = card.dataset.name || '';
            const email = card.dataset.email || '';
            const structure = card.dataset.structure || '';
            const city = card.dataset.city || '';
            const textContent = card.textContent.toLowerCase();
            
            let matchSearch = !currentFilters.search;
            if (currentFilters.search) {
                matchSearch = searchTerms.every(term => 
                    name.includes(term) || 
                    email.includes(term) || 
                    structure.includes(term) ||
                    city.includes(term) ||
                    textContent.includes(term)
                );
            }

            const matchCity = !currentFilters.city || 
                             city === currentFilters.city.toLowerCase();

            if (matchSearch && matchCity) {
                card.style.display = '';
                visibleCount++;
                // Animation d'apparition
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95) translateY(10px)';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1) translateY(0)';
                }, 50 + (visibleCount * 20));
            } else {
                card.style.display = 'none';
            }
        });

        // Tri après filtrage
        sortMembers(currentFilters.sort);

        resultsCountSpan.textContent = visibleCount;
        noResultsDiv.classList.toggle('hidden', visibleCount > 0);
        updateActiveFilters();
    }

    // Réinitialisation complète
    function resetAllFilters() {
        searchInput.value = '';
        cityFilter.value = '';
        sortFilter.value = 'name_asc';
        currentFilters = { search: '', city: '', sort: 'name_asc' };
        applyFilters();
        searchInput.focus();
    }

    // Événements avec debounce
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 200);
    });

    cityFilter.addEventListener('change', applyFilters);
    sortFilter.addEventListener('change', applyFilters);

    // Touche Échap
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            resetAllFilters();
            searchInput.blur();
        }
    });

    // Fonction de visualisation du profil


    // Initialisation
    applyFilters();

    // Animation au survol des cartes
    document.querySelectorAll('.member-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.borderColor = '#2d6268';
            this.style.boxShadow = '0 12px 40px rgba(45,98,104,0.15)';
            this.style.transform = 'translateY(-6px) scale(1.01)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.borderColor = '#e8f3f2';
            this.style.boxShadow = '0 2px 12px rgba(0,0,0,0.04)';
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
</script>

<style>
    /* Animation de pulsation pour le statut */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    /* Animation d'entrée des cartes */
    .member-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 1;
        transform: scale(1) translateY(0);
        position: relative;
        overflow: hidden;
    }

    /* Style de la pagination */
    .pagination {
        display: flex;
        gap: 0.4rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .pagination .page-item {
        list-style: none;
    }
    .pagination .page-link {
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        background: white;
        color: #255156;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 0.95rem;
        border: 1px solid #dceeec;
        font-weight: 500;
    }
    .pagination .page-link:hover {
        background: #255156;
        color: white;
        border-color: #255156;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37,81,86,0.2);
    }
    .pagination .active .page-link {
        background: #255156;
        color: white;
        border-color: #255156;
        box-shadow: 0 4px 12px rgba(37,81,86,0.2);
    }
    .pagination .disabled .page-link {
        color: #b0c8cb;
        cursor: not-allowed;
        background: #f8fcfc;
    }
    
    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #e8f3f2;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb {
        background: #4a8599;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #255156;
    }

    /* Responsive */
    @media (max-width: 1280px) {
        .member-card .p-4 {
            padding: 1rem;
        }
        .member-card .w-14 {
            width: 3.25rem;
            height: 3.25rem;
        }
        .member-card .text-base {
            font-size: 0.95rem;
        }
    }

    @media (max-width: 640px) {
        .member-card .p-4 {
            padding: 0.9rem;
        }
        .member-card .w-14 {
            width: 3rem;
            height: 3rem;
        }
    }

    /* Effet de brillance */
    .member-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        transition: left 0.6s;
        pointer-events: none;
        border-radius: 1rem;
    }
    .member-card:hover::before {
        left: 100%;
    }
</style>
@endsection