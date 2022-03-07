<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequisitionRequest;
use App\Models\Nordigen\StaticObjects;
use Illuminate\Http\Request;

class RequisitionController extends Controller
{
    /**
     * Méthode permettant de créer une réquisition en utilisant nordigen
     * @param StoreRequisitionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequisitionRequest $request){
        $user = $request->user();
        if(!$user->getHasBankAuthorizationAttribute()){
            $validated = $request->validated();

            $bank_id = $validated["bank_id"];
            $requisitionRequest = StaticObjects::$nordigenAPI->postRequisition($bank_id);

            $statusCode = $requisitionRequest->getStatusCode();
            $response = json_decode($requisitionRequest->getBody()->getContents());

            if(in_array($statusCode, [200, 201, 202])){
                $user->idRequisition = $response->id;
                $user->save();
                return response()->json([
                    "user" => $user,
                    "requisition_response" => $response
                ]);
            }
            return response()->json($response, $statusCode);
        }else{
            $requisitionRequest = StaticObjects::$nordigenAPI->getRequisitionById($user->idRequisition);

            $statusCode = $requisitionRequest->getStatusCode();
            $response = json_decode($requisitionRequest->getBody()->getContents());

            if(in_array($statusCode, [200, 201, 202])){
                $user->idRequisition = $response->id;
                $user->save();
                return response()->json([
                    "user" => $user,
                    "requisition_response" => $response
                ]);
            }else{
                return response()->json([
                    "message" => "Erreur lors de l'appel de l'API nordigen !",
                    "response" => $response
                ], $statusCode);
            }
        }
    }
}
