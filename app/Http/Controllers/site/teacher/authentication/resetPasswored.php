<?php

namespace App\Http\Controllers\site\teacher\authentication;

use Aloha\Twilio\Twilio;
use App\Http\Controllers\Controller;
use App\Http\Controllers\site\teacher\authentication\verification;
use App\Http\Resources\teacherResource;
use App\Models\Teacher;
use App\Services\TeacherService;
use App\Traits\response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class resetPasswored extends Controller
{
    use response;
    public $verification;
    public $TeacherService;
    public function __construct(verification $verification, TeacherService $TeacherService)
    {
        $this->verification         = $verification;
        $this->TeacherService         = $TeacherService;
    }
    ////////sent code /////////////

    public function sendCode(Request $request){  // this is most important function to send mail and inside of that there are another function        
        // validate
        $validator = Validator::make($request->all(), [
            'phone'          => 'required',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }
        
        if (!$this->validatePhone($request->phone))
            return $this::faild(trans('auth.phone not found'), 404, 'E04');

        $teacher = Teacher::where('phone', $request->phone)->first();

        // code is important in send mail 
        $code = $this->createCode($request->phone);

        // $response =  $this->send_message($teacher->dialing_code, $request->phone , 'your code is ' . $code);

        return $this::success(trans('auth.send reset password code success, please check your phone.'), 200);
    }

    public function createCode($phone){
        $oldCode = DB::table('teacher_password_resets')->where('phone', $phone)->first();

        if ($oldCode)
            return $oldCode->code;

        // $code = rand(1000,9999);
        $code = "1234";
        $this->saveCode($code, $phone);
        return $code;
    }

    public function saveCode($code, $phone){
        DB::table('teacher_password_resets')->insert([
            'phone'      => $phone,
            'code'          => $code,
            'created_at'    => Carbon::now()
        ]);
    }

    public function validatePhone($phone){
        return !!DB::table('teachers')->where('phone', $phone)->first();
    }
    ///////////////check if code is valid ////////////

    public function checkCode(Request $request){
        $validator = Validator::make($request->all(), [
            'phone'             => 'required|exists:teachers,phone',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403, 'E03');
        }

        $teacher = Teacher::where('phone', $request->phone)->first();

        if (! $token = JWTAuth::fromUser($teacher)) {
            return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');
        }

        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.success'),
            'token'     => $token,
        ], 200);

    }

    //////////////////////// verification ////////////

    public function changePasswordProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'phone'                => 'required',
            'code'                 => 'required',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403, 'E03');
        }

        return $this->verificationRow($request)->count() > 0 ? $this->changePassword($request) : $this::faild(trans('auth.your code is wrong.'), 404, 'E04');
    }

    // Verify if code is valid
    public function passwordResetProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'code'              => 'required',
            'password'          => 'required|string|min:6',
            'confirmPassword'   => 'required|string|same:password',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403, 'E03');
        }

        return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this::faild(trans('auth.your code is wrong.'), 404, 'E04');
    }

    // Verify if code is valid
    private function updatePasswordRow($request){
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        return DB::table('teacher_password_resets')->where([
            'phone'     => $teacher->phone,
            'code'      => $request->code
        ]);
    }

    // Reset password
    private function resetPassword($request) {
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }
        // update password
        DB::table('teachers')
        ->where('phone', $teacher->phone)
        ->update(['password' => bcrypt($request->password)]);

        // remove verification data from db
        $this->updatePasswordRow($request)->delete();

        //get token
        try {
            if (! $token = JWTAuth::fromUser($teacher)) { //login
                return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');
            }
        } catch (JWTException $e) {
            return $this->faild(trans('auth.login faild'), 400, 'E00');
        }
        
        return $this->TeacherService->teacher_response($request, $token);
    } 

    public function new_resetPassword(Request $request) {
        if (! $teacher = auth('teacher')->user())
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');

        // update password
        DB::table('teachers')
        ->where('phone', $teacher->phone)
        ->update(['password' => bcrypt($request->password)]);

        // remove verification data from db
        $this->updatePasswordRow($request)->delete();

        //get token
        if (! $token = JWTAuth::fromUser($teacher)) { //login
            return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');
        }

        return $this->TeacherService->teacher_response($request, $token);
    } 
}
