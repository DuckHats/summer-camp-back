<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Filters\FilterRules\ErrorFilter;
use App\Http\Resources\ErrorResource;
use App\Models\Error;
use Illuminate\Http\Request;

class ErrorService
{
    const ERROR_PER_PAGE = 25;

    const MAX_ERRORS_PER_PAGE = 100;

    public function getAllErrors(Request $request)
    {
        try {
            $query = Error::query();

            $perPage = min($request->get('per_page', self::ERROR_PER_PAGE), self::MAX_ERRORS_PER_PAGE);
            $errors = $query->paginate($perPage);

            return ErrorResource::collection($errors)
                ->additional([
                    'status' => 'success',
                    'message' => 'List of errors retrieved successfully.',
                    'code' => ApiResponse::OK_STATUS,
                ]);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving errors.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function createError(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'errors', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $error = Error::create($validatedData['data']);

            return ApiResponse::success(new ErrorResource($error), 'Error created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'CREATE_FAILED',
                'Error while creating error.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function getErrorById($id)
    {
        try {
            $error = Error::find($id);

            if (! $error) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'Error not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            return ApiResponse::success(new ErrorResource($error), 'Error retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'NOT_FOUND',
                'Error not found.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function updateError(Request $request, $id)
    {
        $error = Error::find($id);
        if (! $error) {
            return ApiResponse::error(
                'NOT_FOUND',
                'Error not found.',
                [],
                ApiResponse::NOT_FOUND_STATUS
            );
        }

        try {
            $placeholders = ['id' => $id];
            $validatedData = ValidationHelper::validateRequest($request, 'errors', 'update', $placeholders);

            if (! $validatedData['success']) {
                return ApiResponse::error(
                    'VALIDATION_ERROR',
                    'Invalid parameters provided.',
                    $validatedData['errors'],
                    ApiResponse::INVALID_PARAMETERS_STATUS
                );
            }

            $error->update($validatedData['data']);

            return ApiResponse::success(new ErrorResource($error), 'Error updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating error.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function deleteError($id)
    {
        try {
            $error = Error::find($id);

            if (! $error) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'Error not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            $error->delete();

            return ApiResponse::success([], 'Error deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'DELETE_FAILED',
                'Error while deleting error.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
