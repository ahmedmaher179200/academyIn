<?php

namespace App\Http\Controllers\site\teacher\authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\teacherResource;
use App\Models\Image;
use App\Models\Teacher;
use App\Models\Teacher_year;
use App\Traits\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class profile extends Controller
{
    use response;
    public function index(Request $request){
        // validate registeration request
        $validator = Validator::make($request->all(), [
            'teacher_id'       => 'required|string',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        //get teacher
        $teacher = Teacher::find($request->get('teacher_id'));

        //if teacher not found
        if($teacher == null)
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');

        return $this->success(trans('auth.success'), 200, 'teacher', new teacherResource($teacher));
    }

    public function myProfile(){
        //get teacher
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        return $this->success(trans('auth.success'), 200, 'teacher', new teacherResource($teacher));
    }

    public function changePassword(Request $request){
        // validate registeration request
        $validator = Validator::make($request->all(), [
            'oldPassword'       => 'required|string',
            'password'          => 'required|string|min:6',
            'confirm_password'  => 'required|string|same:password',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        //get teacher or vender
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('user.teacher not found'), 404, 'E04');
        }        
        
        //update teacher pass
        if(Hash::check($request->oldPassword, $teacher->password)){
            $teacher->password  = Hash::make($request->get('password'));
        } else {
            return $this::faild(trans('auth.old password is wrong'), 400);
        }

        if($teacher->save()){
            return $this::success(trans('auth.change password success'), 200);
        } else {
            return $this::faild(trans('auth.update password falid'), 400);
        }
    }

    public function change_image(Request $request){
        try{
            DB::beginTransaction();
            // validate registeration request
            $validator = Validator::make($request->all(), [
                'image'       => 'required|mimes:jpeg,jpg,png,gif',
            ]);

            if($validator->fails()){
                return $this::faild($validator->errors()->first(), 403);
            }

            //get teacher
            if (! $teacher = auth('teacher')->user()) {
                return $this::faild(trans('auth.teacher not found'), 404, 'E04');
            }

            //update image
            $path = $this->upload_image($request->file('image'),'uploads/teachers', 300, 300);

            if($teacher->Image == null){
                //if user don't have image 
                Image::create([
                    'imageable_id'   => $teacher->id,
                    'imageable_type' => 'App\Models\Teacher',
                    'src'            => $path,
                ]);

            } else {
                //if teacher have image
                $oldImage = $teacher->Image->src;

                if(file_exists(base_path('public/uploads/teachers/') . $oldImage)){
                    unlink(base_path('public/uploads/teachers/') . $oldImage);
                }

                $teacher->Image->src = $path;
                $teacher->Image->save();
            }

            DB::commit();
            return $this::success(trans('auth.update image success'), 200);
        } catch(\Exception $ex){
            return $this::faild(trans('auth.update image faild'), 200);
        }   
    }

    public function setup_profile(Request $request){
        // validate registeration request
        $validator = Validator::make($request->all(), [
            'years_id'   => 'required',
            'years_id.*' => 'required|exists:years,id',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        //get teacher or vender
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        //delete old
        $row = DB::table('teacher_year')->where([
            'teacher_id'    => $teacher->id,
        ])->delete();

        foreach($request->get('years_id') as $year_id){
            // $subject = Subject::where('main_subject_id', $teacher->main_subject_id)
            //         ->whereHas('Term', function($qeury) use($year_id){
            //             $qeury->where('year_id', $year_id);
            //         })->first();

            Teacher_year::create([
                'teacher_id' => $teacher->id,
                'year_id'    => $year_id,
            ]);
        }

        try {
            if (! $token = JWTAuth::fromUser($teacher)) { //login
                return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');
            }
        } catch (JWTException $e) {
            return $this->faild(trans('auth.login faild'), 400, 'E00');
        }

        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.success'),
            'teacher'   => new teacherResource($teacher),
            'token'     => $token,
        ], 200);
    }

    public function updateProfile(Request $request){
        //get teacher or vender
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        // validate
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

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        //selet teatcher

        $input = $request->only(
            'username','email', 'about','dialing_code', 'phone','country_id','curriculum_id','year_id',
            'gender', 'birth'
        );

        if($request->get('subject_id') != null){
            $teacher->main_subject_id = $request->get('subject_id');
            $teacher->save();
        }

        if($request->has('image') != null){
            //update image
            $path = $this->upload_image($request->file('image'),'uploads/teachers', 300, 300);

            if($teacher->Image == null){
                //if user don't have image 
                Image::create([
                    'imageable_id'   => $teacher->id,
                    'imageable_type' => 'App\Models\Teacher',
                    'src'            => $path,
                ]);

            } else {
                //if teacher have image
                $oldImage = $teacher->Image->src;

                if(file_exists(base_path('public/uploads/teachers/') . $oldImage)){
                    unlink(base_path('public/uploads/teachers/') . $oldImage);
                }

                $teacher->Image->src = $path;
                $teacher->Image->save();
            }
        }

        //update teatcher
        if($teacher->update($input)){
            return $this->success(trans('auth.update profile success'), 200, 'teacher', new teacherResource($teacher));
        } else {
            return $this::faild(trans('auth.update profile falid'), 400);
        }
    }
}
