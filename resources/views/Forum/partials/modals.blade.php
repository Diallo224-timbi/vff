<!-- MODAL TOUS LES SUJETS -->
<div class="modal fade fixed inset-0 z-50 overflow-y-auto hidden" id="allThreadsModal" tabindex="-1" aria-hidden="true">
    <div class="min-h-screen px-4 text-center">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true"></div>
        <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
        <div class="inline-block w-full max-w-6xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-list-alt text-2xl"></i>
                        <h5 class="text-2xl font-bold">Tous les sujets</h5>
                    </div>
                    <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors duration-300" 
                            onclick="closeAllThreadsModal()" aria-label="Close">
                        &times;
                    </button>
                </div>
                <p class="text-blue-100 mt-2">{{ $threads->total() }} sujets trouvés</p>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réponses</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($threads as $thread)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('forum.show', $thread) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ Str::limit($thread->title, 50) }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($thread->category)
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                                {{ $thread->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">Aucune catégorie</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $thread->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center gap-1">
                                            <i class="far fa-comment text-gray-400"></i>
                                            <span>{{ $thread->replies_count ?? 0 }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $thread->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-comments text-gray-300 text-3xl mb-2"></i>
                                        <p class="text-lg">Aucun sujet trouvé</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($threads->hasPages())
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        {{ $threads->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
            <div class="bg-gray-50 p-6 border-t border-gray-200">
                <button type="button" 
                        onclick="closeAllThreadsModal()"
                        class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-300 font-semibold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                    <i class="fas fa-times"></i>
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CREER UN SUJET -->
<div id="newThreadModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-plus-circle text-2xl"></i>
                        <h5 class="text-2xl font-bold">Créer un nouveau sujet</h5>
                    </div>
                    <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors duration-300" 
                            onclick="closeNewThreadModal()" aria-label="Close">
                        &times;
                    </button>
                </div>
                <p class="text-blue-100 mt-2">Partagez vos idées avec la communauté</p>
            </div>
            <form action="{{ route('forum.store') }}" method="POST" id="threadForm">
                @csrf
                <div class="p-6 space-y-6">
                    <div class="space-y-2">
                        <label for="title" class="flex items-center gap-2 text-gray-700 font-semibold text-lg">
                            <i class="fas fa-heading text-blue-500"></i>
                            Titre du sujet
                        </label>
                        <input type="text" name="title" id="title" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-gray-700 placeholder-gray-400" placeholder="Donnez un titre clair à votre sujet" required value="{{ old('title') }}">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="body" class="flex items-center gap-2 text-gray-700 font-semibold text-lg">
                            <i class="fas fa-comment-dots text-blue-500"></i>
                            Contenu du message
                        </label>
                        <textarea name="body" id="body" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-gray-700 placeholder-gray-400 resize-none" placeholder="Développez votre idée ici..." required>{{ old('body') }}</textarea>
                        @error('body')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="category_id" class="flex items-center gap-2 text-gray-700 font-semibold text-lg">
                            <i class="fas fa-folder text-blue-500"></i>
                            Catégorie
                        </label>
                        <div class="relative">
                            <select name="category_id" id="category_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-gray-700 appearance-none bg-white">
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="bg-gray-50 p-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-3 w-full">
                        <button type="button" onclick="closeNewThreadModal()" class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-semibold flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            Annuler
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 font-semibold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                            <i class="fas fa-paper-plane"></i>
                            Publier le sujet
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
