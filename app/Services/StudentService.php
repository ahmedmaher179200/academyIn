<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Http\Controllers\site\student\authentication\verification;
use App\Http\Resources\studentResource;
use App\Models\Image;
use App\Models\Student;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentService extends Controller
{
    use response;

    public $verification;
    public function __construct(verification $verification)
    {
        $this->verification = $verification;
    }

    public function Create($request, $verified = 0){
        $student = Student::create([
            'username'          => $request->get('username'),
            'dialing_code'      => $request->get('dialing_code'),
            'phone'             => $request->get('phone'),
            'password'          => Hash::make($request->get('password')),
            'country_id'        => $request->get('country_id'),
            'curriculum_id'     => $request->get('curriculum_id'),
            'gender'            => $request->get('gender'),
            'token_firebase'    => $request->get('token_firebase'),
            'verified'          => $verified,
        ]);

        if($verified == 0){
            $code = $this->verification->createCode($request->get('phone'));
            // $response =  $this->send_message($student->dialing_code , $student->phone , 'your code is ' . $code);
        }

        return $student;
    }

    public function updateImage($student, $image){
        $path = $this->upload_image($image,'uploads/students', 300, 300);

        if($student->Image == null){    //if user don't have image 
            Image::create([
                'imageable_id'   => $student->id,
                'imageable_type' => 'App\Models\Student',
                'src'            => $path,
            ]);

        } else {    //if student have image
            $oldImage = $student->Image->src;

            if(file_exists(base_path('public/uploads/students/') . $oldImage))
                unlink(base_path('public/uploads/students/') . $oldImage);

            $student->Image->src = $path;
            $student->Image->save();
        }
    }

    
    public function student_response($request, $token){
        $student = auth('student')->user();

        //update firbase token
        $student->token_firebase = $request->get('token_firebase');
        $student->save();

        $successful = true;
        $steptep    = true;

        //check if user blocked
        if($student['status'] == 0){
            $successful = false;
            $steptep    = 'blocked';
        }

        // check if student not active
        if($student['verified'] == 0){
            $this->verification->sendCode($request);
            
            $successful = false;
            $steptep    = 'verify';
        }

        // check if setup_profile
        if($student['year_id'] == null){
            $successful = false;
            $steptep    = 'setup_profile';
        }

        return response()->json([
            'successful'=> $successful,
            'step'      => $steptep,
            'message'   => trans('admin.success'),
            'student'   => new studentResource($student),
            'token'     => $token,
        ], 200);
    }

    public function checkBalance($student, $price){
        if($student->balance - $price < 0)
            return false;

        return true;
    }

    public function addToBalance($student, $amount){
        $student->balance   += $amount;
        $student->save();
    }
}