<?php

use Illuminate\Database\Seeder;

class BranchPermissionSeeder extends Seeder
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
                'name' => 'sale',
                'display_name' => 'Realizar ventas',
                'description' => '',
            ],
            [
                'name' => 'buy',
                'display_name' => 'Realizar compras',
                'description' => '',
            ],
            [
                'name' => 'inventory-get',
                'display_name' => 'Obtener inventario',
                'description' => '',
            ],
            [
                'name' => 'inventory-get-detail',
                'display_name' => 'Obtener detalle del inventario',
                'description' => '',
            ],
            [
                'name' => 'inventory-edit',
                'display_name' => 'Modificar inventario',
                'description' => '',
            ],
            [
                'name' => 'user-branch-role-assign',
                'display_name' => 'Asignar rol de sucursal a usuario',
                'description' => '',
            ],
        ];

        foreach ($data as $branchPermission)
        {
            \Ventamatic\Core\User\Security\BranchPermission::create($branchPermission);
        }

        $this->call(BranchPermissionReportsSeeder::class);
        $this->call(BranchPermissionForBranchSeeder::class);

    }
}
