<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphiques Exécutifs - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display:flex; justify-content:space-between; align-items:center; }
        .logo { font-size: 1.2rem; font-weight: bold; }
        .container { max-width: 1100px; margin: 1.5rem auto; padding: 0 1.5rem; }
        .title { font-size: 1.6rem; color:#20B2AA; margin-bottom: .75rem; }
        .card { background:white; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.06); padding:1rem; margin-bottom:1rem; }
        .section { color:#20B2AA; font-weight:700; margin-bottom:.5rem; }
        .kpi-row { display:grid; grid-template-columns: 1fr 3fr 80px; align-items:center; gap:.5rem; margin:.35rem 0; }
        .kpi-label { color:#333; }
        .bar { height:12px; background:#20B2AA; border-radius:9999px; min-width:4px; }
        .bar-alt { background:#FF8C42; }
        .kpi-val { text-align:right; color:#111; font-weight:700; }
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
        <h1 class="title">Graphiques Exécutifs</h1>
        @php
            $studentsCount = (int)($studentsCount ?? 0);
            $teachersCount = (int)($teachersCount ?? 0);
            $paymentsTotal = (float)($paymentsTotal ?? 0);
            $feesCount = (int)($feesCount ?? 0);
            $max = max(1, $studentsCount, $teachersCount, $feesCount, (int)$paymentsTotal);
            $pct = function($val, $max){ return max(2, (int)round(($val*100)/($max ?: 1))); };
        @endphp
        <div class="card">
            <div class="section">Indicateurs clés</div>
            <div class="kpi-row">
                <div class="kpi-label">Élèves</div>
                <div class="bar" style="width: {{ $pct($studentsCount,$max) }}%;"></div>
                <div class="kpi-val">{{ number_format($studentsCount, 0, ',', ' ') }}</div>
            </div>
            <div class="kpi-row">
                <div class="kpi-label">Professeurs</div>
                <div class="bar" style="width: {{ $pct($teachersCount,$max) }}%;"></div>
                <div class="kpi-val">{{ number_format($teachersCount, 0, ',', ' ') }}</div>
            </div>
            <div class="kpi-row">
                <div class="kpi-label">Paiements (Ar)</div>
                <div class="bar bar-alt" style="width: {{ $pct((int)$paymentsTotal,$max) }}%;"></div>
                <div class="kpi-val">{{ number_format($paymentsTotal, 0, ',', ' ') }}</div>
            </div>
            <div class="kpi-row">
                <div class="kpi-label">Frais définis</div>
                <div class="bar" style="width: {{ $pct($feesCount,$max) }}%;"></div>
                <div class="kpi-val">{{ number_format($feesCount, 0, ',', ' ') }}</div>
            </div>
        </div>
    </div>
</body>
</html>
