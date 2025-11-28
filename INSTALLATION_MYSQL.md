# 🗄️ Installation Base de Données MySQL - EduMaster

## 📋 Étapes d'Installation

### 1. 🚀 Créer la Base de Données via phpMyAdmin

1. **Ouvrir phpMyAdmin** dans votre navigateur (généralement `http://localhost/phpmyadmin`)

2. **Se connecter** avec vos identifiants MySQL

3. **Créer une nouvelle base de données** :
   - Cliquer sur "Nouvelle base de données" ou "New"
   - Nom : `edumaster_school`
   - Interclassement : `utf8mb4_unicode_ci`
   - Cliquer "Créer"

### 2. 📝 Exécuter le Script SQL

1. **Sélectionner la base** `edumaster_school` dans phpMyAdmin

2. **Aller dans l'onglet "SQL"**

3. **Copier tout le contenu** du fichier `database_setup.sql`

4. **Coller dans la zone de texte** SQL de phpMyAdmin

5. **Cliquer "Exécuter"** pour créer toutes les tables

### 3. ⚙️ Configuration Laravel

1. **Modifier le fichier `.env`** dans le projet Laravel :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edumaster_school
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe_mysql
```

2. **Remplacer** `votre_mot_de_passe_mysql` par votre vrai mot de passe MySQL

### 4. ✅ Vérification

Après l'exécution du script, vous devriez avoir :

#### **📊 Tables Créées (22 tables)** :
- `users` - Utilisateurs du système
- `roles` - Rôles (Admin, Directeur, etc.)
- `permissions` - Permissions granulaires
- `model_has_roles` - Attribution des rôles
- `model_has_permissions` - Attribution des permissions
- `role_has_permissions` - Permissions par rôle
- `school_years` - Années scolaires
- `subjects` - Matières
- `class_rooms` - Classes
- `teachers` - Professeurs
- `students` - Élèves
- `parent_models` - Parents
- `student_parent` - Liaison élèves-parents
- `enrollments` - Inscriptions
- `teacher_subjects` - Professeurs-matières
- `teacher_classes` - Professeurs-classes
- `timetable_entries` - Emploi du temps
- `attendances` - Présences
- `grades` - Notes
- `fees` - Frais scolaires
- `payments` - Paiements
- `bulletins` - Bulletins
- `disciplinary_records` - Sanctions
- `notifications` - Notifications
- `sessions` - Sessions Laravel
- `jobs` - Tâches en queue
- `failed_jobs` - Tâches échouées

#### **👤 Utilisateurs par Défaut** :
| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@edumaster.mg | password |
| Directeur | director@edumaster.mg | password |
| Secrétaire | secretary@edumaster.mg | password |
| Professeur | teacher@edumaster.mg | password |
| Parent | parent@edumaster.mg | password |

#### **📚 Données de Base** :
- **6 rôles** avec permissions appropriées
- **2 années scolaires** (2024-2025 active, 2023-2024 archivée)
- **7 matières** de base (Maths, Français, Anglais, etc.)
- **12 classes** (CP à Terminale)

### 5. 🔧 Finalisation Laravel

1. **Installer les dépendances** :
```bash
composer install
```

2. **Générer la clé d'application** :
```bash
php artisan key:generate
```

3. **Créer le lien de stockage** :
```bash
php artisan storage:link
```

4. **Démarrer le serveur** :
```bash
php artisan serve
```

### 6. 🌐 Accès à l'Application

1. **Ouvrir** `http://127.0.0.1:8000` dans votre navigateur

2. **Se connecter** avec :
   - Email : `admin@edumaster.mg`
   - Mot de passe : `password`

3. **Explorer** le tableau de bord administrateur

## 🛠️ Dépannage

### ❌ Erreur de Connexion MySQL
- Vérifiez que MySQL est démarré
- Vérifiez les identifiants dans `.env`
- Vérifiez que la base `edumaster_school` existe

### ❌ Erreur de Permissions
- Vérifiez que l'utilisateur MySQL a les droits sur la base
- Essayez avec l'utilisateur `root`

### ❌ Erreur d'Encodage
- Assurez-vous que la base utilise `utf8mb4_unicode_ci`
- Vérifiez la configuration MySQL

### ❌ Tables Non Créées
- Exécutez le script SQL par petites parties
- Vérifiez les messages d'erreur dans phpMyAdmin
- Assurez-vous que la base est sélectionnée

## 📞 Support

Si vous rencontrez des problèmes :

1. **Vérifiez** les logs Laravel dans `storage/logs/`
2. **Consultez** la documentation Laravel
3. **Contactez** le support technique

---

## 🎯 Résultat Attendu

Après cette installation, vous aurez :

✅ **Base de données complète** avec toutes les tables  
✅ **Utilisateurs par défaut** pour tous les rôles  
✅ **Données de base** (matières, classes, années)  
✅ **Système de permissions** fonctionnel  
✅ **Application Laravel** opérationnelle  

**Votre système EduMaster est prêt à être utilisé !** 🎓✨
