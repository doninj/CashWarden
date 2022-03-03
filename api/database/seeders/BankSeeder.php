<?php

namespace Database\Seeders;

use App\Models\Nordigen\StaticObjects;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nordigenAPI = StaticObjects::$nordigenAPI;
        $banks = $nordigenAPI->getBanks("FR");
        $data = [];
        foreach ($banks as $bank){
            $bankData = [
                "id" => $bank->id,
                "name" => $bank->name,
                "logo" => $bank->logo,
            ];
            $data[] = $bankData;
        }
        DB::table("banks")->insert($data);
    }
}
