<?php

namespace App\Services;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;

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

    public function createGroupWithImage($request)
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

            if ($request->hasFile('profile_picture')) {
                $image = $request->file('profile_picture');

                $uniqueFileName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('profile_pictures', $uniqueFileName, 'public');
                $data['profile_picture'] = env('APP_URL') . 'storage/profile_pictures/' . $uniqueFileName;

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
