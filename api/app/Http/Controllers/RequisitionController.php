<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequisitionRequest;
use App\Models\Nordigen\StaticObjects;
use Illuminate\Http\Request;

class RequisitionController extends Controller
{
    /**
     *
     * @OA\Post(
     *      path="/api/requisition",
     *      operationId="postRequisition",
     *      tags={"Requisition"},
     *      summary="Permet de se connecter",
     *      description="Déconnecte l'utilisateur",
     *      security={
     *          {"bearer": {}}
     *      },
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/StoreRequisitionRequest",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Content",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="email",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              type="string",
     *                              example="Merci de renseigner un identifiant de banque !"
     *                          ),
     *                      ),
     *                  ),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="An example resource",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                         @OA\Property (
     *                             property="id", type="integer", example="1"
     *                         ),
     *                         @OA\Property (
     *                             property="lastname", type="string", example="DUPONT"
     *                         ),
     *                         @OA\Property (
     *                             property="firstname", type="string", example="Jean"
     *                         ),
     *                         @OA\Property (
     *                             property="email", type="string", example="dupont.jean@gmail.com"
     *                         ),
     *                         @OA\Property (
     *                             property="bank_id", type="string", example="null"
     *                         ),
     *                         @OA\Property (
     *                             property="IdRequisition", type="string", example="null"
     *                         ),
     *                         @OA\Property (
     *                             property="account_id", type="string", example="null"
     *                         ),
     *                         @OA\Property (
     *                             property="hasBankAuthorization", type="boolean", example="false"
     *                         ),
     *                         @OA\Property (
     *                             property="hasAccountChoices", type="boolean", example="false"
     *                         )
     *             ),
     *             @OA\Property(
     *                 property="requisition_response",
     *                 type="object",
     *                         @OA\Property (
     *                             property="id", type="integer", example="0e981e62-f803-4ff1-a775-eac2e6af6a2c"
     *                         ),
     *                         @OA\Property (
     *                             property="created", type="string", example="2022-03-17T23:07:51.570255Z"
     *                         ),
     *                         @OA\Property (
     *                             property="redirect", type="string", example="http://localhost:3000/bank-register/"
     *                         ),
     *                         @OA\Property (
     *                             property="status", type="string", example="CR"
     *                         ),
     *                         @OA\Property (
     *                             property="institution_id", type="string", example="AGRICOLE_SUD_RHONE_ALPES_AGRIFRPPXXX"
     *                         ),
     *                         @OA\Property (
     *                             property="agreement", type="boolean", example=""
     *                         ),
     *                         @OA\Property (
     *                             property="reference", type="boolean", example="9f6233b3-e1dc-41b8-96f3-c4e423a74266"
     *                         ),
     *                         @OA\Property (
     *                             property="accounts", type="json", example="[]"
     *                         ),
     *                         @OA\Property (
     *                             property="link", type="boolean", example="https://ob.nordigen.com/psd2/start/9f6233b3-e1dc-41b8-96f3-c4e423a74266/AGRICOLE_SUD_RHONE_ALPES_AGRIFRPPXXX"
     *                         ),
     *                         @OA\Property (
     *                             property="ssn", type="boolean", example="null"
     *                         ),
     *                         @OA\Property (
     *                             property="account_selection", type="boolean", example="false"
     *                         ),
     *                         @OA\Property (
     *                             property="redirect_immediate", type="boolean", example="false"
     *                         )
     *                 )
     *             )
     *         )
     *     )
     *
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
