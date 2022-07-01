<?php

namespace Database\Seeders;

use App\Models\Code;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Psy\Util\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {



        Code::create([
            'code' => \Illuminate\Support\Str::random(16),
            'start_time' => Carbon::now()->toDateString(),
            'end_time' => Carbon::now()->addYear()->toDateString(),
            'status' => 'active'
        ]);

        Code::create( [
            'code' => \Illuminate\Support\Str::random(16),
            'start_time' => Carbon::now()->toDateString(),
            'end_time' => Carbon::now()->toDateString(),
            'status' => 'active'
        ]);

       $this->call([
            MangerSeeder::class,
      ]);
    }
}
