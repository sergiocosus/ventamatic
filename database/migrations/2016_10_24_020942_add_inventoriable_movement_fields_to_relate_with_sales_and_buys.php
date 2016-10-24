<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInventoriableMovementFieldsToRelateWithSalesAndBuys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_movements', function(Blueprint $table) {
            $table->unsignedInteger('inventoriable_movement_id');
            $table->string('inventoriable_movement_type');

            $table->index([
                'inventoriable_movement_id',
                'inventoriable_movement_type'
            ], 'inventoriable_index');

            $table->dropColumn('cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_movements', function(Blueprint $table) {
            $table->dropIndex('inventoriable_index');
            $table->dropColumn('inventoriable_movement_id');
            $table->dropColumn('inventoriable_movement_type');
            $table->decimal('cost',15,2);
        });
    }
}
