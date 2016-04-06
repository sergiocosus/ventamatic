<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('last_name');
            $table->string('last_name_2');
            $table->string('email');
            $table->string('phone');
            $table->string('cellphone');
            $table->string('address');
            $table->string('rfc',15);

            $table->unsignedInteger('supplier_category_id')->nullable(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supplier_category_id')
                ->references('id')->on('supplier_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('suppliers');
    }
}
