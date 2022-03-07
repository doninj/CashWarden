<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $account = $user->account;
        $transactions = $account->transactions()->orderByDesc("dateTransaction");
        $transactions->paginate(15);
        return response()->json($transactions->get());
    }
    public function transactionsPerMonth(Request $request)
    {
        $monthInFr = $request->date;
        $year = $request->annee;
        $monthInEng= Carbon::translateTimeString($monthInFr);
        $month = Carbon::parse($monthInEng)->month;
        $user = $request->user();
        $account = $user->account;
        $transaction['AllTransactions'] = $account->transactions()->whereMonth('dateTransaction', '=', $month)->WhereYear('dateTransaction','=',$year)->get();
        $transaction['TotalDépenses'] = abs($transaction['AllTransactions']->map(function ($transaction) {
            if ($transaction->montant > 0)
                return $transaction->montant;
        })->sum());
        return response()->json($transaction);
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
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransactionRequest  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
