<?php

use Illuminate\Database\Seeder;
use Ventamatic\Core\Branch\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
            'name' => "Sinaloa"
        ]);
        Branch::create([
            'name' => "Rinconcito del Oeste"
        ]);
    }
}
