<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Frais - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-links { display: flex; gap: 1rem; }
        .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .page-header { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 2rem; color: #20B2AA; }
        .btn { padding: 0.75rem 1.5rem; background: #20B2AA; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; transition: background 0.3s; }
        .btn:hover { background: #1a9a92; }
        .filters { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom:1rem; }
        .filter-card { background:white; padding:1rem; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.06); }
        .label { display:block; color:#555; font-weight:600; margin-bottom:.4rem; }
        select, input { width:100%; padding:.55rem; border:2px solid #e1e5e9; border-radius:8px; }
        .fees-table { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: bold; color: #333; }
        .amount { font-weight: bold; color: #20B2AA; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.8rem; text-decoration: none; border-radius: 4px; color: white; }
        .btn-view { background: #17a2b8; }
        .btn-edit { background: #ffc107; color:#222; }
        .btn-danger { background: #dc3545; color:white; }
        .record-actions { display:flex; gap:0.5rem; justify-content:center; align-items:center; }
        .record-actions form { margin:0; }
        .btn-icon { width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; padding:0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='none'">🚪 Déconnexion</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">🏷️ Gestion des Frais</h1>
            <a href="{{ route('fees.create') }}" class="btn">+ Nouveau Frais</a>
        </div>

        @if(session('success'))
            <div style="background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:1rem;border-radius:8px;margin-bottom:1rem;">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('fees.index') }}" class="filters">
            <div class="filter-card">
                <label class="label">Année scolaire</label>
                <select name="school_year_id">
                    <option value="">Toutes</option>
                    @foreach(($schoolYears ?? []) as $y)
                        <option value="{{ $y->id }}" {{ (string)($filters['school_year_id'] ?? '') === (string)$y->id ? 'selected' : '' }}>{{ $y->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-card">
                <label class="label">Type</label>
                <select name="type">
                    <option value="">Tous</option>
                    @foreach(($types ?? []) as $t)
                        <option value="{{ $t }}" {{ ($filters['type'] ?? '')===$t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-card">
                <label class="label">Fréquence</label>
                <select name="frequency">
                    <option value="">Toutes</option>
                    @foreach(($frequencies ?? []) as $f)
                        <option value="{{ $f }}" {{ ($filters['frequency'] ?? '')===$f ? 'selected' : '' }}>{{ ucfirst($f) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-card">
                <label class="label">Obligatoire</label>
                <select name="mandatory">
                    <option value="">Tous</option>
                    <option value="1" {{ ($filters['mandatory'] ?? '')==='1' ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ ($filters['mandatory'] ?? '')==='0' ? 'selected' : '' }}>Non</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:.5rem;">
                <button type="submit" class="btn">Filtrer</button>
                <a href="{{ route('fees.index') }}" class="btn" style="background:#6c757d;">Réinitialiser</a>
            </div>
        </form>

        <div class="fees-table">
            <table>
                <thead>
                    <tr>
                        <th>Intitulé</th>
                        <th>Montant</th>
                        <th>Type</th>
                        <th>Fréquence</th>
                        <th>Échéance</th>
                        <th>Année</th>
                        <th>Obligatoire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($fees ?? []) as $fee)
                        <tr>
                            <td>{{ $fee->name }}</td>
                            <td class="amount">{{ number_format((float)$fee->amount, 0, ',', ' ') }} Ar</td>
                            <td>{{ ucfirst($fee->type) }}</td>
                            <td>{{ ucfirst($fee->frequency) }}</td>
                            <td>{{ $fee->due_date ? $fee->due_date->format('d/m/Y') : '—' }}</td>
                            <td>{{ $fee->schoolYear->name ?? '—' }}</td>
                            <td>{{ $fee->is_mandatory ? 'Oui' : 'Non' }}</td>
                            <td>
                                <div class="record-actions">
                                    <a href="{{ route('fees.show', $fee) }}" class="btn-sm btn-view btn-icon" title="Voir le frais" aria-label="Voir le frais">👁️</a>
                                    <a href="{{ route('fees.edit', $fee) }}" class="btn-sm btn-edit btn-icon" title="Modifier le frais" aria-label="Modifier le frais">✏️</a>
                                    <form action="{{ route('fees.destroy', $fee) }}" method="POST" onsubmit="return confirm('Supprimer ce frais ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-danger btn-icon" title="Supprimer le frais" aria-label="Supprimer le frais">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:#666;">Aucun frais trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($fees))
            <div style="margin-top: 1rem;">{{ $fees->links() }}</div>
        @endif
    </div>
</body>
</html>
