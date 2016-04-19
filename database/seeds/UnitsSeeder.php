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
                'abbreviation' => 'pza'
            ],
            [
                'id' => 2,
                'name' => 'Kilogramo',
                'abbreviation' => 'Kg'
            ],
            [
                'id' => 3,
                'name' => 'Litro',
                'abbreviation' => 'l'
            ],

        ];
        
        foreach ($data as $unit)
        {
            Unit::create($unit);
        }
    }
}
