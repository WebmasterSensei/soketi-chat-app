<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Notification;
use App\Models\User;

class PresenceEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
   public $user;
    public $notification;
    /**
     * Create a new event instance.
     */
   public function __construct(User $user, Notification $notification)
    {
        $this->user = $user;
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new PresenceChannel('presence');
    }
   public function broadcastAs()
    {
        return 'my-event';
    }
 public function broadcastWith()
    {
      $data = [
        'user' => $this->user->name,
        'message_id' => $this->notification->id,
        'message' => $this->notification->message,
    ];

         \Log::info('PresenceEvent broadcasted with data:', $data);

          return $data;
    }
}
