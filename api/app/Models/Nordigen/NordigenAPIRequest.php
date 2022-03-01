<?php

namespace App\Models\Nordigen;

interface NordigenAPIRequest
{
    public function CallGETAPI($uri);
    public function CallPOSTAPI($uri, $body);

    public function getBanks();
}
