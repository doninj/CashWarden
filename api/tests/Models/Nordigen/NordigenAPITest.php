<?php

namespace Tests\Models\Nordigen;

use App\Models\Nordigen\NordigenAPI;
use App\Models\Nordigen\StaticObjects;
use PHPUnit\Framework\TestResult;
use Tests\TestCase;

class NordigenAPITest extends TestCase
{
    private $nordigenAPI;

    protected function setUp(): void
    {
        parent::setUp();
        $this->nordigenAPI = StaticObjects::$nordigenAPI;
    }

    public function test__construct()
    {
        self::assertTrue(is_object($this->nordigenAPI));
        self::assertEquals("object", gettype($this->nordigenAPI));
        self::assertEquals("App\Models\Nordigen\NordigenAPI", get_class($this->nordigenAPI));
    }

    public function testGetAccessExpireDate()
    {
        $accessExpireDate = $this->nordigenAPI->getAccessExpireDate();
        self::assertNotNull($accessExpireDate);
        self::assertEquals("object", gettype($accessExpireDate));
        self::assertEquals("Carbon\Carbon", get_class($accessExpireDate));
    }

    public function testGetRefreshExpireDate()
    {
        $refreshExpireDate = $this->nordigenAPI->getRefreshExpireDate();
        self::assertNotNull($refreshExpireDate);
        self::assertEquals("object", gettype($refreshExpireDate));
        self::assertEquals("Carbon\Carbon", get_class($refreshExpireDate));
    }

    public function testGetRefreshToken()
    {
        $refreshToken = $this->nordigenAPI->getRefreshToken();
        self::assertNotNull($refreshToken);
        self::assertEquals("string", gettype($refreshToken));
    }

    public function testGetAccessToken()
    {
        $accessToken = $this->nordigenAPI->getAccessToken();
        self::assertNotNull($accessToken);
        self::assertEquals("string", gettype($accessToken));
    }

    public function testGetBanks()
    {
        $banks = $this->nordigenAPI->getBanks("FR");
        try {
            self::assertTrue(is_array($banks));
        }catch (\Exception $e){
            self::assertEquals("ErrorException", get_class($banks));
        }
    }

    public function testCallGETAPI()
    {
        $uri = "https://ob.nordigen.com/api/v2/institutions/";
        $params = ["country" => "FR"];

        $request = $this->nordigenAPI->CallGETAPI($uri, $params);
        self::assertNotNull($request);
        self::assertIsObject($request);
        self::assertEquals("GuzzleHttp\Psr7\Response", get_class($request));
    }

    public function testCallPOSTAPI()
    {
        $uri = "https://ob.nordigen.com/api/v2/agreements/enduser/";
        $params = ["institution_id" => "AGRICOLE_SUD_RHONE_ALPES_AGRIFRPPXXX"];

        $request = $this->nordigenAPI->CallPOSTAPI($uri, $params);
        self::assertNotNull($request);
        self::assertIsObject($request);
        self::assertEquals("GuzzleHttp\Psr7\Response", get_class($request));
    }
}
