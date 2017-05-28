<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMovementTypeToBuyProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_product', function (Blueprint $table) {
            $table->unsignedInteger('inventory_movement_type_id');

            $table->foreign('inventory_movement_type_id')
                ->references('id')->on('inventory_movement_types');

            //$table->dropPrimary();
            //$table->primary(['product_id', 'buy_id', 'inventory_movement_type_id']);
        });


        DB::statement('alter table buy_product drop primary key, add primary key(product_id, buy_id, inventory_movement_type_id);');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_product', function (Blueprint $table) {
            DB::statement('alter table buy_product drop primary key, add primary key(product_id, buy_id);');

            $table->dropForeign('buy_product_inventory_movement_type_id_foreign');
            $table->dropColumn('inventory_movement_type_id');
        });
    }
}
