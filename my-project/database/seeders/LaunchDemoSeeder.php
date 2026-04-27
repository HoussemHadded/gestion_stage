<?php

namespace Database\Seeders;

use App\Enums\StatutCandidature;
use App\Enums\UserRole;
use App\Models\Candidature;
use App\Models\Offre;
use App\Models\OffreMatch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * LaunchDemoSeeder — Investor / Jury Demo Quality
 *
 * Produces a complete, realistic dataset with:
 * - 4 well-known Tunisian tech companies with real offers
 * - 12 student profiles with varied CVs and skills
 * - Full AI OffreMatch details JSON (skills, summary, level, location)
 * - match_percentage on every Candidature row
 * - Candidature dates spread across 6 months for trend chart
 * - Statut 'ouvert' on all offers for active_offres KPI
 * - Easy demo login accounts (student@demo.com / company@demo.com)
 */
class LaunchDemoSeeder extends Seeder
{
    // ── Skill pools per domain ────────────────────────────────────────────────
    private const SKILLS_FRONTEND = ['Vue.js', 'React', 'TypeScript', 'Tailwind CSS', 'HTML/CSS', 'Alpine.js', 'Figma'];
    private const SKILLS_BACKEND  = ['Laravel', 'Node.js', 'Django', 'REST API', 'GraphQL', 'PHP', 'Python'];
    private const SKILLS_DATA     = ['Python', 'SQL', 'Pandas', 'Tableau', 'Power BI', 'Machine Learning', 'TensorFlow'];
    private const SKILLS_DEVOPS   = ['Docker', 'Kubernetes', 'AWS', 'CI/CD', 'Git', 'Linux', 'Terraform'];
    private const SKILLS_GENERAL  = ['Agile/Scrum', 'Gestion de projet', 'Communication', 'Anglais B2', 'Analyse fonctionnelle'];

    public function run(): void
    {
        // ── 1. Demo quick-access accounts ────────────────────────────────────
        $demoStudent = User::firstOrCreate(['email' => 'student@demo.com'], [
            'name'     => 'Yasmine Belhadj',
            'role'     => UserRole::Etudiant->value,
            'password' => Hash::make('password'),
            'cv_score' => 84,
        ]);

        $demoCompany = User::firstOrCreate(['email' => 'company@demo.com'], [
            'name'          => 'TechNova Solutions',
            'role'          => UserRole::Entreprise->value,
            'company_name'  => 'TechNova Solutions',
            'company_address' => 'Les Berges du Lac II, Tunis',
            'password'      => Hash::make('password'),
        ]);

        // ── 2. Additional realistic companies ─────────────────────────────────
        $companies = [$demoCompany];

        $extraCompanies = [
            ['name' => 'Vermeg Group',    'email' => 'rh@vermeg.com',    'company_name' => 'Vermeg Group',    'company_address' => 'Lac II, Tunis'],
            ['name' => 'Expensya SA',     'email' => 'jobs@expensya.com', 'company_name' => 'Expensya SA',     'company_address' => 'Ariana, Tunis'],
            ['name' => 'InstaDeep Ltd',   'email' => 'hi@instadeep.com',  'company_name' => 'InstaDeep Ltd',   'company_address' => 'Menzah 9, Tunis'],
        ];

        foreach ($extraCompanies as $cd) {
            $companies[] = User::firstOrCreate(['email' => $cd['email']], array_merge($cd, [
                'role'     => UserRole::Entreprise->value,
                'password' => Hash::make('password'),
            ]));
        }

        // ── 3. Internship Offers (with statut = ouvert) ───────────────────────
        $offersMatrix = [
            // TechNova (demoCompany)
            [
                'company'     => $demoCompany,
                'titre'       => 'Développeur FullStack Laravel / Vue.js',
                'description' => 'Rejoignez notre équipe produit pour construire la prochaine génération de notre SaaS RH. Vous serez responsable de fonctionnalités end-to-end : backend Laravel, APIs RESTful, et interfaces Vue.js réactives. Autonomie et rigueur attendues.',
                'lieu'        => 'Tunis / Télétravail partiel',
                'type'        => 'Stage PFE',
                'level_required' => 'Bac+4 / Bac+5',
                'required_skills' => ['Laravel', 'Vue.js', 'REST API', 'Git', 'PHP'],
            ],
            [
                'company'     => $demoCompany,
                'titre'       => 'Ingénieur Data Analyst (Stage 6 mois)',
                'description' => 'Analysez les comportements utilisateurs via nos pipelines BigQuery. Vous construirez des dashboards Tableau, automatiserez des rapports Python, et participerez aux revues de performance produit chaque semaine.',
                'lieu'        => 'Lac II, Tunis',
                'type'        => 'Stage',
                'level_required' => 'Bac+3 / Bac+4',
                'required_skills' => ['Python', 'SQL', 'Tableau', 'Pandas'],
            ],
            // Vermeg
            [
                'company'     => $companies[1],
                'titre'       => 'Ingénieur Intelligence Artificielle (ML)',
                'description' => 'Développez des modèles de scoring financier basés sur le machine learning. Maîtrise de Python, TensorFlow et des principes de MLOps. Encadrement par une équipe senior de Data Scientists.',
                'lieu'        => 'Lac II, Tunis',
                'type'        => 'Stage PFE',
                'level_required' => 'Bac+5 / Master 2',
                'required_skills' => ['Python', 'Machine Learning', 'TensorFlow', 'SQL'],
            ],
            [
                'company'     => $companies[1],
                'titre'       => 'Développeur Backend Node.js',
                'description' => 'Conception et développement de microservices RESTful en Node.js pour notre plateforme fintech. Tests unitaires Jest, déploiement AWS Lambda, revues de code obligatoires.',
                'lieu'        => 'Ariana, Tunis',
                'type'        => 'Alternance',
                'level_required' => 'Bac+3',
                'required_skills' => ['Node.js', 'REST API', 'AWS', 'Git'],
            ],
            // Expensya
            [
                'company'     => $companies[2],
                'titre'       => 'Designer UX/UI Produit (Stage)',
                'description' => 'Participez à la refonte de notre application mobile de gestion de notes de frais. Conception de maquettes Figma, tests utilisateurs, et collaboration avec les PMs et développeurs front-end.',
                'lieu'        => 'Ariana, Tunis',
                'type'        => 'Stage',
                'level_required' => 'Bac+3',
                'required_skills' => ['Figma', 'HTML/CSS', 'Analyse fonctionnelle', 'Communication'],
            ],
            // InstaDeep
            [
                'company'     => $companies[3],
                'titre'       => 'Ingénieur DevOps / Cloud AWS',
                'description' => 'Automatisation des pipelines CI/CD, orchestration Kubernetes, monitoring Datadog. Vous évoluerez dans un environnement 100% cloud AWS sur des projets d\'IA à fort impact.',
                'lieu'        => 'Menzah 9, Tunis',
                'type'        => 'Stage PFE',
                'level_required' => 'Bac+5',
                'required_skills' => ['Docker', 'Kubernetes', 'AWS', 'CI/CD', 'Terraform', 'Linux'],
            ],
            [
                'company'     => $companies[3],
                'titre'       => 'Ingénieur Recherche IA / NLP',
                'description' => 'Travaillez sur des modèles de traitement du langage naturel pour des applications industrielles. Publication d\'articles, participation à des conférences ICLR/NeurIPS, mentorat senior.',
                'lieu'        => 'Menzah 9, Tunis',
                'type'        => 'Stage PFE',
                'level_required' => 'Bac+5 / Doctorat',
                'required_skills' => ['Python', 'Machine Learning', 'TensorFlow', 'Anglais B2', 'Pandas'],
            ],
        ];

        $offers = [];
        $offerSkillsMap = []; // [ offer_index => required_skills[] ]
        foreach ($offersMatrix as $od) {
            $offre = Offre::create([
                'entreprise_id'   => $od['company']->id,
                'titre'           => $od['titre'],
                'description'     => $od['description'],
                'lieu'            => $od['lieu'],
                'type'            => $od['type'],
                'level_required'  => $od['level_required'],
                'date_publication' => Carbon::now()->subDays(rand(3, 30)),
            ]);
            $offerSkillsMap[$offre->id] = $od['required_skills'];
            $offers[] = $offre;
        }

        // ── 4. Student profiles with realistic skill sets ─────────────────────
        $studentProfiles = [
            ['name' => 'Yasmine Belhadj', 'email' => 'student@demo.com', 'cv_score' => 84,
             'skills' => ['Laravel', 'Vue.js', 'PHP', 'REST API', 'Git', 'Agile/Scrum'],
             'level' => 'Bac+4', 'city' => 'Tunis'],

            ['name' => 'Rami Khedher',    'email' => 'rami@student.tn',  'cv_score' => 77,
             'skills' => ['Python', 'SQL', 'Pandas', 'Machine Learning', 'TensorFlow'],
             'level' => 'Bac+5', 'city' => 'Tunis'],

            ['name' => 'Sana Mansouri',   'email' => 'sana@student.tn',  'cv_score' => 68,
             'skills' => ['Figma', 'HTML/CSS', 'Vue.js', 'Communication', 'Anglais B2'],
             'level' => 'Bac+3', 'city' => 'Ariana'],

            ['name' => 'Amine Chaabane',  'email' => 'amine@student.tn', 'cv_score' => 91,
             'skills' => ['Docker', 'Kubernetes', 'AWS', 'CI/CD', 'Linux', 'Terraform', 'Git'],
             'level' => 'Bac+5', 'city' => 'Tunis'],

            ['name' => 'Lina Trabelsi',   'email' => 'lina@student.tn',  'cv_score' => 73,
             'skills' => ['Node.js', 'REST API', 'JavaScript', 'Git', 'SQL'],
             'level' => 'Bac+3', 'city' => 'Manouba'],

            ['name' => 'Youssef Bouzid',  'email' => 'youssef@student.tn','cv_score' => 62,
             'skills' => ['PHP', 'Laravel', 'SQL', 'HTML/CSS'],
             'level' => 'Bac+3', 'city' => 'Bizerte'],

            ['name' => 'Meriem Gharbi',   'email' => 'meriem@student.tn', 'cv_score' => 88,
             'skills' => ['Python', 'TensorFlow', 'Machine Learning', 'SQL', 'Pandas', 'Anglais B2'],
             'level' => 'Bac+5', 'city' => 'Tunis'],

            ['name' => 'Khaled Ben Ali',  'email' => 'khaled@student.tn', 'cv_score' => 55,
             'skills' => ['HTML/CSS', 'JavaScript', 'Figma', 'Communication'],
             'level' => 'Bac+2', 'city' => 'Sfax'],

            ['name' => 'Roua Hamdi',      'email' => 'roua@student.tn',   'cv_score' => 79,
             'skills' => ['Vue.js', 'React', 'TypeScript', 'Tailwind CSS', 'Git', 'REST API'],
             'level' => 'Bac+4', 'city' => 'Tunis'],

            ['name' => 'Tarek Guesmi',    'email' => 'tarek@student.tn',  'cv_score' => 70,
             'skills' => ['AWS', 'Docker', 'Linux', 'CI/CD', 'Git'],
             'level' => 'Bac+4', 'city' => 'Sousse'],

            ['name' => 'Nour Ben Salah',  'email' => 'nour@student.tn',   'cv_score' => 83,
             'skills' => ['Python', 'Tableau', 'Power BI', 'SQL', 'Pandas', 'Analyse fonctionnelle'],
             'level' => 'Bac+4', 'city' => 'Tunis'],

            ['name' => 'Adam Zribi',      'email' => 'adam@student.tn',   'cv_score' => 66,
             'skills' => ['Node.js', 'GraphQL', 'AWS', 'Docker', 'REST API'],
             'level' => 'Bac+4', 'city' => 'Ariana'],
        ];

        $students = [];
        foreach ($studentProfiles as $sp) {
            if ($sp['email'] === 'student@demo.com') {
                $student = $demoStudent;
                $student->update(['cv_score' => $sp['cv_score']]);
            } else {
                $student = User::firstOrCreate(['email' => $sp['email']], [
                    'name'     => $sp['name'],
                    'role'     => UserRole::Etudiant->value,
                    'password' => Hash::make('password'),
                    'cv_score' => $sp['cv_score'],
                ]);
            }
            $student->_profile = $sp; // temp property for matching logic
            $students[] = $student;
        }

        // ── 5. Build AI matches & candidatures ───────────────────────────────
        // Spread 6 months of dates for the trend chart
        $monthBuckets = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthBuckets[] = Carbon::now()->subMonths($i)->startOfMonth();
        }

        $statuses = [
            StatutCandidature::EnAttente,
            StatutCandidature::Shortlisted,
            StatutCandidature::Interview,
            StatutCandidature::Acceptee,
            StatutCandidature::Refusee,
        ];

        foreach ($offers as $offre) {
            $requiredSkills = $offerSkillsMap[$offre->id] ?? [];

            // Pick 6-10 students per offer
            $applicantCount = min(count($students), rand(6, 10));
            $shuffled = collect($students)->shuffle()->take($applicantCount);

            foreach ($shuffled as $student) {
                $profile  = $student->_profile ?? [];
                $owned    = $profile['skills'] ?? [];
                $matched  = array_values(array_intersect($owned, $requiredSkills));
                $missing  = array_values(array_diff($requiredSkills, $owned));

                // Score based on real skill overlap (Jaccard-style)
                $union    = array_unique(array_merge($owned, $requiredSkills));
                $baseScore = count($union) > 0 ? round((count($matched) / count($union)) * 100) : 30;

                // Boost for level / location match
                $levelBoost = str_contains($offre->level_required ?? '', explode('/', $profile['level'] ?? '')[0]) ? 8 : 0;
                $locBoost   = ($profile['city'] ?? '') === explode('/', $offre->lieu)[0] ? 5 : 0;
                $score      = min(99, $baseScore + $levelBoost + $locBoost + rand(-3, 5));

                // Derive an honest AI summary
                $summaryParts = [];
                if (!empty($matched)) {
                    $summaryParts[] = 'Maîtrise de ' . implode(', ', array_slice($matched, 0, 3));
                }
                if (!empty($missing)) {
                    $summaryParts[] = 'lacunes sur ' . implode(', ', array_slice($missing, 0, 2));
                }
                $summary = !empty($summaryParts)
                    ? 'Profil compatible : ' . implode('. ', $summaryParts) . '. Niveau académique ' . ($levelBoost ? 'conforme' : 'à vérifier') . '.'
                    : 'Profil analysé. Compatibilité générale avec les exigences du poste.';

                $details = [
                    'ai_summary' => $summary,
                    'skills'     => [
                        'score'   => count($matched) * 10,
                        'matched' => $matched,
                        'missing' => $missing,
                    ],
                    'level'      => [
                        'score'  => $levelBoost,
                        'reason' => $levelBoost
                            ? "Niveau {$profile['level']} correspond au prérequis {$offre->level_required}."
                            : "Niveau {$profile['level']} partiellement conforme au prérequis {$offre->level_required}.",
                    ],
                    'location'   => [
                        'score'  => $locBoost,
                        'reason' => $locBoost
                            ? "Localisation {$profile['city']} correspond à la ville de l'offre."
                            : "Offre à {$offre->lieu} — mobilité requise pour ce candidat.",
                    ],
                    'projects'   => [
                        'score'  => rand(4, 9),
                        'reason' => 'Projets personnels et expériences académiques détectés sur le CV.',
                    ],
                    'preferences' => [
                        'score'  => rand(3, 8),
                        'reason' => "Type de stage « {$offre->type} » aligné avec les préférences du profil.",
                    ],
                ];

                // Upsert the AI match
                OffreMatch::updateOrCreate(
                    ['student_id' => $student->id, 'offre_id' => $offre->id],
                    ['score' => $score, 'details' => $details]
                );

                // Derive a realistic candidature status from the score
                if ($score >= 85) {
                    $status = fake()->randomElement([StatutCandidature::Interview, StatutCandidature::Acceptee, StatutCandidature::Shortlisted]);
                } elseif ($score >= 65) {
                    $status = fake()->randomElement([StatutCandidature::Shortlisted, StatutCandidature::EnAttente, StatutCandidature::Interview]);
                } elseif ($score >= 45) {
                    $status = fake()->randomElement([StatutCandidature::EnAttente, StatutCandidature::EnAttente, StatutCandidature::Refusee]);
                } else {
                    $status = fake()->randomElement([StatutCandidature::Refusee, StatutCandidature::EnAttente]);
                }

                // Spread date across the last 6 months randomly
                $bucket    = $monthBuckets[array_rand($monthBuckets)];
                $candDate  = $bucket->copy()->addDays(rand(0, 25))->addHours(rand(8, 18));

                Candidature::updateOrCreate(
                    ['student_id' => $student->id, 'offre_id' => $offre->id],
                    [
                        'cv'               => 'CV_' . str_replace(' ', '_', $profile['name'] ?? 'Demo') . '.pdf',
                        'cv_version'       => $score >= 70 ? 'optimized' : 'original',
                        'statut'           => $status,
                        'match_percentage' => $score,
                        'date_candidature' => $candDate,
                    ]
                );
            }
        }

        $this->command->info('✅ LaunchDemoSeeder terminé — données démo investor-ready.');
        $this->command->table(
            ['Compte', 'Email', 'Mot de passe'],
            [
                ['Admin',     'admin@gestionstages.tn', 'password'],
                ['Étudiant',  'student@demo.com',       'password'],
                ['Entreprise','company@demo.com',        'password'],
            ]
        );
    }
}
