@extends('base')

@section('title', 'Forum')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Header -->
    <div class="rounded-2xl p-3 shadow-xl text-white flex items-center justify-between" style="background: linear-gradient(135deg, #255156, #1e7c86);">
        <div>
            <h1 class="text-2xl font-bold font-montserrat">Forum communautaire</h1>
            <p class="text-white/90 text-sm">Échanger, signaler et partager avec la communauté</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <input type="search" id="search" placeholder=" Rechercher..." class="px-3 py-2 rounded-xl text-black focus:outline-none focus:ring-2 focus:ring-white">
            <button onclick="openNewThreadModal()" class="px-3 py-1 bg-white text-[#255156] rounded-xl font-semibold shadow hover:scale-105 transition flex items-center gap-2 text-sm">
                <i class="fas fa-plus-circle"></i> Nouveau sujet
            </button>
            <a href="{{ route('categories.create') }}" class="px-3 py-1 bg-[#255156] rounded-xl font-semibold shadow text-white hover:scale-105 transition flex items-center gap-2 text-sm">
                <i class="fas fa-folder-plus"></i> Catégorie
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

        <!-- Sidebar gauche - Liste des catégories avec recherche -->
        <div class="lg:w-80 space-y-4">
            <!-- Carte des catégories -->
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
                    <!-- Toutes les catégories -->
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
                                            <i class="fas fa-info-circle text-gray-400 text-xs cursor-help"></i>
                                            <div class="absolute right-0 bottom-full mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded-lg px-2 py-1 whitespace-nowrap z-10">
                                                {{ $category->description }}
                                            </div>
                                        </div>
                                    @endif
                                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Message aucun résultat -->
                <div id="noCategoryResult" class="hidden p-4 text-center text-gray-500">
                    <i class="fas fa-search text-3xl mb-2 text-gray-300"></i>
                    <p class="text-sm">Aucune catégorie trouvée</p>
                </div>
                
                <!-- Stats des catégories -->
                <div class="p-3 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between text-xs text-gray-600">
                        <span>Total catégories</span>
                        <span class="font-semibold">{{ $categories->count() }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Suggestions / Catégories populaires -->
            <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl shadow-sm p-4">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-fire text-orange-500"></i>
                    Catégories actives
                </h3>
                <div class="flex flex-wrap gap-2" id="popularCategories">
                    @foreach($categories->take(5) as $category)
                        @php
                            $categoryThreadCount = $threads->where('category_id', $category->id)->count();
                        @endphp
                        @if($categoryThreadCount > 0)
                            <button class="quick-category-btn px-3 py-1 bg-white rounded-full text-xs font-medium text-gray-700 hover:bg-[#255156] hover:text-white transition shadow-sm" data-category-id="{{ $category->id }}">
                                {{ $category->name }}
                                <span class="ml-1 text-xs">({{ $categoryThreadCount }})</span>
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Colonne droite - Liste des sujets -->
        <div class="flex-1 space-y-6">
            <div>
                <!-- Header + filtre -->
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
                             data-likes="{{ $thread->likes() ?? 0 }}"
                             data-thread-id="{{ $thread->id }}"
                             data-title="{{ strtolower($thread->title) }}"
                             data-body="{{ strtolower(strip_tags($thread->body)) }}"
                             data-category="{{ strtolower($thread->category->name) }}"
                             data-category-id="{{ $thread->category_id }}">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <a href="{{ route('forum.show', $thread) }}">
                                        <h3 class="thread-title font-bold text-gray-800 text-lg">
                                            {{ $thread->title }}
                                            @if($thread->is_resolved)
                                                <span class="ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full">Résolu</span>
                                            @endif
                                        </h3>
                                    </a>
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
                                        {{ strtoupper(substr($thread->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm">{{ $thread->user->name }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1">
                                        <i class="far fa-comment"></i> {{ $thread->commentsCount() ?? 0 }}
                                    </span>

                                    @if(auth()->id() === $thread->user_id || (auth()->user()->role ?? '') === "admin")
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
        badge.innerHTML = `<i class="fas fa-filter"></i> ${currentCategoryName}`;
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
        searchQuerySpan.textContent = `"${currentSearch}"`;
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
            visibleCountSpan.textContent = `(${visibleCount}/${totalThreads} affichés)`;
        } else {
            visibleCountSpan.textContent = `(${totalThreads} total)`;
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
    
    // Reset category active style
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

// 🔍 Recherche dynamique des sujets
let searchTimeout;
document.getElementById('search').addEventListener('input', function () {
    clearTimeout(searchTimeout);
    currentSearch = this.value;
    searchTimeout = setTimeout(() => {
        filterThreads();
    }, 300);
});

// 🔍 Recherche dynamique des catégories
const categorySearchInput = document.getElementById('categorySearch');
const clearCategoryBtn = document.getElementById('clearCategorySearch');

function filterCategories() {
    const searchTerm = categorySearchInput.value.toLowerCase();
    const categories = document.querySelectorAll('#categoriesList .category-item');
    let visibleCount = 0;
    
    categories.forEach(category => {
        const categoryName = category.dataset.categoryName || '';
        const categoryDisplay = (category.dataset.categoryDisplay || '').toLowerCase();
        
        const matches = searchTerm === '' || 
                       categoryName.includes(searchTerm) || 
                       categoryDisplay.includes(searchTerm);
        
        if (matches) {
            category.style.display = '';
            visibleCount++;
        } else {
            category.style.display = 'none';
        }
    });
    
    const noResultDiv = document.getElementById('noCategoryResult');
    if (visibleCount === 0) {
        noResultDiv.classList.remove('hidden');
    } else {
        noResultDiv.classList.add('hidden');
    }
    
    // Afficher/masquer le bouton clear
    if (searchTerm !== '') {
        clearCategoryBtn.classList.remove('hidden');
    } else {
        clearCategoryBtn.classList.add('hidden');
    }
}

categorySearchInput.addEventListener('input', filterCategories);

clearCategoryBtn.addEventListener('click', function() {
    categorySearchInput.value = '';
    filterCategories();
});

// 📁 Filtre par catégorie
document.querySelectorAll('.category-item').forEach(categoryItem => {
    categoryItem.addEventListener('click', function() {
        const categoryId = this.dataset.categoryId;
        const categoryName = this.dataset.categoryDisplay || (categoryId === 'all' ? 'Tous les sujets' : this.querySelector('p.font-semibold')?.textContent || '');
        
        // Mettre à jour l'état actif
        document.querySelectorAll('.category-item').forEach(item => {
            item.classList.remove('active-category', 'bg-[#255156]/10');
            const iconDiv = item.querySelector('.w-8.h-8');
            if (iconDiv && item.dataset.categoryId !== 'all') {
                iconDiv.className = 'w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center';
            }
        });
        
        this.classList.add('active-category', 'bg-[#255156]/10');
        const activeIconDiv = this.querySelector('.w-8.h-8');
        if (activeIconDiv) {
            if (categoryId === 'all') {
                activeIconDiv.className = 'w-8 h-8 rounded-full bg-gradient-to-r from-[#255156] to-[#1e7c86] flex items-center justify-center';
            } else {
                activeIconDiv.className = 'w-8 h-8 rounded-full bg-gradient-to-r from-[#255156] to-[#1e7c86] flex items-center justify-center';
            }
        }
        
        currentCategoryId = categoryId;
        currentCategoryName = categoryName;
        filterThreads();
        
        // Scroll vers le haut de la liste des sujets
        document.querySelector('.flex-1').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});

// 🏷️ Filtre rapide par catégories populaires
document.querySelectorAll('.quick-category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const categoryId = this.dataset.categoryId;
        const categoryItem = document.querySelector(`.category-item[data-category-id="${categoryId}"]`);
        if (categoryItem) {
            categoryItem.click();
        }
    });
});

// 🔄 Filtre de tri
const filterButtons = document.querySelectorAll('.filter-btn');
const threadsContainer = document.getElementById('threadsContainer');

function sortThreads(sortType) {
    const threads = Array.from(threadsContainer.querySelectorAll('.thread-item:not([style*="display: none"])'));
    if (threads.length <= 1) return;
    
    let sortedThreads;
    if(sortType === 'recent') {
        sortedThreads = threads.sort((a,b) => new Date(b.dataset.created) - new Date(a.dataset.created));
    } else if(sortType === 'oldest') {
        sortedThreads = threads.sort((a,b) => new Date(a.dataset.created) - new Date(b.dataset.created));
    } else if(sortType === 'popular') {
        sortedThreads = threads.sort((a,b) => parseInt(b.dataset.likes) - parseInt(a.dataset.likes));
    }
    
    sortedThreads.forEach(t => threadsContainer.appendChild(t));
}

filterButtons.forEach(btn => {
    btn.addEventListener('click', function () {
        filterButtons.forEach(b => {
            b.classList.remove('bg-[#255156]', 'text-white');
            b.classList.add('bg-gray-200', 'text-gray-700');
        });
        this.classList.add('bg-[#255156]', 'text-white');
        this.classList.remove('bg-gray-200', 'text-gray-700');

        const sortType = this.getAttribute('data-sort');
        sortThreads(sortType);
    });
});

// ✅ Marquer comme résolu via checkbox
document.querySelectorAll('.resolve-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        const threadId = this.dataset.threadId;
        const resolved = this.checked;
        const card = this.closest('.thread-item');
        
        if (resolved) {
            card.classList.remove('bg-white', 'border-gray-200');
            card.classList.add('bg-green-100', 'border-green-400');
            const titleDiv = card.querySelector('.thread-title');
            if (titleDiv && !titleDiv.querySelector('.resolved-badge')) {
                const badge = document.createElement('span');
                badge.className = 'ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full resolved-badge';
                badge.textContent = 'Résolu';
                titleDiv.appendChild(badge);
            }
        } else {
            card.classList.remove('bg-green-100', 'border-green-400');
            card.classList.add('bg-white', 'border-gray-200');
            const badge = card.querySelector('.resolved-badge');
            if (badge) badge.remove();
        }
        
        fetch(`/forum/${threadId}/resolve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ resolved })
        })
        .then(res => res.json())
        .then(data => {
            if(!data.success) {
                if (resolved) {
                    card.classList.remove('bg-green-100', 'border-green-400');
                    card.classList.add('bg-white', 'border-gray-200');
                    const badge = card.querySelector('.resolved-badge');
                    if (badge) badge.remove();
                } else {
                    card.classList.remove('bg-white', 'border-gray-200');
                    card.classList.add('bg-green-100', 'border-green-400');
                    const titleDiv = card.querySelector('.thread-title');
                    if (titleDiv && !titleDiv.querySelector('.resolved-badge')) {
                        const badge = document.createElement('span');
                        badge.className = 'ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full resolved-badge';
                        badge.textContent = 'Résolu';
                        titleDiv.appendChild(badge);
                    }
                }
                this.checked = !resolved;
                alert('Erreur lors de la mise à jour');
            }
        })
        .catch(err => {
            console.error(err);
            if (resolved) {
                card.classList.remove('bg-green-100', 'border-green-400');
                card.classList.add('bg-white', 'border-gray-200');
                const badge = card.querySelector('.resolved-badge');
                if (badge) badge.remove();
            } else {
                card.classList.remove('bg-white', 'border-gray-200');
                card.classList.add('bg-green-100', 'border-green-400');
                const titleDiv = card.querySelector('.thread-title');
                if (titleDiv && !titleDiv.querySelector('.resolved-badge')) {
                    const badge = document.createElement('span');
                    badge.className = 'ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full resolved-badge';
                    badge.textContent = 'Résolu';
                    titleDiv.appendChild(badge);
                }
            }
            this.checked = !resolved;
            alert('Erreur de connexion');
        });
    });
});

// Initialiser la catégorie active par défaut
document.addEventListener('DOMContentLoaded', function() {
    const totalThreads = document.querySelectorAll('#threadsContainer .thread-item').length;
    updateVisibleCount(totalThreads);
    
    // Activer "Tous les sujets" par défaut
    const allCategory = document.querySelector('.category-item[data-category-id="all"]');
    if (allCategory) {
        allCategory.classList.add('active-category', 'bg-[#255156]/10');
    }
});
</script>

<style>
/* Animation pour les cartes */
.thread-item {
    transition: all 0.3s ease;
}

/* Style pour le champ de recherche */
#search:focus, #categorySearch:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(37, 81, 86, 0.3);
}

/* Line clamp */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Style pour les filtres */
.filter-btn {
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-btn:hover {
    transform: translateY(-1px);
}

/* Style pour la modale */
.modal-header {
    border-bottom: none;
}

.modal-footer {
    border-top: none;
}

/* Checkbox stylée */
.resolve-checkbox {
    accent-color: #255156;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

/* Style pour les catégories */
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

/* Scrollbar personnalisée pour la liste des catégories */
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

/* Animation pour les cartes de catégories */
.quick-category-btn {
    transition: all 0.2s ease;
}

.quick-category-btn:hover {
    transform: translateY(-2px);
}
</style>
@endsection