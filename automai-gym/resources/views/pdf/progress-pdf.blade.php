<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Historial de Progreso - AutomAI Gym</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            margin: 1.5cm;
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #BE9155;
        }

        .header h1 {
            font-size: 36px;
            margin: 0;
            color: #466248;
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        .header p {
            font-size: 14px;
            color: #BE9155;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .user-info {
            margin-bottom: 30px;
        }

        .user-info h2 {
            font-size: 20px;
            color: #466248;
            margin-bottom: 5px;
        }

        .user-info p {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #f8f6f2;
            color: #466248;
            text-align: left;
            padding: 12px 10px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #BE9155;
        }

        td {
            padding: 12px 10px;
            font-size: 13px;
            border-bottom: 1px solid #eee;
        }

        .date-col {
            font-weight: bold;
            color: #466248;
        }

        .footer {
            position: fixed;
            bottom: 1cm;
            left: 0;
            right: 0;
            height: 1cm;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            background-color: #466248;
            color: #fff;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>AUTOM AI GYM</h1>
        <p>Premium Fitness Experience</p>
    </div>

    <div class="user-info">
        <h2>Informe de Progreso</h2>
        <p>Usuario: <strong>{{ $user->nombre_mostrado_usuario }}</strong></p>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Peso (kg)</th>
                <th>Cintura (cm)</th>
                <th>Pecho (cm)</th>
                <th>Cadera (cm)</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td class="date-col">{{ $record->fecha_registro->format('d/m/Y') }}</td>
                    <td>{{ $record->peso_kg_registro ? number_format($record->peso_kg_registro, 2) : '—' }}</td>
                    <td>{{ $record->cintura_cm_registro ? number_format($record->cintura_cm_registro, 2) : '—' }}</td>
                    <td>{{ $record->pecho_cm_registro ? number_format($record->pecho_cm_registro, 2) : '—' }}</td>
                    <td>{{ $record->cadera_cm_registro ? number_format($record->cadera_cm_registro, 2) : '—' }}</td>
                    <td style="font-size: 11px; color: #666;">{{ $record->notas_progreso ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} AUTOM AI GYM — Transformando tu potencial.
    </div>
</body>

</html>
