<?php

namespace App\Http\Controllers\site\teacher\authentication;

use Aloha\Twilio\Twilio;
use App\Http\Controllers\Controller;
use App\Http\Controllers\site\teacher\authentication\verification;
use App\Http\Resources\teacherResource;
use App\Models\Teacher;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class auth extends Controller
{
    use response;
    public $verification;
    public function __construct(verification $verification)
    {
        $this->verification         = $verification;
    }
    public function login(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'phone'             => 'required',
            'password'          => 'required|string',
            'token_firebase'    => 'nullable|string',
        ]);

        if($validator->fails()){
            return $this->faild($validator->errors()->first(), 403, 'E03');
        }

        //data
        $credentials = ['phone' => $request->phone, 'password' => $request->password];
        
        try {
            if (! $token = auth('teacher')->attempt($credentials)) { //login
                return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');
            }
        } catch (JWTException $e) {
            return $this->faild(trans('auth.login faild'), 400, 'E00');
        }

        return $this->teacher_response($request, $token);
    }

    public static function teacher_response($request, $token){
        //get teacher data
        if (! $teacher = auth('teacher')->user())
            return response::faild(trans('auth.teacher not found'), 404, 'E04');

        //update token
        $teacher->token_firebase = $request->get('token_firebase');
        $teacher->save();

        //check if user blocked
        if($teacher['status'] == 0)
            return response::faild(trans('auth.you are blocked'), 402, 'E02');
        
        // check if teatcher not active
        if($teacher['verified'] == 0){
            (new verification)->sendCode($request);

            return response()->json([
                'successful'=> false,
                'step'      => 'verify',
                'token'     => $token,
                'teacher'   => new teacherResource($teacher),
            ], 200);
        }

        // check if setup_profile
        if(count($teacher->Teacher_years) == 0){
            return response()->json([
                'successful'=> false,
                'step'      => 'setup_profile',
                'teacher'   => new teacherResource($teacher),
                'token'     => $token,
            ], 200);
        }
        
        return response()->json([
            'successful'=> true,
            'step'      => true,
            'message'   => 'success',
            'teacher'   => new teacherResource($teacher),
            'token'     => $token,
        ], 200);
    }

    public function logout(){
        //get user teacher
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }
        
        //remove token
        $teacher->token_firebase = null;
        $teacher->save();

        //logout
        FacadesAuth::guard('teacher')->logout();

        return response::success(trans('auth.logout success'), 200);
    }

    public function register(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'username'         => 'required|string|min:3|max:255',
            'dialing_code'     => 'required|string',
            'phone'            => 'required|string|unique:teachers,phone',
            'password'         => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
            'country_id'       => 'required|exists:countries,id',
            'curriculum_id'    => 'required|exists:curriculums,id',
            'subject_id'       => 'required|exists:main_subjects,id', //main subject
            'gender'           => ['required',Rule::in(0,1)],//0->male  1->female
            'token_firebase'   => 'nullable|string',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        //create teacher
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

        //create token
        $token = JWTAuth::fromUser($teacher);

        //send verification code
        $code = $this->verification->createCode($request->get('phone'));

        // $response =  $this->send_message($teacher->dialing_code, $teacher->phone , 'your code is ' . $code);

        //response
        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.register success'),
            'teacher'   => new teacherResource($teacher),
            'token'     => $token,
        ], 200);
    }

    public function get_dialing_code(Request $request){
        // //validation
        $validator = Validator::make($request->all(), [
            'phone'             => 'required|string|exists:teachers,phone',
        ]);

        if($validator->fails()){
            return $this->faild($validator->errors()->first(), 403, 'E03');
        }

        $teacher = Teacher::where('phone', $request->phone)->first();

        return response::success(trans('auth.success'), 200, 'dialing_code', $teacher->dialing_code);
    }
}
