<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduMaster - School Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            text-align: center;
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 600px;
        }
        
        .logo {
            font-size: 3rem;
            font-weight: bold;
            color: #20B2AA;
            margin-bottom: 1rem;
        }
        
        .subtitle {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 10px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #20B2AA;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1a9a92;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #FF8C42;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #e67a35;
            transform: translateY(-2px);
        }
        
        .features {
            margin-top: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .feature {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🎓 EduMaster</div>
        <div class="subtitle">Système de Gestion Scolaire Moderne</div>
        
        <div style="margin: 2rem 0;">
            <a href="/login" class="btn btn-primary">Se Connecter</a>
            <a href="/dashboard" class="btn btn-secondary">Tableau de Bord</a>
        </div>
        
        <div class="features">
            <div class="feature">
                <div class="feature-icon">👥</div>
                <h3>Gestion Utilisateurs</h3>
                <p>Admin, Directeur, Professeurs, Parents, Élèves</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">📚</div>
                <h3>Gestion Pédagogique</h3>
                <p>Classes, Matières, Notes, Bulletins</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">💰</div>
                <h3>Gestion Financière</h3>
                <p>Frais, Paiements, Reçus PDF</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">📊</div>
                <h3>Tableaux de Bord</h3>
                <p>Statistiques et Graphiques</p>
            </div>
        </div>
        
        <div style="margin-top: 2rem; color: #666; font-size: 0.9rem;">
            <p>✅ Base de données configurée</p>
            <p>✅ Laravel opérationnel</p>
            <p>✅ Comptes par défaut créés</p>
        </div>
    </div>
</body>
</html>
