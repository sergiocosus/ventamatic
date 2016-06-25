<?php

use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Ventamatic\Core\External\Client::create([
            'id' => 1,
            'name' => 'Venta al público',
        ]);
    }
}
