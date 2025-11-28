<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Notes - EduMaster</title>
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
        .filters { background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center; }
        .filter-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .filter-group label { font-weight: bold; color: #333; }
        .filter-group select { padding: 0.5rem; border: 2px solid #e1e5e9; border-radius: 5px; }
        .grades-table { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: bold; color: #333; }
        .grade-excellent { background: #d4edda; color: #155724; padding: 0.25rem 0.5rem; border-radius: 15px; font-weight: bold; }
        .grade-good { background: #cce5ff; color: #004085; padding: 0.25rem 0.5rem; border-radius: 15px; font-weight: bold; }
        .grade-average { background: #fff3cd; color: #856404; padding: 0.25rem 0.5rem; border-radius: 15px; font-weight: bold; }
        .grade-poor { background: #f8d7da; color: #721c24; padding: 0.25rem 0.5rem; border-radius: 15px; font-weight: bold; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.8rem; text-decoration: none; border-radius: 4px; color: white; }
        .btn-edit { background: #ffc107; }
        .btn-view { background: #20B2AA; }
        .btn-delete { background: #dc3545; }
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
                <button type="submit" style="background: none; border: none; color: white; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='none'">
                    🚪 Déconnexion
                </button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">📝 Gestion des Notes</h1>
            <a href="{{ route('grades.create') }}" class="btn">+ Nouvelle Note</a>
        </div>
        
        @if(session('success'))
            <div style="background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:1rem;border-radius:8px;margin-bottom:1rem;">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('grades.index') }}" class="filters">
            <div class="filter-group">
                <label>Classe :</label>
                <select name="class_id">
                    <option value="">Toutes les classes</option>
                    @foreach(($classes ?? []) as $c)
                        <option value="{{ $c->id }}" {{ (string)($filters['class_id'] ?? '') === (string)$c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->level }})</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Matière :</label>
                <select name="subject_id">
                    <option value="">Toutes les matières</option>
                    @foreach(($subjects ?? []) as $s)
                        <option value="{{ $s->id }}" {{ (string)($filters['subject_id'] ?? '') === (string)$s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Semestre :</label>
                <select name="semester">
                    <option value="">Tous</option>
                    <option value="1" {{ ($filters['semester'] ?? '')==='1' ? 'selected' : '' }}>1</option>
                    <option value="2" {{ ($filters['semester'] ?? '')==='2' ? 'selected' : '' }}>2</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Année scolaire :</label>
                <select name="school_year_id">
                    @foreach(($schoolYears ?? []) as $y)
                        <option value="{{ $y->id }}" {{ (string)($filters['school_year_id'] ?? $currentYearId) === (string)$y->id ? 'selected' : '' }}>{{ $y->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group" style="align-self:flex-end;">
                <button type="submit" class="btn" style="border:none;">Filtrer</button>
            </div>
            <div class="filter-group" style="align-self:flex-end;">
                <a class="btn" href="{{ route('grades.index') }}" style="background:#6c757d;">Réinitialiser</a>
            </div>
        </form>
        
        <div class="grades-table">
            <table>
                <thead>
                    <tr>
                        <th>Élève</th>
                        <th>Classe</th>
                        <th>Matière</th>
                        <th>Type d'évaluation</th>
                        <th>Note</th>
                        <th>Coefficient</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($grades ?? []) as $g)
                        <tr>
                            <td>{{ $g->student->full_name ?? ($g->student->name ?? '—') }}</td>
                            <td>{{ $g->classRoom->name ?? '—' }}</td>
                            <td>{{ $g->subject->name ?? '—' }}</td>
                            <td>{{ ucfirst($g->exam_type) }}</td>
                            <td>
                                @php
                                    $cls = 'grade-average';
                                    if ($g->score !== null && $g->max_score) {
                                        $scaled = ($g->score / $g->max_score) * 20;
                                        $cls = $scaled >= 16 ? 'grade-excellent' : ($scaled >= 14 ? 'grade-good' : ($scaled >= 10 ? 'grade-average' : 'grade-poor'));
                                    }
                                @endphp
                                <span class="{{ $cls }}">{{ $g->score !== null ? rtrim(rtrim(number_format($g->score,2), '0'), '.') : '—' }}/{{ rtrim(rtrim(number_format($g->max_score,2), '0'), '.') }}</span>
                            </td>
                            <td>{{ $g->subject->coefficient ?? '—' }}</td>
                            <td>{{ $g->exam_date ? $g->exam_date->format('d/m/Y') : '—' }}</td>
                            <td>
                                <div class="record-actions">
                                    <a href="{{ route('grades.show', $g) }}" class="btn-sm btn-view btn-icon" title="Voir la note" aria-label="Voir la note">👁️</a>
                                    <a href="{{ route('grades.edit', $g) }}" class="btn-sm btn-edit btn-icon" title="Modifier la note" aria-label="Modifier la note">✏️</a>
                                    <form action="{{ route('grades.destroy', $g) }}" method="POST" onsubmit="return confirm('Supprimer cette note ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-delete btn-icon" title="Supprimer la note" aria-label="Supprimer la note">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:#666;">Aucune note trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($grades))
            <div style="margin-top: 1rem;">{{ $grades->links() }}</div>
        @endif
    </div>
</body>
</html>
