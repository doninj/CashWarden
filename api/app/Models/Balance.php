<?php

namespace App\Models;

use App\Models\Nordigen\StaticObjects;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function account(){
        return $this->hasOne(Account::class, "id", "account_id");
    }
}
