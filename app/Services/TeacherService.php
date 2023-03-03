<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Http\Controllers\site\teacher\authentication\verification;
use App\Http\Resources\teacherResource;
use App\Models\Image;
use App\Models\Teacher;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherService extends Controller
{
    use response;
    public $verification;
    public function __construct(verification $verification)
    {
        $this->verification = $verification;
    }

    public function teacher_response($request, $token){
        $teacher = auth('teacher')->user();

        //update token
        $teacher->token_firebase = $request->token_firebase;
        $teacher->save();

        //check if user blocked
        if($teacher['status'] == 0)
            return response::faild(trans('auth.you are blocked'), 402, 'E02');
        
        $successful = true;
        $step    = 'verify';
        // check if teatcher not active
        if($teacher['verified'] == 0){
            $this->verification->sendCode($request);
            $successful = false;
            $step    = true;
        }

        // check if setup_profile
        if(count($teacher->Teacher_years) == 0){
            $successful = false;
            $step    = 'setup_profile';
        }
        
        return response()->json([
            'successful'=> $successful,
            'step'      => $step,
            'message'   => 'success',
            'teacher'   => new teacherResource($teacher),
            'token'     => $token,
        ], 200);
    }

    public function create($request){
        $teacher = Teacher::create([
            'username'          => $request->get('username'),
            'dialing_code'      => $request->get('dialing_code'),
            'phone'             => $request->get('phone'),
            'password'          => Hash::make($request->get('password')),
            'country_id'        => $request->get('country_id'),
            'curriculum_id'     => $request->get('curriculum_id'),
            'main_subject_id'   => $request->get('subject_id'),
            'gender'            => $request->get('gender'),
        ]);

        return $teacher;
    }

    public function updateImage($teacher, $image){
        $path = $this->upload_image($image,'uploads/teachers', 300, 300);
        if($teacher->Image == null){
            //if user don't have image 
            Image::create([
                'imageable_id'   => $teacher->id,
                'imageable_type' => 'App\Models\Teacher',
                'src'            => $path,
            ]);

        } else {
            //if teacher have image
            $oldImage = $teacher->Image->src;

            if(file_exists(base_path('public/uploads/teachers/') . $oldImage)){
                unlink(base_path('public/uploads/teachers/') . $oldImage);
            }

            $teacher->Image->src = $path;
            $teacher->Image->save();
        }
    }

    public function update($teacher, $request){
        $input = $request->only(
            'username','email', 'about','dialing_code', 'phone','country_id','curriculum_id','year_id',
            'gender', 'birth'
        );

        if($request->get('subject_id') != null){
            $teacher->main_subject_id = $request->get('subject_id');
            $teacher->save();
        }
        
        if($request->has('image'))
            $this->updateImage($teacher, $request->file('image'));

        $teacher->update($input);
    }
}