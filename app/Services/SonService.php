<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\SonResource;
use App\Models\Son;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class SonService
{
    const SON_PER_PAGE = 25;

    const MAX_SONS_PER_PAGE = 100;

    public function getAllSons(Request $request)
    {
        try {
            $query = Son::query();
            $perPage = min($request->get('per_page', self::SON_PER_PAGE), self::MAX_SONS_PER_PAGE);
            $sons = $query->paginate($perPage);

            return SonResource::collection($sons)
                ->additional(['status' => 'success', 'message' => 'List of sons retrieved successfully.', 'code' => ApiResponse::OK_STATUS]);
        } catch (\Throwable $e) {
            Log::error('Error fetching sons', ['exception' => $e->getMessage()]);

            return ApiResponse::error('FETCH_FAILED', 'Error while retrieving sons.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function createSon(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'sons', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $son = new Son($validatedData['data']);
            Gate::authorize('create', $son);
            $son->save();

            return ApiResponse::success(new SonResource($son), 'Son created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error creating son', ['exception' => $e->getMessage()]);

            return ApiResponse::error('CREATE_FAILED', 'Error while creating son.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function getSonById(Request $request, $id)
    {
        try {
            $query = Son::where('id', $id);

            $son = $query->first();
            if (! $son) {
                return ApiResponse::error('NOT_FOUND', 'Son not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            return ApiResponse::success(new SonResource($son), 'Son retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error retrieving son', ['exception' => $e->getMessage()]);

            return ApiResponse::error('NOT_FOUND', 'Son not found.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function updateSon(Request $request, $id)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'sons', 'update', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $son = Son::find($id);
            if (! $son) {
                return ApiResponse::error('NOT_FOUND', 'Son not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('update', $son);
            $son->update($validatedData['data']);

            return ApiResponse::success(new SonResource($son), 'Son updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error updating son', ['exception' => $e->getMessage()]);

            return ApiResponse::error('UPDATE_FAILED', 'Error while updating son.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function deleteSon($id)
    {
        try {
            $son = Son::find($id);
            if (! $son) {
                return ApiResponse::error('NOT_FOUND', 'Son not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('delete', $son);
            $son->delete();

            return ApiResponse::success([], 'Son deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error deleting son', ['exception' => $e->getMessage()]);

            return ApiResponse::error('DELETE_FAILED', 'Error while deleting son.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function patchSon(Request $request, $id)
    {
        $son = Son::find($id);
        if (! $son) {
            return ApiResponse::error(
                'NOT_FOUND',
                'Son not found.',
                [],
                ApiResponse::NOT_FOUND_STATUS
            );
        }

        try {
            $validatedData = ValidationHelper::validateRequest($request, 'sons', 'patch', ['id' => $id]);

            if (! $validatedData['success']) {
                return ApiResponse::error(
                    'VALIDATION_ERROR',
                    'Invalid parameters provided.',
                    $validatedData['errors'],
                    ApiResponse::INVALID_PARAMETERS_STATUS
                );
            }

            Gate::authorize('update', $son);
            $son->update($validatedData['data']);

            return ApiResponse::success(new SonResource($son), 'Son partially updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error patching son', ['exception' => $e->getMessage()]);

            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while patching son.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
