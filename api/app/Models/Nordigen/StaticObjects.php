<?php

namespace App\Models\Nordigen;

class StaticObjects
{
    public static $nordigenAPI;

    public static function init() {
        if(self::$nordigenAPI == false){
            self::$nordigenAPI = new NordigenAPI();
        }
    }
}
