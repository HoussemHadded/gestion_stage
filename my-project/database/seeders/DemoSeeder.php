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
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Demo Company
        $company = User::factory()->create([
            'name' => 'TechNova AI',
            'email' => 'contact@technova.ai',
            'role' => UserRole::Entreprise->value,
            'company_name' => 'TechNova AI',
            'company_address' => 'Station F, Paris',
            'password' => Hash::make('password'),
        ]);

        // 2. Create High-Quality Internship Offers
        $offersData = [
            [
                'titre' => 'Ingénieur Intelligence Artificielle (H/F)',
                'description' => "Vous participerez à l'élaboration de nos modèles LLM pour la fintech. Maîtrise de Python, PyTorch et de l'algorithmie avancée requise.",
                'lieu' => 'Paris',
                'type' => 'Stage PFE',
                'level_required' => 'Bac+5 / Master 2',
            ],
            [
                'titre' => 'Développeur FullStack Laravel/Vue.js',
                'description' => "Rejoignez notre équipe core pour orchestrer la nouvelle version de notre plateforme SaaS. Forte autonomie attendue.",
                'lieu' => 'Lyon / Télétravail',
                'type' => 'Alternance',
                'level_required' => 'Bac+3 / Licence',
            ],
            [
                'titre' => 'Product Owner Junior',
                'description' => "Gestion du backlog, animation des rituels agiles, roadmap produit. Idéalement une première expérience en conception SaaS.",
                'lieu' => 'Bordeaux',
                'type' => 'Stage de Fin d\'Études',
                'level_required' => 'Bac+5 / Ecole de Commerce',
            ],
            [
                'titre' => 'Data Analyst (Stage 6 mois)',
                'description' => "Analyse des comportements utilisateurs via nos pipelines de données. SQL, Tableau, et Python.",
                'lieu' => 'Paris',
                'type' => 'Stage',
                'level_required' => 'Bac+4 / Master 1',
            ],
            [
                'titre' => 'Ingénieur DevOps / Cloud',
                'description' => "Automatisation de nos déploiements CI/CD avec Docker, Kubernetes, et AWS.",
                'lieu' => 'Nantes',
                'type' => 'Alternance',
                'level_required' => 'Bac+5 / Master 2',
            ]
        ];

        $offers = [];
        foreach ($offersData as $data) {
            $offers[] = Offre::create([
                'entreprise_id' => $company->id,
                'titre' => $data['titre'],
                'description' => $data['description'],
                'lieu' => $data['lieu'],
                'type' => $data['type'],
                'level_required' => $data['level_required'],
                'date_publication' => Carbon::now()->subDays(rand(1, 15)),
            ]);
        }

        // 3. Create Demo Students
        $students = [];
        $studentNames = [
            'Alice Dupuis', 'Thomas Martin', 'Emma Leroy', 'Léo Dubois', 'Chloé Moreau',
            'Hugo Laurent', 'Inès Simon', 'Lucas Michel', 'Julie Garcia', 'Antoine David',
            'Léa Richard', 'Paul Roux', 'Sarah Vincent', 'Arthur Blanc', 'Manon Guerin'
        ];

        foreach ($studentNames as $index => $name) {
            $students[] = User::factory()->create([
                'name' => $name,
                'email' => "student{$index}@example.com",
                'role' => UserRole::Etudiant->value,
                'password' => Hash::make('password'),
                'cv_score' => rand(50, 95), // General CV strength
            ]);
        }
        
        // Let's make sure the first student is easy to log into for demo
        User::factory()->create([
            'name' => 'Demo Student',
            'email' => 'student@demo.com',
            'role' => UserRole::Etudiant->value,
            'password' => Hash::make('password'),
            'cv_score' => 88,
        ]);

        User::factory()->create([
            'name' => 'Demo Company',
            'email' => 'company@demo.com',
            'role' => UserRole::Entreprise->value,
            'company_name' => 'Future Corp',
            'password' => Hash::make('password'),
        ]);

        $demoStudent = User::where('email', 'student@demo.com')->first();
        array_push($students, $demoStudent);

        // 4. Spread Candidatures and AI Match Scores
        $statuses = [
            StatutCandidature::EnAttente->value,
            StatutCandidature::Shortlisted->value,
            StatutCandidature::Interview->value,
            StatutCandidature::Acceptee->value,
            StatutCandidature::Refusee->value,
        ];

        foreach ($offers as $offre) {
            // Pick 5 to 10 random students for each offer
            $applicantsCount = rand(5, 10);
            $applicants = fake()->randomElements($students, $applicantsCount);

            foreach ($applicants as $student) {
                // Determine a realistic status based on match score logic
                $matchScore = rand(40, 98);
                
                // Create AI Match Score Row
                OffreMatch::create([
                    'student_id' => $student->id,
                    'offre_id' => $offre->id,
                    'score' => $matchScore,
                ]);

                // Create Candidature Record
                $status = StatutCandidature::EnAttente->value;

                if ($matchScore > 85) {
                    $status = fake()->randomElement([StatutCandidature::Interview->value, StatutCandidature::Acceptee->value, StatutCandidature::Shortlisted->value]);
                } elseif ($matchScore > 65) {
                    $status = fake()->randomElement([StatutCandidature::Shortlisted->value, StatutCandidature::EnAttente->value]);
                } elseif ($matchScore < 50) {
                    $status = fake()->randomElement([StatutCandidature::Refusee->value, StatutCandidature::EnAttente->value]);
                }

                Candidature::create([
                    'student_id' => $student->id,
                    'offre_id' => $offre->id,
                    'cv' => 'CV_Document_' . Str::random(5) . '.pdf',
                    'cv_version' => rand(0, 1) == 1 ? 'optimized' : 'original',
                    'statut' => $status,
                    'date_candidature' => Carbon::now()->subDays(rand(0, 10))->subHours(rand(0, 23)),
                ]);
            }
        }
    }
}
