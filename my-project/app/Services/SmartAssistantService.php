<?php

namespace App\Services;

use Illuminate\Support\Str;

class SmartAssistantService
{
    public function getResponse(string $message, string $role, string $url): string
    {
        $message = strtolower($message);
        
        // Salutations
        if (in_array(trim($message), ['bonjour', 'salut', 'coucou', 'hello', 'hey', 'bonsoir'])) {
            return $this->getGreeting($role);
        }

        // Requêtes d'aide générales
        if (Str::contains($message, ['aide', 'comment', 'help', 'expliquer', 'pourquoi', 'quoi', 'perdu'])) {
            return $this->getContextualAdvice($role, $url);
        }

        // Politesse
        if (Str::contains($message, ['merci', 'thanks', 'cool', 'super', 'parfait', 'génial'])) {
            return "Avec grand plaisir ! Je reste à votre entière disposition si vous avez d'autres questions pour optimiser votre parcours.";
        }

        // Mots-clés Étudiant spécifiques
        if ($role === 'etudiant') {
            if (Str::contains($message, ['cv', 'compétence', 'profil', 'parser'])) {
                return "L'optimisation de votre profil est primordiale. Dans la section 'Mon CV (IA)', notre algorithme extrait automatiquement vos points forts. C'est la première étape stratégique pour décrocher un entretien !";
            }
            if (Str::contains($message, ['offre', 'stage', 'postuler', 'entreprise', 'recherche'])) {
                return "Pour trouver le stage idéal, explorez la section 'Offres'. Le système calcule un 'Score de Match' en temps réel pour cibler précisément les opportunités où vous excellez.";
            }
            if (Str::contains($message, ['candidature', 'statut', 'réponse', 'refus', 'suivi'])) {
                return "Suivez l'état d'avancement de chaque démarche dans 'Mes Candidatures'. Une attitude proactive est toujours récompensée : n'hésitez pas à postuler à plusieurs offres de manière ciblée.";
            }
            if (Str::contains($message, ['score', 'matching', 'ia', 'algorithme', 'pourcentage'])) {
                return "Le score de Matching analyse vos compétences (comme PHP, Laravel, ou Communication) et les compare aux exigences pointues des entreprises. Enrichissez votre CV pour le booster significativement !";
            }
        }

        // Mots-clés Entreprise spécifiques
        if ($role === 'entreprise') {
            if (Str::contains($message, ['offre', 'publier', 'créer', 'visibilité', 'annonce'])) {
                return "Vos offres reflètent le dynamisme de votre structure. Publiez-en de nouvelles via 'Mes offres' en détaillant les langages exigés. Cela optimise considérablement notre tri automatisé des candidats.";
            }
            if (Str::contains($message, ['candidat', 'profil', 'etudiant', 'cv', 'trier'])) {
                return "La plateforme filtre intelligemment pour vous ! L'onglet 'Candidats (IA)' présente une hiérarchie précise des meilleurs profils. Vous gagnez du temps en ciblant le top 5 d'emblée.";
            }
            if (Str::contains($message, ['candidature', 'accepter', 'refuser', 'gérer'])) {
                return "La gestion fine de votre vivier se pilote depuis 'Candidatures'. Pensez à réagir rapidement (même par un refus respectueux) : cela bonifie votre marque employeur.";
            }
        }

        // Mots-clés Admin spécifiques
        if ($role === 'admin') {
            if (Str::contains($message, ['utilisateur', 'user', 'bloquer', 'ajouter', 'droits'])) {
                return "Le volet sécurité et habilitations se pilote via 'Utilisateurs'. Vous y maîtrisez la conformité des comptes étudiants et recruteurs.";
            }
            if (Str::contains($message, ['statistique', 'stats', 'dashboard', 'global'])) {
                return "Sur le tableau de bord, la vision macro vous aide à piloter la croissance : surveillez la volumétrie d'offres et la fluidité des candidatures.";
            }
        }

        // Global Platform Keywords
        if (Str::contains($message, ['notification', 'alerte', 'cloche', 'email'])) {
             return "L'icône en cloche (en haut à droite) centralise vos notifications critiques en temps réel (nouvelle candidature, état d'avancement...). Gardez-y toujours un œil attentif.";
        }
        if (Str::contains($message, ['profil', 'mot de passe', 'compte', 'paramètre', 'déconnexion'])) {
             return "Concevez votre espace selon vos envies : cliquez sur votre nom (en haut à droite) pour affiner vos préférences sécuritaires et modifier votre mot de passe.";
        }

        // Réponse par défaut orientée contexte
        return "Je suis votre conseiller intelligent de bord. " . $this->getContextualAdvice($role, $url);
    }

    private function getGreeting($role): string
    {
        return match($role) {
            'etudiant' => "Bonjour ! Je suis votre coach carrière virtuel. Souhaitez-vous des recommandations pour valoriser votre CV ou explorer de nouvelles opportunités de stage ?",
            'entreprise' => "Bonjour ! Prêt à recruter les talents de demain ? Indiquez-moi vos besoins pour que je puisse cibler virtuellement votre processus.",
            'admin' => "Bonjour Administrateur. Système cloud stable à 100%. Avez-vous une mission de supervision spécifique aujourd'hui ?",
            default => "Bonjour et bienvenue dans un environnement intelligent ! En quoi puis-je dynamiser votre navigation ?"
        };
    }

    private function getContextualAdvice($role, $url): string
    {
        $url = strtolower($url);
        
        if ($role === 'etudiant') {
            if (Str::contains($url, 'dashboard')) return "Votre tableau de bord centralise vos KPI de carrière. Étape une : assurez-vous que vos technos clés sont importées pour que l'algorithme vous propulse au sommet.";
            if (Str::contains($url, 'offres')) return "Sélection stratégique requise : fiez-vous au 'Score Match'. Ne visez pas seulement de tout choisir, ciblez qualitativement les annonces à plus de 70%.";
            if (Str::contains($url, 'cv')) return "Ce module est le cœur de votre visibilité. En détectant vos technos, le système vous rend virtuellement détectable par les classements des recruteurs.";
            if (Str::contains($url, 'match')) return "Ici, l'AI Matching croise mathématiquement vos mots-clés avec les attendus. Affinez continuellement votre CV pour tendre vers le 100% parfait.";
            if (Str::contains($url, 'candidatures')) return "L'historique détaillé de votre prospection. Relisez régulièrement vos suivis et n'attendez pas de réponse pour continuer vos candidatures ciblées.";
            if (Str::contains($url, 'ranking')) return "Le classement illustre l'intégration de votre profil dans notre écosystème. Une compétition saine qui révèle aux entreprises les talents les plus engagés !";
        }

        if ($role === 'entreprise') {
            if (Str::contains($url, 'dashboard')) return "Vue d'ensemble de votre marque RH en temps réel. Augmentez la rotation de vos offres pour figurer en recommandation auprès de nos meilleurs profils.";
            if (Str::contains($url, 'offres')) return "La précision attire l'excellence. Détaillez rigoureusement la stack technique attendue pour que notre extraction oriente automatiquement les profils adéquats.";
            if (Str::contains($url, 'candidats')) return "Sur ce 'Pool' IA exploratoire, l'adéquation profil-annonce est déjà prémâchée. Filtrez en tri décroissant pour des entretiens à haut potentiel.";
            if (Str::contains($url, 'candidatures')) return "Le workflow décisif. En rejetant aimablement ou en validant avec célérité, l'algorithme valorisera l'efficacité de vos processus RH.";
        }

        if ($role === 'admin') {
            if (Str::contains($url, 'users')) return "Interface modératrice fondamentale. Garantissez l'intégrité de la data en suspendant les comptes fictifs et en rassurant les nouveaux inscrits.";
            if (Str::contains($url, 'offres')) return "Plateforme de validation éthique. Auditez ponctuellement la qualité sémantique des offres pour conserver ce haut niveau de prestige applicatif.";
            if (Str::contains($url, 'candidatures')) return "Vue aérienne des correspondances. Veillez à ce que le cycle d'embauche se poursuive avec fluidité technique globale.";
        }

        // Generic fallback
        return "L'approche la plus efficiente est souvent la plus simple : observez l'indicateur visuel sur votre page courante, ou naviguez doucement via le menu de contrôle premium.";    }
}
