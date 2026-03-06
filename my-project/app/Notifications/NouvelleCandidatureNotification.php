<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidature;

class NouvelleCandidatureNotification extends Notification implements ShouldQueue
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
        $candidature = $this->candidature->loadMissing(['student', 'offre']);
        $studentName = $candidature->student->name ?? 'Un étudiant';
        $offreTitre = $candidature->offre->titre ?? 'une offre';

        return (new MailMessage)
            ->subject('Nouvelle candidature reçue – Plateforme Stage')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle candidature a été déposée pour votre offre : **' . $offreTitre . '**.')
            ->line('Candidat : **' . $studentName . '**.')
            ->line('Connectez-vous à la plateforme pour consulter le CV et gérer cette candidature.')
            ->action('Voir les candidatures', url('/candidatures'))
            ->line('Merci d\'utiliser notre plateforme de gestion de stages.');
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
        ];
    }
}
