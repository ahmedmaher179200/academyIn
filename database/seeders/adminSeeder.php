<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class adminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            'username'  => 'ahmed1',
            'password'  => bcrypt('ahmed1'),
        ]);

        $admin->attachRole('super_admin');
    }
}
