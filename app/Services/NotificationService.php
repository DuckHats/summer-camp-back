<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NotificationService
{
    const POLICY_PER_PAGE = 25;

    const MAX_POLICY_PER_PAGE = 100;

    public function getNotifications(Request $request)
    {
        try {
            $query = Notification::query();

            $perPage = min($request->get('per_page', self::POLICY_PER_PAGE), self::MAX_POLICY_PER_PAGE);
            $notifications = $query->paginate($perPage);

            return NotificationResource::collection($notifications)->additional([
                'status' => 'success',
                'message' => 'List of notifications retrieved successfully.',
                'code' => ApiResponse::OK_STATUS,
            ]);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving notifications.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function createNotification(Request $request)
    {

        $validatedData = ValidationHelper::validateRequest($request, 'notifications', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $notification = new Notification($validatedData['data']);
            Gate::authorize('create', $notification);
            $notification->save();

            return ApiResponse::success(new NotificationResource($notification), 'Notification created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'CREATE_FAILED',
                'Error while creating notification.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function getNotificationById($id)
    {
        try {
            $notification = Notification::find($id);
            if (! $notification) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'Notification not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            return ApiResponse::success(new NotificationResource($notification), 'Notification retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving notification.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function updateNotification(Request $request, $id)
    {

        $validatedData = ValidationHelper::validateRequest($request, 'notifications', 'update', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $notification = Notification::find($id);
            if (! $notification) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'Notification not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }
            Gate::authorize('update', $notification);
            $notification->update($validatedData['data']);

            return ApiResponse::success(new NotificationResource($notification), 'Notification updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating notification.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function deleteNotification($id)
    {
        $notification = Notification::find($id);
        if (! $notification) {
            return ApiResponse::error(
                'NOT_FOUND',
                'Notification not found.',
                [],
                ApiResponse::NOT_FOUND_STATUS
            );
        }
        Gate::authorize('delete', $notification);

        try {
            $notification->delete();

            return ApiResponse::success([], 'Notification deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'DELETE_FAILED',
                'Error while deleting notification.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function patchNotification(Request $request, $id)
    {

        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'notifications', 'patch', $placeholders);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $notification = Notification::find($id);
            if (! $notification) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'Notification not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }
            Gate::authorize('update', $notification);
            $notification->update($validatedData['data']);

            return ApiResponse::success(new NotificationResource($notification), 'Notification updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating notification.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}
