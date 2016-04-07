<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sale', function(Blueprint $table){
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('sale_id');
            $table->decimal('quantity',15,3);
            $table->decimal('price',15,2);

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('sale_id')->references('id')->on('sales');

            $table->primary(['product_id', 'sale_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_sale');
    }
}
