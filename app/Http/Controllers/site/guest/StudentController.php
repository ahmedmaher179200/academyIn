<?php

namespace App\Http\Controllers\site\guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\studentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function show(Request $request){
        $validator = Validator::make($request->all(), [
            'student_id'       => 'required|string|exists:students,id',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $student = Student::find($request->get('student_id'));

        return $this->success(
                trans('auth.success'),
                200,
                'student',
                new studentResource($student)
            );
    }
}
