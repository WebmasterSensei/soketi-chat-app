<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Chat;
use App\Models\User;

class UsersChats implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $chat;
    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Chat $chat)
    {
        //
        $this->user = $user;
        $this->chat = $chat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
             new PresenceChannel('chats.'. $this->chat->recipient_id),
        ];
    }
    public function broadcastAs()
    {
        return 'my-chats';
    }

    public function broadcastWith(){
        return [
            'user' =>$this->user->name,
            'user_id' =>$this->chat->recipient_id,
            'message_id' =>$this->chat->id,
            'sender_id' =>$this->chat->sender_id,
            'recipient_id' => $this->chat->recipient_id,
            'message' => $this->chat->message,
     ];
  }
}
