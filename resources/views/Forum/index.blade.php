@extends('base')

@section('title', 'Forum')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- ========== Header en haut ========== -->
    <div class="rounded-2xl p-3 shadow-xl text-white flex items-center justify-between" style="background: linear-gradient(135deg, #008C95, #59BEC9);">
    <!-- Titre et sous-titre à gauche -->
    <div>
        <h1 class="text-2xl font-bold font-montserrat">Forum communautaire</h1>
        <p class="text-white/90 text-sm">Échanger, signaler et partager avec la communauté</p>
    </div>

    <!-- Boutons à droite -->
    <div class="flex flex-wrap gap-2">
        <button onclick="openNewThreadModal()" class="px-3 py-1 bg-white text-[#008C95] rounded-xl font-semibold shadow hover:scale-105 transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-plus-circle"></i> Nouveau sujet
        </button>

        <a href="{{ route('categories.create') }}" class="px-3 py-1 bg-[#9B7EA4] rounded-xl font-semibold shadow text-white hover:scale-105 transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-folder-plus"></i> Catégorie
        </a>
    </div>
</div>


    <!-- ========== Main content : Colonne gauche / droite ========== -->
    <div class="flex flex-col lg:flex-row gap-6">

        <!-- Colonne gauche : Discussion / sujets récents -->
        <div class="flex-1 space-y-6">

            <!-- Sujets récents -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-[#2D2926] flex items-center gap-2">
                        <i class="fas fa-stream text-[#008C95]"></i> Sujets récents
                    </h2>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-sort"></i>
                        <span>Trier par :</span>
                        <select class="border-none bg-transparent focus:ring-0 text-blue-600 font-medium">
                            <option>Récents</option>
                            <option>Populaires</option>
                            <option>Anciens</option>
                            <option>Cathégories</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($threads as $thread)
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition p-4">
                            <a href="{{ route('forum.show', $thread) }}">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-800 text-lg">Titre: {{ $thread->title }}</h3>
                                        <p class="mt-2 text-xs text-gray-400">Catégorie : <span class="font-medium text-[#008C95]">{{ $thread->category->name }}</span></p>
                                        <p class="text-gray-600 text-sm line-clamp-2">{{ $thread->body }}</p>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $thread->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="flex items-center justify-between mt-2 text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-[#008C95] text-white flex items-center justify-center font-bold text-sm">
                                            {{ strtoupper(substr($thread->user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm">{{ $thread->user->name }}</span>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <!-- Réactions -->
                                        <form action="{{ route('forum.react', $thread) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            <button name="reaction" value="like" class="text-gray-500 hover:text-red-500 transition">❤️ {{ $thread->likes() ?? 0 }}</button>
                                            <button name="reaction" value="dislike" class="text-gray-500 hover:text-gray-900 transition">👎 {{ $thread->dislikes() ?? 0 }}</button>
                                        </form>

                                        <!-- Commentaires -->
                                        <span class="flex items-center gap-1 text-gray-500">
                                            <i class="far fa-comment"></i> {{ $thread->commentsCount() ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-comments text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun sujet pour le moment</h3>
                            <p class="text-gray-500 mb-6">Soyez le premier à créer un sujet !</p>
                            <button onclick="openNewThreadModal()"
                                class="px-6 py-3 bg-[#008C95] text-white rounded-xl hover:bg-[#59BEC9] transition font-semibold flex items-center gap-2 justify-center">
                                <i class="fas fa-plus"></i> Créer un sujet
                            </button>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($threads->hasPages())
                    <div class="mt-4">
                        {{ $threads->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Colonne droite : Catégories + Statistiques -->
        <div class="w-full lg:w-80 space-y-6">

            <!-- Catégories -->
            <div class="bg-white rounded-xl shadow p-4 space-y-2">
                <h2 class="font-bold text-[#2D2926] text-sm flex items-center gap-2"><i class="fas fa-tags text-[#008C95]"></i> Catégories</h2>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('categories.index') }}" class="px-3 py-1 bg-[#C4CEC2] text-[#2D2926] rounded-full text-xs font-medium hover:opacity-80">Toutes</a>
                    @foreach($categoriesLimite as $category)
                        <a href="{{ route('forum.index', ['category' => $category->id]) }}" class="px-3 py-1 rounded-full border text-xs font-medium" style="border-color:#008C95;color:#008C95;">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="space-y-2">
                <div class="flex items-center justify-between p-3 bg-white rounded-xl shadow border-l-4 border-[#008C95]">
                    <div>
                        <p class="text-xs text-gray-500">Sujets</p>
                        <p class="font-bold text-lg text-[#2D2926]">{{ $threads->total() }}</p>
                    </div>
                    <i class="fas fa-comments text-[#008C95]"></i>
                </div>
                <div class="flex items-center justify-between p-3 bg-white rounded-xl shadow border-l-4 border-[#9B7EA4]">
                    <div>
                        <p class="text-xs text-gray-500">Catégories</p>
                        <p class="font-bold text-lg text-[#2D2926]">{{ $categories->count() }}</p>
                    </div>
                    <i class="fas fa-tags text-[#9B7EA4]"></i>
                </div>
                <div class="flex items-center justify-between p-3 bg-white rounded-xl shadow border-l-4 border-[#D2B467]">
                    <div>
                        <p class="text-xs text-gray-500">Dernière activité</p>
                        <p class="font-semibold text-[#2D2926]">{{ $threads->isNotEmpty() ? $threads->first()->created_at->diffForHumans() : 'Aucune' }}</p>
                    </div>
                    <i class="fas fa-clock text-[#D2B467]"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal création sujet -->
<div class="modal fade" id="newThreadModal" tabindex="-1" aria-labelledby="newThreadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="newThreadModalLabel">
          <i class="fas fa-plus-circle me-2"></i> Nouveau sujet
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form action="{{ route('forum.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="title" class="form-label">Titre du sujet</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Titre du sujet" required value="{{ old('title') }}">
            @error('title')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="body" class="form-label">Message</label>
            <textarea name="body" id="body" rows="5" class="form-control" placeholder="Développez votre idée..." required>{{ old('body') }}</textarea>
            @error('body')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="category_id" class="form-label">Catégorie</label>
            <select name="category_id" id="category_id" class="form-select" required>
              <option value="">Sélectionnez une catégorie</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            @error('category_id')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Annuler
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane me-1"></i> Publier
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
function openNewThreadModal() {
    new bootstrap.Modal(document.getElementById('newThreadModal')).show();
}

</script>
@endsection
