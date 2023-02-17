<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class teacherNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $teacher_id;
    public $teacher_notification;
    public function __construct($teacher_id, $teacher_notification)
    {
        $this->teacher_id = $teacher_id;
        $this->teacher_notification = $teacher_notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
  {
      return ['teacherNotification_'. $this->teacher_id]; //channel 
  }

  public function broadcastAs()
  {
      return 'teacherNotification'; //event
  }

  public function broadcastWith()
  {
      return [
          'notification' => $this->teacher_notification,
      ];
  }
}
