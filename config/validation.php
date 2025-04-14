<?php

// Path: config/validation.php
// Validation rules for all the api endpoints

return [
    'auth' => [
        'login' => [
            'user' => 'required',
            'password' => 'required',
        ],
        'register' => [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            // Modificar especificacions de password
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
            'phone' => 'nullable|string|max:20',
        ],
        'reset_password' => [
            'email' => 'required|email|exists:users,email',
            // Modificar especificacions de password
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ],
        'send_reset_code' => [
            'email' => 'required|email|exists:users,email',
        ],
        'send_email_verification_code' => [
            'email' => 'required|email|exists:users,email',
        ],
        'verify_email' => [
            'email' => 'required|email|exists:users,email',
            'verification_code' => 'required|integer',
        ],
        'send_phone_verification_code' => [
            'phone' => 'required|integer',
        ],
        'verify_phone' => [
            'phone' => 'required|integer',
            'verification_code' => 'required|integer',
        ],
    ],
    'users' => [
        'store' => [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            // Modificar especificacions de password
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
        ],
        'update' => [
            'username' => 'required|string|max:255|unique:users,username,{id}',
            'email' => 'required|string|email|max:255|unique:users,email,{id}',
            // Modificar especificacions de password
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string',
        ],
        'patch' => [
            'username' => 'nullable|string|max:255|unique:users,username,{id}',
            'email' => 'nullable|string|email|max:255|unique:users,email,{id}',
            // Modificar especificacions de password
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string',
        ],
        'unBan' => [
            'reason' => 'nullable|string',
        ],
        'avatar' => [
            'avatar' => 'required|string',
        ],
        'bulkUsers' => [
            'users' => 'required|array',
            'users.*.username' => 'required|string|unique:users,username',
            'users.*.phone' => 'nullable|string',
            'users.*.email' => 'required|email|unique:users,email',
            'users.*.status' => 'nullable|integer|in:0,1',
            'users.*.password' => 'required|string|min:6',
            'users.*.childs' => 'nullable|array',
            'users.*.childs.*.dni' => 'required|string|unique:childs,dni',
            'users.*.childs.*.first_name' => 'required|string',
            'users.*.childs.*.last_name' => 'required|string',
            'users.*.childs.*.birth_date' => 'required|date',
            'users.*.childs.*.group_id' => 'nullable|integer',
            'users.*.childs.*.profile_picture_url' => 'nullable|string',
            'users.*.childs.*.profile_extra_info' => 'nullable|string',
            'users.*.childs.*.gender' => 'nullable|string|in:male,female,other',
        ],
    ],

    'errors' => [
        'store' => [
            'error_code' => 'required|string',
            'error_message' => 'required|string',
            'stack_trace' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'session_id' => 'nullable|integer',
            'occurred_at' => 'required|date',
        ],
        'update' => [
            'error_code' => 'required|string',
            'error_message' => 'required|string',
            'stack_trace' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'session_id' => 'nullable|integer',
            'occurred_at' => 'required|date',
        ],
    ],

    'posts' => [
        'store' => [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ],
        'update' => [
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ],
        'patch' => [
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ],
    ],

    'user_settings' => [
        'store' => [
            'user_id' => 'required|exists:users,id',
            'key' => 'required|string',
            'value' => 'required|string',
        ],
        'update' => [
            'key' => 'required|string',
            'value' => 'required|string',
        ],
        'patch' => [
            'key' => 'nullable|string',
            'value' => 'nullable|string',
        ],
    ],

    'policies' => [
        'store' => [
            'user_id' => 'required|exists:users,id',
            'accept_newsletter' => 'required|integer',
            'accept_privacy_policy' => 'required|integer',
            'accept_terms_of_use' => 'required|integer',
        ],
        'update' => [
            'user_id' => 'required|exists:users,id',
            'accept_newsletter' => 'required|integer',
            'accept_privacy_policy' => 'required|integer',
            'accept_terms_of_use' => 'required|integer',
        ],
        'patch' => [
            'user_id' => 'nullable|exists:users,id',
            'accept_newsletter' => 'nullable|integer',
            'accept_privacy_policy' => 'nullable|integer',
            'accept_terms_of_use' => 'nullable|integer',
        ],
    ],
    'notifications' => [
        'store' => [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'data' => 'required|string',
            'read_at' => 'nullable|date',
        ],
        'update' => [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'data' => 'required|string',
            'read_at' => 'nullable|date',
        ],
        'patch' => [
            'user_id' => 'required|exists:users,id',
            'type' => 'nullable|string',
            'data' => 'nullable|string',
            'read_at' => 'nullable|date',
        ],
    ],
    'childs' => [
        'store' => [
            'dni' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'group_id' => 'required|exists:groups,id',
            'profile_picture_url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'profile_extra_info' => 'nullable|string',
            'gender' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ],
        'update' => [
            'dni' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'group_id' => 'nullable|exists:groups,id',
            'profile_picture_url' => 'nullable',
            'profile_extra_info' => 'nullable|string',
            'gender' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ],
        'patch' => [
            'dni' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'group_id' => 'nullable|exists:groups,id',
            'profile_picture_url' => 'nullable',
            'profile_extra_info' => 'nullable|string',
            'gender' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ],
        'multiple_inspect' => [
            'children_ids' => 'required|array',
            'children_ids.*' => 'integer|exists:childs,id',
        ],
    ],
    'groups' => [
        'store' => [
            'name' => 'required|string|max:255',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'monitor_id' => 'required|exists:monitors,id',
        ],
        'update' => [
            'name' => 'nullable|string|max:255',
            'profile_picture' => 'nullable',
            'monitor_id' => 'nullable|exists:monitors,id',

        ],
        'patch' => [
            'name' => 'nullable|string|max:255',
            'profile_picture' => 'nullable',
            'monitor_id' => 'nullable|exists:monitors,id',

        ],
        'bulkGroups' => [
            'groups' => 'required|array',
            'groups.*.name' => 'required|string|max:255',
            'groups.*.monitor_id' => 'nullable|exists:monitors,id',
            'groups.*.profile_picture' => 'nullable|url',

        ],
    ],
    'activities' => [
        'store' => [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ],
        'update' => [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable',

        ],
        'patch' => [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable',

        ],
        'bulkActivities' => [
            'activities' => 'required|array',
            'activities.*.name' => 'required|string|max:255',
            'activities.*.description' => 'nullable|string',
            'activities.*.cover_image' => 'nullable|url',

        ],
    ],
    'monitors' => [
        'store' => [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:monitors,email',
            'phone' => 'nullable|string|max:20',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'extra_info' => 'nullable|string',
        ],
        'update' => [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:monitors,email,{id}',
            'phone' => 'nullable|string|max:20',
            'profile_picture' => 'nullable',
            'extra_info' => 'nullable|string',
        ],
        'patch' => [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:monitors,email,{id}',
            'phone' => 'nullable|string|max:20',
            'profile_picture' => 'nullable',
            'extra_info' => 'nullable|string',
        ],
        'bulkMonitors' => [
            'monitors' => 'required|array',
            'monitors.*.first_name' => 'required|string|max:255',
            'monitors.*.last_name' => 'required|string|max:255',
            'monitors.*.email' => 'required|email|unique:monitors,email',
            'monitors.*.phone' => 'nullable|string|max:20',
            'monitors.*.extra_info' => 'nullable|string',
            'monitors.*.profile_picture' => 'nullable|url',
        ],
    ],
    'photos' => [
        'store' => [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'group_id' => 'required|exists:groups,id',
            'image_url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ],
        'update' => [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
            'image_url' => 'nullable',
        ],
        'patch' => [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
            'image_url' => 'nullable',
        ],
    ],
    'scheduled_activities' => [
        'store' => [
            'activity_id' => 'required|exists:activities,id',
            'group_id' => 'required|exists:groups,id',
            'initial_date' => 'required|date_format:Y-m-d',
            'final_date' => 'required|date_format:Y-m-d',
            'initial_hour' => 'required|date_format:H:i:s',
            'final_hour' => 'required|date_format:H:i:s',
            'location' => 'required|string|max:255',
        ],
        'update' => [
            'activity_id' => 'required|exists:activities,id',
            'group_id' => 'required|exists:groups,id',
            'initial_date' => 'required|date_format:Y-m-d',
            'final_date' => 'required|date_format:Y-m-d',
            'initial_hour' => 'required|date_format:H:i:s',
            'final_hour' => 'required|date_format:H:i:s',
            'location' => 'required|string|max:255',
        ],
        'patch' => [
            'activity_id' => 'nullable|exists:activities,id',
            'group_id' => 'nullable|exists:groups,id',
            'initial_date' => 'nullable|date_format:Y-m-d',
            'final_date' => 'nullable|date_format:Y-m-d',
            'initial_hour' => 'nullable|date_format:H:i:s',
            'final_hour' => 'nullable|date_format:H:i:s',
            'location' => 'nullable|string|max:255',
        ],
        'bulkScheduledActivities' => [
            'scheduled_activities' => 'required|array',
            'scheduled_activities.*.activity_id' => 'required|exists:activities,id',
            'scheduled_activities.*.group_id' => 'required|exists:groups,id',
            'scheduled_activities.*.initial_date' => 'required|date',
            'scheduled_activities.*.final_date' => 'required|date|after_or_equal:scheduled_activities.*.initial_date',
            'scheduled_activities.*.initial_hour' => 'required|date_format:H:i',
            'scheduled_activities.*.final_hour' => 'required|date_format:H:i|after:scheduled_activities.*.initial_hour',
            'scheduled_activities.*.location' => 'required|string|max:255',

        ],
    ],
];
