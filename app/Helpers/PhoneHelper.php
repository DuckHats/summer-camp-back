<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class PhoneHelper
{
    public static function sendSMS($phoneNumber, $verificationCode)
    {

        Log::info("Sending SMS to {$phoneNumber}: {$verificationCode}");

        // Servicio real como Twilio:
        // $client = new \Twilio\Rest\Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        // $client->messages->create($phoneNumber, [
        //     'from' => env('TWILIO_PHONE_NUMBER'),
        //     'body' => "Your verification code is: {$verificationCode}",
        // ]);

        // De momento, solo logeamos el mensaje para verlo en Telescope
    }
}
