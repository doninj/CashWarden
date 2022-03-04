<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ["id" => 1, "label" => "Non renseignée", "icon" => "https://cdn-icons.flaticon.com/png/512/4287/premium/4287539.png?token=exp=1646350043~hmac=79d356a7e58bb81d470b4c4fe7668c0e"],
            ["id" => 2, "label" => "Loisirs", "icon" => "https://cdn-icons-png.flaticon.com/512/2871/2871367.png"],
            ["id" => 3, "label" => "Loyer", "icon" => "https://cdn-icons.flaticon.com/png/512/5055/premium/5055580.png?token=exp=1646349918~hmac=e2771878ebd8b101359e79493149440e"],
        ];
        DB::table("categories")->insert($data);
    }
}
