<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Compte activé</title>
</head>
<body>
    <h2>Bonjour {{ $user->name }},</h2>

    <p>
        Votre compte a été activé par l’administrateur.
    </p>

    <p>
        Vous pouvez maintenant vous connecter en cliquant sur le lien ci-dessous :
    </p>

    <p>
        <a href="{{ url('/login') }}">Se connecter</a>
    </p>

    <p>
        Merci,<br>
        L’équipe
    </p>
</body>
</html>
