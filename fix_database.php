<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'edumaster_school',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    // Vérifier si les colonnes existent déjà
    $columns = Capsule::select("SHOW COLUMNS FROM users LIKE 'classe'");
    
    if (empty($columns)) {
        echo "Ajout des colonnes pour les élèves...\n";
        
        Capsule::statement("ALTER TABLE users 
            ADD COLUMN classe VARCHAR(50) NULL AFTER phone,
            ADD COLUMN date_naissance DATE NULL AFTER classe,
            ADD COLUMN parent_tuteur VARCHAR(255) NULL AFTER date_naissance,
            ADD COLUMN adresse TEXT NULL AFTER parent_tuteur,
            ADD COLUMN is_active BOOLEAN DEFAULT TRUE AFTER adresse");
        
        echo "✅ Colonnes ajoutées avec succès !\n";
    } else {
        echo "✅ Les colonnes existent déjà.\n";
    }
    
    // Vérifier la structure finale
    $structure = Capsule::select("DESCRIBE users");
    echo "\n📋 Structure de la table users :\n";
    foreach ($structure as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
