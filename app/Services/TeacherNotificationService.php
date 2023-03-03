<?php

namespace App\Services;

use App\Events\teacherNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\notificationResource;
use App\Models\student_notification;
use App\Models\Teacher_notification;
use App\Traits\response;
use Illuminate\Support\Facades\Hash;

class TeacherNotificationService extends Controller
{
    use response;
    public $firbaseNotifications;
    public function __construct(firbaseNotifications $firbaseNotifications)
    {
        $this->firbaseNotifications = $firbaseNotifications;
    }

    public function booking_notigication($student, $available_class,$pusher){
        $title = 'تم الحجز';
        $body = ' حصة ' . $student->username . ' حجذ';

        //create notification to teacher
        $teacher_notification = Teacher_notification::create([
            'title'             => $title,
            'content'           => $body,
            'teacher_id'        => $available_class->teacher_id,
            'student_id'        => $student->id,
            'available_class_id'=> $available_class->id,
            'type'              => 1,
        ]);

        //sent firbase notifications
        if($pusher == 1){
            config(['queue.default' => 'sync']);
            event(new teacherNotification($available_class->teacher_id,new notificationResource($teacher_notification)));
        } else {
            $this->firbaseNotifications->send_notification(
                    $title,
                    $body,
                    $available_class->Teacher->token_firebase,
                    new notificationResource($teacher_notification),
                );
        }
    }
}