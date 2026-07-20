<p>Bonjour,</p>

<p>Un nouvel utilisateur vient de s'inscrire, il est en attente de la validation de son compte :</p>

<ul>
    <li><strong>Nom :</strong> {{ $user->name }}</li>
    <li><strong>Prénom :</strong> {{ $user->prenom }}</li>
    <li><strong>Email :</strong> {{ $user->email }}</li>
    <li><strong>Téléphone :</strong> {{ $user->phone }}</li>
    <li><strong>Ville :</strong> {{ $user->ville }}</li>
    <li><strong>Code postal :</strong> {{ $user->code_postal }}</li>
    <li><strong>Structure :</strong> {{ $user->structure->organisme->nom_organisme ?? 'Aucune' }} {{ $user->structure->ville ?? ' - ' }} {{ $user->structure->code_postal ?? ' - ' }} {{ $user->structure->adresse ?? ' - ' }}</li>
</ul>

<p>Cordialement,<br>Votre application</p>
