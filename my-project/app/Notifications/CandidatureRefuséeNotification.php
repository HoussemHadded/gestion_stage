<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidature;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CandidatureRefuséeNotification extends Notification implements ShouldQueue, ShouldBroadcast
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
        $candidature = $this->candidature->loadMissing('offre.entreprise');
        $offreTitre = $candidature->offre->titre ?? 'une offre';
        $entrepriseNom = $candidature->offre->entreprise->company_name ?? $candidature->offre->entreprise->name ?? 'une entreprise';

        return (new MailMessage)
            ->subject('Mise à jour concernant votre candidature')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous vous informons que votre candidature pour l\'offre **' . $offreTitre . '** chez **' . $entrepriseNom . '** n\'a malheureusement pas été retenue.')
            ->line('Ne vous découragez pas, d\'autres opportunités vous attendent !')
            ->action('Voir d\'autres offres', url('/student/offres'))
            ->line('Nous vous souhaitons une excellente continuation dans vos recherches.');
    }

    /**
     * Get the array representation of the notification for the database.
     */
    public function toDatabase(object $notifiable): array
    {
        $candidature = $this->candidature->loadMissing('offre.entreprise');
        return [
            'type_label' => 'candidature_refusee',
            'candidature_id' => $candidature->id,
            'offre_titre' => $candidature->offre->titre ?? 'Offre inconnue',
            'entreprise_name' => $candidature->offre->entreprise->company_name ?? $candidature->offre->entreprise->name ?? 'Une entreprise',
            'message' => 'Votre candidature pour ' . ($candidature->offre->titre ?? 'une offre') . ' n\'a pas été retenue.',
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
