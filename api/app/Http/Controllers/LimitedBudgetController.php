<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLimitedBudgetRequest;
use App\Http\Requests\UpdateLimitedBudgetRequest;
use App\Models\LimitedBudget;
use App\Models\User;
use Illuminate\Http\Request;

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
        return response()->json($user->limitedBudgets());
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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLimitedBudgetRequest $request)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLimitedBudgetRequest $request, LimitedBudget $limitedBudget)
    {
        //
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
