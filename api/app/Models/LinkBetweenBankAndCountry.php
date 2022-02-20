<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkBetweenBankAndCountry extends Model
{
    use HasFactory;

    public function bank(){
        return $this->hasOne(Bank::class);
    }

    public function country(){
        return $this->hasOne(Country::class);
    }
}
