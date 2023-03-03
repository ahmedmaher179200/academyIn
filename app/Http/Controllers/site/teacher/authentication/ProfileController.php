<?php

namespace App\Http\Controllers\site\teacher\authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\teacherResource;
use App\Models\Image;
use App\Models\Teacher;
use App\Models\Teacher_year;
use App\Services\TeacherService;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    use response;
    public $TeacherService;
    public function __construct(TeacherService $TeacherService)
    {
        $this->TeacherService         = $TeacherService;
    }
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'teacher_id'       => 'required|string|exists:teachers,id',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $teacher = Teacher::find($request->teacher_id);

        return $this->success(trans('auth.success'), 200, 'teacher', new teacherResource($teacher));
    }

    public function myProfile(){
        $teacher = auth('teacher')->user();

        return $this->success(trans('auth.success'),
                        200,
                        'teacher',
                        new teacherResource($teacher)
                    );
    }

    public function change_image(Request $request){
        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'image'       => 'required|mimes:jpeg,jpg,png,gif',
            ]);

            if($validator->fails())
                return $this::faild($validator->errors()->first(), 403);

            $teacher = auth('teacher')->user();

            $this->TeacherService->updateImage($teacher, $request->file('image'));

            DB::commit();
            return $this::success(trans('auth.update image success'), 200);
        } catch(\Exception $ex){
            return $this::faild(trans('auth.update image faild'), 200);
        }   
    }

    public function updateProfile(Request $request){
        $teacher = auth('teacher')->user();

        $validator = Validator::make($request->all(), [
            'username'          => 'nullable|string|max:250',
            'email'             => 'nullable|email|max:255|unique:teachers,email,'. $teacher->id,
            'dialing_code'      => 'nullable|string|max:10',
            'phone'             => 'nullable|string|max:20|unique:teachers,phone,'. $teacher->id,
            'country_id'        => 'nullable|integer|exists:countries,id',
            'curriculum_id'     => 'nullable|integer|exists:curriculums,id',
            'gender'            => ['nullable',Rule::in(0,1,2)],    //0->male  1->female
            'subject_id'        => 'nullable|exists:main_subjects,id', //main subject
            'birth'             => 'nullable|date',
            'about'             => 'nullable|string|max:1000',
            'image'             => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $this->TeacherService->update($teacher, $request);

        return $this->success(trans('auth.update profile success'), 200, 'teacher', new teacherResource($teacher));
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'oldPassword'       => 'required|string',
            'password'          => 'required|string|min:6',
            'confirm_password'  => 'required|string|same:password',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $teacher = auth('teacher')->user();      
        
        if(!Hash::check($request->oldPassword, $teacher->password))
            return $this::faild(trans('auth.old password is wrong'), 400);

        $teacher->save();
        return $this::success(trans('auth.change password success'), 200);
    }

    public function setup_profile(Request $request){
        $validator = Validator::make($request->all(), [
            'years_id'   => 'required',
            'years_id.*' => 'required|exists:years,id',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $teacher = auth('teacher')->user();

        $row = DB::table('teacher_year')->where([
            'teacher_id'    => $teacher->id,
        ])->delete();

        foreach($request->get('years_id') as $year_id){
            Teacher_year::create([
                'teacher_id' => $teacher->id,
                'year_id'    => $year_id,
            ]);
        }

        $token = JWTAuth::fromUser($teacher);

        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.success'),
            'teacher'   => new teacherResource($teacher),
            'token'     => $token,
        ], 200);
    }

}
