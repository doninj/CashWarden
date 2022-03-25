<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    /**
     * @var mixed
     */

    public $appends = [
        "hasBankAuthorization",
        "hasAccountChoices"
    ];

    public static $DEFAULT_CATEGORY_ID = 1;

    public function getHasBankAuthorizationAttribute(){
        return $this->transactions()->count();
    }

    public function getHasAccountChoicesAttribute(){
        return $this->transactions()->count();
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, "category_id", "id");
    }

}
