<?php

use Illuminate\Database\Seeder;
use Ventamatic\Core\User\Security\BranchRole;

class BranchRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!$branchRole = BranchRole::whereName('admin')->first()) {
            $branchRole = new BranchRole();
            $branchRole->name = 'admin';
            $branchRole->display_name = 'Administrador de sucursale';
            $branchRole->description = 'Control sobre las sucursales del sistema';
            $branchRole->protected = true;

            $branchRole->save();
        }


        $permissions = \Ventamatic\Core\User\Security\BranchPermission::all();

        $branchRole->savePermissions($permissions);
    }
}
