<?php

use Illuminate\Database\Seeder;

class BranchPermissionReportsSeeder extends Seeder
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
                'name' => 'report-schedule',
                'display_name' => 'Obtener reporte de turnos',
                'description' => '',
            ],
            [
                'name' => 'report-sale',
                'display_name' => 'Obtener reporte de ventas',
                'description' => '',
            ],
            [
                'name' => 'report-buy',
                'display_name' => 'Obtener reporte de compras',
                'description' => '',
            ],
            [
                'name' => 'report-inventory-movement',
                'display_name' => 'Obtener reporte de entradas/salidas',
                'description' => '',
            ],
            [
                'name' => 'report-inventory',
                'display_name' => 'Obtener reporte de inventario',
                'description' => '',
            ],
            [
                'name' => 'report-historic-inventory',
                'display_name' => 'Obtener reporte histórico de inventario',
                'description' => '',
            ],
        ];

        foreach ($data as $branchPermission)
        {
            \Ventamatic\Core\User\Security\BranchPermission::create($branchPermission);
        }
    }
}