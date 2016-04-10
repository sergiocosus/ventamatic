<?php

use Illuminate\Database\Seeder;

class PaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_types')->insert([
            [
                'id' => 1,
                'name' => 'cash',
            ],
            [
                'id' => 2,
                'name' => 'card',
            ]
        ]);
        
    }
}
