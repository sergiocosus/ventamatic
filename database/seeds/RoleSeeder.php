<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
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
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Control total sobre el sistema',
            ],

        ];

        foreach ($data as $role)
        {
            \Ventamatic\Core\User\Security\Role::create($role);
        }
    }
}
