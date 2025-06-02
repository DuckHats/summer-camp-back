<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\OptimizedScheduledActivityResource;
use App\Http\Resources\ScheduledActivityResource;
use App\Jobs\BulkScheduledActivityCreationJob;
use App\Models\ScheduledActivity;
use Illuminate\Http\Request;

class ScheduledActivityService extends BaseService
{
    public function __construct()
    {
        $this->model = new ScheduledActivity;
    }

    protected function getRelations(): array
    {
        return ['activity'];
    }

    protected function resourceClass()
    {
        return ScheduledActivityResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return ['activity'];
    }

    public function bulkScheduledActivities(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'scheduled_activities', 'bulkScheduledActivities');

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        BulkScheduledActivityCreationJob::dispatch($request->input('scheduled_activities'))->onQueue('bulk-processing');

        return ApiResponse::success([], 'Scheduled activities creation in progress.', ApiResponse::ACCEPTED_STATUS);
    }

    public function getOptimizedActivities($request)
    {
        try {
            $scheduledActivities = ScheduledActivity::with([
                'activity',
                'group.monitor',
                'group.childs.user',
            ])->get();

            $optimizedData = [];
            foreach ($scheduledActivities as $scheduledActivity) {
                $date = $scheduledActivity->initial_date;
                if (! isset($optimizedData[$date])) {
                    $optimizedData[$date] = [];
                }

                $optimizedData[$date][] = new OptimizedScheduledActivityResource($scheduledActivity);
            }

            ksort($optimizedData);

            return ApiResponse::success(
                $optimizedData,
                'Optimized activities retrieved successfully.',
                ApiResponse::OK_STATUS
            );
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving optimized activities.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
