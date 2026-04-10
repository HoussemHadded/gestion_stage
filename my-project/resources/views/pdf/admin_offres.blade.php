<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport - Offres de Stage</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #4f46e5; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; color: #4f46e5; }
        tr:nth-child(even) { background-color: #f9fafb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>GestionStages</h1>
        <p>Rapport Administratif : Liste des Offres de Stage ({{ date('d/m/Y') }})</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Réf</th>
                <th>Titre de l'Offre</th>
                <th>Entreprise</th>
                <th>Lieu</th>
                <th>Candidatures Reçues</th>
            </tr>
        </thead>
        <tbody>
            @foreach($offres as $offre)
                <tr>
                    <td>#{{ $offre->id }}</td>
                    <td>{{ $offre->titre }}</td>
                    <td>{{ $offre->entreprise->name ?? 'N/A' }}</td>
                    <td>{{ $offre->lieu ?? 'N/A' }}</td>
                    <td>{{ $offre->candidatures_count ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
