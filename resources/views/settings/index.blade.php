<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - EduMaster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #20B2AA 0%, #FF8C42 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        .nav-links { display: flex; gap: 1rem; }
        .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1000px; margin: 2rem auto; padding: 0 2rem; }
        .page-header { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; text-align: center; }
        .page-title { font-size: 2rem; color: #20B2AA; margin-bottom: 1rem; }
        .settings-sections { display: grid; gap: 2rem; }
        .settings-section { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .section-title { font-size: 1.3rem; color: #20B2AA; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .setting-item { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #eee; }
        .setting-item:last-child { border-bottom: none; }
        .setting-label { font-weight: 500; color: #333; }
        .setting-description { font-size: 0.9rem; color: #666; margin-top: 0.25rem; }
        .setting-control input, .setting-control select { padding: 0.5rem; border: 2px solid #e1e5e9; border-radius: 5px; }
        .btn { padding: 0.75rem 1.5rem; background: #20B2AA; color: white; text-decoration: none; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: background 0.3s; }
        .btn:hover { background: #1a9a92; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .toggle-switch { position: relative; display: inline-block; width: 60px; height: 34px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #20B2AA; }
        input:checked + .slider:before { transform: translateX(26px); }
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
            <h1 class="page-title">⚙️ Paramètres du Système</h1>
            <p>Configurez les paramètres de votre établissement scolaire</p>
        </div>
        
        <div class="settings-sections">
            <div class="settings-section">
                <h2 class="section-title">🏫 Informations de l'École</h2>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Nom de l'établissement</div>
                        <div class="setting-description">Le nom officiel de votre école</div>
                    </div>
                    <div class="setting-control">
                        <input type="text" value="École Primaire EduMaster" style="width: 250px;">
                    </div>
                </div>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Adresse</div>
                        <div class="setting-description">Adresse complète de l'établissement</div>
                    </div>
                    <div class="setting-control">
                        <input type="text" value="Lot II M 15 Antananarivo 101" style="width: 250px;">
                    </div>
                </div>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Téléphone</div>
                        <div class="setting-description">Numéro de téléphone principal</div>
                    </div>
                    <div class="setting-control">
                        <input type="text" value="+261 34 12 345 67" style="width: 200px;">
                    </div>
                </div>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Email</div>
                        <div class="setting-description">Adresse email officielle</div>
                    </div>
                    <div class="setting-control">
                        <input type="email" value="contact@edumaster.mg" style="width: 250px;">
                    </div>
                </div>
            </div>
            
            <div class="settings-section">
                <h2 class="section-title">📅 Année Scolaire</h2>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Année scolaire actuelle</div>
                        <div class="setting-description">Période académique en cours</div>
                    </div>
                    <div class="setting-control">
                        <select style="width: 200px;">
                            <option>2024-2025</option>
                            <option>2025-2026</option>
                        </select>
                    </div>
                </div>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Date de début</div>
                        <div class="setting-description">Début de l'année scolaire</div>
                    </div>
                    <div class="setting-control">
                        <input type="date" value="2024-09-01">
                    </div>
                </div>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Date de fin</div>
                        <div class="setting-description">Fin de l'année scolaire</div>
                    </div>
                    <div class="setting-control">
                        <input type="date" value="2025-06-30">
                    </div>
                </div>
            </div>
            
            <div class="settings-section">
                <h2 class="section-title">📝 Système de Notation</h2>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Échelle de notation</div>
                        <div class="setting-description">Système de notes utilisé</div>
                    </div>
                    <div class="setting-control">
                        <select style="width: 150px;">
                            <option>Sur 20</option>
                            <option>Sur 100</option>
                            <option>Lettres (A-F)</option>
                        </select>
                    </div>
                </div>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Note minimale de passage</div>
                        <div class="setting-description">Note minimum pour valider</div>
                    </div>
                    <div class="setting-control">
                        <input type="number" value="10" min="0" max="20" style="width: 100px;">
                    </div>
                </div>
            </div>
            
            <div class="settings-section">
                <h2 class="section-title">🔔 Notifications</h2>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Notifications par email</div>
                        <div class="setting-description">Recevoir les alertes par email</div>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Rappels de paiement</div>
                        <div class="setting-description">Envoyer des rappels automatiques</div>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="settings-section">
                <h2 class="section-title">🔒 Sécurité</h2>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Sauvegarde automatique</div>
                        <div class="setting-description">Fréquence des sauvegardes</div>
                    </div>
                    <div class="setting-control">
                        <select style="width: 150px;">
                            <option>Quotidienne</option>
                            <option>Hebdomadaire</option>
                            <option>Mensuelle</option>
                        </select>
                    </div>
                </div>
                <div class="setting-item">
                    <div>
                        <div class="setting-label">Durée de session</div>
                        <div class="setting-description">Temps avant déconnexion automatique</div>
                    </div>
                    <div class="setting-control">
                        <select style="width: 150px;">
                            <option>30 minutes</option>
                            <option>1 heure</option>
                            <option>2 heures</option>
                            <option>4 heures</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 2rem;">
                <button class="btn" style="margin-right: 1rem;">💾 Enregistrer les modifications</button>
                <button class="btn btn-danger">🔄 Réinitialiser</button>
            </div>
        </div>
    </div>
</body>
</html>
