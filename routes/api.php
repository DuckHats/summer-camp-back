<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\ActivityController;
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
        // Auth system
        Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth:sanctum');
        Route::post('/logoutAllSessions', [AuthController::class, 'logoutAllSessions'])->name('auth.logoutAll')->middleware('auth:sanctum');
        Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('auth.sendResetCode');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.resetPassword');

        Route::post('/verify/email', [AuthController::class, 'sendEmailVerificationCode'])->name('auth.sendEmailVerificationCode')->middleware('auth:sanctum');
        Route::post('/verify/email/confirm', [AuthController::class, 'verifyEmail'])->name('auth.verifyEmail')->middleware('auth:sanctum');

        Route::post('/verify/phone', [AuthController::class, 'sendPhoneVerificationCode'])->name('auth.sendPhoneVerificationCode')->middleware('auth:sanctum');
        Route::post('/verify/phone/confirm', [AuthController::class, 'verifyPhone'])->name('auth.verifyPhone')->middleware('auth:sanctum');

        // User routes
        Route::get('/users', 'UserController@index')->name('users.index');
        Route::get('/users/me', [UserController::class, 'me'])->name('users.me')->middleware('auth:sanctum');
        Route::get('/users/{id}', 'UserController@show')->name('users.show');

        Route::post('/users', 'UserController@store')->name('users.store')->middleware('auth:sanctum');
        Route::put('/users/{id}', 'UserController@update')->name('users.update')->middleware('auth:sanctum');
        Route::patch('/users/{id}', [UserController::class, 'patch'])->name('users.patch')->middleware('auth:sanctum');
        Route::delete('/users/{id}', 'UserController@destroy')->name('users.destroy')->middleware('auth:sanctum');
        Route::post('/users/{id}/avatar', [UserController::class, 'updateAvatar'])->name('users.avatar')->middleware('auth:sanctum');

        Route::post('/users/{id}/disable', [UserController::class, 'disableUser'])->name('users.disable')->middleware('auth:sanctum');
        Route::post('/users/{id}/enable', [UserController::class, 'enableUser'])->name('users.enable')->middleware('auth:sanctum');

        // Error routes
        Route::get('/errors', 'ErrorController@index')->name('errors.index')->middleware('auth:sanctum');
        Route::get('/errors/{id}', 'ErrorController@show')->name('errors.show')->middleware('auth:sanctum');

        Route::post('/errors', 'ErrorController@store')->name('errors.store')->middleware('auth:sanctum');
        Route::put('/errors/{id}', 'ErrorController@update')->name('errors.update')->middleware('auth:sanctum');
        Route::delete('/errors/{id}', 'ErrorController@destroy')->name('errors.destroy')->middleware('auth:sanctum');

        // Post routes
        Route::get('/posts', 'PostController@index')->name('posts.index');
        Route::get('/posts/{id}', 'PostController@show')->name('posts.show');

        Route::post('/posts', 'PostController@store')->name('posts.store')->middleware('auth:sanctum');
        Route::put('/posts/{id}', 'PostController@update')->name('posts.update')->middleware('auth:sanctum');
        Route::patch('/posts/{id}', [PostController::class, 'patch'])->name('posts.patch')->middleware('auth:sanctum');
        Route::delete('/posts/{id}', 'PostController@destroy')->name('posts.destroy')->middleware('auth:sanctum');

        // User settings routes
        Route::get('/usettings', 'UserSettingController@index')->name('user_settings.index');
        Route::get('/usettings/{id}', 'UserSettingController@show')->name('user_settings.show');

        Route::post('/usettings', 'UserSettingController@store')->name('user_settings.store')->middleware('auth:sanctum');
        Route::put('/usettings/{id}', 'UserSettingController@update')->name('user_settings.update')->middleware('auth:sanctum');
        Route::patch('/usettings/{id}', [UserSettingController::class, 'patch'])->name('user_settings.patch')->middleware('auth:sanctum');
        Route::delete('/usettings/{id}', 'UserSettingController@destroy')->name('user_settings.destroy')->middleware('auth:sanctum');

        // Son routes
        Route::get('/sons', 'SonController@index')->name('sons.index');
        Route::get('/sons/{id}', 'SonController@show')->name('sons.show');

        Route::post('/sons', 'SonController@store')->name('sons.store')->middleware('auth:sanctum');
        Route::put('/sons/{id}', 'SonController@update')->name('sons.update')->middleware('auth:sanctum');
        Route::patch('/sons/{id}', [SonController::class, 'patch'])->name('sons.patch')->middleware('auth:sanctum');
        Route::delete('/sons/{id}', 'SonController@destroy')->name('sons.destroy')->middleware('auth:sanctum');

        // User policy assignment routes
        Route::get('/upolicy', 'PolicyController@index')->name('policy.index');
        Route::get('/upolicy/{id}', 'PolicyController@show')->name('policy.show');

        Route::post('/upolicy', 'PolicyController@store')->name('policy.store')->middleware('auth:sanctum');
        Route::put('/upolicy/{id}', 'PolicyController@update')->name('policy.update')->middleware('auth:sanctum');
        Route::patch('/upolicy/{id}', [PolicyController::class, 'patch'])->name('policy.patch')->middleware('auth:sanctum');
        Route::delete('/upolicy/{id}', 'PolicyController@destroy')->name('policy.destroy')->middleware('auth:sanctum');

        // User notifications assignment routes
        Route::get('/unotification', 'NotificationController@index')->name('notification.index');
        Route::get('/unotification/{id}', 'NotificationController@show')->name('notification.show');

        Route::post('/unotification', 'NotificationController@store')->name('notification.store')->middleware('auth:sanctum');
        Route::put('/unotification/{id}', 'NotificationController@update')->name('notification.update')->middleware('auth:sanctum');
        Route::patch('/unotification/{id}', [NotificationController::class, 'patch'])->name('notification.patch')->middleware('auth:sanctum');
        Route::delete('/unotification/{id}', 'NotificationController@destroy')->name('notification.destroy')->middleware('auth:sanctum');

        // Group routes
        Route::get('/groups', 'GroupController@index')->name('groups.index');
        Route::get('/groups/{id}', 'GroupController@show')->name('groups.show');

        Route::post('/groups', 'GroupController@store')->name('groups.store')->middleware('auth:sanctum');
        Route::put('/groups/{id}', 'GroupController@update')->name('groups.update')->middleware('auth:sanctum');
        Route::patch('/groups/{id}', [GroupController::class, 'patch'])->name('groups.patch')->middleware('auth:sanctum');
        Route::delete('/groups/{id}', 'GroupController@destroy')->name('groups.destroy')->middleware('auth:sanctum');

        // Activities routes
        Route::get('/activities', 'GroupController@index')->name('activities.index');
        Route::get('/activities/{id}', 'GroupController@show')->name('activities.show');

        Route::post('/activities', 'ActivityController@store')->name('activities.store')->middleware('auth:sanctum');
        Route::put('/activities/{id}', 'ActivityController@update')->name('activities.update')->middleware('auth:sanctum');
        Route::patch('/activities/{id}', [ActivityController::class, 'patch'])->name('activities.patch')->middleware('auth:sanctum');
        Route::delete('/activities/{id}', 'ActivityController@destroy')->name('activities.destroy')->middleware('auth:sanctum');
    });
});
