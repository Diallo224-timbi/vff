<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur notre plateforme</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 650px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
            font-size: 26px;
        }

        p {
            font-size: 16px;
            line-height: 1.7;
            color: #333333;
            margin-bottom: 15px;
        }

        .role-box {
            background-color: #f9fafc;
            border-left: 4px solid #2c3e50;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 6px;
        }

        .role-box ul {
            padding-left: 20px;
            margin: 0;
        }

        .role-box li {
            margin-bottom: 8px;
            font-size: 15px;
        }

        .highlight {
            color: #2c3e50;
            font-weight: bold;
        }

        .footer {
            margin-top: 35px;
            font-size: 13px;
            color: #777777;
            text-align: center;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 25px;
            background-color: #2c3e50;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }

        .btn:hover {
            background-color: #1a242f;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Bienvenue sur notre plateforme</h1>

        <p>Bonjour <strong>{{ $user->prenom }}</strong>,</p>

        <p>
            Nous sommes heureux de vous accueillir sur la plateforme multi‑acteurs dédiée à la lutte contre les violences faites aux femmes dans le département des Alpes‑Maritimes.
        </p>

        <p class="highlight">
            Votre compte a bien été créé. Il doit maintenant être validé avant que vous puissiez accéder pleinement à la plateforme.
        </p>

        <div class="role-box">
            <p><strong>Procédure de validation selon votre rôle :</strong></p>
            <ul>
                <li><strong>Responsable d’organisme :</strong> vous devez contacter l’administrateur pour valider votre compte.</li>
                <li><strong>Responsable de structure :</strong> vous devez contacter votre responsable d’organisme.</li>
                <li><strong>Utilisateur simple :</strong> vous devez contacter votre responsable de structure afin qu’il procède à la validation.</li>
            </ul>
        </div>

        <p>
            Pour toute question ou assistance, vous pouvez nous contacter à l’adresse suivante :
            <br><strong>contact@plateforme-vff.fr</strong>
        </p>

        <p>
            Nous vous remercions pour votre engagement et votre volonté de contribuer à cette cause essentielle. Votre participation est précieuse et nous sommes ravis de vous compter parmi nous.
        </p>

        <p class="footer">
            Merci de votre engagement.<br>
            L’équipe de la plateforme.
        </p>
    </div>
</body>
</html>
