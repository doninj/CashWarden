<?php

namespace App\Models;

use App\Models\Nordigen\StaticObjects;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public $incrementing = false;

    public static function refreshData()
    {
        $accounts = self::all();
        foreach ($accounts as $account){
            $account->setCurrentBalanceAmount();
            $account->setLatestTransactions();
        }
    }

    public function users(){
        return $this->hasMany(User::class, "account_id", "id");
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, "account_id", "id");
    }

    public function balances(){
        return $this->hasMany(Balance::class, "account_id", "id");
    }
    public function LatestBalances(){
        return $this->hasOne(Balance::class, "account_id", "id")->orderByDesc('dateAmount');
    }

    public function transactionsForActualMonth() {

        $dateOfStartMonth= Carbon::now()->startOfMonth();
        $dateOfEndMonth = Carbon::now()->endOfMonth();
        return $this->transactions()->whereBetween('dateTransaction',[$dateOfStartMonth,$dateOfEndMonth]);
    }
    public function TotaltransactionsForActualMonth() {

        $total = 0;
        $transactions = $this->transactionsForActualMonth()->get();
        $sum = $transactions->map(function ($transaction, $key) {
            if($transaction->montant < 0) {
            return $transaction->montant;
            }
        })->sum();
        return $sum;
    }
    public function GetThreeTransactions()
    {
        return $this->transactions()->orderByDesc('dateTransaction')->limit(3);
    }
    public function setLatestTransactions()
    {
        $transactions = $this->transactions()->orderBy("dateTransaction");
        if($transactions->count() > 0){
            $latestTransactionDate = $this->transactions()->orderByDesc("dateTransaction")->first()->getTransactionDate();
            $latestTransactionDate->addDay();
            $request = StaticObjects::$nordigenAPI->getTransactions($this->id, $latestTransactionDate);
        }else{
            $request = StaticObjects::$nordigenAPI->getTransactions($this->id);
        }
        $status = $request->getStatusCode();
        $response = json_decode($request->getBody()->getContents());
        if (in_array($status, [200, 201, 202])) {
            $transactionsListResponse = $response->transactions;
            foreach ($transactionsListResponse as $statusTransactionResponse) {
                foreach ($statusTransactionResponse as $transactionResponse) {
                    $transaction = new Transaction();
                    $transaction->label = $transactionResponse->remittanceInformationUnstructuredArray[0];

                    $transactionAmountInformation = $transactionResponse->transactionAmount;
                    $transaction->montant = $transactionAmountInformation->amount;
                    $transaction->monnaie = $transactionAmountInformation->currency;

                    $transaction->dateTransaction = $transactionResponse->bookingDate;
                    $transaction->status = "booked";
                    $transaction->account_id = $this->id;
                    $transaction->category_id = Category::$DEFAULT_CATEGORY_ID;
                    $transaction->save();
                }
            }
        }
    }

    public function initBalance()
    {
        $this->setCurrentBalanceAmount();
    }

    public function setCurrentBalanceAmount(){
        $request = StaticObjects::$nordigenAPI->getCurrentAmmountOfAccount($this->id);
        $status = $request->getStatusCode();
        $response = json_decode($request->getBody()->getContents());
        if(in_array($status, [200, 201, 202])){
            $balanceListAmount = $response->balances;
            $currentBalance = $balanceListAmount[0];
            $balance = new Balance();
            $balance->amount = (double) $currentBalance->balanceAmount->amount;
            if($currentBalance->referenceDate ?? false){
                $balance->dateAmount = $currentBalance->referenceDate;
            }else{
                $balance->dateAmount = Carbon::now();
            }
            $balance->account_id = $this->id;
            $balance->save();
        }
    }
}
