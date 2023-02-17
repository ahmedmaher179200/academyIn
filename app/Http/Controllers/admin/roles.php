<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\roles\addRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class roles extends Controller
{
    public function index(){
        //select all roles
        $roles = Role::get();
        return view('admins.roles.index')->with('roles', $roles);
    }

    public function createView(){
        return view('admins.roles.create');
    }

    public function create(addRequest $request){
        //create role
        $role = Role::create([
            'name'          => $request->name,
            'display_name'  => $request->name,
            'description'   => $request->description
        ]);
        //add permissions to this role
        $role->attachPermissions($request->permissions);

        return redirect('admins/roles')->with('success', 'add permissions success');
    }

    public function delete($id){
        $role = Role::find($id);

        //if admin not found
        if($role == null){
            return redirect('admins/roles')->with('error', 'delete faild');
        }

        //delete admin
        $role->delete();

        return redirect('admins/roles')->with('success', 'edit success');
    }

    public function editView($role_id){
        $role = Role::find($role_id);

        //if admin not found
        if($role == null)
            return redirect('admins/roles');

        return view('admins.roles.edit')->with('role', $role);
    }

    public function edit($role_id, addRequest $request){
        $role = Role::find($role_id);

        //if admin not found
        if($role == null)
            return redirect('admins/roles');

        //update role
        $role->name            = $request->name;
        $role->display_name    = $request->name;
        $role->description     = $request->description;
        $role->save();

        $role->syncPermissions($request->permissions); //update role permassion

        return redirect('admins/roles')->with('success', 'update success');
    }
}
