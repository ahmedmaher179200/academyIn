<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class studentNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $student_id;
    public $student_notification;
    public function __construct($student_id, $student_notification)
    {
        $this->student_id = $student_id;
        $this->student_notification = $student_notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['studentNotification_' . $this->student_id]; //channel 
    }
  
    public function broadcastAs()
    {
        return 'studentNotification'; //event
    }
  
    public function broadcastWith()
    {
        return [
            'notification' => $this->student_notification,
        ];
    }
}
