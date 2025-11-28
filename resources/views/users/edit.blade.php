<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Utilisateur - EduMaster</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .page-title {
            font-size: 2rem;
            color: #20B2AA;
        }
        
        .user-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
        .form-group.full-width { grid-column: 1 / -1; }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        input:focus,
        textarea:focus {
            outline: none;
            border-color: #20B2AA;
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #20B2AA;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1a9a92;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
            margin-left: 1rem;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
            margin-left: 1rem;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .form-actions {
            display: flex;
            align-items: center;
            margin-top: 2rem;
        }
        
        .error-message {
            background: #fee;
            color: #c33;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .required {
            color: #dc3545;
        }
        .danger-zone { margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #eee; }
        .danger-zone h3 { color: #dc3545; margin-bottom: 1rem; }
        
        .password-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
        
        .password-section h3 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .note {
            background: #e7f3ff;
            color: #0066cc;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
            <a href="/users">Gestion Utilisateurs</a>
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
            <div>
                <h1 class="page-title">✏️ Modifier l'Utilisateur</h1>
                <div class="user-info">
                    ID: {{ $user->id }} | Créé le: {{ $user->created_at->format('d/m/Y à H:i') }}
                </div>
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
            
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nom complet <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Numéro de téléphone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+261 34 00 000 00">
                    </div>
                </div>
                
                <div class="form-group full-width">
                    <label for="address">Adresse</label>
                    <textarea id="address" name="address" placeholder="Adresse complète">{{ old('address', $user->address) }}</textarea>
                </div>
                
                <div class="password-section">
                    <h3>🔒 Changer le mot de passe</h3>
                    <div class="note">
                        <strong>Note :</strong> Laissez ces champs vides si vous ne souhaitez pas changer le mot de passe.
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password">
                        <small style="color: #666; font-size: 0.9rem;">Minimum 8 caractères</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">❌ Annuler</a>
                </div>
            </form>
            
            <!-- Formulaire séparé pour la suppression -->
            <div class="danger-zone">
                <h3>🗑️ Zone de Danger</h3>
                <p>La suppression de cet utilisateur est définitive et ne peut pas être annulée.</p>
                
                @if(auth()->id() !== $user->id)
                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?\n\nCette action est IRRÉVERSIBLE !\n\nCliquez OK pour confirmer la suppression.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">🗑️ Supprimer définitivement cet utilisateur</button>
                    </form>
                @else
                    <p style="color: #dc3545; font-style: italic;">
                        ⚠️ Vous ne pouvez pas supprimer votre propre compte.
                    </p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
