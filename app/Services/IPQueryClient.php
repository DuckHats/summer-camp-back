<?php

namespace App\Services;

use App\Helpers\ErrorLogger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class IPQueryClient
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => config('services.ipInfo.url')]);
    }

    public function getPublicIP(): ?string
    {
        try {
            $response = $this->client->get('');

            return trim($response->getBody()->getContents());
        } catch (RequestException $e) {
            ErrorLogger::log(
                'Error while fetching IP address',
                $e->getMessage(),
                $e->getTraceAsString()
            );
        }
    }

    public function getIPDetails(string $ipAddress): ?array
    {
        try {
            $response = $this->client->get("/{$ipAddress}");

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            ErrorLogger::log(
                'Error while fetching IP details',
                $e->getMessage(),
                $e->getTraceAsString()
            );
        }
    }
}
