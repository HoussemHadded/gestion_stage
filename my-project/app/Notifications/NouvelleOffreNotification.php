<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Offre;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class NouvelleOffreNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public function __construct(
        public Offre $offre
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        $offre = $this->offre->loadMissing('entreprise');
        return [
            'type_label' => 'nouvelle_offre',
            'offre_id' => $offre->id,
            'offre_titre' => $offre->titre,
            'entreprise_name' => $offre->entreprise->company_name ?? $offre->entreprise->name ?? 'Une entreprise',
            'message' => 'Nouvelle offre de stage disponible : ' . $offre->titre,
            'url' => route('student.offres.show', $offre->id)
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
