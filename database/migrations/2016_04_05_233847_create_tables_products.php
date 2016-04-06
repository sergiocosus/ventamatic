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
            $table->decimal('minimum',15,3);

            $table->unsignedInteger('unit_id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('category_id')->nullable(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('category_id')->references('id')->on('categories');

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
