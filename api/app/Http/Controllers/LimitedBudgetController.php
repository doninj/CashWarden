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
     * Display the specified resource.
     *
     * @param  \App\Models\LimitedBudget  $limitedBudget
     * @return \Illuminate\Http\Response
     */
    public function show(LimitedBudget $limitedBudget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LimitedBudget  $limitedBudget
     * @return \Illuminate\Http\Response
     */
    public function edit(LimitedBudget $limitedBudget)
    {
        //
    }

    /**
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LimitedBudget  $limitedBudget
     * @return \Illuminate\Http\Response
     */
    public function destroy(LimitedBudget $limitedBudget)
    {
        //
    }

    public function budgetComparison(Request $request){
        $user = $request->user();

        if($request->exists("limit")){
            $limitNumberResult = $request->get("limit");
        }else{
            $limitNumberResult = 3;
        }

//        $limitedBudgets = $user->getLatestLimitedBudget($limitNumberResult);
        $balances = $user->getLatestBalances($limitNumberResult);

        $budgetComparisonResponse = [];
        foreach ($balances as $balance){
            $date = $balance->dateAmount;
            $budgetComparisonResponse[$date] = [
                "prevision" => $user->getLatestLimitedBudgetAt($date)->amount,
                "real" => doubleval($balance->amount)
            ];
        }

        return response()->json($budgetComparisonResponse);
    }
}
