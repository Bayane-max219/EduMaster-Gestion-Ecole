<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Élève - EduMaster</title>
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
        .form-actions { display: flex; align-items: center; margin-top: 2rem; }
        .error-message { background: #fee; color: #c33; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .required { color: #dc3545; }
        .status-inactive { background: #f8d7da; color: #721c24; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem; }
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
            <h1 class="page-title">👦 Créer un Nouvel Élève</h1>
            <p>Ajoutez un nouvel élève à votre établissement</p>
        </div>
        
        <div class="form-container">
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
            
            <form method="POST" action="{{ route('students.store') }}">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nom complet <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Ex: RAKOTO Jean Michel">
                    </div>
                    
                    <div class="form-group">
                        <label for="genre">Genre <span class="required">*</span></label>
                        <select id="genre" name="genre" required>
                            <option value="">Sélectionner le genre</option>
                            <option value="masculin" {{ old('genre') == 'masculin' ? 'selected' : '' }}>👦 Masculin</option>
                            <option value="feminin" {{ old('genre') == 'feminin' ? 'selected' : '' }}>👧 Féminin</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="classe">Classe <span class="required">*</span></label>
                        <select id="classe" name="classe" required>
                            <option value="">Sélectionner une classe</option>
                            
                            @php
                                // Récupérer les classes de la base de données
                                $classRoomsDB = DB::table('class_rooms')->get();
                                
                                // Organiser par niveau
                                $classesParNiveau = [
                                    'PRIMAIRE' => ['CP', 'CE1', 'CE2', 'CM1', 'CM2', '7ème'],
                                    'COLLÈGE' => ['6ème', '5ème', '4ème', '3ème'],
                                    'LYCÉE' => ['2nde', '1ère', 'Terminale'],
                                    'AUTRES' => ['8ème', '9ème', '10ème', '11ème', '12ème']
                                ];
                                
                                // Classes de la DB organisées
                                $classesDB = $classRoomsDB->groupBy('level');
                            @endphp
                            
                            @if($classRoomsDB->count() > 0)
                                <optgroup label="📚 CLASSES DE L'ÉCOLE">
                                    @foreach($classRoomsDB->unique('name')->sortBy('name') as $room)
                                        <option value="{{ $room->name }}" {{ old('classe') == $room->name ? 'selected' : '' }}>
                                            {{ $room->name }} @if($room->section) - {{ $room->section }} @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            
                            <optgroup label="🎒 PRIMAIRE">
                                @foreach($classesParNiveau['PRIMAIRE'] as $classe)
                                    <option value="{{ $classe }}" {{ old('classe') == $classe ? 'selected' : '' }}>
                                        {{ $classe }}{{ $classe == '7ème' ? ' → CEPE' : '' }}
                                    </option>
                                @endforeach
                            </optgroup>
                            
                            <optgroup label="🏫 COLLÈGE">
                                @foreach($classesParNiveau['COLLÈGE'] as $classe)
                                    <option value="{{ $classe }}" {{ old('classe') == $classe ? 'selected' : '' }}>
                                        {{ $classe }}{{ $classe == '3ème' ? ' → BEPC' : '' }}
                                    </option>
                                @endforeach
                            </optgroup>
                            
                            <optgroup label="🎓 LYCÉE">
                                @foreach($classesParNiveau['LYCÉE'] as $classe)
                                    <option value="{{ $classe }}" {{ old('classe') == $classe ? 'selected' : '' }}>
                                        {{ $classe }}{{ $classe == 'Terminale' ? ' → BACC' : '' }}
                                    </option>
                                @endforeach
                            </optgroup>
                            
                            <optgroup label="📚 AUTRES NIVEAUX">
                                @foreach($classesParNiveau['AUTRES'] as $classe)
                                    <option value="{{ $classe }}" {{ old('classe') == $classe ? 'selected' : '' }}>{{ $classe }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_naissance">Date de naissance <span class="required">*</span></label>
                        <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Adresse Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="eleve@example.com">
                        <small style="color: #666; font-size: 0.9rem;">Optionnel - pour les élèves plus âgés</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Téléphone des parents</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+261 34 00 000 00">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="parent_tuteur">Parent/Tuteur <span class="required">*</span></label>
                        <input type="text" id="parent_tuteur" name="parent_tuteur" value="{{ old('parent_tuteur') }}" required placeholder="Ex: M. & Mme RAKOTO">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Téléphone des parents</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+261 34 00 000 00">
                    </div>
                </div>
                
                <div class="form-group full-width">
                    <label for="adresse">Adresse complète</label>
                    <textarea id="adresse" name="adresse" placeholder="Adresse de résidence de l'élève">{{ old('adresse') }}</textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">✅ Créer l'Élève</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">❌ Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
