<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of comments for a specific post.
     */
    public function index($postId): JsonResponse
    {
        $comments = Comment::where('post_id', $postId)->get();
        return response()->json($comments, 200);
    }

    /**
     * Store a newly created comment for a post.
     */
    public function store(Request $request, $postId): JsonResponse
    {
        $validated = $this->validateComment($request);
        if ($validated) return $validated;

        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => $request->user()->id,
            'body'    => $request->body,
        ]);

        return response()->json($comment, 201);
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, $postId, $commentId): JsonResponse
    {
        $validated = $this->validateComment($request);
        if ($validated) return $validated;

        $comment = $this->findComment($postId, $commentId);
        if (!$comment || !$this->authorizeUser($request, $comment)) {
            return response()->json(['message' => 'Unauthorized or comment not found'], 403);
        }

        $comment->update(['body' => $request->body]);

        return response()->json($comment, 200);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Request $request, $postId, $commentId): JsonResponse
    {
        $comment = $this->findComment($postId, $commentId);
        if (!$comment || !$this->authorizeUser($request, $comment)) {
            return response()->json(['message' => 'Unauthorized or comment not found'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }

    /**
     * Validate the incoming comment request.
     */
    private function validateComment(Request $request)
    {
        $validator = Validator::make($request->all(), ['body' => 'required|string']);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        return null;
    }

    /**
     * Find a comment by post-ID and comment ID.
     */
    private function findComment($postId, $commentId)
    {
        return Comment::where('id', $commentId)->where('post_id', $postId)->first();
    }

    /**
     * Check if the authenticated user owns the comment.
     */
    private function authorizeUser(Request $request, Comment $comment): bool
    {
        return $comment->user_id === $request->user()->id;
    }
}
