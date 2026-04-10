<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport - Candidatures globales</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #4f46e5; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f3f4f6; color: #4f46e5; }
        tr:nth-child(even) { background-color: #f9fafb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>GestionStages</h1>
        <p>Rapport Administratif : Suivi Global des Candidatures ({{ date('d/m/Y') }})</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Étudiant Candidat</th>
                <th>Titre de l'Offre</th>
                <th>Entreprise</th>
                <th>Date d'Envoi</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidatures as $candidature)
                <tr>
                    <td>#{{ $candidature->id }}</td>
                    <td>{{ $candidature->student->name ?? 'N/A' }}</td>
                    <td>{{ $candidature->offre->titre ?? 'N/A' }}</td>
                    <td>{{ $candidature->offre->entreprise->name ?? 'N/A' }}</td>
                    <td>{{ $candidature->date_candidature ? $candidature->date_candidature->format('d/m/Y') : '—' }}</td>
                    <td>{{ mb_strtoupper($candidature->statut->label()) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
