<?php

/**
 * lang/fr/candidature.php
 * Refactored: French strings for the Candidature domain.
 */

return [
    // ---- CRUD flash messages ----
    'created'       => 'Candidature soumise avec succès.',
    'updated'       => 'Candidature mise à jour avec succès.',
    'deleted'       => 'Candidature supprimée avec succès.',
    'status_updated'=> 'Statut de la candidature mis à jour.',

    // ---- Validation / business rule errors ----
    'already_applied' => 'Vous avez déjà soumis une candidature pour cette offre.',

    // ---- Status labels ----
    'status' => [
        'en_attente' => 'En attente',
        'accepte'    => 'Acceptée',
        'refuse'     => 'Refusée',
    ],
];
