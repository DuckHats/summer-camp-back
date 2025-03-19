<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PhotoService
{
    const PHOTO_PER_PAGE = 25;

    const MAX_PHOTOS_PER_PAGE = 100;

    public function getAllPhotos(Request $request)
    {
        try {
            $query = Photo::query();
            $perPage = min($request->get('per_page', self::PHOTO_PER_PAGE), self::MAX_PHOTOS_PER_PAGE);
            $photos = $query->paginate($perPage);

            return PhotoResource::collection($photos)
                ->additional(['status' => 'success', 'message' => 'List of photos retrieved successfully.', 'code' => ApiResponse::OK_STATUS]);
        } catch (\Throwable $e) {
            Log::error('Error fetching photos', ['exception' => $e->getMessage()]);

            return ApiResponse::error('FETCH_FAILED', 'Error while retrieving photos.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function createPhoto(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'photos', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $photo = new Photo($validatedData['data']);
            Gate::authorize('create', $request->user());
            $photo->save();

            return ApiResponse::success(new PhotoResource($photo), 'Photo created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error creating photo', ['exception' => $e->getMessage()]);

            return ApiResponse::error('CREATE_FAILED', 'Error while creating photo.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function getPhotoById(Request $request, $id)
    {
        try {
            $query = Photo::where('id', $id);

            $photo = $query->first();
            if (! $photo) {
                return ApiResponse::error('NOT_FOUND', 'Photo not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            return ApiResponse::success(new PhotoResource($photo), 'Photo retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error retrieving photo', ['exception' => $e->getMessage()]);

            return ApiResponse::error('NOT_FOUND', 'Photo not found.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function updatePhoto(Request $request, $id)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'photos', 'update', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $photo = Photo::find($id);
            if (! $photo) {
                return ApiResponse::error('NOT_FOUND', 'Photo not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('update', $request->user());
            $photo->update($validatedData['data']);

            return ApiResponse::success(new PhotoResource($photo), 'Photo updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error updating photo', ['exception' => $e->getMessage()]);

            return ApiResponse::error('UPDATE_FAILED', 'Error while updating photo.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function deletePhoto(Request $request, $id)
    {
        try {
            $photo = Photo::find($id);
            if (! $photo) {
                return ApiResponse::error('NOT_FOUND', 'Photo not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('delete', $request->user());
            $photo->delete();

            return ApiResponse::success([], 'Photo deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error deleting photo', ['exception' => $e->getMessage()]);

            return ApiResponse::error('DELETE_FAILED', 'Error while deleting photo.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function patchPhoto(Request $request, $id)
    {
        $photo = Photo::find($id);
        if (! $photo) {
            return ApiResponse::error(
                'NOT_FOUND',
                'Photo not found.',
                [],
                ApiResponse::NOT_FOUND_STATUS
            );
        }

        try {
            $validatedData = ValidationHelper::validateRequest($request, 'photos', 'patch', ['id' => $id]);

            if (! $validatedData['success']) {
                return ApiResponse::error(
                    'VALIDATION_ERROR',
                    'Invalid parameters provided.',
                    $validatedData['errors'],
                    ApiResponse::INVALID_PARAMETERS_STATUS
                );
            }

            Gate::authorize('update', $request->user());
            $photo->update($validatedData['data']);

            return ApiResponse::success(new PhotoResource($photo), 'Photo partially updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error patching photo', ['exception' => $e->getMessage()]);

            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while patching photo.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
