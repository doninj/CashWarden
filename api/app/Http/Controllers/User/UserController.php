<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Float_;

class UserController extends Controller
{
    /**
     *
     *
     * @OA\Get(
     *      path="/api/user",
     *      operationId="getUserInfo",
     *      tags={"User"},
     *      summary="Permet d'obtenir des informations sur l'utilisateur connecté",
     *      description="Obtiens des informations sur l'utilisateur",
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
     *         description="Lorsqu'un utilisateur est connecté et qu'il possède déjà un compte",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property (
     *                 property="id", type="integer", example="1"
     *             ),
     *             @OA\Property (
     *                 property="lastname", type="string", example="DUPONT"
     *             ),
     *             @OA\Property (
     *                 property="firstname", type="string", example="Jean"
     *             ),
     *             @OA\Property (
     *                 property="email", type="string", example="dupont.jean@gmail.com"
     *             ),
     *             @OA\Property (
     *                 property="bank_id", type="string", example="AGRICOLE_SUD_RHONE_ALPES_AGRIFRPPXXX"
     *             ),
     *             @OA\Property (
     *                 property="idRequisition", type="string", example="0e981e62-f803-4ff1-a775-eac2e6af6a2c"
     *             ),
     *             @OA\Property (
     *                 property="account_id", type="string", example="e806f95a-6455-46a0-f357-d4d15fe4fd90"
     *             ),
     *             @OA\Property (
     *                 property="hasBankAuthorization", type="boolean", example="true"
     *             ),
     *             @OA\Property (
     *                 property="hasAccountChoices", type="boolean", example="true"
     *             ),
     *             @OA\Property (
     *                  property="account",
     *                  type="object",
     *                      @OA\Property(
     *                          property= "id", type="string", example="e806f95a-6455-46a0-f357-d4d15fe4fd90"
     *                      ),
     *                      @OA\Property(
     *                          property= "name", type="string", example="1"
     *                      ),
     *                      @OA\Property(
     *                          property= "created_at", type="date", example="2022-03-11T21:43:08.000000Z"
     *                      ),
     *                      @OA\Property(
     *                          property= "updated_at", type="date", example="2022-03-11T21:43:08.000000Z"
     *                      ),
     *                      @OA\Property(
     *                          property= "totalSpendingOfActualMonth", type="number", example="914.67"
     *                      ),
     *                      @OA\Property(
     *                          property= "totalIncomeOfActualMonth", type="number", example="1797.56"
     *                      ),
     *                      @OA\Property(
     *                          property="months", type="array",
     *                          @OA\Items(type="json", example="[""mars"", ""février"", ""janvier""]")
     *                      ),
     *                      @OA\Property(
     *                          property="years", type="array",
     *                          @OA\Items(type="json", example="[""2022"", ""2021""]")
     *                      ),
     *                      @OA\Property(
     *                          property="get_three_transactions", type="array",
     *                          @OA\Items(
     *                                  @OA\Property(property="id", type="integer", example="153"),
     *                                  @OA\Property(property="label", type="string", example="VIREMENT EN VOTRE FAVEUR CONDUENT BUSINESS SOLUTIONS (FRA"),
     *                                  @OA\Property(property="montant", type="number", example="1250"),
     *                                  @OA\Property(property="monnaie", type="string", example="EUR"),
     *                                  @OA\Property(property="dateTransaction", type="date", example="2022-03-28"),
     *                                  @OA\Property(property="status", type="string", example="booked"),
     *                                  @OA\Property(property="account_id", type="string", example="e806f95a-6455-46a0-f357-d4d15fe4fd90"),
     *                                  @OA\Property(property="category_id", type="integer", example="1"),
     *                                  @OA\Property(property="created_at", type="date", example="2022-03-14T23:29:03.000000Z"),
     *                                  @OA\Property(property="updated_at", type="date", example="2022-03-14T23:29:03.000000Z")
     *                          )
     *                      ),
     *                      @OA\Property(
     *                          property="transactions_for_actual_month", type="array",
     *                          @OA\Items(
     *                                  @OA\Property(property="id", type="integer", example="153"),
     *                                  @OA\Property(property="label", type="string", example="VIREMENT EN VOTRE FAVEUR CONDUENT BUSINESS SOLUTIONS (FRA"),
     *                                  @OA\Property(property="montant", type="number", example="1250"),
     *                                  @OA\Property(property="monnaie", type="string", example="EUR"),
     *                                  @OA\Property(property="dateTransaction", type="date", example="2022-03-28"),
     *                                  @OA\Property(property="status", type="string", example="booked"),
     *                                  @OA\Property(property="account_id", type="string", example="e806f95a-6455-46a0-f357-d4d15fe4fd90"),
     *                                  @OA\Property(property="category_id", type="integer", example="1"),
     *                                  @OA\Property(property="created_at", type="date", example="2022-03-14T23:29:03.000000Z"),
     *                                  @OA\Property(property="updated_at", type="date", example="2022-03-14T23:29:03.000000Z")
     *                          )
     *                      ),
     *                      @OA\Property(
     *                          property="latest_balances", type="object",
     *                          @OA\Property(
     *                              property="id", type="number", example="4"
     *                          ),
     *                          @OA\Property(
     *                              property="amount", type="number", example="5804.18"
     *                          ),
     *                          @OA\Property(
     *                              property="dateAmount", type="number", example="2022-03-14"
     *                          ),
     *                          @OA\Property(
     *                              property="account_id", type="number", example="e846b95d-6490-44b1-a333-d3d15fe4af25"
     *                          ),
     *                      )
     *                  )
     *             )
     *         )
     *     )
     * )
     *
     *
     * Méthode permettant de lister les informations d'un utilisateur
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasAccountChoices) {
            $userData = $user->load([
                'account.GetThreeTransactions',
                'account.transactionsForActualMonth',
                'account.transactions',
                'account.LatestBalances'
            ]);
            $collect = collect($userData->account->transactionsForActualMonth);
            $userData->account['totalSpendingOfActualMonth'] =number_format(abs($collect->map(function ($transaction) {
                if ($transaction->montant < 0)
                    return $transaction->montant;
            })->sum()),2);
            $userData->account['totalIncomeOfActualMonth'] =number_format(abs($collect->map(function ($transaction) {
                if ($transaction->montant > 0)
                    return $transaction->montant;
            })->sum()),2);
            $this->GetMonthAndYear($userData);
        }
        return response()->json($user);
    }

    /**
     * Méthode Permet de lister les mois et années de chaque transactions d'un utilisateur
     *
     * @param $userData
     * @return void
     */
    public function GetMonthAndYear($userData) {
        $monthArray = [];
        $yearArray = [];
        $date =  collect($userData->account->transactions);
        foreach ($date as $transaction) {
            $myDate = $transaction->dateTransaction;
            $date = Carbon::createFromFormat('Y-m-d', $myDate);
            $monthName = $date->translatedFormat('F');
            $yearName = $date->translatedFormat('Y');
            if(!in_array($yearName, $yearArray)) {
                array_push($yearArray, $yearName);
            }
            if(!in_array($monthName, $monthArray)) {
                array_push($monthArray, $monthName);
            }
        }
        $userData->account['months'] = $monthArray;
        $userData->account['years'] = $yearArray;
        unset($userData->account->transactions);
    }

    /**
     * Permet d'afficher toutes les informations nécessaire à un utilisateur connecté
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showUserWhenConnected(Request $request){
        $user = $request->user();
        $userData = $user->load([
            'account.GetThreeTransactions',
            'account.transactionsForActualMonth',
            'account.transactions'
        ]);
        $collect = collect($userData->account->transactionsForActualMonth);
        $userData->account['totalSpendingOfActualMonth'] =number_format(abs($collect->map(function ($transaction) {
            if ($transaction->montant < 0)
                return $transaction->montant;
        })->sum()),2);
        $this->GetMonth($userData);
        return response()->json($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        try {
            // Set nom prénom
            $user->firstName = empty($validated['firstName']) ? $user->firstName : $validated["firstName"];
            $user->lastName = empty($validated['lastName']) ? $user->firstName : $validated["lastName"];

            // Set password
            if (!empty($validated['password'])) {
                $user->setPassword($validated["password"]);
            }

            if (!empty($validated["bank_id"])) {
                $user->setBankById($validated["bank_id"]);
            }

            if(!empty($validated["idRequisition"])) {
                if (!$user->getHasBankAuthorizationAttribute()) {
                    // Set idRequisition
                    $user->idRequisition = $validated["idRequisition"];
                } else {
                    return response(["message" => "Une banque a déjà été associé à cet utilisateur !"], 400);
                }
            }

            // Create and set account
            if(!empty($validated["account"])) {
                if ($user->getHasBankAuthorizationAttribute()) {
                    if (!$user->getHasAccountChoicesAttribute()) {
                        $account = $validated["account"];
                        if (AccountController::accountExistInNordigenAPI($account["id"], $user->idRequisition)) {
                            $user->addAccount($account);
                        } else {
                            return response()->json([
                                "message" => "Le compte saisie n'appartient pas à l'id de réquisition associé à l'utilisateur !", 400
                            ]);
                        }
                    } else {
                        return response(["message" => "Un compte est déjà associé à cet utilisateur !"], 400);
                    }
                } else {
                    return response(["message" => "Merci de renseigner en priorité un id de réquisition !"], 400);
                }
            }

            $user->save();

            return response($user);
        } catch (\Exception $e) {
            return response(
                [
                    "message" => "Oups une erreur est survenue !",
                    "exception" => [
                        "message" => $e->getMessage(),
                        "file" => $e->getFile(),
                        "trace" => $e->getTrace()
                    ]
                ]
            );
        }
    }
}
