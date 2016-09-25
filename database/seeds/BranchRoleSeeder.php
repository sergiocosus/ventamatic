<?php

use Illuminate\Database\Seeder;

class BranchRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branchRole = new \Ventamatic\Core\User\Security\BranchRole();
        $branchRole->name = 'admin';
        $branchRole->display_name = 'Administrador de sucursale';
        $branchRole->description = 'Control sobre las sucursales del sistema';
        $branchRole->protected = true;

        $branchRole->save();

        $permissions = \Ventamatic\Core\User\Security\BranchPermission::all();

        $branchRole->attachPermissions($permissions);
    }
}
