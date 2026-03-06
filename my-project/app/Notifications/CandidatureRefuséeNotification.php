<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidature;

class CandidatureRefuséeNotification extends Notification implements ShouldQueue
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
        $offreTitre = $candidature->offre->titre ?? 'cette offre de stage';

        return (new MailMessage)
            ->subject('Réponse à votre candidature – Plateforme Stage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous vous informons que votre candidature pour l\'offre **' . $offreTitre . '** n\'a malheureusement pas été retenue.')
            ->line('Nous vous encourageons à consulter les autres offres disponibles sur la plateforme.')
            ->action('Voir les offres', url('/offres'))
            ->line('Bonne chance dans vos recherches !');
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
            'statut' => 'refuse',
        ];
    }
}
