<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Élève - EduMaster</title>
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
        .danger-zone p { color: #666; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
            <a href="{{ route('students.index') }}">Gestion Élèves</a>
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
            <h1 class="page-title">✏️ Modifier l'Élève</h1>
            <div class="student-info">
                ID: {{ $student->id }} | Créé le: {{ $student->created_at->format('d/m/Y à H:i') }}
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
            
            <form method="POST" action="{{ route('students.update', $student) }}">
                @csrf
                @method('PUT')
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nom complet <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="genre">Genre <span class="required">*</span></label>
                        <select id="genre" name="genre" required>
                            <option value="">Sélectionner le genre</option>
                            <option value="masculin" {{ old('genre', $student->genre) == 'masculin' ? 'selected' : '' }}>👦 Masculin</option>
                            <option value="feminin" {{ old('genre', $student->genre) == 'feminin' ? 'selected' : '' }}>👧 Féminin</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="classe">Classe <span class="required">*</span></label>
                        <select id="classe" name="classe" required>
                            <option value="">Sélectionner une classe</option>
                            
                            <optgroup label="🎒 PRIMAIRE">
                                <option value="CP" {{ old('classe', $student->classe) == 'CP' ? 'selected' : '' }}>CP (Cours Préparatoire)</option>
                                <option value="CE1" {{ old('classe', $student->classe) == 'CE1' ? 'selected' : '' }}>CE1 (Cours Élémentaire 1)</option>
                                <option value="CE2" {{ old('classe', $student->classe) == 'CE2' ? 'selected' : '' }}>CE2 (Cours Élémentaire 2)</option>
                                <option value="CM1" {{ old('classe', $student->classe) == 'CM1' ? 'selected' : '' }}>CM1 (Cours Moyen 1)</option>
                                <option value="CM2" {{ old('classe', $student->classe) == 'CM2' ? 'selected' : '' }}>CM2 (Cours Moyen 2)</option>
                                <option value="7ème" {{ old('classe', $student->classe) == '7ème' ? 'selected' : '' }}>7ème → CEPE</option>
                            </optgroup>
                            
                            <optgroup label="🏫 COLLÈGE">
                                <option value="6ème" {{ old('classe', $student->classe) == '6ème' ? 'selected' : '' }}>6ème</option>
                                <option value="5ème" {{ old('classe', $student->classe) == '5ème' ? 'selected' : '' }}>5ème</option>
                                <option value="4ème" {{ old('classe', $student->classe) == '4ème' ? 'selected' : '' }}>4ème</option>
                                <option value="3ème" {{ old('classe', $student->classe) == '3ème' ? 'selected' : '' }}>3ème → BEPC</option>
                            </optgroup>
                            
                            <optgroup label="🎓 LYCÉE">
                                <option value="2nde" {{ old('classe', $student->classe) == '2nde' ? 'selected' : '' }}>2nde (Seconde)</option>
                                <option value="1ère" {{ old('classe', $student->classe) == '1ère' ? 'selected' : '' }}>1ère (Première)</option>
                                <option value="Terminale" {{ old('classe', $student->classe) == 'Terminale' ? 'selected' : '' }}>Terminale → BACC</option>
                            </optgroup>
                            
                            <optgroup label="📚 AUTRES NIVEAUX">
                                <option value="8ème" {{ old('classe', $student->classe) == '8ème' ? 'selected' : '' }}>8ème</option>
                                <option value="9ème" {{ old('classe', $student->classe) == '9ème' ? 'selected' : '' }}>9ème</option>
                                <option value="10ème" {{ old('classe', $student->classe) == '10ème' ? 'selected' : '' }}>10ème</option>
                                <option value="11ème" {{ old('classe', $student->classe) == '11ème' ? 'selected' : '' }}>11ème</option>
                                <option value="12ème" {{ old('classe', $student->classe) == '12ème' ? 'selected' : '' }}>12ème</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Adresse Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="date_naissance">Date de naissance <span class="required">*</span></label>
                        <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $student->date_naissance) }}" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="parent_tuteur">Parent/Tuteur <span class="required">*</span></label>
                        <input type="text" id="parent_tuteur" name="parent_tuteur" value="{{ old('parent_tuteur', $student->parent_tuteur) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Téléphone des parents</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $student->phone) }}">
                    </div>
                </div>
                
                <div class="form-group full-width">
                    <label for="adresse">Adresse complète</label>
                    <textarea id="adresse" name="adresse">{{ old('adresse', $student->adresse) }}</textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">❌ Annuler</a>
                </div>
            </form>
            
            <div class="danger-zone">
                <h3>🗑️ Zone de Danger</h3>
                <p>La suppression de cet élève est définitive et ne peut pas être annulée.</p>
                
                <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $student->name }} ?\n\nCette action est IRRÉVERSIBLE !')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">🗑️ Supprimer définitivement cet élève</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
