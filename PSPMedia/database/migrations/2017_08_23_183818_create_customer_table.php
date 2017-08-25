<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Customer', function ($table) {
            $table->engine = 'InnoDB';

            $table->increments('customer_id');
            $table->string('gender', 255);
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('country', 255);
            $table->string('email', 255)->unique();
            $table->integer('bonus')->nullable()->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Customer');
    }
}
