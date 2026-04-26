<?php

namespace App\Notifications;

use App\Models\Candidature;
use Illuminate\Notifications\Notification;

/**
 * Sent synchronously to a Student for statut transitions other than
 * Acceptee / Refusee (e.g. Shortlisted, Interview).
 * Only uses the 'database' channel — no SMTP dependency.
 */
class CandidatureStatusNotification extends Notification
{
    public function __construct(
        public readonly Candidature $candidature,
        public readonly string $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type_label'     => 'candidature_status',
            'candidature_id' => $this->candidature->id,
            'offre_titre'    => $this->candidature->offre->titre ?? 'Offre inconnue',
            'message'        => $this->message,
            'url'            => route('student.candidatures.index'),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
