<?php

namespace App\Services;

use App\Http\Resources\PhotoResource;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Photo;

class PhotoService extends BaseService
{
    public function __construct()
    {
        $this->model = new Photo;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return PhotoResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }

    public function createPhotoWithImage($request)
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

            if ($request->hasFile('image_url')) {
                $image = $request->file('image_url');

                $uniqueFileName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('photos', $uniqueFileName, 'public');
                $data['image_url'] = env('APP_URL') . 'storage/photos/' . $uniqueFileName;

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
