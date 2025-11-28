<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Élèves - EduMaster</title>
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
        .coming-soon { background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center; }
        .icon { font-size: 4rem; margin-bottom: 1rem; }
        .btn { display: inline-block; margin-top: 2rem; padding: 0.75rem 1.5rem; background: #20B2AA; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; }
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
            <h1 class="page-title">🎓 Gestion des Élèves</h1>
            <a href="{{ route('students.create') }}" class="btn">+ Nouvel Élève</a>
        </div>
        
        <form method="GET" action="{{ route('students.index') }}" class="filters">
            <div class="filter-group">
                <label>Recherche :</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom de l'élève..." style="padding: 0.5rem; border: 2px solid #e1e5e9; border-radius: 5px; width: 200px;">
            </div>
            <div class="filter-group">
                <label>Classe :</label>
                <select name="classe" onchange="this.form.submit()">
                    <option value="all">Toutes les classes</option>
                    @php
                        // Récupérer les classes de la table class_rooms
                        $classRoomsDB = DB::table('class_rooms')->pluck('name')->unique()->sort();
                        
                        // Récupérer les classes des élèves existants
                        $classesFromStudents = \App\Models\Student::whereNotNull('classe')->distinct()->pluck('classe')->filter()->sort();
                        
                        // Classes prédéfinies du système malgache
                        $predefinedClasses = collect([
                            'CP', 'CE1', 'CE2', 'CM1', 'CM2',  // Primaire
                            '6ème', '5ème', '4ème', '3ème',     // Collège - 3ème = BEPC
                            '2nde', '1ère', 'Terminale',        // Lycée - Terminale = BACC
                            '7ème',                             // 7ème = CEPE
                            '8ème', '9ème', '10ème', '11ème', '12ème'  // Classes supplémentaires
                        ]);
                        
                        // Fusionner toutes les classes
                        $allClasses = $classRoomsDB
                            ->merge($classesFromStudents)
                            ->merge($predefinedClasses)
                            ->unique()
                            ->sort();
                    @endphp
                    @foreach($allClasses as $classe)
                        <option value="{{ $classe }}" {{ request('classe') == $classe ? 'selected' : '' }}>{{ $classe }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Statut :</label>
                <select name="statut" onchange="this.form.submit()">
                    <option value="all">Tous</option>
                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="filter-group">
                <button type="submit" class="btn" style="margin-top: 1.5rem;">🔍 Rechercher</button>
            </div>
        </form>
        
        @if (session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="students-table">
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom & Prénom</th>
                        <th>Genre</th>
                        <th>Classe</th>
                        <th>Date de naissance</th>
                        <th>Parents/Tuteur</th>
                        <th>Téléphone</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students ?? [] as $student)
                    <tr>
                        <td><div class="student-avatar">{{ $student->genre == 'feminin' ? '👧' : '👦' }}</div></td>
                        <td><strong>{{ $student->name }}</strong></td>
                        <td>
                            @if($student->genre == 'feminin')
                                <span class="genre-feminin">👧 Féminin</span>
                            @else
                                <span class="genre-masculin">👦 Masculin</span>
                            @endif
                        </td>
                        <td>{{ $student->classe ?? 'Non définie' }}</td>
                        <td>{{ $student->date_naissance ? \Carbon\Carbon::parse($student->date_naissance)->format('d/m/Y') : ($student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') : 'N/A') }}</td>
                        <td>{{ $student->parent_tuteur ?? 'N/A' }}</td>
                        <td>{{ $student->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="{{ $student->is_active ? 'status-active' : 'status-inactive' }}" title="{{ $student->is_active ? 'Élève inscrit et présent' : 'Élève suspendu ou parti' }}">
                                {{ $student->is_active ? '✅ Inscrit' : '❌ Inactif' }}
                            </span>
                        </td>
                        <td>
                            <div class="student-actions">
                                <a href="{{ route('students.show', $student) }}" class="btn-sm btn-view btn-icon" title="Voir l'élève" aria-label="Voir l'élève">👁️</a>
                                <a href="{{ route('students.edit', $student) }}" class="btn-sm btn-edit btn-icon" title="Modifier l'élève" aria-label="Modifier l'élève">✏️</a>
                                <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $student->name }} ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-delete btn-icon" title="Supprimer l'élève" aria-label="Supprimer l'élève">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 2rem; color: #666;">
                            @if(request()->hasAny(['search', 'classe', 'statut']))
                                Aucun élève trouvé avec ces critères.
                                <br><a href="{{ route('students.index') }}" style="color: #20B2AA;">Voir tous les élèves</a>
                            @else
                                Aucun élève enregistré.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <style>
            .filters { background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; display: flex; gap: 2rem; align-items: center; }
            .filter-group { display: flex; flex-direction: column; gap: 0.5rem; }
            .filter-group label { font-weight: bold; color: #333; }
            .filter-group select { padding: 0.5rem; border: 2px solid #e1e5e9; border-radius: 5px; }
            .students-table { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden; }
            table { width: 100%; border-collapse: collapse; }
            th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
            th { background: #f8f9fa; font-weight: bold; color: #333; }
            .student-avatar { font-size: 2rem; text-align: center; }
            .status-active { background: #d4edda; color: #155724; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem; }
            .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.8rem; text-decoration: none; border-radius: 4px; color: white; border: none; cursor: pointer; }
            .btn-view { background: #17a2b8; }
            .btn-view:hover { background: #138496; }
            .btn-edit { background: #ffc107; color: #333; }
            .btn-edit:hover { background: #e0a800; }
            .btn-delete { background: #dc3545; }
            .btn-delete:hover { background: #c82333; }
            .student-actions { display: flex; gap: 0.5rem; justify-content: center; align-items: center; white-space: nowrap; }
            .student-actions form { margin: 0; }
            .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
            .status-inactive { background: #f8d7da; color: #721c24; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem; }
            .genre-feminin { background: #ffe4e6; color: #d63384; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem; }
            .genre-masculin { background: #e3f2fd; color: #1976d2; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem; }
        </style>
    </div>
</body>
</html>
