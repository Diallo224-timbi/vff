<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Annuaire des structures</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 10mm 15mm 10mm;
        }
        .pageNumber:before {
            content: counter(page);
        }
       
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #eaeaea;
            font-weight: bold;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
        }
    </style>
</head>
<body>

<h2>Annuaire des structures</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Adresse</th>
            <th>Ville</th>
            <th>Code Postal</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Responsable</th>
            <th>Membres</th>
        </tr>
    </thead>
    <tbody>
        @foreach($structures as $structure)
        <tr>
            <td>{{ $structure->id }}</td>
            <td>{{ $structure->nom_structure }}</td>
            <td>{{ $structure->description }}</td>
            <td>{{ $structure->adresse }}</td>
            <td>{{ $structure->ville }}</td>
            <td>{{ $structure->code_postal }}</td>
            <td>{{ $structure->longitude }}</td>
            <td>{{ $structure->latitude }}</td>
            <td>{{ $structure->contact }}</td>
            <td>{{ $structure->email }}</td>
            <td>{{ $structure->responsable }}</td>
            <td>{{ $structure->members_count() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<footer>
    Généré le {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    <p>Page <span class="pageNumber"></span> </p>
</footer>



</body>
</html>
