<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Offre;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EntrepriseDemoSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');
        $now = Carbon::now();

        $entreprisesData = [
            [
                'company_name' => 'Tunisie Telecom',
                'name' => 'Tunisie Telecom HR',
                'email' => 'contact@tunisietelecom.tn',
                'company_address' => 'Centre Urbain Nord, Tunis',
                'secteur' => 'Télécommunications',
                'website' => 'www.tunisietelecom.tn',
                'phone' => '+216 71 123 456',
                'offers' => [
                    ['titre' => 'Ingénieur Réseaux et Télécoms', 'type' => 'Stage PFE', 'level' => 'Bac+5'],
                    ['titre' => 'Développeur FullStack Laravel', 'type' => 'Stage été', 'level' => 'Bac+3'],
                    ['titre' => 'Chef de Projet IT', 'type' => 'CDI', 'level' => 'Bac+5'],
                ]
            ],
            [
                'company_name' => 'Ooredoo Tunisie',
                'name' => 'Ooredoo Recrutement',
                'email' => 'recrutement@ooredoo.tn',
                'company_address' => 'Les Berges du Lac, Tunis',
                'secteur' => 'Télécommunications',
                'website' => 'www.ooredoo.tn',
                'phone' => '+216 22 123 456',
                'offers' => [
                    ['titre' => 'Data Scientist Analyst', 'type' => 'CDI', 'level' => 'Bac+5'],
                    ['titre' => 'Consultant Cybersécurité', 'type' => 'Stage PFE', 'level' => 'Bac+5'],
                    ['titre' => 'Spécialiste Marketing Digital', 'type' => 'CDD', 'level' => 'Bac+3 / Bac+5'],
                    ['titre' => 'Développeur Mobile Flutter', 'type' => 'Alternance', 'level' => 'Bac+4'],
                ]
            ],
            [
                'company_name' => 'Orange Tunisie',
                'name' => 'Orange Talents',
                'email' => 'rh@orange.tn',
                'company_address' => 'Centre Urbain Nord, Tunis',
                'secteur' => 'Télécommunications / IT',
                'website' => 'www.orange.tn',
                'phone' => '+216 31 123 456',
                'offers' => [
                    ['titre' => 'DevOps Engineer', 'type' => 'CDI', 'level' => 'Bac+5'],
                    ['titre' => 'Développeur Backend Node.js', 'type' => 'Stage PFE', 'level' => 'Bac+5'],
                    ['titre' => 'Analyste QA', 'type' => 'Stage été', 'level' => 'Bac+3'],
                ]
            ],
            [
                'company_name' => 'BIAT',
                'name' => 'BIAT RH',
                'email' => 'hr@biat.tn',
                'company_address' => 'Avenue Habib Bourguiba, Tunis',
                'secteur' => 'Banque & Finance',
                'website' => 'www.biat.tn',
                'phone' => '+216 71 345 678',
                'offers' => [
                    ['titre' => 'Analyste Financier', 'type' => 'CDI', 'level' => 'Master / Bac+5'],
                    ['titre' => 'Auditeur IT', 'type' => 'Stage PFE', 'level' => 'Bac+5'],
                    ['titre' => 'Développeur Java Spring Boot', 'type' => 'CDD', 'level' => 'Bac+4'],
                ]
            ],
            [
                'company_name' => 'Poulina Group',
                'name' => 'Poulina Holding',
                'email' => 'careers@poulinagroup.com',
                'company_address' => 'GP1, Ezzahra',
                'secteur' => 'Agroalimentaire / Industrie',
                'website' => 'www.poulinagroupholding.com',
                'phone' => '+216 71 456 789',
                'offers' => [
                    ['titre' => 'Ingénieur Industriel', 'type' => 'Stage PFE', 'level' => 'Bac+5'],
                    ['titre' => 'Contrôleur de Gestion', 'type' => 'CDI', 'level' => 'Bac+5'],
                    ['titre' => 'Technicien Supérieur Maintenance', 'type' => 'CDD', 'level' => 'Bac+3'],
                    ['titre' => 'Administrateur Systèmes', 'type' => 'Stage été', 'level' => 'Bac+3'],
                ]
            ],
            [
                'company_name' => 'Vermeg',
                'name' => 'Vermeg Careers',
                'email' => 'jobs@vermeg.com',
                'company_address' => 'Les Berges du Lac 1, Tunis',
                'secteur' => 'Éditeur de Logiciels',
                'website' => 'www.vermeg.com',
                'phone' => '+216 71 112 233',
                'offers' => [
                    ['titre' => 'Software Engineer (Java/Angular)', 'type' => 'CDI', 'level' => 'Bac+5'],
                    ['titre' => 'Business Analyst', 'type' => 'Stage PFE', 'level' => 'Bac+5'],
                    ['titre' => 'Ingénieur Qualité Logiciel', 'type' => 'Alternance', 'level' => 'Bac+5'],
                ]
            ],
            [
                'company_name' => 'Telnet',
                'name' => 'Telnet RH',
                'email' => 'recrutement@telnet.tn',
                'company_address' => 'Technopôle El Ghazala, Ariana',
                'secteur' => 'Ingénierie & Technologie',
                'website' => 'www.groupe-telnet.com',
                'phone' => '+216 71 856 789',
                'offers' => [
                    ['titre' => 'Ingénieur Systèmes Embarqués', 'type' => 'Stage PFE', 'level' => 'Bac+5'],
                    ['titre' => 'Développeur C/C++', 'type' => 'CDI', 'level' => 'Bac+5'],
                    ['titre' => 'Chef de Projet IoT', 'type' => 'CDI', 'level' => 'Bac+5'],
                    ['titre' => 'Designer CAO/DAO', 'type' => 'Stage été', 'level' => 'Bac+3'],
                ]
            ],
            [
                'company_name' => 'Délice Holding',
                'name' => 'Délice Recrutement',
                'email' => 'contact.rh@delice.tn',
                'company_address' => 'Soliman, Nabeul',
                'secteur' => 'Agroalimentaire',
                'website' => 'www.delice.tn',
                'phone' => '+216 72 234 567',
                'offers' => [
                    ['titre' => 'Ingénieur Qualité Agroalimentaire', 'type' => 'CDI', 'level' => 'Bac+5'],
                    ['titre' => 'Responsable Logistique', 'type' => 'CDI', 'level' => 'Bac+5'],
                    ['titre' => 'Assistant Marketing', 'type' => 'Stage PFE', 'level' => 'Bac+4 / Bac+5'],
                ]
            ],
            [
                'company_name' => 'Talan Tunisie',
                'name' => 'Talan Talent',
                'email' => 'recrutement.tunisie@talan.com',
                'company_address' => 'Charguia 1, Tunis',
                'secteur' => 'Conseil IT & Intégration',
                'website' => 'www.talan.com',
                'phone' => '+216 71 789 012',
                'offers' => [
                    ['titre' => 'Consultant SAP', 'type' => 'CDI', 'level' => 'Bac+5'],
                    ['titre' => 'Architecte Cloud AWS', 'type' => 'CDI', 'level' => 'Bac+5'],
                    ['titre' => 'Data Engineer', 'type' => 'Stage PFE', 'level' => 'Bac+5'],
                    ['titre' => 'Développeur ReactJS', 'type' => 'CDD', 'level' => 'Bac+3 / Bac+5'],
                ]
            ],
            [
                'company_name' => 'Sopra HR',
                'name' => 'Sopra HR Software',
                'email' => 'recrutement@soprahr.com',
                'company_address' => 'Les Berges du Lac 2, Tunis',
                'secteur' => 'Éditeur de Logiciels RH',
                'website' => 'www.soprahr.com',
                'phone' => '+216 71 890 123',
                'offers' => [
                    ['titre' => 'Consultant SIRH', 'type' => 'CDI', 'level' => 'Bac+5'],
                    ['titre' => 'Développeur PL/SQL', 'type' => 'Stage PFE', 'level' => 'Bac+5'],
                    ['titre' => 'Ingénieur Support Technique', 'type' => 'CDI', 'level' => 'Bac+3 / Bac+5'],
                ]
            ],
        ];

        foreach ($entreprisesData as $eData) {
            // Verify and create company
            $entreprise = User::firstOrCreate(
                ['email' => $eData['email']],
                [
                    'name' => $eData['name'],
                    'company_name' => $eData['company_name'],
                    'company_address' => $eData['company_address'],
                    'role' => UserRole::Entreprise->value ?? 'entreprise',
                    'password' => $password,
                    'email_verified_at' => $now,
                ]
            );

            // Generate realistic offers
            foreach ($eData['offers'] as $index => $offerData) {
                // Generate realistic dynamic attributes
                $skills = $this->getSkillsForOffer($offerData['titre']);
                $duree = str_contains(strtolower($offerData['type']), 'stage') ? rand(3, 6) . ' mois' : 'Indéterminée';
                $salaire = str_contains(strtolower($offerData['type']), 'stage') ? rand(300, 800) . ' TND/mois' : rand(1500, 4500) . ' TND/mois';
                $deadline = Carbon::now()->addDays(rand(10, 60))->format('Y-m-d');
                $status = 'Active';

                $descriptionHTML = "
<p><strong>{$eData['company_name']}</strong> recrute un(e) <strong>{$offerData['titre']}</strong>.</p>
<br>
<h4>🎯 Mission & Description :</h4>
<p>Rejoignez une équipe dynamique dans le secteur de <strong>{$eData['secteur']}</strong>. Vous serez amené(e) à travailler sur des projets innovants et à relever de nouveaux défis techniques et métiers.</p>
<br>
<h4>📋 Profil Recherché :</h4>
<ul>
    <li><strong>Niveau d'études :</strong> {$offerData['level']}</li>
    <li><strong>Compétences demandées :</strong> {$skills}</li>
</ul>
<br>
<h4>💼 Détails de l'Offre :</h4>
<ul>
    <li><strong>Type :</strong> {$offerData['type']}</li>
    <li><strong>Durée :</strong> {$duree}</li>
    <li><strong>Rémunération / Indemnité :</strong> {$salaire}</li>
    <li><strong>Lieu :</strong> {$eData['company_address']}</li>
    <li><strong>Date limite de candidature :</strong> {$deadline}</li>
    <li><strong>Statut :</strong> {$status}</li>
</ul>
<br>
<h4>📞 Contact :</h4>
<p>Site web : <a href='https://{$eData['website']}' target='_blank'>{$eData['website']}</a><br>Téléphone : {$eData['phone']}</p>
";

                Offre::create([
                    'entreprise_id' => $entreprise->id,
                    'titre' => $offerData['titre'],
                    'description' => $descriptionHTML,
                    'lieu' => $eData['company_address'],
                    'type' => $offerData['type'],
                    'level_required' => $offerData['level'],
                    'date_publication' => Carbon::now()->subDays(rand(1, 15)),
                ]);
            }
        }
    }

    private function getSkillsForOffer(string $title): string
    {
        $titleLower = strtolower($title);
        
        if (str_contains($titleLower, 'laravel') || str_contains($titleLower, 'php')) {
            return 'PHP, Laravel, MySQL, Vue.js, Git';
        }
        if (str_contains($titleLower, 'java') || str_contains($titleLower, 'spring')) {
            return 'Java, Spring Boot, Hibernate, PostgreSQL, Docker';
        }
        if (str_contains($titleLower, 'data') || str_contains($titleLower, 'analyst')) {
            return 'Python, SQL, Tableau, Pandas, Machine Learning';
        }
        if (str_contains($titleLower, 'devops') || str_contains($titleLower, 'cloud')) {
            return 'AWS, Docker, Kubernetes, CI/CD, Linux, Terraform';
        }
        if (str_contains($titleLower, 'réseau') || str_contains($titleLower, 'système')) {
            return 'Cisco, TCP/IP, Linux, Windows Server, Sécurité';
        }
        if (str_contains($titleLower, 'mobile') || str_contains($titleLower, 'flutter')) {
            return 'Flutter, Dart, Firebase, REST API, Git';
        }
        if (str_contains($titleLower, 'marketing') || str_contains($titleLower, 'digital')) {
            return 'SEO, Google Analytics, Réseaux Sociaux, Copywriting';
        }
        if (str_contains($titleLower, 'qualité') || str_contains($titleLower, 'qa')) {
            return 'Selenium, Cypress, Tests automatisés, ISTQB';
        }
        if (str_contains($titleLower, 'sap') || str_contains($titleLower, 'consultant')) {
            return 'SAP FI/CO, ABAP, Gestion de projet, Communication';
        }
        if (str_contains($titleLower, 'industriel') || str_contains($titleLower, 'logistique')) {
            return 'Supply Chain, Lean Management, Excel Avancé, SAP';
        }
        if (str_contains($titleLower, 'finance') || str_contains($titleLower, 'gestion')) {
            return 'Analyse Financière, Excel, VBA, Modélisation';
        }

        return 'Travail en équipe, Autonomie, Rigueur, Communication';
    }
}
