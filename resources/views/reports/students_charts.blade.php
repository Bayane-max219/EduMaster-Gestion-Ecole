<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphiques Élèves - EduMaster</title>
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
        <h1 class="title">Graphiques des Élèves</h1>
        @php
            $labels = $labels ?? [];
            $data = $data ?? [];
            if (is_object($labels) && method_exists($labels,'toArray')) $labels = $labels->toArray();
            if (is_object($data) && method_exists($data,'toArray')) $data = $data->toArray();
            $max = 1; foreach($data as $v){ $max = max($max, (int)$v); }
            $genderLabels = $genderLabels ?? [];
            $genderData = $genderData ?? [];
            if (is_object($genderLabels) && method_exists($genderLabels,'toArray')) $genderLabels = $genderLabels->toArray();
            if (is_object($genderData) && method_exists($genderData,'toArray')) $genderData = $genderData->toArray();
            $gmax = 1; foreach($genderData as $v){ $gmax = max($gmax, (int)$v); }
        @endphp

        <div class="card">
            <div class="section">Répartition par classe</div>
            @forelse($labels as $i => $lab)
                @php $val = (int)($data[$i] ?? 0); $pct = round(($val * 100) / ($max ?: 1)); @endphp
                <div class="bar-row">
                    <div class="bar-label">{{ $lab ?: 'Sans classe' }}</div>
                    <div class="bar" style="width: {{ max(2,$pct) }}%;"></div>
                    <div class="bar-val">{{ $val }}</div>
                </div>
            @empty
                <div style="color:#666;">Aucune donnée</div>
            @endforelse
        </div>

        <div class="card">
            <div class="section">Répartition par genre</div>
            @forelse($genderLabels as $i => $lab)
                @php $val = (int)($genderData[$i] ?? 0); $pct = round(($val * 100) / ($gmax ?: 1)); @endphp
                <div class="bar-row">
                    <div class="bar-label">{{ $lab ?: 'Inconnu' }}</div>
                    <div class="bar" style="width: {{ max(2,$pct) }}%; background:#FF8C42;"></div>
                    <div class="bar-val">{{ $val }}</div>
                </div>
            @empty
                <div style="color:#666;">Aucune donnée</div>
            @endforelse
        </div>
    </div>
</body>
</html>
