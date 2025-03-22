<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SetupController extends Controller
{
    public function setup()
    {
        $user = User::where('email', 'AdminSetup@admin.com')->first();

        if (! $user) {
            $user = User::create([
                'dni' => '1234567890',
                'username' => 'AdminSetup',
                'first_name' => 'AdminSetup',
                'last_name' => 'AdminSetup',
                'email' => 'AdminSetup@admin.com',
                'password' => Hash::make('password123'),
                'phone' => '1234567890',
                'profile_picture_url' => 'http://example.com/profile.jpg',
                'profile_extra_info' => 'Admin extra info',
                'gender' => 'male',
                'location' => 'Admin City',
                'birth_date' => '1990-01-01',
            ]);

            UserSetting::create([
                'user_id' => $user->id,
                'key' => 'role',
                'value' => 'admin',
            ]);
        }

        if (Auth::attempt(['email' => 'AdminSetup@admin.com', 'password' => 'password123'])) {

            $token = $user->createToken('auth_token')->plainTextToken;

            UserSetting::create([
                'user_id' => $user->id,
                'key' => 'role',
                'value' => 'admin',
            ]);

            return response($token, 200);
        }

        return response('Failed to login', 500);
    }
}
