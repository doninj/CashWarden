<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use App\Models\Nordigen\StaticObjects;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    /**
     * Méthode permettant de vérifier que le compte de l'utilisateur existe dans la requisition créé sur nordigen
     * @param $accountId
     * @param $requisitionId
     * @return bool
     */
    public static function accountExistInNordigenAPI($accountId, $requisitionId)
    {
        $requisitionRequest = StaticObjects::$nordigenAPI->getRequisitionById($requisitionId);

        $statusCode = $requisitionRequest->getStatusCode();
        $response = json_decode($requisitionRequest->getBody()->getContents());
        if(in_array($statusCode, [200, 201, 202])){
            if(in_array($accountId, $response->accounts)){
                return true;
            }
        }
        return false;
    }

    public function nordigenAccount(Request $request)
    {
        $user = $request->user();
        if($user->getHasBankAuthorizationAttribute()){
            $requisitionRequest = StaticObjects::$nordigenAPI->getRequisitionById($user->idRequisition);

            $statusCode = $requisitionRequest->getStatusCode();
            $response = json_decode($requisitionRequest->getBody()->getContents());
            if(in_array($statusCode, [200, 201, 202])){
                return response()->json($response->accounts);
            }else{
                return response()->json(
                    [
                        "message" => "Une erreur est survenue lors de l'appel à l'API nordigen !",
                        "nordigenAPIMessage" => $response
                    ], $statusCode
                );
            }
        }else{
            return response()->json([
                "message" => "L'utilisateur ne possède pas d'identifiant de requisition de validé !\nAssurez-vous d'avoir lié votre compte à l'application !"
            ], 400);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAccountRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAccountRequest  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        //
    }
}
