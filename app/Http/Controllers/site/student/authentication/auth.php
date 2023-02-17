<?php

namespace App\Http\Controllers\site\student\authentication;

use Aloha\Twilio\Twilio;
use App\Http\Controllers\Controller;
use App\Http\Resources\studentResource;
use App\Models\Student;
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
        // //validation
        $validator = Validator::make($request->all(), [
            'phone'             => 'required|string',
            'password'          => 'required|string',
            'token_firebase'    => 'nullable|string',
        ]);

        if($validator->fails()){
            return $this->faild($validator->errors()->first(), 403, 'E03');
        }

        //data
        $credentials = ['phone' => $request->phone, 'password' => $request->password];
        
        try {
            if (! $token = auth('student')->attempt($credentials)) { //login
                return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');
            }
        } catch (JWTException $e) {
            return $this->faild(trans('auth.login faild'), 400, 'E00');
        }

        return $this->student_response($request, $token);
    }

    public static function student_response($request, $token){
        //get student data
        if (! $student = auth('student')->user())
        return response::faild(trans('auth.student not found'), 404, 'E04');

        //update firbase token
        $student->token_firebase = $request->get('token_firebase');
        $student->save();

        //check if user blocked
        if($student['status'] == 0)
            return response::faild(trans('auth.you are blocked'), 402, 'E02');
        
        // check if student not active
        if($student['verified'] == 0){
            (new verification)->sendCode($request);

            return response()->json([
                'successful'=> false,
                'step'      => 'verify',
                'student'   => new studentResource($student),
                'token'     => $token,
            ], 200);
        }

        // check if student not active
        if($student['year_id'] == null){
            return response()->json([
                'successful'=> false,
                'step'      => 'setup_profile',
                'student'   => new studentResource($student),
                'token'     => $token,
            ], 200);
        }

        return response()->json([
            'successful'=> true,
            'step'      => true,
            'message'   => 'success',
            'student'   => new studentResource($student),
            'token'     => $token,
        ], 200);
    }

    public function logout(){
        //get user teacher
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }
        
        //remove token
        $student->token_firebase = null;
        $student->save();

        //logout
        FacadesAuth::guard('student')->logout();

        return response::success(trans('auth.logout success'), 200);
    }

    public function register(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'username'         => 'required|string|min:3|max:255',
            'dialing_code'     => 'required|string',
            'phone'            => 'required|unique:students|string',
            'password'         => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
            'country_id'       => 'required|exists:countries,id',
            'curriculum_id'    => 'required|exists:curriculums,id',
            'gender'           => ['required',Rule::in(0,1)],//0->male  1->female
            'token_firebase'   => 'nullable|string',
        ]);

        if($validator->fails()){
            return response::faild($validator->errors()->first(), 403, 'E03');
        }

        //create student
        $student = Student::create([
            'username'          => $request->get('username'),
            'dialing_code'      => $request->get('dialing_code'),
            'phone'             => $request->get('phone'),
            'password'          => Hash::make($request->get('password')),
            'country_id'        => $request->get('country_id'),
            'curriculum_id'     => $request->get('curriculum_id'),
            'gender'            => $request->get('gender'),
            'token_firebase'    => $request->get('token_firebase'),
        ]);

        //create token
        $token = JWTAuth::fromUser($student);
        
        //send verification code
        $code = $this->verification->createCode($request->get('phone'));

        // $response =  $this->send_message($student->dialing_code , $student->phone , 'your code is ' . $code);

        //response
        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.register success'),
            'token'     => $token,
        ], 200);
    }

    public function get_dialing_code(Request $request){
        // //validation
        $validator = Validator::make($request->all(), [
            'phone'             => 'required|string|exists:students,phone',
        ]);

        if($validator->fails()){
            return $this->faild($validator->errors()->first(), 403, 'E03');
        }

        $student = Student::where('phone', $request->phone)->first();

        return response::success(trans('auth.success'), 200, 'dialing_code', $student->dialing_code);
    }
}
