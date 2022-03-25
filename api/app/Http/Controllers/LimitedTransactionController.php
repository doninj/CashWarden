<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLimitedTransactionRequest;
use App\Http\Requests\UpdateLimitedTransactionRequest;
use App\Models\LimitedTransaction;

class LimitedTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
     * @param  \App\Http\Requests\StoreLimitedTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLimitedTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LimitedTransaction  $limitedTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(LimitedTransaction $limitedTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LimitedTransaction  $limitedTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(LimitedTransaction $limitedTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLimitedTransactionRequest  $request
     * @param  \App\Models\LimitedTransaction  $limitedTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLimitedTransactionRequest $request, LimitedTransaction $limitedTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LimitedTransaction  $limitedTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(LimitedTransaction $limitedTransaction)
    {
        //
    }
}
