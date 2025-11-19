<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bug;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * Toggle vote on a bug (upvote/unvote).
     */
    public function toggle(Request $request, Bug $bug)
    {
        $request->validate([
            'bug_id' => ['sometimes', 'exists:bugs,id'],
        ]);

        $userId = $request->user()->id;

        $existingVote = Vote::where('user_id', $userId)
            ->where('bug_id', $bug->id)
            ->first();

        if ($existingVote) {
            // Remove vote
            $existingVote->delete();
            $bug->decrement('upvotes');

            return response()->json([
                'message' => 'Vote removed',
                'voted' => false,
                'upvotes' => $bug->fresh()->upvotes,
            ]);
        } else {
            // Add vote
            Vote::create([
                'user_id' => $userId,
                'bug_id' => $bug->id,
            ]);
            $bug->increment('upvotes');

            return response()->json([
                'message' => 'Bug upvoted',
                'voted' => true,
                'upvotes' => $bug->fresh()->upvotes,
            ]);
        }
    }

    /**
     * Check if user has voted on a bug.
     */
    public function check(Request $request, Bug $bug)
    {
        $voted = Vote::where('user_id', $request->user()->id)
            ->where('bug_id', $bug->id)
            ->exists();

        return response()->json([
            'voted' => $voted,
        ]);
    }
}
