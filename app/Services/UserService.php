<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Filters\FilterRules\UserFilter;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserTemporaryBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserService
{
    const USER_PER_PAGE = 25;

    const MAX_USERS_PER_PAGE = 100;

    public function getUsers(Request $request)
    {
        try {
            $query = User::query();
            $this->applyRelations($query, $request);

            $perPage = min($request->get('per_page', self::USER_PER_PAGE), self::MAX_USERS_PER_PAGE);
            $users = $query->paginate($perPage);

            return UserResource::collection($users)->additional([
                'status' => 'success',
                'message' => 'List of users retrieved successfully.',
                'code' => ApiResponse::OK_STATUS,
            ]);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving users.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function createUser(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = new User($validatedData['data']);
            $user->save();

            return ApiResponse::success(new UserResource($user), 'User created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'CREATE_FAILED',
                'Error while creating user.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function getUserById(Request $request, $id)
    {
        try {
            $user = User::where('id', $id);
            $user->with('tags');
            $this->applyRelations($user, $request);

            $user = $user->first();
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            $userResource = new UserResource($user);

            return ApiResponse::success($userResource, 'User retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving user.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function updateUser(Request $request, $id)
    {
        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'update', $placeholders);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            Gate::authorize('update', $user);

            $user->update($validatedData['data']);

            return ApiResponse::success(new UserResource($user), 'User updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating user.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function patchUser(Request $request, $id)
    {
        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'patch', $placeholders);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            Gate::authorize('update', $user);

            $user->update($validatedData['data']);

            return ApiResponse::success(new UserResource($user), 'User updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating user.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            Gate::authorize('delete', $user);

            $user->delete();

            return ApiResponse::success([], 'User deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'DELETE_FAILED',
                'Error while deleting user.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function permaBan(Request $request, $id)
    {
        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'permaBan', $placeholders);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            $adminUser = $request->user();

            Gate::authorize('permaBan', $adminUser);

            $user->status = User::STATUS_PERMABAN;
            $user->save();

            return ApiResponse::success(new UserResource($user), 'User permabaned.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while banning user.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function unBan(Request $request, $id)
    {
        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'unbanUser', $placeholders);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $adminUser = $request->user();
            Gate::authorize('unBan', $adminUser);
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            $user->status = User::STATUS_ACTIVE;
            $user->save();

            return ApiResponse::success(new UserResource($user), 'User reactivated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while reactivating user.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function updateAvatar(Request $request, $id, $authUser)
    {
        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'avatar', $placeholders);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            $user->profile_picture_url = $validatedData['data']['avatar'];
            $user->save();

            return ApiResponse::success(new UserResource($user), 'Avatar updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating user.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    private function applyRelations($query, Request $request)
    {
        if ($request->get('with_user_settings')) {
            $query->with('settings');
        }

        if ($request->get('with_user_notifications')) {
            $query->with('notifications');
        }

        if ($request->get('with_policies')) {
            $query->with('policies');
        }
    }
}
