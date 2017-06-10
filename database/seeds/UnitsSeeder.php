<?php

use Illuminate\Database\Seeder;
use Ventamatic\Core\Product\Unit;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Pieza',
                'abbreviation' => 'pza',
                'step' => 1
            ],
            [
                'id' => 2,
                'name' => 'Kilogramo',
                'abbreviation' => 'Kg',
                'step' => 0.005
            ],
            [
                'id' => 3,
                'name' => 'Litro',
                'abbreviation' => 'l',
                'step' => 0.005
            ],
            [
                'id' => 4,
                'name' => 'Metro',
                'abbreviation' => 'm',
                'step' => 0.005
            ],

        ];
        
        foreach ($data as $unit)
        {
            Unit::create($unit);
        }
    }
}
