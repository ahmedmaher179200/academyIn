<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class settingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create([
            'cost_students_number'      => 1.2,
            'cost_level'                => 1.2,
            'cost_country'              => 1.2,
            'cost_company_percentage'   => 20,
        ]);
    }
}
