<?php

use Illuminate\Database\Seeder;

class BranchPermissionForBranchSeeder extends Seeder
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
                'name' => 'branch-get-detail',
                'display_name' => 'Obtener detalle de sucursal',
                'description' => '',
            ],
            [
                'name' => 'branch-edit',
                'display_name' => 'Editar los datos de una sucursal',
                'description' => '',
            ]
        ];

        foreach ($data as $branchPermission)
        {
            \Ventamatic\Core\User\Security\BranchPermission::create($branchPermission);
        }
    }
}
