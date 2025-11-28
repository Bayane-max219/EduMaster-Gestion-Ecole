<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Exécutif - PDF</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display:flex; justify-content:space-between; align-items:center; }
        .logo { font-size: 1.2rem; font-weight: bold; }
        .container { max-width: 1100px; margin: 1.5rem auto; padding: 0 1.5rem; }
        .title { font-size: 1.6rem; color:#20B2AA; margin-bottom: .75rem; }
        .grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1rem; }
        .card { background:white; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.06); padding:1rem; }
        .label { color:#555; }
        .stat { font-size:2rem; font-weight:800; color:#20B2AA; }
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
        <h1 class="title">Tableau de Bord Exécutif</h1>
        <div class="grid">
            <div class="card">
                <div class="label">Total élèves</div>
                <div class="stat">{{ number_format((int)$studentsCount, 0, ',', ' ') }}</div>
            </div>
            <div class="card">
                <div class="label">Total professeurs</div>
                <div class="stat">{{ number_format((int)$teachersCount, 0, ',', ' ') }}</div>
            </div>
            <div class="card">
                <div class="label">Montant des paiements</div>
                <div class="stat">{{ number_format((float)$paymentsTotal, 0, ',', ' ') }} Ar</div>
            </div>
            <div class="card">
                <div class="label">Frais définis</div>
                <div class="stat">{{ number_format((int)$feesCount, 0, ',', ' ') }}</div>
            </div>
        </div>
    </div>
</body>
</html>
