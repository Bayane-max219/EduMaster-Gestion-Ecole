<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de la Note - EduMaster</title>
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
        .badge { padding: 0.25rem 0.5rem; border-radius: 12px; font-weight: bold; }
        .grade-excellent { background: #d4edda; color: #155724; }
        .grade-good { background: #cce5ff; color: #004085; }
        .grade-average { background: #fff3cd; color: #856404; }
        .grade-poor { background: #f8d7da; color: #721c24; }
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
            <a href="{{ route('grades.index') }}">Notes</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='none'">🚪 Déconnexion</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">📄 Détail de la Note</h1>
            <div class="actions">
                <a href="{{ route('grades.edit', $grade) }}" class="btn btn-warning">Modifier</a>
                <a href="{{ route('grades.index') }}" class="btn btn-secondary">Retour</a>
            </div>
        </div>

        @php
            $cls = 'grade-average';
            if ($grade->score !== null && $grade->max_score) {
                $scaled = ($grade->score / $grade->max_score) * 20;
                $cls = $scaled >= 16 ? 'grade-excellent' : ($scaled >= 14 ? 'grade-good' : ($scaled >= 10 ? 'grade-average' : 'grade-poor'));
            }
        @endphp

        <div class="grid">
            <div class="card">
                <div class="section-title">Informations Générales</div>
                <div class="label">Élève</div>
                <div class="value">{{ $grade->student->full_name ?? ($grade->student->name ?? '—') }}</div>
                <div class="label" style="margin-top:.75rem;">Classe</div>
                <div class="value">{{ $grade->classRoom->name ?? '—' }} {{ $grade->classRoom?->level ? '(' . $grade->classRoom->level . ')' : '' }}</div>
                <div class="label" style="margin-top:.75rem;">Matière</div>
                <div class="value">{{ $grade->subject->name ?? '—' }}</div>
                <div class="label" style="margin-top:.75rem;">Professeur</div>
                <div class="value">{{ $grade->teacher->full_name ?? '—' }}</div>
            </div>
            <div class="card">
                <div class="section-title">Évaluation</div>
                <div class="label">Type</div>
                <div class="value">{{ ucfirst($grade->exam_type) }}</div>
                <div class="label" style="margin-top:.75rem;">Intitulé</div>
                <div class="value">{{ $grade->exam_name }}</div>
                <div class="label" style="margin-top:.75rem;">Note</div>
                <div class="value"><span class="badge {{ $cls }}">{{ $grade->score !== null ? rtrim(rtrim(number_format($grade->score,2), '0'), '.') : '—' }}/{{ rtrim(rtrim(number_format($grade->max_score,2), '0'), '.') }}</span></div>
                <div class="label" style="margin-top:.75rem;">Date</div>
                <div class="value">{{ $grade->exam_date ? $grade->exam_date->format('d/m/Y') : '—' }}</div>
                <div class="label" style="margin-top:.75rem;">Semestre</div>
                <div class="value">{{ $grade->semester }}</div>
            </div>
        </div>

        <div class="grid" style="margin-top:1.5rem;">
            <div class="card">
                <div class="section-title">Année Scolaire</div>
                <div class="label">Année</div>
                <div class="value">{{ $grade->schoolYear->name ?? '—' }}</div>
            </div>
            <div class="card">
                <div class="section-title">Remarques</div>
                <div class="value">{{ $grade->notes ?: '—' }}</div>
            </div>
        </div>

        <div style="margin-top:1.5rem;" class="card danger-zone">
            <div class="section-title" style="color:#dc3545;">Zone Dangereuse</div>
            <p style="margin: .5rem 0 1rem; color:#333;">Supprimer définitivement cette note.</p>
            <form method="POST" action="{{ route('grades.destroy', $grade) }}" onsubmit="return confirm('Confirmer la suppression ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
            </form>
        </div>
    </div>
</body>
</html>
