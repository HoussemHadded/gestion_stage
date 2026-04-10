<x-mail::message>
# Nouvelle Candidature
 
Bonjour **{{ $candidature->offre->entreprise->name ?? 'Entreprise' }}**,

Une nouvelle candidature a été soumise pour votre offre de stage : **{{ $candidature->offre->titre }}**.

**Étudiant(e):** {{ $candidature->student->name }}

<x-mail::button :url="route('entreprise.candidatures.index')">
Voir les candidatures
</x-mail::button>
 
Merci,  
{{ config('app.name') }}
</x-mail::message>
