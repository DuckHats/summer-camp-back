<?php

namespace App\Http\Controllers;

use App\Mail\NewIPAccessMail;
use App\Repositories\UserLastLoginRepository;
use App\Services\IPQueryClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserLastLoginController extends Controller
{
    private $ipQueryClient;

    private $userLastLoginRepository;

    public function __construct(IPQueryClient $ipQueryClient, UserLastLoginRepository $userLastLoginRepository)
    {
        $this->ipQueryClient = $ipQueryClient;
        $this->userLastLoginRepository = $userLastLoginRepository;
    }

    public function trackLogin(Request $request)
    {
        $user = Auth::user();
        $ipAddress = $this->ipQueryClient->getPublicIP();
        if (! $ipAddress) {
            Log::error('Failed to get public IP address'.$user->id);
        }

        $ipDetails = $this->ipQueryClient->getIPDetails($ipAddress);

        $loginData = [
            'user_id' => $user->id,
            'ip_address' => $ipAddress,
            'user_agent' => $request->userAgent(),
            'country' => $ipDetails['location']['country'] ?? null,
            'city' => $ipDetails['location']['city'] ?? null,
            'zip_code' => $ipDetails['location']['zipcode'] ?? null,
            'last_login_at' => now(),
        ];

        $isNewIP = ! $this->userLastLoginRepository->existsForIP($user->id, $ipAddress);

        $this->userLastLoginRepository->create($loginData);

        $ipDetails['date'] = Carbon::parse(now())->format('d-m-Y H:i');

        if ($isNewIP) {
            Mail::to($user->email)->send(new NewIPAccessMail($ipDetails));
        }
    }
}
