<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventoryProducts', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('branch_id');
            $table->unsignedInteger('product_id');
            $table->decimal('quantity',15,3);
            $table->decimal('price',15,2);
            $table->decimal('minimum',15,3);

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('branch_id')->references('id')->on('branches');

            $table->unique(['branch_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('inventoryProducts');
    }
}
