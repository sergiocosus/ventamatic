<?php

use Illuminate\Database\Seeder;
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
        User::create([
            'username' => 'admin',
            'password' => bcrypt('admin2000'),
        ]);
        
    }
}