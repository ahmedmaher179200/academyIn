<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Models\Available_class;
use App\Services\AgoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AgoraController extends Controller
{
    public $AgoraService;
    public function __construct(AgoraService $AgoraService)
    {
        $this->AgoraService         = $AgoraService;
    }

    public function generate_agora_rtm_token(Request $request){
        $validator = Validator::make($request->all(), [
            'schedule_id'       => 'required|exists:available_classes,id',
        ]);
        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403);

        $student = auth('student')->user();
        $user_id =  'student_' . $student->id;

        $student_class = DB::table('student_class')   
                            ->where('available_class_id', $request->get('schedule_id'))
                            ->where('student_id', $student->id);

        if($student_class->first() == null)
            return $this->faild(trans('auth.you do not booking'), 200);

        if($student_class->first()->agora_rtm_token != null){
            return response()->json([
                'successful'                => true,
                'message'                   => trans('auth.success'),
                'agora_rtm_token'           => $student_class->first()->agora_rtm_token,
                'user_id'                   => $user_id,
            ], 200);
        }

        $agora_rtm_token = $this->AgoraService->generateToken($user_id)['rtm_token'];
        $student_class->update([
            'agora_rtm_token'   => $agora_rtm_token,
        ]);

        return response()->json([
            'successful'                => true,
            'message'                   => trans('auth.success'),
            'agora_rtm_token'           => $agora_rtm_token,
            'user_id'                   => $user_id,
        ], 200);
    }

    public function whiteboard(Request $request){
        $validator = Validator::make($request->all(), [
            'schedule_id'       => 'required|exists:available_classes,id',
        ]);
        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403, 'E03');

        $available_class = Available_class::find($request->get('schedule_id'));
        if($available_class->whiteboard_uuid == null){
            return response()->json([
                'successful'        => false,
                'message'           => trans('site.teacher don\'t creat board'),
            ], 400);
        }

        if($available_class->whiteboard_student_token != null){
            return response()->json([
                'successful'        => true,
                'message'           => trans('auth.success'),
                'token'             => $available_class->whiteboard_student_token,
                'uuid'              => $available_class->whiteboard_uuid,
            ], 200);
        }

        $response2 = Http::withHeaders([
            'region' => 'sg',
            'Content-Type'  => 'application/json',
            'token' => "NETLESSSDK_YWs9QjZsQTREM2RwUkI1enhueiZub25jZT0xNjUxNjgwNjI1NTczMDAmcm9sZT0wJnNpZz0yNzMyNzQ2OWI0ZTg3YjkyODJlMDIyNTg2OTk3ZWU1NmI1OTZkMmQxODYxNjFhZjc3ZjU1YTc0MmU3YzkzNDQ0",
        ])->post('https://api.netless.link/v5/tokens/rooms/' . $available_class->whiteboard_uuid, [
            "ak"=>         "B6lA4D3dpRB5zxnz",
            "lifespan"=>         0,
            "role"=> "reader"
        ]);

        $token = json_decode($response2, true);

        $available_class->whiteboard_student_token = $token;
        $available_class->save();

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'token'             => $token,
            'uuid'              => $available_class->whiteboard_uuid,
        ], 200);
    }
}
