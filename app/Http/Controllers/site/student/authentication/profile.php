<?php

namespace App\Http\Controllers\site\student\authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\studentResource;
use App\Models\Image;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class profile extends Controller
{
    public function index(Request $request){
        // validate registeration request
        $validator = Validator::make($request->all(), [
            'student_id'       => 'required|string',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        //get student
        $student = Student::find($request->get('student_id'));

        //if student not found
        if($student == null)
            return $this::faild(trans('auth.student not found'), 404, 'E04');

        return $this->success(
                trans('auth.success'),
                200,
                'student',
                new studentResource($student)
            );
    }

    public function myProfile(){
        //get student
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        return $this->success(
            trans('auth.success'),
            200,
            'student',
            new studentResource($student)
        );
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

        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }        
        
        //update student pass
        if(Hash::check($request->oldPassword, $student->password)){
            $student->password  = Hash::make($request->get('password'));
        } else {
            return $this::faild(trans('auth.old password is wrong'), 400);
        }

        if($student->save()){
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

            //get student
            if (! $student = auth('student')->user()) {
                return $this::faild(trans('auth.auth not found'), 404, 'E04');
            }

            //update image
            $path = $this->upload_image($request->file('image'),'uploads/students', 300, 300);

            if($student->Image == null){
                //if user don't have image 
                Image::create([
                    'imageable_id'   => $student->id,
                    'imageable_type' => 'App\Models\Student',
                    'src'            => $path,
                ]);

            } else {
                //if student have image
                $oldImage = $student->Image->src;

                if(file_exists(base_path('public/uploads/students/') . $oldImage)){
                    unlink(base_path('public/uploads/students/') . $oldImage);
                }

                $student->Image->src = $path;
                $student->Image->save();
            }

            DB::commit();
            return $this::success(trans('auth.update image success'), 200);
        } catch(\Exception $ex){
            return $this::faild(trans('auth.update image faild'), 200);
        }   
    }

    public function updateProfile(Request $request){
        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        // validate
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

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        //selet student

        $input = $request->only(
            'username','email','dialing_code', 'phone','country_id','curriculum_id','year_id',
            'gender', 'birth'
        );

        if($request->has('image') != null){
            //update image
            $path = $this->upload_image($request->file('image'),'uploads/students', 300, 300);

            if($student->Image == null){
                //if user don't have image 
                Image::create([
                    'imageable_id'   => $student->id,
                    'imageable_type' => 'App\Models\Student',
                    'src'            => $path,
                ]);

            } else {
                //if student have image
                $oldImage = $student->Image->src;

                if(file_exists(base_path('public/uploads/students/') . $oldImage)){
                    unlink(base_path('public/uploads/students/') . $oldImage);
                }

                $student->Image->src = $path;
                $student->Image->save();
            }
        }

        //update student
        if($student->update($input)){
            return $this->success(trans('auth.update profile success'), 200, 'student', new studentResource($student));
        } else {
            return $this::faild(trans('auth.update profile falid'), 400);
        }
    }

    public function updateYear(Request $request){
        //get student or vender
        if (! $student = auth('student')->user()) {
            return $this::faild(trans('auth.student not found'), 404, 'E04');
        }

        // validate
        $validator = Validator::make($request->all(), [
            'year_id'           => 'required|exists:years,id',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403);
        }

        $student->year_id   = $request->get('year_id');
        $student->save();

        try {
            if (! $token = JWTAuth::fromUser($student)) { //login
                return $this->faild(trans('auth.passwored or phone is wrong'), 404, 'E04');
            }
        } catch (JWTException $e) {
            return $this->faild(trans('auth.login faild'), 400, 'E00');
        }

        return response()->json([
            "successful"=> true,
            'message'   => trans('auth.success'),
            'student'   => new studentResource($student),
            'token'     => $token,
        ], 200);
    }
}
