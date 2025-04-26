<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\ChildResource;
use App\Models\Child;

class ChildService extends BaseService
{
    public function __construct()
    {
        $this->model = new Child;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return ChildResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }

    public function inspectChild($request, $id)
    {
        try {
            $child = Child::with([
                'user',
                'group.monitor',
                'group.scheduledActivities',
                'group.scheduledActivities.activity',
                'group.photos',
            ])->find($id);

            if (! $child) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'Child not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            $childResource = new ChildResource($child, true);

            return ApiResponse::success($childResource, 'Child inspect retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving Child.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function multipleInspect($request)
    {
        try {
            $validatedData = ValidationHelper::validateRequest($request, 'childs', 'multiple_inspect');

            if (! $validatedData['success']) {
                return ApiResponse::error(
                    'VALIDATION_ERROR',
                    'Invalid parameters provided.',
                    $validatedData['errors'],
                    ApiResponse::INVALID_PARAMETERS_STATUS
                );
            }

            $children = Child::with([
                'user',
                'group.monitor',
                'group.scheduledActivities',
                'group.scheduledActivities.activity',
                'group.photos',
            ])->whereIn('id', $request->children_ids)
                ->orderByRaw('FIELD(id, '.implode(',', $request->children_ids).')')
                ->get();

            if ($children->isEmpty()) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'No children found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            $childrenResources = $children->map(function ($child) {
                return new ChildResource($child, true);
            });

            return ApiResponse::success(
                $childrenResources,
                'Children inspect retrieved successfully.',
                ApiResponse::OK_STATUS
            );
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving children.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
