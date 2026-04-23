<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidature;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CandidatureAcceptéeNotification extends Notification implements ShouldQueue, ShouldBroadcast
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
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $candidature = $this->candidature->loadMissing('offre.entreprise');
        $offreTitre = $candidature->offre->titre ?? 'une offre';
        $entrepriseNom = $candidature->offre->entreprise->company_name ?? $candidature->offre->entreprise->name ?? 'une entreprise';

        return (new MailMessage)
            ->subject('Félicitations ! Votre candidature a été acceptée')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Excellente nouvelle ! Votre candidature pour le poste de **' . $offreTitre . '** a été **acceptée** par **' . $entrepriseNom . '**.')
            ->line('L\'entreprise vous contactera très prochainement pour la suite des démarches.')
            ->action('Voir ma candidature', url('/student/candidatures'))
            ->line('Nous vous souhaitons beaucoup de succès dans votre stage !');
    }

    /**
     * Get the array representation for the database.
     */
    public function toDatabase(object $notifiable): array
    {
        $candidature = $this->candidature->loadMissing('offre.entreprise');
        return [
            'type_label' => 'candidature_acceptee',
            'candidature_id' => $candidature->id,
            'offre_titre' => $candidature->offre->titre ?? 'Offre inconnue',
            'entreprise_name' => $candidature->offre->entreprise->company_name ?? $candidature->offre->entreprise->name ?? 'Une entreprise',
            'message' => 'Félicitations ! Votre candidature pour ' . ($candidature->offre->titre ?? 'une offre') . ' a été acceptée.',
            'url' => route('student.candidatures.index')
        ];
    }
    
    /**
     * Get the array representation for broadcasting.
     */
    public function toBroadcast(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
