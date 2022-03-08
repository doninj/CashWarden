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
        if($year != null && $monthInFr != null) {
        $monthInEng= Carbon::translateTimeString($monthInFr);
        $month = Carbon::parse($monthInEng)->month;
        } else {
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;
        }
        $user = $request->user();
        $account = $user->account;
        $transaction['allTransactions'] = $account->transactions()->whereMonth('dateTransaction', '=', $month)->WhereYear('dateTransaction','=',$year)->orderBy('dateTransaction','desc')->get();
        $transaction['totalDepenses'] = number_format(abs($transaction['allTransactions']->map(function ($transaction) {
            if ($transaction->montant < 0)
                return $transaction->montant;
        })->sum()),2);
        $transaction['totalIncomes'] = number_format(abs($transaction['allTransactions']->map(function ($transaction) {
            if ($transaction->montant > 0)
                return $transaction->montant;
        })->sum()),2);
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
