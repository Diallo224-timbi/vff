<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Formulaire Structure</title>
    <style>
        /* Format A4 */
        @page { size: A4; margin: 20mm; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            padding: 20mm;
            margin: auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            text-decoration: underline;
            font-size: 18px;
        }

        .field {
            margin-bottom: 20px;
        }

        .label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .input-space {
            border: 2px solid #000;
            width: 100%;
            height: 25px;
            padding: 2px 5px;
            box-sizing: border-box;
        }

        .textarea-space {
            border: 2px solid #000;
            width: 100%;
            height: 80px;
            padding: 5px;
            box-sizing: border-box;
        }

        .signature {
            margin-top: 40px;
        }

        .signature .input-space {
            height: 40px;
        }

        .print-btn {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 8px 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Formulaire de création de structure</h2>

        <div class="field">
            <span class="label">Nom de la structure:</span>
            <div class="input-space">{{ $data['nom_structure'] ?? '' }}</div>
        </div>

        <div class="field">
            <span class="label">Adresse:</span>
            <div class="input-space">{{ $data['adresse'] ?? '' }}</div>
        </div>

        <div class="field">
            <span class="label">Ville:</span>
            <div class="input-space">{{ $data['ville'] ?? '' }}</div>
        </div>

        <div class="field">
            <span class="label">Code postal:</span>
            <div class="input-space">{{ $data['code_postal'] ?? '' }}</div>
        </div>

        <div class="field">
            <span class="label">Nom du responsable:</span>
            <div class="input-space">{{ $data['responsable'] ?? '' }}</div>
        </div>

        <div class="field">
            <span class="label">Email du responsable:</span>
            <div class="input-space">{{ $data['email'] ?? '' }}</div>
        </div>

        <div class="field">
            <span class="label">Description de la structure:</span>
            <div class="textarea-space"></div>
        </div>

        <div class="signature">
            <span class="label">Signature du responsable:</span>
            <div class="input-space"></div>
            <small>(à remplir et signer manuellement)</small>
        </div>
    </div>

    
</body>
<button class="print-btn" onclick="window.print()">Imprimer le formulaire</button>
</html>
