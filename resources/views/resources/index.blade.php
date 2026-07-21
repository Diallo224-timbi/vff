@extends('base')

@section('title', 'Espace documentaire & Schéma violences')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Carte principale -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <!-- En-tête avec onglets -->
                <div class="card-header text-white py-3" style="background: #145f68; border: none;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div>
                            <i class="fas fa-folder-open me-2"></i>
                            <h4 class="d-inline-block mb-0 fw-bold">Espace documentaire & Schéma violences</h4>
                            <div class="alert alert-light mt-3 shadow-sm border-0">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <strong>Rappel :</strong>
                                Partagez des ressources professionnelles dans un esprit de bienveillance et de collaboration,
                                tout en respectant les dispositions de la charte de la plateforme.
                            </div>
                        </div>
                        <!-- Onglets de navigation -->
                        <div class="btn-group mt-2 mt-sm-0" role="group">
                            <button type="button" class="btn btn-light active" id="tabDocs" onclick="switchTab('docs')">
                                <i class="fas fa-file-alt me-1"></i> Documents
                            </button>
                            <button type="button" class="btn btn-light" id="tabSchemas" onclick="switchTab('schemas')">
                                <i class="fas fa-project-diagram me-1"></i> Schéma violences
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- ============================================ -->
                    <!-- SECTION DOCUMENTS -->
                    <!-- ============================================ -->
                    <div id="docsSection">
                        <!-- Indicateur de filtre actif -->
                        <div id="activeFilter" class="mb-3 d-none">
                            <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                                <i class="fas fa-filter me-2"></i>
                                <span id="filterLabel">Filtre actif : </span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="clearCategoryFilter()"></button>
                            </div>
                        </div>

                        <!-- FILTRES PAR CATÉGORIE PRINCIPALE -->
                        <div class="mb-4">
                            <label class="small fw-semibold text-secondary mb-2">Filtrer par catégorie</label>
                            <div class="row g-2">
                                <div class="col-6 col-md-3 col-lg-2">
                                    <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="all" onclick="filterByCategory('all', 'Toutes les ressources')">
                                        <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                            <i class="fas fa-folder-open text-secondary fa-lg mb-1"></i>
                                            <p class="fw-semibold text-secondary mb-0 small">Toutes</p>
                                            <small class="text-secondary" id="countAll">0</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 col-lg-2">
                                    <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="guides_etudes" onclick="filterByCategory('guides_etudes', 'Guides & Études')">
                                        <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                            <i class="fas fa-book text-primary fa-lg mb-1"></i>
                                            <p class="fw-semibold text-primary mb-0 small">Guides & Études</p>
                                            <small class="text-primary" id="countGuidesEtudes">0</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 col-lg-2">
                                    <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="affiches_flyers" onclick="filterByCategory('affiches_flyers', 'Affiches & Flyers')">
                                        <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                            <i class="fas fa-poster text-success fa-lg mb-1"></i>
                                            <p class="fw-semibold text-success mb-0 small">Affiches & Flyers</p>
                                            <small class="text-success" id="countAffichesFlyers">0</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 col-lg-2">
                                    <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="reseaux" onclick="filterByCategory('reseaux', 'Réseaux')">
                                        <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                            <i class="fas fa-network-wired text-warning fa-lg mb-1"></i>
                                            <p class="fw-semibold text-warning mb-0 small">Réseaux</p>
                                            <small class="text-warning" id="countReseaux">0</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 col-lg-2">
                                    <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="sensibilisation" onclick="filterByCategory('sensibilisation', 'Sensibilisation & Formations')">
                                        <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                            <i class="fas fa-graduation-cap text-info fa-lg mb-1"></i>
                                            <p class="fw-semibold text-info mb-0 small">Sensibilisation</p>
                                            <small class="text-info" id="countSensibilisation">0</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 col-lg-2">
                                    <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="outils" onclick="filterByCategory('outils', 'Outils')">
                                        <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                            <i class="fas fa-tools text-danger fa-lg mb-1"></i>
                                            <p class="fw-semibold text-danger mb-0 small">Outils</p>
                                            <small class="text-danger" id="countOutils">0</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 col-lg-2">
                                    <div class="cursor-pointer rounded-lg p-2 text-center border filter-category" style="border-radius: 10px; border-color: #e5e7eb; cursor: pointer;" data-category="conventions" onclick="filterByCategory('conventions', 'Conventions & Protocoles')">
                                        <div class="rounded-lg p-2" style="background: #f8f9fa;">
                                            <i class="fas fa-file-signature text-purple fa-lg mb-1"></i>
                                            <p class="fw-semibold text-purple mb-0 small">Conventions</p>
                                            <small class="text-purple" id="countConventions">0</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SOUS-CATÉGORIES -->
                        <div id="subCategoriesContainer" class="mb-3 d-none">
                            <label class="small fw-semibold text-secondary mb-2">Sous-catégorie</label>
                            <div class="d-flex flex-wrap gap-2" id="subCategoriesList">
                                <!-- Rempli dynamiquement par JS -->
                            </div>
                        </div>

                        <!-- Barre de recherche -->
                        <div class="mb-4">
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <div class="input-group" style="border-radius: 10px; overflow: hidden;">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" id="searchInput" class="form-control border-start-0"
                                               placeholder="Rechercher par titre ou description...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select id="filterType" class="form-select">
                                        <option value="">Tous les types</option>
                                        <option value="image">Images</option>
                                        <option value="document">Documents</option>
                                        <option value="link">Liens externes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton Ajouter -->
                        <div class="mb-3">
                            <button onclick="openCreateModal()" class="btn" style="background: #255156; color: white;">
                                <i class="fas fa-upload me-1"></i> Ajouter une ressource
                            </button>
                            @if(auth()->user()->role === 'admin')
                            <a href="{{ route('resources.trash') }}" class="btn" style="background: #255156; color: white;">
                                <i class="fa fa-trash me-1"></i> Corbeille
                            </a>
                            @endif
                        </div>

                        <!-- GRILLE DE CARTES -->
                        <div class="mt-3">
                            <div class="row g-3" id="resourcesGrid">
                                @forelse($resources as $resource)
                                <div class="col-md-6 col-lg-2 col-xl-2 resource-card"
                                     data-id="{{ $resource->id }}"
                                     data-type="{{ $resource->is_link ? 'link' : ($resource->is_image ? 'image' : 'document') }}"
                                     data-category="{{ $resource->category }}"
                                     data-sub-category="{{ $resource->sub_category }}"
                                     data-date="{{ $resource->created_at->timestamp }}"
                                     data-downloads="{{ $resource->download_count }}"
                                     data-title="{{ strtolower($resource->title) }}">
                                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; overflow: hidden; transition: transform 0.2s;">
                                        <!-- Zone image / icône -->
                                        <div style="height: 140px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; position: relative;">
                                            @if($resource->is_image)
                                                <img src="{{ Storage::url($resource->file_path) }}" alt="{{ $resource->title }}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openImageModal('{{ $resource->file_url }}', '{{ $resource->title }}')">
                                            @elseif($resource->is_link)
                                                <i class="fas fa-link" style="font-size: 3.5rem; color: #0d6efd;"></i>
                                            @else
                                                @php
                                                    $extension = strtolower($resource->file_type);
                                                @endphp
                                                @if(in_array($extension, ['pdf']))
                                                    <i class="bx bxs-file-pdf text-danger" style="font-size: 3.5rem;"></i>
                                                @elseif(in_array($extension, ['doc', 'docx', 'odt']))
                                                    <i class="fas fa-file-word text-primary" style="font-size: 3.5rem;"></i>
                                                @elseif(in_array($extension, ['xls', 'xlsx', 'csv']))
                                                    <i class="fas fa-file-excel text-success" style="font-size: 3.5rem;"></i>
                                                @elseif(in_array($extension, ['ppt', 'pptx']))
                                                    <i class="fas fa-file-powerpoint text-warning" style="font-size: 3.5rem;"></i>
                                                @elseif(in_array($extension, ['txt']))
                                                    <i class="fas fa-file-alt text-secondary" style="font-size: 3.5rem;"></i>
                                                @elseif(in_array($extension, ['webm', 'avi', 'mov']))
                                                    <i class="fas fa-file-video text-danger" style="font-size: 3.5rem;"></i>
                                                @else
                                                    <i class="fas fa-file text-secondary" style="font-size: 3.5rem;"></i>
                                                @endif
                                            @endif

                                            <!-- Badges superposés -->
                                            <div style="position: absolute; top: 8px; right: 8px; display: flex; flex-direction: column; gap: 4px;">
                                                @if($resource->is_link)
                                                    <span class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 0.65rem;">
                                                        <i class="fas fa-link"></i> Lien
                                                    </span>
                                                @elseif($resource->is_image)
                                                    <span class="badge" style="background: #f3e8ff; color: #9333ea; font-size: 0.65rem;">
                                                        <i class="fas fa-image me-1"></i>Image
                                                    </span>
                                                @else
                                                    <span class="badge" style="background: #dbeafe; color: #2563eb; font-size: 0.65rem;">
                                                        <i class="fas fa-file me-1"></i>{{ strtoupper($resource->file_type) }}
                                                    </span>
                                                @endif

                                                @if($resource->category == 'guides_etudes')
                                                    <span class="badge" style="background: #dbeafe; color: #2563eb; font-size: 0.65rem;">Guide & Étude</span>
                                                @elseif($resource->category == 'affiches_flyers')
                                                    <span class="badge" style="background: #dcfce7; color: #16a34a; font-size: 0.65rem;">Affiche & Flyer</span>
                                                @elseif($resource->category == 'reseaux')
                                                    <span class="badge" style="background: #fef3c7; color: #d97706; font-size: 0.65rem;">Réseau</span>
                                                @elseif($resource->category == 'sensibilisation')
                                                    <span class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 0.65rem;">Sensibilisation</span>
                                                @elseif($resource->category == 'outils')
                                                    <span class="badge" style="background: #f3e8ff; color: #9333ea; font-size: 0.65rem;">Outil</span>
                                                @elseif($resource->category == 'conventions')
                                                    <span class="badge" style="background: #fce7f3; color: #be185d; font-size: 0.65rem;">Convention</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Corps de la carte -->
                                        <div class="card-body p-2">
                                            <h6 class="card-title fw-semibold mb-1 small" title="{{ $resource->title }}">{{ Str::limit($resource->title, 25) }}</h6>
                                            @if($resource->description)
                                                <p class="card-text small text-muted mb-1">{{ Str::limit($resource->description, 50) }}</p>
                                            @endif
                                            <div class="d-flex flex-wrap gap-1 small text-muted" style="font-size: 0.7rem;">
                                                @if(!$resource->is_link)
                                                    <span><i class="fas fa-download me-1"></i> {{ $resource->download_count }}</span>
                                                @endif
                                                <span><i class="fas fa-calendar me-1"></i> {{ $resource->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </div>

                                        <!-- Pied de carte avec actions -->
                                        <div class="card-footer bg-transparent border-0 pt-0 pb-2">
                                            <div class="d-flex gap-1 justify-content-end flex-wrap">
                                                @if($resource->is_link)
                                                    <a href="{{ $resource->link_url }}" target="_blank" class="btn btn-sm" style="background: #e0f2fe; color: #0284c7; padding: 2px 6px; font-size: 0.7rem;" title="Ouvrir le lien">
                                                        <i class="fas fa-link"></i>
                                                    </a>
                                                @endif

                                                @if($resource->is_image)
                                                    <button onclick="openImageModal('{{ $resource->file_url }}', '{{ $resource->title }}')" class="btn btn-sm" style="background: #f3e8ff; color: #9333ea; padding: 2px 6px; font-size: 0.7rem;" title="Voir l'image">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                @endif

                                                @if(!$resource->is_link)
                                                    <a href="{{ Storage::url($resource->file_path) }}" target="_blank" class="btn btn-sm" style="background: #e5e7eb; color: #4b5563; padding: 2px 6px; font-size: 0.7rem;" title="Ouvrir le fichier">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                    <a href="{{ route('resources.download', $resource) }}" class="btn btn-sm" style="background: #dbeafe; color: #2563eb; padding: 2px 6px; font-size: 0.7rem;" title="Télécharger">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif

                                                @if(auth()->user()->role === 'admin' || auth()->user()->id === $resource->user_id)
                                                    <a href="{{ route('resources.edit', $resource) }}" class="btn btn-sm" style="background: #c7d2fe; color: #3730a3; padding: 2px 6px; font-size: 0.7rem;" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button onclick="deleteResource({{ $resource->id }}, this)" class="btn btn-sm" style="background: #fee2e2; color: #dc2626; padding: 2px 6px; font-size: 0.7rem;" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune ressource disponible</p>
                                    <button onclick="openCreateModal()" class="btn" style="background: #255156; color: white;">
                                        <i class="fas fa-upload me-1"></i>Ajouter la première ressource
                                    </button>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $resources->links() }}
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- SECTION SCHÉMA VIOLENCES -->
                    <!-- ============================================ -->
                    <div id="schemasSection" style="display: none;">
                        <div class="row">
                            <!-- Colonne de gauche : Arborescence des GT -->
                            <div class="col-lg-4 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-sitemap text-primary me-2"></i>
                                        Groupes de travail
                                    </h5>
                                    <button onclick="openCreateSchemaModal()" class="btn btn-sm" style="background: #145f68; color: white;">
                                        <i class="fas fa-plus me-1"></i> Nouveau GT
                                    </button>
                                </div>
                                <!-- Arborescence des GT -->
                                <div class="list-group">
                                    <!-- GT1 avec sous-groupes -->
                                    <div class="list-group-item p-0 border-0 mb-2" style="border-radius: 10px; overflow: hidden; border: 1px solid #e5e7eb;">
                                        <div class="p-3" style="background: #f8f9fa; cursor: pointer;" onclick="toggleSubGroups('subGroupGT1')">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <i class="fas fa-folder text-primary me-2"></i>
                                                    <span class="fw-semibold">GT1 - Réseau VIF-VC & coordination entre acteurs</span>
                                                </div>
                                                <span>
                                                    <i class="fas fa-chevron-down" id="iconSubGroupGT1"></i>
                                                    <span class="badge bg-secondary ms-2" id="countGT1">0</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div id="subGroupGT1" class="p-2" style="background: #fff; border-top: 1px solid #e5e7eb; display: block;">
                                            <!-- SGT1 -->
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded mb-1" style="background: #f1f5f9; cursor: pointer;" onclick="filterSchemasByGT('SGT1')">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-chevron-right text-secondary" style="font-size: 0.7rem;"></i>
                                                    <span class="small">SGT1 - Sensibilisation & formations et information grand public</span>
                                                </div>
                                                <span class="badge bg-info" id="countSGT1">0</span>
                                            </div>
                                            <!-- SGT2 -->
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded mb-1" style="background: #f1f5f9; cursor: pointer;" onclick="filterSchemasByGT('SGT2')">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-chevron-right text-secondary" style="font-size: 0.7rem;"></i>
                                                    <span class="small">SGT2 - Coordination acteurs / Outils professionnels</span>
                                                </div>
                                                <span class="badge bg-info" id="countSGT2">0</span>
                                            </div>
                                            <!-- SGT3 -->
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded mb-1" style="background: #f1f5f9; cursor: pointer;" onclick="filterSchemasByGT('SGT3')">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-chevron-right text-secondary" style="font-size: 0.7rem;"></i>
                                                    <span class="small">SGT3 - Coordination acteurs / Outils professionnels</span>
                                                </div>
                                                <span class="badge bg-info" id="countSGT3">0</span>
                                            </div>
                                            <!-- SGT4 -->
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded mb-1" style="background: #f1f5f9; cursor: pointer;" onclick="filterSchemasByGT('SGT4')">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-chevron-right text-secondary" style="font-size: 0.7rem;"></i>
                                                    <span class="small">SGT4 - Parcours</span>
                                                </div>
                                                <span class="badge bg-info" id="countSGT4">0</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- GT2 -->
                                    <div class="list-group-item p-3 border-0 mb-2" style="border-radius: 10px; border: 1px solid #e5e7eb; background: #f8f9fa; cursor: pointer;" onclick="filterSchemasByGT('GT2')">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fas fa-folder text-primary me-2"></i>
                                                <span>GT2 - Force de l'ordre, justice et santé</span>
                                            </div>
                                            <span class="badge bg-secondary" id="countGT2">0</span>
                                        </div>
                                    </div>

                                    <!-- GT3 -->
                                    <div class="list-group-item p-3 border-0 mb-2" style="border-radius: 10px; border: 1px solid #e5e7eb; background: #f8f9fa; cursor: pointer;" onclick="filterSchemasByGT('GT3')">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fas fa-folder text-primary me-2"></i>
                                                <span>GT3 - Auteurs de violences</span>
                                            </div>
                                            <span class="badge bg-secondary" id="countGT3">0</span>
                                        </div>
                                    </div>

                                    <!-- GT4 -->
                                    <div class="list-group-item p-3 border-0 mb-2" style="border-radius: 10px; border: 1px solid #e5e7eb; background: #f8f9fa; cursor: pointer;" onclick="filterSchemasByGT('GT4')">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fas fa-folder text-primary me-2"></i>
                                                <span>GT4 - Cellule familiale</span>
                                            </div>
                                            <span class="badge bg-secondary" id="countGT4">0</span>
                                        </div>
                                    </div>

                                    <!-- GT5 -->
                                    <div class="list-group-item p-3 border-0 mb-2" style="border-radius: 10px; border: 1px solid #e5e7eb; background: #f8f9fa; cursor: pointer;" onclick="filterSchemasByGT('GT5')">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fas fa-folder text-primary me-2"></i>
                                                <span>GT5 - Hébergement - logement FVVC et auteurs</span>
                                            </div>
                                            <span class="badge bg-secondary" id="countGT5">0</span>
                                        </div>
                                    </div>

                                    <!-- GT6 -->
                                    <div class="list-group-item p-3 border-0 mb-2" style="border-radius: 10px; border: 1px solid #e5e7eb; background: #f8f9fa; cursor: pointer;" onclick="filterSchemasByGT('GT6')">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fas fa-folder text-primary me-2"></i>
                                                <span>GT6 - Pilotage du schéma</span>
                                            </div>
                                            <span class="badge bg-secondary" id="countGT6">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Colonne de droite : Détails du GT sélectionné -->
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap">
                                        <div>
                                            <i class="fas fa-file-alt text-primary me-2"></i>
                                            <span id="currentGtTitle" class="fw-semibold">Sélectionnez un groupe de travail</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3" style="max-height: 500px; overflow-y: auto;">
                                        <!-- Liste des CR de réunion -->
                                        <div id="meetingReportsList">
                                            <div class="text-center py-5 text-muted">
                                                <i class="fas fa-file-pdf fa-3x mb-3 opacity-25"></i>
                                                <p>Sélectionnez un GT pour voir les comptes rendus</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALS -->
<!-- ============================================ -->

<!-- MODAL IMAGE -->
<div id="imageModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-body p-0 text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CRÉATION DOCUMENT -->
<div id="resourceModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header" style="background: #255156; color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title">Ajouter une ressource</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="resourceForm" enctype="multipart/form-data" method="POST" action="{{ route('resources.store') }}">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" required class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea id="description" name="description" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Type de ressource <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group">
                            <button type="button" id="btnFileType" class="btn btn-outline-primary active" onclick="selectResourceType('file')">
                                <i class="fas fa-upload me-1"></i> Fichier
                            </button>
                            <button type="button" id="btnLinkType" class="btn btn-outline-primary" onclick="selectResourceType('link')">
                                <i class="fas fa-link me-1"></i> Lien externe
                            </button>
                        </div>
                    </div>

                    <div id="fileUploadSection" class="mb-3">
                        <label class="form-label fw-semibold">Fichier <span class="text-danger">*</span></label>
                        <input type="file" id="file" name="file" class="form-control" accept=".pdf,.doc,.odt,.docx,.xls,.csv,.jpg,.jpeg,.png,.gif,.webm,.avi">
                        <small class="text-muted">Formats acceptés: PDF, DOC, ODT, DOCX, JPG, PNG, GIF, Max 50Mo</small>
                    </div>

                    <div id="linkSection" class="mb-3 d-none">
                        <label class="form-label fw-semibold">URL du lien <span class="text-danger">*</span></label>
                        <input type="url" id="linkUrl" name="link_url" class="form-control" placeholder="https://exemple.com/document">
                        <small class="text-muted">Entrez l'URL complète du lien externe</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                        <select id="category" name="category" required class="form-select" onchange="updateSubCategories(this.value)">
                            <option value="">Sélectionner une catégorie</option>
                            <option value="guides_etudes">Guides & Études violences faites aux femmes</option>
                            <option value="affiches_flyers">Affiches et flyers des partenaires</option>
                            <option value="reseaux">Réseaux violences conjugales et VIF du département</option>
                            <option value="sensibilisation">Catalogues de sensibilisations & formations des partenaires</option>
                            <option value="outils">Outils</option>
                            <option value="conventions">Conventions, protocoles & dispositif</option>
                        </select>
                    </div>

                    <div class="mb-3" id="subCategoryContainer">
                        <label class="form-label fw-semibold">Sous-catégorie</label>
                        <select id="subCategory" name="sub_category" class="form-select">
                            <option value="">Aucune</option>
                        </select>
                    </div>

                    @if(auth()->user()->role === 'admin')
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="important" name="important" value="1">
                        <label class="form-check-label fw-semibold" for="important">Marquer comme ressource importante</label>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn" style="background: #255156; color: white;">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL CRÉATION SCHÉMA / GT -->
<div id="createSchemaModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header" style="background: #145f68; color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i> <span id="schemaModalTitle">Nouveau compte rendu</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createSchemaForm" method="POST" action="{{ route('schemas.store') }}" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">GT / SGT <span class="text-danger">*</span></label>
                        <select id="schemaCategory" name="category" required class="form-select">
                            <option value="">Sélectionner un GT</option>
                            <option value="GT1">GT1 - Réseau VIF-VC & coordination entre acteurs</option>
                            <option value="GT2">GT2 - Force de l'ordre, justice et santé</option>
                            <option value="GT3">GT3 - Auteurs de violences</option>
                            <option value="GT4">GT4 - Cellule familiale</option>
                            <option value="GT5">GT5 - Hébergement - logement FVVC et auteurs</option>
                            <option value="GT6">GT6 - Pilotage du schéma</option>
                        </select>
                    </div>

                    <div class="mb-3" id="schemaSubCategoryContainer">
                        <label class="form-label fw-semibold">Sous-groupe (SGT)</label>
                        <select id="schemaSubCategory" name="sub_category" class="form-select">
                            <option value="">Aucun</option>
                            <option value="SGT1">SGT1 - Sensibilisation & formations et information grand public</option>
                            <option value="SGT2">SGT2 - Coordination acteurs / Outils professionnels</option>
                            <option value="SGT3">SGT3 - Coordination acteurs / Outils professionnels</option>
                            <option value="SGT4">SGT4 - Parcours</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre du CR / Document <span class="text-danger">*</span></label>
                        <input type="text" id="schemaTitle" name="title" required class="form-control" placeholder="Ex: CR réunion GT1 du 15/04/2025">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea id="schemaDescription" name="description" rows="2" class="form-control" placeholder="Résumé de la réunion..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Compte rendu (PDF) <span class="text-danger">*</span></label>
                        <input type="file" id="schemaFile" name="file" class="form-control" accept=".pdf">
                        <small class="text-muted">Formats acceptés: PDF uniquement, Max 20Mo</small>
                    </div>

                    <input type="hidden" id="schemaData" name="data" value='{"elements":[],"appState":[],"files":[]}'>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn" style="background: #145f68; color: white;">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .resource-card .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .resource-card .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
    }

    .filter-category.active {
        border: 2px solid #255156 !important;
        background-color: #f0f7f7 !important;
        transform: scale(1.02);
        transition: all 0.2s ease;
    }
    .filter-category {
        transition: all 0.2s ease;
    }
    .filter-category:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .text-purple {
        color: #7c3aed;
    }

    @media (max-width: 576px) {
        .resource-card .card-body {
            padding: 0.5rem !important;
        }
        .resource-card .card-footer {
            padding: 0.25rem 0.5rem !important;
        }
    }

    .btn-group .btn-light.active {
        background: white;
        color: #145f68;
        font-weight: 600;
        border-color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .btn-group .btn-light {
        background: rgba(255,255,255,0.2);
        color: white;
        border-color: rgba(255,255,255,0.3);
    }
    .btn-group .btn-light:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }
    .btn-group .btn-light.active:hover {
        background: white;
        color: #145f68;
    }

    .list-group-item .d-flex:hover {
        background: #e8f4f5 !important;
        transition: background 0.2s;
    }

    .list-group-item {
        transition: all 0.2s ease;
    }
    .list-group-item:hover {
        transform: translateX(3px);
    }
    
    .badge.bg-info {
        background-color: #0dcaf0 !important;
        color: #000 !important;
    }

    .fa-chevron-down.rotated {
        transform: rotate(180deg);
        transition: transform 0.3s ease;
    }
    .fa-chevron-down {
        transition: transform 0.3s ease;
    }
</style>

<script>
    // ============================================
    // GESTION DES ONGLETS
    // ============================================
    function switchTab(tab) {
        const docsSection = document.getElementById('docsSection');
        const schemasSection = document.getElementById('schemasSection');
        const tabDocs = document.getElementById('tabDocs');
        const tabSchemas = document.getElementById('tabSchemas');

        if (tab === 'docs') {
            docsSection.style.display = 'block';
            schemasSection.style.display = 'none';
            tabDocs.classList.add('active');
            tabSchemas.classList.remove('active');
        } else {
            docsSection.style.display = 'none';
            schemasSection.style.display = 'block';
            tabDocs.classList.remove('active');
            tabSchemas.classList.add('active');
            setTimeout(updateGtCounts, 500);
        }
    }

    // ============================================
    // GESTION DES DOCUMENTS
    // ============================================
    const allResources = @json($resources->items() ?? []);
    let selectedResourceType = 'file';
    let currentCategoryFilter = null;
    let currentSubCategoryFilter = null;

    const subCategoriesMap = {
        'guides_etudes': [
            { value: 'national', label: 'National' },
            { value: 'departemental', label: 'Départemental' }
        ],
        'affiches_flyers': [
            { value: 'victimes', label: 'Victimes' },
            { value: 'auteurs', label: 'Auteurs' }
        ],
        'reseaux': [
            { value: 'guides', label: 'Guides' },
            { value: 'kit_creation', label: 'Kit création réseau' }
        ],
        'outils': [
            { value: 'coordination', label: 'Coordination acteurs' },
            { value: 'prevention', label: 'Prévention & sensibilisation' }
        ],
        'conventions': [
            { value: 'victimes', label: 'Victimes' },
            { value: 'auteurs', label: 'Auteurs' }
        ]
    };

    function updateSubCategories(category) {
        const subSelect = document.getElementById('subCategory');
        subSelect.innerHTML = '<option value="">Aucune</option>';
        
        if (category && subCategoriesMap[category]) {
            subCategoriesMap[category].forEach(sub => {
                const option = document.createElement('option');
                option.value = sub.value;
                option.textContent = sub.label;
                subSelect.appendChild(option);
            });
        }
    }

    window.filterByCategory = function(category, label) {
        if (currentCategoryFilter === category) {
            clearCategoryFilter();
        } else {
            currentCategoryFilter = category;
            currentSubCategoryFilter = null;
            document.querySelectorAll('.filter-category').forEach(el => {
                el.classList.toggle('active', el.getAttribute('data-category') === category);
            });
            const filterLabel = document.getElementById('filterLabel');
            if (filterLabel) filterLabel.innerHTML = `<i class="fas fa-filter me-2"></i>Filtre actif : ${label}`;
            const activeFilter = document.getElementById('activeFilter');
            if (activeFilter) activeFilter.classList.remove('d-none');

            const subContainer = document.getElementById('subCategoriesContainer');
            const subList = document.getElementById('subCategoriesList');
            subList.innerHTML = '';
            
            if (category && subCategoriesMap[category]) {
                subContainer.classList.remove('d-none');
                const allBtn = document.createElement('span');
                allBtn.className = 'badge bg-secondary p-2 cursor-pointer me-1';
                allBtn.style.cursor = 'pointer';
                allBtn.textContent = 'Toutes';
                allBtn.dataset.value = 'all';
                allBtn.onclick = function() { filterBySubCategory('all', 'Toutes'); };
                subList.appendChild(allBtn);

                subCategoriesMap[category].forEach(sub => {
                    const btn = document.createElement('span');
                    btn.className = 'badge bg-primary p-2 cursor-pointer me-1';
                    btn.style.cursor = 'pointer';
                    btn.textContent = sub.label;
                    btn.dataset.value = sub.value;
                    btn.onclick = function() { filterBySubCategory(sub.value, sub.label); };
                    subList.appendChild(btn);
                });
            } else {
                subContainer.classList.add('d-none');
            }

            filterResourcesByCategory();
        }
    };

    function filterBySubCategory(subCategory, label) {
        currentSubCategoryFilter = subCategory === 'all' ? null : subCategory;
        document.querySelectorAll('#subCategoriesList .badge').forEach(el => {
            if (el.dataset.value === subCategory) {
                el.className = 'badge bg-success p-2 cursor-pointer me-1';
            } else if (el.dataset.value === 'all' && subCategory === 'all') {
                el.className = 'badge bg-success p-2 cursor-pointer me-1';
            } else {
                el.className = 'badge bg-primary p-2 cursor-pointer me-1';
            }
        });
        filterResourcesByCategory();
    }

    window.clearCategoryFilter = function() {
        currentCategoryFilter = null;
        currentSubCategoryFilter = null;
        document.querySelectorAll('.filter-category').forEach(el => el.classList.remove('active'));
        const activeFilter = document.getElementById('activeFilter');
        if (activeFilter) activeFilter.classList.add('d-none');
        document.getElementById('subCategoriesContainer').classList.add('d-none');
        filterResourcesByCategory();
    };

    function filterResourcesByCategory() {
        const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const typeFilter = document.getElementById('filterType')?.value || '';

        document.querySelectorAll('.resource-card').forEach(card => {
            const category = card.dataset.category || '';
            const subCategory = card.dataset.subCategory || '';
            const title = card.dataset.title || '';
            const type = card.dataset.type || '';
            let show = true;

            if (currentCategoryFilter && currentCategoryFilter !== 'all' && category !== currentCategoryFilter) show = false;
            if (show && currentSubCategoryFilter && subCategory !== currentSubCategoryFilter) show = false;
            if (show && searchTerm && !title.includes(searchTerm)) show = false;
            if (show && typeFilter && type !== typeFilter) show = false;

            card.style.display = show ? '' : 'none';
        });
    }

    window.selectResourceType = function(type) {
        selectedResourceType = type;
        const btnFile = document.getElementById('btnFileType');
        const btnLink = document.getElementById('btnLinkType');
        const fileSection = document.getElementById('fileUploadSection');
        const linkSection = document.getElementById('linkSection');
        const fileInput = document.getElementById('file');
        const linkUrlInput = document.getElementById('linkUrl');

        if (type === 'file') {
            btnFile?.classList.add('active');
            btnLink?.classList.remove('active');
            fileSection?.classList.remove('d-none');
            linkSection?.classList.add('d-none');
            if (fileInput) fileInput.required = true;
            if (linkUrlInput) linkUrlInput.required = false;
        } else {
            btnFile?.classList.remove('active');
            btnLink?.classList.add('active');
            fileSection?.classList.add('d-none');
            linkSection?.classList.remove('d-none');
            if (fileInput) fileInput.required = false;
            if (linkUrlInput) linkUrlInput.required = true;
        }
    };

    function updateCategoryCounts() {
        const counts = {
            all: allResources.length,
            guides_etudes: 0,
            affiches_flyers: 0,
            reseaux: 0,
            sensibilisation: 0,
            outils: 0,
            conventions: 0
        };
        allResources.forEach(r => {
            if (counts.hasOwnProperty(r.category)) {
                counts[r.category]++;
            }
        });
        document.getElementById('countAll').textContent = counts.all;
        document.getElementById('countGuidesEtudes').textContent = counts.guides_etudes || 0;
        document.getElementById('countAffichesFlyers').textContent = counts.affiches_flyers || 0;
        document.getElementById('countReseaux').textContent = counts.reseaux || 0;
        document.getElementById('countSensibilisation').textContent = counts.sensibilisation || 0;
        document.getElementById('countOutils').textContent = counts.outils || 0;
        document.getElementById('countConventions').textContent = counts.conventions || 0;
    }

    let imageModal, resourceModal, createSchemaModal;

    document.addEventListener('DOMContentLoaded', function() {
        const imageModalEl = document.getElementById('imageModal');
        const resourceModalEl = document.getElementById('resourceModal');
        const createSchemaModalEl = document.getElementById('createSchemaModal');

        if (imageModalEl) imageModal = new bootstrap.Modal(imageModalEl);
        if (resourceModalEl) resourceModal = new bootstrap.Modal(resourceModalEl);
        if (createSchemaModalEl) createSchemaModal = new bootstrap.Modal(createSchemaModalEl);

        updateCategoryCounts();

        const searchInput = document.getElementById('searchInput');
        const filterType = document.getElementById('filterType');

        if (searchInput && filterType) {
            searchInput.addEventListener('input', filterResourcesByCategory);
            filterType.addEventListener('change', filterResourcesByCategory);
        }

        document.getElementById('createSchemaForm')?.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('schemaFile');
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner un fichier PDF');
                return false;
            }
        });

        // Initialiser les données des schémas
        window.allSchemas = @json($schemas ?? []);
        console.log('📦 Schémas chargés :', window.allSchemas.length);
        if (window.allSchemas.length > 0) {
            console.table(window.allSchemas);
        }
        setTimeout(updateGtCounts, 500);
    });

    window.openCreateModal = function() {
        document.getElementById('resourceForm').reset();
        document.getElementById('fileUploadSection').classList.remove('d-none');
        document.getElementById('linkSection').classList.add('d-none');
        document.getElementById('btnFileType').classList.add('active');
        document.getElementById('btnLinkType').classList.remove('active');
        selectedResourceType = 'file';
        document.getElementById('file').required = true;
        document.getElementById('linkUrl').required = false;
        document.getElementById('subCategory').innerHTML = '<option value="">Aucune</option>';
        if (resourceModal) resourceModal.show();
    };

    window.openImageModal = function(url, title) {
        const modalImage = document.getElementById('modalImage');
        if (modalImage) {
            modalImage.src = url;
            modalImage.alt = title;
        }
        if (imageModal) imageModal.show();
    };

    window.deleteResource = function(id, btn) {
        if (!confirm('Supprimer cette ressource ?')) return;
        fetch(`/ressources/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de la suppression');
            }
        }).catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de connexion');
        });
    };

    document.getElementById('resourceForm')?.addEventListener('submit', function(e) {
        if (selectedResourceType === 'file') {
            const fileInput = document.getElementById('file');
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner un fichier');
                return false;
            }
        } else {
            const linkUrl = document.getElementById('linkUrl');
            if (!linkUrl.value.trim()) {
                e.preventDefault();
                alert('Veuillez entrer une URL valide');
                return false;
            }
        }
    });

    // ============================================
    // GESTION DES SCHÉMAS (GT)
    // ============================================
    let allSchemas = [];

    function toggleSubGroups(id) {
        const subGroup = document.getElementById(id);
        const icon = document.getElementById('icon' + id);
        if (subGroup) {
            if (subGroup.style.display === 'none') {
                subGroup.style.display = 'block';
                if (icon) icon.classList.remove('rotated');
            } else {
                subGroup.style.display = 'none';
                if (icon) icon.classList.add('rotated');
            }
        }
    }

    window.openCreateSchemaModal = function(gt) {
        document.getElementById('createSchemaForm').reset();
        document.getElementById('schemaData').value = '{"elements":[],"appState":[],"files":[]}';
        document.getElementById('schemaModalTitle').textContent = 'Ajouter un CR de réunion';
        
        if (gt) {
            document.getElementById('schemaCategory').value = gt;
            if (gt === 'GT1') {
                document.getElementById('schemaSubCategoryContainer').style.display = 'block';
            } else {
                document.getElementById('schemaSubCategoryContainer').style.display = 'none';
            }
        }
        
        if (createSchemaModal) createSchemaModal.show();
    };

    window.filterSchemasByGT = function(gt) {
        // Récupérer le label
        const labels = {
            'GT1': 'GT1 - Réseau VIF-VC & coordination entre acteurs',
            'GT2': 'GT2 - Force de l\'ordre, justice et santé',
            'GT3': 'GT3 - Auteurs de violences',
            'GT4': 'GT4 - Cellule familiale',
            'GT5': 'GT5 - Hébergement - logement FVVC et auteurs',
            'GT6': 'GT6 - Pilotage du schéma',
            'SGT1': 'SGT1 - Sensibilisation & formations et information grand public',
            'SGT2': 'SGT2 - Coordination acteurs / Outils professionnels',
            'SGT3': 'SGT3 - Coordination acteurs / Outils professionnels',
            'SGT4': 'SGT4 - Parcours'
        };
        const label = labels[gt] || gt;
        document.getElementById('currentGtTitle').textContent = label;
        
        console.log('🔍 Filtrage par GT:', gt);
        console.log('📊 Schémas disponibles:', allSchemas.length);
        
        // Filtrer les schémas
        const filteredSchemas = allSchemas.filter(s => {
            const match = s.category === gt || s.sub_category === gt;
            if (match) {
                console.log('✅ Match trouvé:', s.title);
            }
            return match;
        });
        
        console.log('📋 Résultats:', filteredSchemas.length);
        
        const container = document.getElementById('meetingReportsList');
        if (filteredSchemas.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-file-pdf fa-3x mb-3 opacity-25"></i>
                    <p>Aucun compte rendu pour ce groupe</p>
                    <button onclick="openCreateSchemaModal('${gt}')" class="btn btn-sm" style="background: #145f68; color: white;">
                        <i class="fas fa-plus me-1"></i> Ajouter un CR
                    </button>
                </div>
            `;
            return;
        }

        container.innerHTML = filteredSchemas.map(schema => `
            <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded" style="background: #f8f9fa; border-left: 4px solid #145f68;">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-file-pdf text-danger fa-2x"></i>
                    <div>
                        <h6 class="mb-0 fw-semibold">${schema.title}</h6>
                        ${schema.description ? `<small class="text-muted">${schema.description}</small>` : ''}
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i> ${new Date(schema.created_at).toLocaleDateString('fr-FR')}
                            </small>
                            ${schema.sub_category ? `
                                <span class="badge bg-info ms-2">${schema.sub_category}</span>
                            ` : ''}
                            ${schema.category ? `
                                <span class="badge bg-secondary ms-1">${schema.category}</span>
                            ` : ''}
                        </div>
                    </div>
                </div>
                <div class="btn-group btn-group-sm">
                    ${schema.file_path ? `
                        <a href="${'/storage/' + schema.file_path}" target="_blank" class="btn btn-outline-primary" title="Voir le PDF">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="${'/storage/' + schema.file_path}" download class="btn btn-outline-secondary" title="Télécharger">
                            <i class="fas fa-download"></i>
                        </a>
                    ` : `
                        <span class="badge bg-warning text-dark">Sans PDF</span>
                    `}
                    <button onclick="deleteSchema(${schema.id})" class="btn btn-outline-danger" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
    };

    window.deleteSchema = function(id) {
        if (!confirm('Supprimer ce compte rendu définitivement ?')) return;
        fetch(`/schemas/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression');
            }
        }).catch(console.error);
    };

    window.addMeetingReport = function() {
        const currentTitle = document.getElementById('currentGtTitle').textContent;
        let gt = '';
        const match = currentTitle.match(/^(S?GT\d+)/);
        if (match) {
            gt = match[1];
        }
        openCreateSchemaModal(gt);
    };

    function updateGtCounts() {
        const counts = {
            GT1: 0, SGT1: 0, SGT2: 0, SGT3: 0, SGT4: 0,
            GT2: 0, GT3: 0, GT4: 0, GT5: 0, GT6: 0
        };
        
        allSchemas.forEach(s => {
            if (s.category && counts[s.category] !== undefined) {
                counts[s.category]++;
            }
            if (s.sub_category && counts[s.sub_category] !== undefined) {
                counts[s.sub_category]++;
            }
        });
        
        document.querySelectorAll('[id^="count"]').forEach(el => {
            const id = el.id.replace('count', '');
            if (counts[id] !== undefined) {
                el.textContent = counts[id];
            } else {
                el.textContent = '0';
            }
        });
    }

    // Initialisation des données des schémas
    document.addEventListener('DOMContentLoaded', function() {
        allSchemas = @json($schemas ?? []);
        console.log('📦 Schémas chargés :', allSchemas.length);
        if (allSchemas.length > 0) {
            console.table(allSchemas);
        }
        setTimeout(updateGtCounts, 500);
    });
</script>
@endsection