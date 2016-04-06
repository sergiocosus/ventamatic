<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBuyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_buy', function(Blueprint $table){
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('buy_id');
            $table->decimal('quantity',15,3);
            $table->decimal('cost',15,2);

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('buy_id')->references('id')->on('buys');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_buy');
    }
}
