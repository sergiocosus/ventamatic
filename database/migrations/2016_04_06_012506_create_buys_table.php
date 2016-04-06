<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buys', function(Blueprint $table){
            $table->increments('id');

            $table->enum('payment_type',[
                'cash',
                'card',
            ]);
            $table->string('card_payment_id');
            $table->decimal('iva',13,2);
            $table->decimal('ieps',13,2);

            $table->decimal('total',13,2);

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('branch_id');
            $table->string('supplier_bill_id');


            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
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
        Schema::drop('buys');
    }
}
