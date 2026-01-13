<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\Comment;
use App\Models\CommentReaction;

class CommentController extends Controller
{
    public function store(Request $request, Thread $thread) {
        $request->validate(['body' => 'required|string']);
        $thread->comments()->create([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return back()->with('success', 'Commentaire ajouté !');
    }
    // Editer un commentaire
    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);// Vérifie si l'utilisateur peut modifier le commentaire

        return view('comments.edit', compact('comment'));
    }
    // Mettre à jour un commentaire
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);// Vérifie si l'utilisateur peut modifier le commentaire

        $request->validate(['body' => 'required|string']);

        $comment->update(['body' => $request->body]);

        return redirect()->route('forum.show', $comment->thread_id)->with('success', 'Commentaire mis à jour !');
    }
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);// Vérifie si l'utilisateur peut supprimer le commentaire

        $comment->delete();

        return back()->with('success', 'Commentaire supprimé !');
    }
    // Réagir à un commentaire (like/dislike)   
    public function react(Request $request, Comment $comment)
    {
        $request->validate([
            'type' => 'required|in:like,dislike',
        ]);

        $reaction = CommentReaction::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'comment_id' => $comment->id,
            ],
            [
                'type' => $request->type,
            ]
        );

        return back()->with('success', 'Réaction enregistrée !');
    }   
}
