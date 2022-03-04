<?php

namespace App\Models;

use App\Models\Nordigen\StaticObjects;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function users(){
        return $this->hasMany(User::class, "account_id", "id");
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, "account_id", "id");
    }

    public function initTransaction()
    {
        $request = StaticObjects::$nordigenAPI->getTransactions($this->id);
        $status = $request->getStatusCode();
        $response = json_decode($request->getBody()->getContents());
        if(in_array($status, [200, 201, 202])){
            $transactionsListResponse = $response->transactions;
            foreach($transactionsListResponse as $statusTransactionResponse){
                foreach($statusTransactionResponse as $transactionResponse){
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
}
