@extends('base')

@section('title', 'Forum')

@section('content')
<div class="max-w-10xl mx-auto px-0 sm:px-6 lg:px-4 py-2 space-y-2">
    <!-- message de succès avec fermeture -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Succès !</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    <!-- Header -->
    <div class="rounded-2xl p-3 shadow-xl text-white d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3"
     style="background: linear-gradient(135deg, #255156, #1e7c86);">

        <!-- Titre + rappel -->
        <div class="grow">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-comments me-2"></i>
                <h6 class="mb-0 fw-bold">Forum professionnel</h6>
            </div>
            <div class="bg-white text-dark rounded p-2 small">
                <i class="fas fa-info-circle text-primary me-1"></i>
                <strong>Rappel :</strong>
                Ce forum est un espace d'échange entre professionnels. Merci de privilégier des discussions respectueuses et conformes à la charte.<br> Aucune information permettant d'identifier une victime ne doit être publiée.
            </div>
        </div>
         <!-- Actions -->
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <input
                type="search"
                id="search"
                placeholder="Rechercher..."
                class="form-control form-control-sm"
                style="width: 180px;"
            >
            <button
                onclick="openNewThreadModal()"
                class="btn btn-light btn-sm fw-semibold">
                <i class="fas fa-plus-circle me-1"></i>
                Nouveau sujet
            </button>
            <a href="{{ route('categories.index') }}"
            class="btn btn-outline-light btn-sm fw-semibold">
                <i class="fas fa-folder-plus me-1"></i>
                Catégories
            </a>
        </div>
    </div>
    <!-- Résultat de recherche info -->
    <div id="searchResultInfo" class="bg-blue-50 border-l-4 border-[#255156] p-4 rounded-r-lg hidden">
        <div class="flex items-center justify-between">
            <div>
                <i class="fas fa-search text-[#255156] mr-2"></i>
                <span class="text-gray-700">Résultats pour : <strong id="searchQuery"></strong></span>
                <span class="text-gray-500 text-sm ml-2">(<span id="resultCount">0</span> résultats)</span>
            </div>
            <button onclick="clearSearch()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- Main content -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar gauche - Liste des catégories -->
        <div class="lg:w-80 space-y-4">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="p-4" style="background: linear-gradient(135deg, #255156, #1e7c86);">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <i class="fas fa-folder-tree"></i>
                        Catégories
                    </h3>
                    <p class="text-white/80 text-xs mt-1">Filtrer les sujets par catégorie</p>
                </div> 
                <!-- Barre de recherche des catégories -->
                <div class="p-3 border-b border-gray-200 bg-gray-50">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="categorySearch" placeholder="Rechercher une catégorie..." 
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        <button id="clearCategorySearch" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                            <i class="fas fa-times-circle text-sm"></i>
                        </button>
                    </div>
                </div>            
                <!-- Liste des catégories -->
                <div class="max-h-96 overflow-y-auto" id="categoriesList">
                    <div class="category-item border-b border-gray-100 hover:bg-gray-50 transition cursor-pointer" data-category-name="all" data-category-id="all">
                        <div class="p-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-[#255156] to-[#1e7c86] flex items-center justify-center">
                                    <i class="fas fa-th-large text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Tous les sujets</p>
                                    <p class="text-xs text-gray-500">{{ $threads->total() }} sujets</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                        </div>
                    </div>   
                    @foreach($categories as $category)
                        @php
                            $categoryThreadCount = $threads->where('category_id', $category->id)->count();
                        @endphp
                        <div class="category-item border-b border-gray-100 hover:bg-gray-50 transition cursor-pointer" 
                             data-category-name="{{ strtolower($category->name) }}" 
                             data-category-id="{{ $category->id }}"
                             data-category-display="{{ $category->name }}">
                            <div class="p-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <i class="fas fa-folder text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $category->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $categoryThreadCount }} sujet(s)</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($category->description)
                                        <div class="group relative">
                                            <i class="fas fa-info-circle text-gray-400 text-xs cursor-help" title="{{ $category->description }}"></i> 
                                        </div>
                                    @endif
                                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div> 
                <div id="noCategoryResult" class="hidden p-4 text-center text-gray-500">
                    <i class="fas fa-search text-3xl mb-2 text-gray-300"></i>
                    <p class="text-sm">Aucune catégorie trouvée</p>
                </div>
                <div class="p-3 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between text-xs text-gray-600">
                        <a href="{{ route('categories.index') }}" class="hover:underline">Voir toutes les catégories</a>
                        <span>{{ $categories->count() }} catégories</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Colonne droite - Liste des sujets -->
        <div class="flex-1 space-y-6">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
                    <h2 class="text-xl font-bold text-[#2D2926] flex items-center gap-2">
                        <i class="fas fa-stream text-[#255156]"></i> Sujets
                        <span id="visibleCount" class="text-sm font-normal text-gray-500"></span>
                        <span id="selectedCategoryBadge" class="hidden ml-2 text-xs bg-[#255156] text-white px-2 py-1 rounded-full"></span>
                    </h2>
                    <div class="flex gap-2 text-sm">
                        <button class="filter-btn px-3 py-1 rounded-xl bg-[#255156] text-white font-medium transition" data-sort="recent">
                            <i class="fas fa-clock"></i> Récents
                        </button>
                        <button class="filter-btn px-3 py-1 rounded-xl bg-gray-200 text-gray-700 font-medium transition" data-sort="oldest">
                            <i class="fas fa-history"></i> Anciens
                        </button>
                        <button class="filter-btn px-3 py-1 rounded-xl bg-gray-200 text-gray-700 font-medium transition" data-sort="popular">
                            <i class="fas fa-fire"></i> Populaires
                        </button>
                    </div>
                </div>
                <!-- Threads container -->
                <div class="grid grid-cols-1 gap-4" id="threadsContainer">
                    @forelse($threads as $thread)
                        <div class="thread-item rounded-xl shadow-sm hover:shadow-md transition p-4 border 
                            @if($thread->is_resolved) bg-green-100 border-green-400 
                            @else bg-white border-gray-200 @endif" 
                             data-created="{{ $thread->created_at }}" 
                             data-comments="{{ $thread->commentsCount() ?? 0 }}"
                             data-thread-id="{{ $thread->id }}"
                             data-title="{{ strtolower($thread->title) }}"
                             data-body="{{ strtolower(strip_tags($thread->body)) }}"
                             data-category="{{ strtolower($thread->category->name) }}"
                             data-category-id="{{ $thread->category_id }}"
                             data-thread-title="{{ addslashes($thread->title) }}"
                             data-thread-body="{{ addslashes(strip_tags($thread->body)) }}"
                             data-category-id-edit="{{ $thread->category_id }}">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('forum.show', $thread) }}" class="flex-1">
                                            <h3 class="thread-title font-bold text-gray-800 text-lg">
                                                {{ $thread->title }}
                                                @if($thread->is_resolved)
                                                    <span class="ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full">Résolu</span>
                                                @endif
                                            </h3>
                                        </a>
                                        <!-- Boutons Modifier et Supprimer - visibles uniquement pour le créateur et l'admin -->
                                        @if(auth()->id() === $thread->user_id || (auth()->user()->role ?? '') === 'admin')
                                            <div class="flex gap-1">
                                                <button type="button" 
                                                        class="edit-thread-btn p-1.5 rounded-lg transition-all duration-300 hover:bg-[#255156] hover:text-white group/tooltip relative"
                                                        style="background: rgba(37, 81, 86, 0.1); color: #255156;"
                                                        data-thread-id="{{ $thread->id }}"
                                                        data-thread-title="{{ addslashes($thread->title) }}"
                                                        data-thread-body="{{ addslashes($thread->body) }}"
                                                        data-category-id="{{ $thread->category_id }}"
                                                        title="Modifier le sujet">
                                                    <i class="fas fa-edit text-sm"></i>
                                                    <span class="absolute top-full right-0 mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover/tooltip:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">Modifier</span>
                                                </button>
                                                <button type="button" 
                                                        class="delete-thread-btn p-1.5 rounded-lg transition-all duration-300 hover:bg-red-600 hover:text-white group/tooltip relative"
                                                        style="background: rgba(199, 150, 116, 0.1); color: #C79674;"
                                                        data-thread-id="{{ $thread->id }}"
                                                        data-thread-title="{{ addslashes($thread->title) }}"
                                                        title="Supprimer le sujet">
                                                    <i class="fas fa-trash text-sm"></i>
                                                    <span class="absolute top-full right-0 mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover/tooltip:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">Supprimer</span>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-xs text-gray-400">
                                        Catégorie : 
                                        <span class="thread-category font-medium text-[#255156]">{{ $thread->category->name }}</span>
                                    </p>
                                    <p class="thread-body text-gray-600 text-sm line-clamp-2 mt-1">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($thread->body), 100) }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-400 whitespace-nowrap ml-2">{{ $thread->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center justify-between mt-2 text-gray-500">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-bold text-sm" style="background: linear-gradient(135deg, #255156, #1e7c86);">
                                        {{ strtoupper(substr($thread->user->prenom, 0, 1)) }}
                                    </div>
                                    <a href="{{ route('annuaire.membre') }}">
                                        <span class="text-sm">{{ $thread->user->prenom }} <small>{{ $thread->user->structure->organisme->nom_organisme ??' ' }} {{ $thread->user->structure->organisme->ville  ?? ' ' }} ({{ $thread->user->structure->organisme->code_postal  ?? ' ' }})</small></span>
                                    </a>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1">
                                        <i class="far fa-comment"></i> {{ $thread->commentsCount() ?? 0 }}
                                    </span>

                                    @if(auth()->id() === $thread->user_id || (auth()->user()->role ?? '') === 'admin')
                                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                                            <input type="checkbox" class="resolve-checkbox w-4 h-4 cursor-pointer" data-thread-id="{{ $thread->id }}" @if($thread->is_resolved) checked @endif>
                                            <span class="text-gray-600">Résolu</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12" id="emptyState">
                            <i class="fas fa-comments text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun sujet pour le moment</h3>
                            <p class="text-gray-500 mb-6">Soyez le premier à créer un sujet !</p>
                            <button onclick="openNewThreadModal()" class="px-6 py-3 bg-[#255156] text-white rounded-xl hover:bg-[#1e7c86] transition font-semibold flex items-center gap-2 justify-center mx-auto">
                                <i class="fas fa-plus"></i> Créer un sujet
                            </button>
                        </div>
                    @endforelse
                </div>
                @if($threads->hasPages())
                    <div class="mt-4">
                        {{ $threads->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Modal création -->
<div class="modal fade" id="newThreadModal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, #255156, #1e7c86);">
        <h5 class="modal-title text-white">Nouveau sujet</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('forum.store') }}" method="POST">
        @csrf
        <div class="modal-body">
            <input type="text" name="title" class="form-control mb-3" placeholder="Titre" required>
            <textarea name="body" class="form-control mb-3" placeholder="Message" required></textarea>
            <label for="category_id" class="form-label">Catégorie</label>
            <select name="category_id" id="category_id" class="form-select" required>
              <option value="">Sélectionnez une catégorie</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn px-4 py-2 rounded-lg transition hover:scale-105" style="background: linear-gradient(135deg, #255156, #1e7c86); color: white;">
            Publier
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Formulaire de modification caché -->
<form id="editThreadForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="title" id="edit_thread_title">
    <input type="hidden" name="body" id="edit_thread_body">
    <input type="hidden" name="category_id" id="edit_thread_category_id">
</form>
<!-- Formulaire de suppression caché -->
<form id="deleteThreadForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentSearch = '';
let currentCategoryId = 'all';
let currentCategoryName = '';
function openNewThreadModal() {
    new bootstrap.Modal(document.getElementById('newThreadModal')).show();
}
function clearSearch() {
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.value = '';
        currentSearch = '';
        filterThreads();
    }
}
function filterThreads() {
    const searchTerm = currentSearch.toLowerCase();
    const threads = document.querySelectorAll('#threadsContainer .thread-item');
    let visibleCount = 0;
    
    threads.forEach(thread => {
        const title = thread.dataset.title || '';
        const body = thread.dataset.body || '';
        const category = thread.dataset.category || '';
        const categoryId = thread.dataset.categoryId || '';
        const matchesSearch = searchTerm === '' || 
                              title.includes(searchTerm) || 
                              body.includes(searchTerm) || 
                              category.includes(searchTerm);
        
        const matchesCategory = currentCategoryId === 'all' || categoryId === currentCategoryId;
        
        if (matchesSearch && matchesCategory) {
            thread.style.display = '';
            visibleCount++;
        } else {
            thread.style.display = 'none';
        }
    });
    
    updateSearchResultInfo(visibleCount);
    showNoResultsMessage(visibleCount);
    updateVisibleCount(visibleCount);
    updateSelectedCategoryBadge();
}
function updateSelectedCategoryBadge() {
    const badge = document.getElementById('selectedCategoryBadge');
    if (currentCategoryId !== 'all') {
        badge.classList.remove('hidden');
        badge.innerHTML = '<i class="fas fa-filter"></i> ' + currentCategoryName;
    } else {
        badge.classList.add('hidden');
    }
}
function updateSearchResultInfo(visibleCount) {
    const searchResultInfo = document.getElementById('searchResultInfo');
    const searchQuerySpan = document.getElementById('searchQuery');
    const resultCountSpan = document.getElementById('resultCount');
    
    if (currentSearch !== '') {
        searchResultInfo.classList.remove('hidden');
        searchQuerySpan.textContent = '"' + currentSearch + '"';
        resultCountSpan.textContent = visibleCount;
    } else {
        searchResultInfo.classList.add('hidden');
    }
}
function updateVisibleCount(visibleCount) {
    const visibleCountSpan = document.getElementById('visibleCount');
    const totalThreads = document.querySelectorAll('#threadsContainer .thread-item').length;
    if (visibleCountSpan && totalThreads > 0) {
        if (currentSearch !== '' || currentCategoryId !== 'all') {
            visibleCountSpan.textContent = '(' + visibleCount + '/' + totalThreads + ' affichés)';
        } else {
            visibleCountSpan.textContent = '(' + totalThreads + ' total)';
        }
    }
}
function showNoResultsMessage(visibleCount) {
    const existingNoResults = document.querySelector('#noResultsMessage');
    if (existingNoResults) existingNoResults.remove();
    
    const threadsContainer = document.getElementById('threadsContainer');
    const existingThreads = threadsContainer.querySelectorAll('.thread-item');
    const emptyState = document.querySelector('#emptyState');
    
    if (visibleCount === 0 && existingThreads.length > 0) {
        if (emptyState) emptyState.style.display = 'none';
        
        const noResultsDiv = document.createElement('div');
        noResultsDiv.id = 'noResultsMessage';
        noResultsDiv.className = 'text-center py-12 col-span-full';
        noResultsDiv.innerHTML = `
            <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun résultat trouvé</h3>
            <p class="text-gray-500 mb-6">Aucun sujet ne correspond à vos critères</p>
            <button onclick="resetAllFilters()" class="px-6 py-3 bg-[#255156] text-white rounded-xl hover:bg-[#1e7c86] transition font-semibold flex items-center gap-2 justify-center mx-auto">
                <i class="fas fa-arrow-left"></i> Voir tous les sujets
            </button>
        `;
        threadsContainer.appendChild(noResultsDiv);
    } else if (visibleCount > 0) {
        if (emptyState) emptyState.style.display = '';
    }
}
function resetAllFilters() {
    currentSearch = '';
    currentCategoryId = 'all';
    currentCategoryName = '';
    document.getElementById('search').value = '';
    filterThreads();
    document.querySelectorAll('.category-item').forEach(item => {
        item.classList.remove('active-category', 'bg-[#255156]/10');
        const iconDiv = item.querySelector('.w-8.h-8');
        if (iconDiv && item.dataset.categoryId !== 'all') {
            iconDiv.className = 'w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center';
        }
    });
    const allCategoryItem = document.querySelector('.category-item[data-category-id="all"]');
    if (allCategoryItem) {
        allCategoryItem.classList.add('active-category', 'bg-[#255156]/10');
        const iconDiv = allCategoryItem.querySelector('.w-8.h-8');
        if (iconDiv) {
            iconDiv.className = 'w-8 h-8 rounded-full bg-gradient-to-r from-[#255156] to-[#1e7c86] flex items-center justify-center';
        }
    }
}
// ==================== MODIFICATION D'UN SUJET ====================
document.querySelectorAll('.edit-thread-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault(); 
        const threadId = this.dataset.threadId;
        const currentTitle = this.dataset.threadTitle;
        const currentBody = this.dataset.threadBody;
        const currentCategoryId = this.dataset.categoryId;
        // Récupérer la liste des catégories pour le select
        let categoriesOptions = '';
        @foreach($categories as $category)
            categoriesOptions += `<option value="{{ $category->id }}" ${currentCategoryId == {{ $category->id }} ? 'selected' : ''}>{{ addslashes($category->name) }}</option>`;
        @endforeach
        Swal.fire({
            title: '<i class="fas fa-edit mr-2"></i>Modifier le sujet',
            html: `
                <div class="text-left">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2 text-left">Titre</label>
                        <input type="text" id="swal-thread-title" class="swal2-input w-full" placeholder="Titre du sujet" value="${escapeHtml(currentTitle)}" style="width: 100%; margin: 0;">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2 text-left">Contenu</label>
                        <textarea id="swal-thread-body" class="swal2-textarea w-full" placeholder="Contenu du sujet" rows="6" style="width: 100%; margin: 0; resize: vertical;">${escapeHtml(currentBody)}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 font-semibold mb-2 text-left">Catégorie</label>
                        <select id="swal-thread-category" class="swal2-select w-full" style="width: 100%; margin: 0; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                            ${categoriesOptions}
                        </select>
                    </div>
                </div>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonColor: '#255156',
            cancelButtonColor: '#C79674',
            confirmButtonText: '<i class="fas fa-save mr-2"></i>Enregistrer',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Annuler',
            reverseButtons: true,
            background: '#fff',
            customClass: {
                popup: 'rounded-2xl',
                title: 'text-2xl font-bold',
                confirmButton: 'px-5 py-2.5 rounded-lg font-semibold text-white',
                cancelButton: 'px-5 py-2.5 rounded-lg font-semibold'
            },
            preConfirm: () => {
                const newTitle = document.getElementById('swal-thread-title').value.trim();
                const newBody = document.getElementById('swal-thread-body').value.trim();
                const newCategoryId = document.getElementById('swal-thread-category').value;
                
                if (!newTitle) {
                    Swal.showValidationMessage('Le titre est requis');
                    return false;
                }
                
                if (newTitle.length < 3) {
                    Swal.showValidationMessage('Le titre doit contenir au moins 3 caractères');
                    return false;
                }
                
                if (!newBody) {
                    Swal.showValidationMessage('Le contenu est requis');
                    return false;
                }
                
                if (newBody.length < 10) {
                    Swal.showValidationMessage('Le contenu doit contenir au moins 10 caractères');
                    return false;
                }
                
                if (!newCategoryId) {
                    Swal.showValidationMessage('Veuillez sélectionner une catégorie');
                    return false;
                }
                
                return { title: newTitle, body: newBody, category_id: newCategoryId };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { title, body, category_id } = result.value;
                
                // Afficher le chargement
                Swal.fire({
                    title: 'Modification en cours...',
                    html: 'Veuillez patienter...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Préparer et soumettre le formulaire
                const form = document.getElementById('editThreadForm');
                form.action = `/forum/${threadId}`;
                document.getElementById('edit_thread_title').value = title;
                document.getElementById('edit_thread_body').value = body;
                document.getElementById('edit_thread_category_id').value = category_id;
                
                setTimeout(() => {
                    form.submit();
                }, 300);
            }
        });
    });
});

// ==================== SUPPRESSION D'UN SUJET ====================
document.querySelectorAll('.delete-thread-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const threadId = this.dataset.threadId;
        const threadTitle = this.dataset.threadTitle;
        
        Swal.fire({
            title: '<i class="fas fa-trash mr-2"></i>Supprimer le sujet',
            html: `
                <div class="text-left">
                    <p class="mb-3 text-gray-700">Vous êtes sur le point de supprimer :</p>
                    <div class="bg-gray-100 p-3 rounded-lg mb-3">
                        <p class="font-bold text-lg" style="color: #C79674;">"${escapeHtml(threadTitle)}"</p>
                    </div>
                    <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-400 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-red-700">Attention ! Suppression définitive</p>
                                <p class="text-sm text-red-600 mt-1">
                                    Tous les commentaires associés à ce sujet seront également <strong>définitivement supprimés</strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 text-gray-500 text-sm">
                        <i class="fas fa-ban mr-1"></i> Cette action est irréversible.
                    </p>
                </div>
            `,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#C79674',
            cancelButtonColor: '#255156',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Oui, supprimer définitivement',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Annuler',
            reverseButtons: true,
            background: '#fff',
            customClass: {
                popup: 'rounded-2xl',
                title: 'text-2xl font-bold',
                confirmButton: 'px-5 py-2.5 rounded-lg font-semibold text-white',
                cancelButton: 'px-5 py-2.5 rounded-lg font-semibold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Afficher le chargement
                Swal.fire({
                    title: 'Suppression en cours...',
                    html: 'Veuillez patienter...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Préparer et soumettre le formulaire de suppression
                const form = document.getElementById('deleteThreadForm');
                form.action = `/forum/${threadId}`;
                
                setTimeout(() => {
                    form.submit();
                }, 300);
            }
        });
    });
});

// ==================== FONCTIONS UTILITAIRES ====================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
// Recherche dynamique
let searchTimeout;
var searchInput = document.getElementById('search');
if (searchInput) {
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        currentSearch = this.value;
        searchTimeout = setTimeout(function() {
            filterThreads();
        }, 300);
    });
}
// Recherche catégories
var categorySearchInput = document.getElementById('categorySearch');
var clearCategoryBtn = document.getElementById('clearCategorySearch');

function filterCategories() {
    var searchTerm = categorySearchInput.value.toLowerCase();
    var categories = document.querySelectorAll('#categoriesList .category-item');
    var visibleCount = 0;
    
    categories.forEach(function(category) {
        var categoryName = category.dataset.categoryName || '';
        var categoryDisplay = (category.dataset.categoryDisplay || '').toLowerCase();
        
        var matches = searchTerm === '' || 
                       categoryName.includes(searchTerm) || 
                       categoryDisplay.includes(searchTerm);
        
        if (matches) {
            category.style.display = '';
            visibleCount++;
        } else {
            category.style.display = 'none';
        }
    });
    var noResultDiv = document.getElementById('noCategoryResult');
    if (visibleCount === 0) {
        noResultDiv.classList.remove('hidden');
    } else {
        noResultDiv.classList.add('hidden');
    }
    
    if (searchTerm !== '') {
        clearCategoryBtn.classList.remove('hidden');
    } else {
        clearCategoryBtn.classList.add('hidden');
    }
}
if (categorySearchInput) {
    categorySearchInput.addEventListener('input', filterCategories);
}
if (clearCategoryBtn) {
    clearCategoryBtn.addEventListener('click', function() {
        categorySearchInput.value = '';
        filterCategories();
    });
}
// Filtre par catégorie
document.querySelectorAll('.category-item').forEach(function(categoryItem) {
    categoryItem.addEventListener('click', function() {
        var categoryId = this.dataset.categoryId;
        var categoryName = this.dataset.categoryDisplay || (categoryId === 'all' ? 'Tous les sujets' : (this.querySelector('p.font-semibold') ? this.querySelector('p.font-semibold').textContent : ''));
        
        document.querySelectorAll('.category-item').forEach(function(item) {
            item.classList.remove('active-category', 'bg-[#255156]/10');
            var iconDiv = item.querySelector('.w-8.h-8');
            if (iconDiv && item.dataset.categoryId !== 'all') {
                iconDiv.className = 'w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center';
            }
        });
        
        this.classList.add('active-category', 'bg-[#255156]/10');
        var activeIconDiv = this.querySelector('.w-8.h-8');
        if (activeIconDiv) {
            activeIconDiv.className = 'w-8 h-8 rounded-full bg-gradient-to-r from-[#255156] to-[#1e7c86] flex items-center justify-center';
        }
        
        currentCategoryId = categoryId;
        currentCategoryName = categoryName;
        filterThreads();
        
        var flexContainer = document.querySelector('.flex-1');
        if (flexContainer) {
            flexContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// TRI DES SUJETS
var filterButtons = document.querySelectorAll('.filter-btn');
var threadsContainer = document.getElementById('threadsContainer');

function sortThreads(sortType) {
    var threads = Array.from(threadsContainer.querySelectorAll('.thread-item:not([style*="display: none"])'));
    if (threads.length <= 1) return;
    
    var sortedThreads;
    if(sortType === 'recent') {
        sortedThreads = threads.sort(function(a,b) {
            return new Date(b.dataset.created) - new Date(a.dataset.created);
        });
    } else if(sortType === 'oldest') {
        sortedThreads = threads.sort(function(a,b) {
            return new Date(a.dataset.created) - new Date(b.dataset.created);
        });
    } else if(sortType === 'popular') {
        sortedThreads = threads.sort(function(a,b) {
            return parseInt(b.dataset.comments) - parseInt(a.dataset.comments);
        });
    }
    
    sortedThreads.forEach(function(t) {
        threadsContainer.appendChild(t);
    });
}

filterButtons.forEach(function(btn) {
    btn.addEventListener('click', function() {
        filterButtons.forEach(function(b) {
            b.classList.remove('bg-[#255156]', 'text-white');
            b.classList.add('bg-gray-200', 'text-gray-700');
        });
        this.classList.add('bg-[#255156]', 'text-white');
        this.classList.remove('bg-gray-200', 'text-gray-700');

        var sortType = this.getAttribute('data-sort');
        sortThreads(sortType);
    });
});

// Marquer comme résolu
document.querySelectorAll('.resolve-checkbox').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var threadId = this.dataset.threadId;
        var resolved = this.checked;
        var card = this.closest('.thread-item');
        
        if (resolved) {
            card.classList.remove('bg-white', 'border-gray-200');
            card.classList.add('bg-green-100', 'border-green-400');
            var titleDiv = card.querySelector('.thread-title');
            if (titleDiv && !titleDiv.querySelector('.resolved-badge')) {
                var badge = document.createElement('span');
                badge.className = 'ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full resolved-badge';
                badge.textContent = 'Résolu';
                titleDiv.appendChild(badge);
            }
        } else {
            card.classList.remove('bg-green-100', 'border-green-400');
            card.classList.add('bg-white', 'border-gray-200');
            var badgeEl = card.querySelector('.resolved-badge');
            if (badgeEl) badgeEl.remove();
        }
        
        fetch('/forum/' + threadId + '/resolve', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ resolved: resolved })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if(!data.success) {
                if (resolved) {
                    card.classList.remove('bg-green-100', 'border-green-400');
                    card.classList.add('bg-white', 'border-gray-200');
                    var badgeToRemove = card.querySelector('.resolved-badge');
                    if (badgeToRemove) badgeToRemove.remove();
                } else {
                    card.classList.remove('bg-white', 'border-gray-200');
                    card.classList.add('bg-green-100', 'border-green-400');
                    var titleDivEl = card.querySelector('.thread-title');
                    if (titleDivEl && !titleDivEl.querySelector('.resolved-badge')) {
                        var newBadge = document.createElement('span');
                        newBadge.className = 'ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full resolved-badge';
                        newBadge.textContent = 'Résolu';
                        titleDivEl.appendChild(newBadge);
                    }
                }
                this.checked = !resolved;
                alert('Erreur lors de la mise à jour');
            }
        }.bind(this))
        .catch(function(err) {
            console.error(err);
            if (resolved) {
                card.classList.remove('bg-green-100', 'border-green-400');
                card.classList.add('bg-white', 'border-gray-200');
                var badgeToDel = card.querySelector('.resolved-badge');
                if (badgeToDel) badgeToDel.remove();
            } else {
                card.classList.remove('bg-white', 'border-gray-200');
                card.classList.add('bg-green-100', 'border-green-400');
                var titleDivElement = card.querySelector('.thread-title');
                if (titleDivElement && !titleDivElement.querySelector('.resolved-badge')) {
                    var addBadge = document.createElement('span');
                    addBadge.className = 'ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full resolved-badge';
                    addBadge.textContent = 'Résolu';
                    titleDivElement.appendChild(addBadge);
                }
            }
            this.checked = !resolved;
            alert('Erreur de connexion');
        }.bind(this));
    });
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    var totalThreads = document.querySelectorAll('#threadsContainer .thread-item').length;
    updateVisibleCount(totalThreads);
    
    var allCategory = document.querySelector('.category-item[data-category-id="all"]');
    if (allCategory) {
        allCategory.classList.add('active-category', 'bg-[#255156]/10');
    }
});
</script>

<style>
.thread-item {
    transition: all 0.3s ease;
}

#search:focus, #categorySearch:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(37, 81, 86, 0.3);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.filter-btn {
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-btn:hover {
    transform: translateY(-1px);
}

.modal-header {
    border-bottom: none;
}

.modal-footer {
    border-top: none;
}

.resolve-checkbox {
    accent-color: #255156;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.category-item {
    transition: all 0.2s ease;
}

.category-item.active-category {
    background: rgba(37, 81, 86, 0.1);
    border-left: 3px solid #255156;
}

.category-item.active-category .font-semibold {
    color: #255156;
}

#categoriesList::-webkit-scrollbar {
    width: 5px;
}

#categoriesList::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#categoriesList::-webkit-scrollbar-thumb {
    background: #255156;
    border-radius: 5px;
}

/* Style pour les boutons d'édition et suppression */
.edit-thread-btn, .delete-thread-btn {
    transition: all 0.2s ease;
}

.edit-thread-btn:hover {
    transform: scale(1.1);
    background: #255156 !important;
    color: white !important;
}

.delete-thread-btn:hover {
    transform: scale(1.1);
    background: #dc2626 !important;
    color: white !important;
}

/* Style pour les inputs SweetAlert2 */
.swal2-input, .swal2-textarea, .swal2-select {
    border-radius: 0.5rem !important;
    border: 1px solid #e2e8f0 !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.875rem !important;
}

.swal2-input:focus, .swal2-textarea:focus, .swal2-select:focus {
    border-color: #255156 !important;
    box-shadow: 0 0 0 3px rgba(37, 81, 86, 0.1) !important;
    outline: none !important;
}
</style>
@endsection