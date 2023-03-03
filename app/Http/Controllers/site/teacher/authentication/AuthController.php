<?php

namespace App\Http\Controllers\site\teacher\authentication;

use App\Http\Controllers\Controller;
use App\Http\Controllers\site\teacher\authentication\verification;
use App\Http\Resources\teacherResource;
use App\Models\Teacher;
use App\Services\TeacherService;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use response;
    public $verification;
    public $TeacherService;
    public function __construct(verification $verification, TeacherService $TeacherService)
    {
        $this->verification         = $verification;
        $this->TeacherService         = $TeacherService;
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'phone'             => 'required',
            'password'          => 'required|string',
            'token_firebase'    => 'nullable|string',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors()->first(), 403, 'E03');

        $credentials = ['phone' => $request->phone, 'password' => $request->password];

        if (! $token = auth('teacher')->attempt($credentials))
            return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');

        return $this->TeacherService->teacher_response($request, $token);
    }

    public function logout(){
        $teacher = auth('teacher')->user();
        
        $teacher->token_firebase = null;
        $teacher->save();

        FacadesAuth::guard('teacher')->logout();

        return response::success(trans('auth.logout success'), 200);
    }

    public function register(Request $request){
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

        if($validator->fails())
            return response::faild($validator->errors()->first(), 403, 'E03');

        $teacher = $this->TeacherService->create($request);

        $token = JWTAuth::fromUser($teacher);

        $this->verification->createCode($request->get('phone'));

        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.register success'),
            'teacher'   => new teacherResource($teacher),
            'token'     => $token,
        ], 200);
    }

    public function get_dialing_code(Request $request){
        $validator = Validator::make($request->all(), [
            'phone'             => 'required|string|exists:teachers,phone',
        ]);

        if($validator->fails())
            return $this->faild($validator->errors()->first(), 403, 'E03');

        $teacher = Teacher::where('phone', $request->phone)->first();

        return response::success(trans('auth.success'),
                                    200,
                                    'dialing_code',
                                    $teacher->dialing_code);
    }
}
