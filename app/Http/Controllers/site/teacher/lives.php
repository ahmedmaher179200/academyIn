<?php

namespace App\Http\Controllers\site\teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\liveResource;
use App\Models\Live;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class lives extends Controller
{
    public function lives(){
        //get teacher
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        $lives = Live::where('teacher_id', $teacher->id)
                            ->notCome()
                            ->get();

        return $this::success(
            trans('auth.success'),
            200,
            'lives',
            liveResource::collection($lives)
        );
    }

    public function add_live(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'subject_id'    => 'required|exists:subjects,id',
            'title'         => 'required|string',
            'cost'          => 'required|integer',
            'from'          => 'required|date_format:Y-m-d H:i:s',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403, 'E03');
        }

        //get teacher
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        //settings
        $settings = Settings::first();

        Live::create([
            'teacher_id'            => $teacher->id,
            'subject_id'            => $request->get('subject_id'),
            'title'                 => $request->get('title'),
            'cost'                  => $request->get('cost'),
            'from'                  => $request->get('from'),
            'company_percentage'    => $settings->live_company_percentage,
        ]);

        return $this->success(trans('auth.success'), 200);
    }

    public function cancel_live(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'live_id'       => 'required|exists:lives,id',
        ]);

        if($validator->fails()){
            return $this::faild($validator->errors()->first(), 403, 'E03');
        }

        //get teacher
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        $live = Live::where('teacher_id', $teacher->id)
                        ->find($request->get('live_id'));

        //if availble_class is empty
        if($live == null)
            return $this->faild(trans('site.live not found'), 400, 'E04');
        
        $live->status = -1;
        $live->save();

        return $this->success(trans('auth.success'), 200);
    }
}
