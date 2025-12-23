@extends('base')

@section('title', $thread->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">

    <div class="mb-6">
        <a href="{{ route('forum.index') }}" class="text-blue-600 hover:underline">&larr; Retour au forum</a>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $thread->title }}</h1>
        <p class="text-gray-500 text-sm mt-1">Par {{ $thread->user->name }} 
            @if($thread->category) dans <span class="font-medium">{{ $thread->category->name }}</span> @endif
            • {{ $thread->created_at->diffForHumans() }}</p>
        <p class="text-gray-700 mt-4">{{ $thread->body }}</p>
    </div>

    <!-- COMMENTAIRES -->
    <h2 class="text-xl font-semibold mb-4">Commentaires</h2>

    <div class="space-y-4 mb-6">
        @forelse($thread->comments as $comment)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-semibold text-gray-800">{{ $comment->user->name }}</span>
                    <span class="text-gray-500 text-xs">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-gray-700">{{ $comment->body }}</p>
            </div>
        @empty
            <p class="text-gray-500">Aucun commentaire pour le moment.</p>
        @endforelse
    </div>

    <!-- FORMULAIRE COMMENTAIRE -->
    <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Ajouter un commentaire</h3>
        <form action="{{ route('comment.store', $thread) }}" method="POST" class="space-y-4">
            @csrf
            <textarea name="body" rows="4" class="form-control w-full" placeholder="Votre commentaire..." required></textarea>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow">Publier</button>
        </form>
    </div>
</div>
@endsection
