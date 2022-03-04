<?php

namespace Tests\Models\Nordigen;

use App\Models\Nordigen\NordigenAPI;
use PHPUnit\Framework\TestCase;

class NordigenAPITest extends TestCase
{

    public function test__construct()
    {
        $nordigenAPI = new NordigenAPI();
        self::assertTrue(is_object($nordigenAPI));
        self::assertEquals(get_class($nordigenAPI), "App\Models\Nordigen\NordigenAPI");
    }

    public function testGetAccessExpireDate()
    {

    }

    public function testGetRefreshExpireDate()
    {

    }

    public function testSetRefreshExpireDate()
    {

    }

    public function testGetRefreshToken()
    {

    }

    public function testSetAccessToken()
    {

    }

    public function testGetBanks()
    {

    }

    public function testCallGETAPI()
    {

    }

    public function testSetAccessExpireDate()
    {

    }

    public function testGetAccessToken()
    {

    }

    public function testSetRefreshToken()
    {

    }

    public function testCallPOSTAPI()
    {

    }
}
