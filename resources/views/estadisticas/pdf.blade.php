<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Estadísticas de entrenamiento</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; padding: 24px; color: #3F403A; }
        .header { display: flex; align-items: center; gap: 14px; margin-bottom: 6px; }
        .header div.logo { width: 48px; height: 48px; background: #3F403A; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #F2BF5E; font-weight: bold; font-size: 18px; flex-shrink: 0; }
        .header h1 { margin: 0; font-size: 22px; color: #3F403A; }
        .subtitle { text-align: center; font-size: 12px; color: #A68851; margin-bottom: 6px; }
        .profile-box { background: #F2DBAE; border: 1px solid #F2BF5E; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 11px; color: #3F403A; border-left: 4px solid #F2BF5E; }
        .profile-box strong { color: #3F403A; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        th { background-color: #3F403A; color: #F2BF5E; padding: 8px 10px; text-align: left; font-size: 11px; }
        td { padding: 6px 10px; border-bottom: 1px solid #F2DBAE; font-size: 11px; color: #3F403A; }
        tr:nth-child(even) { background-color: #F2F2F2; }
        .dia-header { background-color: #F2DBAE; }
        .dia-header td { padding: 8px 10px; border-bottom: 2px solid #F2BF5E; font-weight: bold; font-size: 13px; color: #3F403A; }
        .footer { text-align: center; font-size: 10px; color: #A68851; margin-top: 20px; padding-top: 10px; border-top: 2px solid #F2BF5E; }
        .empty-msg { text-align: center; color: #A68851; font-size: 13px; padding: 30px 0; }
        .badge { display: inline-block; background: #F2BF5E; color: #3F403A; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-secondary { background: #A68851; color: #F2F2F2; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">ES</div>
        <h1>Entrestats</h1>
    </div>
    <p class="subtitle">📊 Estadísticas de entrenamiento — Del {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</p>

    <div class="profile-box">
        <strong>Nombre:</strong> {{ $userName }} &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Email:</strong> {{ $userEmail }} &nbsp;&nbsp;|&nbsp;&nbsp;
        @if($userSexo)<strong>Sexo:</strong> {{ ucfirst($userSexo) }} &nbsp;&nbsp;|&nbsp;&nbsp;@endif
        <strong>Generado:</strong> {{ date('d/m/Y H:i') }}
    </div>

    @php $totalEjercicios = 0; $totalVolumen = 0; @endphp
    @forelse ($data as $dia)
    <table>
        <tr class="dia-header"><td colspan="6">🗓 {{ \Carbon\Carbon::parse($dia['dia'])->format('d/m/Y') }}</td></tr>
        <tr>
            <th>Grupo</th>
            <th>Ejercicio</th>
            <th style="text-align:center;">Peso</th>
            <th style="text-align:center;">Series</th>
            <th style="text-align:center;">Reps</th>
            <th style="text-align:center;">Vol.</th>
        </tr>
        @foreach ($dia['ejercicios'] as $ej)
            @php
                $volumen = ($ej['peso'] ?? 0) * ($ej['series'] ?? 0) * ($ej['reps'] ?? 0);
                $totalEjercicios++;
                $totalVolumen += $volumen;
            @endphp
            <tr>
                <td><span class="badge badge-secondary">{{ $ej['grupo'] }}</span></td>
                <td>{{ $ej['ejercicio'] }}</td>
                <td style="text-align:center;">{{ $ej['peso'] ?? '-' }}</td>
                <td style="text-align:center;">{{ $ej['series'] ?? '-' }}</td>
                <td style="text-align:center;">{{ $ej['reps'] ?? '-' }}</td>
                <td style="text-align:center; font-weight:bold;">{{ number_format($volumen, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
    @empty
    <p class="empty-msg">No hay registros de entrenamiento en este rango de fechas.</p>
    @endforelse

    <p class="footer">
        <strong>Total:</strong> {{ $totalEjercicios }} registros en {{ count($data) }} días —
        <strong>Volumen total:</strong> {{ number_format($totalVolumen, 0, ',', '.') }} kg
    </p>
</body>
</html>