@extends('base')
@section('title', 'Annuaire des structures')
@section('content')
<div class="container-fluid px-0 py-0">
    <!-- Messages de succès -->
    <div class="container-fluid px-1 py-1">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg" 
             role="alert" 
             style="z-index: 9999; min-width: 300px; border-left: 4px solid #4caf50;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2" style="color: #4caf50;"></i>
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
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg" 
             role="alert" 
             style="z-index: 9999; min-width: 300px; border-left: 4px solid #ef5350;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2" style="color: #ef5350;"></i>
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
    @if($errors->any())
        <div class="position-fixed top-0 end-0 m-3" style="z-index: 9999; max-width: 400px;">
            @foreach($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show shadow-lg mb-2" role="alert" style="border-left: 4px solid #ef5350;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2" style="color: #ef5350;"></i>
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
    <div class="sticky top-0 z-40 pt-4 pb-2 shadow-sm" style="background: linear-gradient(180deg, #E9F7F6 0%, #f5fafa 100%); margin-top: -1px;">
        <div class="mb-2 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold" style="color: #255156;"><i class="bx bx-building"></i> Annuaire des structures</h1>
                <small style="color: #4a7a7f;"><i class="bx bx-info-circle"></i>Accédez aux informations détaillées et réalisez les mises à jour autorisées selon vos droits d’accès.</small>
            </div>
        </div>       
        <!-- Barre d'actions -->
        <div class="flex flex-wrap items-center justify-between mb-2 p-3 rounded-xl" style="background: white; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #dceeec;">
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur')
            <div class="flex flex-wrap items-center gap-3">
                <button class="text-white px-4 py-2 rounded-lg transition-all flex items-center gap-2 font-medium" 
                        style="background: linear-gradient(135deg, #255156, #3a7378); box-shadow: 0 4px 12px rgba(37,81,86,0.25);"
                        data-bs-toggle="modal" 
                        data-bs-target="#addModal"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(37,81,86,0.35)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(37,81,86,0.25)';">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter une structure
                </button>
            </div>
            @endif  
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('structures.map') }}" 
                   class="px-4 py-2 rounded-lg transition-all flex items-center gap-2 font-medium"
                   style="border: 2px solid #255156; color: #255156;"
                   onmouseover="this.style.background='#255156'; this.style.color='white';"
                   onmouseout="this.style.background='transparent'; this.style.color='#255156';">
                    <i class="fas fa-map-marked-alt"></i>
                    Voir la carte
                </a>
                <a href="{{ route('annuaire.list') }}" 
                   class="px-4 py-2 rounded-lg transition-all flex items-center gap-2 font-medium"
                   style="border: 2px solid #255156; color: #255156;"
                   onmouseover="this.style.background='#255156'; this.style.color='white';"
                   onmouseout="this.style.background='transparent'; this.style.color='#255156';">
                    <i class="fas fa-list"></i>
                    Voir fiche structure
                </a>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="text-sm px-4 py-2 rounded-lg" style="background: #f8fcfc; color: #4a7a7f; border: 1px solid #dceeec;">
                    <i class="fas fa-info-circle mr-2" style="color: #255156;"></i>
                    <span id="resultCount">{{ $structures->total() }} structures</span>
                </div>
            </div>
        </div> 
        
        <!-- Section recherche et filtres -->
        <div class="rounded-xl p-3" style="background: white; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #dceeec;">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold mb-2" style="color: #255156;"><i class="fas fa-search mr-2"></i>Recherche globale</label>
                    <div class="relative">
                        <input type="text" id="searchInput" 
                               placeholder="Rechercher par nom, ville, catégorie..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm focus:outline-none focus:ring-2 transition-all"
                               style="border: 1px solid #dceeec; background: #f8fcfc; color: #255156;"
                               onfocus="this.style.borderColor='#2d6268'; this.style.boxShadow='0 0 0 4px rgba(45,98,104,0.08)';"
                               onblur="this.style.borderColor='#dceeec'; this.style.boxShadow='none';">
                        <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #7fa8ac;"></i>
                    </div>
                </div>   
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #255156;"><i class="fas fa-building mr-2"></i>Organisme</label>
                    <div class="relative">
                        <select id="filterOrganisme" 
                                class="w-full pl-10 pr-10 py-2.5 rounded-lg text-sm focus:outline-none focus:ring-2 transition-all appearance-none cursor-pointer"
                                style="border: 1px solid #dceeec; background: #f8fcfc; color: #255156;"
                                onfocus="this.style.borderColor='#2d6268'; this.style.boxShadow='0 0 0 4px rgba(45,98,104,0.08)';"
                                onblur="this.style.borderColor='#dceeec'; this.style.boxShadow='none';">
                            <option value="">Tous les organismes</option>
                            @php
                                $organismesList = $structures->pluck('organisme.nom_organisme')->unique()->filter()->sort();
                            @endphp
                            @foreach($organismesList as $organismeItem)
                                <option value="{{ $organismeItem }}">{{ $organismeItem }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-building" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #7fa8ac;"></i>
                        <i class="fas fa-chevron-down" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #7fa8ac; font-size: 0.7rem; pointer-events: none;"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #255156;"><i class="fas fa-city mr-2"></i>Ville</label>
                    <div class="relative">
                        <select id="filterCity" 
                                class="w-full pl-10 pr-10 py-2.5 rounded-lg text-sm focus:outline-none focus:ring-2 transition-all appearance-none cursor-pointer"
                                style="border: 1px solid #dceeec; background: #f8fcfc; color: #255156;"
                                onfocus="this.style.borderColor='#2d6268'; this.style.boxShadow='0 0 0 4px rgba(45,98,104,0.08)';"
                                onblur="this.style.borderColor='#dceeec'; this.style.boxShadow='none';">
                            <option value="">Toutes les villes</option>
                            @php
                                $villes = $structures->pluck('organisme.ville')->unique()->filter()->sort();
                            @endphp
                            @foreach($villes as $ville)
                                <option value="{{ $ville }}">{{ $ville }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-city" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #7fa8ac;"></i>
                        <i class="fas fa-chevron-down" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #7fa8ac; font-size: 0.7rem; pointer-events: none;"></i>
                    </div>
                </div>
            </div>     
        </div>
    </div>   
    
    <!-- Affichage en accordéon par organisme -->
    <div class="rounded-xl shadow-lg overflow-hidden mt-4" style="background: white; border: 1px solid #dceeec;">
        <div class="overflow-y-auto max-h-[calc(100vh-320px)] sm:max-h-[calc(100vh-250px)]">
            <div id="accordionContainer" class="divide-y divide-gray-200">
                @php
                    $groupedStructures = $structures->groupBy(function($item) {
                        return $item->organisme->nom_organisme ?? 'Sans organisme';
                    });
                @endphp    
                @forelse($groupedStructures as $organismeNom => $structuresByOrganisme)
                    <div class="organisme-group" data-organisme="{{ $organismeNom }}">
                        <!-- En-tête de l'organisme -->
                        <div class="organisme-header cursor-pointer transition-colors duration-200"
                             style="background: linear-gradient(90deg, #f8fcfc, #f0f6f5);"
                             onmouseover="this.style.background='linear-gradient(90deg, #f0f6f5, #e8f3f2)';"
                             onmouseout="this.style.background='linear-gradient(90deg, #f8fcfc, #f0f6f5)';">
                            <div class="px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-chevron-right text-gray-400 transition-transform duration-200 organisme-chevron"></i>
                                    </div>
                                    <!-- LOGO AVEC OBJECT-FIT POUR BIEN TENIR DANS LA CASE -->
                                    <div class="flex-shrink-0 w-12 h-12 rounded-xl overflow-hidden flex items-center justify-center font-bold text-white"
                                         style="background: linear-gradient(135deg, #255156, #4a8599); box-shadow: 0 2px 8px rgba(37,81,86,0.2); font-size: 1.1rem; min-width: 3rem; min-height: 3rem;">
                                        @php
                                            $firstStructure = $structuresByOrganisme->first();
                                            $logoPath = $firstStructure && $firstStructure->organisme ? $firstStructure->organisme->logo_path : null;
                                            $nom = $organismeNom;
                                            $initiales = '';
                                            $mots = explode(' ', $nom);
                                            foreach($mots as $mot) {
                                                if(!empty($mot) && strlen($mot) > 0) {
                                                    $initiales .= strtoupper(substr($mot, 0, 1));
                                                }
                                                if(strlen($initiales) >= 2) break;
                                            }
                                            if(empty($initiales)) $initiales = 'S';
                                        @endphp
                                        
                                        @if($logoPath && file_exists(storage_path('app/public/' . $logoPath)))
                                            <img src="{{ asset('storage/' . $logoPath) }}" 
                                                 alt="Logo {{ $organismeNom }}"
                                                 class="w-full h-full object-cover object-center"
                                                 style="width: 100%; height: 100%; object-fit: cover; object-position: center;"
                                                 onerror="this.style.display='none'; this.parentElement.textContent='{{ $initiales }}'; this.parentElement.style.fontSize='1.1rem';">
                                        @else
                                            {{ $initiales }}
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold" style="color: #255156;">{{ $organismeNom }}</h3>
                                        <p class="text-sm" style="color: #4a7a7f;">
                                            <i class="fas fa-layer-group mr-1"></i>
                                            {{ $structuresByOrganisme->count() }} structure(s)
                                        </p>
                                    </div>
                                    @if($firstStructure->organisme && $firstStructure->organisme->adresse)
                                        <div class="text-sm" style="color: #4a7a7f;">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ $firstStructure->organisme->adresse }} - {{ $firstStructure->organisme->code_postal ?? '' }} {{ $firstStructure->organisme->ville ?? '' }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Corps de l'organisme -->
                        <div class="organisme-body hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead style="background: #255156; color: white;">
                                        <tr>
                                            <th class="px-3 py-2.5 text-left text-xs font-semibold">Ville</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-semibold">Code Postal</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-semibold">Adresse</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-semibold">Description</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-semibold">Site Web</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-semibold">Catégories</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-semibold">Public cible</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-semibold">Zone</th>
                                            <th class="px-3 py-2.5 text-center text-xs font-semibold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($structuresByOrganisme as $structure)
                                            <tr class="structure-row hover:bg-gray-50 transition-colors duration-150" 
                                                data-id="{{ $structure->id }}"
                                                data-organisme="{{ $structure->organisme->nom_organisme ?? '' }}"
                                                data-city="{{ $structure->organisme->ville ?? '' }}"
                                                data-category="{{ $structure->categories ?? '' }}">
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[120px]" title="{{ $structure->ville ?? '' }}">
                                                        {{ $structure->ville ?? '-' }}
                                                    </div>
                                                </td>  
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[100px]" title="{{ $structure->code_postal ?? '' }}">
                                                        {{ $structure->code_postal ?? '-' }}
                                                    </div>
                                                </td> 
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[150px]" title="{{ $structure->adresse ?? '' }}">
                                                        {{ $structure->adresse ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[200px]" title="{{ $structure->description ?? '' }}">
                                                        {{ $structure->description ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-3 py-2">
                                                    @if($structure->organisme && $structure->organisme->site_web)
                                                        <a href="{{ $structure->organisme->site_web }}" target="_blank" 
                                                           class="hover:underline text-sm font-medium truncate max-w-[120px] block"
                                                           style="color: #255156;"
                                                           title="{{ $structure->organisme->site_web }}">
                                                            {{ Str::limit($structure->organisme->site_web, 25) }}
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400 text-sm">-</span>
                                                    @endif
                                                </td>    
                                                <td class="px-3 py-2">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium" 
                                                          style="background: #e8f3f2; color: #255156;">
                                                        {{ $structure->categories ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[120px]" title="{{ $structure->public_cible ?? '' }}">
                                                        {{ $structure->public_cible ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-3 py-2">
                                                    <div class="text-gray-700 text-sm truncate max-w-[120px]" title="{{ $structure->zone ?? '' }}">
                                                        {{ $structure->zone ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-3 py-2">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <button class="p-2 rounded-lg transition-colors view-details-btn" 
                                                                style="background: #e8f3f2; color: #255156;"
                                                                title="Voir les détails"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#detailsModal"
                                                                data-structure='@json($structure)'
                                                                onmouseover="this.style.background='#255156'; this.style.color='white';"
                                                                onmouseout="this.style.background='#e8f3f2'; this.style.color='#255156';">
                                                            <i class="fas fa-eye text-xs"></i>
                                                        </button>
                                                        @if(auth()->user()->role === 'admin')
                                                            <a href="{{ route('structures.edit', $structure) }}" 
                                                               class="p-2 rounded-lg transition-colors inline-flex items-center justify-center"
                                                               style="background: #fff3cd; color: #856404;"
                                                               title="Modifier"
                                                               onmouseover="this.style.background='#ffc107'; this.style.color='white';"
                                                               onmouseout="this.style.background='#fff3cd'; this.style.color='#856404';">
                                                                <i class="fas fa-edit text-xs"></i>
                                                            </a>
                                                            <form action="{{ route('structures.destroy', $structure) }}" 
                                                                  method="POST" 
                                                                  class="inline"
                                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette structure ?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="p-2 rounded-lg transition-colors" 
                                                                        style="background: #ffebee; color: #c62828;"
                                                                        type="submit" 
                                                                        title="Supprimer"
                                                                        onmouseover="this.style.background='#ef5350'; this.style.color='white';"
                                                                        onmouseout="this.style.background='#ffebee'; this.style.color='#c62828';">
                                                                    <i class="fas fa-trash text-xs"></i>
                                                                </button>
                                                            </form>
                                                        @elseif(str_contains(auth()->user()->role, 'moderateur') && isset(auth()->user()->id_structure) && auth()->user()->id_structure === $structure->id)
                                                            <a href="{{ route('structures.edit', $structure) }}" 
                                                               class="p-2 rounded-lg transition-colors inline-flex items-center justify-center"
                                                               style="background: #fff3cd; color: #856404;"
                                                               title="Modifier ma structure"
                                                               onmouseover="this.style.background='#ffc107'; this.style.color='white';"
                                                               onmouseout="this.style.background='#fff3cd'; this.style.color='#856404';">
                                                                <i class="fas fa-edit text-xs"></i>
                                                            </a>
                                                            <form action="{{ route('structures.destroy', $structure) }}" 
                                                                  method="POST" 
                                                                  class="inline"
                                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer votre structure ?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="p-2 rounded-lg transition-colors" 
                                                                        style="background: #ffebee; color: #c62828;"
                                                                        type="submit" 
                                                                        title="Supprimer ma structure"
                                                                        onmouseover="this.style.background='#ef5350'; this.style.color='white';"
                                                                        onmouseout="this.style.background='#ffebee'; this.style.color='#c62828';">
                                                                    <i class="fas fa-trash text-xs"></i>
                                                                </button>
                                                            </form>
                                                        @elseif(auth()->user()->role === 'moderateur'
                                                                && auth()->user()->structure
                                                                && auth()->user()->structure->id_organisme
                                                                && $structure->organisme
                                                                && auth()->user()->structure->id_organisme === $structure->organisme->id)
                                                            <a href="{{ route('structures.edit', $structure) }}" 
                                                               class="p-2 rounded-lg transition-colors inline-flex items-center justify-center"
                                                               style="background: #fff3cd; color: #856404;"
                                                               title="Modifier une structure de mon organisme"
                                                               onmouseover="this.style.background='#ffc107'; this.style.color='white';"
                                                               onmouseout="this.style.background='#fff3cd'; this.style.color='#856404';">
                                                                <i class="fas fa-edit text-xs"></i>
                                                            </a>
                                                            <form action="{{ route('structures.destroy', $structure) }}" 
                                                                  method="POST" 
                                                                  class="inline"
                                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette structure de votre organisme ?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="p-2 rounded-lg transition-colors" 
                                                                        style="background: #ffebee; color: #c62828;"
                                                                        type="submit" 
                                                                        title="Supprimer une structure de mon organisme"
                                                                        onmouseover="this.style.background='#ef5350'; this.style.color='white';"
                                                                        onmouseout="this.style.background='#ffebee'; this.style.color='#c62828';">
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
                        <i class="fas fa-building text-4xl" style="color: #dceeec;"></i>
                        <p class="mt-4" style="color: #4a7a7f;">Aucune structure trouvée</p>
                        <p class="text-sm mt-2" style="color: #7fa8ac;">Essayez de modifier vos critères de recherche</p>
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
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 1rem; overflow: hidden;">
            <div style="background: linear-gradient(135deg, #255156, #3a7378); color: white; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <h5 class="text-xl font-bold flex items-center gap-3">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter une structure locale
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="p-6" style="background: #f8fcfc;">
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

<!-- MODAL DETAILS AMÉLIORÉ -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 800px;">
        <div class="modal-content border-0 shadow-2xl overflow-hidden" style="border-radius: 1rem;">
            <div style="background: linear-gradient(135deg, #255156, #3a7378); color: white; padding: 0.75rem 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center overflow-hidden">
                        <div id="modal-logo-container" class="w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-building text-white text-xl" id="modal-logo-icon"></i>
                            <img id="modal-logo-img" src="" alt="Logo" class="w-full h-full object-cover hidden">
                        </div>
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
            <div class="modal-body p-4" style="background: #f8fcfc; max-height: 70vh; overflow-y: auto;">
                <!-- 2 colonnes : Informations principales et Localisation -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                    <!-- Colonne 1 : Informations principales -->
                    <div class="bg-white p-3 rounded-lg border" style="border-color: #dceeec; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                        <h6 class="font-semibold mb-2 text-sm" style="color: #255156;">
                            <i class="fas fa-info-circle mr-1"></i> Informations principales
                        </h6>
                        <div class="space-y-1.5 text-sm">
                            <div class="flex justify-between py-1 border-b" style="border-color: #f0f6f5;">
                                <span class="font-medium" style="color: #4a7a7f;">Organisme:</span>
                                <span class="font-semibold" style="color: #255156;" id="modal-organisme-text">-</span>
                            </div>
                            <div class="flex justify-between py-1 border-b" style="border-color: #f0f6f5;">
                                <span class="font-medium" style="color: #4a7a7f;">Catégories:</span>
                                <span style="color: #3d6f74;" id="modal-categories">-</span>
                            </div>
                            <div class="flex justify-between py-1 border-b" style="border-color: #f0f6f5;">
                                <span class="font-medium" style="color: #4a7a7f;">Public cible:</span>
                                <span style="color: #3d6f74;" id="modal-public_cible">-</span>
                            </div>
                            <div class="flex justify-between py-1 border-b" style="border-color: #f0f6f5;">
                                <span class="font-medium" style="color: #4a7a7f;">Zone d'intervention:</span>
                                <span style="color: #3d6f74;" id="modal-zone">-</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="font-medium" style="color: #4a7a7f;">Site web:</span>
                                <span style="color: #3d6f74;" id="modal-site">-</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne 2 : Localisation -->
                    <div class="bg-white p-3 rounded-lg border" style="border-color: #dceeec; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                        <h6 class="font-semibold mb-2 text-sm" style="color: #255156;">
                            <i class="fas fa-map-marker-alt mr-1"></i> Localisation
                        </h6>
                        <div class="mb-2 p-2 rounded" style="background: #e3f2fd;">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-landmark text-blue-500 text-xs"></i>
                                <span class="font-semibold text-blue-700 text-xs">SIÈGE SOCIAL</span>
                            </div>
                            <div class="text-xs space-y-1">
                                <div class="flex">
                                    <span class="w-16" style="color: #4a7a7f;">Ville:</span>
                                    <span class="font-medium" style="color: #255156;" id="modal-siege_ville">-</span>
                                </div>
                                <div class="flex">
                                    <span class="w-16" style="color: #4a7a7f;">Adresse:</span>
                                    <span class="truncate" style="color: #3d6f74;" id="modal-siege_adresse">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 rounded" style="background: #e8f5e9;">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-map-pin text-green-500 text-xs"></i>
                                <span class="font-semibold text-green-700 text-xs">STRUCTURE LOCALE</span>
                            </div>
                            <div class="text-xs space-y-1">
                                <div class="flex">
                                    <span class="w-20" style="color: #4a7a7f;">Ville:</span>
                                    <span class="font-medium" style="color: #255156;" id="modal-ville">-</span>
                                </div>
                                <div class="flex">
                                    <span class="w-20" style="color: #4a7a7f;">Code postal:</span>
                                    <span style="color: #3d6f74;" id="modal-code_postal">-</span>
                                </div>
                                <div class="flex">
                                    <span class="w-20" style="color: #4a7a7f;">Adresse:</span>
                                    <span class="truncate" style="color: #3d6f74;" id="modal-adresse">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact -->
                <div class="bg-white p-3 rounded-lg border mb-3" style="border-color: #dceeec; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                    <h6 class="font-semibold mb-2 text-sm" style="color: #255156;">
                        <i class="fas fa-address-card mr-1"></i> Contact
                    </h6>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                        <div class="flex items-center gap-2 p-2 rounded" style="background: #f8fcfc;">
                            <i class="fas fa-phone text-green-500"></i>
                            <div>
                                <div class="text-xs" style="color: #4a7a7f;">Téléphone</div>
                                <div class="font-medium" style="color: #255156;" id="modal-telephone">-</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded" style="background: #f8fcfc;">
                            <i class="fas fa-envelope text-blue-500"></i>
                            <div>
                                <div class="text-xs" style="color: #4a7a7f;">Email</div>
                                <span class="font-medium" style="color: #255156;" id="modal-email">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="bg-white p-3 rounded-lg border" style="border-color: #dceeec; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                    <h6 class="font-semibold mb-2 text-sm" style="color: #255156;">
                        <i class="fas fa-align-left mr-1"></i> Description
                    </h6>
                    <div class="text-sm leading-relaxed p-3 rounded min-h-[80px]" style="background: #f8fcfc; color: #3d6f74; border: 1px dashed #dceeec;" id="modal-description">-</div>
                </div>
            </div>
            <div class="modal-footer bg-white p-3 border-t" style="border-color: #dceeec;">
                <div class="flex justify-end gap-2 w-full">
                    <button type="button" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2"
                            style="background: #e8f3f2; color: #255156;"
                            data-bs-dismiss="modal"
                            onmouseover="this.style.background='#d4ecea';"
                            onmouseout="this.style.background='#e8f3f2';">
                        <i class="fas fa-times"></i> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Style net et précis pour l'accordéon */
    .organisme-body { 
        transition: all 0.3s ease-in-out; 
    }
    .organisme-body:not(.hidden) { 
        display: block; 
        animation: fadeIn 0.3s ease; 
    }
    @keyframes fadeIn { 
        from { opacity: 0; transform: translateY(-10px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    .organisme-chevron { 
        transition: transform 0.2s ease; 
    }
    .organisme-header.active .organisme-chevron { 
        transform: rotate(90deg); 
    }
    
    /* Tous les accordéons fermés par défaut */
    .organisme-body {
        display: none !important;
    }
    .organisme-body:not(.hidden) {
        display: block !important;
    }
    
    /* Scrollbar personnalisée */
    ::-webkit-scrollbar { 
        width: 6px; 
        height: 6px; 
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
    
    /* Truncate pour les textes longs */
    .truncate { 
        overflow: hidden; 
        text-overflow: ellipsis; 
        white-space: nowrap; 
    }
    
    /* Style des selects - fermés par défaut */
    select {
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
    }
    
    /* Tableau - lignes alternées */
    .structure-row:nth-child(even) {
        background: #fafdfd;
    }
    .structure-row:nth-child(odd) {
        background: #ffffff;
    }
    
    /* En-tête de colonne */
    thead th {
        font-weight: 600;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        font-size: 0.7rem;
    }
    
    /* Conteneur de logo - taille fixe */
    .logo-container {
        width: 48px;
        height: 48px;
        min-width: 48px;
        min-height: 48px;
        flex-shrink: 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .logo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
</style>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function initAccordion() {
        const headers = document.querySelectorAll('.organisme-header');
        // Tous fermés par défaut
        headers.forEach(header => {
            header.classList.remove('active');
            const body = header.nextElementSibling;
            if(body) body.classList.add('hidden');
        });
        
        headers.forEach(header => {
            header.addEventListener('click', function(e) {
                if(e.target.closest('.edit-organisme-btn')) return;
                const body = this.nextElementSibling;
                const isActive = this.classList.contains('active');
                
                // Fermer tous les autres
                headers.forEach(h => {
                    if(h !== header && h.classList.contains('active')) {
                        h.classList.remove('active');
                        const otherBody = h.nextElementSibling;
                        if(otherBody) otherBody.classList.add('hidden');
                    }
                });
                
                if(isActive) {
                    this.classList.remove('active');
                    if(body) body.classList.add('hidden');
                } else {
                    this.classList.add('active');
                    if(body) body.classList.remove('hidden');
                }
            });
        });
    }
    
    function filterAndSearch() {
        const searchQuery = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const organismeFilter = document.getElementById('filterOrganisme')?.value.toLowerCase() || '';
        const cityFilter = document.getElementById('filterCity')?.value.toLowerCase() || '';
        const groups = document.querySelectorAll('.organisme-group');
        let totalVisible = 0;
        groups.forEach(group => {
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
                if(isVisible) { groupVisible = true; totalVisible++; }
            });
            group.style.display = groupVisible ? '' : 'none';
        });
        const resultCount = document.getElementById('resultCount');
        const totalRows = document.querySelectorAll('.structure-row').length;
        if(resultCount) resultCount.textContent = totalVisible === totalRows ? `${totalRows} structures` : `${totalVisible} structures (sur ${totalRows})`;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        initAccordion();
        
        const searchInput = document.getElementById('searchInput');
        if(searchInput) searchInput.addEventListener('input', filterAndSearch);
        const filterOrganisme = document.getElementById('filterOrganisme');
        if(filterOrganisme) filterOrganisme.addEventListener('change', filterAndSearch);
        const filterCity = document.getElementById('filterCity');
        if(filterCity) filterCity.addEventListener('change', filterAndSearch);
        
        const viewDetailsButtons = document.querySelectorAll('.view-details-btn');
        viewDetailsButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                try {
                    const structure = JSON.parse(this.getAttribute('data-structure'));
                    const org = structure.organisme || {};
                    
                    document.getElementById('modal-organisme').textContent = org.nom_organisme || '-';
                    document.getElementById('modal-organisme-text').textContent = org.nom_organisme || '-';
                    document.getElementById('modal-description').textContent = structure.description || 'Aucune description';
                    document.getElementById('modal-categories').textContent = structure.categories || '-';
                    document.getElementById('modal-public_cible').textContent = structure.public_cible || '-';
                    document.getElementById('modal-zone').textContent = structure.zone || '-';
                    document.getElementById('modal-site').innerHTML = org.site_web ? `<a href="${org.site_web}" target="_blank" style="color: #255156; text-decoration: underline;">${org.site_web}</a>` : '-';
                    document.getElementById('modal-siege_ville').textContent = org.ville && org.code_postal ? `${org.ville} (${org.code_postal})` : '-';
                    document.getElementById('modal-siege_adresse').textContent = org.adresse || '-';
                    document.getElementById('modal-ville').textContent = structure.ville || '-';
                    document.getElementById('modal-code_postal').textContent = structure.code_postal || '-';
                    document.getElementById('modal-adresse').textContent = structure.adresse || '-';
                    document.getElementById('modal-telephone').textContent = structure.telephone || '-';
                    document.getElementById('modal-email').textContent = structure.email || '-';
                    
                    // Gestion du logo dans la modale
                    const logoImg = document.getElementById('modal-logo-img');
                    const logoIcon = document.getElementById('modal-logo-icon');
                    if(org.logo_path && org.logo_path !== '') {
                        const logoUrl = "{{ asset('storage/') }}/" + org.logo_path;
                        logoImg.src = logoUrl;
                        logoImg.classList.remove('hidden');
                        logoIcon.classList.add('hidden');
                    } else {
                        logoImg.classList.add('hidden');
                        logoIcon.classList.remove('hidden');
                    }
                } catch(e) { console.error('Erreur:', e); }
            });
        });
    });
</script>
@endsection