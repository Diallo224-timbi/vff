@extends('base')

@section('title', 'Annuaire des membres')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="container mx-auto px-0 py-0">
        <h1 class="text-4xl font-bold text-center mb-8 bg-gradient-to-r from-[#4a8599] to-[#255156] bg-clip-text text-transparent">
            Annuaire des membres
        </h1> 
        <!-- Barre de recherche et filtre - Design compact -->
        <div class="max-w-5xl mx-auto mb-8">
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-0">
                <div class="flex flex-col lg:flex-row gap-3">
                    <!-- Champ de recherche -->
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" name="search"
                               placeholder="Rechercher par nom, email, fonction..."
                               class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#255156] focus:border-transparent text-sm bg-gray-50 hover:bg-white transition-all">
                    </div>   
                    <!-- Filtre par structure -->
                    <div class="lg:w-72 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <select id="structureFilter" class="w-full pl-0 pr-0 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#255156] focus:border-transparent appearance-none bg-gray-50 hover:bg-white transition-all cursor-pointer text-sm">
                            <option value="">Toutes les structures</option>
                            @php
                                $structuresUniques = $membres->unique(function($membre) {
                                    return $membre->structure->id ?? null;
                                })->sortBy(function($membre) {
                                    return $membre->structure->organisme->nom_organisme ?? '';
                                });
                            @endphp
                            @foreach($structuresUniques as $membre)
                                @if($membre->structure)
                                    <option value="{{ $membre->structure->id }}">
                                        {{ $membre->structure->organisme->nom_organisme ?? 'N/A' }}
                                        @if($membre->structure->ville) - {{ $membre->structure->ville }} @endif
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-0 flex items-center pointer-events-none">
                            <svg class="h-0 w-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <!-- Badges des filtres actifs -->
                <div id="activeFilters" class="flex flex-wrap gap-2 mt-3 min-h-[32px]"></div>
            </div>
        </div>
        <!-- Compteur de résultats -->
        <div class="text-center mb-6">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white shadow-sm">
                <span id="resultsCount">0</span>
                <span class="ml-1">membre(s) trouvé(s)</span>
            </span>
        </div>
        <!-- Liste des membres -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5" id="members-container">
            @foreach($membres as $membre)
                <div class="member-card bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden group border border-gray-100"
                     data-structure-id="{{ $membre->structure->id ?? '' }}">
                    <div class="h-1 bg-gradient-to-r from-[#1780a3] to-[#255156]"></div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-lg font-bold text-gray-800 group-hover:text-[#255156] transition-colors line-clamp-1">
                                {{ $membre->prenom }} {{ $membre->name }}
                            </h2>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#255156] to-[#4a8599] flex items-center justify-center text-white font-bold text-xs shadow-md">
                                {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p class="text-gray-600 flex items-start">
                                <svg class="w-3.5 h-3.5 mr-2 text-[#255156] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="truncate"><strong>Email :</strong> <span class="member-email">{{ $membre->email }}</span></span>
                            </p>
                            <p class="text-gray-600 flex items-start">
                                <svg class="w-3.5 h-3.5 mr-2 text-[#255156] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="line-clamp-2">
                                    <strong>Structure :</strong>
                                    <span class="member-structure">
                                        {{ $membre->structure->organisme->nom_organisme ?? 'N/A' }}   
                                    </span>
                                </span>
                            </p>
                            <p class="text-gray-600 flex items-start">
                                <svg class="w-3.5 h-3.5 mr-2 text-[#255156] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span><strong>Téléphone :</strong> <span class="member-phone">{{ $membre->phone ?? 'Non renseigné' }}</span></span>
                            </p>
                            <p class="text-gray-600 flex items-start">
                                <svg class="w-3.5 h-3.5 mr-2 text-[#255156] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="truncate"><strong>Fonction :</strong> <span class="member-function">{{ $membre->fonction ?? 'Non renseignée' }}</span></span>  
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Aucun résultat -->
        <div id="no-results" class="text-center py-12 hidden">
            <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md mx-auto">
                <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun membre trouvé</h3>
                <p class="text-gray-500">Essayez de modifier vos critères de recherche</p>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            @if(method_exists($membres, 'links'))
                <div class="flex justify-center">
                    {{ $membres->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    const searchInput = document.querySelector('#search');
    const structureFilter = document.querySelector('#structureFilter');
    const memberCards = document.querySelectorAll('.member-card');
    const noResultsDiv = document.querySelector('#no-results');
    const activeFiltersDiv = document.querySelector('#activeFilters');
    const resultsCountSpan = document.querySelector('#resultsCount');
    
    let currentStructureFilter = '';
    let currentSearchTerm = '';

    function updateActiveFiltersBadges() {
        activeFiltersDiv.innerHTML = '';
        
        if (currentStructureFilter) {
            const selectedOption = structureFilter.options[structureFilter.selectedIndex];
            const badge = document.createElement('div');
            badge.className = 'inline-flex items-center bg-[#255156]/10 text-[#255156] rounded-full px-2.5 py-1 text-xs font-medium';
            badge.innerHTML = `
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span>${selectedOption.text.length > 40 ? selectedOption.text.substring(0, 40) + '...' : selectedOption.text}</span>
                <button type="button" class="ml-1.5 hover:scale-110 transition-transform">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            badge.querySelector('button').addEventListener('click', () => {
                structureFilter.value = '';
                currentStructureFilter = '';
                filterMembers();
            });
            activeFiltersDiv.appendChild(badge);
        }
        
        if (currentSearchTerm) {
            const badge = document.createElement('div');
            badge.className = 'inline-flex items-center bg-blue-50 text-blue-700 rounded-full px-2.5 py-1 text-xs font-medium';
            badge.innerHTML = `
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span>"${currentSearchTerm.length > 30 ? currentSearchTerm.substring(0, 30) + '...' : currentSearchTerm}"</span>
                <button type="button" class="ml-1.5 hover:scale-110 transition-transform">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            badge.querySelector('button').addEventListener('click', () => {
                searchInput.value = '';
                currentSearchTerm = '';
                filterMembers();
            });
            activeFiltersDiv.appendChild(badge);
        }
    }

    function filterMembers() {
        currentSearchTerm = searchInput.value.toLowerCase().trim();
        currentStructureFilter = structureFilter.value;
        
        let visibleCount = 0;

        memberCards.forEach(card => {
            const name = card.querySelector('h2').textContent.toLowerCase();
            const email = card.querySelector('.member-email').textContent.toLowerCase();
            const phone = card.querySelector('.member-phone').textContent.toLowerCase();
            const structure = card.querySelector('.member-structure').textContent.toLowerCase();
            const fonction = card.querySelector('.member-function').textContent.toLowerCase();
            const structureId = card.dataset.structureId;

            const matchesSearch = !currentSearchTerm || 
                name.includes(currentSearchTerm) ||
                email.includes(currentSearchTerm) ||
                phone.includes(currentSearchTerm) ||
                structure.includes(currentSearchTerm) ||
                fonction.includes(currentSearchTerm);

            const matchesStructure = !currentStructureFilter || structureId === currentStructureFilter;

            if (matchesSearch && matchesStructure) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        resultsCountSpan.textContent = visibleCount;
        noResultsDiv.classList.toggle('hidden', visibleCount !== 0);
        updateActiveFiltersBadges();
    }

    let timeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(filterMembers, 300);
    });

    structureFilter.addEventListener('change', filterMembers);
    filterMembers();
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.member-card { 
    animation: fadeIn 0.3s ease-out;
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Style pour la pagination */
.pagination {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

.pagination .page-item {
    list-style: none;
}

.pagination .page-link {
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    background: white;
    color: #255156;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 0.875rem;
}

.pagination .page-link:hover {
    background: #255156;
    color: white;
    transform: translateY(-1px);
}

.pagination .active .page-link {
    background: #255156;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .member-card {
        font-size: 0.875rem;
    }
}
</style>
@endsection