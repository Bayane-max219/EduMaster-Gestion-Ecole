<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Frais - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-links { display: flex; gap: 1rem; }
        .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1100px; margin: 2rem auto; padding: 0 2rem; }
        .page-header { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; display:flex; justify-content:space-between; align-items:center; }
        .page-title { font-size: 2rem; color: #20B2AA; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .card { background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .label { color: #555; font-weight: 600; margin-bottom: 0.25rem; display:block; }
        .value { color: #111; font-size: 1.05rem; }
        .actions { display:flex; gap: .5rem; }
        .btn { padding: 0.6rem 1rem; border-radius: 8px; text-decoration: none; color: white; display:inline-block; }
        .btn-primary { background:#20B2AA; }
        .btn-secondary { background:#6c757d; }
        .btn-warning { background:#ffc107; color:#222; }
        .btn-danger { background:#dc3545; }
        .section-title { font-weight:700; color:#20B2AA; margin-bottom: .75rem; }
        .danger-zone { background: #fff; border: 2px solid #ffe1e1; padding: 1rem; border-radius: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
            <a href="{{ route('fees.index') }}">Frais</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='none'">🚪 Déconnexion</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">📄 Détail du Frais</h1>
            <div class="actions">
                <a href="{{ route('fees.edit', $fee) }}" class="btn btn-warning">Modifier</a>
                <a href="{{ route('fees.index') }}" class="btn btn-secondary">Retour</a>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <div class="section-title">Informations</div>
                <div class="label">Intitulé</div>
                <div class="value">{{ $fee->name }}</div>
                <div class="label" style="margin-top:.75rem;">Montant</div>
                <div class="value">{{ number_format((float)$fee->amount, 0, ',', ' ') }} Ar</div>
                <div class="label" style="margin-top:.75rem;">Type</div>
                <div class="value">{{ ucfirst($fee->type) }}</div>
            </div>
            <div class="card">
                <div class="section-title">Période</div>
                <div class="label">Fréquence</div>
                <div class="value">{{ ucfirst($fee->frequency) }}</div>
                <div class="label" style="margin-top:.75rem;">Échéance</div>
                <div class="value">{{ $fee->due_date ? $fee->due_date->format('d/m/Y') : '—' }}</div>
                <div class="label" style="margin-top:.75rem;">Année scolaire</div>
                <div class="value">{{ $fee->schoolYear->name ?? '—' }}</div>
            </div>
        </div>

        <div class="grid" style="margin-top:1.5rem;">
            <div class="card">
                <div class="section-title">Autres</div>
                <div class="label">Obligatoire</div>
                <div class="value">{{ $fee->is_mandatory ? 'Oui' : 'Non' }}</div>
                <div class="label" style="margin-top:.75rem;">Niveau concerné</div>
                <div class="value">{{ $fee->class_level ?: '—' }}</div>
            </div>
            <div class="card">
                <div class="section-title">Description</div>
                <div class="value">{{ $fee->description ?: '—' }}</div>
            </div>
        </div>

        <div style="margin-top:1.5rem;" class="card danger-zone">
            <div class="section-title" style="color:#dc3545;">Zone Dangereuse</div>
            <p style="margin: .5rem 0 1rem; color:#333;">Supprimer définitivement ce frais.</p>
            <form method="POST" action="{{ route('fees.destroy', $fee) }}" onsubmit="return confirm('Confirmer la suppression ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
            </form>
        </div>
    </div>
</body>
</html>
