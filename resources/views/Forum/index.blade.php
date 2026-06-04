@extends('base')

@section('title', 'Forum')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Header avec animation -->
    <div class="rounded-2xl p-6 shadow-xl text-white transform transition-all duration-500 hover:shadow-2xl" style="background: linear-gradient(135deg, #255156, #1e7c86);">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold font-montserrat flex items-center gap-3">
                    <i class="fas fa-comments text-4xl"></i>
                    Forum communautaire
                </h1>
                <p class="text-white/90 text-sm mt-2">Échanger, signaler et partager avec la communauté</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="search" id="search" placeholder="Rechercher un sujet..." class="pl-10 pr-4 py-2 rounded-xl text-black focus:outline-none focus:ring-2 focus:ring-white w-64">
                </div>
                <button onclick="openNewThreadModal()" class="px-4 py-2 bg-white text-[#255156] rounded-xl font-semibold shadow hover:scale-105 transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Nouveau sujet
                </button>
                <a href="{{ route('categories.create') }}" class="px-4 py-2 bg-[#255156] rounded-xl font-semibold shadow text-white hover:scale-105 transition-all duration-300 flex items-center gap-2 border border-white/20">
                    <i class="fas fa-folder-plus"></i> Catégorie
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-[#255156] hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total sujets</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $threads->total() }}</p>
                </div>
                <i class="fas fa-comments text-4xl text-[#255156]/20"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Sujets résolus</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $threads->where('is_resolved', true)->count() }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-green-500/20"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Messages</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $threads->sum(function($t) { return $t->commentsCount() ?? 0; }) }}</p>
                </div>
                <i class="fas fa-reply-all text-4xl text-blue-500/20"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Membres</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
                </div>
                <i class="fas fa-users text-4xl text-purple-500/20"></i>
            </div>
        </div>
    </div>

    <!-- Résultat de recherche info -->
    <div id="searchResultInfo" class="bg-blue-50 border-l-4 border-[#255156] p-4 rounded-r-lg hidden animate-slideDown">
        <div class="flex items-center justify-between">
            <div>
                <i class="fas fa-search text-[#255156] mr-2"></i>
                <span class="text-gray-700">Résultats pour : <strong id="searchQuery"></strong></span>
                <span class="text-gray-500 text-sm ml-2">(<span id="resultCount">0</span> résultats)</span>
            </div>
            <button onclick="clearSearch()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex flex-col lg:flex-row gap-6">

        <!-- Sidebar gauche - Catégories -->
        <div class="lg:w-64 space-y-4">
            <div class="bg-white rounded-xl shadow-sm p-4">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-tags text-[#255156]"></i>
                    Catégories
                </h3>
                <div class="space-y-2" id="categoryFilter">
                    <button class="category-filter-btn w-full text-left px-3 py-2 rounded-lg text-sm transition-all active-filter" data-category="all">
                        <i class="fas fa-th-large mr-2"></i> Tous les sujets
                        <span class="float-right text-gray-400">{{ $threads->total() }}</span>
                    </button>
                    @foreach($categories as $category)
                        <button class="category-filter-btn w-full text-left px-3 py-2 rounded-lg text-sm transition-all hover:bg-gray-100" data-category="{{ strtolower($category->name) }}">
                            <i class="fas fa-folder mr-2"></i> {{ $category->name }}
                            <span class="float-right text-gray-400">{{ $threads->where('category_id', $category->id)->count() }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-sm p-4">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-chart-line text-[#255156]"></i>
                    Tendances
                </h3>
                <div class="space-y-3" id="trendingThreads">
                    @php
                        $trending = $threads->sortByDesc(function($t) {
                            return ($t->likes() ?? 0) + ($t->commentsCount() ?? 0);
                        })->take(5);
                    @endphp
                    @foreach($trending as $trend)
                        <a href="{{ route('forum.show', $trend) }}" class="block hover:bg-white/50 rounded-lg p-2 transition">
                            <p class="text-sm font-medium text-gray-800 line-clamp-1">{{ $trend->title }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                ❤️ {{ $trend->likes() ?? 0 }} 💬 {{ $trend->commentsCount() ?? 0 }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Colonne droite - Contenu principal -->
        <div class="flex-1 space-y-6">

            <div>
                <!-- Header + filtre -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
                    <h2 class="text-xl font-bold text-[#2D2926] flex items-center gap-2">
                        <i class="fas fa-stream text-[#255156]"></i> Sujets
                        <span id="visibleCount" class="text-sm font-normal text-gray-500"></span>
                    </h2>

                    <div class="flex gap-2 text-sm">
                        <button class="filter-btn px-4 py-2 rounded-xl font-medium transition-all duration-300" data-sort="recent">
                            <i class="fas fa-clock mr-1"></i> Récents
                        </button>
                        <button class="filter-btn px-4 py-2 rounded-xl font-medium transition-all duration-300" data-sort="oldest">
                            <i class="fas fa-history mr-1"></i> Anciens
                        </button>
                        <button class="filter-btn px-4 py-2 rounded-xl font-medium transition-all duration-300" data-sort="popular">
                            <i class="fas fa-fire mr-1"></i> Populaires
                        </button>
                    </div>
                </div>

                <!-- Threads container -->
                <div class="grid grid-cols-1 gap-4" id="threadsContainer">
                    @forelse($threads as $thread)
                        <div class="thread-item rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-5 border transform hover:-translate-y-1 
                            @if($thread->is_resolved) bg-gradient-to-r from-green-50 to-white border-green-200 
                            @else bg-white border-gray-200 @endif" 
                             data-created="{{ $thread->created_at }}" 
                             data-likes="{{ $thread->likes() ?? 0 }}"
                             data-thread-id="{{ $thread->id }}"
                             data-title="{{ strtolower($thread->title) }}"
                             data-body="{{ strtolower(strip_tags($thread->body)) }}"
                             data-category="{{ strtolower($thread->category->name) }}">
                            
                            <!-- Badge épinglé si populaire -->
                            @if(($thread->likes() ?? 0) > 10)
                                <div class="absolute -top-2 -left-2">
                                    <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                        <i class="fas fa-star"></i> Populaire
                                    </span>
                                </div>
                            @endif

                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <a href="{{ route('forum.show', $thread) }}" class="hover:no-underline">
                                        <h3 class="thread-title font-bold text-gray-800 text-xl mb-2 hover:text-[#255156] transition-colors">
                                            {{ $thread->title }}
                                            @if($thread->is_resolved)
                                                <span class="ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full">✓ Résolu</span>
                                            @endif
                                        </h3>
                                        <p class="thread-body text-gray-600 text-sm line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($thread->body), 150) }}
                                        </p>
                                    </a>
                                </div>

                                <div class="text-right">
                                    <span class="text-xs text-gray-400 whitespace-nowrap">{{ $thread->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full text-white flex items-center justify-center font-bold text-sm shadow-md" style="background: linear-gradient(135deg, #255156, #1e7c86);">
                                        {{ strtoupper(substr($thread->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">{{ $thread->user->name }}</span>
                                        <span class="text-xs text-gray-400 block">Créateur</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-5">
                                    <div class="flex gap-3">
                                        <form action="{{ route('forum.react', $thread) }}" method="POST" class="inline">
                                            @csrf
                                            <button name="reaction" value="like" class="hover:scale-110 transition-transform">
                                                <span class="text-red-500">❤️</span> <span class="text-sm text-gray-600">{{ $thread->likes() ?? 0 }}</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('forum.react', $thread) }}" method="POST" class="inline">
                                            @csrf
                                            <button name="reaction" value="dislike" class="hover:scale-110 transition-transform">
                                                <span class="text-gray-500">👎</span> <span class="text-sm text-gray-600">{{ $thread->dislikes() ?? 0 }}</span>
                                            </button>
                                        </form>
                                    </div>

                                    <span class="flex items-center gap-1 text-gray-500">
                                        <i class="far fa-comment"></i>
                                        <span class="text-sm">{{ $thread->commentsCount() ?? 0 }}</span>
                                    </span>

                                    @if(auth()->id() === $thread->user_id || (auth()->user()->role ?? '') === "admin" )
                                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                                            <input type="checkbox" class="resolve-checkbox w-4 h-4 cursor-pointer rounded" data-thread-id="{{ $thread->id }}" @if($thread->is_resolved) checked @endif>
                                            <span class="text-xs text-gray-500">Résolu</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-gray-50 rounded-xl" id="emptyState">
                            <i class="fas fa-comments text-gray-300 text-7xl mb-4"></i>
                            <h3 class="text-2xl font-bold text-gray-700 mb-2">Aucun sujet pour le moment</h3>
                            <p class="text-gray-500 mb-6">Soyez le premier à créer un sujet dans le forum !</p>
                            <button onclick="openNewThreadModal()" class="px-6 py-3 bg-[#255156] text-white rounded-xl hover:bg-[#1e7c86] transition-all font-semibold flex items-center gap-2 justify-center mx-auto transform hover:scale-105">
                                <i class="fas fa-plus"></i> Créer un sujet
                            </button>
                        </div>
                    @endforelse
                </div>

                @if($threads->hasPages())
                    <div class="mt-6">
                        {{ $threads->links('vendor.pagination.tailwind') }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- Modal création améliorée -->
<div class="modal fade" id="newThreadModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-xl overflow-hidden">
      <div class="modal-header p-4" style="background: linear-gradient(135deg, #255156, #1e7c86);">
        <h5 class="modal-title text-white text-xl">
            <i class="fas fa-pen-alt mr-2"></i>Créer un nouveau sujet
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('forum.store') }}" method="POST">
        @csrf
        <div class="modal-body p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre du sujet</label>
                <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#255156] focus:border-transparent" placeholder="Ex: Problème avec l'application..." required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                <textarea name="body" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#255156] focus:border-transparent" rows="6" placeholder="Décrivez votre sujet..." required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <select name="category_id" id="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#255156] focus:border-transparent" required>
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer p-4 bg-gray-50">
          <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="px-6 py-2 rounded-lg transition-all transform hover:scale-105 shadow-md" style="background: linear-gradient(135deg, #255156, #1e7c86); color: white;">
            <i class="fas fa-paper-plane mr-2"></i>Publier
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
let currentSearch = '';
let currentCategory = 'all';
let currentSort = 'recent';

function openNewThreadModal() {
    new bootstrap.Modal(document.getElementById('newThreadModal')).show();
}

function clearSearch() {
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.value = '';
        currentSearch = '';
        filterAndSortAndCategorize();
    }
}

function filterAndSortAndCategorize() {
    const searchTerm = currentSearch.toLowerCase();
    const threads = document.querySelectorAll('#threadsContainer .thread-item');
    let visibleCount = 0;
    
    threads.forEach(thread => {
        const title = thread.dataset.title || '';
        const body = thread.dataset.body || '';
        const category = thread.dataset.category || '';
        
        const matchesSearch = searchTerm === '' || 
                              title.includes(searchTerm) || 
                              body.includes(searchTerm) || 
                              category.includes(searchTerm);
        
        const matchesCategory = currentCategory === 'all' || category === currentCategory;
        
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
    sortThreads(currentSort);
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
        if (currentSearch !== '' || currentCategory !== 'all') {
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
        noResultsDiv.className = 'text-center py-16 col-span-full bg-gray-50 rounded-xl';
        noResultsDiv.innerHTML = `
            <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun résultat trouvé</h3>
            <p class="text-gray-500 mb-6">Aucun sujet ne correspond à vos critères</p>
            <button onclick="resetAllFilters()" class="px-6 py-3 bg-[#255156] text-white rounded-xl hover:bg-[#1e7c86] transition-all font-semibold flex items-center gap-2 justify-center mx-auto">
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
    currentCategory = 'all';
    currentSort = 'recent';
    document.getElementById('search').value = '';
    filterAndSortAndCategorize();
    
    // Reset category buttons
    document.querySelectorAll('.category-filter-btn').forEach(btn => {
        btn.classList.remove('active-filter', 'bg-[#255156]', 'text-white');
        btn.classList.add('hover:bg-gray-100');
        if(btn.dataset.category === 'all') {
            btn.classList.add('active-filter', 'bg-[#255156]', 'text-white');
        }
    });
    
    // Reset sort buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-[#255156]', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
        if(btn.dataset.sort === 'recent') {
            btn.classList.add('bg-[#255156]', 'text-white');
            btn.classList.remove('bg-gray-200', 'text-gray-700');
        }
    });
}

function sortThreads(sortType) {
    const container = document.getElementById('threadsContainer');
    const threads = Array.from(container.querySelectorAll('.thread-item:not([style*="display: none"])'));
    if (threads.length <= 1) return;
    
    let sortedThreads;
    if(sortType === 'recent') {
        sortedThreads = threads.sort((a,b) => new Date(b.dataset.created) - new Date(a.dataset.created));
    } else if(sortType === 'oldest') {
        sortedThreads = threads.sort((a,b) => new Date(a.dataset.created) - new Date(b.dataset.created));
    } else if(sortType === 'popular') {
        sortedThreads = threads.sort((a,b) => parseInt(b.dataset.likes) - parseInt(a.dataset.likes));
    }
    
    sortedThreads.forEach(t => container.appendChild(t));
}

// 🔍 Recherche dynamique
let searchTimeout;
document.getElementById('search').addEventListener('input', function () {
    clearTimeout(searchTimeout);
    currentSearch = this.value;
    searchTimeout = setTimeout(() => {
        filterAndSortAndCategorize();
    }, 300);
});

// 🏷️ Filtre par catégorie
document.querySelectorAll('.category-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.category-filter-btn').forEach(b => {
            b.classList.remove('active-filter', 'bg-[#255156]', 'text-white');
            b.classList.add('hover:bg-gray-100');
        });
        this.classList.add('active-filter', 'bg-[#255156]', 'text-white');
        this.classList.remove('hover:bg-gray-100');
        
        currentCategory = this.dataset.category;
        filterAndSortAndCategorize();
    });
});

// 🔄 Filtre de tri
const filterButtons = document.querySelectorAll('.filter-btn');

filterButtons.forEach(btn => {
    btn.addEventListener('click', function () {
        filterButtons.forEach(b => {
            b.classList.remove('bg-[#255156]', 'text-white');
            b.classList.add('bg-gray-200', 'text-gray-700');
        });
        this.classList.add('bg-[#255156]', 'text-white');
        this.classList.remove('bg-gray-200', 'text-gray-700');

        currentSort = this.getAttribute('data-sort');
        sortThreads(currentSort);
    });
});

// Active le tri par défaut
document.querySelector('.filter-btn[data-sort="recent"]').click();

// ✅ Marquer comme résolu avec animation
document.querySelectorAll('.resolve-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        const threadId = this.dataset.threadId;
        const resolved = this.checked;
        const card = this.closest('.thread-item');
        
        if (resolved) {
            card.classList.remove('bg-white', 'border-gray-200');
            card.classList.add('bg-gradient-to-r', 'from-green-50', 'to-white', 'border-green-200');
            const titleDiv = card.querySelector('.thread-title');
            if (titleDiv && !titleDiv.querySelector('.resolved-badge')) {
                const badge = document.createElement('span');
                badge.className = 'ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full resolved-badge';
                badge.innerHTML = '✓ Résolu';
                titleDiv.appendChild(badge);
            }
        } else {
            card.classList.remove('bg-gradient-to-r', 'from-green-50', 'to-white', 'border-green-200');
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
                    card.classList.remove('bg-gradient-to-r', 'from-green-50', 'to-white', 'border-green-200');
                    card.classList.add('bg-white', 'border-gray-200');
                    const badge = card.querySelector('.resolved-badge');
                    if (badge) badge.remove();
                } else {
                    card.classList.remove('bg-white', 'border-gray-200');
                    card.classList.add('bg-gradient-to-r', 'from-green-50', 'to-white', 'border-green-200');
                    const titleDiv = card.querySelector('.thread-title');
                    if (titleDiv && !titleDiv.querySelector('.resolved-badge')) {
                        const badge = document.createElement('span');
                        badge.className = 'ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full resolved-badge';
                        badge.innerHTML = '✓ Résolu';
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
                card.classList.remove('bg-gradient-to-r', 'from-green-50', 'to-white', 'border-green-200');
                card.classList.add('bg-white', 'border-gray-200');
                const badge = card.querySelector('.resolved-badge');
                if (badge) badge.remove();
            } else {
                card.classList.remove('bg-white', 'border-gray-200');
                card.classList.add('bg-gradient-to-r', 'from-green-50', 'to-white', 'border-green-200');
                const titleDiv = card.querySelector('.thread-title');
                if (titleDiv && !titleDiv.querySelector('.resolved-badge')) {
                    const badge = document.createElement('span');
                    badge.className = 'ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full resolved-badge';
                    badge.innerHTML = '✓ Résolu';
                    titleDiv.appendChild(badge);
                }
            }
            this.checked = !resolved;
            alert('Erreur de connexion');
        });
    });
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    const totalThreads = document.querySelectorAll('#threadsContainer .thread-item').length;
    if(totalThreads > 0) {
        updateVisibleCount(totalThreads);
    }
});
</script>

<style>
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideDown {
    animation: slideDown 0.3s ease-out;
}

.thread-item {
    transition: all 0.3s ease;
    position: relative;
}

.thread-item:hover {
    transform: translateY(-2px);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.filter-btn, .category-filter-btn {
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-btn:hover, .category-filter-btn:hover {
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

.active-filter {
    background: linear-gradient(135deg, #255156, #1e7c86);
    color: white;
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
    background: #255156;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #1e7c86;
}
</style>
@endsection