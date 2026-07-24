@extends('base')

@section('title', 'Liste groupée des structures')

@section('content')
<div class="max-w-10xl mx-auto px-0 sm:px-6 lg:px-4 py-2 space-y-2">
    <!-- Message succès -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Succès !</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- HEADER SIMPLIFIÉ -->
    <div class="rounded-2xl p-4 shadow-xl text-white d-flex flex-wrap justify-content-between align-items-center gap-3"
         style="background: linear-gradient(135deg, #255156, #1e7c86);">
        
        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-building" style="font-size: 1.3rem;"></i>
            <div>
                <h5 class="mb-0 fw-bold">Structure par organisme</h5>
                <small class="text-white/80">
                    <i class="fas fa-info-circle me-1"></i>
                    {{ $totalStructures }} structures - {{ count($groupes) }} sièges
                </small>
            </div>
        </div>
        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur' || auth()->user()->role === 'moderateur_classique')
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('annuaire.index') }}" 
                    class="btn btn-outline-light btn-sm fw-semibold px-3 py-1.5 rounded-lg transition-all"
                    style="border: 1px solid rgba(255,255,255,0.3); color: white;"
                    onmouseover="this.style.background='rgba(255,255,255,0.15)';"
                    onmouseout="this.style.background='transparent';">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
         @endif
            </div>
   
    <!-- FILTRES -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="p-3" style="background: linear-gradient(135deg, #255156, #1e7c86);">
            <h6 class="font-bold text-white flex items-center gap-2 mb-0">
                <i class="fas fa-filter"></i> Filtres
            </h6>
        </div>
        <div class="p-3" style="background: #f8fcfc;">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-3">
                <div class="lg:col-span-2">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" id="searchGroupes" 
                               placeholder="Rechercher..."
                               class="w-full pl-8 pr-3 py-2 text-sm rounded-lg border border-gray-200 focus:border-[#255156] focus:ring-1 focus:ring-[#255156] outline-none transition-all bg-white">
                    </div>
                </div>
                
                <div>
                    <select id="filterOrganisme" 
                            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:border-[#255156] focus:ring-1 focus:ring-[#255156] outline-none appearance-none bg-white">
                        <option value="">Tous les organismes</option>
                        @foreach($organismes as $organisme)
                            <option value="{{ $organisme->nom_organisme }}">{{ $organisme->nom_organisme }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <select id="filterVille" 
                            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:border-[#255156] focus:ring-1 focus:ring-[#255156] outline-none appearance-none bg-white">
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
            
            <div class="flex flex-wrap items-center justify-between mt-2">
                <div id="activeFilters" class="hidden">
                    <div id="filterBadges" class="flex flex-wrap gap-1"></div>
                </div>
                <button id="resetFilters"
                        class="text-xs text-[#255156] hover:underline px-2 py-1 rounded transition-all">
                    <i class="fas fa-undo-alt me-1"></i> Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <!-- LISTE GROUPÉE -->
    <div class="space-y-3" id="groupesContainer">
        @forelse($groupes as $siege => $structures)
            @php
                $firstStructure = $structures->first();
                $logoPath = $firstStructure && $firstStructure->organisme ? $firstStructure->organisme->logo_path : null;
                $nom = $siege;
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
            <div class="groupe-card bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 transition-all duration-300"
                 data-siege="{{ $siege }}">
                
                <div class="groupe-header cursor-pointer transition-colors duration-200 p-3"
                     style="background: linear-gradient(90deg, #f8fcfc, #f0f6f5);"
                     onmouseover="this.style.background='linear-gradient(90deg, #f0f6f5, #e8f3f2)';"
                     onmouseout="this.style.background='linear-gradient(90deg, #f8fcfc, #f0f6f5)';">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-chevron-right text-gray-400 transition-transform duration-200 groupe-chevron"></i>
                            </div>
                            <!-- LOGO AVEC ZOOM AU CLIC -->
                            <div class="logo-container w-16 h-16 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden cursor-pointer"
                                 style="background: linear-gradient(135deg, #f8fbfc, #e3e7e9);"
                                 onclick="openLogoZoom('{{ $logoPath ? asset('storage/' . $logoPath) : '' }}', '{{ $siege }}')">
                                
                                @if($logoPath && file_exists(storage_path('app/public/' . $logoPath)))
                                    <img src="{{ asset('storage/' . $logoPath) }}" 
                                         alt="Logo {{ $siege }}"
                                         class="w-full h-full object-contain"
                                         style="width: 100%; height: 100%; object-fit: contain; object-position: center; padding: 4px;"
                                         onerror="this.style.display='none'; this.parentElement.innerHTML='<span style=\'font-size: 0.9rem; font-weight: 700; color: white;\'>{{ $initiales }}</span>';">
                                @else
                                    <span style="font-size: 0.9rem; font-weight: 700; color: white;">{{ $initiales }}</span>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-lg font-bold" style="color: #255156;">
                                    {{ $siege ?: 'Siège non spécifié' }}
                                </h2>
                                <p class="text-sm" style="color: #4a7a7f;">
                                    <i class="fas fa-building mr-1"></i>
                                    <span class="structures-count">{{ $structures->count() }}</span> structure(s)
                                    @if($structures->first()->siege_adresse || $structures->first()->siege_ville)
                                        <span class="text-xs ml-2" style="color: #7fa8ac;">
                                            <i class="fas fa-map-pin mr-1"></i>
                                            {{ $structures->first()->siege_adresse ?: '' }}
                                            {{ $structures->first()->siege_ville ? '- ' . $structures->first()->siege_ville : '' }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <button class="toggle-group text-gray-400 hover:text-[#255156] transition-all p-1 rounded-lg hover:bg-white/50">
                            <i class="fas fa-chevron-down text-lg"></i>
                        </button>
                    </div>
                </div>
                
                <div class="structures-list hidden p-3 border-t border-gray-100" style="background: #fafdfd;">
                    <div class="mb-3">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" 
                                   class="search-structure w-full pl-8 pr-3 py-1.5 text-sm rounded-lg border border-gray-200 focus:border-[#255156] focus:ring-1 focus:ring-[#255156] outline-none transition-all bg-white"
                                   placeholder="Rechercher dans {{ $structures->count() }} structures..."
                                   data-siege-id="siege-{{ $loop->index }}">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-xs px-2 py-0.5 rounded-full"
                                  style="background: #e8f3f2; color: #255156;">
                                {{ $structures->count() }}
                            </span>
                        </div>
                    </div>
                    
                    @if($structures->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3" 
                             id="siege-{{ $loop->index }}-grid">
                            @foreach($structures as $index => $structure)
                                @php
                                    $rowColor = $index % 2 === 0 ? 'white' : '#f8fcfc';
                                    $borderColor = $index % 2 === 0 ? '#e8f3f2' : '#dceeec';
                                    $structLogoPath = $structure->organisme && $structure->organisme->logo_path ? $structure->organisme->logo_path : null;
                                    $structNom = $structure->organisme->nom_organisme ?? 'S';
                                    $structInitiales = '';
                                    $structMots = explode(' ', $structNom);
                                    foreach($structMots as $mot) {
                                        if(!empty($mot) && strlen($mot) > 0) {
                                            $structInitiales .= strtoupper(substr($mot, 0, 1));
                                        }
                                        if(strlen($structInitiales) >= 2) break;
                                    }
                                    if(empty($structInitiales)) $structInitiales = 'S';
                                @endphp
                                <div class="structure-card rounded-lg border p-3 transition-all duration-200"
                                     style="background: {{ $rowColor }}; border-color: {{ $borderColor }};"
                                     data-organisme="{{ strtolower($structure->organisme->nom_organisme ?? '') }}"
                                     data-ville="{{ strtolower($structure->ville ?? '') }}"
                                     data-search="{{ strtolower($structure->organisme->nom_organisme ?? '') . ' ' . ($structure->ville ?: '') . ' ' . ($structure->adresse ?: '') . ' ' . ($structure->categories ?: '') }}"
                                     data-siege-id="siege-{{ $loop->parent->index }}"
                                     onmouseover="this.style.borderColor='#2d6268'; this.style.boxShadow='0 4px 12px rgba(45,98,104,0.12)'; this.style.transform='translateY(-2px)';"
                                     onmouseout="this.style.borderColor='{{ $borderColor }}'; this.style.boxShadow='none'; this.style.transform='translateY(0)';">                               
                                    
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-start gap-2">
                                            <!-- Logo structure avec zoom -->
                                            <div class="logo-container w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden cursor-pointer"
                                                 style="background: linear-gradient(135deg, #255156, #4a8599);"
                                                 onclick="event.stopPropagation(); openLogoZoom('{{ $structLogoPath ? asset('storage/' . $structLogoPath) : '' }}', '{{ $structure->organisme->nom_organisme ?? 'Structure' }}')">
                                                
                                                @if($structLogoPath && file_exists(storage_path('app/public/' . $structLogoPath)))
                                                    <img src="{{ asset('storage/' . $structLogoPath) }}" 
                                                         alt="Logo {{ $structure->organisme->nom_organisme ?? '' }}"
                                                         class="w-full h-full object-contain"
                                                         style="width:100%; height:100%; object-fit:contain; object-position:center; padding:2px;"
                                                         onerror="this.style.display='none'; this.parentElement.innerHTML='<span style=\'font-size: 0.7rem; font-weight: 700; color: white;\'>{{ $structInitiales }}</span>';">
                                                @else
                                                    <span style="font-size: 0.7rem; font-weight: 700; color: white;">{{ $structInitiales }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-sm line-clamp-2" style="color: #1a3c40;" 
                                                    title="{{ $structure->organisme->nom_organisme ?? '' }}">
                                                    {{ $structure->organisme->nom_organisme ?? 'Organisme non spécifié' }}
                                                </h3>
                                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-xs font-medium"
                                                      style="background: #e8f3f2; color: #255156;">
                                                    {{ Str::limit($structure->categories ?? 'Non catégorisé', 20) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-1.5 text-xs" style="color: #3d6f74;">
                                        <div class="flex items-start gap-2">
                                            <i class="fas fa-map-marker-alt mt-0.5" style="color: #7fa8ac;"></i>
                                            <span class="flex-1 line-clamp-1" title="{{ $structure->adresse }}, {{ $structure->code_postal }} {{ $structure->ville }}">
                                                {{ Str::limit($structure->adresse ?? 'Adresse non spécifiée', 25) }}
                                                @if($structure->code_postal || $structure->ville)
                                                    , {{ $structure->code_postal }} {{ Str::limit($structure->ville, 15) }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-phone" style="color: #7fa8ac;"></i>
                                            @if($structure->telephone)
                                                <a href="tel:{{ $structure->telephone }}" 
                                                   class="hover:underline font-medium"
                                                   style="color: #255156;">
                                                    {{ Str::limit($structure->telephone, 15) }}
                                                </a>
                                            @else
                                                <span style="color: #b0c8cb;">Non disponible</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-envelope" style="color: #7fa8ac;"></i>
                                            @if($structure->email)
                                                <a href="mailto:{{ $structure->email }}" 
                                                   class="hover:underline truncate font-medium"
                                                   style="color: #255156;">
                                                    {{ Str::limit($structure->email, 20) }}
                                                </a>
                                            @else
                                                <span style="color: #b0c8cb;">Non disponible</span>
                                            @endif
                                        </div>
                                    </div>

                                   <div class="mt-2 pt-2 border-t d-flex gap-2" style="border-color: #f0f6f5;">

                                <button class="view-details-btn text-xs font-medium px-3 py-1.5 rounded-lg transition-all d-flex align-items-center gap-1"
                                        style="background: #e8f3f2; color: #255156;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailsModal"
                                        data-structure='@json($structure)'
                                        onmouseover="this.style.background='#255156'; this.style.color='white';"
                                        onmouseout="this.style.background='#e8f3f2'; this.style.color='#255156';">
                                    <i class="fas fa-eye text-xs"></i>
                                    Détails
                                </button>

                                <a href="{{ route('annuaire.membre_structure', [$structure->id]) }}"
                                    class="text-xs font-medium px-3 py-1.5 rounded-lg transition-all d-flex align-items-center gap-1 text-decoration-none"
                                    style="background: #e8f3f2; color: #255156;"
                                    onmouseover="this.style.background='#255156'; this.style.color='white';"
                                    onmouseout="this.style.background='#e8f3f2'; this.style.color='#255156';">
                                    <i class="fas fa-users text-xs"></i>
                                    Membres
                                </a>

                            </div>
                        </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mb-3"
                                     style="background: #e8f3f2;">
                                    <i class="fas fa-building text-2xl" style="color: #b0c8cb;"></i>
                                </div>
                                <p class="text-sm font-medium" style="color: #7fa8ac;">Aucune structure</p>
                                <p class="text-xs mt-1" style="color: #b0c8cb;">Aucune structure n'est rattachée à ce siège</p>
                            </div>
                        </div>
                    @endif
                    
                    <div id="no-structure-result-{{ $loop->index }}" 
                         class="hidden flex flex-col items-center justify-center py-6" style="color: #7fa8ac;">
                        <i class="fas fa-map-marker-alt text-3xl mb-2 opacity-30"></i>
                        <p class="text-sm font-medium">Aucune structure trouvée</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-lg p-8 text-center border border-gray-100">
                <div class="flex flex-col items-center justify-center" style="color: #7fa8ac;">
                    <i class="fas fa-building text-5xl mb-3 opacity-20"></i>
                    <p class="text-base font-medium" style="color: #255156;">Aucune structure trouvée</p>
                    <p class="text-sm mt-1">Il n'y a pas encore de structures dans l'annuaire</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- MODAL ZOOM LOGO -->
<div class="modal fade" id="logoZoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 1rem; ">
            <div class="modal-body p-4 text-center">
                <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="modal"></button>
                <div class="mt-2">
                    <img id="logoZoomImage" src="" alt="Logo agrandi" 
                         style="max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 0.5rem;">
                    <p id="logoZoomTitle" class="text-white mt-3 font-semibold"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DÉTAILS AVEC LOGO INTÉGRÉ -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 800px;">
        <div class="modal-content border-0 shadow-2xl overflow-hidden" style="border-radius: 1rem;">
            <div style="background: linear-gradient(135deg, #255156, #3a7378); color: white; padding: 0.75rem 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                <div class="flex items-center gap-3">
                    <!-- LOGO DANS LA MODALE -->
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden cursor-pointer"
                         style="background: linear-gradient(135deg, #255156, #4a8599);"
                         onclick="event.stopPropagation(); openLogoZoom(document.getElementById('modal-logo-img-detail').src, document.getElementById('modal-organisme-detail').textContent)">
                        <i class="fas fa-building text-white text-2xl" id="modal-logo-icon-detail"></i>
                        <img id="modal-logo-img-detail" src="" alt="Logo" 
                             class="w-full h-full object-contain" 
                             style="width:100%; height:100%; object-fit:contain; object-position:center; padding:4px; display:none;">
                    </div>
                    <div>
                        <h5 class="modal-title text-lg font-bold" id="detailsModalLabel">
                            <span id="modal-organisme-detail">-</span>
                        </h5>
                        <p class="text-sm text-white/80 font-medium">Structure détaillée</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: #f8fcfc; max-height: 70vh; overflow-y: auto;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
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

<style>
/* Accordéon */
.groupe-chevron {
    transition: transform 0.2s ease;
}
.groupe-header.active .groupe-chevron {
    transform: rotate(90deg);
}
.structures-list {
    display: none !important;
}
.structures-list:not(.hidden) {
    display: block !important;
}

/* Animation */
.groupe-card {
    animation: slideIn 0.3s ease-out;
}
@keyframes slideIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Cartes */
.structure-card {
    transition: all 0.2s ease-in-out;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Logos */
.logo-container {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.logo-container:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 16px rgba(37,81,86,0.3);
}

/* Selects */
select {
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="%23999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 12px;
    padding-right: 30px !important;
}

/* Scrollbar */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: #e8f3f2; border-radius: 10px; }
::-webkit-scrollbar-thumb { background: #4a8599; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #255156; }

/* Responsive */
@media (max-width: 768px) {
    .groupe-header .flex.items-center.justify-between {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}

/* Modal Zoom Logo */
.modal-content.bg-dark {
    background: rgba(0,0,0,0.9) !important;
}
</style>

<script>
function openLogoZoom(imageUrl, title) {
    const modal = new bootstrap.Modal(document.getElementById('logoZoomModal'));
    const img = document.getElementById('logoZoomImage');
    const titleEl = document.getElementById('logoZoomTitle');
    
    if (imageUrl && imageUrl !== '' && imageUrl !== 'http://localhost' && imageUrl !== 'http://localhost/') {
        img.src = imageUrl;
        img.style.display = 'block';
        titleEl.textContent = title || 'Logo';
    } else {
        img.style.display = 'none';
        titleEl.textContent = 'Aucun logo disponible';
    }
    
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // ==================== ACCORDÉON ====================
    const headers = document.querySelectorAll('.groupe-header');
    const toggleButtons = document.querySelectorAll('.toggle-group');
    
    headers.forEach(header => {
        header.classList.remove('active');
        const list = header.closest('.groupe-card').querySelector('.structures-list');
        if(list) list.classList.add('hidden');
        const icon = header.querySelector('.toggle-group i');
        if(icon) {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    });
    
    headers.forEach(header => {
        header.addEventListener('click', function(e) {
            if (e.target.closest('.toggle-group') || e.target.closest('.logo-container')) return;
            
            const card = this.closest('.groupe-card');
            const list = card.querySelector('.structures-list');
            const isActive = this.classList.contains('active');
            
            headers.forEach(h => {
                if(h !== this && h.classList.contains('active')) {
                    h.classList.remove('active');
                    const otherList = h.closest('.groupe-card').querySelector('.structures-list');
                    if(otherList) {
                        otherList.classList.add('hidden');
                        const otherIcon = h.querySelector('.toggle-group i');
                        if(otherIcon) {
                            otherIcon.classList.remove('fa-chevron-up');
                            otherIcon.classList.add('fa-chevron-down');
                        }
                    }
                }
            });
            
            if(isActive) {
                this.classList.remove('active');
                list.classList.add('hidden');
                const icon = this.querySelector('.toggle-group i');
                if(icon) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            } else {
                this.classList.add('active');
                list.classList.remove('hidden');
                const icon = this.querySelector('.toggle-group i');
                if(icon) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            }
        });
    });
    
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const header = this.closest('.groupe-header');
            const card = header.closest('.groupe-card');
            const list = card.querySelector('.structures-list');
            const isActive = header.classList.contains('active');
            const icon = this.querySelector('i');
            
            if(isActive) {
                header.classList.remove('active');
                list.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                header.classList.add('active');
                list.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        });
    });

    // ==================== FILTRAGE ====================
    function filterStructures() {
        const searchQuery = document.getElementById('searchGroupes')?.value.toLowerCase().trim() || '';
        const filterOrganisme = document.getElementById('filterOrganisme')?.value.toLowerCase().trim() || '';
        const filterVille = document.getElementById('filterVille')?.value.toLowerCase().trim() || '';
        
        const groupeCards = document.querySelectorAll('.groupe-card');
        let totalVisibleStructures = 0;
        let activeFilters = [];
        
        if (filterOrganisme) activeFilters.push('Organisme');
        if (filterVille) activeFilters.push('Ville');
        if (searchQuery) activeFilters.push('Recherche');
        
        const activeFiltersDiv = document.getElementById('activeFilters');
        const filterBadges = document.getElementById('filterBadges');
        
        if (activeFilters.length > 0) {
            activeFiltersDiv.classList.remove('hidden');
            filterBadges.innerHTML = '';
            
            if (filterOrganisme) {
                filterBadges.innerHTML += `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium"
                    style="background: #e8f3f2; color: #255156;">
                    <i class="fas fa-filter text-[8px]"></i> ${document.getElementById('filterOrganisme').value}
                </span>`;
            }
            if (filterVille) {
                filterBadges.innerHTML += `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium"
                    style="background: #e8f3f2; color: #255156;">
                    <i class="fas fa-city text-[8px]"></i> ${document.getElementById('filterVille').value}
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
                const ville = structure.dataset.ville || '';
                const structureText = structure.textContent.toLowerCase();
                
                const matchesSearch = searchQuery === '' || structureText.includes(searchQuery) || siegeNom.includes(searchQuery);
                const matchesOrganisme = filterOrganisme === '' || organisme.includes(filterOrganisme);
                const matchesVille = filterVille === '' || ville.includes(filterVille);
                
                const matches = matchesSearch && matchesOrganisme && matchesVille;
                
                structure.style.display = matches ? '' : 'none';
                if (matches) {
                    hasVisibleStructure = true;
                    visibleInSiege++;
                }
            });
            
            const countSpan = card.querySelector('.structures-count');
            if (countSpan) countSpan.textContent = visibleInSiege;
            
            if (searchQuery === '' && filterOrganisme === '' && filterVille === '') {
                card.style.display = '';
                totalVisibleStructures += structuresCards.length;
            } else {
                card.style.display = hasVisibleStructure ? '' : 'none';
                totalVisibleStructures += visibleInSiege;
            }
        });
    }

    function filterStructuresInSiege(siegeId, query) {
        const grid = document.getElementById(siegeId + '-grid');
        if (!grid) return;
        
        const structures = grid.querySelectorAll('.structure-card');
        const noResultMsg = document.getElementById('no-structure-result-' + siegeId.split('-')[1]);
        let visibleCount = 0;
        
        structures.forEach(structure => {
            const searchData = structure.dataset.search || '';
            const matches = query === '' || searchData.includes(query.toLowerCase());
            structure.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });
        
        if (noResultMsg) {
            if (visibleCount === 0 && query !== '') {
                noResultMsg.classList.remove('hidden');
            } else {
                noResultMsg.classList.add('hidden');
            }
        }
    }

    // Écouteurs
    document.getElementById('searchGroupes')?.addEventListener('input', filterStructures);
    document.getElementById('filterOrganisme')?.addEventListener('change', filterStructures);
    document.getElementById('filterVille')?.addEventListener('change', filterStructures);
    
    document.getElementById('resetFilters')?.addEventListener('click', function() {
        document.getElementById('searchGroupes').value = '';
        document.getElementById('filterOrganisme').value = '';
        document.getElementById('filterVille').value = '';
        filterStructures();
        document.querySelectorAll('.search-structure').forEach(input => {
            input.value = '';
            filterStructuresInSiege(input.dataset.siegeId, '');
        });
    });
    
    document.querySelectorAll('.search-structure').forEach(input => {
        input.addEventListener('input', function() {
            filterStructuresInSiege(this.dataset.siegeId, this.value.toLowerCase().trim());
        });
    });

    // ==================== MODAL DÉTAILS AVEC LOGO ====================
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const structure = JSON.parse(this.dataset.structure);
            const org = structure.organisme || {};
            
            // Informations principales
            document.getElementById('modal-organisme-detail').textContent = org.nom_organisme || '-';
            document.getElementById('modal-organisme-text').textContent = org.nom_organisme || '-';
            document.getElementById('modal-categories').textContent = structure.categories || 'Non spécifié';
            document.getElementById('modal-public_cible').textContent = structure.public_cible || 'Non spécifié';
            document.getElementById('modal-zone').textContent = structure.zone || 'Non spécifié';
            
            // Site web
            const siteElement = document.getElementById('modal-site');
            if (org.site) {
                siteElement.innerHTML = `<a href="${org.site}" target="_blank" style="color: #255156; text-decoration: underline;">${org.site}</a>`;
            } else {
                siteElement.innerHTML = '<span class="text-gray-400">Non disponible</span>';
            }
            
            // Localisation
            document.getElementById('modal-siege_ville').textContent = org.ville && org.code_postal ? `${org.ville} (${org.code_postal})` : 'Non spécifié';
            document.getElementById('modal-siege_adresse').textContent = org.adresse || 'Non spécifié';
            document.getElementById('modal-ville').textContent = structure.ville || 'Non spécifié';
            document.getElementById('modal-code_postal').textContent = structure.code_postal || 'Non spécifié';
            document.getElementById('modal-adresse').textContent = structure.adresse || 'Non spécifié';
            
            // Contact
            document.getElementById('modal-telephone').textContent = structure.telephone || 'Non disponible';
            document.getElementById('modal-email').textContent = structure.email || 'Non disponible';
            document.getElementById('modal-contact').textContent = structure.contact || 'Non spécifié';
            
            // Description
            document.getElementById('modal-description').textContent = structure.description || 'Aucune description disponible';
            
            // ==================== GESTION DU LOGO DANS LA MODALE ====================
            const modalLogoImg = document.getElementById('modal-logo-img-detail');
            const modalLogoIcon = document.getElementById('modal-logo-icon-detail');
            
            if (org.logo_path && org.logo_path !== '') {
                const logoUrl = "{{ asset('storage/') }}/" + org.logo_path;
                modalLogoImg.src = logoUrl;
                modalLogoImg.style.display = 'block';
                modalLogoIcon.style.display = 'none';
            } else {
                modalLogoImg.style.display = 'none';
                modalLogoIcon.style.display = 'block';
            }
        });
    });
});
</script>
@endsection