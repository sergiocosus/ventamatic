<?php

use Illuminate\Database\Seeder;

class ScheduleStatuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('schedule_statuses')->insert([
            [
                'id' => 1,
                'name' => 'incomplete',
            ],
            [
                'id' => 2,
                'name' => 'warning',
            ],
            [
                'id' => 3,
                'name' => 'ok',
            ]
        ]);
        
    }
}
