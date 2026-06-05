@extends('base')

@section('title', 'Catégories du forum')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <!-- Header avec les couleurs du forum -->
    <div class="rounded-2xl p-6 mb-8 shadow-xl text-white" style="background: linear-gradient(135deg, #255156, #1e7c86);">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <div class="text-center sm:text-left mb-4 sm:mb-0">
                <h1 class="text-3xl sm:text-4xl font-bold font-montserrat mb-2">
                    <i class="fas fa-folder-open mr-3"></i>Catégories du forum
                </h1>
                <p class="text-white/80">Explorez et gérez les différentes catégories de discussion</p>
            </div>
            <a href="{{ route('categories.create') }}"
               class="group px-6 py-3 rounded-xl font-semibold shadow-lg text-white bg-white/20 hover:bg-white/30 backdrop-blur-sm transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
               <i class="fas fa-plus-circle group-hover:rotate-90 transition-transform duration-300"></i>
               Ajouter une catégorie
            </a>
        </div>
    </div>

    <!-- Messages avec animations -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-[#1e7c86] rounded-lg shadow-md animate-slide-in-right">
            <div class="flex items-center gap-3 text-[#2D2926]">
                <i class="fas fa-check-circle text-2xl text-[#1e7c86]"></i>
                <div>
                    <strong class="font-semibold">Succès !</strong>
                    <p class="text-sm mt-1">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-6 p-4 bg-orange-50 border-l-4 border-[#C79674] rounded-lg shadow-md animate-shake">
            <div class="flex items-center gap-3 text-[#2D2926]">
                <i class="fas fa-exclamation-triangle text-2xl text-[#C79674]"></i>
                <div>
                    <strong class="font-semibold">Attention !</strong>
                    <p class="text-sm mt-1">{{ session('warning') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistiques et barre de recherche -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="rounded-xl p-4 shadow-md transform hover:scale-105 transition-all duration-300 text-white" style="background: linear-gradient(135deg, #255156, #1e7c86);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total catégories</p>
                    <p class="text-3xl font-bold">{{ $categories->total() }}</p>
                </div>
                <i class="fas fa-folder text-4xl opacity-50"></i>
            </div>
        </div>
        
        <div class="rounded-xl p-4 shadow-md transform hover:scale-105 transition-all duration-300 text-white" style="background: linear-gradient(135deg, #1e7c86, #146b73);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Sujets total</p>
                    <p class="text-3xl font-bold">{{ $categories->sum(function($cat) { return $cat->threads->count(); }) }}</p>
                </div>
                <i class="fas fa-comments text-4xl opacity-50"></i>
            </div>
        </div>
        
        <div class="rounded-xl p-4 shadow-md transform hover:scale-105 transition-all duration-300 text-white" style="background: linear-gradient(135deg, #008C95, #006B73);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Par page</p>
                    <p class="text-3xl font-bold">{{ $categories->perPage() }}</p>
                </div>
                <i class="fas fa-table text-4xl opacity-50"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-4 shadow-md" style="border: 1px solid #e5e7eb;">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-[#255156]"></i>
                <input type="text" id="searchCategory" 
                       placeholder="Rechercher une catégorie..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#255156] border border-gray-200">
            </div>
        </div>
    </div>

    <!-- Cartes des catégories -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($categories as $category)
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden category-card" data-category-name="{{ strtolower($category->name) }}">
            <!-- Bandeau coloré en haut avec les couleurs du forum -->
            <div class="h-2" style="background: linear-gradient(135deg, #255156, #1e7c86, #008C95);"></div>
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shadow-md text-white" style="background: linear-gradient(135deg, #255156, #1e7c86);">
                                <i class="fas fa-folder text-white text-lg"></i>
                            </div>
                            <h3 class="text-xl font-bold text-[#2D2926] group-hover:text-[#255156] transition-colors">
                                {{ $category->name }}
                            </h3>
                        </div>
                        
                        @if($category->description)
                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                            <p class="text-gray-600 text-sm leading-relaxed">
                                <i class="fas fa-quote-left text-[#1e7c86] text-xs mr-1"></i>
                                {{ $category->description }}
                                <i class="fas fa-quote-right text-[#1e7c86] text-xs ml-1"></i>
                            </p>
                        </div>
                        @endif
                        
                        <div class="flex flex-wrap gap-3 text-sm text-gray-500">
                            <div class="flex items-center gap-1">
                                <i class="fas fa-hashtag text-[#255156]"></i>
                                <span>ID: {{ $category->id }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-calendar-alt text-[#1e7c86]"></i>
                                <span>Créée le {{ optional($category->created_at)->format('d/m/Y') ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-comments text-[#008C95]"></i>
                                <span>{{ $category->threads->count() }} sujet(s)</span>
                            </div>
                        </div>
                    </div>
                    <!-- accessible uniquement aux admins et à celui qui a créé la catégorie -->
                    @if(auth()->user()?->role === "admin" || auth()->id() === $category->user_id)
                    <div class="flex gap-2">
                        <a href="{{ route('categories.edit', $category) }}"
                           class="p-2 rounded-lg transition-all duration-300 group/tooltip relative" style="background: rgba(37, 81, 86, 0.1); color: #255156; hover:bg-[#255156] hover:text-white">
                            <i class="fas fa-edit" title="modifier"></i>
                            <span class="absolute top-full right-0 mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover/tooltip:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">Modifier</span>
                        </a>
                        
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirmDelete('{{ $category->name }}', {{ $category->threads->count() }})"
                                    class="p-2 rounded-lg transition-all duration-300 group/tooltip relative" style="background: rgba(199, 150, 116, 0.1); color: #C79674; hover:bg-[#C79674] hover:text-white">
                                <i class="fas fa-trash" title="supprimer"></i>
                                <span class="absolute top-full right-0 mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover/tooltip:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">Supprimer</span>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-2 text-center py-12">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="text-2xl font-semibold text-gray-600 mb-2">Aucune catégorie</h3>
            <p class="text-gray-500">Aucune catégorie trouvée dans le forum</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination stylisée -->
    <div class="mt-8">
        <div class="bg-white rounded-xl shadow-md p-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>

<!-- JavaScript amélioré -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(categoryName, threadsCount) {
    let warningMessage = `Voulez-vous vraiment supprimer la catégorie "${categoryName}" ?`;
    
    if (threadsCount > 0) {
        warningMessage += `\n\n⚠️ Attention : Cette catégorie contient ${threadsCount} sujet(s). Tous les sujets et leurs commentaires seront définitivement supprimés !`;
    }
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        html: `<div class="text-left">
            <p class="mb-2">Vous allez supprimer la catégorie :</p>
            <p class="text-lg font-bold" style="color: #C79674;">"${categoryName}"</p>
            ${threadsCount > 0 ? `<div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                <span class="text-sm text-yellow-800">Cette catégorie contient ${threadsCount} sujet(s) qui seront supprimés avec tous leurs commentaires !</span>
            </div>` : ''}
        </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#C79674',
        cancelButtonColor: '#255156',
        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Oui, supprimer',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Annuler',
        reverseButtons: true,
        background: '#fff',
        customClass: {
            popup: 'rounded-2xl',
            title: 'text-2xl font-bold text-[#2D2926]',
            confirmButton: 'px-6 py-2 rounded-lg font-semibold',
            cancelButton: 'px-6 py-2 rounded-lg font-semibold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Suppression en cours...',
                text: 'Veuillez patienter',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            setTimeout(() => {
                event.target.closest('form').submit();
            }, 500);
        }
    });
    
    return false;
}

// Recherche améliorée avec animation
document.getElementById('searchCategory').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const categories = document.querySelectorAll('.category-card');
    let visibleCount = 0;
    
    categories.forEach(card => {
        const categoryName = card.getAttribute('data-category-name');
        if (categoryName.includes(filter)) {
            card.style.display = '';
            card.style.animation = 'fadeInUp 0.3s ease-out';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Afficher un message si aucun résultat
    const existingMessage = document.querySelector('#no-results-message');
    if (visibleCount === 0 && categories.length > 0) {
        if (!existingMessage) {
            const message = document.createElement('div');
            message.id = 'no-results-message';
            message.className = 'col-span-2 text-center py-12 animate-fadeIn';
            message.innerHTML = `
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-2xl font-semibold text-gray-600 mb-2">Aucun résultat</h3>
                <p class="text-gray-500">Aucune catégorie ne correspond à votre recherche</p>
            `;
            document.querySelector('.grid').appendChild(message);
        }
    } else if (existingMessage) {
        existingMessage.remove();
    }
});
</script>

<!-- Styles d'animation supplémentaires -->
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.animate-fadeInUp {
    animation: fadeInUp 0.3s ease-out;
}

.animate-slide-in-right {
    animation: slideInRight 0.4s ease-out;
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
    width: 8px;
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

/* Améliorations pour la pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.pagination .page-item .page-link {
    border-radius: 0.5rem;
    padding: 0.5rem 0.75rem;
    color: #2D2926;
    border: 1px solid #e5e7eb;
    transition: all 0.3s;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #255156, #1e7c86);
    border-color: #255156;
    color: white;
}

.pagination .page-item .page-link:hover {
    background: linear-gradient(135deg, #255156, #1e7c86);
    color: white;
    transform: translateY(-2px);
}

/* Animation au survol des cartes */
.category-card {
    transition: all 0.3s ease;
}

.category-card:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endsection