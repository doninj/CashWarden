<?php

namespace App\Models;

use App\Models\Nordigen\NordigenAPI;
use App\Models\Nordigen\StaticObjects;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @OA\Schema(
 *     required={"password"},
 *     @OA\Property (
 *         property="id", type="integer", example="1"
 *     ),
 *     @OA\Property (
 *         property="lastname", type="string", example="DUPONT"
 *     ),
 *     @OA\Property (
 *         property="firstname", type="string", example="Jean"
 *     ),
 *     @OA\Property (
 *         property="email", type="string", example="dupont.jean@gmail.com"
 *     )
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public $appends = [
        "hasBankAuthorization",
        "hasAccountChoices"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function limitedBudgets(){
        return $this->hasMany(LimitedBudget::class);
    }

    public function limitedTransactions(){
        return $this->hasMany(LimitedTransaction::class);
    }

    public function account(){
        return $this->hasOne(Account::class, "id", "account_id");
    }

    public function bank(){
        return $this->hasOne(Bank::class, "id", "bank_id");
    }

    public function getHasBankAuthorizationAttribute(){
        if($this->idRequisition ?? null){
            return $this->haveRequisitionValidated();
        }else{
            return false;
        }
    }

    public function getHasAccountChoicesAttribute(){
        if($this->account()->exists()){
            return true;
        }else{
            return false;
        }
    }

    public function haveRequisitionValidated(){
        $request = StaticObjects::$nordigenAPI->getRequisitionById($this->idRequisition);
        if(in_array($request->getStatusCode(), [200, 201, 202])){
            //?ref=f1cb3135-f831-4f2a-be09-22e5c803e671
            $response = json_decode($request->getBody()->getContents());
            if($response->status == "LN"){
                return true;
            }
        }
        return false;
    }

    /**
     * Méthode permettant de set le mot de passe
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = bcrypt($password);
    }

    /**
     * Méthode permettant de set une banque par son id
     * @param $bankId
     * @return void
     */
    public function setBankById($bankId){
        $bank = Bank::find($bankId);
        $this->setRelation("bank", $bank);
    }

    public function addAccount($accountData)
    {
        $account = new Account();
        $account->id = $accountData["id"];
        $account->name = $accountData["name"];
        $account->save();
        $this->account_id = $account->id;
        $this->save();
        $account->initBalance();
        $account->setLatestTransactions();
    }

    public function haveAnyLimitedBudgetAt($dateBudget){
        $limitedBudgets = $this->limitedBudgets();
        return !$limitedBudgets->whereDate("previsionDate", "=", $dateBudget)->exists();
    }

    public function getLatestLimitedBudget(){
        return $this->limitedBudgets()->orderByDesc("previsionDate")->first();
    }

    public function getLimitedBudgetAfter($dateString){
        $date = Carbon::parse($dateString);
        return $this->limitedBudgets()->whereDate("previsionDate", ">", $date)->orderByDesc("previsionDate");
    }

    public function getLatestLimitedBudgets($limit){
        $limitedBudgets = $this->limitedBudgets()->orderByDesc("previsionDate")->limit($limit);
    }

    public function getLatestBalances($limit){
        return $this->account->balances()->orderByDesc("dateAmount")->limit($limit)->get();
    }

    public function getLatestLimitedBudgetAt($date){
        return $this->limitedBudgets()->whereYear("previsionDate", "=", $date->year)->whereMonth("previsionDate", "=", $date->month)->orderByDesc("previsionDate")->first();
    }

    public function getLatestRealBalanceAt($date){
        return $this->account->balances()->whereYear("dateAmount", "=", $date->year)->whereMonth("dateAmount", "=", $date->month)->orderByDesc("dateAmount")->first();
    }
}


