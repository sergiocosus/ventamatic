<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_supplier', function(Blueprint $table){
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('supplier_id');

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->primary(['brand_id', 'supplier_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('brand_supplier');
    }
}
