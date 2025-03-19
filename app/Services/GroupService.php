<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class GroupService
{
    const GROUP_PER_PAGE = 25;

    const MAX_GROUPS_PER_PAGE = 100;

    public function getAllGroups(Request $request)
    {
        try {
            $query = Group::query()->with('sons');
            $perPage = min($request->get('per_page', self::GROUP_PER_PAGE), self::MAX_GROUPS_PER_PAGE);
            $groups = $query->paginate($perPage);

            return GroupResource::collection($groups)
                ->additional(['status' => 'success', 'message' => 'List of groups retrieved successfully.', 'code' => ApiResponse::OK_STATUS]);
        } catch (\Throwable $e) {
            Log::error('Error fetching groups', ['exception' => $e->getMessage()]);

            return ApiResponse::error('FETCH_FAILED', 'Error while retrieving groups.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function createGroup(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'groups', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $group = new Group($validatedData['data']);
            Gate::authorize('create', $group);
            $group->save();

            $group->load('sons');

            return ApiResponse::success(new GroupResource($group), 'Group created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error creating group', ['exception' => $e->getMessage()]);

            return ApiResponse::error('CREATE_FAILED', 'Error while creating group.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }


    public function getGroupById(Request $request, $id)
    {
        try {
            $query = Group::where('id', $id);

            $group = $query->with('sons')->first();
            if (! $group) {
                return ApiResponse::error('NOT_FOUND', 'Group not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            return ApiResponse::success(new GroupResource($group), 'Group retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error retrieving group', ['exception' => $e->getMessage()]);

            return ApiResponse::error('NOT_FOUND', 'Group not found.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function updateGroup(Request $request, $id)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'groups', 'update', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $group = Group::find($id);
            if (! $group) {
                return ApiResponse::error('NOT_FOUND', 'Group not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('update', $group);
            $group->update($validatedData['data']);

            $group->load('sons');

            return ApiResponse::success(new GroupResource($group), 'Group updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error updating group', ['exception' => $e->getMessage()]);

            return ApiResponse::error('UPDATE_FAILED', 'Error while updating group.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }


    public function deleteGroup($id)
    {
        try {
            $group = Group::find($id);
            if (! $group) {
                return ApiResponse::error('NOT_FOUND', 'Group not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            Gate::authorize('delete', $group);
            $group->delete();

            return ApiResponse::success([], 'Group deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error deleting group', ['exception' => $e->getMessage()]);

            return ApiResponse::error('DELETE_FAILED', 'Error while deleting group.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function patchGroup(Request $request, $id)
    {
        $group = Group::find($id);
        if (! $group) {
            return ApiResponse::error('NOT_FOUND', 'Group not found.', [], ApiResponse::NOT_FOUND_STATUS);
        }

        try {
            $validatedData = ValidationHelper::validateRequest($request, 'groups', 'patch', ['id' => $id]);

            if (! $validatedData['success']) {
                return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
            }

            Gate::authorize('update', $group);
            $group->update($validatedData['data']);

            $group->load('sons');

            return ApiResponse::success(new GroupResource($group), 'Group partially updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error patching group', ['exception' => $e->getMessage()]);

            return ApiResponse::error('UPDATE_FAILED', 'Error while patching group.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }
}
