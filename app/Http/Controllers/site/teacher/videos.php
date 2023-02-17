<?php

namespace App\Http\Controllers\site\teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\videoResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class videos extends Controller
{
    public function videos(Request $request){
        //get teacher
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        $videos = Video::where('teacher_id', $teacher->id)
                            ->active()
                            ->get();

        return $this::success(
            trans('auth.success'),
            200,
            'videos',
            videoResource::collection($videos)
        );
    }

    public function add_video(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string',
            'subject_id'    => 'required|exists:subjects,id',
            'cost'          => 'required|integer',
            'src'           => 'required|string',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403, 'E03');
        }

        //get teacher
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        Video::create([
            'teacher_id' => $teacher->id,
            'subject_id' => $request->get('subject_id'),
            'title'      => $request->get('title'),
            'cost'       => $request->get('cost'),
            'src'        => $request->get('src'),
        ]);

        return $this->success(trans('auth.success'), 200);
    }

    public function cancel_video(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'video_id'       => 'required|exists:videos,id',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403, 'E03');
        }

        //get teacher
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        $video = Video::where('teacher_id', $teacher->id)
                        ->find($request->get('video_id'));
        //if availble_class is empty
        if($video == null)
            return $this->faild(trans('site.video not found'), 400, 'E04');
        
        $video->status = -1;
        $video->save();

        return $this->success(trans('auth.success'), 200);
    }
}
