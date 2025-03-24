<?php

use App\Constants\RouteConstants;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// v1 routes
Route::middleware('throttle:api')->group(function () {

    Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post(RouteConstants::REGISTER, 'register')->name('auth.register');
            Route::post(RouteConstants::LOGIN, 'login')->name('auth.login');

            Route::post(RouteConstants::LOGOUT, 'logout')->name('auth.logout')->middleware('auth:sanctum');
            Route::post(RouteConstants::LOGOUT_ALL_SESSIONS, 'logoutAllSessions')->name('auth.logoutAll')->middleware('auth:sanctum');
            Route::post(RouteConstants::FORGOT_PASSWORD, 'sendResetCode')->name('auth.sendResetCode');
            Route::post(RouteConstants::RESET_PASSWORD, 'resetPassword')->name('auth.resetPassword');

            Route::post(RouteConstants::VERIFY_EMAIL, 'sendEmailVerificationCode')->name('auth.sendEmailVerificationCode')->middleware('auth:sanctum');
            Route::post(RouteConstants::VERIFY_EMAIL_CONFIRM, 'verifyEmail')->name('auth.verifyEmail')->middleware('auth:sanctum');

            Route::post(RouteConstants::VERIFY_PHONE, 'sendPhoneVerificationCode')->name('auth.sendPhoneVerificationCode')->middleware('auth:sanctum');
            Route::post(RouteConstants::VERIFY_PHONE_CONFIRM, 'verifyPhone')->name('auth.verifyPhone')->middleware('auth:sanctum');
        });

        Route::controller(UserController::class)->group(function () {
            Route::get(RouteConstants::USERS, 'index')->name('users.index');
            Route::get(RouteConstants::USERS_ME, 'me')->name('users.me')->middleware('auth:sanctum');
            Route::get(RouteConstants::USERS_DETAIL, 'show')->name('users.show');

            Route::post(RouteConstants::USERS_CREATE, 'store')->name('users.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::USERS_UPDATE, 'update')->name('users.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::USERS_PATCH, 'patch')->name('users.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::USERS_DESTROY, 'destroy')->name('users.destroy')->middleware('auth:sanctum');

            Route::post(RouteConstants::USERS_AVATAR, 'updateAvatar')->name('users.avatar')->middleware('auth:sanctum');
            Route::post(RouteConstants::USERS_DISABLE, 'disableUser')->name('users.disable')->middleware('auth:sanctum');
            Route::post(RouteConstants::USERS_ENABLE, 'enableUser')->name('users.enable')->middleware('auth:sanctum');

            Route::post(RouteConstants::USERS_BULK, 'bulkUsers')->name('users.bulk')->middleware('auth:sanctum');
        });

        Route::controller(ErrorController::class)->group(function () {
            Route::get(RouteConstants::ERRORS, 'index')->name('errors.index')->middleware('auth:sanctum');
            Route::get(RouteConstants::ERROR_DETAIL, 'show')->name('errors.show')->middleware('auth:sanctum');

            Route::post(RouteConstants::ERROR_CREATE, 'store')->name('errors.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::ERROR_UPDATE, 'update')->name('errors.update')->middleware('auth:sanctum');
            Route::delete(RouteConstants::ERROR_DELETE, 'destroy')->name('errors.destroy')->middleware('auth:sanctum');
        });

        Route::controller(PostController::class)->group(function () {
            Route::get(RouteConstants::POSTS, 'index')->name('posts.index');
            Route::get(RouteConstants::POST_DETAIL, 'show')->name('posts.show');

            Route::post(RouteConstants::POST_CREATE, 'store')->name('posts.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::POST_UPADTE, 'update')->name('posts.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::POST_PATCH, 'patch')->name('posts.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::POST_DELETE, 'destroy')->name('posts.destroy')->middleware('auth:sanctum');
        });

        Route::controller(UserSettingController::class)->group(function () {
            Route::get(RouteConstants::USER_SETTINGS, 'index')->name('user_settings.index');
            Route::get(RouteConstants::USER_SETTING_DETAIL, 'show')->name('user_settings.show');

            Route::post(RouteConstants::USER_SETTING_CREATE, 'store')->name('user_settings.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::USER_SETTING_UPDATE, 'update')->name('user_settings.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::USER_SETTING_PATCH, 'patch')->name('user_settings.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::USER_SETTING_DELETE, 'destroy')->name('user_settings.destroy')->middleware('auth:sanctum');
        });

        Route::controller(ChildController::class)->group(function () {
            Route::get(RouteConstants::CHILDS, 'index')->name('childs.index');
            Route::get(RouteConstants::CHILD_DETAIL, 'show')->name('childs.show');

            Route::post(RouteConstants::CHILD_CREATE, 'store')->name('childs.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::CHILD_UPDATE, 'update')->name('childs.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::CHILD_PATCH, 'patch')->name('childs.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::CHILD_DELETE, 'destroy')->name('childs.destroy')->middleware('auth:sanctum');
        });

        Route::controller(PolicyController::class)->group(function () {
            Route::get(RouteConstants::USER_POLICY, 'index')->name('policy.index');
            Route::get(RouteConstants::USER_POLICY_DETAIL, 'show')->name('policy.show');

            Route::post(RouteConstants::USER_POLICY_CREATE, 'store')->name('policy.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::USER_POLICY_UPDATE, 'update')->name('policy.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::USER_POLICY_PATCH, 'patch')->name('policy.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::USER_POLICY_DELETE, 'destroy')->name('policy.destroy')->middleware('auth:sanctum');
        });

        Route::controller(NotificationController::class)->group(function () {
            Route::get(RouteConstants::USER_NOTIFICATION, 'index')->name('notification.index');
            Route::get(RouteConstants::USER_NOTIFICATION_DETAIL, 'show')->name('notification.show');

            Route::post(RouteConstants::USER_NOTIFICATION_CREATE, 'store')->name('notification.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::USER_NOTIFICATION_UPDATE, 'update')->name('notification.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::USER_NOTIFICATION_PATCH, 'patch')->name('notification.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::USER_NOTIFICATION_DELETE, 'destroy')->name('notification.destroy')->middleware('auth:sanctum');
        });

        Route::controller(GroupController::class)->group(function () {
            Route::get(RouteConstants::GROUPS, 'index')->name('groups.index');
            Route::get(RouteConstants::GROUP_DETAIL, 'show')->name('groups.show');

            Route::post(RouteConstants::GROUP_CREATE, 'store')->name('groups.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::GROUP_UPDATE, 'update')->name('groups.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::GROUP_PATCH, 'patch')->name('groups.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::GROUP_DELETE, 'destroy')->name('groups.destroy')->middleware('auth:sanctum');
        });

        Route::controller(ActivityController::class)->group(function () {
            Route::get(RouteConstants::ACTIVITIES, 'index')->name('activities.index');
            Route::get(RouteConstants::ACTIVITY_DETAIL, 'show')->name('activities.show');

            Route::post(RouteConstants::ACTIVITY_CREATE, 'store')->name('activities.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::ACTIVITY_UPDATE, 'update')->name('activities.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::ACTIVITY_PATCH, 'patch')->name('activities.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::ACTIVITY_DELETE, 'destroy')->name('activities.destroy')->middleware('auth:sanctum');
        });

        Route::controller(MonitorController::class)->group(function () {
            Route::get(RouteConstants::MONITORS, 'index')->name('monitors.index');
            Route::get(RouteConstants::MONITOR_DETAIL, 'show')->name('monitors.show');

            Route::post(RouteConstants::MONITOR_CREATE, 'store')->name('monitors.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::MONITOR_UPDATE, 'update')->name('monitors.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::MONITOR_PATCH, 'patch')->name('monitors.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::MONITOR_DELETE, 'destroy')->name('monitors.destroy')->middleware('auth:sanctum');
        });

        Route::controller(PhotoController::class)->group(function () {
            Route::get(RouteConstants::PHOTOS, 'index')->name('photos.index');
            Route::get(RouteConstants::PHOTO_DETAIL, 'show')->name('photos.show');

            Route::post(RouteConstants::PHOTO_CREATE, 'store')->name('photos.store')->middleware('auth:sanctum');
            Route::put(RouteConstants::PHOTO_UPDATE, 'update')->name('photos.update')->middleware('auth:sanctum');
            Route::patch(RouteConstants::PHOTO_PATCH, 'patch')->name('photos.patch')->middleware('auth:sanctum');
            Route::delete(RouteConstants::PHOTO_DELETE, 'destroy')->name('photos.destroy')->middleware('auth:sanctum');
        });
    });
});
