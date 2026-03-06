<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidature;

class CandidatureAcceptéeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Candidature $candidature
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $candidature = $this->candidature->loadMissing('offre');
        $offreTitre = $candidature->offre->titre ?? 'une offre de stage';

        return (new MailMessage)
            ->subject('Bonne nouvelle : votre candidature a été acceptée – Plateforme Stage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous avons le plaisir de vous informer que votre candidature pour l\'offre **' . $offreTitre . '** a été **acceptée**.')
            ->line('L\'entreprise va probablement vous contacter pour la suite du processus.')
            ->action('Voir mes candidatures', url('/offres'))
            ->line('Félicitations et bonne continuation !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'candidature_id' => $this->candidature->id,
            'statut' => 'accepte',
        ];
    }
}
