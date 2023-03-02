<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Http\Resources\notificationResource;
use App\Models\student_notification;
use App\Traits\response;
use Illuminate\Support\Facades\Hash;

class StudentNotificationService extends Controller
{
    use response;
    public $firbaseNotifications;
    public $AnswerService;
    public function __construct(firbaseNotifications $firbaseNotifications, AnswerService $AnswerService)
    {
        $this->firbaseNotifications = $firbaseNotifications;
        $this->AnswerService = $AnswerService;
    }

    public function send($question_owner, $answer_id){
        $title = $question_owner->username .' add answer for your question';
        $body = $question_owner->username .' add answer for your question';

        $notification = student_notification::create([
            'student_id'        => $question_owner->id,
            'answer_id'         => $answer_id,
            'title'             => $title,
            'content'           => $body,
            'type'              => 4,
        ]);

        $this->firbaseNotifications->send_notification($title, 
            $body,
            $question_owner->token_firebase,
            new notificationResource($notification),    
        );
    }
}