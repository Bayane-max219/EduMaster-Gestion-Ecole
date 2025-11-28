-- ========================================
-- EduMaster - School Management System
-- Base de données MySQL - Requêtes SQL
-- ========================================

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS edumaster_school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE edumaster_school;

-- ========================================
-- 1. Table des utilisateurs
-- ========================================
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY users_email_unique (email)
);

-- ========================================
-- 2. Tables des rôles et permissions (Spatie)
-- ========================================
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL,
    guard_name VARCHAR(191) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY roles_name_guard_name_unique (name, guard_name)
);

CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL,
    guard_name VARCHAR(191) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY permissions_name_guard_name_unique (name, guard_name)
);

CREATE TABLE model_has_permissions (
    permission_id BIGINT UNSIGNED NOT NULL,
    model_type VARCHAR(191) NOT NULL,
    model_id BIGINT UNSIGNED NOT NULL,
    INDEX model_has_permissions_model_id_model_type_index (model_id, model_type),
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    PRIMARY KEY (permission_id, model_id, model_type)
);

CREATE TABLE model_has_roles (
    role_id BIGINT UNSIGNED NOT NULL,
    model_type VARCHAR(191) NOT NULL,
    model_id BIGINT UNSIGNED NOT NULL,
    INDEX model_has_roles_model_id_model_type_index (model_id, model_type),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (role_id, model_id, model_type)
);

CREATE TABLE role_has_permissions (
    permission_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (permission_id, role_id)
);

-- ========================================
-- 3. Table des années scolaires
-- ========================================
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
CREATE TABLE subjects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (school_year_id) REFERENCES school_years(id) ON DELETE CASCADE
);

-- ========================================
-- 6. Table des professeurs
-- ========================================
CREATE TABLE teachers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    teacher_number VARCHAR(50) NOT NULL UNIQUE,
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
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ========================================
-- 7. Table des élèves
-- ========================================
CREATE TABLE students (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    student_number VARCHAR(50) NOT NULL UNIQUE,
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
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ========================================
-- 8. Table des parents
-- ========================================
CREATE TABLE parent_models (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NULL,
    address TEXT NULL,
    profession VARCHAR(255) NULL,
    relationship ENUM('father', 'mother', 'guardian', 'other') NOT NULL,
    is_primary_contact BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ========================================
-- 9. Table de liaison élèves-parents
-- ========================================
CREATE TABLE student_parent (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    parent_id BIGINT UNSIGNED NOT NULL,
    relationship ENUM('father', 'mother', 'guardian', 'other') NOT NULL,
    is_primary_contact BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES parent_models(id) ON DELETE CASCADE,
    UNIQUE KEY student_parent_unique (student_id, parent_id)
);

-- ========================================
-- 10. Table des inscriptions
-- ========================================
CREATE TABLE enrollments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    class_id BIGINT UNSIGNED NOT NULL,
    school_year_id BIGINT UNSIGNED NOT NULL,
    enrollment_date DATE NOT NULL,
    status ENUM('active', 'inactive', 'completed', 'transferred') DEFAULT 'active',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES class_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (school_year_id) REFERENCES school_years(id) ON DELETE CASCADE,
    UNIQUE KEY enrollment_unique (student_id, school_year_id)
);

-- ========================================
-- 11. Table de liaison professeurs-matières
-- ========================================
CREATE TABLE teacher_subjects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    teacher_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    UNIQUE KEY teacher_subject_unique (teacher_id, subject_id)
);

-- ========================================
-- 12. Table de liaison professeurs-classes
-- ========================================
CREATE TABLE teacher_classes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    teacher_id BIGINT UNSIGNED NOT NULL,
    class_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    is_class_teacher BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES class_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    UNIQUE KEY teacher_class_subject_unique (teacher_id, class_id, subject_id)
);

-- ========================================
-- 13. Table des emplois du temps
-- ========================================
CREATE TABLE timetable_entries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    class_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    teacher_id BIGINT UNSIGNED NOT NULL,
    day_of_week TINYINT NOT NULL, -- 0=Dimanche, 1=Lundi, etc.
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    room VARCHAR(50) NULL,
    school_year_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES class_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (school_year_id) REFERENCES school_years(id) ON DELETE CASCADE
);

-- ========================================
-- 14. Table des présences
-- ========================================
CREATE TABLE attendances (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    class_id BIGINT UNSIGNED NOT NULL,
    teacher_id BIGINT UNSIGNED NULL,
    date DATE NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused') NOT NULL,
    arrival_time TIME NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES class_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL,
    UNIQUE KEY attendance_unique (student_id, date)
);

-- ========================================
-- 15. Table des notes
-- ========================================
CREATE TABLE grades (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    teacher_id BIGINT UNSIGNED NOT NULL,
    class_id BIGINT UNSIGNED NOT NULL,
    exam_type ENUM('devoir', 'controle', 'examen', 'oral', 'pratique') NOT NULL,
    exam_name VARCHAR(255) NOT NULL,
    score DECIMAL(5,2) NULL,
    max_score DECIMAL(5,2) DEFAULT 20.00,
    exam_date DATE NOT NULL,
    semester ENUM('1', '2') NOT NULL,
    school_year_id BIGINT UNSIGNED NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES class_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (school_year_id) REFERENCES school_years(id) ON DELETE CASCADE
);

-- ========================================
-- 16. Table des frais scolaires
-- ========================================
CREATE TABLE fees (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    amount DECIMAL(10,2) NOT NULL,
    type ENUM('inscription', 'scolarite', 'cantine', 'transport', 'activite', 'autre') NOT NULL,
    frequency ENUM('unique', 'mensuel', 'trimestriel', 'semestriel', 'annuel') DEFAULT 'unique',
    class_level VARCHAR(100) NULL, -- Si spécifique à un niveau
    is_mandatory BOOLEAN DEFAULT TRUE,
    due_date DATE NULL,
    school_year_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (school_year_id) REFERENCES school_years(id) ON DELETE CASCADE
);

-- ========================================
-- 17. Table des paiements
-- ========================================
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    fee_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method ENUM('especes', 'cheque', 'virement', 'mobile_money', 'carte') DEFAULT 'especes',
    reference VARCHAR(255) NULL,
    status ENUM('pending', 'paid', 'partial', 'overdue', 'cancelled') DEFAULT 'pending',
    receipt_number VARCHAR(100) NULL,
    notes TEXT NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (fee_id) REFERENCES fees(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- ========================================
-- 18. Table des bulletins
-- ========================================
CREATE TABLE bulletins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    class_id BIGINT UNSIGNED NOT NULL,
    school_year_id BIGINT UNSIGNED NOT NULL,
    semester ENUM('1', '2') NOT NULL,
    average DECIMAL(5,2) NULL,
    rank INT NULL,
    total_students INT NULL,
    conduct_grade ENUM('A', 'B', 'C', 'D') DEFAULT 'B',
    teacher_comment TEXT NULL,
    director_comment TEXT NULL,
    generated_at TIMESTAMP NULL,
    generated_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES class_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (school_year_id) REFERENCES school_years(id) ON DELETE CASCADE,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY bulletin_unique (student_id, semester, school_year_id)
);

-- ========================================
-- 19. Table des sanctions disciplinaires
-- ========================================
CREATE TABLE disciplinary_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    incident_date DATE NOT NULL,
    incident_type ENUM('retard', 'absence', 'comportement', 'travail', 'autre') NOT NULL,
    description TEXT NOT NULL,
    action_taken ENUM('avertissement', 'blame', 'exclusion_temporaire', 'exclusion_definitive', 'autre') NOT NULL,
    action_details TEXT NULL,
    reported_by BIGINT UNSIGNED NOT NULL,
    handled_by BIGINT UNSIGNED NULL,
    parent_notified BOOLEAN DEFAULT FALSE,
    parent_notified_at TIMESTAMP NULL,
    status ENUM('open', 'resolved', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (handled_by) REFERENCES users(id) ON DELETE SET NULL
);

-- ========================================
-- 20. Table des notifications
-- ========================================
CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY,
    type VARCHAR(191) NOT NULL,
    notifiable_type VARCHAR(191) NOT NULL,
    notifiable_id BIGINT UNSIGNED NOT NULL,
    data JSON NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX notifications_notifiable_type_notifiable_id_index (notifiable_type, notifiable_id)
);

-- ========================================
-- 21. Table des sessions Laravel
-- ========================================
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);

-- ========================================
-- 22. Table des jobs en queue
-- ========================================
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX jobs_queue_index (queue)
);

CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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

-- Insérer les permissions
INSERT INTO permissions (name, guard_name) VALUES
-- Users Management
('users.view', 'web'), ('users.create', 'web'), ('users.edit', 'web'), ('users.delete', 'web'),
-- Students Management
('students.view', 'web'), ('students.create', 'web'), ('students.edit', 'web'), ('students.delete', 'web'),
('students.enroll', 'web'), ('students.export', 'web'),
-- Teachers Management
('teachers.view', 'web'), ('teachers.create', 'web'), ('teachers.edit', 'web'), ('teachers.delete', 'web'),
('teachers.assign', 'web'),
-- Classes Management
('classes.view', 'web'), ('classes.create', 'web'), ('classes.edit', 'web'), ('classes.delete', 'web'),
-- Subjects Management
('subjects.view', 'web'), ('subjects.create', 'web'), ('subjects.edit', 'web'), ('subjects.delete', 'web'),
-- Grades Management
('grades.view', 'web'), ('grades.create', 'web'), ('grades.edit', 'web'), ('grades.delete', 'web'),
('grades.bulk', 'web'), ('bulletins.generate', 'web'),
-- Attendance Management
('attendances.view', 'web'), ('attendances.create', 'web'), ('attendances.edit', 'web'), ('attendances.delete', 'web'),
('attendances.bulk', 'web'),
-- Payments Management
('payments.view', 'web'), ('payments.create', 'web'), ('payments.edit', 'web'), ('payments.delete', 'web'),
('payments.receipts', 'web'), ('payments.reports', 'web'),
-- Timetables Management
('timetables.view', 'web'), ('timetables.create', 'web'), ('timetables.edit', 'web'), ('timetables.delete', 'web'),
('timetables.generate', 'web'),
-- Reports
('reports.view', 'web'), ('reports.financial', 'web'), ('reports.academic', 'web'), ('reports.attendance', 'web'),
-- System
('system.settings', 'web'), ('system.logs', 'web'), ('system.backup', 'web');

-- Assigner toutes les permissions à l'admin
INSERT INTO role_has_permissions (role_id, permission_id)
SELECT 1, id FROM permissions;

-- Insérer les utilisateurs par défaut
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

-- ========================================
-- FIN DU SCRIPT
-- ========================================

-- Vérification des tables créées
SHOW TABLES;

-- Message de confirmation
SELECT 'Base de données EduMaster créée avec succès!' as message;
SELECT 'Utilisateurs par défaut créés avec mot de passe: password' as info;
SELECT 'Vous pouvez maintenant vous connecter avec admin@edumaster.mg' as login_info;
