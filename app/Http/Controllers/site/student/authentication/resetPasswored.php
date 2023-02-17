<?php

namespace App\Http\Controllers\site\student\authentication;

use Aloha\Twilio\Twilio;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Traits\response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\site\student\authentication\verification;

class resetPasswored extends Controller
{
    use response;

    public $verification;
    public function __construct(verification $verification)
    {
        $this->verification         = $verification;
    }
    ////////sent code /////////////

    public function sendCode(Request $request){         
        // validate
        $validator = Validator::make($request->all(), [
            'phone'          => 'required',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }
        
        if (!$this->validatePhone($request->phone)) {
            return $this::faild(trans('auth.phone not found'), 404, 'E04');
        }

        //get student
        $student = Student::where('phone', $request->phone)->first();
        
        // code is important in send mail 
        $code = $this->createCode($request->phone);
        
        // $response =  $this->send_message($student->dialing_code, $request->phone , 'your code is ' . $code);

        return $this::success(trans('auth.send reset password code success, please check your phone.'), 200);
    }

    public function createCode($phone){  
        $oldCode = DB::table('student_password_resets')->where('phone', $phone)->first();

        //if user already has code
        if ($oldCode)
            return $oldCode->code;

        // $code = rand(1000,9999);
        $code = "1234";
        $this->saveCode($code, $phone);
        return $code;
    }

    public function saveCode($code, $phone){  
        DB::table('student_password_resets')->insert([
            'phone'      => $phone,
            'code'          => $code,
            'created_at'    => Carbon::now()
        ]);
    }

    public function validatePhone($phone){
        return !!DB::table('students')->where('phone', $phone)->first();
    }
    ///////////////check if code is valid ////////////
    public function checkCode(Request $request){
        $validator = Validator::make($request->all(), [
            'phone'             => 'required|exists:students,phone',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403, 'E03');
        }

        $student = Student::where('phone', $request->phone)->first();

        if (! $token = JWTAuth::fromUser($student)) {
            return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');
        }

        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.success'),
            'token'     => $token,
        ], 200);
    }

    //////////////////////// resetpasswored ////////////

    public function changePasswordProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'phone'                => 'required',
            'code'                 => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'successful'=> false,
                'step'      => 'validation',
            ], 200);
        }

        if($this->verificationRow($request)->count() > 0){
            return $this->changePassword($request);
        } else{
            return response()->json([
                'successful'=> false,
                'step'      => 'wrong_code',
                'message'   => trans('auth.your code is wrong.'),
            ], 200);
        } 
    }

    public function passwordResetProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'code'              => 'required',
            'password'          => 'required|string|min:6',
            'confirmPassword'   => 'required|string|same:password',
        ]);

        if($validator->fails()){
            return response()->json([
                'successful'=> false,
                'step'      => 'validation',
                'message'   => $validator->errors()->first(),
            ], 200);
        }

        if($this->updatePasswordRow($request)->count() > 0){
            return $this->resetPassword($request);
        } else {
            return response()->json([
                'successful'=> false,
                'step'      => 'wrong_code',
                'message'   => trans('auth.your code is wrong.'),
            ], 200);
        }
            
    }

    private function updatePasswordRow($request){
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        return DB::table('student_password_resets')->where([
            'phone'  => $student->phone,
            'code'      => $request->code
        ]);
    }

    // Reset password
    private function resetPassword($request) {
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }
        // update password
        DB::table('students')
        ->where('phone', $student->phone)
        ->update(['password' => bcrypt($request->password)]);

        // remove verification data from db
        $this->updatePasswordRow($request)->delete();

        //get token
        try {
            if (! $token = JWTAuth::fromUser($student)) { //login
                return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');
            }
        } catch (JWTException $e) {
            return $this->faild(trans('auth.login faild'), 400, 'E00');
        }

        return auth::student_response($request, $token);
    } 

    public function new_resetPassword(Request $request) {
        if (! $student = auth('student')->user())
            return $this::faild(trans('auth.student not found'), 404, 'E04');

        // update password
        DB::table('students')
        ->where('phone', $student->phone)
        ->update(['password' => bcrypt($request->password)]);

        // remove verification data from db
        $this->updatePasswordRow($request)->delete();

        //get token
        if (! $token = JWTAuth::fromUser($student)) { //login
            return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');
        }

        return auth::student_response($request, $token);
    } 
}
