<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('currency');
            $table->string('account_type');
            $table->bigInteger('bvn')->unsigned();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->bigInteger('phone_number')->unsigned()->nullable();
            $table->enum('status',['active','inactive','dormant'])->default('active');
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
        Schema::dropIfExists('customers');
    }
}
