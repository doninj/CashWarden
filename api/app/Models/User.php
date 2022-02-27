<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

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
        "hasBankAutorization",
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

    public function getHasBankAutorizationAttribute(){
        if($this->idRequisition ?? null){
            return true;
        }else{
            return false;
        }
    }

    public function getHasAccountChoicesAttribute(){
        if($this->account_id){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Méthode permettant de set le mot de passe
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = bcrypt($password);
    }
}
