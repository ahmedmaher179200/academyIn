<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'teacher_id'     => 'required|integer|exists:teachers,id',
            'rating'             => 'required|integer|min:0|max:5',
        ]);

        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        Rating::create([
            'teacher_id'    => $request->teacher_id,
            'stars'         => $request->rating,
        ]);

        return $this->success(trans('auth.success'), 200);
    }
}
