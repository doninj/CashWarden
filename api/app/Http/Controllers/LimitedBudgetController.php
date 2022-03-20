<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLimitedBudgetRequest;
use App\Http\Requests\UpdateLimitedBudgetRequest;
use App\Models\LimitedBudget;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LimitedBudgetController extends Controller
{
    /**
     *
     *
     * @OA\Get(
     *      path="/api/limitedBudgets",
     *      operationId="getLimitedBudgets",
     *      tags={"LimitedBudgets"},
     *      summary="Permet d'obtenir la liste des budgets limités",
     *      description="Retourne la liste des budgets limités",
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
     *             @OA\Property (
     *                 property="limitedBudgets",
     *                 type="array",
     *                 @OA\Items(
     *                         @OA\Property(
     *                             property="id", type="integer", example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="amount", type="string", example="5800"
     *                         ),
     *                         @OA\Property(
     *                             property="previsionDate", type="date", example="2022-03-01"
     *                         ),
     *                         @OA\Property(
     *                             property="user_id", type="integer", example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="created_at", type="date", example="2022-03-11T21:44:23.000000Z"
     *                         ),
     *                         @OA\Property(
     *                             property="updated_at", type="date", example="2022-03-14T23:30:22.000000Z"
     *                         ),
     *                         @OA\Property(
     *                             property="isLatestLimitedBudgetOfUser", type="boolean", example="true"
     *                         )
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return response()->json(["limitedBudgets" => $user->limitedBudgets()->get()]);
    }

    /**
     *
     *
     * @OA\Post(
     *      path="/api/limitedBudgets",
     *      operationId="postLimitedBudgets",
     *      tags={"LimitedBudgets"},
     *      summary="Permet de créer un budget limité",
     *      description="Crée un budget limité",
     *      security={
     *          {"bearer": {}}
     *      },
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/StoreLimitedBudgetRequest"
     *             )
     *         )
     *     ),
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
     *             @OA\Property (
     *                 property="limitedBudgets",
     *                 type="array",
     *                 @OA\Items(
     *                         @OA\Property(
     *                             property="id", type="integer", example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="amount", type="string", example="2000.0"
     *                         ),
     *                         @OA\Property(
     *                             property="previsionDate", type="date", example="2022-03-01"
     *                         ),
     *                         @OA\Property(
     *                             property="user_id", type="integer", example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="created_at", type="date", example="2022-03-11T21:44:23.000000Z"
     *                         ),
     *                         @OA\Property(
     *                             property="updated_at", type="date", example="2022-03-14T23:30:22.000000Z"
     *                         ),
     *                         @OA\Property(
     *                             property="isLatestLimitedBudgetOfUser", type="boolean", example="true"
     *                         )
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     *
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLimitedBudgetRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLimitedBudgetRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        $previsionDate = Carbon::parse($validated["previsionDate"]);

        if($user->haveAnyLimitedBudgetAt($previsionDate)){
            $actualMonth = Carbon::parse(Carbon::now()->format('Y-m'))->subMonth();
            if($previsionDate->greaterThan($actualMonth)) {
                $limitedBudget = new LimitedBudget();
                $limitedBudget->amount = $validated["amount"];
                $limitedBudget->previsionDate = $previsionDate;
                $limitedBudget->user_id = $user->id;
                $limitedBudget->save();
            }else{
                return response()->json(["message" => "La date prévisionnel ne doit pas être antérieure à ".$actualMonth->format("Y-m")], 400);
            }

            return response()->json($limitedBudget);
        }else{
            return response()->json(["message" => "La date sélectionné possède déjà une limite budgétaire ! Veuillez choisir une date postérieur."]);
        }
    }

    /**
     *
     *
     * @OA\Put(
     *      path="/api/limitedBudgets/{id}",
     *      operationId="putLimitedBudgets",
     *      tags={"LimitedBudgets"},
     *      summary="Permet de modifier un budget limité",
     *      description="Modifie un budget limité",
     *      security={
     *          {"bearer": {}}
     *      },
     *
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="limited budget id",
     *          @OA\Schema (
     *              type="integer",
     *              format="int64"
     *          ),
     *          required=true,
     *          example=1
     *      ),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/UpdateLimitedBudgetRequest"
     *             )
     *         )
     *     ),
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
     *                         @OA\Property(
     *                             property="id", type="integer", example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="amount", type="string", example="5800"
     *                         ),
     *                         @OA\Property(
     *                             property="previsionDate", type="date", example="2022-03-01"
     *                         ),
     *                         @OA\Property(
     *                             property="user_id", type="integer", example="1"
     *                         ),
     *                         @OA\Property(
     *                             property="created_at", type="date", example="2022-03-11T21:44:23.000000Z"
     *                         ),
     *                         @OA\Property(
     *                             property="updated_at", type="date", example="2022-03-14T23:30:22.000000Z"
     *                         ),
     *                         @OA\Property(
     *                             property="isLatestLimitedBudgetOfUser", type="boolean", example="true"
     *                         )
     *             )
     *         )
     *     )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLimitedBudgetRequest  $request
     * @param  \App\Models\LimitedBudget  $limitedBudget
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateLimitedBudgetRequest $request, LimitedBudget $limitedBudget)
    {
        $validated = $request->validated();
        $limitedBudget->amount = $validated["amount"];
        $limitedBudget->save();

        return response()->json($limitedBudget);
    }

    /**
     * Méthode permettant de récupérer une comparaison des budgets (prévisionnel/réel) par date
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function budgetComparison(Request $request){
        $user = $request->user();

        if($request->exists("limit")){
            $limitNumberResult = $request->get("limit");
        }else{
            $limitNumberResult = 3;
        }

        $today = Carbon::now();
        $previous = $today->subMonths($limitNumberResult-1);
        $date = $previous;

        $budgetComparisonResponse = [];
        for($i=0; $i<$limitNumberResult; $i++){
            $previsionValue = $user->getLatestLimitedBudgetAt($date);
            $balanceValue = $user->getLatestRealBalanceAt($date);
            $budgetComparisonResponse[$date->format("m-Y")] = [
                "prevision" => is_null($previsionValue) ? 0 : doubleval($previsionValue->amount),
                "real" => is_null($balanceValue) ? 0 : doubleval($balanceValue->amount)
            ];
            $date->addMonth();
        }

        return response()->json($budgetComparisonResponse);
    }
}
