<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidature;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NouvelleCandidatureNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Candidature $candidature
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
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
     * Get the array representation of the notification for the database.
     */
    public function toDatabase(object $notifiable): array
    {
        $candidature = $this->candidature->loadMissing(['student', 'offre']);
        return [
            'type_label' => 'nouvelle_candidature',
            'candidature_id' => $candidature->id,
            'offre_titre' => $candidature->offre->titre ?? 'Offre inconnue',
            'student_name' => $candidature->student->name ?? 'Un étudiant',
            'message' => 'Nouvelle candidature de ' . ($candidature->student->name ?? 'Un étudiant') . ' pour ' . ($candidature->offre->titre ?? 'votre offre'),
            'url' => route('entreprise.candidatures.index')
        ];
    }
    
    /**
     * Get the array representation of the notification for broadcasting.
     */
    public function toBroadcast(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
