<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PaymentTypesSeeder::class);
        $this->call(ScheduleStatuesSeeder::class);
        $this->call(UnitsSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(InventoryMovementTypeSeeder::class);
        $this->call(ClientSeeder::class);

        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(BranchPermissionSeeder::class);
        $this->call(BranchRoleSeeder::class);

        $this->call(UsersSeeder::class);
    }
}
