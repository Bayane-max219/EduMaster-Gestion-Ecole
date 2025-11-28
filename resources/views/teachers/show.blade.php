<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil du Professeur - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-links { display: flex; gap: 1rem; }
        .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1000px; margin: 2rem auto; padding: 0 2rem; }
        .page-header { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 2rem; color: #20B2AA; }
        .teacher-avatar { font-size: 4rem; text-align: center; margin-bottom: 1rem; }
        .profile-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; }
        .profile-card { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .profile-section { margin-bottom: 2rem; }
        .section-title { font-size: 1.3rem; color: #20B2AA; margin-bottom: 1rem; border-bottom: 2px solid #e1e5e9; padding-bottom: 0.5rem; }
        .info-item { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #f0f0f0; }
        .info-item:last-child { border-bottom: none; }
        .info-label { font-weight: bold; color: #333; }
        .info-value { color: #666; }
        .status-active { background: #d4edda; color: #155724; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem; }
        .status-inactive { background: #f8d7da; color: #721c24; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem; }
        .btn { padding: 0.75rem 1.5rem; background: #20B2AA; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; transition: background 0.3s; margin-right: 1rem; }
        .btn:hover { background: #1a9a92; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-warning { background: #ffc107; color: #333; }
        .btn-warning:hover { background: #e0a800; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); text-align: center; }
        .stat-number { font-size: 2rem; font-weight: bold; color: #20B2AA; }
        .stat-label { color: #666; margin-top: 0.5rem; }
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
            <div>
                <h1 class="page-title">{{ $teacher->gender == 'female' ? '👩‍🏫' : '👨‍🏫' }} Profil du Professeur</h1>
                <p style="color: #666; margin-top: 0.5rem;">Informations détaillées de {{ $teacher->full_name }}</p>
            </div>
            <div>
                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning">✏️ Modifier</a>
                <a href="{{ route('teachers.index') }}" class="btn btn-secondary">← Retour</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->age : 'N/A' }}</div>
                <div class="stat-label">Âge (ans)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $teacher->specialization ?? 'N/A' }}</div>
                <div class="stat-label">Spécialisation</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $teacher->hire_date ? \Carbon\Carbon::parse($teacher->hire_date)->diffInYears(now()) : 'N/A' }}</div>
                <div class="stat-label">Années de service</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <span class="{{ $teacher->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        {{ $teacher->status === 'active' ? 'Actif' : ($teacher->status === 'suspended' ? 'Suspendu' : 'Inactif') }}
                    </span>
                </div>
                <div class="stat-label">Statut</div>
            </div>
        </div>

        <div class="profile-grid">
            <div class="profile-card">
                <div class="teacher-avatar">{{ $teacher->gender == 'female' ? '👩‍🏫' : '👨‍🏫' }}</div>
                <h2 style="text-align: center; color: #20B2AA; margin-bottom: 2rem;">{{ $teacher->full_name }}</h2>

                <div class="profile-section">
                    <h3 class="section-title">📋 Informations Personnelles</h3>
                    <div class="info-item">
                        <span class="info-label">Nom complet :</span>
                        <span class="info-value">{{ $teacher->full_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date de naissance :</span>
                        <span class="info-value">{{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d/m/Y') : 'Non renseignée' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Âge :</span>
                        <span class="info-value">{{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->age . ' ans' : 'Non calculé' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Genre :</span>
                        <span class="info-value">{{ $teacher->gender == 'female' ? 'Féminin' : 'Masculin' }}</span>
                    </div>
                </div>

                <div class="profile-section">
                    <h3 class="section-title">📇 Identifiants & Contact</h3>
                    <div class="info-item">
                        <span class="info-label">Numéro professeur :</span>
                        <span class="info-value"><code>{{ $teacher->teacher_number ?? 'Non assigné' }}</code></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email :</span>
                        <span class="info-value">{{ $teacher->email ?? 'Non renseigné' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Téléphone :</span>
                        <span class="info-value">{{ $teacher->phone ?? 'Non renseigné' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Adresse :</span>
                        <span class="info-value">{{ $teacher->address ?? 'Non renseignée' }}</span>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-section">
                    <h3 class="section-title">🏫 Informations Professionnelles</h3>
                    <div class="info-item">
                        <span class="info-label">Spécialisation :</span>
                        <span class="info-value">{{ $teacher->specialization ?? 'Non renseignée' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Qualification :</span>
                        <span class="info-value">{{ $teacher->qualification ?? 'Non renseignée' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date d'embauche :</span>
                        <span class="info-value">{{ $teacher->hire_date ? \Carbon\Carbon::parse($teacher->hire_date)->format('d/m/Y') : 'Non renseignée' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Salaire :</span>
                        <span class="info-value">{{ $teacher->salary ? number_format($teacher->salary, 0, ',', ' ') . ' Ar' : 'Non renseigné' }}</span>
                    </div>
                </div>

                <div class="profile-section">
                    <h3 class="section-title">ℹ️ Informations Supplémentaires</h3>
                    <div class="info-item">
                        <span class="info-label">Contact d'urgence :</span>
                        <span class="info-value">{{ $teacher->emergency_contact ?? 'Non renseigné' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Téléphone d'urgence :</span>
                        <span class="info-value">{{ $teacher->emergency_phone ?? 'Non renseigné' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Notes :</span>
                        <span class="info-value">{{ $teacher->notes ?? 'Aucune note' }}</span>
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('teachers.edit', $teacher) }}" class="btn">✏️ Modifier les informations</a>
                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary">← Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
