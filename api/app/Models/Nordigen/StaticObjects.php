<?php

namespace App\Models\Nordigen;

class StaticObjects
{
    public static $nordigenAPI;

    public static function init() {
        self::$nordigenAPI = new NordigenAPI();
    }
}
