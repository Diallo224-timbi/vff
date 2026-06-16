@extends('base')

@section('title', 'Charte d\'utilisation')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">

        <!-- En-tête -->
        <div class="bg-gradient-to-r from-teal-700 to-cyan-700 text-white p-6">
            <h1 class="text-2xl font-bold">
                Charte d'utilisation de la Plateforme Multi-Acteurs VFF 06
            </h1>
            <p class="mt-2 text-sm opacity-90">
                Cette charte définit les règles de bonne utilisation de la plateforme et les engagements de ses utilisateurs.
            </p>
        </div>

        <!-- Contenu -->
        <div class="p-6 overflow-y-auto max-h-[75vh] text-gray-700">

            <section class="mb-6">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">Préambule</h2>
                <p>
                    La Plateforme Multi-Acteurs VFF 06 est un espace numérique collaboratif destiné aux professionnels
                    engagés dans la prévention, la protection et l'accompagnement des femmes victimes de violences.
                </p>
                <p class="mt-2">
                    Son objectif est de favoriser la coopération, le partage d'informations professionnelles
                    et le renforcement des connaissances entre les différents acteurs du territoire.
                </p>
            </section>

            <section class="mb-6">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">1. Principes fondamentaux</h2>
                <ul class="list-disc pl-6 space-y-1">
                    <li>Adopter un comportement respectueux et bienveillant.</li>
                    <li>Favoriser un climat de confiance et de coopération.</li>
                    <li>Respecter les missions et compétences de chaque acteur.</li>
                    <li>Utiliser la plateforme exclusivement dans un cadre professionnel.</li>
                </ul>
            </section>

            <section class="mb-6">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">2. Confidentialité et RGPD</h2>

                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-3">
                    <strong class="text-red-700">Important :</strong>
                    Il est strictement interdit de publier des informations permettant d'identifier une victime.
                </div>

                <ul class="list-disc pl-6 space-y-1">
                    <li>Aucun nom, prénom, adresse, téléphone ou photographie ne doit être diffusé.</li>
                    <li>Les échanges doivent porter uniquement sur les pratiques professionnelles et les dispositifs existants.</li>
                    <li>Toute publication non conforme doit être signalée aux administrateurs.</li>
                </ul>
            </section>

            <section class="mb-6">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">3. Utilisation du forum professionnel</h2>

                <ul class="list-disc pl-6 space-y-1">
                    <li>Publier uniquement des contenus en lien avec les objectifs de la plateforme.</li>
                    <li>Respecter les règles de courtoisie et de respect mutuel.</li>
                    <li>Privilégier des échanges constructifs et professionnels.</li>
                    <li>Ne pas diffuser de contenus injurieux, discriminatoires ou illicites.</li>
                    <li>Ne jamais exposer une situation permettant d'identifier une victime.</li>
                </ul>
            </section>

            <section class="mb-6">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">4. Partage des ressources documentaires</h2>

                <ul class="list-disc pl-6 space-y-1">
                    <li>Partager des ressources fiables et pertinentes.</li>
                    <li>Vérifier l'exactitude des informations avant publication.</li>
                    <li>Respecter les droits d'auteur et les licences d'utilisation.</li>
                    <li>Classer les documents dans les catégories appropriées.</li>
                    <li>Mettre à jour les contenus devenus obsolètes.</li>
                </ul>
            </section>

            <section class="mb-6">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">5. Sécurité des accès</h2>

                <ul class="list-disc pl-6 space-y-1">
                    <li>Conserver la confidentialité de ses identifiants.</li>
                    <li>Utiliser un mot de passe sécurisé.</li>
                    <li>Ne jamais partager son compte avec un tiers.</li>
                    <li>Signaler immédiatement toute utilisation suspecte.</li>
                </ul>
            </section>

            <section class="mb-6">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">6. Responsabilités</h2>

                <ul class="list-disc pl-6 space-y-1">
                    <li>Chaque utilisateur est responsable des contenus qu'il publie.</li>
                    <li>Les administrateurs peuvent modérer ou supprimer les contenus non conformes.</li>
                    <li>Un compte peut être suspendu ou supprimé en cas de non-respect de la charte.</li>
                </ul>
            </section>

            <section class="mb-6">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">7. Acceptation de la charte</h2>

                <p>
                    L'accès à la Plateforme Multi-Acteurs VFF 06 implique l'acceptation pleine et entière
                    de la présente charte.
                </p>

                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded mt-4">
                    <strong>Engagement :</strong>
                    En créant un compte et en utilisant la plateforme, vous vous engagez à respecter
                    l'ensemble des règles définies dans cette charte.
                </div>
            </section>

        </div>

        <!-- Footer -->
        <div class="bg-gray-100 px-6 py-4 text-right">
            <a href="{{ route('register') }}"
               class="inline-block bg-teal-700 hover:bg-teal-800 text-white px-5 py-2 rounded-lg transition">
                Retour à l'inscription
            </a>
        </div>

    </div>
</div>
@endsection