<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\ThreadReaction;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function react(Request $request, Thread $thread)
    {
        $user = Auth::user();
        $type = $request->input('reaction'); // "like", "dislike", "fun"

        $reaction = ThreadReaction::where('thread_id', $thread->id)
                                  ->where('user_id', $user->id)
                                  ->first();

        if ($reaction) {
            $reaction->update(['type' => $type]);
        } else {
            ThreadReaction::create([
                'thread_id' => $thread->id,
                'user_id' => $user->id,
                'type' => $type,
            ]);
        }

        return back();
    }
}
