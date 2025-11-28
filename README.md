# 🎓 EduMaster - School Management System

**EduMaster** est une solution Laravel complète de gestion scolaire couvrant les modules d'administration des élèves, professeurs, classes, notes, paiements et emploi du temps, avec rôles sécurisés, tableau de bord analytique, génération PDF et reporting avancé dans une interface moderne turquoise & orange.

## 🌟 Fonctionnalités Principales

### 👥 Gestion Utilisateurs & Accès
- **Rôles multiples** : Admin, Proviseur/Directeur, Secrétariat, Professeurs, Parents, Élèves
- **Authentification sécurisée** avec Laravel Sanctum
- **Permissions granulaires** avec Spatie Permission
- **Historique des actions** et logs d'audit

### 🏫 Gestion Pédagogique
- **Années scolaires** avec gestion des périodes
- **Niveaux éducatifs** : CP → Terminale
- **Classes & sections** avec capacités
- **Matières** et programmes scolaires
- **Cahiers de texte** et planification professeurs

### 🗓️ Emploi du Temps
- **Création automatique** ou manuelle des horaires
- **Vue hebdomadaire** par classe et par professeur
- **Gestion des remplacements** et absences
- **Export PDF/Excel** des plannings
- **Synchronisation** avec présences et cours

### 👨‍🎓 Gestion Élèves
- **Inscription & réinscription** simplifiées
- **Dossier scolaire complet** avec photos
- **Suivi des absences & retards** avec QR code scanning
- **Discipline** : sanctions et observations
- **Export dossiers PDF** personnalisés

### 🧑‍🏫 Gestion Professeurs
- **Affectation matières & classes**
- **Emploi du temps personnel**
- **Suivi saisie des notes**
- **Notifications** réunions et devoirs

### 📝 Notes & Bulletins
- **Contrôles, Devoirs, Examens**
- **Calcul automatique** moyennes & classements
- **Bulletins PDF** personnalisés par élève
- **Historique semestriel/annuel**
- **Graphiques de progression**

### 💰 Frais & Paiements
- **Gestion des frais** et échéances
- **États** : payé/partiel/impayé
- **Reçus PDF automatiques**
- **Rapports financiers** détaillés
- **Notifications SMS/Email** retards de paiement

### 📊 Tableau de Bord Analytique
#### Admin/Directeur :
- Nombre d'élèves par niveau
- Statistiques professeurs
- Total impayés et revenus
- Graphiques d'évolution des effectifs

#### Professeur :
- Devoirs/notes à corriger
- Présences du jour
- Planning personnel

### 🔔 Notifications & Communication
- **Messages internes** Admin → Prof/Classe
- **Annonces d'examens**
- **Alertes discipline**
- **Communication Parents-École**

## 🎨 Interface & Design

### Thème Professionnel
- **Couleurs principales** : Turquoise (#20B2AA) & Orange (#FF8C42)
- **Design moderne** et responsive
- **Interface intuitive** avec navigation contextuelle
- **Graphiques interactifs** avec Chart.js

### Technologies Frontend
- **Livewire 3.0** pour l'interactivité
- **Alpine.js** pour les composants dynamiques
- **TailwindCSS** pour le styling
- **Font Awesome** pour les icônes
- **Chart.js** pour les statistiques

## 📸 Captures d’écran

- **01 – Page d’accueil (Tableau de bord / vue globale)**  
  ![01 – Accueil](screenshoots/01-Accueil.png)
- **02 – Page de connexion**  
  ![02 – Connexion](screenshoots/02-Connexion.png)
- **03 – Tableau de bord administrateur**  
  ![03 – Tableau de bord](screenshoots/03-Tableau_De_Bord.png)
- **04 – Gestion des utilisateurs**  
  ![04 – Gestion utilisateurs](screenshoots/04-Gestion_Utilisateur.png)
- **05 – Gestion des élèves**  
  ![05 – Gestion élèves](screenshoots/05-Gestion_Eleve.png)
- **06 – Gestion des professeurs**  
  ![06 – Gestion professeurs](screenshoots/06-Gestion_Professeur.png)
- **07 – Gestion des classes**  
  ![07 – Gestion classes](screenshoots/07-Gestion_Classe.png)
- **08 – Gestion des notes**  
  ![08 – Gestion notes](screenshoots/08-Gestion_Note.png)
- **Ajout d’un utilisateur**  
  ![Ajout utilisateur](screenshoots/Ajout_Utilisateur.png)
- **Détail d’un élève**  
  ![Détail élève](screenshoots/Details_Eleve.png)
- **Détail d’un professeur**  
  ![Détail professeur](screenshoots/Details_Prof.png)
- **Détail d’une note**  
  ![Détail note](screenshoots/Details_Note.png)
- **Détail d’un paiement**  
  ![Détail paiement](screenshoots/Details_Paiement.png)
- **Gestion des frais**  
  ![Gestion des frais](screenshoots/Gestion_Frais.png)
- **Gestion des paiements**  
  ![Gestion des paiements](screenshoots/Gestion_Paiement.png)
- **Rapport des paiements**  
  ![Rapport paiements](screenshoots/Rapport_Paiement.png)
- **Rapport des professeurs**  
  ![Rapport professeurs](screenshoots/Rapport_Professeur.png)
- **Vue globale des rapports**  
  ![Rapports](screenshoots/Rapports.png)
- **Impression des notes / bulletins**  
  ![Impression des notes](screenshoots/Impression_Note.png)
- **Page des paramètres de l’application**  
  ![Paramètres](screenshoots/Parametre.png)

## 🗄️ Architecture Base de Données

### Tables Principales
```
users, roles, permissions, model_has_roles
school_years, classes, subjects, teachers, students
enrollments, attendances, grades, timetables
fees, payments, bulletins, notifications
disciplinary_records, parent_student
```

### Relations Eloquent Avancées
- **OneToMany** : User → Student/Teacher
- **ManyToMany** : Student ↔ Parent, Teacher ↔ Subject
- **Polymorphic** : Notifications, Attachments
- **Soft Deletes** : Préservation historique

## 🚀 Installation

### Prérequis
- PHP 8.1+
- Composer
- MySQL 8.0+ ou PostgreSQL 13+
- Node.js 16+ (pour les assets)

### Installation Rapide

```bash
# 1. Cloner le projet
git clone https://github.com/your-repo/edumaster.git
cd edumaster

# 2. Installer les dépendances
composer install
npm install

# 3. Configuration environnement
cp .env.example .env
php artisan key:generate

# 4. Configuration base de données
# Éditer .env avec vos paramètres DB

# 5. Migrations et seeders
php artisan migrate --seed

# 6. Permissions et stockage
php artisan storage:link
php artisan permission:cache-reset

# 7. Compilation assets
npm run build

# 8. Lancement serveur
php artisan serve
```

### Configuration Base de Données

#### MySQL
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edumaster_school
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### PostgreSQL
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=edumaster_school
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

## 👤 Comptes par Défaut

Après `php artisan db:seed` :

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@edumaster.mg | password |
| Directeur | director@edumaster.mg | password |
| Secrétaire | secretary@edumaster.mg | password |
| Professeur | teacher@edumaster.mg | password |
| Parent | parent@edumaster.mg | password |

## 📦 Packages Utilisés

### Backend Laravel
- **spatie/laravel-permission** : Gestion rôles/permissions
- **barryvdh/laravel-dompdf** : Génération PDF
- **maatwebsite/excel** : Import/Export Excel
- **intervention/image** : Traitement images
- **livewire/livewire** : Composants interactifs
- **laravel/horizon** : Queue monitoring

### Frontend
- **Alpine.js** : Réactivité JavaScript
- **Chart.js** : Graphiques et statistiques
- **Font Awesome** : Icônes
- **TailwindCSS** : Framework CSS

## 🧪 Tests

```bash
# Tests unitaires
php artisan test

# Tests avec couverture
php artisan test --coverage

# Tests spécifiques
php artisan test --filter=StudentTest
```

## 📈 Performance

### Optimisations Incluses
- **Query optimization** avec Eloquent
- **Eager loading** pour éviter N+1
- **Cache Redis** pour sessions
- **Queue system** pour emails/notifications
- **Image optimization** automatique

### Monitoring
- **Laravel Horizon** pour les queues
- **Logs structurés** avec rotation
- **Health checks** intégrés

## 🔒 Sécurité

### Mesures Implémentées
- **CSRF Protection** sur tous les formulaires
- **XSS Prevention** avec validation stricte
- **SQL Injection** protection via Eloquent
- **Rate Limiting** sur authentification
- **Permissions granulaires** par rôle

## 🌍 Déploiement

### Serveur Partagé
```bash
# Optimisation production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Railway/Heroku
Configuration automatique avec `Procfile` inclus.

## 📞 Support

### Documentation
- **Wiki complet** : `/docs`
- **API Documentation** : `/api/documentation`
- **Changelog** : `CHANGELOG.md`

### Contact
- **Email** : support@edumaster.mg
- **GitHub Issues** : Pour bugs et features
- **Discord** : Communauté développeurs

## 📄 Licence

Ce projet est sous licence **MIT**. Voir `LICENSE` pour plus de détails.

---

## 🏆 Démonstration Recruteur

**EduMaster** démontre une maîtrise complète de :

✅ **Architecture Laravel MVC** avancée  
✅ **Relations Eloquent** complexes  
✅ **Système de permissions** granulaire  
✅ **Interface moderne** responsive  
✅ **Génération PDF/Excel** automatique  
✅ **Tests unitaires** complets  
✅ **Optimisations performance**  
✅ **Sécurité** renforcée  
✅ **Code clean** et documenté  

**Prêt pour production** et **évolutif** pour des milliers d'utilisateurs.

---

*Développé avec ❤️ pour l'éducation malgache*
