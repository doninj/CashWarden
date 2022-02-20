<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public function users(){
        return $this->hasMany(User::class, "account_id", "id");
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, "account_id", "id");
    }
}
