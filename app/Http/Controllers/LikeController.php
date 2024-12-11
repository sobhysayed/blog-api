<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Fetch all likes for a specific post.
     *
     * @param int $postId
     * @return JsonResponse
     */
    public function index(int $postId): JsonResponse
    {
        $likesForPost = Like::where('post_id', $postId)->get();

        return response()->json([
            'message' => 'Likes fetched successfully',
            'data' => $likesForPost
        ], 200);
    }

    /**
     * Add a like to a post.
     *
     * @param Request $request
     * @param int $postId
     * @return JsonResponse
     */
    public function store(Request $request, int $postId): JsonResponse
    {
        $userId = $request->user()->id;

        if ($this->likeExists($userId, $postId)) {
            return response()->json([
                'message' => 'You have already liked this post'
            ], 400);
        }

        $newLike = Like::create([
            'user_id' => $userId,
            'post_id' => $postId,
        ]);

        return response()->json([
            'message' => 'Post liked successfully',
            'data' => $newLike
        ], 201);
    }

    /**
     * Remove a like from a post.
     *
     * @param Request $request
     * @param int $postId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $postId): JsonResponse
    {
        $userId = $request->user()->id;

        $userLike = $this->findUserLike($userId, $postId);

        if (!$userLike) {
            return response()->json([
                'message' => 'Like not found'
            ], 404);
        }

        $userLike->delete();

        return response()->json([
            'message' => 'Post unliked successfully'
        ], 200);
    }

    /**
     * Check if a user has already liked a specific post.
     *
     * @param int $userId
     * @param int $postId
     * @return bool
     */
    private function likeExists(int $userId, int $postId): bool
    {
        return Like::where('user_id', $userId)
            ->where('post_id', $postId)
            ->exists();
    }

    /**
     * Retrieve the user's like for a specific post.
     *
     * @param int $userId
     * @param int $postId
     * @return Like|null
     */
    private function findUserLike(int $userId, int $postId): ?Like
    {
        return Like::where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();
    }
}
