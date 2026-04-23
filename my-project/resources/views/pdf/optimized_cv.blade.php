<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Curriculum Vitae - {{ $student->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #111827;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0;
            color: #6B7280;
            font-size: 14px;
        }
        .section-title {
            color: #4F46E5;
            font-size: 18px;
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .pitch-box {
            background-color: #EEF2FF;
            border-left: 4px solid #4F46E5;
            padding: 15px 20px;
            margin-bottom: 30px;
            border-radius: 0 4px 4px 0;
        }
        .pitch-box p {
            margin: 0;
            font-style: italic;
            color: #374151;
            font-size: 15px;
        }

        .skills-container {
            margin-bottom: 30px;
        }
        .skill-group {
            margin-bottom: 15px;
        }
        .skill-level {
            font-weight: bold;
            color: #4B5563;
            margin-bottom: 5px;
            font-size: 14px;
            text-transform: uppercase;
        }
        .skill-badges {
            display: block;
        }
        .skill-badge {
            display: inline-block;
            background-color: #F3F4F6;
            color: #374151;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 13px;
            margin-right: 5px;
            margin-bottom: 5px;
            border: 1px solid #D1D5DB;
        }

        .raw-cv {
            white-space: pre-wrap;
            font-size: 14px;
            color: #4B5563;
            line-height: 1.8;
            background-color: #F9FAFB;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #F3F4F6;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #9CA3AF;
            border-top: 1px solid #E5E7EB;
            padding-top: 20px;
        }
        
        /* Fallback for float layout of skills if dompdf doesn't like inline-block wrapping perfectly */
        .skill-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .skill-list li {
            float: left;
            margin-right: 8px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $student->name }}</h1>
        <p>{{ $student->email }} | Profil Étudiant</p>
        <p>Candidature pour : <strong>{{ $offre->titre }}</strong> ({{ $offre->entreprise->company_name ?? 'Entreprise' }})</p>
    </div>

    @if(!empty($optimization['improved_summary']))
    <div class="pitch-box">
        <p>"{{ $optimization['improved_summary'] }}"</p>
    </div>
    @endif

    <div class="section-title">Compétences Techniques</div>
    
    <div class="skills-container">
        @if($skills->isEmpty())
            <p>Aucune compétence extraite.</p>
        @else
            @foreach(['expert' => 'Expert', 'advanced' => 'Avancé', 'intermediate' => 'Intermédiaire', 'beginner' => 'Débutant'] as $levelKey => $levelLabel)
                @php
                    $levelSkills = $skills->where('pivot.level', $levelKey);
                @endphp
                
                @if($levelSkills->isNotEmpty())
                    <div class="skill-group">
                        <div class="skill-level">{{ $levelLabel }}</div>
                        <ul class="skill-list clearfix">
                            @foreach($levelSkills as $skill)
                                <li class="skill-badge">{{ $skill->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach
        @endif
    </div>

    <div class="section-title">Expériences & Formations</div>
    
    <div class="raw-cv">
        {{ $student->cv_text ?? 'Aucun détail fourni.' }}
    </div>

    <!-- Note on AI missing skills -->
    @if(!empty($optimization['missing_skills']))
    <div style="margin-top: 30px; padding: 15px; border: 1px solid #FCA5A5; background-color: #FEF2F2; border-radius: 4px;">
        <strong style="color: #B91C1C; font-size: 14px;">Note IA - Compétences recommandées à acquérir pour ce poste :</strong>
        <p style="margin: 5px 0 0; font-size: 13px; color: #7F1D1D;">
            {{ implode(', ', $optimization['missing_skills']) }}
        </p>
    </div>
    @endif

    <div class="footer">
        CV Optimisé par IA - Généré le {{ now()->format('d/m/Y') }}<br>
        Plateforme de Gestion de Stages
    </div>

</body>
</html>
