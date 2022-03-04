<?php

namespace App\Models\Nordigen;

interface NordigenAPIRequest
{
    public function CallGETAPI($uri, $params);
    public function CallPOSTAPI($uri, $body);

    public function getBanks($country);
}
