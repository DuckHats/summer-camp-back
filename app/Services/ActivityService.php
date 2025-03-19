<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ActivityService
{
    const ACTIVITY_PER_PAGE = 25;

    const MAX_ACTIVITY_PER_PAGE = 100;

    public function getAllActivities(Request $request)
    {
        try {
            $query = Activity::query();
            $perPage = min($request->get('per_page', self::ACTIVITY_PER_PAGE), self::MAX_ACTIVITY_PER_PAGE);
            $activities = $query->paginate($perPage);

            return ActivityResource::collection($activities)
                ->additional(['status' => 'success', 'message' => 'List of activities retrieved successfully.', 'code' => ApiResponse::OK_STATUS]);
        } catch (\Throwable $e) {
            Log::error('Error fetching activities', ['exception' => $e->getMessage()]);

            return ApiResponse::error('FETCH_FAILED', 'Error while retrieving activities.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function createActivity(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'activities', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $activity = new Activity($validatedData['data']);
            Gate::authorize('create', $request->user());
            $activity->save();
            
            if (!empty($validatedData['data']['days'])) {
                $activity->days()->sync($validatedData['data']['days']);
            }

            return ApiResponse::success(new ActivityResource($activity), 'Activity created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error creating activity', ['exception' => $e->getMessage()]);

            return ApiResponse::error('CREATE_FAILED', 'Error while creating activity.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function getActivityById(Request $request, $id)
    {
        try {
            $query = Activity::where('id', $id);

            $activity = $query->first();
            if (! $activity) {
                return ApiResponse::error('NOT_FOUND', 'Activity not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            return ApiResponse::success(new ActivityResource($activity), 'Activity retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error retrieving activity', ['exception' => $e->getMessage()]);

            return ApiResponse::error('NOT_FOUND', 'Activity not found.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function updateActivity(Request $request, $id)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'activities', 'update', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $activity = Activity::find($id);
            if (! $activity) {
                return ApiResponse::error('NOT_FOUND', 'Activity not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('update', $request->user());
            $activity->update($validatedData['data']);

            if (!empty($validatedData['data']['days'])) {
                $activity->days()->sync($validatedData['data']['days']);
            }

            return ApiResponse::success(new ActivityResource($activity), 'Activity updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error updating activity', ['exception' => $e->getMessage()]);

            return ApiResponse::error('UPDATE_FAILED', 'Error while updating activity.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function deleteActivity($request, $id)
    {
        try {
            $activity = Activity::find($id);
            if (! $activity) {
                return ApiResponse::error('NOT_FOUND', 'Activity not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('delete', $request->user());

            $activity->days()->detach();

            $activity->delete();

            return ApiResponse::success([], 'Activity deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error deleting activity', ['exception' => $e->getMessage()]);

            return ApiResponse::error('DELETE_FAILED', 'Error while deleting activity.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function patchActivity(Request $request, $id)
    {
        $activity = Activity::find($id);
        if (! $activity) {
            return ApiResponse::error(
                'NOT_FOUND',
                'Activity not found.',
                [],
                ApiResponse::NOT_FOUND_STATUS
            );
        }

        try {
            $validatedData = ValidationHelper::validateRequest($request, 'activities', 'patch', ['id' => $id]);

            if (! $validatedData['success']) {
                return ApiResponse::error(
                    'VALIDATION_ERROR',
                    'Invalid parameters provided.',
                    $validatedData['errors'],
                    ApiResponse::INVALID_PARAMETERS_STATUS
                );
            }

            Gate::authorize('update', $request->user());
            $activity->update($validatedData['data']);

            if (!empty($validatedData['data']['days'])) {
                $activity->days()->sync($validatedData['data']['days']);
            }

            return ApiResponse::success(new ActivityResource($activity), 'Activity partially updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error patching activity', ['exception' => $e->getMessage()]);

            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while patching activity.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
