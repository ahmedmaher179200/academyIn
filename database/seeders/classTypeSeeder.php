<?php

namespace Database\Seeders;

use App\Models\Class_type;
use Illuminate\Database\Seeder;

class classTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['long' => 45, 'long_cost' => 1.2,'status' => 0],
            ['long' => 60, 'long_cost' => 1.2,'status' => 1],
          ];

          Class_type::insert($data);
    }
}
