<?php

namespace App\Http\Controllers\site\student\authentication;

use Aloha\Twilio\Twilio;
use App\Http\Controllers\Controller;
use App\Http\Controllers\site\student\authentication\auth as x;
use App\Http\Resources\studentResource;
use App\Traits\response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class verification extends Controller
{
    use response;
    ////////sent email /////////////
    public function sendCode(Request $request){
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }
        if (!$this->validatePhone($student->phone)) {
            return $this::faild(trans('auth.phone not found'), 404, 'E04');
        }
        
        // code is important in send mail 
        $code = $this->createCode($student->phone);

        // $response =  $this->send_message($student->dialing_code , $student->phone , 'your code is ' . $code);

        return $this::success(trans('auth.send verify code success, please check your phone.'), 200);
    }

    public function createCode($phone){

        $oldCode = DB::table('student_verified')->where('phone', $phone)->first();

        if ($oldCode)
            return $oldCode->code;

        // $code = rand(1000,9999);
        $code = "1234";
        $this->saveCode($code, $phone);
        return $code;
    }

    public function saveCode($code, $phone){
        DB::table('student_verified')->insert([
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
        if($this->updatePasswordRow($request)->count() > 0){
            return $this::success(trans('auth.success'), 200);
        } else {
            return $this::faild(trans('auth.your code is wrong.'), 404, 'E04');
        }
    }

    //////////////////////// verification ////////////

    public function verificationProcess(Request $request){
        $validator = Validator::make($request->all(), [
            'code'              => 'required',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403, 'E03');
        }

        return $this->verificationRow($request)->count() > 0 ? $this->verification($request) : $this::faild(trans('auth.your code is wrong.'), 404, 'E04');
    }
  
    // Verify if code is valid
    private function verificationRow($request){
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }
        return DB::table('student_verified')->where([
            'phone'  => $student->phone,
            'code'      => $request->code
        ]);
    }

    private function verification($request) {
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }
        // update students
        DB::table('students')
        ->where('phone', $student->phone)
        ->update(['verified' => 1]);

        $this->verificationRow($request)->delete();

        try {
            if (! $token = JWTAuth::fromUser($student)) {
                return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');
            }
        } catch (JWTException $e) {
            return $this->faild(trans('auth.login faild'), 400, 'E00');
        }

        //update token
        $student->token_firebase = $request->get('token_firebase');
        $student->save();

        return response()->json([
            'successful'=> true,
            'message'   => 'success',
            'student'   => new studentResource($student),
            'token'     => $token,
        ], 200);
    }

    public function new_verification(){
        if (! $student = auth('student')->user())
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        
        $student->verified = 1;
        $student->save();

        return response::success(trans('auth.success'), 200);
    }
}
