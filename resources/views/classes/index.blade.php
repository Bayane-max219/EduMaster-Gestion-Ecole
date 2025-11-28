<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Classes - EduMaster</title>
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
        .classes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; }
        .class-card { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .class-card:hover { transform: translateY(-5px); }
        .class-name { font-size: 1.5rem; font-weight: bold; color: #20B2AA; margin-bottom: 1rem; }
        .class-info { color: #666; margin-bottom: 0.5rem; }
        .class-actions { margin-top: 1rem; display: flex; gap: 0.5rem; justify-content: center; align-items: center; }
        .class-actions form { margin: 0; }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.9rem; text-decoration: none; border-radius: 5px; color: white; }
        .btn-edit { background: #ffc107; }
        .btn-view { background: #17a2b8; }
        .btn-danger { background: #dc3545; border: none; cursor: pointer; }
        .btn-danger:hover { background: #c82333; }
        .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
        .success-message { background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
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
            <h1 class="page-title">📚 Gestion des Classes</h1>
            <a href="{{ route('classes.create') }}" class="btn">+ Nouvelle Classe</a>
        </div>

        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif
        
        <div class="classes-grid">
            @forelse($classesData as $item)
                @php($c = $item['model'])
                <div class="class-card">
                    <div class="class-name">{{ $c->name }}</div>
                    <div class="class-info">👨‍🏫 Professeur principal : {{ $item['principal'] ?? 'Non assigné' }}</div>
                    <div class="class-info">👥 Nombre d'élèves : {{ $item['student_count'] }}</div>
                    <div class="class-info">📅 Année scolaire : {{ $item['school_year'] ?? '—' }}</div>
                    <div class="class-actions">
                        <a href="{{ route('classes.show', $c) }}" class="btn-sm btn-view btn-icon" title="Voir la classe" aria-label="Voir la classe">👁️</a>
                        <a href="{{ route('classes.edit', $c) }}" class="btn-sm btn-edit btn-icon" title="Modifier la classe" aria-label="Modifier la classe">✏️</a>
                        <form method="POST" action="{{ route('classes.destroy', $c) }}" onsubmit="return confirm('Supprimer définitivement la classe {{ $c->name }} ?\n\nCette action est irréversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-danger btn-icon" title="Supprimer la classe" aria-label="Supprimer la classe">🗑️</button>
                        </form>
                    </div>
                </div>
            @empty
                <p>Aucune classe pour l'instant. Créez la première classe.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
