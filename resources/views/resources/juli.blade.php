@extends('base')

@section('title', 'Espace documentaire')

@section('content')
<div class="container mx-auto px-0 py-0">
   
    <!-- En-tête fixe -->
    <div class="sticky top-0 z-40 bg-gray-50 pt-0 pb-0 shadow-sm" style="margin-top: -1px;">
        <!-- En-tête et légende des actions -->
        <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center bg-white rounded-xl shadow-lg p-4">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-[#255156] mb-2">📚 Espace documentaire</h1>
                <small class="text-gray-600">Centralisation des ressources professionnelles</small>
            </div>
            
            <!-- LÉGENDE DES BOUTONS D'ACTION + BOUTON STATISTIQUES -->
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-4 w-full lg:w-auto">
                <div class="flex flex-wrap items-center gap-2 bg-gray-100 px-4 py-2 rounded-lg">
                    <div class="flex items-center gap-1">
                        <span class="w-6 h-6 bg-red-100 text-red-600 rounded-lg flex items-center justify-center text-xs">
                            <i class="bx bx-play-circle text-lg"></i>
                        </span>
                        <span class="text-xs text-gray-600">Vidéo</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-6 h-6 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-xs">
                            <i class="bx bx-image text-xs"></i>
                        </span>
                        <span class="text-xs text-gray-600">Image</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-6 h-6 bg-gray-100 text-gray-600 rounded-lg flex items-center justify-center text-xs border border-gray-300">
                            <i class="bx bx-link text-xs"></i>
                        </span>
                        <span class="text-xs text-gray-600">Ouvrir</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs">
                            <i class="bx bx-download text-xs"></i>
                        </span>
                        <span class="text-xs text-gray-600">Télécharger</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-6 h-6 bg-yellow-200 text-yellow-600 rounded-lg flex items-center justify-center text-xs">
                            <i class="bx bx-edit text-xs"></i>
                        </span>
                        <span class="text-xs text-gray-600">Modifier</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-6 h-6 bg-red-600 text-white rounded-lg flex items-center justify-center text-xs">
                            <i class="bx bx-trash text-xs"></i>
                        </span>
                        <span class="text-xs text-gray-600">Supprimer</span>
                    </div>
                </div>
                
                <!-- BOUTON STATISTIQUES -->
                <button onclick="openStatsModal()" class="bg-[#255156] hover:bg-[#1d4144] text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors w-full lg:w-auto justify-center">
                    <i class="fas fa-chart-pie"></i>
                    Statistiques
                </button>
            </div>
        </div>

        <!-- Barre de recherche et filtres -->
        <div class="bg-white rounded-xl shadow-lg p-2">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rechercher</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" 
                               placeholder="Titre, description..."
                               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                    <select id="filterType" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                        <option value="">Tous</option>
                        <option value="image">🖼️ Images</option>
                        <option value="video">🎥 Vidéos</option>
                        <option value="document">📄 Documents</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie</label>
                    <select id="filterCategory" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                        <option value="">Toutes</option>
                        <option value="procedure">📋 Procédures</option>
                        <option value="outil">🛠️ Outils</option>
                        <option value="fiche_reflexe">⚡ Fiches réflexes</option>
                        <option value="ressource">📄 Ressources</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <select id="sortBy" class="text-sm border rounded-lg px-3 py-2">
                        <option value="newest">Plus récents</option>
                        <option value="oldest">Plus anciens</option>
                        <option value="popular">Plus téléchargés</option>
                    </select>
                </div>
                
                <button onclick="openCreateModal()" 
                        class="bg-[#255156] text-white px-4 py-2 rounded-lg hover:bg-[#1d4144] transition-colors flex items-center gap-2 w-full sm:w-auto justify-center">
                    <i class="fas fa-upload"></i>
                    Ajouter une ressource
                </button>
            </div>
        </div>
    </div>

    <!-- 📋 GRILLE DES RESSOURCES (CARTES) -->
    <div class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="resourcesGrid">
            @forelse($resources as $resource)
            <div class="resource-card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                 data-id="{{ $resource->id }}"
                 data-type="{{ $resource->is_image ? 'image' : ($resource->is_video ? 'video' : 'document') }}"
                 data-category="{{ $resource->category }}"
                 data-service="{{ $resource->service }}"
                 data-date="{{ $resource->created_at->timestamp }}"
                 data-downloads="{{ $resource->download_count }}"
                 data-title="{{ strtolower($resource->title) }}">
                
                <!-- En-tête de la carte avec type et icône -->
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        @if($resource->is_image)
                            <span class="flex items-center gap-2 text-purple-600 bg-purple-50 px-3 py-1 rounded-full">
                                <i class="fas fa-image"></i>
                                <span class="text-xs font-medium">Image</span>
                            </span>
                        @elseif($resource->is_video)
                            <span class="flex items-center gap-2 text-red-600 bg-red-50 px-3 py-1 rounded-full">
                                <i class="fas fa-video"></i>
                                <span class="text-xs font-medium">Vidéo</span>
                            </span>
                        @else
                            <span class="flex items-center gap-2 text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                <i class="fas {{ $resource->file_icon }}"></i>
                                <span class="text-xs font-medium">{{ strtoupper($resource->file_type) }}</span>
                            </span>
                        @endif
                    </div>
                    
                    <!-- Badge catégorie -->
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                        {{ ucfirst($resource->category) }}
                    </span>
                </div>
                
                <!-- Miniature ou icône selon le type -->
                <div class="px-4 pt-2 pb-0 flex justify-center">
                    @if($resource->is_image)
                        <div class="w-full h-32 rounded-lg overflow-hidden bg-gray-100 cursor-pointer" onclick="openImageModal('{{ $resource->url }}', '{{ $resource->title }}')">
                            <img src="{{ $resource->url }}" alt="{{ $resource->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                    @elseif($resource->is_video)
                        <div class="w-full h-32 rounded-lg overflow-hidden bg-gray-900 relative cursor-pointer group" onclick="openVideoModal('{{ $resource->url }}', '{{ $resource->title }}')">
                            <video class="w-full h-full object-cover opacity-75 group-hover:opacity-100 transition-opacity">
                                <source src="{{ $resource->url }}" type="video/mp4">
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="fas fa-play text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-32 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center cursor-pointer" onclick="window.open('{{ $resource->url }}', '_blank')">
                            <div class="text-center">
                                <i class="fas fa-file-pdf text-5xl text-blue-500"></i>
                                <p class="text-xs text-gray-600 mt-2">Aperçu non disponible</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Contenu de la carte -->
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 mb-1 line-clamp-2" title="{{ $resource->title }}">
                        {{ $resource->title }}
                    </h3>
                    
                    @if($resource->description)
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2" title="{{ $resource->description }}">
                            {{ $resource->description }}
                        </p>
                    @endif
                    
                    <!-- Métadonnées -->
                    <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
                        <div class="bg-gray-50 p-2 rounded-lg">
                            <span class="text-gray-500 block">Service</span>
                            <span class="font-medium text-gray-700">{{ $resource->service ?? 'Non spécifié' }}</span>
                        </div>
                        <div class="bg-gray-50 p-2 rounded-lg">
                            <span class="text-gray-500 block">Ajouter par</span>
                            <span class="font-medium text-gray-700">{{ $resource->user->name ?? 'Inconnu' }}</span>
                        </div>
                        <div class="bg-gray-50 p-2 rounded-lg">
                            <span class="text-gray-500 block">Téléchargements</span>
                            <span class="font-medium text-gray-700 flex items-center gap-1">
                                <i class="fas fa-download text-xs"></i>
                                {{ $resource->download_count }}
                            </span>
                        </div>
                        <div class="bg-gray-50 p-2 rounded-lg">
                            <span class="text-gray-500 block">Ajouté le</span>
                            <span class="font-medium text-gray-700">{{ $resource->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    <!-- Nom du fichier -->
                    <div class="text-xs text-gray-400 truncate mb-3" title="{{ $resource->file_name }}">
                        <i class="fas fa-paperclip mr-1"></i>
                        {{ $resource->file_name }}
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex flex-wrap items-center justify-center gap-1 pt-2 border-t border-gray-100">
                        <!-- 🎬 VOIR LA VIDÉO (modal) -->
                        @if($resource->is_video)
                            <button onclick="openVideoModal('{{ $resource->url }}', '{{ $resource->title }}')"
                                    class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors"
                                    title="Voir la vidéo">
                                <i class="bx bx-play-circle text-lg"></i>
                            </button>
                        @endif
                        
                        <!-- 🖼️ VOIR L'IMAGE (modal) -->
                        @if($resource->is_image)
                            <button onclick="openImageModal('{{ $resource->url }}', '{{ $resource->title }}')"
                                    class="p-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition-colors"
                                    title="Voir l'image">
                                <i class="bx bx-image text-lg"></i>
                            </button>
                        @endif
                        
                        <!-- Ouvrir dans un nouvel onglet -->
                        <a href="{{ $resource->url }}" 
                           target="_blank"
                           class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors"
                           title="Ouvrir dans un nouvel onglet">
                            <i class="bx bx-link"></i>
                        </a>
                        
                        <!-- Télécharger -->
                        <a href="{{ route('resources.download', $resource) }}" 
                           class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors"
                           title="Télécharger">
                            <i class="bx bx-download text-lg"></i>
                        </a>
                        
                        <!-- Modifier (admin/propriétaire) en MODAL -->
                        @if(auth()->user()->role === 'admin' || auth()->user()->id === $resource->user_id)
                        <button onclick="openEditModal({{ $resource->id }})"
                                class="p-2 bg-yellow-400 text-yellow-800 rounded-lg hover:bg-yellow-500 transition-colors"
                                title="Modifier">
                            <i class="bx bx-edit text-lg"></i>
                        </button>
                        
                        <!-- Supprimer -->
                        <button onclick="deleteResource({{ $resource->id }}, this)"
                                class="p-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                                title="Supprimer">
                            <i class="bx bx-trash text-lg"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Aucune ressource disponible</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $resources->links() }}
    </div>
</div>

<!-- 🎬 MODAL VIDÉO -->
<div id="videoModal" class="fixed inset-0 bg-black bg-opacity-95 hidden items-center justify-center z-50" onclick="closeVideoModal()">
    <div class="relative w-full max-w-5xl mx-4" onclick="event.stopPropagation()">
        <button onclick="closeVideoModal()" 
                class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors flex items-center gap-2">
            <i class="fas fa-times text-xl"></i>
            <span>Fermer</span>
        </button>
        
        <div class="bg-black rounded-lg overflow-hidden">
            <video id="modalVideo" controls class="w-full" style="max-height: 80vh;">
                <source src="" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
        </div>
        
        <p id="modalVideoTitle" class="text-white text-center mt-4 text-lg font-medium"></p>
        <p class="text-gray-400 text-center text-sm mt-2">
            <i class="fas fa-info-circle mr-1"></i> Échap pour fermer
        </p>
    </div>
</div>

<!-- 🖼️ MODAL IMAGE -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-95 hidden items-center justify-center z-50" onclick="closeImageModal()">
    <div class="relative max-w-6xl mx-4" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" 
                class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors flex items-center gap-2">
            <i class="fas fa-times text-xl"></i>
            <span>Fermer</span>
        </button>
        
        <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] object-contain rounded-lg">
        <p id="modalImageTitle" class="text-white text-center mt-4 text-lg font-medium"></p>
    </div>
</div>

<!-- 📝 MODAL DE CRÉATION/MODIFICATION -->
<div id="resourceModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50" onclick="closeResourceModal()">
    <div class="relative w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="bg-white rounded-xl shadow-2xl">
            <!-- En-tête -->
            <div class="bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white p-4 rounded-t-xl flex justify-between items-center">
                <h3 id="modalTitle" class="text-xl font-bold">Ajouter une ressource</h3>
                <button onclick="closeResourceModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Formulaire -->
            <form id="resourceForm" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" id="resourceId" name="id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                
                <div class="space-y-4">
                    <!-- Titre -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Titre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" required
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]"></textarea>
                    </div>
                    
                    <!-- Fichier -->
                    <div id="fileUploadSection">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Fichier <span class="text-red-500" id="fileRequired">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-[#8bbdc3] transition-colors">
                            <input type="file" id="file" name="file" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.svg,.webp,.mp4,.webm,.avi,.mov,.mkv,.txt"
                                   class="hidden" onchange="updateFileName(this)">
                            <button type="button" onclick="document.getElementById('file').click()"
                                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Choisir un fichier
                            </button>
                            <p id="file-name" class="text-sm text-gray-500 mt-2">Aucun fichier sélectionné</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Images, Vidéos, Documents. Max 20 Mo</p>
                        @error('file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Fichier actuel (pour modification) -->
                    <div id="currentFileSection" class="hidden bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Fichier actuel :</p>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-file text-gray-400"></i>
                            <span id="currentFileName" class="text-sm text-gray-600"></span>
                        </div>
                    </div>
                    
                    <!-- Catégorie -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie *</label>
                        <select id="category" name="category" required
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                            <option value="procedure">📋 Procédure</option>
                            <option value="outil">🛠️ Outil</option>
                            <option value="fiche_reflexe">⚡ Fiche réflexe</option>
                            <option value="ressource">📄 Ressource</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Thème -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Thème</label>
                        <input type="text" id="theme" name="theme" 
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                    </div>
                    
                    <!-- Service -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Service</label>
                        <input type="text" id="service" name="service" 
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                    </div>
                </div>
                
                <!-- Boutons -->
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeResourceModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#255156] text-white rounded-lg hover:bg-[#1d4144] transition-colors">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 📊 MODAL STATISTIQUES -->
<div id="statsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50" onclick="closeStatsModal()">
    <div class="relative w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="bg-white rounded-xl shadow-2xl">
            <!-- En-tête -->
            <div class="bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white p-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <i class="fas fa-chart-pie"></i>
                    Statistiques des documents
                </h3>
                <button onclick="closeStatsModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Contenu -->
            <div class="p-6 bg-gray-50">
                <!-- Statistiques générales -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">
                    <div class="bg-white p-3 rounded-lg border border-gray-200 text-center">
                        <div class="text-xs text-gray-500 mb-1">Total documents</div>
                        <div class="text-2xl font-bold text-[#255156]">{{ $resources->total() }}</div>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-gray-200 text-center">
                        <div class="text-xs text-gray-500 mb-1">Images</div>
                        <div class="text-2xl font-bold text-purple-600">{{ $stats['images'] }}</div>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-gray-200 text-center">
                        <div class="text-xs text-gray-500 mb-1">Vidéos</div>
                        <div class="text-2xl font-bold text-red-600">{{ $stats['videos'] }}</div>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-gray-200 text-center">
                        <div class="text-xs text-gray-500 mb-1">Documents</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['documents'] }}</div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Graphique par type -->
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-chart-pie text-[#255156] mr-2"></i>
                            Répartition par type
                        </h4>
                        <div style="height: 250px;">
                            <canvas id="typeChart"></canvas>
                        </div>
                    </div>

                    <!-- Graphique par catégorie -->
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-chart-bar text-[#255156] mr-2"></i>
                            Répartition par catégorie
                        </h4>
                        <div style="height: 250px;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Légende -->
                <div class="mt-4 text-xs text-gray-500 text-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    Données mises à jour en temps réel
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ==================== STATISTIQUES ====================

// Données des statistiques
const statsData = {
    types: {
        images: {{ $stats['images'] }},
        videos: {{ $stats['videos'] }},
        documents: {{ $stats['documents'] }}
    },
    categories: {
        procedure: {{ $stats['categories']['procedure'] ?? 0 }},
        outil: {{ $stats['categories']['outil'] ?? 0 }},
        fiche_reflexe: {{ $stats['categories']['fiche_reflexe'] ?? 0 }},
        ressource: {{ $stats['categories']['ressource'] ?? 0 }}
    }
};

// Ouvrir la modal de statistiques
function openStatsModal() {
    document.getElementById('statsModal').classList.remove('hidden');
    document.getElementById('statsModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Initialiser les graphiques
    setTimeout(() => {
        initCharts();
    }, 100);
}

// Fermer la modal
function closeStatsModal() {
    document.getElementById('statsModal').classList.add('hidden');
    document.getElementById('statsModal').classList.remove('flex');
    document.body.style.overflow = '';
}

// Initialiser les graphiques
function initCharts() {
    // Graphique des types
    const typeCtx = document.getElementById('typeChart')?.getContext('2d');
    if(typeCtx) {
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Images', 'Vidéos', 'Documents'],
                datasets: [{
                    data: [statsData.types.images, statsData.types.videos, statsData.types.documents],
                    backgroundColor: ['#8b5cf6', '#ef4444', '#3b82f6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: (context) => `${context.raw} document${context.raw > 1 ? 's' : ''}`
                        }
                    }
                }
            }
        });
    }

    // Graphique des catégories
    const categoryCtx = document.getElementById('categoryChart')?.getContext('2d');
    if(categoryCtx) {
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: ['Procédures', 'Outils', 'Fiches réflexes', 'Ressources'],
                datasets: [{
                    label: 'Nombre de documents',
                    data: [
                        statsData.categories.procedure,
                        statsData.categories.outil,
                        statsData.categories.fiche_reflexe,
                        statsData.categories.ressource
                    ],
                    backgroundColor: ['#255156', '#8bbdc3', '#f59e0b', '#10b981'],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 }
                    }
                }
            }
        });
    }
}

// ==================== AUTRES FONCTIONS EXISTANTES ====================

// Variables globales
let currentEditId = null;

// Ouvrir la modal de création
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Ajouter une ressource';
    document.getElementById('resourceForm').reset();
    document.getElementById('resourceId').value = '';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('fileUploadSection').classList.remove('hidden');
    document.getElementById('currentFileSection').classList.add('hidden');
    document.getElementById('file').required = true;
    document.getElementById('fileRequired').classList.remove('hidden');
    document.getElementById('resourceModal').classList.remove('hidden');
    document.getElementById('resourceModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Nettoyer les anciennes erreurs
    clearValidationErrors();
}

// Ouvrir la modal de modification
function openEditModal(id) {
    currentEditId = id;
    
    // Afficher un indicateur de chargement
    showNotification('info', 'Chargement...');
    
    fetch(`/ressources/${id}/edit`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Modifier la ressource';
            document.getElementById('resourceId').value = data.id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('title').value = data.title || '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('category').value = data.category || 'procedure';
            document.getElementById('theme').value = data.theme || '';
            document.getElementById('service').value = data.service || '';
            
            // Afficher le fichier actuel
            document.getElementById('currentFileName').textContent = data.file_name || 'Aucun fichier';
            document.getElementById('fileUploadSection').classList.add('hidden');
            document.getElementById('currentFileSection').classList.remove('hidden');
            document.getElementById('file').required = false;
            document.getElementById('fileRequired').classList.add('hidden');
            
            document.getElementById('resourceModal').classList.remove('hidden');
            document.getElementById('resourceModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Nettoyer les anciennes erreurs
            clearValidationErrors();
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('error', 'Erreur lors du chargement des données');
        });
}

// Fermer la modal
function closeResourceModal() {
    document.getElementById('resourceModal').classList.add('hidden');
    document.getElementById('resourceModal').classList.remove('flex');
    document.body.style.overflow = '';
    currentEditId = null;
    clearValidationErrors();
}

// Nettoyer les erreurs de validation
function clearValidationErrors() {
    const errorMessages = document.querySelectorAll('.text-red-500.text-xs');
    errorMessages.forEach(el => el.remove());
}

// Mettre à jour le nom du fichier
function updateFileName(input) {
    const fileName = input.files[0] ? input.files[0].name : 'Aucun fichier sélectionné';
    document.getElementById('file-name').textContent = fileName;
}

// Soumettre le formulaire
document.getElementById('resourceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('resourceId').value;
    const method = document.getElementById('formMethod').value;
    
    // Déterminer l'URL
    let url = '/ressources';
    if (method === 'PUT' && id) {
        url = `/ressources/${id}`;
        formData.append('_method', 'PUT');
    }
    
    // Désactiver le bouton pendant l'envoi
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enregistrement...';
    
    // Nettoyer les anciennes erreurs
    clearValidationErrors();
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeResourceModal();
            showNotification('success', method === 'PUT' ? 'Ressource modifiée avec succès' : 'Ressource ajoutée avec succès');
            
            // 🟢 ACTUALISER LA PAGE APRÈS MODIFICATION
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Afficher les erreurs de validation
            if (data.errors) {
                Object.keys(data.errors).forEach(key => {
                    const input = document.getElementById(key);
                    if (input) {
                        const errorDiv = document.createElement('p');
                        errorDiv.className = 'text-red-500 text-xs mt-1';
                        errorDiv.textContent = data.errors[key][0];
                        input.parentNode.appendChild(errorDiv);
                    }
                });
                showNotification('error', 'Veuillez corriger les erreurs du formulaire');
            } else {
                showNotification('error', data.message || 'Erreur lors de l\'enregistrement');
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('error', 'Erreur de connexion');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// ==================== VIDÉO ====================

function openVideoModal(url, title) {
    const modal = document.getElementById('videoModal');
    const video = document.getElementById('modalVideo');
    const source = video.querySelector('source');
    
    source.src = url;
    video.load();
    document.getElementById('modalVideoTitle').textContent = title;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    video.play().catch(e => console.log('Lecture auto bloquée:', e));
}

function closeVideoModal() {
    const modal = document.getElementById('videoModal');
    const video = document.getElementById('modalVideo');
    
    video.pause();
    video.currentTime = 0;
    
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

// ==================== IMAGE ====================

function openImageModal(url, title) {
    const modal = document.getElementById('imageModal');
    const img = document.getElementById('modalImage');
    
    img.src = url;
    document.getElementById('modalImageTitle').textContent = title;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

// ==================== SUPPRESSION ====================

function deleteResource(id, button) {
    if (!confirm('Voulez-vous vraiment supprimer cette ressource ?')) return;
    
    const card = button.closest('.resource-card');
    card.style.opacity = '0.5';
    card.style.backgroundColor = '#fee2e2';
    
    fetch(`/ressources/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Ressource supprimée avec succès');
            
            // 🟢 ACTUALISER LA PAGE APRÈS SUPPRESSION
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            card.style.opacity = '1';
            card.style.backgroundColor = '';
            showNotification('error', 'Erreur lors de la suppression');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        card.style.opacity = '1';
        card.style.backgroundColor = '';
        showNotification('error', 'Erreur de connexion');
    });
}

// ==================== NOTIFICATIONS ====================

function showNotification(type, message) {
    // Supprimer les anciennes notifications
    const oldNotifications = document.querySelectorAll('.notification-toast');
    oldNotifications.forEach(n => n.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-20 right-4 z-50 px-6 py-4 rounded-lg shadow-2xl transform transition-all duration-500 ${
        type === 'success' ? 'bg-gradient-to-r from-green-500 to-green-600' : 
        type === 'error' ? 'bg-gradient-to-r from-red-500 to-red-600' : 
        'bg-gradient-to-r from-blue-500 to-blue-600'
    } text-white`;
    
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100%)';
    
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} text-xl"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 500);
    }, 5000);
}

// ==================== FILTRAGE ====================

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('filterType').value;
    const categoryFilter = document.getElementById('filterCategory').value;
    const sortBy = document.getElementById('sortBy').value;
    
    const cards = Array.from(document.querySelectorAll('.resource-card'));
    const grid = document.getElementById('resourcesGrid');
    
    const visibleCards = cards.filter(card => {
        const title = card.dataset.title;
        const type = card.dataset.type;
        const category = card.dataset.category;
        
        const matchesSearch = searchTerm === '' || title.includes(searchTerm);
        const matchesType = typeFilter === '' || type === typeFilter;
        const matchesCategory = categoryFilter === '' || category === categoryFilter;
        
        return matchesSearch && matchesType && matchesCategory;
    });
    
    visibleCards.sort((a, b) => {
        switch(sortBy) {
            case 'newest': return b.dataset.date - a.dataset.date;
            case 'oldest': return a.dataset.date - b.dataset.date;
            case 'popular': return b.dataset.downloads - a.dataset.downloads;
            default: return 0;
        }
    });
    
    cards.forEach(card => card.style.display = 'none');
    visibleCards.forEach(card => {
        card.style.display = '';
        grid.appendChild(card);
    });
    
    // Message aucun résultat
    const noResults = document.getElementById('noResultsMessage');
    if (visibleCards.length === 0) {
        if (!noResults) {
            const messageDiv = document.createElement('div');
            messageDiv.id = 'noResultsMessage';
            messageDiv.className = 'col-span-full text-center py-12';
            messageDiv.innerHTML = `
                <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Aucune ressource trouvée</p>
            `;
            grid.appendChild(messageDiv);
        }
    } else if (noResults) {
        noResults.remove();
    }
}

// ==================== INITIALISATION ====================

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput')?.addEventListener('input', filterTable);
    document.getElementById('filterType')?.addEventListener('change', filterTable);
    document.getElementById('filterCategory')?.addEventListener('change', filterTable);
    document.getElementById('sortBy')?.addEventListener('change', filterTable);
    
    // Fermeture avec Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeVideoModal();
            closeImageModal();
            closeResourceModal();
            closeStatsModal();
        }
    });
});
</script>

<style>
/* Styles existants */
.resource-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.resource-card:hover {
    border-color: #8bbdc3;
}

/* Animation pour les cartes */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.resource-card {
    animation: fadeIn 0.5s ease-out;
}

/* Pour les images et vidéos */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

#videoModal, #imageModal, #resourceModal, #statsModal {
    animation: fadeIn 0.3s ease;
}

#videoModal > div, #imageModal > div, #resourceModal > div, #statsModal > div {
    animation: scaleIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
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

.resource-card button, .resource-card a {
    transition: all 0.2s ease;
}

.resource-card button:hover, .resource-card a:hover {
    transform: scale(1.1);
}

.pagination {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.pagination .page-link {
    padding: 0.5rem 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    color: #4a5568;
    transition: all 0.2s;
}

.pagination .page-link:hover {
    background-color: #edf2f7;
    border-color: #cbd5e0;
}

.pagination .active .page-link {
    background-color: #255156;
    border-color: #255156;
    color: white;
}

/* Style pour les notifications */
.notification-toast {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    border-left: 4px solid rgba(255, 255, 255, 0.5);
    z-index: 9999;
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
    background: #cbd5e0;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Style pour l'en-tête fixe */
.sticky {
    position: sticky;
    top: 0;
    z-index: 40;
    background-color: #f9fafb;
}

/* Ombre pour l'en-tête fixe quand on scroll */
.sticky.shadow-sm {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Style pour les erreurs de validation */
.text-red-500.text-xs {
    margin-top: 0.25rem;
    font-size: 0.75rem;
    line-height: 1rem;
    color: #ef4444;
}

/* Amélioration du contraste pour les messages */
.bg-gradient-to-r {
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>
@endsection