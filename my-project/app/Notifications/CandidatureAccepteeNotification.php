<?php

namespace App\Notifications;

use App\Models\Candidature;
use Illuminate\Notifications\Notification;

/**
 * Sent synchronously to a Student when their candidature is accepted.
 * Only uses the 'database' channel — no SMTP dependency.
 */
class CandidatureAccepteeNotification extends Notification
{
    public function __construct(
        public readonly Candidature $candidature
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $candidature   = $this->candidature->loadMissing('offre.entreprise');
        $offreTitre    = $candidature->offre->titre ?? 'Offre inconnue';
        $entrepriseNom = $candidature->offre->entreprise->company_name
            ?? $candidature->offre->entreprise->name
            ?? 'Une entreprise';

        return [
            'type_label'      => 'candidature_acceptee',
            'candidature_id'  => $candidature->id,
            'offre_titre'     => $offreTitre,
            'entreprise_name' => $entrepriseNom,
            'message'         => 'Félicitations ! Votre candidature pour « ' . $offreTitre . ' » a été acceptée.',
            'url'             => route('student.candidatures.index'),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
