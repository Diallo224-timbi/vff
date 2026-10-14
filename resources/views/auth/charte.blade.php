@extends('base')
@section('title', 'Charte de bon usage')
@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-teal-700 to-cyan-700 text-white p-6">
            <h1 class="text-2xl font-bold">
                Charte de bon usage de la plateforme multi-acteurs de lutte contre les violences faites aux femmes
            </h1>
            <p class="mt-2 text-sm opacity-90">
                Cette charte fixe les règles de bon usage de la plateforme pour chacun de ses modules.
            </p>
        </div>
        <!-- Contenu -->
        <div class="p-6 overflow-y-auto max-h-[75vh] text-gray-700">
            <!-- 1. Préambule et objet -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">1. Préambule et objet</h2>
                <p>
                    Cette charte fixe les règles de bon usage de la plateforme multi-acteurs de lutte contre les
                    violences faites aux femmes ; chaque utilisateur l'accepte avant de recevoir son accès.
                </p>
                <p class="mt-2">
                    La plateforme s'inscrit dans le Schéma départemental de lutte contre les violences faites aux
                    femmes des Alpes-Maritimes, porté par la Préfecture, les parquets de Nice et de Grasse, la DDETS,
                    les collectivités et les associations spécialisées. Elle répond aux actions 1.2 (améliorer
                    l'interconnaissance des acteurs) et 2.1 (renforcer la coordination interprofessionnelle).
                </p>
                <p class="mt-2">
                    <strong>Public concerné.</strong> La plateforme est réservée aux professionnels des structures
                    signataires du Schéma : services de l'État, forces de l'ordre, justice, collectivités,
                    associations, structures sanitaires et médico-sociales, réseaux territoriaux VIF. Elle n'est pas
                    ouverte au grand public.
                </p>
                <p class="mt-2">
                    <strong>Ce que la plateforme est.</strong> Un outil de partage d'informations, de ressources et de
                    pratiques entre professionnels : annuaire et cartographie, espace documentaire, forum, agenda
                    partagé, projets et dispositifs.
                </p>
                <p class="mt-2">
                    <strong>Ce que la plateforme n'est pas.</strong> Elle n'est pas un outil de suivi individuel des
                    victimes, ni un dispositif d'urgence ou de signalement.
                </p>
            </section>
            <!-- 2. Principes communs -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">2. Principes communs à tous les modules</h2>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-3">
                    <strong class="text-red-700">Important :</strong>
                    Il est strictement interdit de saisir des informations permettant d'identifier une victime.
                </div>
                <p class="mb-2">
                    Cinq règles s'appliquent partout sur la plateforme, quel que soit le module utilisé.
                </p>
                <ol class="list-decimal pl-6 space-y-2">
                    <li>
                        <strong>Aucune donnée sur les victimes.</strong> Il est interdit de saisir un nom, un prénom,
                        une adresse, une date de naissance, un numéro de téléphone, une photo ou tout élément
                        permettant d'identifier une victime, un auteur ou un enfant. Une situation ne peut être
                        évoquée qu'en termes généraux et anonymisés.
                    </li>
                    <li>
                        <strong>Usage strictement professionnel.</strong> Les contenus et les contacts de la
                        plateforme servent la coordination des acteurs, pas un autre objectif (commercial, militant,
                        personnel, religieux).
                    </li>
                    <li>
                        <strong>Confidentialité.</strong> Les informations consultées restent dans le cercle des
                        professionnels autorisés. Aucune capture d'écran, extraction ou diffusion de contenus à
                        l'extérieur sans l'accord de leur auteur.
                    </li>
                    <li>
                        <strong>Fiabilité et bienveillance.</strong> Chacun publie des informations exactes, à jour et
                        sourcées, et s'exprime avec respect envers les autres acteurs, leurs missions et leurs
                        pratiques.
                    </li>
                    <li>
                        <strong>Conformité RGPD.</strong> Les seules données personnelles présentes sont celles des
                        professionnels (identité, fonction, structure, coordonnées professionnelles). Chacun peut
                        exercer ses droits d'accès, de rectification et de suppression auprès de l'administrateur de
                        la plateforme.
                    </li>
                </ol>
                <p class="mt-3">L'utilisateur est responsable des contenus qu'il publie sous son compte.</p>
            </section>
            <!-- 3. Accès, comptes et gestion des utilisateurs -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">3. Module Accès, comptes et gestion des utilisateurs</h2>
                <p>
                    Chaque compte est nominatif et validé manuellement par un administrateur avant d'être activé :
                    c'est ce qui garantit que seuls des professionnels des structures signataires accèdent à la
                    plateforme.
                </p>
                <h3 class="font-semibold mt-3 mb-1">Je m'engage à :</h3>
                <ul class="list-disc pl-6 space-y-1">
                    <li>m'inscrire avec mon adresse électronique professionnelle et renseigner des informations exactes (identité, structure, fonction, coordonnées) ;</li>
                    <li>choisir un mot de passe fort, unique, que je ne partage avec personne ;</li>
                    <li>activer la double authentification dès qu'elle est proposée ;</li>
                    <li>me déconnecter après usage, surtout sur un poste partagé ou un appareil personnel ;</li>
                    <li>signaler à l'administrateur tout changement de poste ou de structure, afin que mon profil et mes droits soient mis à jour ;</li>
                    <li>informer l'administrateur du départ d'un collègue qui disposait d'un accès.</li>
                </ul>
                <h3 class="font-semibold mt-3 mb-1">Je m'interdis de :</h3>
                <ul class="list-disc pl-6 space-y-1">
                    <li>prêter, partager ou revendre mon accès, ou utiliser le compte d'un tiers ;</li>
                    <li>créer un compte pour une personne qui n'appartient pas à une structure signataire ;</li>
                    <li>tenter de contourner mes droits d'accès ou ceux d'un autre utilisateur.</li>
                </ul>
                <h3 class="font-semibold mt-3 mb-1">Rôles et administration</h3>
                <p>
                    La plateforme prévoit quatre profils d'utilisateurs : administrateur, responsable d'organisme,
                    responsable de structure et utilisateur. Les droits d'accès et les actions autorisées sont
                    définis en fonction du profil attribué. Chaque utilisateur ne peut consulter, créer, modifier ou
                    valider que les informations relevant de ses habilitations.
                </p>
                <p class="mt-2">
                    L'administrateur assure la gestion générale de la plateforme. À ce titre, il peut valider,
                    modifier ou bloquer des comptes. Toute décision de blocage est systématiquement motivée et
                    notifiée à la personne concernée. Les données personnelles auxquelles il accède sont traitées
                    avec le même niveau de confidentialité que l'ensemble des informations de la plateforme et
                    uniquement dans le cadre de ses missions d'administration.
                </p>
                <p class="mt-2">
                    Le responsable d'organisme est chargé de la gestion de son organisme ainsi que des structures qui
                    y sont rattachées. Le responsable de structure, quant à lui, assure la gestion et le suivi des
                    informations relatives à sa propre structure.
                </p>
            </section>
            <!-- 4. Annuaire et cartographie -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">4. Module Annuaire et cartographie</h2>
                <p>
                    L'annuaire n'est utile que si ses fiches sont exactes : chaque structure est responsable de la
                    sienne et vérifie les modifications avant publication.
                </p>
                <h3 class="font-semibold mt-3 mb-1">Je m'engage à :</h3>
                <ul class="list-disc pl-6 space-y-1">
                    <li>vérifier que la fiche de ma structure est complète (missions, public accueilli, territoire couvert, contacts, horaires) ;</li>
                    <li>signaler toute modification (changement de contact, d'horaires, de périmètre) dans les meilleurs délais, et relire ma fiche au moins une fois par an ;</li>
                    <li>n'indiquer que des coordonnées professionnelles, validées par ma structure ;</li>
                    <li>utiliser les filtres (type d'acteur, zone géographique) et la carte pour orienter vers le bon interlocuteur ;</li>
                    <li>signaler une fiche qui me paraît erronée ou périmée, même si elle ne concerne pas ma structure.</li>
                </ul>
                <h3 class="font-semibold mt-3 mb-1">Je m'interdis de :</h3>
                <ul class="list-disc pl-6 space-y-1">
                    <li>modifier la fiche d'une autre structure sans son accord ;</li>
                    <li>utiliser l'annuaire pour de la prospection commerciale ou pour constituer des listes de diffusion extérieures ;</li>
                    <li>indiquer sur la carte l'adresse d'un lieu d'hébergement ou de mise à l'abri qui doit rester confidentielle.</li>
                </ul>
            </section>
            <!-- 5. Espace documentaire -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">5. Module Espace documentaire</h2>
                <p>
                    L'espace documentaire rassemble les procédures, fiches réflexes, outils métiers et ressources
                    utiles aux professionnels : un document n'est publié que s'il est fiable, à jour et exempt de
                    toute donnée sur une situation réelle.
                </p>
                <h3 class="font-semibold mt-3 mb-1">Je m'engage à :</h3>
                <ul class="list-disc pl-6 space-y-1">
                    <li>ne déposer que des documents dont je suis l'auteur ou que j'ai le droit de diffuser, en citant la source et l'auteur ;</li>
                    <li>anonymiser entièrement tout cas pratique, retour d'expérience ou exemple de situation avant dépôt ;</li>
                    <li>renseigner les métadonnées  et classer le document dans la bonne catégorie ;</li>
                    <li>indiquer la version et la date du document, et signaler ceux qui deviennent obsolètes ;</li>
                    <li>vérifier la lisibilité et l'accessibilité du fichier (format courant, texte exploitable) ;</li>
                    <li>m'assurer que le document est destiné à un usage professionnel partagé.</li>
                </ul>
                <h3 class="font-semibold mt-3 mb-1">Je m'interdis de :</h3>
                <ul class="list-disc pl-6 space-y-1">
                    <li>déposer un dossier individuel, un procès-verbal, une main courante, un certificat médical ou tout document nominatif ;</li>
                    <li>publier un document confidentiel, interne ou protégé par le droit d'auteur sans autorisation ;</li>
                    <li>diffuser à l'extérieur les documents téléchargés lorsqu'ils sont réservés au réseau.</li>
                </ul>
            </section>
            <!-- 6. Forum professionnel -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">6. Module Forum professionnel</h2>
                <p>
                    Le forum sert à poser une question à ses pairs, partager une pratique ou clarifier une procédure :
                    on y échange sur des sujets professionnels, jamais sur des personnes.
                </p>
                <h3 class="font-semibold mt-3 mb-1">Je m'engage à :</h3>
                <ul class="list-disc pl-6 space-y-1">
                    <li>choisir la bonne catégorie et un titre clair avant de créer un sujet, et consulter les sujets existants pour éviter les doublons ;</li>
                    <li>formuler mes questions de façon générale et anonymisée ;</li>
                    <li>répondre de manière factuelle, courtoise et constructive, en citant mes sources (texte, procédure, dispositif) lorsque je donne une information ;</li>
                    <li>respecter les compétences et le cadre d'intervention de chaque structure ;</li>
                    <li>m'exprimer en mon nom de professionnel et distinguer ce qui relève de ma pratique de ce qui relève d'une règle officielle ;</li>
                    <li>signaler un message inapproprié plutôt que d'y répondre sur le même ton.</li>
                </ul>
                <h3 class="font-semibold mt-3 mb-1">Je m'interdis de :</h3>
                <ul class="list-disc pl-6 space-y-1">
                    <li>mentionner l'identité ou toute information permettant d'identifier une victime, un auteur de violences, un enfant ou un proche, y compris par recoupement (lieu, date, profession, circonstances rares) ;</li>
                    <li>publier des propos discriminatoires, injurieux, diffamatoires ou culpabilisants envers les victimes ;</li>
                    <li>critiquer nommément un professionnel ou une structure, ou régler un différend sur le forum ;</li>
                    <li>utiliser le forum pour de la publicité, de la prospection ou des messages sans lien avec la lutte contre les violences faites aux femmes ;</li>
                    <li>demander ou donner un avis juridique ou médical individuel sur une situation précise : le forum ne remplace ni un avis d'expert, ni les procédures en vigueur, ni un dispositif d'urgence.</li>
                </ul>
                <p class="mt-3">
                    <strong>Modération.</strong> L'administrateur peut supprimer un message qui contrevient à la
                    charte, et en informer son auteur. Un message qui contient des informations sur une victime est
                    retiré immédiatement.
                </p>
            </section>
            <!-- 7. Agenda partagé -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">7. Module Agenda partagé</h2>
                <p>
                    L'agenda rassemble les réunions, formations, ateliers et temps forts du réseau : il n'est fiable
                    que si chaque événement est complet, à jour et réservé à un usage professionnel.
                </p>
                <h3 class="font-semibold mt-3 mb-1">Je m'engage à :</h3>
                <ul class="list-disc pl-6 space-y-1">
                    <li>renseigner pour chaque événement un titre, une date, un horaire, un lieu ou un lien, le public visé, l'organisateur et un contact ;</li>
                    <li>préciser si l'événement est ouvert à tous les professionnels du réseau ou réservé à certains profils, et s'il existe un nombre de places limité ;</li>
                    <li>publier assez tôt pour laisser aux acteurs le temps de s'organiser ;</li>
                    <li>mettre l'événement à jour dès qu'il est déplacé, modifié ou annulé ;</li>
                    <li>consulter l'agenda régulièrement pour éviter les chevauchements entre actions du réseau.</li>
                </ul>
                <h3 class="font-semibold mt-3 mb-1">Je m'interdis de :</h3>
                <ul class="list-disc pl-6 space-y-1">
                    <li>publier un événement à caractère commercial, partisan ou sans lien avec le périmètre de la plateforme ;</li>
                    <li>indiquer dans l'événement des informations personnelles sur des victimes.</li>
                </ul>
            </section>
            <!-- 8. Sécurité, journalisation et manquements -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">8. Sécurité, journalisation et manquements</h2>
                <p>
                    <strong>Journalisation.</strong> Les connexions et les actions sensibles (modification,
                    publication, suppression, validation de compte) sont enregistrées automatiquement. Ces traces
                    servent à la sécurité et à la transparence de la plateforme ; elles ne sont consultées que par les
                    personnes habilitées et conservées pour une durée limitée.
                </p>
                <p class="mt-2">
                    <strong>Bonnes pratiques de sécurité.</strong> Je n'utilise la plateforme que depuis un appareil
                    et un réseau de confiance, je tiens mon navigateur à jour, je ne conserve pas de documents
                    téléchargés plus longtemps que nécessaire, et je me méfie des messages qui me demandent mon mot de
                    passe.
                </p>
                <p class="mt-2">
                    <strong>Signalement.</strong> Je signale sans délai à l'administrateur tout incident : compte
                    compromis, perte d'un appareil connecté, donnée sur une victime publiée par erreur, contenu
                    inapproprié, fiche ou document erroné. Une donnée publiée par erreur est retirée dès son
                    signalement, et la personne qui l'a signalée n'a pas à craindre de reproche.
                </p>
                <p class="mt-2">
                    <strong>Manquements.</strong> En cas de non-respect de la charte, l'administrateur peut, selon la
                    gravité et après avoir entendu la personne concernée :
                </p>
                <ol class="list-decimal pl-6 space-y-1 mt-1">
                    <li>rappeler la règle par message ;</li>
                    <li>retirer ou modifier le contenu concerné ;</li>
                    <li>restreindre temporairement les droits du compte ;</li>
                    <li>bloquer le compte, avec un motif communiqué à l'intéressé.</li>
                </ol>
                <p class="mt-2">
                    La publication d'une donnée permettant d'identifier une victime ou une atteinte grave à la
                    sécurité de la plateforme entraîne un blocage immédiat du compte et l'information de la structure
                    de l'utilisateur ; la violation du RGPD peut aussi engager la responsabilité personnelle de son
                    auteur.
                </p>
            </section>
            <!-- 9. Évolution de la charte et engagement -->
            <section class="mb-6">
                <h2 class="text-lg font-semibold text-teal-700 mb-2">9. Évolution de la charte et engagement</h2>
                <p>
                    Cette charte est acceptée à l'inscription et s'applique dès l'activation du compte, qui reste
                    soumise à la validation d'un administrateur. Elle peut évoluer avec la plateforme et le Schéma
                    départemental ; toute modification importante est notifiée aux utilisateurs, qui doivent alors la
                    ré-accepter pour continuer à utiliser leur compte.
                </p>
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded mt-4">
                    <strong>Acceptation de la charte :</strong>
                    l'acceptation se fait en ligne, en cochant la case prévue lors de l'inscription. Sans cette case
                    cochée, la demande de compte ne peut pas être envoyée.
                    <p class="mt-2 italic">
                        « J'ai lu et j'accepte la charte de bon usage de la plateforme. Je m'engage à respecter ses
                        règles pour l'ensemble des modules, et à ne jamais y saisir de donnée permettant d'identifier
                        une victime. »
                    </p>
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