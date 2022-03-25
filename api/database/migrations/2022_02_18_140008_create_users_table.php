<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email')->unique();
            $table->string('password');
            $table->string("bank_id")->nullable(true);
            $table->string('idRequisition', 128)->nullable(true);
            $table->string("account_id")->nullable(true);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
