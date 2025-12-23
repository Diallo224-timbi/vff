@extends('base')

@section('title', 'Forum')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Header avec fond dégradé -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl p-6 md:p-8 mb-8 shadow-xl">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-white">
                <h1 class="text-3xl md:text-4xl font-bold mb-2">Forum Communautaire</h1>
                <p class="text-blue-100 text-lg">Partagez, discutez et échangez avec la communauté</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button onclick="openNewThreadModal()"
                   class="flex items-center gap-2 px-5 py-3 bg-white text-blue-600 rounded-xl hover:bg-blue-50 shadow-lg hover:shadow-xl transition-all duration-300 font-semibold group">
                   <i class="fas fa-plus-circle text-lg"></i>
                   Nouveau Sujet
                </button>
                <a href="{{ route('categories.create') }}" 
                   class="flex items-center gap-2 px-5 py-3 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 shadow-lg hover:shadow-xl transition-all duration-300 font-semibold group">
                   <i class="fas fa-folder-plus text-lg"></i>
                   Nouvelle Catégorie
                </a>
            </div>
        </div>
    </div>

    <!-- Catégories - Navigation stylisée -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <i class="fas fa-tags text-blue-500 text-xl"></i>
            <h2 class="text-xl font-bold text-gray-800">Catégories</h2>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('forum.index') }}" 
               class="flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-full transition-all duration-300 group">
               <i class="fas fa-layer-group text-gray-600"></i>
               <span class="font-medium">Toutes</span>
            </a>
            @foreach($categories as $category)
                <a href="{{ route('forum.index', ['category' => $category->id]) }}" 
                   class="flex items-center gap-2 px-4 py-2.5 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-full transition-all duration-300 group">
                   <i class="fas fa-folder text-blue-500"></i>
                   <span class="font-medium text-blue-700">{{ $category->name }}</span>
                   <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">
                       {{ $category->threads_count ?? 0 }}
                   </span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Sujets actifs</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $threads->total() }}</p>
                </div>
                <i class="fas fa-comments text-blue-500 text-2xl"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-5 border border-emerald-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Catégories</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $categories->count() }}</p>
                </div>
                <i class="fas fa-tags text-emerald-500 text-2xl"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-5 border border-purple-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Dernière activité</p>
                    <p class="text-lg font-bold text-gray-800">
                        @if($threads->isNotEmpty())
                            {{ $threads->first()->created_at->diffForHumans() }}
                        @else
                            Aucune
                        @endif
                    </p>
                </div>
                <i class="fas fa-clock text-purple-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Message de succès stylisé -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl flex items-center gap-3 animate-fade-in">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Liste des sujets - Cards modernes -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-stream text-blue-500 text-xl"></i>
                <h2 class="text-xl font-bold text-gray-800">Sujets récents</h2>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <i class="fas fa-sort"></i>
                <span>Trier par :</span>
                <select class="border-none bg-transparent focus:ring-0 text-blue-600 font-medium">
                    <option>Récents</option>
                    <option>Populaires</option>
                    <option>Anciens</option>
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($threads as $thread)
                <div class="group bg-white rounded-2xl border border-gray-200 hover:border-blue-300 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden">
                    <a href="{{ route('forum.show', $thread) }}" class="block">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        @if($thread->category)
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                                {{ $thread->category->name }}
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-500 flex items-center gap-1">
                                            <i class="far fa-clock"></i>
                                            {{ $thread->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300 mb-3">
                                        {{ $thread->title }}
                                    </h3>
                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $thread->body }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($thread->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $thread->user->name }}</p>
                                        <p class="text-xs text-gray-500">Auteur</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1 text-gray-500 hover:text-blue-500 transition-colors">
                                        <i class="far fa-comment"></i>
                                        <span class="font-medium">{{ $thread->replies_count ?? 0 }}</span>
                                    </span>
                                    <span class="flex items-center gap-1 text-gray-500 hover:text-red-500 transition-colors">
                                        <i class="far fa-heart"></i>
                                        <span class="font-medium">{{ $thread->likes_count ?? 0 }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-2 text-center py-12">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-comments text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun sujet pour le moment</h3>
                        <p class="text-gray-500 mb-6">Soyez le premier à créer un sujet de discussion !</p>
                        <button onclick="openNewThreadModal()"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all duration-300 font-semibold">
                           <i class="fas fa-plus"></i>
                           Créer le premier sujet
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination stylisée -->
    @if($threads->hasPages())
        <div class="mt-8">
            {{ $threads->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>

<!-- MODAL CREER UN SUJET - Design amélioré -->
<div class="modal fade fixed inset-0 z-50 overflow-y-auto" id="newThreadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered max-w-2xl">
        <div class="modal-content bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="modal-header bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-plus-circle text-2xl"></i>
                        <h5 class="modal-title text-2xl font-bold">Créer un nouveau sujet</h5>
                    </div>
                    <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors duration-300" 
                            data-bs-dismiss="modal" aria-label="Close">
                        &times;
                    </button>
                </div>
                <p class="text-blue-100 mt-2">Partagez vos idées avec la communauté</p>
            </div>
            
            <form action="{{ route('forum.store') }}" method="POST" class="modal-body p-6 space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label for="title" class="flex items-center gap-2 text-gray-700 font-semibold text-lg">
                        <i class="fas fa-heading text-blue-500"></i>
                        Titre du sujet
                    </label>
                    <input type="text" name="title" id="title" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-gray-700 placeholder-gray-400"
                           placeholder="Donnez un titre clair à votre sujet"
                           required>
                </div>
                
                <div class="space-y-2">
                    <label for="body" class="flex items-center gap-2 text-gray-700 font-semibold text-lg">
                        <i class="fas fa-comment-dots text-blue-500"></i>
                        Contenu du message
                    </label>
                    <textarea name="body" id="body" rows="6" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-gray-700 placeholder-gray-400 resize-none"
                              placeholder="Développez votre idée ici... Soyez clair et précis !"
                              required></textarea>
                </div>
                
                <div class="space-y-2">
                    <label for="category_id" class="flex items-center gap-2 text-gray-700 font-semibold text-lg">
                        <i class="fas fa-folder text-blue-500"></i>
                        Catégorie
                    </label>
                    <div class="relative">
                        <select name="category_id" id="category_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-gray-700 appearance-none bg-white">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Choisissez la catégorie la plus appropriée pour votre sujet</p>
                </div>
            </form>
            
            <div class="modal-footer bg-gray-50 p-6 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <button type="button" 
                            class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-semibold flex items-center justify-center gap-2"
                            data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        Annuler
                    </button>
                    <button type="submit" form="newThreadModal" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 font-semibold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                        <i class="fas fa-paper-plane"></i>
                        Publier le sujet
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript pour ouvrir la modale -->
<script>
function openNewThreadModal() {
    const modal = new bootstrap.Modal(document.getElementById('newThreadModal'));
    modal.show();
}
</script>

<!-- Styles personnalisés -->
<style>
/* Animation d'entrée */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

/* Style pour les selects personnalisés */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

/* Effet de hover sur les cartes */
.group:hover .card-hover-effect {
    transform: translateY(-5px);
}

/* Style pour la modale */
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

/* Responsive design amélioré */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 1rem;
        max-width: calc(100% - 2rem);
    }
    
    .modal-content {
        border-radius: 1rem;
    }
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>

<!-- Ajout de Font Awesome si pas déjà inclus -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@endsection