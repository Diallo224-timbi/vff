<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\Category;

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

}
