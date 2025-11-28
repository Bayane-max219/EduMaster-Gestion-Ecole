<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Frais - EduMaster</title>
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
        .form-container { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group.full-width { grid-column: 1 / -1; }
        label { display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500; }
        input, select, textarea { width: 100%; padding: 12px 15px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #20B2AA; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; font-size: 1rem; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.3s ease; }
        .btn-primary { background: #20B2AA; color: white; }
        .btn-primary:hover { background: #1a9a92; }
        .btn-secondary { background: #6c757d; color: white; margin-left: 1rem; }
        .btn-secondary:hover { background: #5a6268; }
        .form-actions { display: flex; align-items: center; margin-top: 2rem; }
        .error-message { background: #fee; color: #c33; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .required { color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
            <a href="{{ route('fees.index') }}">Gestion des Frais</a>
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
            <h1 class="page-title">✏️ Modifier le Frais</h1>
            <p>Mettez à jour les informations du frais</p>
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

            <form method="POST" action="{{ route('fees.update', $fee) }}">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Intitulé <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $fee->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Montant <span class="required">*</span></label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount', $fee->amount) }}" step="0.01" min="0" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="type">Type <span class="required">*</span></label>
                        <select id="type" name="type" required>
                            @foreach($types as $t)
                                <option value="{{ $t }}" {{ old('type', $fee->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="frequency">Fréquence <span class="required">*</span></label>
                        <select id="frequency" name="frequency" required>
                            @foreach($frequencies as $f)
                                <option value="{{ $f }}" {{ old('frequency', $fee->frequency) == $f ? 'selected' : '' }}>{{ ucfirst($f) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="due_date">Échéance</label>
                        <input type="date" id="due_date" name="due_date" value="{{ old('due_date', optional($fee->due_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label for="school_year_id">Année scolaire <span class="required">*</span></label>
                        <select id="school_year_id" name="school_year_id" required>
                            @foreach($schoolYears as $y)
                                <option value="{{ $y->id }}" {{ (string)old('school_year_id', $fee->school_year_id) === (string)$y->id ? 'selected' : '' }}>{{ $y->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="class_level">Niveau concerné</label>
                        <input type="text" id="class_level" name="class_level" value="{{ old('class_level', $fee->class_level) }}" placeholder="Ex: CM2, 4ème ...">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" placeholder="Détails...">{{ old('description', $fee->description) }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Mettre à jour le Frais</button>
                    <a href="{{ route('fees.show', $fee) }}" class="btn btn-secondary">↩️ Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
