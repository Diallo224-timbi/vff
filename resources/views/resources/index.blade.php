@extends('base')

@section('title', 'Espace documentaire')

@section('content')
<div class="container mx-auto px-4 py-4">  
    <!-- En-tête fixe -->
    <div class="sticky top-0 z-40 bg-gray-50 pt-2 pb-2 shadow-sm" style="margin-top: -1px;">
        <!-- En-tête et légende des actions -->
        <div class="mb-3 flex flex-col md:flex-row justify-between items-start md:items-center bg-white rounded-lg shadow p-3">
            <div class="mb-2 md:mb-0">
                <h1 class="text-2xl font-bold text-[#255156]"><i class="fas fa-folder-open mr-2"></i>Espace documentaire</h1>
                <p class="text-xs text-gray-600"><i class=""></i> des ressources professionnelles</p>
            </div>
            
            <!-- BOUTON STATISTIQUES -->
            <button onclick="openStatsModal()" class="bg-[#255156] hover:bg-[#1d4144] text-white px-3 py-1.5 rounded-lg text-sm flex items-center gap-2 transition-colors">
                <i class="fas fa-chart-pie text-xs"></i>
                Statistiques
            </button>
        </div>
        <!-- Barre de recherche et filtres -->
        <div class="bg-white rounded-lg shadow p-3">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Rechercher</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchInput" 
                               placeholder="Titre, description..."
                               class="w-full pl-8 pr-3 py-1.5 text-sm border rounded-lg focus:ring-1 focus:ring-[#8bbdc3]">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Type</label>
                    <select id="filterType" class="w-full px-2 py-1.5 text-sm border rounded-lg focus:ring-1 focus:ring-[#8bbdc3]">
                        <option value="">Tous</option>
                        <option value="image">Images</option>
                        <option value="video">Vidéos</option>
                        <option value="document">Documents</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catégorie</label>
                    <select id="filterCategory" class="w-full px-2 py-1.5 text-sm border rounded-lg focus:ring-1 focus:ring-[#8bbdc3]">
                        <option value="">Toutes</option>
                        <option value="procedure">Procédures</option>
                        <option value="outil">Outils</option>
                        <option value="fiche_reflexe">Fiches réflexes</option>
                        <option value="ressource">Ressources</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex items-center gap-3">
                    <select id="sortBy" class="text-xs border rounded-lg px-2 py-1.5">
                        <option value="newest">Plus récents</option>
                        <option value="oldest">Plus anciens</option>
                        <option value="popular">Plus téléchargés</option>
                    </select>
                </div>
                
                <button onclick="openCreateModal()" 
                        class="bg-[#255156] text-white px-3 py-1.5 rounded-lg text-sm hover:bg-[#1d4144] transition-colors flex items-center gap-2">
                    <i class="fas fa-upload text-xs"></i>
                    Ajouter une ressource
                </button>
            </div>
        </div>
    </div>

    <!-- GRILLE DES RESSOURCES (CARTES COMPACTES) -->
    <div class="mt-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3" id="resourcesGrid">
            @forelse($resources as $resource)
            <div class="resource-card bg-white rounded-lg shadow hover:shadow-md transition-all duration-200 border border-gray-100"
                 data-id="{{ $resource->id }}"
                 data-type="{{ $resource->is_image ? 'image' : ($resource->is_video ? 'video' : 'document') }}"
                 data-category="{{ $resource->category }}"
                 data-service="{{ $resource->service }}"
                 data-date="{{ $resource->created_at->timestamp }}"
                 data-downloads="{{ $resource->download_count }}"
                 data-title="{{ strtolower($resource->title) }}">
                
                <!-- En-tête compact -->
                <div class="p-2 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center gap-1">
                        @if($resource->is_image)
                            <span class="flex items-center gap-1 text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full text-xs">
                                <i class="fas fa-image text-xs"></i>
                                <span>Image</span>
                            </span>
                        @elseif($resource->is_video)
                            <span class="flex items-center gap-1 text-red-600 bg-red-50 px-2 py-0.5 rounded-full text-xs">
                                <i class="fas fa-video text-xs"></i>
                                <span>Vidéo</span>
                            </span>
                        @else
                            <span class="flex items-center gap-1 text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full text-xs">
                                <i class="fas {{ $resource->file_icon }} text-xs"></i>
                                <span>{{ strtoupper($resource->file_type) }}</span>
                            </span>
                        @endif
                    </div>
                    
                    <span class="px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded-full text-[10px]">
                        {{ ucfirst($resource->category) }}
                    </span>
                </div>
                
                <!-- Miniature compacte -->
                <div class="px-2 pt-1 pb-0 flex justify-center">
                    @if($resource->is_image)
                        <div class="w-full h-20 rounded overflow-hidden bg-gray-100 cursor-pointer" onclick="openImageModal('{{ $resource->url }}', '{{ $resource->title }}')">
                            <img src="{{ $resource->url }}" alt="{{ $resource->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                        </div>
                    @elseif($resource->is_video)
                        <div class="w-full h-20 rounded overflow-hidden bg-gray-900 relative cursor-pointer group" onclick="openVideoModal('{{ $resource->url }}', '{{ $resource->title }}')">
                            <video class="w-full h-full object-cover opacity-75">
                                <source src="{{ $resource->url }}" type="video/mp4">
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="fas fa-play text-white text-xs"></i>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-20 rounded bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center cursor-pointer" onclick="window.open('{{ $resource->url }}', '_blank')">
                            <div class="text-center">
                                <i class="fas fa-file-pdf text-3xl text-blue-500"></i>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Contenu compact -->
                <div class="p-2">
                    <h3 class="font-semibold text-gray-800 text-sm mb-0.5 line-clamp-1" title="{{ $resource->title }}">
                        {{ $resource->title }}
                    </h3>
                    
                    @if($resource->description)
                        <p class="text-xs text-gray-500 mb-1 line-clamp-1" title="{{ $resource->description }}">
                            {{ $resource->description }}
                        </p>
                    @endif
                    
                    <!-- Métadonnées compactes -->
                    <div class="grid grid-cols-2 gap-1 mb-1 text-[10px]">
                        <div class="bg-gray-50 p-1 rounded">
                            <span class="text-gray-400 block">Service</span>
                            <span class="font-medium text-gray-700 truncate block">{{ $resource->service ?? 'Non spécifié' }}</span>
                        </div>
                        <div class="bg-gray-50 p-1 rounded">
                            <span class="text-gray-400 block">Ajouté par</span>
                            <span class="font-medium text-gray-700 truncate block">{{ $resource->user->name ?? 'Inconnu' }}</span>
                        </div>
                        <div class="bg-gray-50 p-1 rounded">
                            <span class="text-gray-400 block flex items-center gap-0.5">
                                <i class="fas fa-download text-[8px]"></i> Téléch.
                            </span>
                            <span class="font-medium text-gray-700">{{ $resource->download_count }}</span>
                        </div>
                        <div class="bg-gray-50 p-1 rounded">
                            <span class="text-gray-400 block">Date</span>
                            <span class="font-medium text-gray-700">{{ $resource->created_at->format('d/m/y') }}</span>
                        </div>
                    </div>
                    
                    <!-- Nom du fichier compact -->
                    <div class="text-[9px] text-gray-400 truncate mb-1" title="{{ $resource->file_name }}">
                        <i class="fas fa-paperclip mr-0.5"></i>
                        {{ $resource->file_name }}
                    </div>
                    
                    <!-- Actions compactes -->
                    <div class="flex items-center justify-center gap-1 pt-1 border-t border-gray-100">
                        @if($resource->is_video)
                            <button onclick="openVideoModal('{{ $resource->url }}', '{{ $resource->title }}')"
                                    class="p-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors"
                                    title="Voir la vidéo">
                                <i class="bx bx-play-circle text-sm"></i>
                            </button>
                        @endif
                        
                        @if($resource->is_image)
                            <button onclick="openImageModal('{{ $resource->url }}', '{{ $resource->title }}')"
                                    class="p-1 bg-purple-100 text-purple-600 rounded hover:bg-purple-200 transition-colors"
                                    title="Voir l'image">
                                <i class="bx bx-image text-sm"></i>
                            </button>
                        @endif
                        
                        <a href="{{ $resource->url }}" 
                           target="_blank"
                           class="p-1 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition-colors"
                           title="Ouvrir">
                            <i class="bx bx-link text-sm"></i>
                        </a>
                        
                        <a href="{{ route('resources.download', $resource) }}" 
                           class="p-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition-colors"
                           title="Télécharger">
                            <i class="bx bx-download text-sm"></i>
                        </a>
                        
                        @if(auth()->user()->role === 'admin' || auth()->user()->id === $resource->user_id)
                        <button onclick="openEditModal({{ $resource->id }})"
                                class="p-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition-colors"
                                title="Modifier">
                            <i class="bx bx-edit text-sm"></i>
                        </button>
                        
                        <button onclick="deleteResource({{ $resource->id }}, this)"
                                class="p-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors"
                                title="Supprimer">
                            <i class="bx bx-trash text-sm"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="text-center py-8">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500 text-sm">Aucune ressource disponible</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination compacte -->
    <div class="mt-4 text-sm">
        {{ $resources->links() }}
    </div>
</div>

<!-- Les modales restent identiques -->
<!-- MODAL VIDÉO -->
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

<!-- MODAL IMAGE -->
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

<!-- MODAL DE CRÉATION/MODIFICATION -->
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
            
            <!-- Formulaire (identique) -->
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
                    </div>
                    
                    <!-- Fichier actuel -->
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
                            <option value="procedure">Procédure</option>
                            <option value="outil">Outil</option>
                            <option value="fiche_reflexe">Fiche réflexe</option>
                            <option value="ressource">Ressource</option>
                        </select>
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

<!-- MODAL STATISTIQUES (identique) -->
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

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-chart-pie text-[#255156] mr-2"></i>
                            Répartition par type
                        </h4>
                        <div style="height: 250px;">
                            <canvas id="typeChart"></canvas>
                        </div>
                    </div>

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

function openStatsModal() {
    document.getElementById('statsModal').classList.remove('hidden');
    document.getElementById('statsModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        initCharts();
    }, 100);
}

function closeStatsModal() {
    document.getElementById('statsModal').classList.add('hidden');
    document.getElementById('statsModal').classList.remove('flex');
    document.body.style.overflow = '';
}

function initCharts() {
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

// ==================== GESTION DES MODALES ====================
let currentEditId = null;

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
    clearValidationErrors();
}

function openEditModal(id) {
    currentEditId = id;
    showNotification('info', 'Chargement...');
    
    fetch(`/ressources/${id}/edit`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
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
            
            document.getElementById('currentFileName').textContent = data.file_name || 'Aucun fichier';
            document.getElementById('fileUploadSection').classList.add('hidden');
            document.getElementById('currentFileSection').classList.remove('hidden');
            document.getElementById('file').required = false;
            document.getElementById('fileRequired').classList.add('hidden');
            
            document.getElementById('resourceModal').classList.remove('hidden');
            document.getElementById('resourceModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
            clearValidationErrors();
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('error', 'Erreur lors du chargement');
        });
}

function closeResourceModal() {
    document.getElementById('resourceModal').classList.add('hidden');
    document.getElementById('resourceModal').classList.remove('flex');
    document.body.style.overflow = '';
    currentEditId = null;
    clearValidationErrors();
}

function clearValidationErrors() {
    const errorMessages = document.querySelectorAll('.text-red-500.text-xs');
    errorMessages.forEach(el => el.remove());
}

function updateFileName(input) {
    const fileName = input.files[0] ? input.files[0].name : 'Aucun fichier sélectionné';
    document.getElementById('file-name').textContent = fileName;
}

// Soumission du formulaire
document.getElementById('resourceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('resourceId').value;
    const method = document.getElementById('formMethod').value;
    
    let url = '/ressources';
    if (method === 'PUT' && id) {
        url = `/ressources/${id}`;
        formData.append('_method', 'PUT');
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enregistrement...';
    
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
            showNotification('success', method === 'PUT' ? 'Ressource modifiée' : 'Ressource ajoutée');
            setTimeout(() => window.location.reload(), 1000);
        } else {
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
                showNotification('error', 'Veuillez corriger les erreurs');
            } else {
                showNotification('error', data.message || 'Erreur');
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
    if (!confirm('Supprimer cette ressource ?')) return;
    
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
            showNotification('success', 'Ressource supprimée');
            setTimeout(() => window.location.reload(), 1000);
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
    const oldNotifications = document.querySelectorAll('.notification-toast');
    oldNotifications.forEach(n => n.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-20 right-4 z-50 px-4 py-2 rounded-lg shadow-lg transform transition-all duration-500 ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 
        'bg-blue-600'
    } text-white text-sm`;
    
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100%)';
    
    notification.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
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
    }, 3000);
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
    
    const noResults = document.getElementById('noResultsMessage');
    if (visibleCards.length === 0) {
        if (!noResults) {
            const messageDiv = document.createElement('div');
            messageDiv.id = 'noResultsMessage';
            messageDiv.className = 'col-span-full text-center py-8';
            messageDiv.innerHTML = `
                <i class="fas fa-folder-open text-4xl text-gray-300 mb-2"></i>
                <p class="text-gray-500 text-sm">Aucune ressource trouvée</p>
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
/* Styles compacts */
.resource-card {
    transition: all 0.2s ease;
    border: 1px solid #e5e7eb;
}

.resource-card:hover {
    border-color: #8bbdc3;
    transform: translateY(-1px);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.resource-card {
    animation: fadeIn 0.3s ease-out;
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

#videoModal, #imageModal, #resourceModal, #statsModal {
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scaleIn {
    from {
        transform: scale(0.98);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

#videoModal > div, #imageModal > div, #resourceModal > div, #statsModal > div {
    animation: scaleIn 0.2s ease;
}

.notification-toast {
    box-shadow: 0 5px 15px -3px rgba(0, 0, 0, 0.2);
    border-left: 3px solid rgba(255, 255, 255, 0.5);
    z-index: 9999;
}

::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

.sticky {
    position: sticky;
    top: 0;
    z-index: 40;
    background-color: #f9fafb;
}

.sticky.shadow-sm {
    box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
}

.text-red-500.text-xs {
    margin-top: 0.2rem;
    font-size: 0.7rem;
}
</style>
@endsection