<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\MonitorResource;
use App\Jobs\BulkMonitorCreationJob;
use App\Models\Monitor;
use Illuminate\Http\Request;

class MonitorService extends BaseService
{
    public function __construct()
    {
        $this->model = new Monitor;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return MonitorResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }

    public function bulkMonitors(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'monitors', 'bulkMonitors');

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        BulkMonitorCreationJob::dispatch($request->input('monitors'))->onQueue('bulk-processing');

        return ApiResponse::success([], 'Monitor creation in progress.', ApiResponse::ACCEPTED_STATUS);
    }
}
