@extends('base')

@section('title', 'Annuaire des structures')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Message succès -->
    @if(session('success'))
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-lg mb-6 shadow-lg border-l-4 border-white"
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"    
        >
            <div class="flex items-center">
                <i class="fas fa-check-circle text-xl mr-3"></i>
                <div>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @elseif(session('errors'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-4 rounded-lg mb-6 shadow-lg border-l-4 border-white"
        >
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                <div>
                    <p class="font-semibold">{{ session('errors') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Barre d'actions -->
    <div class="flex flex-wrap items-center justify-between mb-2 p-2 bg-white rounded-xl border border-gray-100">
        <div class="flex flex-wrap items-center gap-3">
            <!-- Bouton ajouter structure -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur')
                <button class="btn-primary-custom flex items-center gap-2 px-2 py-2 rounded-lg font-semibold transition-all hover:scale-[1.02] active:scale-[0.98]" 
                        data-bs-toggle="modal" 
                        data-bs-target="#addModal">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter une structure
                </button>
            

            <!-- Bouton Export PDF -->
                <a href="{{ route('annuaire.export.pdf') }}" 
                class="btn-danger-custom flex items-center gap-2 px-6 py-3 rounded-lg font-semibold transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-file-pdf"></i>
                    Exporter PDF
                </a>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-3">
            {{--fiche structure --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('structures.map') }}" 
                class="btn-secondary-custom flex items-center gap-2 px-6 py-3 rounded-lg font-semibold transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-map-marked-alt"></i>
                    Voir la carte
                </a>
            </div>
            {{-- fiche structure pour voir la liste des strure antenne responsable et contact --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('annuaire.list') }}" 
                class="btn-secondary-custom flex items-center gap-2 px-6 py-3 rounded-lg font-semibold transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-list"></i>
                    Voir la liste
                </a>
            </div>
        </div>    
        <div class="mt-4 md:mt-0">
            <div class="text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                <i class="fas fa-info-circle mr-2 text-[#255156]"></i>
                <span id="resultCount">{{ $structures->total() }} structures</span>
                <span id="filteredCount" class="hidden ml-2">
                    (<span id="filteredNumber" class="font-semibold text-[#255156]">0</span> filtrées)
                </span>
            </div>
        </div>
    </div>

    <!-- Section recherche et filtres -->
    <div class="bg-white p-2 rounded-xl shadow-lg mb-2 border border-gray-100" style="max-height:110px; overflow-y:auto;">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
            <!-- Recherche dynamique -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Recherche globale</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" class="form-control-professional pl-12 w-full" 
                           placeholder="Rechercher par nom, ville, catégorie...">
                </div>
                <p class="text-xs text-gray-500 mt-2 ml-1">
                    <i class="fas fa-lightbulb mr-1"></i>Recherche en temps réel dans toutes les colonnes
                </p>
            </div>
            
            <!-- Filtre par catégorie -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Catégorie</label>
                <div class="relative">
                    <i class="fas fa-tags absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <select id="filterCategory" class="form-control-professional pl-12 w-full">
                        <option value="">Toutes les catégories</option>
                        @php
                            $categories = $structures->pluck('categories')->unique()->filter()->sort();
                        @endphp
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Filtre par ville -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Ville</label>
                <div class="relative">
                    <i class="fas fa-city absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <select id="filterCity" class="form-control-professional pl-12 w-full">
                        <option value="">Toutes les villes</option>
                        @php
                            $villes = $structures->pluck('ville')->unique()->filter()->sort();
                        @endphp
                        @foreach($villes as $ville)
                            <option value="{{ $ville }}">{{ $ville }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Filtres avancés -->
        <div class="mb-2">
            <button id="toggleAdvancedFilters" 
                    class="text-[#255156] hover:text-[#8bbdc3] font-semibold text-sm flex items-center gap-2 px-4 py-3 rounded-lg border border-gray-200 hover:border-[#8bbdc3] transition-all">
                <i class="fas fa-sliders-h"></i>
                <span>Filtres avancés</span>
                <i class="fas fa-chevron-down ml-auto transition-transform" id="filterArrow"></i>
            </button>
            
            <div id="advancedFilters" class="hidden mt-4 p-6 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Filtre par type de structure -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Type de structure</label>
                        <div class="relative">
                            <i class="fas fa-building absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select id="filterType" class="form-control-professional pl-12 w-full">
                                <option value="">Tous les types</option>
                                @php
                                    $types = $structures->pluck('type_structure')->unique()->filter()->sort();
                                @endphp
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Filtre par zone -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Zone géographique</label>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select id="filterZone" class="form-control-professional pl-12 w-full">
                                <option value="">Toutes les zones</option>
                                @php
                                    $zones = $structures->pluck('zone')->unique()->filter()->sort();
                                @endphp
                                @foreach($zones as $zone)
                                    <option value="{{ $zone }}">{{ $zone }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Filtre par public cible -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Public cible</label>
                        <div class="relative">
                            <i class="fas fa-users absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select id="filterPublic" class="form-control-professional pl-12 w-full">
                                <option value="">Tous les publics</option>
                                @php
                                    $publics = $structures->pluck('public_cible')->unique()->filter()->sort();
                                @endphp
                                @foreach($publics as $public)
                                    <option value="{{ $public }}">{{ $public }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons de réinitialisation -->
                <div class="mt-8 flex justify-end">
                    <button id="resetFilters" 
                            class="flex items-center gap-2 px-6 py-3 rounded-lg font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all">
                        <i class="fas fa-undo"></i>
                        Réinitialiser tous les filtres
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table avec design professionnel -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-auto" style="max-height:500px;">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white sticky top-0 z-2">
                    <tr>
                        <th class="px-3 py-2 text-left font-bold text-sm uppercase tracking-wider border-r border-white/20">Organisme</th>
                        <th class="px-3 py-2 text-left font-bold text-sm uppercase tracking-wider border-r border-white/20">Siège Ville</th>
                        <th class="px-3 py-2 text-left font-bold text-sm uppercase tracking-wider border-r border-white/20">Siège Adresse</th>
                        <th class="px-3 py-2 text-left font-bold text-sm uppercase tracking-wider border-r border-white/20">Catégories</th>
                        <th class="px-3 py-2 text-left font-bold text-sm uppercase tracking-wider border-r border-white/20">Site web</th>
                        <th class="px-3 py-2 text-left font-bold text-sm uppercase tracking-wider border-r border-white/20">Public Cible</th>
                        <th class="px-3 py-2 text-left font-bold text-sm uppercase tracking-wider border-r border-white/20">Zone</th>
                        <th class="px-3 py-2 text-left font-bold text-sm uppercase tracking-wider border-r border-white/20">Type Structure</th>
                        <th class="px-3 py-2 text-left font-bold text-sm uppercase tracking-wider border-r border-white/20">Ville</th>
                        <th class="px-3 py-2 text-left font-bold text-sm uppercase tracking-wider border-r border-white/20">Adresse</th>
                        <th class="px-3 py-2 text-left font-bold text-sm uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="structuresTableBody" class="divide-y divide-gray-200">
                    @foreach($structures as $structure)
                        <tr class="structure-row hover:bg-blue-50/50 transition-colors duration-150" 
                            data-category="{{ $structure->categories ?? '' }}"
                            data-city="{{ $structure->ville ?? '' }}"
                            data-type="{{ $structure->type_structure ?? '' }}"
                            data-zone="{{ $structure->zone ?? '' }}"
                            data-public="{{ $structure->public_cible ?? '' }}">
                            
                            <!-- 🟢 ORGANISME AVEC LOGO -->
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <!-- Logo de la structure -->
                                    <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg overflow-hidden border border-gray-200 flex items-center justify-center">
                                        @if($structure->logo)
                                            <img src="{{ asset('storage/' . $structure->logo) }}" 
                                                 alt="Logo {{ $structure->organisme }}"
                                                 class="w-full h-full object-contain"
                                                 onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-building text-gray-400 text-sm\'></i>';">
                                        @else
                                            <i class="fas fa-building text-gray-400 text-sm"></i>
                                        @endif
                                    </div>
                                    <!-- Nom de l'organisme -->
                                    <div class="font-semibold text-gray-800 text-sm truncate max-w-[100px]" title="{{ $structure->organisme }}">
                                        {{ $structure->organisme }}
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Siège Ville -->
                            <td class="px-3 py-2 truncate max-w-[120px]">
                                <div class="text-gray-700 text-sm truncate max-w-[100px]" title="{{ $structure->siege_ville ?? '' }}">
                                    {{ $structure->siege_ville ?? '-' }}
                                </div>
                            </td>
                            
                            <!-- Siège Adresse -->
                            <td class="px-3 py-2 truncate max-w-[120px]">
                                <div class="text-gray-600 text-sm truncate max-w-[150px]" title="{{ $structure->siege_adresse ?? '' }}">
                                    {{ $structure->siege_adresse ?? '-' }}
                                </div>
                            </td>
                            
                            <!-- Catégories -->
                            <td class="px-3 py-2 truncate max-w-[120px]">
                                <div class="text-gray-700 text-sm truncate max-w-[120px]" title="{{ $structure->categories ?? '' }}">
                                    {{ $structure->categories ?? '-' }}
                                </div>
                            </td>
                            
                            <!-- Site web -->
                            <td class="px-3 py-2 truncate max-w-[120px]">
                                @if($structure->site)
                                    <a href="{{ $structure->site }}" target="_blank" 
                                       class="site-link-table text-[#255156] hover:text-[#8bbdc3] text-sm font-medium truncate max-w-[120px] block"
                                       title="{{ $structure->site }}">
                                        {{ Str::limit($structure->site, 30) }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            
                            <!-- Public Cible -->
                            <td class="px-3 py-2 truncate max-w-[120px]">
                                <div class="text-gray-700 text-sm truncate max-w-[120px]" title="{{ $structure->public_cible ?? '' }}">
                                    {{ $structure->public_cible ?? '-' }}
                                </div>
                            </td>
                            
                            <!-- Zone -->
                            <td class="px-3 py-2 truncate max-w-[120px]">
                                <div class="text-gray-700 text-sm truncate max-w-[100px]" title="{{ $structure->zone ?? '' }}">
                                    {{ $structure->zone ?? '-' }}
                                </div>
                            </td>
                            
                            <!-- Type Structure -->
                            <td class="px-3 py-2 truncate max-w-[120px]">
                                <div class="text-gray-700 text-sm truncate max-w-[100px]" title="{{ $structure->type_structure ?? '' }}">
                                    {{ $structure->type_structure ?? '-' }}
                                </div>
                            </td>
                            
                            <!-- Ville -->
                            <td class="px-3 py-2 truncate max-w-[120px]">
                                <div class="text-gray-700 text-sm truncate max-w-[100px]" title="{{ $structure->ville ?? '' }}">
                                    {{ $structure->ville ?? '-' }}
                                </div>
                            </td>
                            
                            <!-- Adresse -->
                            <td class="px-3 py-2 truncate max-w-[120px]">
                                <div class="text-gray-600 text-sm truncate max-w-[150px]" title="{{ $structure->adresse ?? '' }}">
                                    {{ $structure->adresse ?? '-' }}
                                </div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-1">
                                    <!-- Bouton Voir détails (tous les utilisateurs) -->
                                    <button class="btn-action-primary view-details-btn" 
                                            title="Voir les détails"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailsModal"
                                            data-structure='@json($structure)'>
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>

                                    <!-- ADMIN : Peut modifier et supprimer TOUTES les structures -->
                                    @if(auth()->user()->role === 'admin')
                                        <button class="btn-action-warning edit-btn" 
                                                data-id="{{ $structure->id }}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal"
                                                title="Modifier">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>

                                        <form action="{{ route('structures.destroy', $structure) }}" 
                                            method="POST" 
                                            class="inline"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette structure ? Attention, tous les utilisateurs rattachés seront aussi supprimés.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-action-danger" type="submit" title="Supprimer">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>

                                    <!-- MODERATEUR : Peut modifier et supprimer UNIQUEMENT sa propre structure -->
                                    @elseif(auth()->user()->role === 'moderateur' && isset(auth()->user()->id_structure) && auth()->user()->id_structure === $structure->id)
                                        <button class="btn-action-warning edit-btn" 
                                                data-id="{{ $structure->id }}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal"
                                                title="Modifier ma structure">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>

                                        <form action="{{ route('structures.destroy', $structure) }}" 
                                            method="POST" 
                                            class="inline"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer VOTRE structure ? Attention, tous les utilisateurs rattachés seront aussi supprimés.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-action-danger" type="submit" title="Supprimer ma structure">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if($structures->isEmpty())
                        <tr id="noResultsRow">
                            <td colspan="11" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-building text-4xl mb-4 opacity-50"></i>
                                    <p class="text-lg font-medium mb-2">Aucune structure trouvée</p>
                                    <p class="text-sm">Essayez de modifier vos critères de recherche</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        {{ $structures->links() }}
    </div>
</div>

<!-- MODAL AJOUT (admin seulement) -->
@if(auth()->user()->role === 'admin')
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-2xl">
            <div class="modal-header bg-gradient-to-r from-[#255156] to-[#4b7479] text-white rounded-t-lg">
                <h5 class="modal-title text-xl font-bold flex items-center gap-3">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter une structure
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-gray-50">
                @include('structures.form', [
                    'structure' => new \App\Models\Structures,
                    'action' => route('structures.store'),
                    'method' => 'POST'
                ])
            </div>
        </div>
    </div>
</div>
@endif

<!-- MODAL MODIFIER - ACCESSIBLE À TOUS (ADMIN ET MODERATEUR) -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-2xl">
            <div class="modal-header bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white rounded-t-lg">
                <h5 class="modal-title text-xl font-bold flex items-center gap-3">
                    <i class="fas fa-edit"></i>
                    Modifier la structure
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-gray-50" id="editModalBody">
                <!-- Formulaire chargé dynamiquement via fetch -->
                <div class="flex justify-center items-center p-8">
                    <div class="spinner-border text-[#255156]" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🟢 MODAL DETAILS - AVEC AFFICHAGE DU LOGO -->
<div class="modal fade animate__animated" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered animate__animated animate__zoomIn">
        <div class="modal-content border-0 shadow-2xl overflow-hidden">
            <!-- Header avec animation et LOGO -->
            <div class="modal-header bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white p-4">
                <div class="flex items-center gap-3 animate__animated animate__fadeInLeft">
                    <!-- 🟢 LOGO DANS L'EN-TÊTE DE LA MODAL -->
                    <div id="modal-logo-container" class="bg-white/20 p-1 rounded-lg w-12 h-12 flex items-center justify-center">
                        <div id="modal-logo-placeholder" class="hidden">
                            <i class="fas fa-building text-white text-2xl"></i>
                        </div>
                        <img id="modal-logo-img" src="" alt="Logo" class="w-10 h-10 object-contain hidden">
                    </div>
                    <div>
                        <h5 class="modal-title text-lg font-bold" id="detailsModalLabel">
                            <span id="modal-organisme">-</span>
                        </h5>
                        <p class="text-sm text-white/80 font-medium">Structure détaillée</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-80 hover:opacity-100 transition-opacity" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body avec espace réduit -->
            <div class="modal-body bg-gray-50 p-4 max-h-[70vh] overflow-y-auto">
                
                <!-- Informations principales (2 colonnes compactes) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    <!-- Colonne gauche -->
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                        <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                            <i class="fas fa-info-circle text-xs"></i> 
                            <span>Informations principales</span>
                        </h6>
                        <div class="space-y-1.5 text-sm">
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="font-medium text-gray-600">Organisme:</span>
                                <span class="text-gray-800 font-semibold" id="modal-organisme-text">-</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="font-medium text-gray-600">Catégories:</span>
                                <span class="text-gray-800" id="modal-categories">-</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="font-medium text-gray-600">Type:</span>
                                <span class="text-gray-800" id="modal-type_structure">-</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="font-medium text-gray-600">Public:</span>
                                <span class="text-gray-800" id="modal-public_cible">-</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="font-medium text-gray-600">Zone:</span>
                                <span class="text-gray-800" id="modal-zone">-</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="font-medium text-gray-600">Site web:</span>
                                <span id="modal-site" class="text-gray-800">-</span>
                            </div>
                        </div>
                    </div>
                    <!-- Colonne droite - Localisation -->
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                        <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-xs"></i> 
                            <span>Localisation</span>
                        </h6>      
                        <!-- Siège social -->
                        <div class="mb-3 p-2 bg-blue-50/50 rounded border border-blue-100">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-landmark text-blue-500 text-xs"></i>
                                <span class="font-semibold text-blue-700 text-xs">SIÈGE SOCIAL</span>
                            </div>
                            <div class="text-xs space-y-1">
                                <div class="flex">
                                    <span class="w-16 text-gray-500">Ville:</span>
                                    <span class="text-gray-700 font-medium" id="modal-siege_ville">-</span>
                                </div>
                                <div class="flex">
                                    <span class="w-16 text-gray-500">Adresse:</span>
                                    <span class="text-gray-700 truncate" id="modal-siege_adresse" title="-">-</span>
                                </div>
                            </div>
                        </div>    
                        <!-- Antenne locale -->
                        <div class="p-2 bg-green-50/50 rounded border border-green-100">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-map-pin text-green-500 text-xs"></i>
                                <span class="font-semibold text-green-700 text-xs">ANTENNE LOCALE</span>
                            </div>
                            <div class="text-xs space-y-1">
                                <div class="flex">
                                    <span class="w-20 text-gray-500">Ville:</span>
                                    <span class="text-gray-700 font-medium" id="modal-ville">-</span>
                                </div>
                                <div class="flex">
                                    <span class="w-20 text-gray-500">Code postal:</span>
                                    <span class="text-gray-700" id="modal-code_postal">-</span>
                                </div>
                                <div class="flex">
                                    <span class="w-20 text-gray-500">Adresse:</span>
                                    <span class="text-gray-700 truncate" id="modal-adresse" title="-">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact (ligne unique compacte) -->
                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                    <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                        <i class="fas fa-address-book text-xs"></i> 
                        <span>Contact</span>
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                            <i class="fas fa-phone text-green-500"></i>
                            <div>
                                <div class="text-xs text-gray-500">Téléphone</div>
                                <div class="font-medium" id="modal-telephone">-</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                            <i class="fas fa-envelope text-blue-500"></i>
                            <div>
                                <div class="text-xs text-gray-500">Email</div>
                                <span id="modal-email" class="font-medium text-gray-800">-</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                            <i class="fas fa-user text-purple-500"></i>
                            <div>
                                <div class="text-xs text-gray-500">Contact</div>
                                <div class="font-medium truncate" id="modal-contact">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description avec badge -->
                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between mb-2">
                        <h6 class="text-[#255156] font-semibold text-sm flex items-center gap-2">
                            <i class="fas fa-file-alt text-xs"></i> 
                            <span>Description</span>
                        </h6>
                        <span class="text-xs bg-[#255156]/10 text-[#255156] px-2 py-1 rounded-full font-medium">
                            <i class="fas fa-align-left mr-1"></i> Détails
                        </span>
                    </div>
                    <div class="text-sm text-gray-700 leading-relaxed p-2 bg-gray-50 rounded" id="modal-description">
                        -
                    </div>
                </div>
                <!-- Informations complémentaires (en ligne) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                            <i class="fas fa-bed text-xs"></i> 
                            <span>Hébergement</span>
                        </h6>
                        <div class="text-sm text-gray-700" id="modal-hebergement">-</div>
                    </div>
                    
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                            <i class="fas fa-list-ul text-xs"></i> 
                            <span>Détails spécifiques</span>
                        </h6>
                        <div class="text-sm text-gray-700" id="modal-details">-</div>
                    </div>
                </div>
            </div>

            <!-- Footer avec actions -->
            <div class="modal-footer bg-white p-3 border-t border-gray-200">
                <div class="flex justify-between items-center w-full">
                    <div class="text-xs text-gray-500 flex items-center gap-2">
                        <i class="fas fa-clock"></i>
                        <span>Dernière mise à jour: <span id="modal-created_at">-</span></span>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" 
                                class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105 active:scale-95 flex items-center gap-2"
                                data-bs-dismiss="modal">
                            <i class="fas fa-times"></i>
                            Fermer
                        </button>
                        <button type="button" 
                                class="px-4 py-1.5 bg-gradient-to-r from-[#255156] to-[#8bbdc3] hover:from-[#1d4144] hover:to-[#7aa8ad] text-white rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105 active:scale-95 flex items-center gap-2"
                                onclick="window.print()">
                            <i class="fas fa-print"></i>
                            Imprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    /* Variables de couleurs */
    :root {
        --primary-color: #255156;
        --secondary-color: #8bbdc3;
        --primary-light: rgba(37, 81, 86, 0.1);
        --secondary-light: rgba(139, 189, 195, 0.1);
    }
    
    /* Styles personnalisés pour les boutons */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        box-shadow: 0 4px 15px rgba(37, 81, 86, 0.3);
    }
    
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        box-shadow: 0 6px 20px rgba(37, 81, 86, 0.4);
    }
    
    .btn-danger-custom {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        color: white;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    }
    
    .btn-danger-custom:hover {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    }
    
    .btn-secondary-custom {
        background: white;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-secondary-custom:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
    }
    
    /* Boutons d'action */
    .btn-action-primary {
        background: var(--primary-light);
        color: var(--primary-color);
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .btn-action-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: scale(1.1);
    }
    
    .btn-action-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .btn-action-warning:hover {
        background: #d97706;
        color: white;
        transform: scale(1.1);
    }
    
    .btn-action-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .btn-action-danger:hover {
        background: #dc2626;
        color: white;
        transform: scale(1.1);
    }
    
    /* Formulaire professionnel */
    .form-control-professional {
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: white;
    }
    
    .form-control-professional:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(139, 189, 195, 0.2);
        outline: none;
    }
    
    /* Table stylisée */
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 0.875rem;
    }
    
    thead th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-right: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    tbody td {
        padding: 0.75rem 1rem;
        border-right: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    
    tbody tr {
        border-bottom: 1px solid #e5e7eb;
    }
    
    tbody tr:hover {
        background-color: #f8fafc;
    }
    
    /* Liens dans la table */
    .site-link-table {
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .site-link-table:hover {
        text-decoration: underline;
    }
    
    /* Truncation pour les cellules */
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Scrollbar personnalisée */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, var(--secondary-color), var(--primary-color));
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
    }

    /* 🟢 Style pour le logo */
    .logo-container {
        transition: all 0.3s ease;
    }
    
    .logo-container:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('scripts')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
    // Variables globales pour le tri
    let currentSortColumn = null;
    let currentSortDirection = 'asc';

    // Fonction principale de filtrage et recherche
    function filterAndSearch() {
        const searchQuery = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const categoryFilter = document.getElementById('filterCategory')?.value.toLowerCase() || '';
        const cityFilter = document.getElementById('filterCity')?.value.toLowerCase() || '';
        const typeFilter = document.getElementById('filterType')?.value.toLowerCase() || '';
        const zoneFilter = document.getElementById('filterZone')?.value.toLowerCase() || '';
        const publicFilter = document.getElementById('filterPublic')?.value.toLowerCase() || '';
        
        const rows = document.querySelectorAll('.structure-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            const rowCategory = (row.dataset.category || '').toLowerCase();
            const rowCity = (row.dataset.city || '').toLowerCase();
            const rowType = (row.dataset.type || '').toLowerCase();
            const rowZone = (row.dataset.zone || '').toLowerCase();
            const rowPublic = (row.dataset.public || '').toLowerCase();
            
            // Vérifier chaque condition de filtre
            const matchesSearch = searchQuery === '' || rowText.includes(searchQuery);
            const matchesCategory = categoryFilter === '' || rowCategory.includes(categoryFilter);
            const matchesCity = cityFilter === '' || rowCity.includes(cityFilter);
            const matchesType = typeFilter === '' || rowType.includes(typeFilter);
            const matchesZone = zoneFilter === '' || rowZone.includes(zoneFilter);
            const matchesPublic = publicFilter === '' || rowPublic.includes(publicFilter);
            
            // Afficher ou masquer la ligne
            const isVisible = matchesSearch && matchesCategory && matchesCity && 
                             matchesType && matchesZone && matchesPublic;
            
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });
        
        // Mettre à jour le compteur
        updateResultCount(visibleCount, rows.length);
        
        // Gérer l'affichage du message "aucun résultat"
        const noResultsRow = document.getElementById('noResultsRow');
        if (noResultsRow) {
            noResultsRow.style.display = visibleCount === 0 ? '' : 'none';
        }
    }

    // Mettre à jour le compteur de résultats
    function updateResultCount(visible, total) {
        const resultCount = document.getElementById('resultCount');
        const filteredCount = document.getElementById('filteredCount');
        const filteredNumber = document.getElementById('filteredNumber');
        
        if (filteredNumber) filteredNumber.textContent = visible;
        
        if (resultCount) {
            if (visible < total) {
                if (filteredCount) filteredCount.classList.remove('hidden');
                resultCount.textContent = `${visible} structures trouvées (sur ${total})`;
            } else {
                if (filteredCount) filteredCount.classList.add('hidden');
                resultCount.textContent = `${total} structures trouvées`;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // RECHERCHE ET FILTRES
        // Recherche dynamique
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', filterAndSearch);
        }
        
        // Filtres
        ['filterCategory', 'filterCity', 'filterType', 'filterZone', 'filterPublic'].forEach(filterId => {
            const filter = document.getElementById(filterId);
            if (filter) {
                filter.addEventListener('change', filterAndSearch);
            }
        });
        
        // Toggle filtres avancés
        const toggleFiltersBtn = document.getElementById('toggleAdvancedFilters');
        const advancedFilters = document.getElementById('advancedFilters');
        const filterArrow = document.getElementById('filterArrow');
        
        if (toggleFiltersBtn && advancedFilters) {
            toggleFiltersBtn.addEventListener('click', () => {
                advancedFilters.classList.toggle('hidden');
                if (filterArrow) {
                    filterArrow.classList.toggle('fa-chevron-down');
                    filterArrow.classList.toggle('fa-chevron-up');
                }
            });
        }
        
        // Réinitialisation des filtres
        const resetBtn = document.getElementById('resetFilters');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                // Réinitialiser les champs
                const searchInput = document.getElementById('searchInput');
                if (searchInput) searchInput.value = '';
                
                const filterCategory = document.getElementById('filterCategory');
                if (filterCategory) filterCategory.value = '';
                
                const filterCity = document.getElementById('filterCity');
                if (filterCity) filterCity.value = '';
                
                const filterType = document.getElementById('filterType');
                if (filterType) filterType.value = '';
                
                const filterZone = document.getElementById('filterZone');
                if (filterZone) filterZone.value = '';
                
                const filterPublic = document.getElementById('filterPublic');
                if (filterPublic) filterPublic.value = '';
                
                // Appliquer le filtrage
                filterAndSearch();
            });
        }

        // EDIT MODAL - Chargement dynamique du formulaire
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                
                // Afficher le loader
                document.getElementById('editModalBody').innerHTML = `
                    <div class="flex justify-center items-center p-8">
                        <div class="spinner-border text-[#255156]" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                `;
                
                // Charger le formulaire
                fetch(`/structures/${id}/edit`)
                    .then(res => {
                        if (!res.ok) throw new Error('Erreur réseau');
                        return res.text();
                    })
                    .then(html => {
                        document.getElementById('editModalBody').innerHTML = html;
                    })
                    .catch(err => {
                        console.error(err);
                        document.getElementById('editModalBody').innerHTML = `
                            <div class="text-center p-8 text-red-600">
                                <i class="fas fa-exclamation-circle text-4xl mb-4"></i>
                                <p>Erreur lors du chargement du formulaire.</p>
                            </div>
                        `;
                    });
            });
        });

        // 🟢 DETAILS MODAL - Remplissage des données AVEC LOGO
        const viewDetailsButtons = document.querySelectorAll('.view-details-btn');
        viewDetailsButtons.forEach(btn => {
            btn.addEventListener('click', function(){
                try {
                    const structure = JSON.parse(this.getAttribute('data-structure'));
                    
                    // 🟢 GESTION DU LOGO DANS LA MODAL
                    const modalLogoImg = document.getElementById('modal-logo-img');
                    const modalLogoPlaceholder = document.getElementById('modal-logo-placeholder');
                    
                    if (structure.logo) {
                        // Afficher l'image
                        modalLogoImg.src = '{{ asset('storage') }}/' + structure.logo;
                        modalLogoImg.classList.remove('hidden');
                        modalLogoPlaceholder.classList.add('hidden');
                    } else {
                        // Afficher le placeholder
                        modalLogoImg.classList.add('hidden');
                        modalLogoPlaceholder.classList.remove('hidden');
                    }
                    
                    // Remplir la modal avec les données
                    document.getElementById('modal-organisme').textContent = structure.organisme || '-';
                    document.getElementById('modal-organisme-text').textContent = structure.organisme || '-';
                    
                    // Description
                    const descriptionElement = document.getElementById('modal-description');
                    if (structure.description && structure.description.trim() !== '') {
                        descriptionElement.textContent = structure.description;
                        descriptionElement.classList.remove('text-gray-400');
                    } else {
                        descriptionElement.textContent = 'Aucune description disponible';
                        descriptionElement.classList.add('text-gray-400');
                    }
                    
                    // Informations principales
                    document.getElementById('modal-categories').textContent = structure.categories || 'Non spécifié';
                    document.getElementById('modal-type_structure').textContent = structure.type_structure || 'Non spécifié';
                    document.getElementById('modal-public_cible').textContent = structure.public_cible || 'Non spécifié';
                    document.getElementById('modal-zone').textContent = structure.zone || 'Non spécifié';
                    
                    // Site web
                    const siteElement = document.getElementById('modal-site');
                    if (structure.site && structure.site.trim() !== '') {
                        siteElement.innerHTML = `<a href="${structure.site}" target="_blank" 
                                               class="text-[#255156] hover:text-[#8bbdc3] font-medium underline transition-colors">
                            <i class="fas fa-external-link-alt mr-1"></i>${structure.site}
                        </a>`;
                    } else {
                        siteElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
                    }
                    
                    // Localisation - Siège social
                    document.getElementById('modal-siege_ville').textContent = structure.siege_ville || 'Non spécifié';
                    document.getElementById('modal-siege_adresse').textContent = structure.siege_adresse || 'Non spécifié';
                    
                    // Localisation - Antenne locale
                    document.getElementById('modal-ville').textContent = structure.ville || 'Non spécifié';
                    document.getElementById('modal-code_postal').textContent = structure.code_postal || 'Non spécifié';
                    
                    const adresseElement = document.getElementById('modal-adresse');
                    if (structure.adresse && structure.adresse.trim() !== '') {
                        adresseElement.textContent = structure.adresse;
                    } else {
                        adresseElement.innerHTML = '<span class="text-gray-400 italic">Non spécifiée</span>';
                    }
                    
                    // Contact
                    const telephoneElement = document.getElementById('modal-telephone');
                    if (structure.telephone && structure.telephone.trim() !== '') {
                        telephoneElement.innerHTML = `<a href="tel:${structure.telephone}" 
                                                       class="text-[#255156] hover:text-[#8bbdc3] font-medium">
                            <i class="fas fa-phone mr-1"></i>${structure.telephone}
                        </a>`;
                    } else {
                        telephoneElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
                    }
                    
                    const emailElement = document.getElementById('modal-email');
                    if (structure.email && structure.email.trim() !== '') {
                        emailElement.innerHTML = `<a href="mailto:${structure.email}" 
                                                   class="text-[#255156] hover:text-[#8bbdc3] font-medium">
                            <i class="fas fa-envelope mr-1"></i>${structure.email}
                        </a>`;
                    } else {
                        emailElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
                    }
                    
                    const contactElement = document.getElementById('modal-contact');
                    if (structure.contact && structure.contact.trim() !== '') {
                        contactElement.textContent = structure.contact;
                    } else {
                        contactElement.innerHTML = '<span class="text-gray-400 italic">Non spécifié</span>';
                    }
                    
                    // Informations complémentaires
                    document.getElementById('modal-hebergement').textContent = structure.hebergement || 'Non spécifié';
                    document.getElementById('modal-details').textContent = structure.details || 'Aucun détail spécifique';
                    document.getElementById('modal-created_at').textContent = structure.created_at ? new Date(structure.created_at).toLocaleDateString('fr-FR') : '-';
                    
                } catch (e) {
                    console.error('Erreur parsing JSON:', e);
                }
            });
        });
        
        // Initialiser le compteur
        const totalRows = document.querySelectorAll('.structure-row').length;
        updateResultCount(totalRows, totalRows);
    });
</script>
@endsection