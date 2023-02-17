<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                //role seeder
                $role = Role::create([
                    'name'          => 'super_admin',
                    'display_name'  => 'super admin',
                    'description'   => 'can do any thing',
                ]);
        
        
                //permission
                $rols = [
                        "admins",
                        "roles",
                        'students',
                        'teachers',
                        "countries",
                        "curriculums",
                        "promo_codes",
                        "questions",
                        'class_types',
                        'settings',
                        'offers',
                        'classes',
                    ];
        
                foreach($rols as $rol){
                    $create = Permission::create([
                        'name'          => 'create-' . $rol,
                        'display_name'  => 'Create ' . $rol,
                        'description'   => 'create new' . $rol,
                    ]);
        
                    $read = Permission::create([
                        'name'          => 'read-' . $rol,
                        'display_name'  => 'read ' . $rol,
                        'description'   => 'read ' . $rol,
                    ]);
        
                    $update = Permission::create([
                        'name'          => 'update-' . $rol,
                        'display_name'  => 'update ' . $rol,
                        'description'   => 'update ' . $rol,
                    ]);
        
                    $delete = Permission::create([
                        'name'          => 'delete-' . $rol,
                        'display_name'  => 'delete ' . $rol,
                        'description'   => 'delete ' . $rol,
                    ]);
        
                    //add to permission_role
                    $role->attachPermissions([$create, $read, $update, $delete]);
                }
    }
}
