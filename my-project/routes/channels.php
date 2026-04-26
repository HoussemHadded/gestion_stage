<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
| Authorize private channels used by CvScoreUpdated, MatchScoreUpdated,
| CandidatureSubmitted, and NouvelleCandidatureNotification events.
*/

// Default: user's own notification channel (used by Laravel notifications)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Student's private channel: receives CV score updates and match score updates
Broadcast::channel('student.{studentId}', function ($user, $studentId) {
    return (int) $user->id === (int) $studentId;
});

// Enterprise's private channel: receives new candidature alerts and match score updates
Broadcast::channel('entreprise.{entrepriseId}', function ($user, $entrepriseId) {
    // An admin may also listen; an enterprise user may only see their own channel
    return $user->isAdmin() || (int) $user->id === (int) $entrepriseId;
});

// Candidature-specific channel: fine-grained match score updates
Broadcast::channel('candidature.{candidatureId}', function ($user, $candidatureId) {
    $candidature = \App\Models\Candidature::with('offre')->find($candidatureId);

    if (! $candidature) {
        return false;
    }

    // Allow: the student who applied OR the enterprise that owns the offer OR admin
    return $user->isAdmin()
        || (int) $user->id === (int) $candidature->student_id
        || (int) $user->id === (int) $candidature->offre?->entreprise_id;
});
