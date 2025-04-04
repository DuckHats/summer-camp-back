<?php

namespace App\Services;

use App\Helpers\ApiResponse;
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
                'group.activities.days',
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
}
