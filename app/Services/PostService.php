<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PostService
{
    const POST_PER_PAGE = 25;

    const MAX_POSTS_PER_PAGE = 100;

    public function getAllPosts(Request $request)
    {
        try {
            $query = Post::query();
            $perPage = min($request->get('per_page', self::POST_PER_PAGE), self::MAX_POSTS_PER_PAGE);
            $posts = $query->paginate($perPage);

            return PostResource::collection($posts)
                ->additional(['status' => 'success', 'message' => 'List of posts retrieved successfully.', 'code' => ApiResponse::OK_STATUS]);
        } catch (\Throwable $e) {
            Log::error('Error fetching posts', ['exception' => $e->getMessage()]);

            return ApiResponse::error('FETCH_FAILED', 'Error while retrieving posts.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function createPost(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'posts', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $post = new Post($validatedData['data']);
            Gate::authorize('create', $post);
            $post->save();

            return ApiResponse::success(new PostResource($post), 'Post created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error creating post', ['exception' => $e->getMessage()]);

            return ApiResponse::error('CREATE_FAILED', 'Error while creating post.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function getPostById(Request $request, $id)
    {
        try {
            $query = Post::where('id', $id);

            $post = $query->first();
            if (! $post) {
                return ApiResponse::error('NOT_FOUND', 'Post not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            return ApiResponse::success(new PostResource($post), 'Post retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error retrieving post', ['exception' => $e->getMessage()]);

            return ApiResponse::error('NOT_FOUND', 'Post not found.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function updatePost(Request $request, $id)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'posts', 'update', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $post = Post::find($id);
            if (! $post) {
                return ApiResponse::error('NOT_FOUND', 'Post not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('update', $post);
            $post->update($validatedData['data']);

            return ApiResponse::success(new PostResource($post), 'Post updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error updating post', ['exception' => $e->getMessage()]);

            return ApiResponse::error('UPDATE_FAILED', 'Error while updating post.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function deletePost($id)
    {
        try {
            $post = Post::find($id);
            if (! $post) {
                return ApiResponse::error('NOT_FOUND', 'Post not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('delete', $post);
            $post->delete();

            return ApiResponse::success([], 'Post deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error deleting post', ['exception' => $e->getMessage()]);

            return ApiResponse::error('DELETE_FAILED', 'Error while deleting post.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function patchPost(Request $request, $id)
    {
        $post = Post::find($id);
        if (! $post) {
            return ApiResponse::error(
                'NOT_FOUND',
                'Post not found.',
                [],
                ApiResponse::NOT_FOUND_STATUS
            );
        }

        try {
            $validatedData = ValidationHelper::validateRequest($request, 'posts', 'patch', ['id' => $id]);

            if (! $validatedData['success']) {
                return ApiResponse::error(
                    'VALIDATION_ERROR',
                    'Invalid parameters provided.',
                    $validatedData['errors'],
                    ApiResponse::INVALID_PARAMETERS_STATUS
                );
            }

            Gate::authorize('update', $post);
            $post->update($validatedData['data']);

            return ApiResponse::success(new PostResource($post), 'Post partially updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error patching post', ['exception' => $e->getMessage()]);

            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while patching post.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
