<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkBetweenBankAndCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_between_bank_and_countries', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('country_id');
            $table->primary(["bank_id", "country_id"]);
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_between_bank_and_countries');
    }
}
