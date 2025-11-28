<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphiques Professeurs - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display:flex; justify-content:space-between; align-items:center; }
        .logo { font-size: 1.2rem; font-weight: bold; }
        .container { max-width: 1100px; margin: 1.5rem auto; padding: 0 1.5rem; }
        .title { font-size: 1.6rem; color:#20B2AA; margin-bottom: .75rem; }
        .card { background:white; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.06); padding:1rem; margin-bottom:1rem; }
        .section { color:#20B2AA; font-weight:700; margin-bottom:.5rem; }
        .bar-row { display:flex; align-items:center; gap:.5rem; margin:.35rem 0; }
        .bar-label { width: 240px; color:#333; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
        .bar { height:12px; background:#20B2AA; border-radius:9999px; min-width:4px; }
        .bar-alt { background:#FF8C42; }
        .bar-val { min-width:48px; text-align:right; color:#111; font-weight:700; }
        @media print { .header { display:none; } body { background:#fff; } .card { box-shadow:none; border:1px solid #eee; } }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div>
            <a href="{{ route('reports.index') }}" style="color:#fff; text-decoration:none; margin-right:1rem;">Rapports</a>
            <button onclick="window.print();" style="background:#fff;color:#20B2AA;border:none;padding:.5rem .75rem;border-radius:6px;cursor:pointer;">🖨️ Imprimer</button>
        </div>
    </div>

    <div class="container">
        <h1 class="title">Graphiques des Professeurs</h1>
        @php
            $statusLabels = $statusLabels ?? [];
            $statusData = $statusData ?? [];
            if (is_object($statusLabels) && method_exists($statusLabels,'toArray')) $statusLabels = $statusLabels->toArray();
            if (is_object($statusData) && method_exists($statusData,'toArray')) $statusData = $statusData->toArray();
            $smax = 1; foreach($statusData as $v){ $smax = max($smax, (int)$v); }
            $specLabels = $specLabels ?? [];
            $specData = $specData ?? [];
            if (is_object($specLabels) && method_exists($specLabels,'toArray')) $specLabels = $specLabels->toArray();
            if (is_object($specData) && method_exists($specData,'toArray')) $specData = $specData->toArray();
            $pmax = 1; foreach($specData as $v){ $pmax = max($pmax, (int)$v); }
        @endphp
        <div class="card">
            <div class="section">Par statut</div>
            @forelse($statusLabels as $i => $lab)
                @php $val = (int)($statusData[$i] ?? 0); $pct = round(($val * 100) / ($smax ?: 1)); @endphp
                <div class="bar-row">
                    <div class="bar-label">{{ $lab ?: 'Inconnu' }}</div>
                    <div class="bar" style="width: {{ max(2,$pct) }}%;"></div>
                    <div class="bar-val">{{ $val }}</div>
                </div>
            @empty
                <div style="color:#666;">Aucune donnée</div>
            @endforelse
        </div>
        <div class="card">
            <div class="section">Par spécialisation</div>
            @forelse($specLabels as $i => $lab)
                @php $val = (int)($specData[$i] ?? 0); $pct = round(($val * 100) / ($pmax ?: 1)); @endphp
                <div class="bar-row">
                    <div class="bar-label">{{ $lab ?: 'Non défini' }}</div>
                    <div class="bar bar-alt" style="width: {{ max(2,$pct) }}%;"></div>
                    <div class="bar-val">{{ $val }}</div>
                </div>
            @empty
                <div style="color:#666;">Aucune donnée</div>
            @endforelse
        </div>
    </div>
</body>
</html>
