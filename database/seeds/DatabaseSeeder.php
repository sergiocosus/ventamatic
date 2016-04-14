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
        $this->call(UsersSeeder::class);
    }
}
