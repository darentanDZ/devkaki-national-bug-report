<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'bug_id' => $request->bug_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        $comment->load('user');

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment,
        ], 201);
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        // Only owner can update
        if ($request->user()->id !== $comment->user_id) {
            return response()->json([
                'message' => 'Unauthorized to update this comment',
            ], 403);
        }

        $request->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $comment->update([
            'content' => $request->content,
            'is_edited' => true,
        ]);

        $comment->load('user');

        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment,
        ]);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Request $request, Comment $comment)
    {
        // Only owner or moderator can delete
        if ($request->user()->id !== $comment->user_id && !$request->user()->isModerator()) {
            return response()->json([
                'message' => 'Unauthorized to delete this comment',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
        ]);
    }
}
