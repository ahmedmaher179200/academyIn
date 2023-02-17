<?php

namespace App\Http\Controllers\office;

use App\Http\Controllers\Controller;
use App\Http\Resources\teacher_targetResource;
use App\Models\Teacher;
use Illuminate\Http\Request;

class teachers extends Controller
{
    public function index(){
        //select all teachers
        $teachers = teacher::where('status', '!=', -1)
                            ->whereHas('Office_teacher', function($query){
                                $query->where('office_id', auth('office')->user()->id);
                            })
                            ->get();
                
        return view('offices.teachers.index')->with('teachers', $teachers);
    }
}
