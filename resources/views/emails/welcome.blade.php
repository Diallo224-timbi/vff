<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur notre plateforme</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #333333;
        }
        .highlight {
            color: #e74c3c;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888888;
            text-align: center;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #e74c3c;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue sur notre plateforme </h1>
        <p>Bonjour <strong>{{ $user->prenom }}</strong>,</p>
        <p>
            Nous sommes heureux de vous accueillir sur notre plateforme multi-acteurs dédiée à la lutte contre les violences faites aux femmes dans le département des Alpes‑Maritimes.
        </p>
        <p class="font-semibold text-blue-700">
            Votre compte a bien été créé. Un administrateur devra le valider avant que vous puissiez accéder pleinement à la plateforme.
        </p>

        <p class="footer">
            Merci de votre engagement pour cette cause essentielle.<br>
            L’équipe de la plateforme.
        </p>
    </div>
</body>
</html>
