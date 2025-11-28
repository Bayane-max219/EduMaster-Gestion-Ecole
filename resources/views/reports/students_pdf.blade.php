<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Élèves - PDF</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display:flex; justify-content:space-between; align-items:center; }
        .logo { font-size: 1.2rem; font-weight: bold; }
        .container { max-width: 1100px; margin: 1.5rem auto; padding: 0 1.5rem; }
        .title { font-size: 1.6rem; color:#20B2AA; margin-bottom: .75rem; }
        .info { color:#555; margin-bottom: 1rem; }
        .grid { display:grid; grid-template-columns: 1fr 1fr; gap:1rem; }
        .card { background:white; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.06); padding:1rem; }
        table { width:100%; border-collapse: collapse; }
        th, td { padding:.6rem .75rem; border-bottom:1px solid #eee; text-align:left; }
        th { background:#f8f9fa; color:#333; }
        .stat { font-size:2rem; font-weight:800; color:#20B2AA; }
        @media print { .header { display:none; } body { background:#fff; } .card { box-shadow:none; border:1px solid #eee; } }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div>
            <a href="{{ route('reports.index') }}" style="color:#fff; text-decoration:none; margin-right:1rem;">Rapports</a>
            <button onclick="window.print()" style="background:#fff;color:#20B2AA;border:none;padding:.5rem .75rem;border-radius:6px;cursor:pointer;">🖨️ Imprimer</button>
        </div>
    </div>

    <div class="container">
        <h1 class="title">Rapport des Élèves</h1>
        <p class="info">Ce document est prêt pour impression/PDF via le navigateur.</p>

        @if(!$has)
            <div class="card">Aucune donnée disponible (table students manquante).</div>
        @else
            <div class="grid" style="margin-bottom:1rem;">
                <div class="card">
                    <div style="color:#555;">Total élèves</div>
                    <div class="stat">{{ number_format((int)$total, 0, ',', ' ') }}</div>
                </div>
            </div>

            <div class="grid">
                <div class="card">
                    <h3 style="color:#20B2AA; margin-bottom:.5rem;">Répartition par classe</h3>
                    <table>
                        <thead><tr><th>Classe</th><th>Total</th></tr></thead>
                        <tbody>
                        @forelse($byClass as $row)
                            <tr><td>{{ $row->classe ?: 'Sans classe' }}</td><td>{{ $row->count }}</td></tr>
                        @empty
                            <tr><td colspan="2" style="color:#666; text-align:center;">Aucune donnée</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card">
                    <h3 style="color:#20B2AA; margin-bottom:.5rem;">Répartition par genre</h3>
                    <table>
                        <thead><tr><th>Genre</th><th>Total</th></tr></thead>
                        <tbody>
                        @forelse($byGender as $row)
                            <tr><td>{{ $row->genre ?: 'Inconnu' }}</td><td>{{ $row->count }}</td></tr>
                        @empty
                            <tr><td colspan="2" style="color:#666; text-align:center;">Aucune donnée</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
