<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $usersCount = DB::table('users')->count();
    $classesCount = Schema::hasTable('class_rooms') ? DB::table('class_rooms')->count() : 0;
    $subjectsCount = Schema::hasTable('subjects') ? DB::table('subjects')->count() : 0;
    $schoolYearsCount = Schema::hasTable('school_years') ? DB::table('school_years')->count() : 0;

    return view('dashboard', [
        'usersCount' => $usersCount,
        'classesCount' => $classesCount,
        'subjectsCount' => $subjectsCount,
        'schoolYearsCount' => $schoolYearsCount,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Route de test pour vérifier les utilisateurs
Route::get('/test-users', function () {
    $users = \App\Models\User::all();
    $html = '<h1>Utilisateurs dans la base de données :</h1>';
    foreach ($users as $user) {
        $html .= '<p><strong>Email:</strong> ' . $user->email . ' | <strong>Name:</strong> ' . $user->name . '</p>';
    }
    
    // Test de création d'un utilisateur avec mot de passe haché
    $testUser = \App\Models\User::where('email', 'admin@edumaster.mg')->first();
    if ($testUser) {
        $html .= '<h2>Test utilisateur admin :</h2>';
        $html .= '<p>Email: ' . $testUser->email . '</p>';
        $html .= '<p>Password hash: ' . substr($testUser->password, 0, 50) . '...</p>';
        
        // Vérifier si le mot de passe "password" correspond
        $passwordCheck = \Illuminate\Support\Facades\Hash::check('password', $testUser->password);
        $html .= '<p>Password "password" matches: ' . ($passwordCheck ? 'OUI' : 'NON') . '</p>';
    }
    
    return $html;
});

// Route pour corriger les mots de passe
Route::get('/fix-passwords', function () {
    $users = [
        ['email' => 'admin@edumaster.mg', 'name' => 'Administrateur EduMaster'],
        ['email' => 'director@edumaster.mg', 'name' => 'Directeur École'],
        ['email' => 'secretary@edumaster.mg', 'name' => 'Secrétaire École'],
        ['email' => 'teacher@edumaster.mg', 'name' => 'Professeur Rakoto'],
        ['email' => 'parent@edumaster.mg', 'name' => 'Parent Rabe'],
    ];
    
    $html = '<h1>Correction des mots de passe :</h1>';
    
    foreach ($users as $userData) {
        $user = \App\Models\User::where('email', $userData['email'])->first();
        if ($user) {
            $user->password = \Illuminate\Support\Facades\Hash::make('password');
            $user->save();
            $html .= '<p>✅ Mot de passe corrigé pour : ' . $userData['email'] . '</p>';
        } else {
            $html .= '<p>❌ Utilisateur non trouvé : ' . $userData['email'] . '</p>';
        }
    }
    
    $html .= '<p><a href="/test-users">Vérifier les utilisateurs</a></p>';
    $html .= '<p><a href="/login">Tester la connexion</a></p>';
    
    return $html;
});

// Routes pour les modules de gestion (nécessitent une authentification)
Route::middleware('auth')->group(function () {
    // Gestion des utilisateurs
    Route::resource('users', \App\Http\Controllers\UserController::class);
    
    // Gestion des élèves
    Route::resource('students', \App\Http\Controllers\StudentController::class);
    
    // Gestion des professeurs
    Route::resource('teachers', \App\Http\Controllers\TeacherController::class);
    
    // Route de test simple pour les professeurs
    Route::get('teachers-simple', function () {
        $teachers = \App\Models\Teacher::all();
        $html = '<h1>Liste des Professeurs</h1>';
        $html .= '<a href="/teachers/create" style="background: #20B2AA; color: white; padding: 10px; text-decoration: none; border-radius: 5px;">+ Nouveau Professeur</a><br><br>';
        
        if ($teachers->count() > 0) {
            $html .= '<table border="1" style="border-collapse: collapse; width: 100%;">';
            $html .= '<tr><th>Nom</th><th>Email</th><th>Spécialisation</th><th>Statut</th><th>Actions</th></tr>';
            foreach ($teachers as $teacher) {
                $html .= '<tr>';
                $html .= '<td>' . $teacher->full_name . '</td>';
                $html .= '<td>' . ($teacher->email ?: 'N/A') . '</td>';
                $html .= '<td>' . $teacher->specialization . '</td>';
                $html .= '<td>' . $teacher->status . '</td>';
                $html .= '<td><a href="/teachers/' . $teacher->id . '">Voir</a> | <a href="/teachers/' . $teacher->id . '/edit">Modifier</a></td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html .= '<p>Aucun professeur trouvé. <a href="/teachers/create">Créer le premier professeur</a></p>';
        }
        
        return $html;
    })->name('teachers.simple');
    
    Route::get('teachers-old', function () {
        $teachers = \App\Models\Teacher::paginate(10);
        return view('teachers.index', compact('teachers'));
    });
    
    // Gestion des classes (CRUD complet)
    Route::resource('classes', \App\Http\Controllers\ClassRoomController::class);
    
    // Gestion des matières (temporaire)
    Route::get('subjects', function () {
        return '<h1>Matières - En cours de développement</h1><a href="/dashboard">Retour au tableau de bord</a>';
    })->name('subjects.index');
    
    // Gestion des notes
    Route::resource('grades', \App\Http\Controllers\GradeController::class);
    
    // Routes temporaires pour éviter les erreurs
    Route::get('attendances', function () {
        return '<h1>Présences - En cours de développement</h1><a href="/dashboard">Retour au tableau de bord</a>';
    })->name('attendances.index');
    
    Route::resource('fees', \App\Http\Controllers\FeeController::class);
    Route::resource('payments', \App\Http\Controllers\PaymentController::class);
    
    Route::get('timetables', function () {
        return '<h1>Emploi du Temps - En cours de développement</h1><a href="/dashboard">Retour au tableau de bord</a>';
    })->name('timetables.index');
    
    Route::get('reports', [\App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    Route::get('reports/payments', [\App\Http\Controllers\ReportsController::class, 'payments'])->name('reports.payments');
    Route::get('reports/payments/export', [\App\Http\Controllers\ReportsController::class, 'exportPaymentsCsv'])->name('reports.payments.export');
    Route::prefix('reports')->group(function () {
        Route::get('students/pdf', [\App\Http\Controllers\ReportsController::class, 'studentsPdf'])->name('reports.students.pdf');
        Route::get('students/charts', [\App\Http\Controllers\ReportsController::class, 'studentsCharts'])->name('reports.students.charts');

        Route::get('grades/pdf', [\App\Http\Controllers\ReportsController::class, 'gradesPdf'])->name('reports.grades.pdf');
        Route::get('grades/charts', [\App\Http\Controllers\ReportsController::class, 'gradesCharts'])->name('reports.grades.charts');

        Route::get('attendances/pdf', [\App\Http\Controllers\ReportsController::class, 'attendancesPdf'])->name('reports.attendances.pdf');
        Route::get('attendances/charts', [\App\Http\Controllers\ReportsController::class, 'attendancesCharts'])->name('reports.attendances.charts');

        Route::get('teachers/pdf', [\App\Http\Controllers\ReportsController::class, 'teachersPdf'])->name('reports.teachers.pdf');
        Route::get('teachers/charts', [\App\Http\Controllers\ReportsController::class, 'teachersCharts'])->name('reports.teachers.charts');

        Route::get('executive/pdf', [\App\Http\Controllers\ReportsController::class, 'executivePdf'])->name('reports.executive.pdf');
        Route::get('executive/charts', [\App\Http\Controllers\ReportsController::class, 'executiveCharts'])->name('reports.executive.charts');
    });
    
    Route::get('profile/edit', function () {
        return '<h1>Profil - En cours de développement</h1><a href="/dashboard">Retour au tableau de bord</a>';
    })->name('profile.edit');
    
    // Routes pour professeurs
    Route::get('teacher/classes', function () {
        return '<h1>Mes Classes - En cours de développement</h1><a href="/dashboard">Retour au tableau de bord</a>';
    })->name('teacher.classes');
    
    Route::get('teacher/students', function () {
        return '<h1>Mes Élèves - En cours de développement</h1><a href="/dashboard">Retour au tableau de bord</a>';
    })->name('teacher.students');
    
    Route::get('teacher/timetable', function () {
        return '<h1>Mon Emploi du Temps - En cours de développement</h1><a href="/dashboard">Retour au tableau de bord</a>';
    })->name('teacher.timetable');
    
    // Routes pour parents
    Route::get('parent/children', function () {
        return '<h1>Mes Enfants - En cours de développement</h1><a href="/dashboard">Retour au tableau de bord</a>';
    })->name('parent.children');
    
    // Paramètres
    Route::get('settings', function () {
        return view('settings.index');
    })->name('settings.index');
});

// Route temporaire pour corriger la base de données
Route::get('/fix-database', function () {
    try {
        $html = '<h1>🔧 Correction de la base de données</h1>';
        
        // Vérifier chaque colonne individuellement
        $columnsToAdd = [
            'classe' => 'VARCHAR(50) NULL',
            'date_naissance' => 'DATE NULL',
            'parent_tuteur' => 'VARCHAR(255) NULL',
            'adresse' => 'TEXT NULL',
            'genre' => 'ENUM("masculin", "feminin") NULL'
        ];
        
        $html .= '<h3>🔍 Vérification des colonnes :</h3>';
        
        foreach ($columnsToAdd as $columnName => $columnType) {
            $exists = DB::select("SHOW COLUMNS FROM users LIKE '$columnName'");
            
            if (empty($exists)) {
                $html .= '<p>❌ Colonne "' . $columnName . '" manquante. Ajout en cours...</p>';
                try {
                    DB::statement("ALTER TABLE users ADD COLUMN $columnName $columnType");
                    $html .= '<p>✅ Colonne "' . $columnName . '" ajoutée avec succès</p>';
                } catch (Exception $e) {
                    $html .= '<p>⚠️ Erreur pour "' . $columnName . '" : ' . $e->getMessage() . '</p>';
                }
            } else {
                $html .= '<p>✅ Colonne "' . $columnName . '" existe déjà</p>';
            }
        }
        
        // Vérifier is_active séparément car elle existe déjà
        $isActiveExists = DB::select("SHOW COLUMNS FROM users LIKE 'is_active'");
        if (empty($isActiveExists)) {
            try {
                DB::statement("ALTER TABLE users ADD COLUMN is_active BOOLEAN DEFAULT TRUE");
                $html .= '<p>✅ Colonne "is_active" ajoutée</p>';
            } catch (Exception $e) {
                $html .= '<p>⚠️ Colonne "is_active" : ' . $e->getMessage() . '</p>';
            }
        } else {
            $html .= '<p>✅ Colonne "is_active" existe déjà</p>';
        }
        
        $html .= '<h2>✅ Vérification terminée !</h2>';
        
        // Afficher la structure finale
        $structure = DB::select("DESCRIBE users");
        $html .= '<h3>📋 Structure actuelle de la table users :</h3><ul>';
        foreach ($structure as $column) {
            $html .= '<li><strong>' . $column->Field . '</strong> (' . $column->Type . ')</li>';
        }
        $html .= '</ul>';
        
        $html .= '<p><a href="/students" style="background: #20B2AA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🎓 Tester la création d\'élève</a></p>';
        
        return $html;
        
    } catch (Exception $e) {
        return '<h1>❌ Erreur</h1><p>' . $e->getMessage() . '</p><p>Trace: ' . $e->getTraceAsString() . '</p>';
    }
});

// Route pour synchroniser les classes avec la table class_rooms
Route::get('/sync-classes', function () {
    try {
        $html = '<h1>🔄 Synchronisation des classes</h1>';
        
        // Récupérer les classes de la table class_rooms
        $classRooms = DB::table('class_rooms')->get();
        
        $html .= '<h3>📋 Classes trouvées dans class_rooms :</h3><ul>';
        foreach ($classRooms as $room) {
            $html .= '<li><strong>' . $room->name . '</strong> (Niveau: ' . $room->level . ', Section: ' . $room->section . ')</li>';
        }
        $html .= '</ul>';
        
        // Créer une liste des classes uniques
        $uniqueClasses = $classRooms->pluck('name')->unique()->sort()->values();
        
        $html .= '<h3>🎓 Classes uniques disponibles :</h3><ul>';
        foreach ($uniqueClasses as $classe) {
            $html .= '<li>' . $classe . '</li>';
        }
        $html .= '</ul>';
        
        $html .= '<p><a href="/students" style="background: #20B2AA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🎓 Aller à la gestion des élèves</a></p>';
        
        return $html;
        
    } catch (Exception $e) {
        return '<h1>❌ Erreur</h1><p>' . $e->getMessage() . '</p>';
    }
});

// Route de test pour debug suppression
Route::get('/test-delete/{id}', function ($id) {
    try {
        $student = \App\Models\Student::find($id);
        if ($student) {
            $name = $student->name;
            $result = $student->forceDelete();
            return "Élève {$name} supprimé: " . ($result ? 'OUI' : 'NON');
        }
        return "Élève non trouvé";
    } catch (\Exception $e) {
        return "Erreur: " . $e->getMessage();
    }
});

require __DIR__.'/auth.php';
