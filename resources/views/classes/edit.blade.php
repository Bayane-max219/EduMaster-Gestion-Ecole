<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Classe - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-links { display: flex; gap: 1rem; }
        .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 900px; margin: 2rem auto; padding: 0 2rem; }
        .page-header { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; text-align: center; }
        .page-title { font-size: 2rem; color: #20B2AA; margin-bottom: 1rem; }
        .class-info { background: #f8f9fa; padding: 1rem; border-radius: 8px; font-size: 0.9rem; color: #666; }
        .form-container { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group.full-width { grid-column: 1 / -1; }
        label { display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500; }
        input, select { width: 100%; padding: 12px 15px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease; }
        input:focus, select:focus { outline: none; border-color: #20B2AA; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; font-size: 1rem; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.3s ease; }
        .btn-primary { background: #20B2AA; color: white; }
        .btn-primary:hover { background: #1a9a92; }
        .btn-secondary { background: #6c757d; color: white; margin-left: 1rem; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-danger { background: #dc3545; color: white; margin-left: 1rem; }
        .btn-danger:hover { background: #c82333; }
        .form-actions { display: flex; align-items: center; margin-top: 2rem; }
        .error-message { background: #fee; color: #c33; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .success-message { background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .required { color: #dc3545; }
        .danger-zone { margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #eee; }
        .danger-zone h3 { color: #dc3545; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
            <a href="{{ route('classes.index') }}">Gestion des Classes</a>
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
            <h1 class="page-title">✏️ Modifier la Classe</h1>
            <div class="class-info">
                ID: {{ $classRoom->id }} | Créée le: {{ $classRoom->created_at->format('d/m/Y à H:i') }}
            </div>
        </div>

        <div class="form-container">
            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="error-message">
                    <strong>Erreurs de validation :</strong>
                    <ul style="margin-top: 0.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('classes.update', $classRoom) }}">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nom de la classe <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $classRoom->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="level">Niveau <span class="required">*</span></label>
                        <select id="level" name="level" required>
                            <option value="">Sélectionner un niveau</option>
                            @foreach(['CP','CE1','CE2','CM1','CM2','6ème','5ème','4ème','3ème','2nde','1ère','Terminale'] as $lvl)
                                <option value="{{ $lvl }}" {{ old('level', $classRoom->level)===$lvl?'selected':'' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="section">Section</label>
                        <input type="text" id="section" name="section" value="{{ old('section', $classRoom->section) }}" placeholder="Ex: A, B, C">
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacité (élèves)</label>
                        <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $classRoom->capacity) }}" min="0" step="1">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="room_number">Numéro de salle</label>
                        <input type="text" id="room_number" name="room_number" value="{{ old('room_number', $classRoom->room_number) }}">
                    </div>
                    <div class="form-group">
                        <label for="school_year_id">Année scolaire <span class="required">*</span></label>
                        <select id="school_year_id" name="school_year_id" required>
                            <option value="">Sélectionner l'année scolaire</option>
                            @foreach($schoolYears as $year)
                                <option value="{{ $year->id }}" {{ old('school_year_id', $classRoom->school_year_id)==$year->id?'selected':'' }}>{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="status">Statut <span class="required">*</span></label>
                        <select id="status" name="status" required>
                            <option value="active" {{ old('status', $classRoom->status)==='active'?'selected':'' }}>✅ Active</option>
                            <option value="inactive" {{ old('status', $classRoom->status)==='inactive'?'selected':'' }}>❌ Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Mettre à jour la Classe</button>
                    <a href="{{ route('classes.index') }}" class="btn btn-secondary">❌ Annuler</a>
                </div>
            </form>

            <div class="danger-zone">
                <h3>🗑️ Zone de Danger</h3>
                <p>La suppression de cette classe est définitive et ne peut pas être annulée.</p>
                <form method="POST" action="{{ route('classes.destroy', $classRoom) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $classRoom->name }} ?\n\nCette action est IRRÉVERSIBLE !')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">🗑️ Supprimer définitivement cette classe</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
