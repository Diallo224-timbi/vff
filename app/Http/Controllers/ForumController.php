<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\Category;
use App\Models\ThreadReaction;  

class ForumController extends Controller
{
    public function index() {
        $threads = Thread::latest()->with('user', 'category')->paginate(10);
        $categories = Category::all();
        $categories->loadCount('threads');
        $categoriesLimite = Category::latest()->take(5)->get(); // récupère les 10 dernières catégories
        return view('forum.index', compact('threads', 'categories', 'categoriesLimite'));
    }

    public function show(Thread $thread) {
        $thread->load('comments.user');
        return view('forum.show', compact('thread'));
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $thread = Thread::create([
            'title' => $request->title,
            'body' => $request->body,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('forum.show', $thread)->with('success', 'Sujet créé !');
    }
    public function toggleResolve(Request $request, Thread $thread)
    {
        // Vérifier les permissions
        if (auth()->id() !== $thread->user_id && !in_array(auth()->user()->role, ['admin', 'moderateur','moderateur_classique','user'])) {
            return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
        }

        // Valider la requête
        $request->validate([
            'resolved' => 'required|boolean'
        ]);

        // Mettre à jour le statut
        $thread->is_resolved = $request->resolved;
        $thread->save();

        return response()->json([
            'success' => true,
            'message' => $request->resolved ? 'Sujet marqué comme résolu' : 'Sujet marqué comme non résolu',
            'is_resolved' => $thread->is_resolved
        ]);
    }
    public function react(Request $request, Thread $thread)
    {
        $request->validate([
            'type' => 'required|in:like,dislike',
        ]);

        $reaction = ThreadReaction::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'thread_id' => $thread->id,
            ],
            [
                'type' => $request->type,
            ]
        );

        return back()->with('success', 'Réaction enregistrée !');
    }   

}
