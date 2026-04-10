<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Détails Candidature</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #7c3aed; padding-bottom: 15px; margin-bottom: 30px; }
        .header h1 { color: #7c3aed; margin: 0 0 5px 0; }
        .details h3 { background: #f3f4f6; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .row { margin-bottom: 12px; }
        .label { font-weight: bold; display: inline-block; width: 150px; }
        .status { padding: 5px 10px; border-radius: 5px; font-weight: bold; color: white; display: inline-block; }
        .status-attente { background-color: #f59e0b; }
        .status-accepte { background-color: #10b981; }
        .status-refuse { background-color: #ef4444; }
    </style>
</head>
<body>

    <div class="header">
        <h1>GestionStages</h1>
        <p>Attestation de Candidature Officielle</p>
    </div>

    <div class="row" style="text-align: right; color: #777;">
        Réf: #{{ str_pad($candidature->id, 5, '0', STR_PAD_LEFT) }}<br>
        Date d'édition: {{ date('d/m/Y') }}
    </div>

    <div class="details">
        <h3>Informations de l'Étudiant</h3>
        <div class="row">
            <span class="label">Nom complet:</span> {{ $candidature->student->name }}
        </div>
        <div class="row">
            <span class="label">Email:</span> {{ $candidature->student->email }}
        </div>
        <div class="row">
            <span class="label">Date de soumission:</span> {{ $candidature->date_candidature ? $candidature->date_candidature->format('d/m/Y à H:i') : 'N/A' }}
        </div>
    </div>

    <div class="details">
        <h3>Détails du Stage Ciblé</h3>
        <div class="row">
            <span class="label">Titre de l'Offre:</span> {{ $candidature->offre->titre }}
        </div>
        <div class="row">
            <span class="label">Entreprise:</span> {{ $candidature->offre->entreprise->name ?? 'N/A' }}
        </div>
        <div class="row">
            <span class="label">Lieu:</span> {{ $candidature->offre->lieu ?? 'N/A' }}
        </div>
        <div class="row" style="margin-top: 20px;">
            <span class="label">Statut Actuel:</span>
            @php
                $bgClass = 'status-attente';
                if($candidature->statut->value === 'acceptée') $bgClass = 'status-accepte';
                if($candidature->statut->value === 'refusée') $bgClass = 'status-refuse';
            @endphp
            <span class="status {{ $bgClass }}">
                {{ mb_strtoupper($candidature->statut->label()) }}
            </span>
        </div>
    </div>

</body>
</html>
