<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\students_numbers_cost\add;
use App\Models\Cost_student_number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class students_numbers_cost extends Controller
{
    public function index(){
        $cost_students_numbers = Cost_student_number::get();
        return view('admins.students_numbers_cost.index')->with('students_numbers_cost', $cost_students_numbers);
    }

    public function createView(){
        return view('admins.students_numbers_cost.create');
    }

    public function create(add $request){
        try{
            DB::beginTransaction();
                //create countries percentage
                Cost_student_number::create([
                    'cost'                   => $request->cost,
                    'min_students_number'    => $request->min,
                    'max_students_number'    => $request->max,
                ]);

            DB::commit();
            return redirect('admins/students_numbers_cost')->with('success', 'add students numbers cost success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/students_numbers_cost')->with('error', 'add students numbers cost faild');
        }
    }

    public function delete($id){
        //get cost student number
        $cost_student_number = Cost_student_number::find($id);

        if($cost_student_number == null)
            return redirect('admins/students_numbers_cost')->with('error', 'delete faild');

        $cost_student_number->delete();

        return redirect('admins/students_numbers_cost')->with('success', 'delete students numbers cost success');
    }

    public function editView($id){
        $cost_student_number = Cost_student_number::find($id);

        //if cost student number not found
        if($cost_student_number == null)
            return redirect('admins/students_numbers_cost');

        return view('admins.students_numbers_cost.edit')->with([
            'student_number_cost' => $cost_student_number,
        ]);
    }

    public function edit($id,add $request){
        $cost_student_number = Cost_student_number::find($id);

        try{
            DB::beginTransaction();
                //edit country
                $cost_student_number->min_students_number        = $request->min;
                $cost_student_number->max_students_number        = $request->max;
                $cost_student_number->save();

            DB::commit();
            return redirect('admins/students_numbers_cost')->with('success', 'add students numbers cost success');
        } catch(\Exception $ex){
            //if there are error
            return redirect('admins/students_numbers_cost')->with('error', 'add students numbers cost faild');
        }
    }
}
