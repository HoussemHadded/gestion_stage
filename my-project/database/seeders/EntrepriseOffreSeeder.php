<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Offre;
use App\Models\Skill;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EntrepriseOffreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Définition des compétences techniques et business
        $skillsList = [
            'PHP', 'Laravel', 'React', 'Vue.js', 'Python', 'TensorFlow', 'SQL', 
            'DevOps', 'Docker', 'Kubernetes', 'Java', 'Angular', 'AWS', 'NLP',
            'Marketing Digital', 'Business Development', 'UI/UX Design', 'SEO',
            'Gestion de Projet', 'Agile/Scrum', 'Node.js', 'Cyber-sécurité'
        ];

        $skills = collect($skillsList)->map(function ($skillName) {
            return Skill::firstOrCreate(['name' => $skillName]);
        });

        // 2. Définition des entreprises réalistes en Tunisie
        $companies = [
            [
                'name' => 'Recrutement Vermeg',
                'company_name' => 'Vermeg',
                'email' => 'hr@vermeg.com',
                'company_address' => 'Les Berges du Lac 1, Tunis',
            ],
            [
                'name' => 'Instadeep Careers',
                'company_name' => 'Instadeep',
                'email' => 'careers@instadeep.com',
                'company_address' => 'Avenue Habib Bourguiba, Tunis',
            ],
            [
                'name' => 'Talan Tunisie Recruitment',
                'company_name' => 'Talan Tunisie',
                'email' => 'recrutement@talan.com',
                'company_address' => 'Charguia II, Tunis',
            ],
            [
                'name' => 'Expensya HR',
                'company_name' => 'Expensya',
                'email' => 'jobs@expensya.com',
                'company_address' => 'Technopole El Ghazala, Ariana',
            ],
            [
                'name' => 'Orange Tunisie RH',
                'company_name' => 'Orange Tunisie',
                'email' => 'orange.recrutement@orange.tn',
                'company_address' => 'Centre Urbain Nord, Tunis',
            ],
            [
                'name' => 'Sagemcom RH',
                'company_name' => 'Sagemcom Tunisie',
                'email' => 'hr-sagemcom@sagemcom.com',
                'company_address' => 'Zone Industrielle Borj Cedria, Ben Arous',
            ],
            [
                'name' => 'Telnet HR Department',
                'company_name' => 'Telnet Holding',
                'email' => 'contact@telnet.com.tn',
                'company_address' => 'Technopole Sfax, Sfax',
            ],
            [
                'name' => 'Vistaprint Tech Careers',
                'company_name' => 'Vistaprint Tunisie',
                'email' => 'careers-tn@vistaprint.com',
                'company_address' => 'Z.I. Kheireddine, Le Kram, Tunis',
            ],
            [
                'name' => 'Gomycode Talent',
                'company_name' => 'Gomycode',
                'email' => 'hr@gomycode.com',
                'company_address' => 'Les Berges du Lac 2, Tunis',
            ],
            [
                'name' => 'Advancia Recruitment',
                'company_name' => 'Advancia Téléservices',
                'email' => 'recrutement@advancia.tn',
                'company_address' => 'Khereddine Pacha, Tunis',
            ],
        ];

        foreach ($companies as $compData) {
            // Création de l'utilisateur avec rôle Entreprise
            $user = User::create([
                'name' => $compData['name'],
                'email' => $compData['email'],
                'password' => Hash::make('password123'),
                'role' => UserRole::Entreprise,
                'company_name' => $compData['company_name'],
                'company_address' => $compData['company_address'],
            ]);

            // 3. Génération d'offres pour chaque entreprise
            $numOffers = rand(3, 6);
            for ($i = 0; $i < $numOffers; $i++) {
                $type = collect(['stage PFE', 'stage d\'été', 'alternance', 'job'])->random();
                $offre = Offre::create([
                    'titre' => $this->getRandomTitle($compData['company_name']),
                    'description' => $this->getRandomDescription($compData['company_name']),
                    'lieu' => $compData['company_address'],
                    'type' => $type,
                    'level_required' => collect(['Bac+3', 'Master', 'Ingénieur'])->random(),
                    'date_publication' => Carbon::now()->subDays(rand(1, 30)),
                    'entreprise_id' => $user->id,
                ]);

                // Attachement de compétences aléatoires
                $offre->skills()->attach(
                    $skills->random(rand(3, 5))->pluck('id')->toArray()
                );
            }
        }
    }

    /**
     * Génère un titre d'offre réaliste en fonction de l'entreprise.
     */
    private function getRandomTitle(string $companyName): string
    {
        $techTitles = [
            'Développeur Fullstack Laravel/React',
            'Ingénieur Cloud & DevOps',
            'Data Scientist Junior (IA & NLP)',
            'Développeur Frontend Vue.js',
            'Ingénieur Systèmes Embarqués',
            'Expert Cyber-sécurité',
            'Développeur Mobile Flutter',
            'Ingénieur QA & Test Automation',
        ];

        $bizTitles = [
            'Digital Marketing Specialist',
            'Business Development Manager',
            'Product Owner Junior',
            'UI/UX Designer Senior',
            'Analyste Financier',
            'Consultant IT & Transformation Digitale',
        ];

        return collect(array_merge($techTitles, $bizTitles))->random();
    }

    /**
     * Génère une description réaliste.
     */
    private function getRandomDescription(string $companyName): string
    {
        $descriptions = [
            "Nous recherchons un talent passionné pour rejoindre notre équipe dynamique chez $companyName. Vous participerez activement au développement de solutions innovantes et à la mise en œuvre de nouvelles fonctionnalités.\n\nResponsabilités :\n- Conception et développement technique\n- Participation aux réunions de sprint\n- Optimisation des performances\n- Veille technologique constante.",
            "Opportunité unique chez $companyName pour un profil motivé. Intégrez un environnement stimulant où l'innovation est au cœur de notre stratégie.\n\nMissions :\n- Analyse des besoins utilisateurs\n- Développement et maintenance évolutive\n- Collaboration avec les équipes design et produit\n- Documentation technique.",
            "Rejoignez l'aventure $companyName ! Nous offrons un cadre de travail moderne et des projets à forte valeur ajoutée.\n\nProfil recherché :\n- Autonomie et esprit d'équipe\n- Capacité d'analyse et de synthèse\n- Passion pour les nouvelles technologies\n- Bonne maîtrise de la langue française.",
            "Dans le cadre de notre expansion, $companyName recrute des talents pour renforcer son pôle technique. Vous serez amené à travailler sur des architectures complexes et scalables.\n\nCe que nous offrons :\n- Encadrement de qualité\n- Projets internationaux\n- Possibilité d'embauche après stage\n- Ambiance startup tech.",
        ];

        return collect($descriptions)->random();
    }
}
