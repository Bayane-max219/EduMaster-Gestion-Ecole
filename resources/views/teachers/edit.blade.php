<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Professeur - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-links { display: flex; gap: 1rem; }
        .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 800px; margin: 2rem auto; padding: 0 2rem; }
        .page-header { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; text-align: center; }
        .page-title { font-size: 2rem; color: #20B2AA; margin-bottom: 1rem; }
        .student-info { background: #f8f9fa; padding: 1rem; border-radius: 8px; font-size: 0.9rem; color: #666; }
        .form-container { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group.full-width { grid-column: 1 / -1; }
        label { display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500; }
        input, select, textarea { width: 100%; padding: 12px 15px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #20B2AA; }
        textarea { resize: vertical; min-height: 100px; }
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
            <a href="{{ route('teachers.index') }}">Gestion Professeurs</a>
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
            <h1 class="page-title">✏️ Modifier le Professeur</h1>
            <p>Mettez à jour les informations du professeur</p>
            <div class="student-info">
                ID: {{ $teacher->id }} | Créé le: {{ $teacher->created_at->format('d/m/Y à H:i') }}
            </div>
        </div>

        <div class="form-container">
            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
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

            <form method="POST" action="{{ route('teachers.update', $teacher) }}">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">Prénom <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $teacher->first_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Nom <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $teacher->last_name) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date de naissance <span class="required">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $teacher->date_of_birth ? $teacher->date_of_birth->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Genre <span class="required">*</span></label>
                        <select id="gender" name="gender" required>
                            <option value="">Sélectionnez le genre</option>
                            <option value="male" {{ old('gender', $teacher->gender) == 'male' ? 'selected' : '' }}>👨 Masculin</option>
                            <option value="female" {{ old('gender', $teacher->gender) == 'female' ? 'selected' : '' }}>👩 Féminin</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $teacher->email) }}" placeholder="professeur@example.com">
                    </div>
                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $teacher->phone) }}" placeholder="+261 34 00 000 00">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="specialization">Spécialisation <span class="required">*</span></label>
                        <select id="specialization" name="specialization" required>
                            <option value="">Sélectionnez une spécialisation</option>
                            <option value="Mathématiques" {{ old('specialization', $teacher->specialization) == 'Mathématiques' ? 'selected' : '' }}>Mathématiques</option>
                            <option value="Français" {{ old('specialization', $teacher->specialization) == 'Français' ? 'selected' : '' }}>Français</option>
                            <option value="Sciences" {{ old('specialization', $teacher->specialization) == 'Sciences' ? 'selected' : '' }}>Sciences</option>
                            <option value="Histoire" {{ old('specialization', $teacher->specialization) == 'Histoire' ? 'selected' : '' }}>Histoire</option>
                            <option value="Géographie" {{ old('specialization', $teacher->specialization) == 'Géographie' ? 'selected' : '' }}>Géographie</option>
                            <option value="Anglais" {{ old('specialization', $teacher->specialization) == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                            <option value="Sport" {{ old('specialization', $teacher->specialization) == 'Sport' ? 'selected' : '' }}>Sport</option>
                            <option value="Arts" {{ old('specialization', $teacher->specialization) == 'Arts' ? 'selected' : '' }}>Arts</option>
                            <option value="Musique" {{ old('specialization', $teacher->specialization) == 'Musique' ? 'selected' : '' }}>Musique</option>
                            <option value="Informatique" {{ old('specialization', $teacher->specialization) == 'Informatique' ? 'selected' : '' }}>Informatique</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qualification">Qualification <span class="required">*</span></label>
                        <select id="qualification" name="qualification" required>
                            <option value="">Sélectionnez une qualification</option>
                            <option value="BEPC" {{ old('qualification', $teacher->qualification) == 'BEPC' ? 'selected' : '' }}>BEPC</option>
                            <option value="BACC" {{ old('qualification', $teacher->qualification) == 'BACC' ? 'selected' : '' }}>BACC</option>
                            <option value="Licence" {{ old('qualification', $teacher->qualification) == 'Licence' ? 'selected' : '' }}>Licence</option>
                            <option value="Master" {{ old('qualification', $teacher->qualification) == 'Master' ? 'selected' : '' }}>Master</option>
                            <option value="Doctorat" {{ old('qualification', $teacher->qualification) == 'Doctorat' ? 'selected' : '' }}>Doctorat</option>
                            <option value="CAPEN" {{ old('qualification', $teacher->qualification) == 'CAPEN' ? 'selected' : '' }}>CAPEN</option>
                            <option value="Autre" {{ old('qualification', $teacher->qualification) == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="hire_date">Date d'embauche <span class="required">*</span></label>
                        <input type="date" id="hire_date" name="hire_date" value="{{ old('hire_date', $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="salary">Salaire (Ar)</label>
                        <input type="number" id="salary" name="salary" value="{{ old('salary', $teacher->salary) }}" min="0" step="1000" placeholder="Ex: 500000">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Statut <span class="required">*</span></label>
                        <select id="status" name="status" required>
                            <option value="active" {{ old('status', $teacher->status) == 'active' ? 'selected' : '' }}>✅ Actif</option>
                            <option value="inactive" {{ old('status', $teacher->status) == 'inactive' ? 'selected' : '' }}>❌ Inactif</option>
                            <option value="suspended" {{ old('status', $teacher->status) == 'suspended' ? 'selected' : '' }}>⚠️ Suspendu</option>
                        </select>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="address">Adresse</label>
                    <textarea id="address" name="address" placeholder="Adresse complète du professeur">{{ old('address', $teacher->address) }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Mettre à jour le Professeur</button>
                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary">❌ Annuler</a>
                </div>
            </form>

            <div class="danger-zone">
                <h3>🗑️ Zone de Danger</h3>
                <p>La suppression de ce professeur est définitive et ne peut pas être annulée.</p>
                <form method="POST" action="{{ route('teachers.destroy', $teacher) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $teacher->full_name }} ?\n\nCette action est IRRÉVERSIBLE !')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">🗑️ Supprimer définitivement ce professeur</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
