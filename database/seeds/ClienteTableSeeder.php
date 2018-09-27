<?php

use Illuminate\Database\Seeder;

class ClienteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('clientes')->insert([
            'nombre'=>str_random(10),
            'apellido'=>str_random(10),
            'telefono'=>str_random(8),
            'dni'=>str_random(10)
        ]);
    }
}
