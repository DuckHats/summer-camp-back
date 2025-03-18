<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\UserSettingResource;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserSettingService
{
    const SETTING_PER_PAGE = 25;

    const MAX_SETTINGS_PER_PAGE = 100;

    public function getUserSettings(Request $request)
    {
        try {
            $query = UserSetting::query();

            $perPage = min($request->get('per_page', self::SETTING_PER_PAGE), self::MAX_SETTINGS_PER_PAGE);
            $settings = $query->paginate($perPage);

            return UserSettingResource::collection($settings)->additional([
                'status' => 'success',
                'message' => 'List of user settings retrieved successfully.',
                'code' => ApiResponse::OK_STATUS,
            ]);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving user settings.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function createUserSetting(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'user_settings', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $setting = new UserSetting($validatedData['data']);
            Gate::authorize('create', $setting);
            $setting->save();

            return ApiResponse::success(new UserSettingResource($setting), 'User setting created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'CREATE_FAILED',
                'Error while creating user setting.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function getUserSettingById($id)
    {
        try {
            $setting = UserSetting::find($id);

            if (! $setting) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User setting not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            return ApiResponse::success(new UserSettingResource($setting), 'User setting retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving user setting.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function updateUserSetting(Request $request, $id)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'user_settings', 'update', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $setting = UserSetting::find($id);

            if (! $setting) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User setting not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            Gate::authorize('update', $setting);

            $setting->update($validatedData['data']);

            return ApiResponse::success(new UserSettingResource($setting), 'User setting updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating user setting.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function patchUserSetting(Request $request, $id)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'user_settings', 'patch', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $setting = UserSetting::find($id);

            if (! $setting) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User setting not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }
            Gate::authorize('update', $setting);

            $setting->update($validatedData['data']);

            return ApiResponse::success(new UserSettingResource($setting), 'User setting patched successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'PATCH_FAILED',
                'Error while patching user setting.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function deleteUserSetting($id)
    {
        try {
            $setting = UserSetting::find($id);

            if (! $setting) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User setting not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            Gate::authorize('delete', $setting);

            $setting->delete();

            return ApiResponse::success([], 'User setting deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'DELETE_FAILED',
                'Error while deleting user setting.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
