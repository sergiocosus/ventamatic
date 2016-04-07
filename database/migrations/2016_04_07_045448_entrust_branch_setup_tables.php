<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustBranchSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('branch_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('branch_role_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('branch_role_id')->unsigned();
            $table->integer('branch_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('branch_role_id')->references('id')->on('branch_roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'branch_role_id', 'branch_id']);
        });

        // Create table for storing permissions
        Schema::create('branch_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('branch_permission_role', function (Blueprint $table) {
            $table->integer('branch_permission_id')->unsigned();
            $table->integer('branch_role_id')->unsigned();

            $table->foreign('branch_permission_id')->references('id')->on('branch_permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('branch_role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['branch_permission_id', 'branch_role_id'],'branch_permission_role_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('branch_permission_role');
        Schema::drop('branch_permissions');
        Schema::drop('branch_role_user');
        Schema::drop('branch_roles');
    }
}
