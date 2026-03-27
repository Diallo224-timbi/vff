@forelse($threads as $thread)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition p-4">
        <a href="{{ route('forum.show', $thread) }}">
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-lg">Titre: {{ $thread->title }}</h3>
                    <p class="mt-2 text-xs text-gray-400">Catégorie : <span class="font-medium text-[#255156]">{{ $thread->category->name }}</span></p>
                    <p class="text-gray-600 text-sm line-clamp-2">{{ $thread->body }}</p>
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
                        <button name="reaction" value="like" class="text-gray-500 hover:text-red-500 transition">❤️ {{ $thread->likes() ?? 0 }}</button>
                        <button name="reaction" value="dislike" class="text-gray-500 hover:text-gray-900 transition">👎 {{ $thread->dislikes() ?? 0 }}</button>
                    </form>

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
        @if(request('search') || currentSearch)
            <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun résultat trouvé</h3>
            <p class="text-gray-500 mb-6">Aucun sujet ne correspond à votre recherche</p>
            <button onclick="clearSearch()" class="px-6 py-3 bg-[#255156] text-white rounded-xl hover:bg-[#1e7c86] transition font-semibold flex items-center gap-2 justify-center">
                <i class="fas fa-arrow-left"></i> Voir tous les sujets
            </button>
        @else
            <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun sujet pour le moment</h3>
            <p class="text-gray-500 mb-6">Soyez le premier à créer un sujet !</p>
            <button onclick="openNewThreadModal()" class="px-6 py-3 bg-[#255156] text-white rounded-xl hover:bg-[#1e7c86] transition font-semibold flex items-center gap-2 justify-center">
                <i class="fas fa-plus"></i> Créer un sujet
            </button>
        @endif
    </div>
@endforelse