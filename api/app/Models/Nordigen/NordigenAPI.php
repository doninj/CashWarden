<?php

namespace App\Models\Nordigen;

use Carbon\Carbon;
use Carbon\Traits\Date;
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
        $this->prepare();
        return [
            "accept" => "application/json",
            "Authorization" => "Bearer $this->accessToken"
        ];
    }

    public function CallGETAPI($uri)
    {
        return HTTP::get($uri);
    }

    public function CallPOSTAPI($uri, $body)
    {
        return HTTP::withHeaders($this->getHeader())->post($uri, $body);
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
        return getenv("NORDIGEN_ID");
    }

    private function getNordigenKey(){
        return getenv("NORDIGEN_KEY");
    }

    private function createNewToken(){
        $url = "https://ob.nordigen.com/api/v2/token/new/";
        $body = [
            "secret_id" => $this->getNordigenId(),
            "secret_key" => $this->getNordigenKey()
        ];
        $response = $this->CallPOSTAPI($url, $body);
        if($response->ok()){

            $this->accessToken = $response["access"];
            $accessExpiresSeconds = $response["access_expires"];

            $dateExpirationAccessToken = Carbon::now();
            $this->addSecondToDate($dateExpirationAccessToken, $accessExpiresSeconds);
            $this->accessExpireDate = $dateExpirationAccessToken;

            $this->refreshToken = $response["refresh"];

            $dateExpirationRefreshToken = Carbon::now();
            $refreshExpireSeconds = $response["refresh_expires"];
            $this->addSecondToDate($dateExpirationRefreshToken, $refreshExpireSeconds);
            $this->refreshExpireDate = $dateExpirationRefreshToken;

        }else{
            throw new \ErrorException("La requête à échouée");
        }
    }

    private function addSecondToDate($date, $seconds){
        $date->addSecond($seconds);
    }

    private function tokenCanBeRefresh(){
        return $this->accessExpireDate->lessThan($this->refreshExpireDate);
    }

    private function tokenMustBeRefresh(){
        return Carbon::now()->greaterThan($this->accessExpireDate);
    }

    private function refreshToken()
    {
        $url = "https://ob.nordigen.com/api/v2/token/refresh/";
        $body = [
            "refresh" => $this->refreshToken
        ];
        $response = $this->CallPOSTAPI($url, $body);
        if($response->ok()){

            $this->accessToken = $response["access"];
            $dateExpirationNewAccessToken = Carbon::now();
            $this->addSecondToDate($dateExpirationNewAccessToken, $response["access_expires"]);
            $this->accessExpireDate = $dateExpirationNewAccessToken;

        }else{
            throw new \ErrorException("La requête à échouée !");
        }
    }

    public function getBanks()
    {
        $this->CallGETAPI("https://ob.nordigen.com/api/v2/institutions/");
    }
}
