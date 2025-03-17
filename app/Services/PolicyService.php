<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\PolicyResource;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PolicyService
{
    const POLICY_PER_PAGE = 25;

    const MAX_POLICY_PER_PAGE = 100;

    public function getPolicy(Request $request)
    {
        try {
            $query = Policy::query();

            $perPage = min($request->get('per_page', self::POLICY_PER_PAGE), self::MAX_POLICY_PER_PAGE);
            $policies = $query->paginate($perPage);

            return PolicyResource::collection($policies)->additional([
                'status' => 'success',
                'message' => 'List of policies retrieved successfully.',
                'code' => ApiResponse::OK_STATUS,
            ]);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving policies.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function createPolicy(Request $request)
    {

        $validatedData = ValidationHelper::validateRequest($request, 'policies', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $policy = new Policy($validatedData['data']);
            Gate::authorize('create', $policy);
            $policy->save();

            return ApiResponse::success(new PolicyResource($policy), 'Policy created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'CREATE_FAILED',
                'Error while creating policy.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function getPolicyById($id)
    {
        try {
            $policy = Policy::find($id);
            if (! $policy) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'Policy not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            return ApiResponse::success(new PolicyResource($policy), 'Policy retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving policy.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function updatePolicy(Request $request, $id)
    {

        $validatedData = ValidationHelper::validateRequest($request, 'policies', 'update', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $policy = Policy::find($id);
            if (! $policy) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'Policy not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }
            Gate::authorize('update', $policy);
            $policy->update($validatedData['data']);

            return ApiResponse::success(new PolicyResource($policy), 'Policy updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating policy.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function deletePolicy($id)
    {
        $policy = Policy::find($id);
        if (! $policy) {
            return ApiResponse::error(
                'NOT_FOUND',
                'Policy not found.',
                [],
                ApiResponse::NOT_FOUND_STATUS
            );
        }
        Gate::authorize('delete', $policy);

        try {
            $policy->delete();

            return ApiResponse::success([], 'Policy deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'DELETE_FAILED',
                'Error while deleting policy.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function patchPolicy(Request $request, $id)
    {

        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'policies', 'patch', $placeholders);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $policy = Policy::find($id);
            if (! $policy) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'Policy not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }
            Gate::authorize('update', $policy);
            $policy->update($validatedData['data']);

            return ApiResponse::success(new PolicyResource($policy), 'Policy updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating policy.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
