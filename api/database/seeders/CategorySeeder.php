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
            ["id" => 1, "label" => "Non renseignée", "icon" => "\uf141"],
            ["id" => 2, "label" => "Loisirs", "icon" => "\uf135"],
            ["id" => 3, "label" => "Loyer", "icon" => "\uf658"],
            ["id" => 4, "label" => "Shopping", "icon" => "\uf07a"],
            ["id" => 5, "label" => "Santé", "icon" => "\uf469"],
            ["id" => 6, "label" => "Abonnement", "icon" => "\uf3cd"]
        ];
        DB::table("categories")->insert($data);
    }
}
