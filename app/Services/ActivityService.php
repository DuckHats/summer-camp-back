<?php

namespace App\Services;

use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;

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

    public function createActivityWithImage($request)
    {
        if (! $this->isAuthorized('create')) {
            return ApiResponse::error('UNAUTHORIZED', 'No tens permisos.', [], ApiResponse::FORBIDDEN_STATUS);
        }

        $validatedData = $this->validateRequest($request, 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $data = $validatedData['data'];

            if ($request->hasFile('cover_image')) {
                $image = $request->file('cover_image');

                $uniqueFileName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('cover_images', $uniqueFileName, 'public');
                $data['cover_image'] = env('APP_URL') . 'storage/cover_images/' . $uniqueFileName;

            }

            $item = $this->model->create($data);
            $this->syncRelations($item, $data);
            $item->load($this->getRelations());

            return ApiResponse::success(
                new ($this->resourceClass())($item),
                'Item created successfully.',
                ApiResponse::CREATED_STATUS
            );
        } catch (\Throwable $e) {
            Log::error('Error creating item', ['exception' => $e->getMessage()]);

            return ApiResponse::error(
                'CREATE_FAILED',
                'Error while creating item.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
