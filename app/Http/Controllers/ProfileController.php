<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        return $this->successResponse($request->user(), 'User profile retrieved successfully');
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        // Validate request
        $validation = $this->validateProfileUpdate($request, $user->id);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), 400);
        }

        try {
            $this->updateProfileFields($user, $request);
            $user->save();

            return $this->successResponse($user, 'Profile updated successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }

    /**
     * Validate the profile update request.
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateProfileUpdate(Request $request, int $userId)
    {
        return Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $userId,
            'password' => 'sometimes|required|string|min:6|confirmed',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    /**
     * Update profile fields based on request data.
     *
     * @param $user
     * @param Request $request
     * @return void
     */
    private function updateProfileFields($user, Request $request): void
    {
        // Update name if provided
        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        // Update email if provided
        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update image if provided
        if ($request->hasFile('image')) {
            $this->updateProfileImage($user, $request->file('image'));
        }
    }

    /**
     * Update the profile image for the user.
     *
     * @param $user
     * @param $imageFile
     * @return void
     */
    private function updateProfileImage($user, $imageFile): void
    {
        // Delete old image if exists
        if ($user->image) {
            Storage::delete($user->image);
        }

        // Store the new image and update the path
        $imagePath = $imageFile->store('profile_images');
        $user->image = $imagePath;
    }

    /**
     * Generate a success response.
     *
     * @param $data
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
     * Generate an error response.
     *
     * @param $message
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
