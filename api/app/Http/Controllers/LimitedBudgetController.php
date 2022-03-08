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

        $previsionDate = $validated["previsionDate"];

        if($user->haveAnyLimitedBudgetAt(Carbon::parse($previsionDate))){
            $limitedBudget = new LimitedBudget();
            $limitedBudget->amount = $validated["amount"];
            $limitedBudget->previsionDate = $previsionDate;
            $limitedBudget->user_id = $user->id;
            $limitedBudget->save();

            return response()->json($limitedBudget);
        }else{
            return response()->json(["message" => "La date sélectionné possède déjà une limite budgétaire !"]);
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
        $user = $request->user();

        if($limitedBudget->isLatestLimitedBudgetOfUser){
            $validated = $request->validated();
            $limitedBudget->amount = empty($validated["amount"]) ? $limitedBudget->amount : $validated["amount"];
            $limitedBudget->previsionDate = empty($validated["previsionDate"]) ? $limitedBudget->previsionDate : $validated["previsionDate"];
            $limitedBudget->save();

            return response()->json($limitedBudget);
        }else{
            return response()->json([
                "response" => "La limite budgétaire que vous essayez de modifier n'est pas la dernière de l'utilisateur !"
            ]);
        }
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
}
