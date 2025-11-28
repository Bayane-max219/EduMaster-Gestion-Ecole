<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports - EduMaster</title>
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
        .reports-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        .report-card { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s; cursor: pointer; }
        .report-card:hover { transform: translateY(-5px); }
        .report-icon { font-size: 3rem; text-align: center; margin-bottom: 1rem; }
        .report-title { font-size: 1.3rem; font-weight: bold; color: #20B2AA; margin-bottom: 1rem; text-align: center; }
        .report-description { color: #666; text-align: center; margin-bottom: 1.5rem; }
        .report-actions { display: flex; gap: 0.5rem; justify-content: center; }
        .btn { padding: 0.5rem 1rem; background: #20B2AA; color: white; text-decoration: none; border-radius: 5px; font-size: 0.9rem; transition: background 0.3s; }
        .btn:hover { background: #1a9a92; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 EduMaster</div>
        <div class="nav-links">
            <a href="/dashboard">Tableau de Bord</a>
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
            <h1 class="page-title">📊 Rapports et Statistiques</h1>
            <p>Générez et consultez les rapports de votre établissement</p>
        </div>
        
        <div class="reports-grid">
            <div class="report-card">
                <div class="report-icon">👥</div>
                <div class="report-title">Rapport des Élèves</div>
                <div class="report-description">
                    Statistiques sur les inscriptions, répartition par classe, évolution des effectifs
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.students.pdf') }}" class="btn">📄 Générer PDF</a>
                    <a href="{{ route('reports.students.charts') }}" class="btn btn-secondary">📊 Voir graphiques</a>
                </div>
            </div>
            
            <div class="report-card">
                <div class="report-icon">📝</div>
                <div class="report-title">Bulletin de Notes</div>
                <div class="report-description">
                    Moyennes par classe, par matière, classements et évolution des résultats
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.grades.pdf') }}" class="btn">📄 Générer PDF</a>
                    <a href="{{ route('reports.grades.charts') }}" class="btn btn-secondary">📊 Voir graphiques</a>
                </div>
            </div>
            
            <div class="report-card">
                <div class="report-icon">💰</div>
                <div class="report-title">Rapport des Paiements</div>
                <div class="report-description">
                    Liste filtrable, totaux, export CSV et impression
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.payments') }}" class="btn">📄 Ouvrir le rapport</a>
                    <a href="{{ route('reports.payments.export') }}" class="btn btn-secondary">⬇️ Export CSV</a>
                </div>
            </div>
            
            <div class="report-card">
                <div class="report-icon">📅</div>
                <div class="report-title">Rapport de Présence</div>
                <div class="report-description">
                    Taux d'assiduité, absences par classe, suivi des retards
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.attendances.pdf') }}" class="btn">📄 Générer PDF</a>
                    <a href="{{ route('reports.attendances.charts') }}" class="btn btn-secondary">📊 Voir graphiques</a>
                </div>
            </div>
            
            <div class="report-card">
                <div class="report-icon">👨‍🏫</div>
                <div class="report-title">Rapport des Professeurs</div>
                <div class="report-description">
                    Charge de travail, matières enseignées, évaluations des enseignants
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.teachers.pdf') }}" class="btn">📄 Générer PDF</a>
                    <a href="{{ route('reports.teachers.charts') }}" class="btn btn-secondary">📊 Voir graphiques</a>
                </div>
            </div>
            
            <div class="report-card">
                <div class="report-icon">📈</div>
                <div class="report-title">Tableau de Bord Exécutif</div>
                <div class="report-description">
                    Vue d'ensemble complète avec tous les indicateurs clés de performance
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.executive.pdf') }}" class="btn">📄 Générer PDF</a>
                    <a href="{{ route('reports.executive.charts') }}" class="btn btn-secondary">📊 Voir graphiques</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
