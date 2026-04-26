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

class CandidatureSubmitted implements ShouldBroadcast
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
        // Broadcast to the company that owns the offer
        return [
            new PrivateChannel('entreprise.' . $this->candidature->offre->entreprise_id),
        ];
    }
    
    public function broadcastWith(): array
    {
        return [
            'candidature_id' => $this->candidature->id,
            'student_name' => $this->candidature->student->name,
            'offre_titre' => $this->candidature->offre->titre,
        ];
    }
}
