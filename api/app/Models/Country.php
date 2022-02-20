<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public function countries(){
        $this->belongsToMany(Bank::class, "link_between_bank_and_contries", "country_id", "bank_id");
    }
}
