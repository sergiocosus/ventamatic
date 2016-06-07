<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableCardPaymentIdBuys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buys', function(Blueprint $table){
            $table->string('card_payment_id')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buys', function(Blueprint $table){
            $table->string('card_payment_id')->nullable(false)->change();
        });
    }
}
