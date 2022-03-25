<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimitedBudget extends Model
{
    use HasFactory;

    public $appends = [
        "isLatestLimitedBudgetOfUser"
    ];

    public function user(){
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function getIsLatestLimitedBudgetOfUserAttribute(){
        $latestLimitedBudget = $this->user->getLatestLimitedBudget();
        return $this->id == $latestLimitedBudget->id;
    }
}
