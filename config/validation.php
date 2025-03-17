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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            // Modificar especificacions de password
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
            'phone' => 'nullable|string|max:20',
            'profile_picture_url' => 'nullable|url',
            'gender' => 'nullable|string|in:male,female,other',
            'location' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'cv_path' => 'nullable|file',
            'portfolio_url' => 'nullable|url',
            'level' => 'nullable|string|max:255',
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // Modificar especificacions de password
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
            'profile_picture_url' => 'nullable|url',
            'profile_short_description' => 'nullable|string',
            'profile_description' => 'nullable|string',
            'gender' => 'nullable|string',
            'location' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'cv_path' => 'nullable|string',
            'portfolio_url' => 'nullable|url',
            'level' => 'nullable|string',
        ],
        'update' => [
            'username' => 'required|string|max:255|unique:users,username,{id}',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,{id}',
            // Modificar especificacions de password
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string',
            'profile_picture_url' => 'nullable|url',
            'profile_short_description' => 'nullable|string',
            'profile_description' => 'nullable|string',
            'gender' => 'nullable|string',
            'location' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'cv_path' => 'nullable|string',
            'portfolio_url' => 'nullable|url',
            'level' => 'nullable|string',
        ],
        'patch' => [
            'username' => 'nullable|string|max:255|unique:users,username,{id}',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,{id}',
            // Modificar especificacions de password
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string',
            'profile_picture_url' => 'nullable|url',
            'profile_short_description' => 'nullable|string',
            'profile_description' => 'nullable|string',
            'gender' => 'nullable|string',
            'location' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'cv_path' => 'nullable|string',
            'portfolio_url' => 'nullable|url',
            'level' => 'nullable|string',
        ],
        'permaBan' => [
            'reason' => 'nullable|string',
        ],
        'unBan' => [
            'reason' => 'nullable|string',
        ],
        'avatar' => [
            'avatar' => 'required|string',
        ],
        'tempBanUser' => [
            'days' => 'required|integer|min:1',
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
];
