<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\MonitorResource;
use App\Models\Monitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class MonitorService
{
    const MONITOR_PER_PAGE = 25;

    const MAX_MONITORS_PER_PAGE = 100;

    public function getAllMonitors(Request $request)
    {
        try {
            $query = Monitor::query();
            $perPage = min($request->get('per_page', self::MONITOR_PER_PAGE), self::MAX_MONITORS_PER_PAGE);
            $monitors = $query->paginate($perPage);

            return MonitorResource::collection($monitors)
                ->additional(['status' => 'success', 'message' => 'List of monitors retrieved successfully.', 'code' => ApiResponse::OK_STATUS]);
        } catch (\Throwable $e) {
            Log::error('Error fetching monitors', ['exception' => $e->getMessage()]);

            return ApiResponse::error('FETCH_FAILED', 'Error while retrieving monitors.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function createMonitor(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'monitors', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $monitor = new Monitor($validatedData['data']);
            Gate::authorize('create', $request->user());
            $monitor->save();

            return ApiResponse::success(new MonitorResource($monitor), 'Monitor created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error creating monitor', ['exception' => $e->getMessage()]);

            return ApiResponse::error('CREATE_FAILED', 'Error while creating monitor.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function getMonitorById(Request $request, $id)
    {
        try {
            $query = Monitor::where('id', $id);

            $monitor = $query->first();
            if (! $monitor) {
                return ApiResponse::error('NOT_FOUND', 'Monitor not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            return ApiResponse::success(new MonitorResource($monitor), 'Monitor retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error retrieving monitor', ['exception' => $e->getMessage()]);

            return ApiResponse::error('NOT_FOUND', 'Monitor not found.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function updateMonitor(Request $request, $id)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'monitors', 'update', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $monitor = Monitor::find($id);
            if (! $monitor) {
                return ApiResponse::error('NOT_FOUND', 'Monitor not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('update', $request->user());
            $monitor->update($validatedData['data']);

            return ApiResponse::success(new MonitorResource($monitor), 'Monitor updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error updating monitor', ['exception' => $e->getMessage()]);

            return ApiResponse::error('UPDATE_FAILED', 'Error while updating monitor.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function deleteMonitor(Request $request, $id)
    {
        try {
            $monitor = Monitor::find($id);
            if (! $monitor) {
                return ApiResponse::error('NOT_FOUND', 'Monitor not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('delete', $request->user());
            $monitor->delete();

            return ApiResponse::success([], 'Monitor deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error deleting monitor', ['exception' => $e->getMessage()]);

            return ApiResponse::error('DELETE_FAILED', 'Error while deleting monitor.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function patchMonitor(Request $request, $id)
    {
        $monitor = Monitor::find($id);
        if (! $monitor) {
            return ApiResponse::error(
                'NOT_FOUND',
                'Monitor not found.',
                [],
                ApiResponse::NOT_FOUND_STATUS
            );
        }

        try {
            $validatedData = ValidationHelper::validateRequest($request, 'monitors', 'patch', ['id' => $id]);

            if (! $validatedData['success']) {
                return ApiResponse::error(
                    'VALIDATION_ERROR',
                    'Invalid parameters provided.',
                    $validatedData['errors'],
                    ApiResponse::INVALID_PARAMETERS_STATUS
                );
            }

            Gate::authorize('update', $request->user());
            $monitor->update($validatedData['data']);

            return ApiResponse::success(new MonitorResource($monitor), 'Monitor partially updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error patching monitor', ['exception' => $e->getMessage()]);

            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while patching monitor.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
