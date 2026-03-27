@extends('base')
@section('title', 'Annuaire des structures')

@section('content')
<div class="container mx-auto px-0 py-0">
    <!-- Messages de succès - Style espace documentaire -->
    <div class="container mx-auto px-1 py-1">
    <!-- Messages de succès  -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg" 
             role="alert" 
             style="z-index: 9999; min-width: 300px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        
        <!-- Script pour auto-fermeture après 5 secondes -->
        <script>
            setTimeout(function() {
                const alert = document.querySelector('.alert-success');
                if (alert) {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        </script>
    @endif

    <!-- Messages d'erreur - Version Bootstrap -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg" 
             role="alert" 
             style="z-index: 9999; min-width: 300px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        
        <script>
            setTimeout(function() {
                const alert = document.querySelector('.alert-danger');
                if (alert) {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        </script>
    @endif

    <!-- Messages de validation (erreurs de formulaire) -->
    @if($errors->any())
        <div class="position-fixed top-0 end-0 m-3" style="z-index: 9999; max-width: 400px;">
            @foreach($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show shadow-lg mb-2" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>{{ $error }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endforeach
        </div>
        
        <script>
            setTimeout(function() {
                document.querySelectorAll('.alert-danger').forEach(alert => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 300);
                });
            }, 8000);
        </script>
    @endif

    <!-- En-tête fixe - Style espace documentaire -->
    <div class="sticky top-0 z-40 bg-gray-50 pt-4 pb-2 shadow-sm" style="margin-top: -1px;">
        <!-- En-tête et titre -->
        <div class="mb-2 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#255156] mb-2"><i class="bx bx-building"></i>  Annuaire des structures</h1>
                <small class="text-gray-600 "><i class="bx bx-info-circle"></i>Gestion centralisée des structures et organismes</small>
            </div>
            
            <!-- LÉGENDE DES ACTIONS - Style espace documentaire -->
            <div class="flex items-center gap-4 bg-gray-100 px-4 py-2 rounded-lg">
                <div class="flex items-center gap-1">
                    <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs">
                        <i class="fas fa-eye"></i>
                    </span>
                    <span class="text-xs text-gray-600">Voir détails</span>
                </div>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur')
                    <div class="flex items-center gap-1">
                        <span class="w-6 h-6 bg-yellow-200 text-yellow-600 rounded-lg flex items-center justify-center text-xs">
                            <i class="fas fa-edit"></i>
                        </span>
                        <span class="text-xs text-gray-600">Modifier</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-6 h-6 bg-red-600 text-white rounded-lg flex items-center justify-center text-xs">
                            <i class="fas fa-trash"></i>
                        </span>
                        <span class="text-xs text-gray-600">Supprimer</span>
                    </div>
                @endif
            </div>
        </div>
        <!-- Barre d'actions - Style espace documentaire -->
        <div class="flex flex-wrap items-center justify-between mb-2 p-2 bg-white rounded-xl shadow-lg">
            <div class="flex flex-wrap items-center gap-3">
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur')
                    <button class="bg-[#255156] text-white px-4 py-2 rounded-lg hover:bg-[#1d4144] transition-colors flex items-center gap-2" 
                            data-bs-toggle="modal" 
                            data-bs-target="#addModal">
                        <i class="fas fa-plus-circle"></i>
                        Ajouter une structure
                    </button>

                    <a href="{{ route('annuaire.export.pdf') }}" 
                       class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg hover:from-red-600 hover:to-red-700 transition-colors flex items-center gap-2">
                        <i class="fas fa-file-pdf"></i>
                        Exporter PDF
                    </a>
                @endif
            </div>  
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('structures.map') }}" 
                   class="border-2 border-[#255156] text-[#255156] px-4 py-2 rounded-lg hover:bg-[#255156] hover:text-white transition-colors flex items-center gap-2">
                    <i class="fas fa-map-marked-alt"></i>
                    Voir la carte
                </a>
                
                <a href="{{ route('annuaire.list') }}" 
                   class="border-2 border-[#255156] text-[#255156] px-4 py-2 rounded-lg hover:bg-[#255156] hover:text-white transition-colors flex items-center gap-2">
                    <i class="fas fa-list"></i>
                    Voir fiche strucuture
                </a>
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

        <!-- Section recherche et filtres - Style espace documentaire -->
        <div class="bg-white rounded-xl shadow-lg p-2">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                <!-- Recherche dynamique -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Recherche globale</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" 
                               placeholder="Rechercher par nom, ville, catégorie..."
                               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                    </div>
                </div>
                
                <!-- Filtre par catégorie -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Structures</label>
                    <div class="relative">
                        <i class="fas fa-tags absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select id="filterCategory" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                            <option value="">Toutes les structures</option>
                            @php
                                $organismes = $structures->pluck('organisme')->unique()->filter()->sort();
                            @endphp
                            @foreach($organismes as $organisme)
                                <option value="{{ $organisme }}">{{ $organisme }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Filtre par ville -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ville</label>
                    <div class="relative">
                        <i class="fas fa-city absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select id="filterCity" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
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
            <!--
            <div class="mb-2">
                <button id="toggleAdvancedFilters" 
                        class="text-[#255156] hover:text-[#8bbdc3] font-semibold text-sm flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 hover:border-[#8bbdc3] transition-all w-full">
                    <i class="fas fa-sliders-h"></i>
                    <span>Filtres avancés</span>
                    <i class="fas fa-chevron-down ml-auto transition-transform" id="filterArrow"></i>
                </button>
                
                <div id="advancedFilters" class="hidden mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                      Filtre par type de structure
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Type de structure</label>
                            <div class="relative">
                                <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select id="filterType" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
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
                        
                     Filtre par zone
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Zone géographique</label>
                            <div class="relative">
                                <i class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select id="filterZone" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
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
                        
                       Filtre par public cible 
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Public cible</label>
                            <div class="relative">
                                <i class="fas fa-users absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select id="filterPublic" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
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
                -->  
                    <!-- Boutons de réinitialisation -->
                    <div class="mt-4 flex justify-end">
                        <button id="resetFilters" 
                                class="flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-undo"></i>
                            Réinitialiser tous les filtres
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tableau d'affichage avec visibilité mobile -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden max-h-[calc(100vh-280px)] sm:max-h-[calc(100vh-200px)]">
    <div class="overflow-y-auto max-h-[calc(100vh-280px)] sm:max-h-[calc(100vh-200px)]">
            <table class="w-full" id="structuresTable">
                <thead class="bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white sticky top-0 z-30 shadow-md">
                     <tr>
                        <th class="px-3 py-3 text-left text-sm font-semibold">Organisme</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold">Siège Ville</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold">Catégories</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold">Site web</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold">Public Cible</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold">Zone</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold">Type</th>
                        <th class="px-3 py-3 text-left text-sm font-semibold">Ville</th>
                        <th class="px-3 py-3 text-center text-sm font-semibold">Actions</th>
                     </tr>
                </thead>
                <tbody id="structuresTableBody" class="divide-y divide-gray-200">
                    @forelse($structures as $structure)
                        <tr class="structure-row hover:bg-gray-50 transition-colors duration-150" 
                            data-id="{{ $structure->id }}"
                            data-category="{{ $structure->organisme ?? '' }}"
                            data-city="{{ $structure->ville ?? '' }}"
                            data-type="{{ $structure->type_structure ?? '' }}"
                            data-zone="{{ $structure->zone ?? '' }}"
                            data-public="{{ $structure->public_cible ?? '' }}">
                            <!-- ORGANISME AVEC LOGO -->
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
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
                                    <div class="font-medium text-gray-800 text-sm truncate max-w-[150px]" title="{{ $structure->organisme }}">
                                        {{ $structure->organisme }}
                                    </div>
                                </div>
                             </td>
                            
                            <!-- Siège Ville -->
                            <td class="px-3 py-2">
                                <div class="text-gray-700 text-sm truncate max-w-[120px]" title="{{ $structure->siege_ville ?? '' }}">
                                    {{ $structure->siege_ville ?? '-' }}
                                </div>
                             </td>
                            
                            <!-- Catégories -->
                            <td class="px-3 py-2">
                                <div class="text-gray-700 text-sm truncate max-w-[120px]" title="{{ $structure->categories ?? '' }}">
                                    {{ $structure->categories ?? '-' }}
                                </div>
                             </td>
                            
                            <!-- Site web -->
                            <td class="px-3 py-2">
                                @if($structure->site)
                                    <a href="{{ $structure->site }}" target="_blank" 
                                       class="text-[#255156] hover:text-[#8bbdc3] text-sm font-medium truncate max-w-[120px] block"
                                       title="{{ $structure->site }}">
                                        {{ Str::limit($structure->site, 25) }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                             </td>
                            <!-- Public Cible -->
                            <td class="px-3 py-2">
                                <div class="text-gray-700 text-sm truncate max-w-[120px]" title="{{ $structure->public_cible ?? '' }}">
                                    {{ $structure->public_cible ?? '-' }}
                                </div>
                             </td>    
                            <!-- Zone -->
                            <td class="px-3 py-2">
                                <div class="text-gray-700 text-sm truncate max-w-[100px]" title="{{ $structure->zone ?? '' }}">
                                    {{ $structure->zone ?? '-' }}
                                </div>
                             </td>  
                            <!-- Type Structure -->
                            <td class="px-3 py-2">
                                <div class="text-gray-700 text-sm truncate max-w-[100px]" title="{{ $structure->type_structure ?? '' }}">
                                    {{ $structure->type_structure ?? '-' }}
                                </div>
                             </td> 
                            <!-- Ville -->
                            <td class="px-3 py-2">
                                <div class="text-gray-700 text-sm truncate max-w-[100px]" title="{{ $structure->ville ?? '' }}">
                                    {{ $structure->ville ?? '-' }}
                                </div>
                             </td>
                            <!-- Actions - Style espace documentaire -->
                            <td class="px-3 py-2">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Bouton Voir détails - TOUS les utilisateurs -->
                                    <button class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors view-details-btn" 
                                            title="Voir les détails"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailsModal"
                                            data-structure='@json($structure)'>
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <!-- ADMIN : Peut modifier et supprimer TOUTES les structures -->
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('structures.edit', $structure) }}" 
                                           class="p-2 bg-yellow-400 text-yellow-800 rounded-lg hover:bg-yellow-500 transition-colors inline-flex items-center justify-center"
                                           title="Modifier">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('structures.destroy', $structure) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette structure ? Attention, tous les utilisateurs rattachés seront aussi supprimés.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="p-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" type="submit" title="Supprimer">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    <!-- MODERATEUR : Peut modifier et supprimer UNIQUEMENT sa propre structure -->
                                    @elseif(auth()->user()->role === 'moderateur' && isset(auth()->user()->id_structure) && auth()->user()->id_structure === $structure->id)
                                        <a href="{{ route('structures.edit', $structure) }}" 
                                           class="p-2 bg-yellow-400 text-yellow-800 rounded-lg hover:bg-yellow-500 transition-colors inline-flex items-center justify-center"
                                           title="Modifier ma structure">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('structures.destroy', $structure) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer VOTRE structure ? Attention, tous les utilisateurs rattachés seront aussi supprimés.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="p-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" type="submit" title="Supprimer ma structure">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                             </td>
                         </tr>
                    @empty
                        <tr id="noResultsRow">
                            <td colspan="9" class="px-6 py-12 text-center">
                                <i class="fas fa-building text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Aucune structure trouvée</p>
                                <p class="text-sm text-gray-400 mt-2">Essayez de modifier vos critères de recherche</p>
                             </td>
                         </tr>
                    @endforelse
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
            <div class="bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white p-4 rounded-t-xl d-flex justify-content-between align-items-center">
                <h5 class="text-xl font-bold flex items-center gap-3">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter une structure
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="p-6 bg-gray-50">
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

<!-- MODAL DETAILS -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl overflow-hidden">
            <!-- Header avec logo -->
            <div class="bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white p-3 rounded-t-xl d-flex justify-content-between align-items-center">
                <div class="flex items-center gap-3">
                    <div id="modal-logo-container" class="bg-white/20 p-1 rounded-lg w-12 h-12 flex items-center justify-center">
                        <div id="modal-logo-placeholder">
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body avec toutes les informations -->
            <div class="modal-body bg-gray-50 p-4 max-h-[70vh] overflow-y-auto">
                
                <!-- Informations principales (2 colonnes) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    <!-- Colonne gauche - Infos générales -->
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
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
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
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

                <!-- Section Contact -->
                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm mb-4">
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

                <!-- Description -->
                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <h6 class="text-[#255156] font-semibold text-sm flex items-center gap-2">
                            <i class="fas fa-file-alt text-xs"></i> 
                            <span>Description</span>
                        </h6>
                        <span class="text-xs bg-[#255156]/10 text-[#255156] px-2 py-1 rounded-full font-medium">
                            <i class="fas fa-align-left mr-1"></i> Détails
                        </span>
                    </div>
                    <div class="text-sm text-gray-700 leading-relaxed p-2 bg-gray-50 rounded min-h-[60px]" id="modal-description">
                        -
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                            <i class="fas fa-bed text-xs"></i> 
                            <span>Hébergement</span>
                        </h6>
                        <div class="text-sm text-gray-700 min-h-[40px]" id="modal-hebergement">-</div>
                    </div>
                    
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                            <i class="fas fa-list-ul text-xs"></i> 
                            <span>Détails spécifiques</span>
                        </h6>
                        <div class="text-sm text-gray-700 min-h-[40px]" id="modal-details">-</div>
                    </div>
                </div>

                <!-- Date de mise à jour -->
                <div class="mt-3 text-xs text-gray-500 text-right">
                    <i class="fas fa-clock mr-1"></i>
                    Dernière mise à jour: <span id="modal-created_at">-</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-white p-3 border-t border-gray-200">
                <div class="flex justify-end gap-2 w-full">
                    <button type="button" 
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors flex items-center gap-2"
                            data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        Fermer
                    </button>
                    <button type="button" 
                            class="px-4 py-2 bg-[#255156] text-white rounded-lg hover:bg-[#1d4144] transition-colors flex items-center gap-2"
                            onclick="window.print()">
                        <i class="fas fa-print"></i>
                        Imprimer
                    </button>
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
    }
    
    /* Animation pour les notifications */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate-slide-in {
        animation: slideIn 0.3s ease-out forwards;
    }
    
    /* Style des notifications */
    .notification-toast {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        border-left: 4px solid rgba(255, 255, 255, 0.5);
        z-index: 9999;
    }
    
    /* Style pour l'en-tête fixe */
    .sticky {
        position: sticky;
        top: 0;
        z-index: 40;
        background-color: #f9fafb;
    }
    
    /* Style pour l'en-tête du tableau */
    thead.sticky {
        position: sticky;
        top: 0;
        z-index: 30;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    /* Scrollbar personnalisée */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, var(--secondary-color), var(--primary-color));
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
    }
    
    /* Styles pour les boutons d'action */
    .p-2 {
        transition: all 0.2s ease;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .p-2:hover {
        transform: scale(1.1);
    }
    
    /* Style pour les lignes du tableau */
    .structure-row {
        transition: background-color 0.2s ease;
    }
    
    .structure-row:hover {
        background-color: #f9fafb;
    }
    
    /* Style pour les cellules avec texte tronqué */
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Style pour les modales */
    .modal-content {
        animation: scaleIn 0.3s ease;
    }
    
    @keyframes scaleIn {
        from {
            transform: scale(0.95);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    /* Ajustements pour les modales Bootstrap */
    .modal-header, .bg-gradient-to-r {
        border-bottom: none;
    }
    
    .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }
    
    .btn-close:hover {
        opacity: 1;
    }
    
    /* Style pour le compteur */
    #resultCount {
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('scripts')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>

<script>
    // Variables globales
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
            
            const matchesSearch = searchQuery === '' || rowText.includes(searchQuery);
            const matchesCategory = categoryFilter === '' || rowCategory.includes(categoryFilter);
            const matchesCity = cityFilter === '' || rowCity.includes(cityFilter);
            const matchesType = typeFilter === '' || rowType.includes(typeFilter);
            const matchesZone = zoneFilter === '' || rowZone.includes(zoneFilter);
            const matchesPublic = publicFilter === '' || rowPublic.includes(publicFilter);
            
            const isVisible = matchesSearch && matchesCategory && matchesCity && 
                             matchesType && matchesZone && matchesPublic;
            
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });
        
        updateResultCount(visibleCount, rows.length);
        
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
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', filterAndSearch);
        }
        
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
                document.getElementById('searchInput').value = '';
                document.getElementById('filterCategory').value = '';
                document.getElementById('filterCity').value = '';
                document.getElementById('filterType').value = '';
                document.getElementById('filterZone').value = '';
                document.getElementById('filterPublic').value = '';
                filterAndSearch();
            });
        }

        // DETAILS MODAL - Remplissage complet des données
        const viewDetailsButtons = document.querySelectorAll('.view-details-btn');
        viewDetailsButtons.forEach(btn => {
            btn.addEventListener('click', function(){
                try {
                    const structure = JSON.parse(this.getAttribute('data-structure'));
                    
                    // Gestion du logo
                    const modalLogoImg = document.getElementById('modal-logo-img');
                    const modalLogoPlaceholder = document.getElementById('modal-logo-placeholder');
                    
                    if (structure.logo) {
                        modalLogoImg.src = '{{ asset('storage') }}/' + structure.logo;
                        modalLogoImg.classList.remove('hidden');
                        modalLogoPlaceholder.classList.add('hidden');
                    } else {
                        modalLogoImg.classList.add('hidden');
                        modalLogoPlaceholder.classList.remove('hidden');
                    }
                    
                    // Informations principales
                    document.getElementById('modal-organisme').textContent = structure.organisme || '-';
                    document.getElementById('modal-organisme-text').textContent = structure.organisme || '-';
                    
                    // Description
                    const descriptionElement = document.getElementById('modal-description');
                    if (structure.description && structure.description.trim() !== '') {
                        descriptionElement.textContent = structure.description;
                        descriptionElement.classList.remove('text-gray-400', 'italic');
                    } else {
                        descriptionElement.textContent = 'Aucune description disponible';
                        descriptionElement.classList.add('text-gray-400', 'italic');
                    }
                    
                    // Catégories et autres infos
                    document.getElementById('modal-categories').textContent = structure.categories || 'Non spécifié';
                    document.getElementById('modal-type_structure').textContent = structure.type_structure || 'Non spécifié';
                    document.getElementById('modal-public_cible').textContent = structure.public_cible || 'Non spécifié';
                    document.getElementById('modal-zone').textContent = structure.zone || 'Non spécifié';
                    
                    // Site web avec lien
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
                        adresseElement.classList.remove('text-gray-400', 'italic');
                    } else {
                        adresseElement.textContent = 'Non spécifiée';
                        adresseElement.classList.add('text-gray-400', 'italic');
                    }
                    
                    // Contact - Téléphone avec lien
                    const telephoneElement = document.getElementById('modal-telephone');
                    if (structure.telephone && structure.telephone.trim() !== '') {
                        telephoneElement.innerHTML = `<a href="tel:${structure.telephone}" 
                                                       class="text-[#255156] hover:text-[#8bbdc3] font-medium">
                            <i class="fas fa-phone mr-1"></i>${structure.telephone}
                        </a>`;
                    } else {
                        telephoneElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
                    }
                    
                    // Contact - Email avec lien
                    const emailElement = document.getElementById('modal-email');
                    if (structure.email && structure.email.trim() !== '') {
                        emailElement.innerHTML = `<a href="mailto:${structure.email}" 
                                                   class="text-[#255156] hover:text-[#8bbdc3] font-medium">
                            <i class="fas fa-envelope mr-1"></i>${structure.email}
                        </a>`;
                    } else {
                        emailElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
                    }
                    
                    // Contact - Personne de contact
                    const contactElement = document.getElementById('modal-contact');
                    if (structure.contact && structure.contact.trim() !== '') {
                        contactElement.textContent = structure.contact;
                        contactElement.classList.remove('text-gray-400', 'italic');
                    } else {
                        contactElement.textContent = 'Non spécifié';
                        contactElement.classList.add('text-gray-400', 'italic');
                    }
                    
                    // Informations complémentaires
                    document.getElementById('modal-hebergement').textContent = structure.hebergement || 'Non spécifié';
                    document.getElementById('modal-details').textContent = structure.details || 'Aucun détail spécifique';
                    
                    // Date de création/mise à jour
                    const dateElement = document.getElementById('modal-created_at');
                    if (structure.created_at) {
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
                    
                } catch (e) {
                    console.error('Erreur parsing JSON:', e);
                }
            });
        });
        
        // Ajuster la hauteur du tableau
        function adjustTableHeight() {
            const headerHeight = document.querySelector('.sticky.top-0')?.offsetHeight || 0;
            const tableContainer = document.querySelector('.bg-white.rounded-xl.shadow-lg.overflow-hidden');
            if (tableContainer) {
                tableContainer.style.maxHeight = `calc(100vh - ${headerHeight + 40}px)`;
            }
        }
        
        adjustTableHeight();
        window.addEventListener('resize', adjustTableHeight);
        
        // Initialiser le compteur
        const totalRows = document.querySelectorAll('.structure-row').length;
        updateResultCount(totalRows, totalRows);
    });
</script>
@endsection