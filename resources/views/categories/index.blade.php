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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="categoriesGrid">
        @forelse($categories as $category)
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden category-card" data-category-name="{{ strtolower($category->name) }}" data-category-id="{{ $category->id }}">
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
                        <!-- Bouton Modifier avec modale SweetAlert2 -->
                        <button type="button" 
                                class="edit-category-btn p-2 rounded-lg transition-all duration-300 group/tooltip relative" 
                                style="background: rgba(37, 81, 86, 0.1); color: #255156; hover:bg-[#255156] hover:text-white"
                                data-category-id="{{ $category->id }}"
                                data-category-name="{{ $category->name }}"
                                data-category-description="{{ $category->description ?? '' }}">
                            <i class="fas fa-edit" title="modifier"></i>
                            <span class="absolute top-full right-0 mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover/tooltip:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">Modifier</span>
                        </button>
                        
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="delete-form" data-category-name="{{ $category->name }}" data-threads-count="{{ $category->threads->count() }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    class="delete-category-btn p-2 rounded-lg transition-all duration-300 group/tooltip relative" 
                                    style="background: rgba(199, 150, 116, 0.1); color: #C79674; hover:bg-[#C79674] hover:text-white">
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

<!-- Formulaire de modification caché -->
<form id="editCategoryForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="name" id="edit_category_name">
    <input type="hidden" name="description" id="edit_category_description">
</form>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ==================== GESTION DE LA MODIFICATION ====================
document.querySelectorAll('.edit-category-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const categoryId = this.dataset.categoryId;
        const currentName = this.dataset.categoryName;
        const currentDescription = this.dataset.categoryDescription || '';
        
        Swal.fire({
            title: '<i class="fas fa-edit"></i> Modifier la catégorie',
            html: `
                <div class="text-left">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2 text-left">Nom de la catégorie</label>
                        <input type="text" id="swal-category-name" class="swal2-input w-full" placeholder="Nom de la catégorie" value="${escapeHtml(currentName)}" style="width: 100%; margin: 0;">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 font-semibold mb-2 text-left">Description</label>
                        <textarea id="swal-category-description" class="swal2-textarea w-full" placeholder="Description de la catégorie" rows="3" style="width: 100%; margin: 0; resize: vertical;">${escapeHtml(currentDescription)}</textarea>
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
                const newName = document.getElementById('swal-category-name').value.trim();
                const newDescription = document.getElementById('swal-category-description').value.trim();
                
                if (!newName) {
                    Swal.showValidationMessage('Le nom de la catégorie est requis');
                    return false;
                }
                
                if (newName.length < 2) {
                    Swal.showValidationMessage('Le nom doit contenir au moins 2 caractères');
                    return false;
                }
                
                if (newName.length > 100) {
                    Swal.showValidationMessage('Le nom ne peut pas dépasser 100 caractères');
                    return false;
                }
                
                return { name: newName, description: newDescription };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { name, description } = result.value;
                
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
                const form = document.getElementById('editCategoryForm');
                form.action = `/categories/${categoryId}`;
                document.getElementById('edit_category_name').value = name;
                document.getElementById('edit_category_description').value = description;
                
                setTimeout(() => {
                    form.submit();
                }, 300);
            }
        });
    });
});

// ==================== GESTION DE LA SUPPRESSION ====================
document.querySelectorAll('.delete-category-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const form = this.closest('.delete-form');
        const categoryName = form.dataset.categoryName;
        const threadsCount = parseInt(form.dataset.threadsCount) || 0;
        
        Swal.fire({
            title: '<i class="fas fa-trash"></i> Supprimer la catégorie',
            html: `
                <div class="text-left">
                    <p class="mb-3 text-gray-700">Vous êtes sur le point de supprimer :</p>
                    <div class="bg-gray-100 p-3 rounded-lg mb-3">
                        <p class="font-bold text-lg" style="color: #C79674;">"${escapeHtml(categoryName)}"</p>
                    </div>
                    ${threadsCount > 0 ? `
                        <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-400 rounded-lg">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-exclamation-triangle text-red-500 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-red-700">Attention ! Suppression en cascade</p>
                                    <p class="text-sm text-red-600 mt-1">
                                        Cette catégorie contient <strong>${threadsCount} sujet(s)</strong>.
                                        Tous les sujets et leurs commentaires seront <strong>définitivement supprimés</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    ` : `
                        <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <p class="text-sm text-blue-700">
                                    Cette catégorie est vide. Aucun sujet ne sera affecté.
                                </p>
                            </div>
                        </div>
                    `}
                    <p class="mt-3 text-gray-500 text-sm">
                        <i class="fas fa-ban mr-1"></i> Cette action est irréversible.
                    </p>
                </div>
            `,
            icon: threadsCount > 0 ? 'error' : 'warning',
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
                    html: 'Veuillez patienter, suppression des données associées...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Soumettre le formulaire après un court délai
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

// ==================== RECHERCHE ====================
const searchInput = document.getElementById('searchCategory');
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase().trim();
        const categories = document.querySelectorAll('.category-card');
        let visibleCount = 0;
        
        categories.forEach(card => {
            const categoryName = card.getAttribute('data-category-name');
            if (categoryName && categoryName.includes(filter)) {
                card.style.display = '';
                card.style.animation = 'fadeInUp 0.3s ease-out';
                visibleCount++;
            } else if (card.style) {
                card.style.display = 'none';
            }
        });
        
        const grid = document.getElementById('categoriesGrid');
        let existingMessage = document.querySelector('#no-results-message');
        
        if (visibleCount === 0 && categories.length > 0 && filter !== '') {
            if (!existingMessage) {
                const message = document.createElement('div');
                message.id = 'no-results-message';
                message.className = 'col-span-2 text-center py-12 animate-fadeIn';
                message.innerHTML = `
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-2xl font-semibold text-gray-600 mb-2">Aucun résultat</h3>
                    <p class="text-gray-500">Aucune catégorie ne correspond à "${escapeHtml(filter)}"</p>
                `;
                grid.appendChild(message);
            } else {
                existingMessage.querySelector('p.text-gray-500').innerHTML = `Aucune catégorie ne correspond à "${escapeHtml(filter)}"`;
            }
        } else if (existingMessage) {
            existingMessage.remove();
        }
    });
}

// ==================== AUTO-DISPARITION DES MESSAGES FLASH ====================
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.animate-slide-in-right, .animate-shake');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transform = 'translateX(20px)';
            setTimeout(() => {
                if (message.parentNode) message.remove();
            }, 300);
        }, 5000);
    });
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

.animate-fadeIn {
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
    flex-wrap: wrap;
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

/* Transition pour les messages flash */
.animate-slide-in-right, .animate-shake {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Style pour les inputs SweetAlert2 */
.swal2-input, .swal2-textarea {
    border-radius: 0.5rem !important;
    border: 1px solid #e2e8f0 !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.875rem !important;
}

.swal2-input:focus, .swal2-textarea:focus {
    border-color: #255156 !important;
    box-shadow: 0 0 0 3px rgba(37, 81, 86, 0.1) !important;
    outline: none !important;
}
</style>
@endsection