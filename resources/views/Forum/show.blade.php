@extends('base')

@section('title', $thread->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">

    <!-- Retour au forum -->
    <div class="mb-6">
        <a href="{{ route('forum.index') }}" class="text-blue-600 hover:underline">&larr; Retour au forum</a>
    </div>

    <!-- Sujet -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow p-6 mb-6 animate-fade-in">
        <h1 class="text-2xl sm:text-3xl font-bold font-montserrat text-[#2D2926]">{{ $thread->title }}</h1>
        <p class="text-gray-500 text-sm mt-1">
            Par <span class="font-medium">{{ $thread->user->name }}</span> 
            @if($thread->category) dans <span class="font-medium">{{ $thread->category->name }}</span> @endif
            • {{ $thread->created_at->diffForHumans() }}
        </p>
        <p class="text-gray-700 mt-4">{{ $thread->body }}</p>
    </div>

    <!-- Commentaires -->
    <h2 class="text-xl font-semibold mb-4">Commentaires</h2>

    <div class="space-y-4 mb-6">
        @forelse($thread->comments as $comment)
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center animate-fade-in transition hover:shadow-md">
                
                <!-- Auteur et corps -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-gray-800">{{ $comment->user->name }}</span>
                        <span class="text-gray-500 text-xs">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-700">{{ $comment->body }}</p>
                </div>

                <!-- Actions et réactions -->
                <div class="flex items-center gap-4 mt-2 sm:mt-0">
                    <!-- Like / Unlike -->
                    <form action="{{ route('comment.react', $comment) }}" method="POST" class="flex gap-2">
                        @csrf
                        <button type="submit" name="type" value="like" class="flex items-center gap-1 text-gray-500 hover:text-blue-500 transition">
                            <i class="fas fa-thumbs-up"></i> {{ $comment->likes() }}
                        </button>
                        <button type="submit" name="type" value="dislike" class="flex items-center gap-1 text-gray-500 hover:text-red-500 transition">
                            <i class="fas fa-thumbs-down"></i> {{ $comment->dislikes() }}    
                        </button>
                    </form>

                    <!-- Modifier / Supprimer si auteur -->
                    @if(auth()->id() === $comment->user_id)
                        <!--<a href="{{ route('comment.edit', $comment) }}" class="text-blue-500 hover:text-blue-600 flex items-center gap-1 text-sm">
                            <i class="fas fa-edit"></i>
                        </a>-->
                        <form action="{{ route('comment.destroy', $comment) }}" method="POST" class="inline" onsubmit="return confirmDelete(this);">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600 flex items-center gap-1 text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
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

<!-- Confirmation attractive -->
<script>
function confirmDelete(form) {
    return confirm("⚠️ Voulez-vous vraiment supprimer ce commentaire ?");
}

// Resize automatique du textarea selon le contenu
function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}
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
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
