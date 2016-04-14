<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username');
            $table->string('last_name');
            $table->string('last_name_2');
            $table->string('phone');
            $table->string('cellphone');
            $table->string('address');
            $table->string('rfc', 15);

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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropColumn('last_name_2');
            $table->dropColumn('phone');
            $table->dropColumn('cellphone');
            $table->dropColumn('address');
            $table->dropColumn('rfc');

            $table->dropSoftDeletes();
        });
    }
}
