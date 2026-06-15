@extends('base')

@section('title', $thread->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <!-- Retour au forum -->
    <div class="mb-6">
        <a href="{{ route('forum.index') }}"
        class="inline-flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-800 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Retour au forum
        </a>
    </div>

    <!-- Sujet -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow p-6 mb-6 animate-fade-in">
        <h1 class="text-2xl sm:text-3xl font-bold font-montserrat text-[#2D2926]">{{ $thread->title }}</h1>
        <p class="text-gray-500 text-sm mt-1">
            Par <span class="font-medium">{{ $thread->user->name }}</span> 
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
                        <span class="font-semibold text-gray-800">{{ $comment->user->name }}</span>
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

                    <!-- Modifier / Supprimer si auteur -->
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
            <button type="submit" class="px-4 py-2 bg-[#008C95] text-white rounded hover:bg-[#59BEC9] shadow transition">Publier</button>
        </form>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ==================== MODIFICATION D'UN COMMENTAIRE ====================
document.querySelectorAll('.edit-comment-btn').forEach(button => {
    button.addEventListener('click', function() {
        const commentId = this.dataset.commentId;
        
        // Cacher l'affichage normal
        const displayDiv = document.querySelector(`.comment-display-${commentId}`);
        // Afficher le formulaire d'édition
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
        
        // Afficher l'affichage normal
        const displayDiv = document.querySelector(`.comment-display-${commentId}`);
        // Cacher le formulaire d'édition
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
            title: '⚠️ Supprimer le commentaire',
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

// ==================== SOUMISSION DU FORMULAIRE D'ÉDITION AVEC SWEETALERT ====================
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
        
        // Soumettre le formulaire normalement
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

// Resize automatique du textarea selon le contenu
function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

// Animation des messages flash (si présents)
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.alert');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => {
                if (message.parentNode) message.remove();
            }, 300);
        }, 5000);
    });
});
</script>

<!-- Animation fade-in -->
<style>
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Style pour les boutons d'édition */
.edit-comment-btn, .delete-comment-btn {
    transition: all 0.2s ease;
    cursor: pointer;
}

.edit-comment-btn:hover, .delete-comment-btn:hover {
    transform: scale(1.1);
}

/* Transition pour les formulaires d'édition */
.comment-edit-form {
    transition: all 0.3s ease;
}

/* Style pour le textarea en mode édition */
.comment-edit-form textarea {
    font-size: 0.9rem;
    line-height: 1.5;
}
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection