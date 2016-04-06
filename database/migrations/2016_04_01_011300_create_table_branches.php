<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBranches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->mediumText('description');
            $table->string('address');
            $table->string('title_ticket');
            $table->mediumText('header_ticket');
            $table->string('footer_ticket');
            $table->string('image_hash');
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
        Schema::drop('branches');
    }
}
