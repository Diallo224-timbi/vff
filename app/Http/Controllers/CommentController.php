<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Thread;

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

}
