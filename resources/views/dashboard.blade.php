<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - EduMaster</title>
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
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .welcome-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .welcome-title {
            font-size: 2rem;
            color: #20B2AA;
            margin-bottom: 1rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #20B2AA;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .quick-actions {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .quick-actions h3 {
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: #f8f9fa;
            color: #333;
            text-decoration: none;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            min-height: 56px;
            font-size: 0.95rem;
        }
        
        .action-btn:hover {
            background: #20B2AA;
            color: white;
            transform: translateY(-2px);
        }
        
        .logout-btn {
            background: #FF8C42;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background: #e67a35;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="user-info">
            <span>Bienvenue, {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn" style="background: none; border: none; color: white; cursor: pointer;">
                    🚪 Déconnexion
                </button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome-card">
            <div class="welcome-title">Tableau de Bord EduMaster</div>
            <p>Système de gestion scolaire moderne et intuitif</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number">{{ $usersCount ?? 0 }}</div>
                <div class="stat-label">Utilisateurs</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🎓</div>
                <div class="stat-number">{{ $classesCount ?? 0 }}</div>
                <div class="stat-label">Classes</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📚</div>
                <div class="stat-number">{{ $subjectsCount ?? 0 }}</div>
                <div class="stat-label">Matières</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-number">{{ $schoolYearsCount ?? 0 }}</div>
                <div class="stat-label">Années Scolaires</div>
            </div>
        </div>
        
        <div class="quick-actions">
            <h3>Actions Rapides</h3>
            <div class="actions-grid">
                <a href="{{ route('users.index') }}" class="action-btn">
                    👥 Gestion Utilisateurs
                </a>
                <a href="{{ route('students.index') }}" class="action-btn">
                    🎓 Gestion Élèves
                </a>
                <a href="{{ route('teachers.index') }}" class="action-btn">
                    👨‍🏫 Gestion Professeurs
                </a>
                <a href="{{ route('classes.index') }}" class="action-btn">
                    📚 Gestion Classes
                </a>
                <a href="{{ route('grades.index') }}" class="action-btn">
                    📝 Gestion Notes
                </a>
                <a href="{{ route('payments.index') }}" class="action-btn">
                    💰 Gestion Paiements
                </a>
                <a href="{{ route('fees.index') }}" class="action-btn">
                    💸 Gestion Frais
                </a>
                <a href="{{ route('reports.index') }}" class="action-btn">
                    📊 Rapports
                </a>
                <a href="{{ route('settings.index') }}" class="action-btn">
                    ⚙️ Paramètres
                </a>
            </div>
        </div>
    </div>
</body>
</html>
