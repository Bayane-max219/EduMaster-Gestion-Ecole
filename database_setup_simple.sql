-- ========================================
-- EduMaster - School Management System
-- Base de données MySQL - Version Simplifiée
-- ========================================

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS edumaster_school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE edumaster_school;

-- Désactiver les vérifications de clés étrangères temporairement
SET FOREIGN_KEY_CHECKS = 0;

-- ========================================
-- 1. Table des utilisateurs
-- ========================================
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(191) NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    avatar VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_login_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Créer l'index unique pour email séparément
ALTER TABLE users ADD UNIQUE KEY users_email_unique (email);

-- ========================================
-- 2. Tables des rôles et permissions (Spatie)
-- ========================================
DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL,
    guard_name VARCHAR(191) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS permissions;
CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL,
    guard_name VARCHAR(191) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS model_has_permissions;
CREATE TABLE model_has_permissions (
    permission_id BIGINT UNSIGNED NOT NULL,
    model_type VARCHAR(191) NOT NULL,
    model_id BIGINT UNSIGNED NOT NULL
);

DROP TABLE IF EXISTS model_has_roles;
CREATE TABLE model_has_roles (
    role_id BIGINT UNSIGNED NOT NULL,
    model_type VARCHAR(191) NOT NULL,
    model_id BIGINT UNSIGNED NOT NULL
);

DROP TABLE IF EXISTS role_has_permissions;
CREATE TABLE role_has_permissions (
    permission_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL
);

-- ========================================
-- 3. Table des années scolaires
-- ========================================
DROP TABLE IF EXISTS school_years;
CREATE TABLE school_years (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_current BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive', 'archived') DEFAULT 'active',
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ========================================
-- 4. Table des matières
-- ========================================
DROP TABLE IF EXISTS subjects;
CREATE TABLE subjects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL,
    description TEXT NULL,
    coefficient DECIMAL(3,1) DEFAULT 1.0,
    color VARCHAR(7) DEFAULT '#20B2AA',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ========================================
-- 5. Table des classes
-- ========================================
DROP TABLE IF EXISTS class_rooms;
CREATE TABLE class_rooms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    level VARCHAR(100) NOT NULL,
    section VARCHAR(50) NULL,
    capacity INT DEFAULT 30,
    room_number VARCHAR(50) NULL,
    school_year_id BIGINT UNSIGNED NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ========================================
-- 6. Table des professeurs
-- ========================================
DROP TABLE IF EXISTS teachers;
CREATE TABLE teachers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    teacher_number VARCHAR(50) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    date_of_birth DATE NULL,
    gender ENUM('M', 'F') NOT NULL,
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    qualification VARCHAR(255) NULL,
    specialization VARCHAR(255) NULL,
    hire_date DATE NOT NULL,
    salary DECIMAL(10,2) NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    photo VARCHAR(255) NULL,
    emergency_contact VARCHAR(255) NULL,
    emergency_phone VARCHAR(20) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- ========================================
-- 7. Table des élèves
-- ========================================
DROP TABLE IF EXISTS students;
CREATE TABLE students (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    student_number VARCHAR(50) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    date_of_birth DATE NOT NULL,
    place_of_birth VARCHAR(255) NULL,
    gender ENUM('M', 'F') NOT NULL,
    address TEXT NULL,
    phone VARCHAR(20) NULL,
    emergency_contact VARCHAR(255) NOT NULL,
    emergency_phone VARCHAR(20) NOT NULL,
    photo VARCHAR(255) NULL,
    enrollment_date DATE NOT NULL,
    status ENUM('active', 'inactive', 'graduated', 'transferred') DEFAULT 'active',
    medical_info TEXT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- ========================================
-- INSERTION DES DONNÉES INITIALES
-- ========================================

-- Insérer les rôles
INSERT INTO roles (name, guard_name) VALUES
('admin', 'web'),
('director', 'web'),
('secretary', 'web'),
('teacher', 'web'),
('parent', 'web'),
('student', 'web');

-- Insérer les permissions principales
INSERT INTO permissions (name, guard_name) VALUES
('users.view', 'web'), ('users.create', 'web'), ('users.edit', 'web'), ('users.delete', 'web'),
('students.view', 'web'), ('students.create', 'web'), ('students.edit', 'web'), ('students.delete', 'web'),
('teachers.view', 'web'), ('teachers.create', 'web'), ('teachers.edit', 'web'), ('teachers.delete', 'web'),
('classes.view', 'web'), ('classes.create', 'web'), ('classes.edit', 'web'), ('classes.delete', 'web'),
('subjects.view', 'web'), ('subjects.create', 'web'), ('subjects.edit', 'web'), ('subjects.delete', 'web'),
('grades.view', 'web'), ('grades.create', 'web'), ('grades.edit', 'web'), ('grades.delete', 'web'),
('attendances.view', 'web'), ('attendances.create', 'web'), ('attendances.edit', 'web'),
('payments.view', 'web'), ('payments.create', 'web'), ('payments.edit', 'web'),
('reports.view', 'web'), ('system.settings', 'web');

-- Assigner toutes les permissions à l'admin (role_id = 1)
INSERT INTO role_has_permissions (role_id, permission_id)
SELECT 1, id FROM permissions;

-- Insérer les utilisateurs par défaut (mot de passe: password)
INSERT INTO users (name, email, password, phone, address, is_active) VALUES
('Administrateur EduMaster', 'admin@edumaster.mg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261 34 00 000 01', 'Antananarivo, Madagascar', TRUE),
('Directeur École', 'director@edumaster.mg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261 34 00 000 02', 'Antananarivo, Madagascar', TRUE),
('Secrétaire École', 'secretary@edumaster.mg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261 34 00 000 03', 'Antananarivo, Madagascar', TRUE),
('Professeur Rakoto', 'teacher@edumaster.mg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261 34 00 000 04', 'Antananarivo, Madagascar', TRUE),
('Parent Rabe', 'parent@edumaster.mg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261 34 00 000 05', 'Antananarivo, Madagascar', TRUE);

-- Assigner les rôles aux utilisateurs
INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES
(1, 'App\\Models\\User', 1), -- Admin
(2, 'App\\Models\\User', 2), -- Director
(3, 'App\\Models\\User', 3), -- Secretary
(4, 'App\\Models\\User', 4), -- Teacher
(5, 'App\\Models\\User', 5); -- Parent

-- Insérer les années scolaires
INSERT INTO school_years (name, start_date, end_date, is_current, status, description) VALUES
('2024-2025', '2024-09-01', '2025-07-31', TRUE, 'active', 'Année scolaire 2024-2025'),
('2023-2024', '2023-09-01', '2024-07-31', FALSE, 'archived', 'Année scolaire 2023-2024');

-- Insérer quelques matières de base
INSERT INTO subjects (name, code, description, coefficient, color) VALUES
('Mathématiques', 'MATH', 'Mathématiques générales', 4.0, '#FF6B6B'),
('Français', 'FR', 'Langue française', 4.0, '#4ECDC4'),
('Anglais', 'EN', 'Langue anglaise', 3.0, '#45B7D1'),
('Sciences Physiques', 'PHY', 'Physique et Chimie', 3.0, '#96CEB4'),
('Sciences Naturelles', 'SVT', 'Sciences de la Vie et de la Terre', 3.0, '#FFEAA7'),
('Histoire-Géographie', 'HG', 'Histoire et Géographie', 3.0, '#DDA0DD'),
('Éducation Physique', 'EPS', 'Éducation Physique et Sportive', 2.0, '#98D8C8');

-- Insérer quelques classes de base
INSERT INTO class_rooms (name, level, section, capacity, room_number, school_year_id) VALUES
('CP A', 'CP', 'A', 25, 'R101', 1),
('CE1 A', 'CE1', 'A', 30, 'R102', 1),
('CE2 A', 'CE2', 'A', 30, 'R103', 1),
('CM1 A', 'CM1', 'A', 32, 'R201', 1),
('CM2 A', 'CM2', 'A', 32, 'R202', 1),
('6ème A', '6ème', 'A', 35, 'R301', 1),
('5ème A', '5ème', 'A', 35, 'R302', 1),
('4ème A', '4ème', 'A', 35, 'R303', 1),
('3ème A', '3ème', 'A', 35, 'R304', 1),
('2nde A', '2nde', 'A', 40, 'R401', 1),
('1ère A', '1ère', 'A', 40, 'R402', 1),
('Terminale A', 'Terminale', 'A', 40, 'R403', 1);

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- ========================================
-- FIN DU SCRIPT SIMPLIFIÉ
-- ========================================

-- Vérification des tables créées
SHOW TABLES;

-- Message de confirmation
SELECT 'Base de données EduMaster créée avec succès!' as message;
SELECT 'Utilisateurs par défaut créés avec mot de passe: password' as info;
SELECT 'Vous pouvez maintenant vous connecter avec admin@edumaster.mg' as login_info;
