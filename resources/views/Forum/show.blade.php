@extends('base')

@section('title', $thread->title)

@section('content')
<a href="{{ route('forum.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-semibold shadow-md text-white bg-[#255156] hover:bg-[#1e7c86] transition-all duration-300">
    <i class="fas fa-arrow-left"></i> Retour au forum
</a>
<div class="max-w-10xl mx-auto px-4 py-6">
    <!-- Sujet -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow p-6 mb-6 animate-fade-in">
        <h1 class="text-2xl sm:text-3xl font-bold font-montserrat text-[#2D2926]">{{ $thread->title }}</h1>
        <p class="text-gray-500 text-sm mt-1">
            Par <span class="font-medium">{{ $thread->user->prenom }} {{ $thread->user->nom ?? '' }}</span>
            <small style="color: rgb(11, 131, 131)">
                <i class="">{{ $thread->user->structure->organisme->nom_organisme ?? '' }} 
                {{ $thread->user->structure->ville ?? '' }} 
                ({{ $thread->user->structure->code_postal ?? '' }})
                </i>
            </small>
            @if($thread->category) dans <span class="font-medium">{{ $thread->category->name }}</span> @endif
            {{ $thread->created_at->diffForHumans() }}
        </p>
        <p class="text-gray-700 mt-4">{{ $thread->body }}</p>
    </div>  
    <!-- Commentaires -->
    <h2 class="text-xl font-semibold mb-4">Commentaires</h2>
    <div class="space-y-4 mb-6">
        @forelse($thread->comments as $comment)
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center animate-fade-in transition hover:shadow-md comment-item" data-comment-id="{{ $comment->id }}">    
                <!-- Auteur et corps -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <span class="cursor-pointer user-info-trigger font-semibold text-gray-800 hover:text-[#255156] transition-colors" 
                              data-user-id="{{ $comment->user->id }}"
                              data-user-prenom="{{ $comment->user->prenom }}"
                              data-user-nom="{{ $comment->user->name ?? '' }}"
                              data-user-email="{{ $comment->user->email }}"
                              data-user-role="{{ $comment->user->role ?? 'Membre' }}"
                              data-user-structure="{{ $comment->user->structure->ville ?? '' }} ({{ $comment->user->structure->code_postal ?? '' }})"
                              data-user-organisme="{{ $comment->user->structure->organisme->nom_organisme ?? '' }}"
                              data-user-telephone="{{ $comment->user->phone ?? 'Non renseigné' }}"
                              data-user-fonction="{{ $comment->user->fonction ?? 'Non renseigné' }}">
                            {{ $comment->user->prenom }} {{ $comment->user->nom ?? '' }}
                            <small style="color: rgb(11, 131, 131)">
                                <i class="fas fa-building"></i> {{ $comment->user->structure->organisme->nom_organisme ?? '' }} 
                                    {{ $comment->user->structure->organisme->ville ?? '' }} 
                                    ({{$comment->user->structure->organisme->code_postal ?? '' }})
                                </i>
                            </small>
                        </span>
                        <span class="text-gray-500 text-xs">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <!-- Contenu du commentaire (modifiable) -->
                    <div class="comment-display-{{ $comment->id }}">
                        <p class="text-gray-700 comment-text-{{ $comment->id }}">{{ $comment->body }}</p>
                    </div>
                    <!-- Formulaire d'édition caché -->
                    <div class="comment-edit-form-{{ $comment->id }} hidden mt-2">
                        <form action="{{ route('comment.update', $comment) }}" method="POST" class="edit-comment-form">
                            @csrf
                            @method('PUT')
                            <textarea name="body" rows="2" class="form-control w-full border border-gray-300 rounded-xl p-2 focus:ring-2 focus:ring-[#008C95] focus:border-[#008C95] transition resize-none" placeholder="Modifier votre commentaire..." required>{{ $comment->body }}</textarea>
                            <div class="flex gap-2 mt-2">
                                <button type="submit" class="px-3 py-1 bg-[#008C95] text-white rounded-lg hover:bg-[#59BEC9] transition text-sm">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                                <button type="button" class="cancel-edit-btn px-3 py-1 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition text-sm" data-comment-id="{{ $comment->id }}">
                                    <i class="fas fa-times"></i> Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Actions et réactions -->
                <div class="flex items-center gap-4 mt-2 sm:mt-0">
                    <!-- Like / Unlike -->
                    <form action="{{ route('comment.react', $comment) }}" method="POST" class="flex gap-2">
                        @csrf
                        <button type="submit" name="type" value="like" class="flex items-center gap-1 text-gray-500 hover:text-blue-500 transition">
                            <i class="fas fa-thumbs-up"></i> <span class="like-count-{{ $comment->id }}">{{ $comment->likes() }}</span>
                        </button>
                        <button type="submit" name="type" value="dislike" class="flex items-center gap-1 text-gray-500 hover:text-red-500 transition">
                            <i class="fas fa-thumbs-down"></i> <span class="dislike-count-{{ $comment->id }}">{{ $comment->dislikes() }}</span>    
                        </button>
                    </form>
                    <!-- Modifier / Supprimer si auteur ou admin -->
                    @if(auth()->id() === $comment->user_id || (auth()->user()->role ?? '') === 'admin')
                        <div class="flex gap-2">
                            <button type="button" class="edit-comment-btn text-blue-500 hover:text-blue-600 flex items-center gap-1 text-sm" data-comment-id="{{ $comment->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('comment.destroy', $comment) }}" method="POST" class="inline delete-comment-form" data-comment-id="{{ $comment->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-comment-btn text-red-500 hover:text-red-600 flex items-center gap-1 text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500">Aucun commentaire pour le moment.</p>
        @endforelse
    </div>
    <!-- Formulaire nouveau commentaire -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow p-6 animate-fade-in">
        <h3 class="font-semibold text-gray-900 mb-4">Ajouter un commentaire</h3>
        <form action="{{ route('comment.store', $thread) }}" method="POST" class="space-y-4">
            @csrf
            <textarea name="body" rows="2" class="form-control w-full border border-gray-300 rounded-xl p-2 focus:ring-2 focus:ring-[#008C95] focus:border-[#008C95] transition resize-none" placeholder="Votre commentaire..." oninput="autoResize(this)" required></textarea>
            <button type="submit" class="px-4 py-2 text-white bg-[#255156] hover:bg-[#1e7c86] shadow transition">Publier</button>
        </form>
    </div>
</div>
<!-- ============ MODAL UTILISATEUR COMPACT ============ -->
<div id="userModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 opacity-0 pointer-events-none transition-all duration-400 ease-out">
    <!-- Overlay -->
    <div id="modalOverlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-400"></div>
    <!-- Modal compact -->
    <div id="modalContent" class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl transform scale-95 translate-y-6 transition-all duration-400 ease-out overflow-hidden">
        <!-- En-tête avec dégradé -->
        <div class="relative bg-gradient-to-r from-[#255156] to-[#008C95] px-6 py-4 text-white">
            <button id="closeModal" class="absolute top-2 right-3 text-white/60 hover:text-white transition-colors text-lg">
                <i class="fas fa-times"></i>
            </button>
            <!-- Nom + Prénom (sans avatar) -->
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/50 flex items-center justify-center text-2xl text-white shadow-lg flex-shrink-0">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h3 id="modalName" class="text-lg font-bold leading-tight">Prénom Nom</h3>
                    <p id="modalRole" class="text-xs text-white/80">
                        <span class="inline-block px-2 py-0.5 bg-white/20 rounded-full backdrop-blur-sm">Membre</span>
                    </p>
                </div>
            </div>
        </div> 
        <!-- Corps compact -->
        <div class="p-4 space-y-2">
            <!-- Grille 2 colonnes pour les infos -->
            <div class="grid grid-cols-2 gap-2">
                <!-- Structure -->
                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-[#255156]/10 flex items-center justify-center text-[#255156] flex-shrink-0 text-xs">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Structure</p>
                        <p id="modalStructure" class="text-xs text-gray-700 font-medium truncate">Non renseignée</p>
                    </div>
                </div>
                <!-- Organisme -->
                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-[#C9A227]/10 flex items-center justify-center text-[#C9A227] flex-shrink-0 text-xs">
                        <i class="fas fa-industry"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Organisme</p>
                        <p id="modalOrganisme" class="text-xs text-gray-700 font-medium truncate">Non renseigné</p>
                    </div>
                </div>
                <!-- Fonction -->
                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 flex-shrink-0 text-xs">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Fonction</p>
                        <p id="modalFonction" class="text-xs text-gray-700 font-medium truncate">Non renseignée</p>
                    </div>
                </div>
                <!-- Téléphone -->
                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-[#C9A227]/10 flex items-center justify-center text-[#C9A227] flex-shrink-0 text-xs">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Téléphone</p>
                        <p id="modalTelephone" class="text-xs text-gray-700 font-medium truncate">Non renseigné</p>
                    </div>
                </div>
            </div>
            <!-- Email sur toute la largeur -->
            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 text-xs">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Email</p>
                    <p id="modalEmail" class="text-xs text-gray-700 font-medium truncate">Non renseigné</p>
                </div>
            </div>
            <!-- Bouton de contact compact -->
            <button id="modalContactBtn" class="w-full mt-1 py-2 bg-gradient-to-r from-[#255156] to-[#008C95] text-white font-semibold rounded-lg hover:shadow-lg hover:scale-[1.02] transition-all duration-300 text-sm">
                <i class="fas fa-paper-plane mr-2"></i> Contacter
            </button>
        </div>
    </div>
</div>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ==================== MODAL UTILISATEUR COMPACT ====================
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('userModal');
    const overlay = document.getElementById('modalOverlay');
    const content = document.getElementById('modalContent');
    const closeBtn = document.getElementById('closeModal');
    // Éléments du modal
    const modalName = document.getElementById('modalName');
    const modalRole = document.getElementById('modalRole');
    const modalStructure = document.getElementById('modalStructure');
    const modalOrganisme = document.getElementById('modalOrganisme');
    const modalFonction = document.getElementById('modalFonction');
    const modalEmail = document.getElementById('modalEmail');
    const modalTelephone = document.getElementById('modalTelephone');
    const modalContactBtn = document.getElementById('modalContactBtn');
    // Ouvrir le modal
    document.querySelectorAll('.user-info-trigger').forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            // Récupérer les données
            const userData = {
                prenom: this.dataset.userPrenom || 'Utilisateur',
                nom: this.dataset.userNom || '',
                email: this.dataset.userEmail || 'Non renseigné',
                role: this.dataset.userRole || 'Membre',
                structure: this.dataset.userStructure || 'Non renseignée',
                organisme: this.dataset.userOrganisme || 'Non renseigné',
                telephone: this.dataset.userTelephone || 'Non renseigné',
                fonction: this.dataset.userFonction || 'Non renseigné'
            }; 
            // Remplir le modal
            const fullName = userData.prenom + (userData.nom ? ' ' + userData.nom : '');
            modalName.textContent = fullName;
            modalRole.innerHTML = `<span class="inline-block px-2 py-0.5 bg-white/20 rounded-full backdrop-blur-sm">${userData.role}</span>`;
            modalStructure.textContent = userData.structure;
            modalOrganisme.textContent = userData.organisme;
            modalFonction.textContent = userData.fonction;
            modalEmail.textContent = userData.email;
            modalTelephone.textContent = userData.telephone;
            // Afficher le modal
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');
            content.classList.remove('scale-95', 'translate-y-6');
            content.classList.add('scale-100', 'translate-y-0');
            document.body.style.overflow = 'hidden';
        });
    });
    // Fermer le modal
    function closeModal() {
        modal.classList.remove('opacity-100', 'pointer-events-auto');
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-100', 'translate-y-0');
        content.classList.add('scale-95', 'translate-y-6');
        document.body.style.overflow = '';
    }
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    // Fermer avec Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
    // Bouton de contact
    modalContactBtn.addEventListener('click', function() {
        const email = modalEmail.textContent;
        if (email && email !== 'Non renseigné') {
            window.location.href = `mailto:${email}`;
        } else {
            Swal.fire({
                title: 'Information',
                text: 'Cet utilisateur n\'a pas d\'email renseigné.',
                icon: 'info',
                confirmButtonColor: '#255156'
            });
        }
    });
});
// ==================== MODIFICATION D'UN COMMENTAIRE ====================
document.querySelectorAll('.edit-comment-btn').forEach(button => {
    button.addEventListener('click', function() {
        const commentId = this.dataset.commentId;
        
        const displayDiv = document.querySelector(`.comment-display-${commentId}`);
        const editForm = document.querySelector(`.comment-edit-form-${commentId}`);
        
        if (displayDiv && editForm) {
            displayDiv.classList.add('hidden');
            editForm.classList.remove('hidden');
        }
    });
});
// ==================== ANNULATION DE LA MODIFICATION ====================
document.querySelectorAll('.cancel-edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const commentId = this.dataset.commentId;
        const displayDiv = document.querySelector(`.comment-display-${commentId}`);
        const editForm = document.querySelector(`.comment-edit-form-${commentId}`);
        
        if (displayDiv && editForm) {
            displayDiv.classList.remove('hidden');
            editForm.classList.add('hidden');
        }
    });
});
// ==================== SUPPRESSION D'UN COMMENTAIRE ====================
document.querySelectorAll('.delete-comment-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.delete-comment-form');
        const commentDiv = this.closest('.comment-item');
        const commentText = commentDiv.querySelector(`.comment-text-${form.dataset.commentId}`)?.innerText || 'ce commentaire';
        Swal.fire({
            title: '<i class="fas fa-trash mr-2"></i>Supprimer le commentaire',
            html: `
                <div class="text-left">
                    <p class="mb-3 text-gray-700">Vous êtes sur le point de supprimer :</p>
                    <div class="bg-gray-100 p-3 rounded-lg mb-3">
                        <p class="text-sm text-gray-600">"${escapeHtml(commentText.substring(0, 100))}${commentText.length > 100 ? '...' : ''}"</p>
                    </div>
                    <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-400 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5"></i>
                            <p class="text-sm text-red-600">
                                Cette action est <strong>irréversible</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            `,
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
                title: 'text-2xl font-bold',
                confirmButton: 'px-5 py-2.5 rounded-lg font-semibold text-white',
                cancelButton: 'px-5 py-2.5 rounded-lg font-semibold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Suppression en cours...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                setTimeout(() => {
                    form.submit();
                }, 300);
            }
        });
    });
});
// ==================== SOUMISSION DU FORMULAIRE D'ÉDITION ====================
document.querySelectorAll('.edit-comment-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const textarea = this.querySelector('textarea[name="body"]');
        const newBody = textarea.value.trim();
        
        if (!newBody) {
            Swal.fire({
                title: 'Erreur',
                text: 'Le commentaire ne peut pas être vide',
                icon: 'error',
                confirmButtonColor: '#255156'
            });
            return;
        } 
        if (newBody.length < 2) {
            Swal.fire({
                title: 'Erreur',
                text: 'Le commentaire doit contenir au moins 2 caractères',
                icon: 'error',
                confirmButtonColor: '#255156'
            });
            return;
        }
        
        Swal.fire({
            title: 'Modification en cours...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        this.submit();
    });
});

// ==================== FONCTIONS UTILITAIRES ====================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}
</script>

<!-- Styles -->
<style>
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Style du curseur pour les noms cliquables */
.user-info-trigger {
    cursor: pointer;
    transition: all 0.2s ease;
}

.user-info-trigger:hover {
    color: #255156 !important;
    text-decoration: underline;
}

/* Animation du modal */
#userModal {
    transition: opacity 0.4s ease, pointer-events 0.4s ease;
}

#modalContent {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* Style pour les boutons d'édition */
.edit-comment-btn, .delete-comment-btn {
    transition: all 0.2s ease;
    cursor: pointer;
}

.edit-comment-btn:hover, .delete-comment-btn:hover {
    transform: scale(1.1);
}

.comment-edit-form {
    transition: all 0.3s ease;
}

.comment-edit-form textarea {
    font-size: 0.9rem;
    line-height: 1.5;
}
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection