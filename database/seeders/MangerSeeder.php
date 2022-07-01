<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Manger;

class MangerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Manger::create([
            'name' => 'Eazly manger',
            'email' => 'Eazly@Manger.com',
            'phone' => '231523532',
            'password' => 'password',
            'code_id' => 1
        ]);

        Manger::create([
            'name' => 'Eazly manger',
            'email' => 'Eazly2@Manger.com',
            'phone' => '231522133532',
            'password' => 'password',
            'code_id' => 2
        ]);
    }
}
