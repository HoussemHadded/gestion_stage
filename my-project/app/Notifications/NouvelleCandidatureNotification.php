<?php

namespace App\Notifications;

use App\Models\Candidature;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent synchronously to an Entreprise user when a student applies.
 * via() returns ['database'] only — mail is handled separately via
 * a Mailable if SMTP is configured; removing it from here prevents
 * a fatal MailMessage-not-found error from blocking the DB channel.
 */
class NouvelleCandidatureNotification extends Notification
{
    public function __construct(
        public readonly Candidature $candidature
    ) {}

    public function via(object $notifiable): array
    {
        // 'database' MUST come before 'mail' so a mail config error
        // does NOT prevent the notification row being written to DB.
        return ['database'];
    }

    /**
     * Stored in the `notifications` table (data column).
     */
    public function toDatabase(object $notifiable): array
    {
        $candidature = $this->candidature->loadMissing(['student', 'offre']);

        return [
            'type_label'     => 'nouvelle_candidature',
            'candidature_id' => $candidature->id,
            'offre_titre'    => $candidature->offre->titre    ?? 'Offre inconnue',
            'student_name'   => $candidature->student->name   ?? 'Un étudiant',
            'message'        => 'Nouvelle candidature de '
                . ($candidature->student->name   ?? 'un étudiant')
                . ' pour « '
                . ($candidature->offre->titre ?? 'votre offre')
                . ' ».',
            'url'            => route('entreprise.candidatures.index'),
        ];
    }

    /**
     * toArray() is the fallback used by the 'database' channel when
     * toDatabase() is not explicitly defined — we keep both for safety.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
