<?php

use Illuminate\Database\Seeder;
use Ventamatic\Core\User\Security\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'admin';
        $role->display_name = 'Administrador';
        $role->description = 'Control total sobre el sistema';
        $role->protected = true;

        $role->save();

        $permissions = \Ventamatic\Core\User\Security\Permission::all();

        $role->attachPermissions($permissions);
    }
}
