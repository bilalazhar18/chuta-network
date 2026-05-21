<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class callingFriend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sender, $receiver, $meeting_url, $sender_name;
 
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sender ,$receiver, $meeting_url, $sender_name)
    {
        Log::info("in callingFeature");
        Log::info($sender);
        Log::info($receiver);
        Log::info($meeting_url);
        Log::info("TYPE");
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->meeting_url = $meeting_url;
        $this->sender_name = $sender_name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('my-channel');
    }
    public function broadcastAs(){
        return 'video-call';
    }
}
