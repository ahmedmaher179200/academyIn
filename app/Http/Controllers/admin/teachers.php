<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class teachers extends Controller
{
    public function index(){
        //select all teachers
        $teachers = Teacher::where('status', '!=', -1)->get();
        return view('admins.teachers.index')->with('teachers', $teachers);
    }

    public function block($teacher_id){
        //get teachers 
        $teacher = Teacher::find($teacher_id);

        if($teacher == null)
            return redirect('admins/teachers')->with('error', 'block faild');

        //change user status
        if($teacher->status == 0){
            $teacher->status = 1;
        } else {
            $teacher->status = 0;
        }  
        $teacher->save();

        return redirect('admins/teachers')->with('success', 'block teacher success');
    }
}
