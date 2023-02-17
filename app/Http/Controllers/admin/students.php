<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class students extends Controller
{
    public function index(){
        //select all admin
        $students = Student::where('status', '!=', -1)->get();
        return view('admins.students.index')->with('students', $students);
    }

    public function block($student_id){
        //get student 
        $student = Student::find($student_id);

        if($student == null)
            return redirect('admins/students')->with('error', 'block faild');

        //change user status
        if($student->status == 0){
            $student->status = 1;
        } else {
            $student->status = 0;
        }  
        $student->save();

        return redirect('admins/students')->with('success', 'block student success');
    }
}
