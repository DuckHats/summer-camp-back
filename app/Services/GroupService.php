<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\GroupResource;
use App\Jobs\BulkGroupCreationJob;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupService extends BaseService
{
    public function __construct()
    {
        $this->model = new Group;
    }

    protected function getRelations(): array
    {
        return ['childs', 'scheduledActivities', 'monitor', 'photos', 'scheduledActivities.activity'];
    }

    protected function resourceClass()
    {
        return GroupResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return ['scheduledActivities', 'photos', 'scheduledActivities.activity'];
    }

    public function bulkGroups(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'groups', 'bulkGroups');

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        BulkGroupCreationJob::dispatch($request->input('groups'))->onQueue('bulk-processing');

        return ApiResponse::success([], 'Group creation in progress.', ApiResponse::ACCEPTED_STATUS);
    }
}
