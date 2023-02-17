<?php

namespace App\Http\Controllers\site\teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\notificationResource;
use App\Models\Teacher;
use App\Models\Teacher_notification;
use Illuminate\Http\Request;

class notificaitons extends Controller
{
    public function index(){
        //get teacher
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        //get teacher notifications
        $new_notifications = Teacher_notification::where('teacher_id', $teacher->id)
                                ->where('seen', 0)
                                ->count();

        $notifications = Teacher_notification::where('teacher_id', $teacher->id)
                                ->orderBy('id', 'desc')
                                ->limit(15)
                                ->get();

        Teacher_notification::where('teacher_id', $teacher->id)
                                ->where('seen', 0)
                                ->update(['seen'=> 1]);

        return response()->json([
            'successful'            => true,
            'message'               => trans('auth.success'),
            'new_notifications'     => $new_notifications,
            'notifications'         => notificationResource::collection($notifications),
        ], 200);
    }


    public function index_pagination(){
        //get teacher
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        //get teacher notifications
        $new_notifications = Teacher_notification::where('teacher_id', $teacher->id)
                                ->where('seen', 0)
                                ->count();

        $notifications = Teacher_notification::where('teacher_id', $teacher->id)
                                ->orderBy('id', 'desc');

        Teacher_notification::where('teacher_id', $teacher->id)
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
        //get teacher
        if (! $teacher = auth('teacher')->user()) {
            return $this::faild(trans('auth.teacher not found'), 404, 'E04');
        }

        //get teacher notifications
        $notifications_count = Teacher_notification::where('teacher_id', $teacher->id)
                                ->where('seen', 0)
                                ->count();

        return response()->json([
            'successful'            => true,
            'message'               => trans('auth.success'),
            'notifications_count'   => $notifications_count,
        ], 200);
    }
}
