<?php

namespace App\Models\Nordigen;

use Carbon\Carbon;
use Carbon\Traits\Date;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class NordigenAPI implements NordigenAPIRequest
{
    private $accessToken;
    private $accessExpireDate;
    private $refreshToken;
    private $refreshExpireDate;

    public function __construct()
    {
        $this->prepare();
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return mixed
     */
    public function getAccessExpireDate()
    {
        return $this->accessExpireDate;
    }

    /**
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @return mixed
     */
    public function getRefreshExpireDate()
    {
        return $this->refreshExpireDate;
    }

    private function getHeader()
    {
        return [
            "Content-Type" => "application/json"
        ];
    }

    public function CallGETAPI($uri, $params)
    {
        $client = new Client();

        $request = $client->get($uri, [
            "headers" => ["Authorization" => "Bearer " . $this->accessToken],
            "query" => $params
        ]);

        if($request->getStatusCode() == "200"){
            return json_decode($request->getBody()->getContents());
        }else{
            throw new \ErrorException("Une erreur est survenue : " . $request->getBody());
        }
    }

    public function CallPOSTAPI($uri, $body, $needAuthentification=true)
    {
        $client = new Client();
        $params = [
            "headers" => $this->getHeader(),
            "body" => json_encode([
                "secret_id" => $this->getNordigenId(),
                "secret_key" => $this->getNordigenKey()
            ])
        ];

        if ($needAuthentification){
            $params["body"][] = ["Authorization" => "Bearer " . $this->accessToken];
        }

        $request = $client->post($uri, $params);
        if($request->getStatusCode() == "200"){
            return json_decode($request->getBody()->getContents());
        }else{
            throw new \ErrorException("Une erreur est survenue : " . $request->getBody());
        }
    }

    private function prepare()
    {
        if(isset($this->accessExpireDate) && isset($this->refreshExpireDate)){
            if($this->tokenMustBeRefresh()){
                if($this->tokenCanBeRefresh()){
                    $this->refreshToken();
                }else{
                    $this->createNewToken();
                }
            }
        }else{
            $this->createNewToken();
        }
    }

    private function getNordigenId(){
        return env("NORDIGEN_ID");
    }

    private function getNordigenKey(){
        return env("NORDIGEN_KEY");
    }

    private function createNewToken(){
        $url = "https://ob.nordigen.com/api/v2/token/new/";
        $body = [
            "secret_id" => $this->getNordigenId(),
            "secret_key" => $this->getNordigenKey()
        ];
        $response = $this->CallPOSTAPI($url, $body, false);
        $this->accessToken = $response->access;
        $accessExpiresSeconds = $response->access_expires;
        $dateExpirationAccessToken = Carbon::now();
        $this->addSecondToDate($dateExpirationAccessToken, $accessExpiresSeconds);
        $this->accessExpireDate = $dateExpirationAccessToken;

        $this->refreshToken = $response->refresh;
        $dateExpirationRefreshToken = Carbon::now();
        $refreshExpireSeconds = $response->refresh_expires;
        $this->addSecondToDate($dateExpirationRefreshToken, $refreshExpireSeconds);
        $this->refreshExpireDate = $dateExpirationRefreshToken;

//        echo $this->accessToken;
//        echo "\n";
//        echo $this->accessExpireDate;
//        echo "\n";
//        echo $this->refreshToken;
//        echo "\n";
//        echo $this->refreshExpireDate;
//        echo "\n";
//        die;

    }

    private function addSecondToDate($date, $seconds){
        $date->addSecond($seconds);
    }

    private function tokenCanBeRefresh(){
        return $this->accessExpireDate->lessThan($this->refreshExpireDate);
    }

    private function tokenMustBeRefresh(){
        return Carbon::now()->lessThan($this->accessExpireDate);
    }

    private function refreshToken()
    {
        $url = "https://ob.nordigen.com/api/v2/token/refresh/";
        $body = [
            "refresh" => $this->refreshToken
        ];
        $response = $this->CallPOSTAPI($url, $body);

        $this->accessToken = $response["access"];
        $dateExpirationNewAccessToken = Carbon::now();
        $this->addSecondToDate($dateExpirationNewAccessToken, $response["access_expires"]);
        $this->accessExpireDate = $dateExpirationNewAccessToken;
    }

    public function getBanks($country)
    {
        $uri = "https://ob.nordigen.com/api/v2/institutions/";
        $params = ["country" => $country];
        return $this->CallGETAPI($uri, $params);
    }
}
