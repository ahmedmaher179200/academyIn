<?php

namespace App\Http\Controllers\site\student\authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\students\CreateRequest;
use App\Http\Requests\Api\students\LoginRequest;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public $StudentService;
    public function __construct(StudentService $StudentService)
    {
        $this->StudentService = $StudentService;
    }

    public function login(LoginRequest $request){        
        $credentials = ['phone' => $request->phone, 'password' => $request->password];
        
        if (! $token = auth('student')->attempt($credentials))
            return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');

        return $this->StudentService->student_response($request, $token);
    }

    public function logout(){
        $student = auth('student')->user();
        
        $student->token_firebase = null;
        $student->save();

        FacadesAuth::guard('student')->logout();

        return $this->success(trans('auth.success'), 200);
    }

    public function leave(){
        $student = auth('student')->user();

        $student->update(['online' => 0]);

        return $this->success(trans('auth.success'), 200);
    }

    public function register(CreateRequest $request){
        $student = $this->StudentService->Create($request);

        $token = JWTAuth::fromUser($student);

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

        return $this->success(trans('auth.success'), 200, 'dialing_code', $student->dialing_code);
    }
}
