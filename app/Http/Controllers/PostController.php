<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a specific post with likes count.
     *
     * @param int $postId
     * @return JsonResponse
     */
    public function show(int $postId): JsonResponse
    {
        $post = Post::withCount('likes')->find($postId);

        if (!$post) {
            return $this->errorResponse('Post not found', 404);
        }

        return $this->successResponse($post, 'Post retrieved successfully');
    }

    /**
     * Display a list of posts with their relationships.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::with(['user', 'comments', 'likes'])->get();

        return $this->successResponse($posts, 'Posts retrieved successfully');
    }

    /**
     * Create a new post.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $this->validatePost($request);

        if ($validatedData->fails()) {
            return $this->errorResponse($validatedData->errors(), 400);
        }

        try {
            $post = Post::create([
                'title' => $request->title,
                'body' => $request->body,
                'user_id' => auth()->id(),
            ]);

            return $this->successResponse($post, 'Post created successfully', 201);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }

    /**
     * Update an existing post.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validatedData = $this->validatePost($request);

        if ($validatedData->fails()) {
            return $this->errorResponse($validatedData->errors(), 400);
        }

        try {
            $post = Post::findOrFail($id);
            $post->update([
                'title' => $request->title,
                'body' => $request->body,
            ]);

            return $this->successResponse($post, 'Post updated successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }

    /**
     * Delete a post.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();

            return $this->successResponse(null, 'Post deleted successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }

    /**
     * Validate post data.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validatePost(Request $request)
    {
        return Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
    }

    /**
     * Format success responses.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    private function successResponse($data, string $message, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Format error responses.
     *
     * @param mixed $message
     * @param int $statusCode
     * @return JsonResponse
     */
    private function errorResponse($message, int $statusCode): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}
