<?php

use Illuminate\Database\Seeder;
use Ventamatic\Core\Branch\InventoryMovementType;

class InventoryMovementTypeSeeder extends Seeder
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
                'name' => 'Promoción'
            ],
            [
                'id' => 2,
                'name' => 'Traslado'
            ],
            [
                'id' => 3,
                'name' => 'Conversión'
            ],
            [
                'id' => 4,
                'name' => 'Concesión'
            ],
            [
                'id' => 5,
                'name' => 'Caducado'
            ],
            [
                'id' => 6,
                'name' => 'Traslado'
            ],
            [
                'id' => 7,
                'name' => 'Ajuste'
            ],
        ];
        
        foreach ($data as $movementType)
        {
            InventoryMovementType::create($movementType);
        }
    }
}
