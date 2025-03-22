<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiResponse;
use App\Services\Contracts\ServiceInterface;
use App\Helpers\ValidationHelper;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;

abstract class BaseService implements ServiceInterface
{
    protected Model $model;
    protected $min_per_page = 250;
    protected $max_per_page = 3000;

    public function getAll(Request $request)
    {
        try {
            $query = $this->model->query();

            $relations = $this->getRelations();
            if (!empty($relations)) {
                $query->with($relations);
            }

            $perPage = min($request->get('per_page', $this->min_per_page), $this->max_per_page);
            $items = $query->paginate($perPage);

            return ($this->resourceClass())::collection($items)
                ->additional(['status' => 'success', 'message' => 'List retrieved successfully.', 'code' => ApiResponse::OK_STATUS]);
        } catch (\Throwable $e) {
            Log::error('Error fetching data', ['exception' => $e->getMessage()]);
            return ApiResponse::error('FETCH_FAILED', 'Error while retrieving data.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function getById(Request $request, $id)
    {
        try {
            $query = $this->model->where('id', $id);

            $relations = $this->getRelations();
            if (!empty($relations)) {
                $query->with($relations);
            }

            $item = $query->first();

            if (!$item) {
                return ApiResponse::error('NOT_FOUND', 'Item not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error retrieving item', ['exception' => $e->getMessage()]);
            return ApiResponse::error('NOT_FOUND', 'Item not found.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function create(Request $request)
    {

        $validatedData = $this->validateRequest($request, 'store');

        if (!$validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {            
            $item = $this->model->create($validatedData['data']);

            $this->syncRelations($item, $validatedData['data']);

            $item->load($this->getRelations());

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error creating item', ['exception' => $e->getMessage()]);
            return ApiResponse::error('CREATE_FAILED', 'Error while creating item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }


    public function update(Request $request, $id)
    {
        $validatedData = $this->validateRequest($request, 'update', ['id' => $id]);

        if (!$validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $item = $this->model->find($id);
            if (!$item) {
                return ApiResponse::error('NOT_FOUND', 'Item not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            $item->update($validatedData['data']);
            $this->syncRelations($item, $validatedData['data']);
            $item->load($this->getRelations());

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error updating item', ['exception' => $e->getMessage()]);
            return ApiResponse::error('UPDATE_FAILED', 'Error while updating item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function patch(Request $request, $id)
    {
        $validatedData = $this->validateRequest($request, 'patch', ['id' => $id]);

        if (!$validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $item = $this->model->find($id);
            if (!$item) {
                return ApiResponse::error('NOT_FOUND', 'Item not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            $item->update($validatedData['data']);
            $this->syncRelations($item, $validatedData['data']);
            $item->load($this->getRelations());

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error patching item', ['exception' => $e->getMessage()]);
            return ApiResponse::error('UPDATE_FAILED', 'Error while patching item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $item = $this->model->find($id);
            if (!$item) {
                return ApiResponse::error('NOT_FOUND', 'Item not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            $item->delete();

            return ApiResponse::success([], 'Item deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error deleting item', ['exception' => $e->getMessage()]);
            return ApiResponse::error('DELETE_FAILED', 'Error while deleting item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    protected abstract function getRelations(): array;
    protected abstract function resourceClass();

    protected function validateRequest(Request $request, $method, array $extraData = [])
    {
        return ValidationHelper::validateRequest($request, $this->model->getTable(), $method, $extraData);
    }

    protected function syncRelations($model, array $data)
    {
        foreach ($this->getSyncableRelations() as $relation) {
            if (isset($data[$relation])) {
                $model->{$relation}()->sync($data[$relation]);
            }
        }
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
