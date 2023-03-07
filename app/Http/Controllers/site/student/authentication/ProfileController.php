<?php

namespace App\Http\Controllers\site\student\authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\studentResource;
use App\Models\Image;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    public $StudentService;
    public function __construct(StudentService $StudentService)
    {
        $this->StudentService = $StudentService;
    }

    public function myProfile(){
        $student = auth('student')->user();

        return $this->success(
                            trans('auth.success'),
                            200,
                            'student',
                            new studentResource($student)
                        );
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'oldPassword'       => 'required|string',
            'password'          => 'required|string|min:6',
            'confirm_password'  => 'required|string|same:password',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $student = auth('student')->user();
        
        if(!Hash::check($request->oldPassword, $student->password))
            return $this::faild(trans('auth.old password is wrong'), 400);
            
        $student->password  = Hash::make($request->get('password'));

        return $this::success(trans('auth.change password success'), 200);
    }

    public function changeImage(Request $request){
        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'image'       => 'required|mimes:jpeg,jpg,png,gif',
            ]);

            if($validator->fails())
                return $this::faild($validator->errors()->first(), 403);

            $student = auth('student')->user();

            $this->StudentService->updateImage($student, $request->file('image'));

            DB::commit();
            return $this::success(trans('auth.success'), 200);
        } catch(\Exception $ex){
            return $this::faild(trans('auth.faild'), 200);
        }   
    }

    public function updateProfile(Request $request){
        $student = auth('student')->user();

        $validator = Validator::make($request->all(), [
            'username'          => 'nullable|string|max:250',
            'email'             => 'nullable|email|max:255|unique:students,email,'. $student->id,
            'dialing_code'      => 'nullable|string|max:10',
            'phone'             => 'nullable|string|max:20|unique:students,phone,'. $student->id,
            'country_id'        => 'nullable|integer|exists:countries,id',
            'curriculum_id'     => 'nullable|integer|exists:curriculums,id',
            'year_id'           => 'nullable|integer|exists:years,id',
            'gender'            => ['nullable',Rule::in(0,1,2)],    //0->male  1->female
            'birth'             => 'nullable|date',
            'image'             => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $input = $request->only(
            'username','email','dialing_code', 'phone','country_id','curriculum_id','year_id',
            'gender', 'birth'
        );

        if($request->has('image'))
            $this->StudentService->updateImage($student, $request->file('image'));

        $student->update($input);

        return $this->success(trans('auth.success'),
                                200,
                                'student',
                                new studentResource($student)
                            );
    }

    public function updateYear(Request $request){
        $student = auth('student')->user();

        $validator = Validator::make($request->all(), [
            'year_id'           => 'required|exists:years,id',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $student->year_id   = $request->get('year_id');
        $student->save();

        $token = JWTAuth::fromUser($student);

        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.success'),
            'student'   => new studentResource($student),
            'token'     => $token,
        ], 200);
    }
}
