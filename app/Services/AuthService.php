<?php

namespace App\Services;

use App\Helpers\EmailHelper;
use App\Helpers\PhoneHelper;
use App\Helpers\ValidationHelper;
use App\Mail\EmailVerificationMail;
use App\Mail\PasswordChangedMail;
use App\Mail\ResetPasswordCodeMail;
use App\Mail\WelcomeMail;
use App\Models\EmailVerification;
use App\Models\PhoneVerification;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(Request $request)
    {
        $fields = ValidationHelper::validateRequest($request, 'auth', 'register');
        if (! $fields['success']) {
            throw new \Exception('Invalid parameters provided.');
        }

        $fields = $fields['data'];
        $fields['password'] = Hash::make($fields['password']);
        $user = User::create($fields);
        $this->authRepository->createBasicSettings($user->id);
        $this->authRepository->setWelcomeNotification($user->id);
        $this->authRepository->setBasicPolicies($user->id);
        $this->authRepository->setAdminRole($user->id);
        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        EmailHelper::sendEmail($user->email, WelcomeMail::class, [$user]);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(Request $request)
    {
        $validationResult = ValidationHelper::validateRequest($request, 'auth', 'login');
        if (! $validationResult['success']) {
            throw new \Exception('Invalid parameters provided.');
        }

        $validatedData = $validationResult['data'];

        if (! Auth::attempt(['email' => $validatedData['user'], 'password' => $validatedData['password']])) {
            if (! Auth::attempt(['username' => $validatedData['user'], 'password' => $validatedData['password']])) {
                throw new \Exception('The provided credentials are incorrect.');
            }
        }

        $user = Auth::user();

        if ($user->status != User::STATUS_ACTIVE) {
            Auth::logout();
            throw new \Exception('Your account is banned or inactive.');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function sendResetCode(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'auth', 'send_reset_code');
        if (! $validatedData['success']) {
            throw new \Exception('Invalid parameters provided.');
        }

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            throw new \Exception('User not found.');
        }

        $resetCode = rand(100000, 999999);
        $expiresAt = now()->addMinutes(15);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $resetCode, 'created_at' => now(), 'expires_at' => $expiresAt]
        );

        EmailHelper::sendEmail($user->email, ResetPasswordCodeMail::class, [$resetCode]);
    }

    public function resetPassword(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'auth', 'reset_password');
        if (! $validatedData['success']) {
            throw new \Exception('Invalid parameters provided.');
        }

        $resetEntry = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->code)
            ->first();

        if (! $resetEntry) {
            throw new \Exception('Invalid or expired code.');
        }

        if (now()->greaterThan($resetEntry->expires_at)) {
            throw new \Exception('The code has expired.');
        }

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $request->email)->delete();
        EmailHelper::sendEmail($user->email, PasswordChangedMail::class);
    }

    public function sendEmailVerificationCode(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'auth', 'send_email_verification_code');
        if (! $validatedData['success']) {
            throw new \Exception('Invalid parameters provided.');
        }

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            throw new \Exception('User not found.');
        }

        $verificationCode = rand(10000000, 99999999);
        $expiresAt = now()->addMinutes(15);

        EmailVerification::updateOrCreate(
            ['email' => $user->email],
            ['verification_code' => $verificationCode, 'expires_at' => $expiresAt, 'updated_at' => now()]
        );

        EmailHelper::sendEmail($user->email, EmailVerificationMail::class, [$verificationCode]);
    }

    public function verifyEmail(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'auth', 'verify_email');
        if (! $validatedData['success']) {
            throw new \Exception('Invalid parameters provided.');
        }

        $email = $validatedData['data']['email'];
        $code = $validatedData['data']['verification_code'];

        $verificationEntry = EmailVerification::where('email', $email)
            ->where('verification_code', $code)
            ->where('expires_at', '>', now())
            ->first();

        if (! $verificationEntry) {
            throw new \Exception('Invalid or expired code.');
        }

        $user = User::where('email', $email)->first();
        $user->email_verified_at = now();
        $user->save();

        EmailVerification::where('email', $request->email)->delete();
    }

    public function sendPhoneVerificationCode(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'auth', 'send_phone_verification_code');
        if (! $validatedData['success']) {
            throw new \Exception('Invalid parameters provided.');
        }

        $user = User::where('phone', $request->phone)->first();
        if (! $user) {
            throw new \Exception('User not found.');
        }

        $verificationCode = rand(10000000, 99999999);
        $expiresAt = now()->addMinutes(15);

        PhoneVerification::updateOrCreate(
            ['phone' => $user->phone],
            ['verification_code' => $verificationCode, 'expires_at' => $expiresAt, 'updated_at' => now()]
        );

        PhoneHelper::sendSMS($user->phone, $verificationCode);
    }

    public function verifyPhone(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'auth', 'verify_phone');
        if (! $validatedData['success']) {
            throw new \Exception('Invalid parameters provided.');
        }

        $phone = preg_replace('/[^0-9]/', '', $validatedData['data']['phone']);
        $code = $validatedData['data']['verification_code'];

        $verificationEntry = PhoneVerification::where('phone', $phone)
            ->where('verification_code', $code)
            ->first();

        if (! $verificationEntry) {
            throw new \Exception('Invalid or expired code.');
        }

        $user = User::where('phone', $phone)->first();
        $user->phone_verified = now();
        $user->save();

        PhoneVerification::where('phone', $phone)->delete();
    }
}
