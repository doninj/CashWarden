<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function category(){
        return $this->hasOne(Category::class);
    }

    public function account(){
        return $this->hasOne(Account::class);
    }
}
