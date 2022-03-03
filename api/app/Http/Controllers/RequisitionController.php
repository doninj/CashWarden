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
        $validated = $request->validated();

        $bank_id = $validated["bank_id"];
        $requisitionRequest = StaticObjects::$nordigenAPI->postRequisition();

        return response()->json($requisitionRequest->getBody()->getContents(), $request->getStatusCode());
    }
}
