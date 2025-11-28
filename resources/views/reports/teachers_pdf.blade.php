<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Professeurs - PDF</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display:flex; justify-content:space-between; align-items:center; }
        .logo { font-size: 1.2rem; font-weight: bold; }
        .container { max-width: 1100px; margin: 1.5rem auto; padding: 0 1.5rem; }
        .title { font-size: 1.6rem; color:#20B2AA; margin-bottom: .75rem; }
        .card { background:white; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.06); padding:1rem; margin-bottom:1rem; }
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
            <button onclick="window.print();" style="background:#fff;color:#20B2AA;border:none;padding:.5rem .75rem;border-radius:6px;cursor:pointer;">🖨️ Imprimer</button>
        </div>
    </div>

    <div class="container">
        <h1 class="title">Rapport des Professeurs</h1>
        @if(!$has)
            <div class="card">Aucune donnée disponible (table teachers manquante).</div>
        @else
            <div class="card">
                <div style="color:#555;">Total professeurs</div>
                <div class="stat">{{ number_format((int)$total, 0, ',', ' ') }}</div>
            </div>
            <div class="card">
                <h3 style="color:#20B2AA; margin-bottom:.5rem;">Répartition par statut</h3>
                <table>
                    <thead><tr><th>Statut</th><th>Total</th></tr></thead>
                    <tbody>
                    @forelse($byStatus as $row)
                        <tr><td>{{ $row->status ?: 'Inconnu' }}</td><td>{{ $row->count }}</td></tr>
                    @empty
                        <tr><td colspan="2" style="color:#666; text-align:center;">Aucune donnée</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card">
                <h3 style="color:#20B2AA; margin-bottom:.5rem;">Répartition par spécialisation</h3>
                <table>
                    <thead><tr><th>Spécialisation</th><th>Total</th></tr></thead>
                    <tbody>
                    @forelse($bySpec as $row)
                        <tr><td>{{ $row->specialization ?: 'Non défini' }}</td><td>{{ $row->count }}</td></tr>
                    @empty
                        <tr><td colspan="2" style="color:#666; text-align:center;">Aucune donnée</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>
</html>
