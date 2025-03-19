<?php

// Path: config/validation.php
// Validation rules for all the api endpoints

return [
    'auth' => [
        'login' => [
            'email' => 'required|email|exists:users,email',
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
    'sons' => [
        'store' => [
            'dni' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'group_id' => 'required|exists:groups,id',
            'profile_picture_url' => 'nullable|string',
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
            'profile_picture_url' => 'nullable|string',
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
            'profile_picture_url' => 'nullable|string',
            'profile_extra_info' => 'nullable|string',
            'gender' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ],
    ],
    'groups' => [
        'store' => [
            'name' => 'required|string|max:255',
            'profile_picture' => 'required|string',
        ],
        'update' => [
            'name' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|string',
        ],
        'patch' => [
            'name' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|string',
        ],
    ],
    'activities' => [
        'store' => [
            'name' => 'required|string|max:255',
            'initial_hour' => 'required|date_format:H:i:s',
            'final_hour' => 'required|date_format:H:i:s|after:initial_hour',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'location' => 'required|string',
            'group_id' => 'required|exists:groups,id',
            'days' => 'required|array',
            'days.*' => 'required|exists:days,id'
        ],
        'update' => [
            'name' => 'nullable|string|max:255',
            'initial_hour' => 'nullable|date_format:H:i:s',
            'final_hour' => 'nullable|date_format:H:i:s|after:initial_hour',
            'duration' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'location' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
            'days' => 'nullable|array',
            'days.*' => 'nullable|exists:days,id'
        ],
        'patch' => [
            'name' => 'nullable|string|max:255',
            'initial_hour' => 'nullable|date_format:H:i:s',
            'final_hour' => 'nullable|date_format:H:i:s|after:initial_hour',
            'duration' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'location' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
            'days' => 'nullable|array',
            'days.*' => 'nullable|exists:days,id'
        ],
    ]
];
