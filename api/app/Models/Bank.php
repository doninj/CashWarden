<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    public function countries(){
        return $this->belongsToMany(Country::class, "link_between_bank_and_contries", "bank_id", "country_id");
    }

    public function users(){
        return $this->hasMany(User::class, "bank_id", "id");
    }
}
