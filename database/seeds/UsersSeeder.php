<?php

use Illuminate\Database\Seeder;
use Ventamatic\Core\User\Security\BranchRole;
use Ventamatic\Core\User\Security\Role;
use Ventamatic\Core\User\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->username = 'admin';
        $user->password = bcrypt('admin2000');
        $user->protected = true;
        $user->save();


        $user->attachRole(Role::whereName('admin')->first());

        $branches = \Ventamatic\Core\Branch\Branch::all();
        $branchRole = BranchRole::whereName('admin')->first();
        foreach ($branches as $branch) {
            $user->attachBranchRole($branchRole, $branch);
        }

    }
}
