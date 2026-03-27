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

        <!-- Colonne gauche -->
        <div class="flex-1 space-y-6">

            <div>
                <!-- Header + filtre -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
                    <h2 class="text-xl font-bold text-[#2D2926] flex items-center gap-2">
                        <i class="fas fa-stream text-[#255156]"></i> Sujets
                        <span id="visibleCount" class="text-sm font-normal text-gray-500"></span>
                    </h2>

                    <div class="flex gap-2 text-sm">
                        <button class="filter-btn px-3 py-1 rounded-xl bg-[#255156] text-white font-medium transition" data-sort="recent">Récents</button>
                        <button class="filter-btn px-3 py-1 rounded-xl bg-gray-200 text-gray-700 font-medium transition" data-sort="oldest">Anciens</button>
                        <button class="filter-btn px-3 py-1 rounded-xl bg-gray-200 text-gray-700 font-medium transition" data-sort="popular">Populaires</button>
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
                             data-category="{{ strtolower($thread->category->name) }}">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <a href="{{ route('forum.show', $thread) }}"><h3 class="thread-title font-bold text-gray-800 text-lg">
                                        {{ $thread->title }}
                                        @if($thread->is_resolved)
                                            <span class="ml-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full">Résolu</span>
                                        @endif
                                    </h3>
                                    <p class="mt-2 text-xs text-gray-400">
                                        Catégorie : 
                                        <span class="thread-category font-medium text-[#255156]">{{ $thread->category->name }}</span>
                                    </p>
                                    <p class="thread-body text-gray-600 text-sm line-clamp-2">
                                        {{ $thread->body }}
                                    </p>
                                    </a>
                                </div>

                                <span class="text-xs text-gray-400">{{ $thread->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="flex items-center justify-between mt-2 text-gray-500">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-bold text-sm" style="background: linear-gradient(135deg, #255156, #1e7c86);">
                                        {{ strtoupper(substr($thread->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm">{{ $thread->user->name }}</span>
                                </div>

                                <div class="flex items-center gap-4">
                                    <form action="{{ route('forum.react', $thread) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <button name="reaction" value="like" class="hover:text-red-500 transition">❤️ {{ $thread->likes() ?? 0 }}</button>
                                        <button name="reaction" value="dislike" class="hover:text-gray-900 transition">👎 {{ $thread->dislikes() ?? 0 }}</button>
                                    </form>

                                    <span class="flex items-center gap-1">
                                        <i class="far fa-comment"></i> {{ $thread->commentsCount() ?? 0 }}
                                    </span>

                                    @if(auth()->id() === $thread->user_id || auth()->user()->role === "admin" || auth()->user()->role === "moderateur")
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
        
        const matchesSearch = searchTerm === '' || 
                              title.includes(searchTerm) || 
                              body.includes(searchTerm) || 
                              category.includes(searchTerm);
        
        if (matchesSearch) {
            thread.style.display = '';
            visibleCount++;
        } else {
            thread.style.display = 'none';
        }
    });
    
    updateSearchResultInfo(visibleCount);
    showNoResultsMessage(visibleCount);
    updateVisibleCount(visibleCount);
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
        if (currentSearch !== '') {
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
            <p class="text-gray-500 mb-6">Aucun sujet ne correspond à "${currentSearch}"</p>
            <button onclick="clearSearch()" class="px-6 py-3 bg-[#255156] text-white rounded-xl hover:bg-[#1e7c86] transition font-semibold flex items-center gap-2 justify-center mx-auto">
                <i class="fas fa-arrow-left"></i> Voir tous les sujets
            </button>
        `;
        threadsContainer.appendChild(noResultsDiv);
    } else if (visibleCount > 0) {
        if (emptyState) emptyState.style.display = '';
    }
}

// 🔍 Recherche dynamique
let searchTimeout;
document.getElementById('search').addEventListener('input', function () {
    clearTimeout(searchTimeout);
    currentSearch = this.value;
    searchTimeout = setTimeout(() => {
        filterThreads();
    }, 300);
});

// 🔄 Filtre
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

// ✅ Marquer comme résolu via checkbox avec changement de couleur immédiat
document.querySelectorAll('.resolve-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        const threadId = this.dataset.threadId;
        const resolved = this.checked;
        const card = this.closest('.thread-item');
        
        // Changement de couleur immédiat pour une meilleure UX
        if (resolved) {
            card.classList.remove('bg-white', 'border-gray-200');
            card.classList.add('bg-green-100', 'border-green-400');
            // Ajouter le badge "Résolu" si pas déjà présent
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
            // Retirer le badge "Résolu"
            const badge = card.querySelector('.resolved-badge');
            if (badge) badge.remove();
        }
        
        // Envoyer la requête au serveur
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
                // Revenir en arrière si erreur
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
            // Revenir en arrière en cas d'erreur
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

// Initialiser les compteurs
document.addEventListener('DOMContentLoaded', function() {
    const totalThreads = document.querySelectorAll('#threadsContainer .thread-item').length;
    updateVisibleCount(totalThreads);
});
</script>

<style>
/* Animation pour les cartes */
.thread-item {
    transition: all 0.3s ease;
}

/* Style pour le champ de recherche */
#search:focus {
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
</style>
@endsection