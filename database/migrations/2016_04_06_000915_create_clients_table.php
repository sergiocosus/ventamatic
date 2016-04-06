<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('last_name');
            $table->string('last_name_2');
            $table->string('email');
            $table->string('phone');
            $table->string('cellphone');
            $table->string('address');
            $table->string('rfc',15);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clients');
    }
}
