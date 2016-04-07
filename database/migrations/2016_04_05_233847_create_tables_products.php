<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function(Blueprint $table) {
            $table->increments('id');
            $table->string('bar_code')->unique();
            $table->string('description')->unique();
            $table->decimal('global_minimum',15,3)->nullable(true);
            $table->decimal('global_price', 15,2)->nullable(true);

            $table->unsignedInteger('unit_id');
            $table->unsignedInteger('brand_id')->nullable(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('brand_id')->references('id')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}
