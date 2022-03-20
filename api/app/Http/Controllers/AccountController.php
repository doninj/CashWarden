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

    /**
     *
     *
     * @OA\Get(
     *      path="/api/nordigen/accounts",
     *      operationId="getNordigenAccount",
     *      tags={"NordigenAccounts"},
     *      summary="Permet d'obtenir la liste des comptes associé à l'utilisateur sur 'nordigen'",
     *      description="Obtiens la liste des comptes associé à l'utilisateur sur 'nordigen'",
     *      security={
     *          {"bearer": {}}
     *      },
     *
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Unauthenticated."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="accounts", type="json", example="[""aa9456eb-5fa98gfd5a-51abb385f9""]"
     *             )
     *         )
     *     )
     * )
     *
     *
     * Méthode permettant de récupérer les comptes associés grâce à nordigen
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
}
