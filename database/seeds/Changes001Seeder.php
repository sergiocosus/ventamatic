<?php

use Illuminate\Database\Seeder;
use Ventamatic\Core\Branch\InventoryMovementType;

class Changes001Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Ventamatic\Core\User\Security\BranchPermission::create( [
            'name' => 'sale-delete',
            'display_name' => 'Eliminar ventas',
            'description' => '',
        ]);

        InventoryMovementType::firstOrCreate(  [
            'id' => 11,
            'name' => 'Venta Cancelada'
        ]);


    }
}
