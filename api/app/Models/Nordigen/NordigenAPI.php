<?php

namespace App\Models\Nordigen;

use Carbon\Carbon;
use Carbon\Traits\Date;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

/**
 * Class nordigenAPI permettant de gérer les appels d'API nordigen
 */
class NordigenAPI implements NordigenAPIRequest
{
    const requisitionsUri = "https://ob.nordigen.com/api/v2/requisitions/";
    const redirectConfirmedLink = "http://localhost:3000/bank-register/";
    const accountsUri = "https://ob.nordigen.com/api/v2/accounts/";
    private $accessToken;
    private $accessExpireDate;
    private $refreshToken;
    private $refreshExpireDate;

    /**
     * Initialisation de l'objet
     * @throws \ErrorException
     */
    public function __construct()
    {
        $this->prepare();
    }

    /**
     * Get accessToken
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Get accessExpireDate
     * @return mixed
     */
    public function getAccessExpireDate()
    {
        return $this->accessExpireDate;
    }

    /**
     * Get refreshToken
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Get refreshExpireDate
     * @return mixed
     */
    public function getRefreshExpireDate()
    {
        return $this->refreshExpireDate;
    }

    /**
     * Permet de récupérer un header de requête
     *
     * @return string[]
     */
    private function getHeader()
    {
        return [
            "Content-Type" => "application/json"
        ];
    }

    /**
     * Permet d'appeler une requête GET
     *
     * @param $uri
     * @param $params
     * @return \GuzzleHttp\Psr7\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function CallGETAPI($uri, $params)
    {
        $client = new Client();

        $request = $client->get($uri, [
            "headers" => ["Authorization" => "Bearer " . $this->accessToken],
            "query" => $params
        ]);

        return $request;
    }

    /**
     * Permet d'appeler une requête POST
     *
     * @param $uri
     * @param $body
     * @param $needAuthentification
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function CallPOSTAPI($uri, $body, $needAuthentification=true)
    {
        $client = new Client();
        $params = [
            "headers" => $this->getHeader(),
            "body" => json_encode($body)
        ];

        if ($needAuthentification){
            $params["headers"]["Authorization"] = "Bearer " . $this->accessToken;
        }

        return $client->post($uri, $params);
    }

    /**
     * Permet de préparer la requête et réinitialiser le token si celui-ci est obsolète
     *
     * @return void
     * @throws \ErrorException
     */
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

    /**
     * Méthode permettant de récupérer l'identifiant nordigen en variable d'environnement
     *
     * @return mixed
     */
    private function getNordigenId(){
        return env("NORDIGEN_ID");
    }

    /**
     * Méthode permettant de récupérer la clé nordigen en variable d'environnement
     * @return mixed
     */
    private function getNordigenKey(){
        return env("NORDIGEN_KEY");
    }

    /**
     * Permet de créer un nouveau token en appellant l'api nordigen
     *
     * @return void
     * @throws \ErrorException
     */
    private function createNewToken(){
        $url = "https://ob.nordigen.com/api/v2/token/new/";
        $body = [
            "secret_id" => $this->getNordigenId(),
            "secret_key" => $this->getNordigenKey()
        ];

        $request = $this->CallPOSTAPI($url, $body, false);

        if($request->getStatusCode() == "200"){
            $response = json_decode($request->getBody()->getContents());
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

        }else{
            throw new \ErrorException("Une erreur est survenue : " . $request->getBody());
        }

    }

    /**
     * Méthode permettant d'ajouter des secondes à une date
     *
     * @param $date
     * @param $seconds
     * @return void
     */
    private function addSecondToDate($date, $seconds){
        $date->addSecond($seconds);
    }

    /**
     * Méthode permettant de vérifier si le token peut être raffraichi
     * @return mixed
     */
    private function tokenCanBeRefresh(){
        return $this->accessExpireDate->lessThan($this->refreshExpireDate);
    }

    /**
     * Méthode permettant de vérifier si le token doit être raffraichi
     * @return bool
     */
    private function tokenMustBeRefresh(){
        return Carbon::now()->lessThan($this->accessExpireDate);
    }

    /**
     * Méthode permettant de raffraichir le token
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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

    /**
     * Méthode permettant de récupérer une liste de banques nordigen avec les initiales d'un pays [FR = France]
     *
     * @param $country
     * @return mixed
     * @throws \ErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBanks($country)
    {
        $uri = "https://ob.nordigen.com/api/v2/institutions/";
        $params = ["country" => $country];

        $request = $this->CallGETAPI($uri, $params);

        if($request->getStatusCode() == "200"){
            return json_decode($request->getBody()->getContents());
        }else{
            throw new \ErrorException("Une erreur est survenue : " . $request->getBody());
        }
    }

    /**
     * Méthode permettant de créer une réquisition sur nordigen
     *
     * @param $bank_id
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function postRequisition($bank_id){
        return $this->CallPOSTAPI(self::requisitionsUri, [
            "redirect" => self::redirectConfirmedLink,
            "institution_id" => $bank_id
        ]);
    }

    /**
     * Méthode permettant de récupérer une requisition nordigen par son id
     *
     * @param $requisition_id
     * @return \GuzzleHttp\Psr7\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRequisitionById($requisition_id){
        return $this->CallGETAPI(self::requisitionsUri."$requisition_id", []);
    }

    /**
     * Méthode permettant de récupérer les transactions d'un compte
     *
     * @param $account_id
     * @param $dateFrom
     * @param $dateTo
     * @return \GuzzleHttp\Psr7\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTransactions($account_id, $dateFrom=null, $dateTo=null){
        $params = [];

        if($dateFrom != null){
            $params["date_from"] = $dateFrom->format("Y-m-d");
        }

        if($dateTo != null){
            $params["date_to"] = $dateTo->format("Y-m-d");
        }

        return $this->CallGETAPI(self::accountsUri."$account_id/transactions", $params);
    }

    /**
     * Méthode permettant de récupérer les soldes d'un compte
     *
     * @param $account_id
     * @return \GuzzleHttp\Psr7\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCurrentAmmountOfAccount($account_id){

        return $this->CallGETAPI(self::accountsUri."$account_id/balances/", []);
    }
}
