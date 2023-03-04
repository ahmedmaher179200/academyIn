<?php

namespace App\Http\Controllers\site\teacher;

use App\Http\Controllers\Controller;
use App\Models\Available_class;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AgoraController extends Controller
{
    public function whiteboard(Request $request){
        $validator = Validator::make($request->all(), [
            'schedule_id'       => 'required|exists:available_classes,id',
        ]);
        if($validator->fails())
            return $this::faild($validator->errors()->first(), 403, 'E03');

        $available_class = Available_class::find($request->get('schedule_id'));
        if($available_class->whiteboard_uuid != null){
            return response()->json([
                'successful'        => true,
                'message'           => trans('auth.success'),
                'token'              => $available_class->whiteboard_teacher_token,
                'uuid'             => $available_class->whiteboard_uuid,
            ], 200);
        }

        $response = Http::withHeaders([
            'region' => 'sg',
            'Content-Type'  => 'application/json',
            'token' => "NETLESSSDK_YWs9QjZsQTREM2RwUkI1enhueiZub25jZT0xNjUxNjgwNjI1NTczMDAmcm9sZT0wJnNpZz0yNzMyNzQ2OWI0ZTg3YjkyODJlMDIyNTg2OTk3ZWU1NmI1OTZkMmQxODYxNjFhZjc3ZjU1YTc0MmU3YzkzNDQ0",
        ])->post('https://api.netless.link/v5/rooms', [
            "isRecord"=>         false,
            "limit"=>         0
        ]);

        $response2 = Http::withHeaders([
            'region' => 'sg',
            'Content-Type'  => 'application/json',
            'token' => "NETLESSSDK_YWs9QjZsQTREM2RwUkI1enhueiZub25jZT0xNjUxNjgwNjI1NTczMDAmcm9sZT0wJnNpZz0yNzMyNzQ2OWI0ZTg3YjkyODJlMDIyNTg2OTk3ZWU1NmI1OTZkMmQxODYxNjFhZjc3ZjU1YTc0MmU3YzkzNDQ0",
        ])->post('https://api.netless.link/v5/tokens/rooms/' . $response["uuid"], [
            "ak"=>         "B6lA4D3dpRB5zxnz",
            "lifespan"=>         0,
            "role"=> "writer"
        ]);

        $token = json_decode($response2, true);

        $available_class->whiteboard_uuid = $response["uuid"];
        $available_class->whiteboard_teacher_token = $token;
        $available_class->save();

        return response()->json([
            'successful'        => true,
            'message'           => trans('auth.success'),
            'token'             => $token,
            'uuid'              => $response["uuid"],
        ], 200);
    }
}
