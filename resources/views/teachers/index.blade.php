<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Professeurs - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-links { display: flex; gap: 1rem; }
        .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .page-header { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; text-align: center; }
        .page-title { font-size: 2rem; color: #20B2AA; margin-bottom: 1rem; }
        .btn { display: inline-block; margin-top: 2rem; padding: 0.75rem 1.5rem; background: #20B2AA; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; }
        .teachers-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem; }
        .teacher-card { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .teacher-card:hover { transform: translateY(-5px); }
        .teacher-avatar { font-size: 4rem; text-align: center; margin-bottom: 1rem; }
        .teacher-name { font-size: 1.3rem; font-weight: bold; color: #20B2AA; text-align: center; margin-bottom: 0.5rem; }
        .teacher-subject { color: #666; text-align: center; margin-bottom: 1.5rem; font-style: italic; }
        .teacher-info { margin-bottom: 1.5rem; }
        .info-item { margin-bottom: 0.5rem; font-size: 0.9rem; color: #555; }
        .teacher-actions { display: flex; gap: 0.5rem; justify-content: center; align-items: center; }
        .teacher-actions form { margin: 0; }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.9rem; text-decoration: none; border-radius: 5px; color: white; border: none; cursor: pointer; }
        .btn-view { background: #17a2b8; }
        .btn-edit { background: #ffc107; }
        .btn-danger { background: #dc3545; border: none; cursor: pointer; }
        .btn-danger:hover { background: #c82333; }
        .btn-icon { width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
            <a href="/">Déconnexion</a>
        </div>
    </div>
    
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">👨‍🏫 Gestion des Professeurs</h1>
            <a href="{{ route('teachers.create') }}" class="btn">+ Nouveau Professeur</a>
        </div>
        
        <div class="teachers-grid">
            @if($teachers->count() > 0)
                @foreach($teachers as $teacher)
                <div class="teacher-card">
                    <div class="teacher-avatar">{{ $teacher->gender == 'female' ? '👩‍🏫' : '👨‍🏫' }}</div>
                    <div class="teacher-name">{{ $teacher->full_name }}</div>
                    <div class="teacher-subject">{{ $teacher->specialization }}</div>
                    <div class="teacher-info">
                        @if($teacher->email)
                            <div class="info-item">📧 {{ $teacher->email }}</div>
                        @endif
                        @if($teacher->phone)
                            <div class="info-item">📱 {{ $teacher->phone }}</div>
                        @endif
                        <div class="info-item">🏫 Qualification : {{ $teacher->qualification }}</div>
                    </div>
                    <div class="teacher-actions">
                        <a href="{{ route('teachers.show', $teacher) }}" class="btn-sm btn-view btn-icon" title="Voir le professeur" aria-label="Voir le professeur">👁️</a>
                        <a href="{{ route('teachers.edit', $teacher) }}" class="btn-sm btn-edit btn-icon" title="Modifier le professeur" aria-label="Modifier le professeur">✏️</a>
                        <form method="POST" action="{{ route('teachers.destroy', $teacher) }}" onsubmit="return confirm('Supprimer définitivement {{ $teacher->full_name }} ?\n\nCette action est irréversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-danger btn-icon" title="Supprimer le professeur" aria-label="Supprimer le professeur">🗑️</button>
                        </form>
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align:center; color:#666;">Aucun professeur trouvé. <a href="{{ route('teachers.create') }}" style="color:#20B2AA; text-decoration:none; font-weight:600;">Créer le premier professeur</a></div>
            @endif
        </div>
    </div>
</body>
</html>
