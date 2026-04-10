<x-mail::message>
# Mise à jour de votre candidature
 
Bonjour **{{ $candidature->student->name }}**,

Le statut de votre candidature pour le poste **{{ $candidature->offre->titre }}** chez **{{ $candidature->offre->entreprise->name ?? 'l\'entreprise' }}** a été mis à jour.

Nouveau statut : **{{ $candidature->statut->label() }}**

<x-mail::button :url="route('student.candidatures.index')">
Consulter mon espace
</x-mail::button>
 
Merci,  
{{ config('app.name') }}
</x-mail::message>
