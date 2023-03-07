<?php

namespace App\Http\Controllers\site\guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\teacher_classesTypeResourc;
use App\Http\Resources\teacherResource;
use App\Models\Subject;
use App\Models\Teacher;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    use response;
    public function show(Request $request){
        $validator = Validator::make($request->all(), [
            'teacher_id'       => 'required|string|exists:teachers,id',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $teacher = Teacher::find($request->teacher_id);

        return $this->success(trans('auth.success'), 200, 'teacher', new teacherResource($teacher));
    }

    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'username'    => 'required|string',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $teachers = Teacher::active()->where('username', 'LIKE', '%' . $request->username . '%');

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'teachers_count'    => $teachers->count(),
            'teachers'          => teacherResource::collection($teachers->paginate(5))->response()->getData(true),
        ], 200);
    }
    
    public function teachersBysubject(Request $request){
        $validator = Validator::make($request->all(), [
            'subject_id'    => 'required|exists:subjects,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $subject = Subject::find($request->subject_id);

        $online_teachers = Teacher::active()
                                    ->where('online', 1)
                                    ->where('main_subject_id', $subject->main_subject_id)
                                    ->whereHas('Teacher_years', function($qeury) use($subject){
                                        $qeury->where('year_id', $subject->Term->year_id);
                                    })
                                    ->limit(5);

        $offline_teachers = Teacher::active()
                                    ->where('online', 0)
                                    ->where('main_subject_id', $subject->main_subject_id)
                                    ->whereHas('Teacher_years', function($qeury) use($subject){
                                        $qeury->where('year_id', $subject->Term->year_id);
                                    });

        return response()->json([
            'successful'                => true,
            'message'                   => trans('auth.success'),
            'online_teachers_count'     => $online_teachers->count(),
            'offline_teachers_count'     => $offline_teachers->count(),
            'online_teachers'   => teacher_classesTypeResourc::collection($online_teachers->get()),
            'offline_teachers'  => teacher_classesTypeResourc::collection($offline_teachers->paginate(5))->response()->getData(true),
        ], 200);
    }


    public function online_teachers_bysubject(Request $request){
        $validator = Validator::make($request->all(), [
            'subject_id'    => 'required|exists:subjects,id',
        ]);

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');
        
        $subject = Subject::find($request->subject_id);

        //online
        $online_teachers = Teacher::active()
                                    ->where('online', 1)
                                    ->where('main_subject_id', $subject->main_subject_id)
                                    ->whereHas('Teacher_years', function($qeury) use($subject){
                                        $qeury->where('year_id', $subject->Term->year_id);
                                    })
                                    ->limit(5);

        return response()->json([
            'successful'            => true,
            'message'               => trans('auth.success'),
            'online_teachers_count' => $online_teachers->count(),
            'online_teachers'       => teacher_classesTypeResourc::collection($online_teachers->get()),
        ], 200);
    }
}
