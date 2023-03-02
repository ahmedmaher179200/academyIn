<?php

namespace App\Http\Controllers\site\student;

use App\Http\Controllers\Controller;
use App\Http\Resources\notificationResource;
use App\Models\student_notification;
use Illuminate\Http\Request;

class NotificaitonController extends Controller
{
    public function index(Request $request){
        $student = auth('student')->user();

        $request->request->add(['student' => $student]);

        //get student notifications
        $new_notifications = student_notification::where('student_id', $student->id)
                                ->where('seen', 0)
                                ->count();

        $notifications = student_notification::where('student_id', $student->id)
                                ->orderBy('id', 'desc');

        student_notification::where('student_id', $student->id)
                                ->where('seen', 0)
                                ->update(['seen'=> 1]);

        return response()->json([
            'successful'            => true,
            'message'               => trans('auth.success'),
            'new_notifications'     => $new_notifications,
            'notifications_count'   => $notifications->count(),
            'notifications'         => notificationResource::collection($notifications->paginate(5))->response()->getData(true),
        ], 200);
    }

    public function notification_count(){
        $student = auth('student')->user();

        $notifications_count = student_notification::where('student_id', $student->id)
                                ->where('seen', 0)
                                ->count();

        return response()->json([
            'successful'            => true,
            'message'               => trans('auth.success'),
            'notifications_count'   => $notifications_count,
        ], 200);
    }
}
