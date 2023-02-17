<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\class_types\add;
use App\Models\Class_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class class_types extends Controller
{
    public function index(){
        $class_types = Class_type::where('status', '!=', -1)->get();
        return view('admins.class_types.index')->with('class_types', $class_types);
    }

    public function createView(){
        return view('admins.class_types.create');
    }

    public function create(add $request){
        try{
            DB::beginTransaction();
                ($request->status== 1)? $active = 1: $active = 0;
                //create Class type
                Class_type::create([
                    'long'          => $request->long,
                    'long_cost'     => $request->long_cost,
                    'status'        => $active,
                ]);

            DB::commit();
            return redirect('admins/class_types')->with('success', 'add class type success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/class_types')->with('error', 'add class type faild');
        }
    }

    public function delete($id){
        //get Class type 
        $class_type = Class_type::find($id);

        if($class_type == null)
            return redirect('admins/class_types')->with('error', 'delete faild');

        $class_type->status = -1;
        $class_type->save();

        return redirect('admins/class_types')->with('success', 'delete class type success');
    }

    public function editView($id){
        $class_type = Class_type::find($id);

        //if class types not found
        if($class_type == null)
            return redirect('admins/class_types');
        
        return view('admins.class_types.edit')->with([
            'class_type' => $class_type,
        ]);
    }

    public function edit($id,add $request){
        $class_type = Class_type::find($id);

        try{
            DB::beginTransaction();
                ($request->status== 1)? $active = 1: $active = 0;

                //edit class_type
                $class_type->long        = $request->long;
                $class_type->long_cost   = $request->long_cost;
                $class_type->status      = $active;
                $class_type->save();

            DB::commit();
            return redirect('admins/class_types')->with('success', 'add class type e success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/class_types')->with('error', 'add class type faild');
        }
    }
}
