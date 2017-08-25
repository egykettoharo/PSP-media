<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Withdraw', function ($table) {
            $table->engine = 'InnoDB';

            $table->increments('withdraw_id');
            $table->integer('customer_id')->nullable()->unsigned();
            $table->float('amount')->unsigned();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('Customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Withdraw');
    }
}
