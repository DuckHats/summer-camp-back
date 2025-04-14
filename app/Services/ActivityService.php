<?php

namespace App\Services;

use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Jobs\BulkActivityCreationJob;
use App\Helpers\ValidationHelper;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class ActivityService extends BaseService
{
    public function __construct()
    {
        $this->model = new Activity;
    }

    protected function getRelations(): array
    {
        return ['scheduledActivities'];
    }

    protected function resourceClass()
    {
        return ActivityResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return ['scheduledActivities'];
    }

    public function bulkActivities(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'activities', 'bulkActivities');

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        BulkActivityCreationJob::dispatch($request->input('activities'))->onQueue('bulk-processing');

        return ApiResponse::success([], 'Activity creation in progress.', ApiResponse::ACCEPTED_STATUS);
    }
}
