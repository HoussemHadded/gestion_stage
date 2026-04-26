<?php

namespace App\Events;

use App\Models\Candidature;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchScoreUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $candidature;

    /**
     * Create a new event instance.
     */
    public function __construct(Candidature $candidature)
    {
        $this->candidature = $candidature;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('candidature.' . $this->candidature->id),
            new PrivateChannel('entreprise.' . $this->candidature->offre->entreprise_id),
            new PrivateChannel('student.' . $this->candidature->student_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'candidature_id' => $this->candidature->id,
            'match_percentage' => $this->candidature->match_percentage,
            'student_id' => $this->candidature->student_id,
            'offre_id' => $this->candidature->offre_id,
        ];
    }
}
