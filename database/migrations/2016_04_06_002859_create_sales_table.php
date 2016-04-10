<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('payment_type_id');

            $table->string('card_payment_id');
            $table->decimal('client_payment',13,2);
            $table->decimal('total',13,2);

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('branch_id');
            
            $table->foreign('payment_type_id')->references('id')->on('payment_types');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('branch_id')->references('id')->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sales');
    }
}
