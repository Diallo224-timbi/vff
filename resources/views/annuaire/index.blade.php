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

    <!-- Messages d'erreur -->
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

    <!-- Messages de validation -->
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

    <!-- En-tête fixe -->
    <div class="sticky top-0 z-40 bg-gray-50 pt-4 pb-2 shadow-sm" style="margin-top: -1px;">
        <div class="mb-2 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#255156] mb-2"><i class="bx bx-building"></i>  Annuaire des structures</h1>
                <small class="text-gray-600 "><i class="bx bx-info-circle"></i>Gestion centralisée des structures et organismes</small>
            </div>
            
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
        
        <!-- Barre d'actions -->
        <div class="flex flex-wrap items-center justify-between mb-2 p-2 bg-white rounded-xl shadow-lg">
             @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur')
            <div class="flex flex-wrap items-center gap-3">
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
            </div>
            @endif  
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('structures.map') }}" 
                   class="border-2 border-[#255156] text-[#255156] px-4 py-2 rounded-lg hover:bg-[#255156] hover:text-white transition-colors flex items-center gap-2">
                    <i class="fas fa-map-marked-alt"></i>
                    Voir la carte
                </a>
                
                <a href="{{ route('annuaire.list') }}" 
                   class="border-2 border-[#255156] text-[#255156] px-4 py-2 rounded-lg hover:bg-[#255156] hover:text-white transition-colors flex items-center gap-2">
                    <i class="fas fa-list"></i>
                    Voir fiche structure
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

        <!-- Section recherche et filtres -->
        <div class="bg-white rounded-xl shadow-lg p-2">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Recherche globale</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" 
                               placeholder="Rechercher par nom, ville, catégorie..."
                               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Organisme</label>
                    <div class="relative">
                        <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select id="filterOrganisme" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                            <option value="">Tous les organismes</option>
                            @php
                                $organismes = $structures->pluck('organisme')->unique()->filter()->sort();
                            @endphp
                            @foreach($organismes as $organisme)
                                <option value="{{ $organisme }}">{{ $organisme }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
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
    
    <!-- Affichage en accordéon par organisme -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-4">
        <div class="overflow-y-auto max-h-[calc(100vh-320px)] sm:max-h-[calc(100vh-250px)]">
            <div id="accordionContainer" class="divide-y divide-gray-200">
                @php
                    // Regroupement des structures par organisme
                    $groupedStructures = $structures->groupBy('organisme');
                @endphp
                
                @forelse($groupedStructures as $organisme => $organismeStructures)
                    <div class="organisme-group" data-organisme="{{ $organisme }}">
                        <!-- En-tête de l'organisme (dépliable) -->
                        <div class="organisme-header bg-gradient-to-r from-gray-50 to-gray-100 hover:bg-gray-100 cursor-pointer transition-colors duration-200"
                             data-organisme-name="{{ $organisme }}">
                            <div class="px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-chevron-right text-gray-400 transition-transform duration-200 organisme-chevron"></i>
                                    </div>
                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-[#255156] to-[#8bbdc3] rounded-lg overflow-hidden flex items-center justify-center text-white">
                                        @if($organismeStructures->first()->logo)
                                            <img src="{{ asset('storage/' . $organismeStructures->first()->logo) }}" 
                                                 alt="Logo {{ $organisme }}"
                                                 class="w-full h-full object-cover"
                                                 onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-building text-white text-lg\'></i>';">
                                        @else
                                            <i class="fas fa-building text-white text-lg"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-[#255156]">{{ $organisme }}</h3>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-layer-group mr-1"></i>
                                            @if ($organismeStructures->count()>1)
                                                {{ $organismeStructures->count() }} antennes
                                            @else
                                                aucune antenne
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if(auth()->user()->role === 'admin')
                                        <button class="text-gray-400 hover:text-gray-600 p-1 edit-organisme-btn" 
                                                data-organisme="{{ $organisme }}"
                                                title="Modifier l'organisme">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Corps de l'organisme (contenu dépliable) -->
                        <div class="organisme-body hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Siège Ville</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Catégories</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Site web</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Public Cible</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Zone</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Type</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Ville</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($organismeStructures as $structure)
                                            <tr class="structure-row hover:bg-gray-50 transition-colors duration-150" 
                                                data-id="{{ $structure->id }}"
                                                data-organisme="{{ $structure->organisme ?? '' }}"
                                                data-city="{{ $structure->ville ?? '' }}"
                                                data-category="{{ $structure->categories ?? '' }}">
                                                
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[120px]" title="{{ $structure->siege_ville ?? '' }}">
                                                        {{ $structure->siege_ville ?? '-' }}
                                                    </div>
                                                </td>
                                                
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[120px]" title="{{ $structure->categories ?? '' }}">
                                                        {{ $structure->categories ?? '-' }}
                                                    </div>
                                                </td>
                                                
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
                                                
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[120px]" title="{{ $structure->public_cible ?? '' }}">
                                                        {{ $structure->public_cible ?? '-' }}
                                                    </div>
                                                </td>
                                                
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[100px]" title="{{ $structure->zone ?? '' }}">
                                                        {{ $structure->zone ?? '-' }}
                                                    </div>
                                                </td>
                                                
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[100px]" title="{{ $structure->type_structure ?? '' }}">
                                                        {{ $structure->type_structure ?? '-' }}
                                                    </div>
                                                </td>
                                                
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[100px]" title="{{ $structure->ville ?? '' }}">
                                                        {{ $structure->ville ?? '-' }}
                                                    </div>
                                                </td>
                                                
                                                <td class="px-3 py-2">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <button class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors view-details-btn" 
                                                                title="Voir les détails"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#detailsModal"
                                                                data-structure='@json($structure)'>
                                                            <i class="fas fa-eye text-xs"></i>
                                                        </button>
                                                        
                                                        @if(auth()->user()->role === 'admin')
                                                            <a href="{{ route('structures.edit', $structure) }}" 
                                                               class="p-2 bg-yellow-400 text-yellow-800 rounded-lg hover:bg-yellow-500 transition-colors inline-flex items-center justify-center"
                                                               title="Modifier">
                                                                <i class="fas fa-edit text-xs"></i>
                                                            </a>
                                                            <form action="{{ route('structures.destroy', $structure) }}" 
                                                                  method="POST" 
                                                                  class="inline"
                                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette structure ?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="p-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" type="submit" title="Supprimer">
                                                                    <i class="fas fa-trash text-xs"></i>
                                                                </button>
                                                            </form>
                                                        @elseif(auth()->user()->role === 'moderateur' && isset(auth()->user()->id_structure) && auth()->user()->id_structure === $structure->id)
                                                            <a href="{{ route('structures.edit', $structure) }}" 
                                                               class="p-2 bg-yellow-400 text-yellow-800 rounded-lg hover:bg-yellow-500 transition-colors inline-flex items-center justify-center"
                                                               title="Modifier ma structure">
                                                                <i class="fas fa-edit text-xs"></i>
                                                            </a>
                                                            <form action="{{ route('structures.destroy', $structure) }}" 
                                                                  method="POST" 
                                                                  class="inline"
                                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer VOTRE structure ?')">
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
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i class="fas fa-building text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Aucune structure trouvée</p>
                        <p class="text-sm text-gray-400 mt-2">Essayez de modifier vos critères de recherche</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        {{ $structures->links() }}
    </div>
</div>

<!-- MODAL AJOUT -->
@if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur')
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

<!-- MODAL DETAILS (identique à l'original) -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl overflow-hidden">
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

            <div class="modal-body bg-gray-50 p-4 max-h-[70vh] overflow-y-auto">
                <!-- Informations principales (2 colonnes) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
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
                    
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <h6 class="text-[#255156] font-semibold mb-2 text-sm flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-xs"></i> 
                            <span>Localisation</span>
                        </h6>      
                        
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

                <div class="mt-3 text-xs text-gray-500 text-right">
                    <i class="fas fa-clock mr-1"></i>
                    Dernière mise à jour: <span id="modal-created_at">-</span>
                </div>
            </div>

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
    :root {
        --primary-color: #255156;
        --secondary-color: #8bbdc3;
    }
    
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
    
    .notification-toast {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        border-left: 4px solid rgba(255, 255, 255, 0.5);
        z-index: 9999;
    }
    
    .sticky {
        position: sticky;
        top: 0;
        z-index: 40;
        background-color: #f9fafb;
    }
    
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
    
    .structure-row {
        transition: background-color 0.2s ease;
    }
    
    .structure-row:hover {
        background-color: #f9fafb;
    }
    
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
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
    
    .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }
    
    .btn-close:hover {
        opacity: 1;
    }
    
    #resultCount {
        transition: all 0.3s ease;
    }
    
    /* Animation pour l'accordéon */
    .organisme-body {
        transition: all 0.3s ease-in-out;
    }
    
    .organisme-body:not(.hidden) {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .organisme-header:hover {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    }
    
    .organisme-chevron {
        transition: transform 0.2s ease;
    }
    
    .organisme-header.active .organisme-chevron {
        transform: rotate(90deg);
    }
</style>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Ajout de Bootstrap JS et dépendances -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Gestion de l'accordéon
    function initAccordion() {
        const headers = document.querySelectorAll('.organisme-header');
        
        headers.forEach(header => {
            header.addEventListener('click', function(e) {
                // Éviter que le clic sur les boutons d'édition ne déclenche l'accordéon
                if(e.target.closest('.edit-organisme-btn')) {
                    return;
                }
                
                const body = this.nextElementSibling;
                const isActive = this.classList.contains('active');
                
                // Fermer tous les autres (optionnel - commenter pour garder multiples ouverts)
                headers.forEach(h => {
                    if(h !== header && h.classList.contains('active')) {
                        h.classList.remove('active');
                        h.nextElementSibling.classList.add('hidden');
                    }
                });
                
                // Toggle l'état actuel
                if(isActive) {
                    this.classList.remove('active');
                    body.classList.add('hidden');
                } else {
                    this.classList.add('active');
                    body.classList.remove('hidden');
                }
            });
        });
    }
    
    // Fonction de filtrage
    function filterAndSearch() {
        const searchQuery = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const organismeFilter = document.getElementById('filterOrganisme')?.value.toLowerCase() || '';
        const cityFilter = document.getElementById('filterCity')?.value.toLowerCase() || '';
        
        const groups = document.querySelectorAll('.organisme-group');
        let totalVisible = 0;
        
        groups.forEach(group => {
            const organismeName = (group.dataset.organisme || '').toLowerCase();
            const rows = group.querySelectorAll('.structure-row');
            let groupVisible = false;
            
            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                const rowCity = (row.dataset.city || '').toLowerCase();
                const rowOrganisme = (row.dataset.organisme || '').toLowerCase();
                
                const matchesSearch = searchQuery === '' || rowText.includes(searchQuery);
                const matchesOrganisme = organismeFilter === '' || rowOrganisme.includes(organismeFilter);
                const matchesCity = cityFilter === '' || rowCity.includes(cityFilter);
                
                const isVisible = matchesSearch && matchesOrganisme && matchesCity;
                
                row.style.display = isVisible ? '' : 'none';
                if(isVisible) {
                    groupVisible = true;
                    totalVisible++;
                }
            });
            
            // Afficher/masquer le groupe entier si aucune structure visible
            group.style.display = groupVisible ? '' : 'none';
        });
        
        updateResultCount(totalVisible, document.querySelectorAll('.structure-row').length);
        
        // Vérifier s'il n'y a aucun résultat
        const noResultsMsg = document.getElementById('noResultsMessage');
        if(totalVisible === 0) {
            if(!noResultsMsg) {
                const container = document.getElementById('accordionContainer');
                const msg = document.createElement('div');
                msg.id = 'noResultsMessage';
                msg.className = 'text-center py-12';
                msg.innerHTML = `
                    <i class="fas fa-building text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Aucune structure trouvée</p>
                    <p class="text-sm text-gray-400 mt-2">Essayez de modifier vos critères de recherche</p>
                `;
                container.appendChild(msg);
            }
        } else if(noResultsMsg) {
            noResultsMsg.remove();
        }
    }
    
    function updateResultCount(visible, total) {
        const resultCount = document.getElementById('resultCount');
        const filteredCount = document.getElementById('filteredCount');
        const filteredNumber = document.getElementById('filteredNumber');
        
        if(filteredNumber) filteredNumber.textContent = visible;
        
        if(resultCount) {
            if(visible < total) {
                if(filteredCount) filteredCount.classList.remove('hidden');
                resultCount.textContent = `${visible} structures trouvées (sur ${total})`;
            } else {
                if(filteredCount) filteredCount.classList.add('hidden');
                resultCount.textContent = `${total} structures trouvées`;
            }
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser l'accordéon
        initAccordion();
        
        // Ouvrir le premier groupe par défaut
        const firstHeader = document.querySelector('.organisme-header');
        if(firstHeader) {
            firstHeader.classList.add('active');
            firstHeader.nextElementSibling?.classList.remove('hidden');
        }
        
        // Écouteurs de filtres
        const searchInput = document.getElementById('searchInput');
        if(searchInput) searchInput.addEventListener('input', filterAndSearch);
        
        const filterOrganisme = document.getElementById('filterOrganisme');
        if(filterOrganisme) filterOrganisme.addEventListener('change', filterAndSearch);
        
        const filterCity = document.getElementById('filterCity');
        if(filterCity) filterCity.addEventListener('change', filterAndSearch);
        
        // Réinitialisation des filtres
        const resetBtn = document.getElementById('resetFilters');
        if(resetBtn) {
            resetBtn.addEventListener('click', () => {
                if(searchInput) searchInput.value = '';
                if(filterOrganisme) filterOrganisme.value = '';
                if(filterCity) filterCity.value = '';
                filterAndSearch();
            });
        }
        
        // Modal détails (identique à l'original)
        const viewDetailsButtons = document.querySelectorAll('.view-details-btn');
        viewDetailsButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                try {
                    const structure = JSON.parse(this.getAttribute('data-structure'));
                    
                    const modalLogoImg = document.getElementById('modal-logo-img');
                    const modalLogoPlaceholder = document.getElementById('modal-logo-placeholder');
                    
                    if(structure.logo) {
                        modalLogoImg.src = '{{ asset('storage') }}/' + structure.logo;
                        modalLogoImg.classList.remove('hidden');
                        modalLogoPlaceholder.classList.add('hidden');
                    } else {
                        modalLogoImg.classList.add('hidden');
                        modalLogoPlaceholder.classList.remove('hidden');
                    }
                    
                    document.getElementById('modal-organisme').textContent = structure.organisme || '-';
                    document.getElementById('modal-organisme-text').textContent = structure.organisme || '-';
                    
                    const descriptionElement = document.getElementById('modal-description');
                    if(structure.description && structure.description.trim() !== '') {
                        descriptionElement.textContent = structure.description;
                        descriptionElement.classList.remove('text-gray-400', 'italic');
                    } else {
                        descriptionElement.textContent = 'Aucune description disponible';
                        descriptionElement.classList.add('text-gray-400', 'italic');
                    }
                    
                    document.getElementById('modal-categories').textContent = structure.categories || 'Non spécifié';
                    document.getElementById('modal-type_structure').textContent = structure.type_structure || 'Non spécifié';
                    document.getElementById('modal-public_cible').textContent = structure.public_cible || 'Non spécifié';
                    document.getElementById('modal-zone').textContent = structure.zone || 'Non spécifié';
                    
                    const siteElement = document.getElementById('modal-site');
                    if(structure.site && structure.site.trim() !== '') {
                        siteElement.innerHTML = `<a href="${structure.site}" target="_blank" class="text-[#255156] hover:text-[#8bbdc3] font-medium underline transition-colors"><i class="fas fa-external-link-alt mr-1"></i>${structure.site}</a>`;
                    } else {
                        siteElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
                    }
                    
                    document.getElementById('modal-siege_ville').textContent = structure.siege_ville || 'Non spécifié';
                    document.getElementById('modal-siege_adresse').textContent = structure.siege_adresse || 'Non spécifié';
                    document.getElementById('modal-ville').textContent = structure.ville || 'Non spécifié';
                    document.getElementById('modal-code_postal').textContent = structure.code_postal || 'Non spécifié';
                    
                    const adresseElement = document.getElementById('modal-adresse');
                    if(structure.adresse && structure.adresse.trim() !== '') {
                        adresseElement.textContent = structure.adresse;
                        adresseElement.classList.remove('text-gray-400', 'italic');
                    } else {
                        adresseElement.textContent = 'Non spécifiée';
                        adresseElement.classList.add('text-gray-400', 'italic');
                    }
                    
                    const telephoneElement = document.getElementById('modal-telephone');
                    if(structure.telephone && structure.telephone.trim() !== '') {
                        telephoneElement.innerHTML = `<a href="tel:${structure.telephone}" class="text-[#255156] hover:text-[#8bbdc3] font-medium"><i class="fas fa-phone mr-1"></i>${structure.telephone}</a>`;
                    } else {
                        telephoneElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
                    }
                    
                    const emailElement = document.getElementById('modal-email');
                    if(structure.email && structure.email.trim() !== '') {
                        emailElement.innerHTML = `<a href="mailto:${structure.email}" class="text-[#255156] hover:text-[#8bbdc3] font-medium"><i class="fas fa-envelope mr-1"></i>${structure.email}</a>`;
                    } else {
                        emailElement.innerHTML = '<span class="text-gray-400 italic">Non disponible</span>';
                    }
                    
                    const contactElement = document.getElementById('modal-contact');
                    if(structure.contact && structure.contact.trim() !== '') {
                        contactElement.textContent = structure.contact;
                        contactElement.classList.remove('text-gray-400', 'italic');
                    } else {
                        contactElement.textContent = 'Non spécifié';
                        contactElement.classList.add('text-gray-400', 'italic');
                    }
                    
                    document.getElementById('modal-hebergement').textContent = structure.hebergement || 'Non spécifié';
                    document.getElementById('modal-details').textContent = structure.details || 'Aucun détail spécifique';
                    
                    const dateElement = document.getElementById('modal-created_at');
                    if(structure.created_at) {
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
                    
                } catch(e) {
                    console.error('Erreur parsing JSON:', e);
                }
            });
        });
        
        // Compteur initial
        const totalRows = document.querySelectorAll('.structure-row').length;
        updateResultCount(totalRows, totalRows);
    });
</script>
@endsection