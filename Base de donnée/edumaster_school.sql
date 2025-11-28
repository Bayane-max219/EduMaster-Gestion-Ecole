-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 28 nov. 2025 à 12:13
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `edumaster_school`
--

-- --------------------------------------------------------

--
-- Structure de la table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
CREATE TABLE IF NOT EXISTS `attendances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','late','excused') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_student_id_index` (`student_id`),
  KEY `attendances_date_index` (`date`),
  KEY `attendances_status_index` (`status`),
  KEY `attendances_created_by_foreign` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `class_rooms`
--

DROP TABLE IF EXISTS `class_rooms`;
CREATE TABLE IF NOT EXISTS `class_rooms` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `level` varchar(100) NOT NULL,
  `section` varchar(50) DEFAULT NULL,
  `capacity` int DEFAULT '30',
  `room_number` varchar(50) DEFAULT NULL,
  `school_year_id` bigint UNSIGNED NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `class_rooms`
--

INSERT INTO `class_rooms` (`id`, `name`, `level`, `section`, `capacity`, `room_number`, `school_year_id`, `status`, `created_at`, `updated_at`) VALUES
(32, '9 èm A', '9ème', 'A', 35, 'A304', 1, 'active', '2025-11-28 07:20:53', '2025-11-28 07:20:53'),
(33, '8 èm A', '8ème', 'A', 35, 'A305', 1, 'active', '2025-11-28 07:20:53', '2025-11-28 07:20:53'),
(34, '7 èm A', '7ème', 'A', 35, 'A306', 1, 'active', '2025-11-28 07:20:53', '2025-11-28 07:20:53'),
(22, '6 èm A', '6ème', 'A', 30, 'A101', 1, 'active', '2025-11-23 12:41:39', '2025-11-23 12:42:00'),
(23, '5 èm A', '5ème', 'A', 25, 'A102', 1, 'active', '2025-11-23 12:47:24', '2025-11-23 12:47:24'),
(24, '4 èm A', '4ème', 'A', 20, 'A103', 1, 'active', '2025-11-23 12:48:00', '2025-11-23 12:48:00'),
(25, '3 èm A', '3ème', 'A', 15, 'A104', 1, 'active', '2025-11-23 12:48:39', '2025-11-23 12:48:39'),
(26, '2nde A', '2nde', 'A', 40, 'A201', 1, 'active', '2025-11-24 01:32:56', '2025-11-24 01:32:56'),
(27, '1ère A', '1ère', 'A', 30, 'A202', 1, 'active', '2025-11-24 01:36:27', '2025-11-24 01:36:27'),
(28, 'Terminale A', 'Terminale', 'A', 25, 'A203', 1, 'active', '2025-11-24 01:37:06', '2025-11-24 01:37:06'),
(29, '12 èm A', '12ème', 'A', 40, 'A301', 1, 'active', '2025-11-28 07:20:53', '2025-11-28 07:20:53'),
(30, '11 èm A', '11ème', 'A', 40, 'A302', 1, 'active', '2025-11-28 07:20:53', '2025-11-28 07:20:53'),
(31, '10 èm A', '10ème', 'A', 40, 'A303', 1, 'active', '2025-11-28 07:20:53', '2025-11-28 07:20:53');

-- --------------------------------------------------------

--
-- Structure de la table `fees`
--

DROP TABLE IF EXISTS `fees`;
CREATE TABLE IF NOT EXISTS `fees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('inscription','scolarite','cantine','transport','activite','autre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `frequency` enum('unique','mensuel','trimestriel','semestriel','annuel') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unique',
  `class_level` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '1',
  `due_date` date DEFAULT NULL,
  `school_year_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fees_school_year_id_index` (`school_year_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `fees`
--

INSERT INTO `fees` (`id`, `name`, `description`, `amount`, `type`, `frequency`, `class_level`, `is_mandatory`, `due_date`, `school_year_id`, `created_at`, `updated_at`) VALUES
(3, 'Frais d\'inscription', 'Droit d\'inscription annuel pour tous les nouveaux élèves.', '20000.00', 'inscription', 'unique', NULL, 1, '2024-09-30', 1, '2025-11-28 12:01:19', '2025-11-28 12:01:19'),
(4, 'Frais de dossier', 'Ouverture et traitement du dossier élève.', '5000.00', 'autre', 'unique', NULL, 1, '2024-09-15', 1, '2025-11-28 12:01:19', '2025-11-28 12:01:19'),
(5, 'Carte d\'élève', 'Impression et délivrance de la carte d\'élève.', '3000.00', 'autre', 'unique', NULL, 1, '2024-10-15', 1, '2025-11-28 12:01:19', '2025-11-28 12:01:19'),
(6, 'Scolarité - Primaire (CP à CM2)', 'Mensualité de scolarité pour les classes du primaire.', '50000.00', 'scolarite', 'mensuel', 'Primaire', 1, NULL, 1, '2025-11-28 12:01:19', '2025-11-28 12:01:19'),
(7, 'Scolarité - Collège (6ème à 3ème)', 'Mensualité de scolarité pour les classes du collège.', '60000.00', 'scolarite', 'mensuel', 'Collège', 1, NULL, 1, '2025-11-28 12:01:19', '2025-11-28 12:01:19'),
(8, 'Scolarité - Lycée (2nde à Terminale)', 'Mensualité de scolarité pour les classes du lycée.', '70000.00', 'scolarite', 'mensuel', 'Lycée', 1, NULL, 1, '2025-11-28 12:01:19', '2025-11-28 12:01:19'),
(9, 'Cantine - Demi-pension (midi)', 'Repas du midi à la cantine, tarif mensuel.', '35000.00', 'cantine', 'mensuel', NULL, 0, NULL, 1, '2025-11-28 12:01:19', '2025-11-28 12:01:19'),
(10, 'Transport scolaire - Zone proche', 'Ramassage scolaire mensuel pour les quartiers proches.', '50000.00', 'transport', 'mensuel', NULL, 0, NULL, 1, '2025-11-28 12:01:19', '2025-11-28 12:01:19'),
(11, 'Transport scolaire - Zone éloignée', 'Ramassage scolaire mensuel pour les quartiers éloignés.', '80000.00', 'transport', 'mensuel', NULL, 0, NULL, 1, '2025-11-28 12:01:19', '2025-11-28 12:01:19'),
(12, 'Activités parascolaires', 'Clubs, sport et activités culturelles pour l\'année.', '20000.00', 'activite', 'annuel', NULL, 0, '2025-06-30', 1, '2025-11-28 12:01:19', '2025-11-28 12:01:19');

-- --------------------------------------------------------

--
-- Structure de la table `grades`
--

DROP TABLE IF EXISTS `grades`;
CREATE TABLE IF NOT EXISTS `grades` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `class_id` bigint UNSIGNED NOT NULL,
  `exam_type` enum('devoir','controle','examen','oral','pratique') COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `max_score` decimal(5,2) NOT NULL DEFAULT '20.00',
  `exam_date` date NOT NULL,
  `semester` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_year_id` bigint UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grades_student_id_index` (`student_id`),
  KEY `grades_subject_id_index` (`subject_id`),
  KEY `grades_teacher_id_index` (`teacher_id`),
  KEY `grades_class_id_index` (`class_id`),
  KEY `grades_school_year_id_index` (`school_year_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `subject_id`, `teacher_id`, `class_id`, `exam_type`, `exam_name`, `score`, `max_score`, `exam_date`, `semester`, `school_year_id`, `notes`, `created_at`, `updated_at`) VALUES
(2, 18, 3, 3, 31, 'examen', 'Controle chap2', '17.00', '20.00', '2025-11-12', '1', 1, 'Assiduté', '2025-11-18 14:17:42', '2025-11-28 08:52:32'),
(3, 14, 3, 6, 30, 'examen', 'Controle chap1', '18.00', '20.00', '2025-11-28', '1', 1, 'Continue, c\'est très bien', '2025-11-28 08:52:14', '2025-11-28 08:52:14'),
(4, 6, 2, 5, 29, 'oral', 'Controle chap1', '15.00', '20.00', '2025-11-27', '1', 1, 'Bien', '2025-11-28 08:53:15', '2025-11-28 08:53:15'),
(5, 13, 6, 9, 27, 'examen', 'Controle chap1', '11.00', '20.00', '2025-11-26', '1', 1, 'Tu peux faire mieux', '2025-11-28 08:54:25', '2025-11-28 08:54:25'),
(6, 7, 5, 7, 26, 'examen', 'Controle chap1', '3.00', '20.00', '2025-11-26', '1', 1, 'Fait un effort', '2025-11-28 08:55:37', '2025-11-28 08:55:37'),
(7, 9, 4, 10, 24, 'pratique', 'chapitre 1', '20.00', '20.00', '2025-11-27', '1', 1, 'Bravo', '2025-11-28 09:46:06', '2025-11-28 09:46:06');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_18_171200_create_grades_table', 1),
(2, '2025_11_18_171300_create_fees_table', 2),
(3, '2025_11_18_171400_create_payments_table', 3),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 4),
(5, '2024_01_01_000000_create_users_table', 5),
(6, '2024_01_02_000000_create_school_years_table', 5),
(7, '2024_11_12_000000_add_student_fields_to_users_table', 5),
(8, '2025_11_20_201500_create_attendances_table', 5),
(9, '2025_11_28_130500_add_classe_to_payments_table', 5);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `classe` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fee_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` enum('especes','cheque','virement','mobile_money','carte') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'especes',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','paid','partial','overdue','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `receipt_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_student_id_index` (`student_id`),
  KEY `payments_fee_id_index` (`fee_id`),
  KEY `payments_payment_date_index` (`payment_date`),
  KEY `payments_created_by_foreign` (`created_by`),
  KEY `payments_classe_index` (`classe`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `payments`
--

INSERT INTO `payments` (`id`, `student_id`, `classe`, `fee_id`, `amount`, `payment_date`, `payment_method`, `reference`, `status`, `receipt_number`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 14, '1ère', 8, '70000.00', '2025-11-20', 'especes', 'Especes', 'paid', 'R-2025-002', 'Bien recue', 1, '2025-11-20 18:00:40', '2025-11-28 12:11:40'),
(3, 6, '7ème', 3, '20000.00', '2025-11-28', 'cheque', 'chèque', 'paid', 'R-2025-003', 'Payé', 1, '2025-11-28 09:24:49', '2025-11-28 12:11:11'),
(2, 18, '6ème', 7, '60000.00', '2025-11-28', 'especes', 'Especes', 'paid', 'R-2025-001', 'Merci', 1, '2025-11-28 08:57:46', '2025-11-28 12:11:25'),
(4, 13, '1ère', 11, '80000.00', '2025-11-28', 'cheque', 'chèque', 'paid', 'R-2025-004', NULL, 1, '2025-11-28 09:25:23', '2025-11-28 12:10:33'),
(5, 7, '4ème', 4, '5000.00', '2025-11-28', 'mobile_money', 'Mvola', 'paid', 'R-2025-005', NULL, 1, '2025-11-28 09:25:54', '2025-11-28 12:10:05'),
(7, 9, '3ème', 5, '3000.00', '2025-11-28', 'especes', 'espèces', 'paid', 'R-2025-001', NULL, 1, '2025-11-28 10:05:47', '2025-11-28 12:09:48');

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'users.view', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(2, 'users.create', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(3, 'users.edit', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(4, 'users.delete', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(5, 'students.view', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(6, 'students.create', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(7, 'students.edit', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(8, 'students.delete', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(9, 'teachers.view', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(10, 'teachers.create', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(11, 'teachers.edit', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(12, 'teachers.delete', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(13, 'classes.view', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(14, 'classes.create', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(15, 'classes.edit', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(16, 'classes.delete', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(17, 'subjects.view', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(18, 'subjects.create', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(19, 'subjects.edit', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(20, 'subjects.delete', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(21, 'grades.view', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(22, 'grades.create', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(23, 'grades.edit', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(24, 'grades.delete', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(25, 'attendances.view', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(26, 'attendances.create', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(27, 'attendances.edit', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(28, 'payments.view', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(29, 'payments.create', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(30, 'payments.edit', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(31, 'reports.view', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(32, 'system.settings', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(2, 'director', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(3, 'secretary', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(4, 'teacher', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(5, 'parent', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09'),
(6, 'student', 'web', '2025-11-12 10:17:09', '2025-11-12 10:17:09');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1);

-- --------------------------------------------------------

--
-- Structure de la table `school_years`
--

DROP TABLE IF EXISTS `school_years`;
CREATE TABLE IF NOT EXISTS `school_years` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_current` tinyint(1) DEFAULT '0',
  `status` enum('active','inactive','archived') DEFAULT 'active',
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `school_years`
--

INSERT INTO `school_years` (`id`, `name`, `start_date`, `end_date`, `is_current`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, '2024-2025', '2024-09-01', '2025-07-31', 1, 'active', 'Année scolaire 2024-2025', '2025-11-12 10:17:10', '2025-11-12 10:17:10'),
(2, '2023-2024', '2023-09-01', '2024-07-31', 0, 'archived', 'Année scolaire 2023-2024', '2025-11-12 10:17:10', '2025-11-12 10:17:10');

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `student_number` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `emergency_phone` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'active',
  `medical_info` text,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `classe` varchar(50) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `parent_tuteur` varchar(255) DEFAULT NULL,
  `adresse` text,
  `genre` enum('masculin','feminin') DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_number`, `first_name`, `last_name`, `date_of_birth`, `place_of_birth`, `gender`, `address`, `phone`, `emergency_contact`, `emergency_phone`, `photo`, `enrollment_date`, `status`, `medical_info`, `notes`, `created_at`, `updated_at`, `name`, `email`, `classe`, `date_naissance`, `parent_tuteur`, `adresse`, `genre`, `is_active`) VALUES
(7, NULL, 'E001', 'Tiana', 'Rakoto', '2012-03-15', 'Antananarivo', 'male', 'Analakely, Antananarivo', '+261340000101', 'Rasoa Rakoto', '+261340000201', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Tiana Rakoto', 'tiana.rakoto@ecole.mg', '4ème', '2012-03-15', 'Rasoa Rakoto', 'Analakely, Antananarivo', 'masculin', 1),
(6, NULL, 'STU20250001', 'Franco', 'Goulzaraly', '2014-02-23', NULL, 'male', 'Alasora', '0348349886', NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2025-11-23 11:04:02', '2025-11-23 11:07:24', 'Franco Goulzaraly', 'franco@gmail.com', '7ème', '2014-02-23', 'Miguel Sincgol', 'Alasora', 'masculin', 1),
(8, NULL, 'E002', 'Fara', 'Randria', '2012-06-20', 'Antananarivo', 'female', 'Ankadifotsy, Antananarivo', '+261340000102', 'Rabe Randria', '+261340000202', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Fara Randria', 'fara.randria@ecole.mg', '4ème', '2012-06-20', 'Rabe Randria', 'Ankadifotsy, Antananarivo', 'feminin', 1),
(9, NULL, 'E003', 'Joël', 'Ramanana', '2011-11-05', 'Antananarivo', 'male', 'Ambatonakanga, Antananarivo', '+261340000103', 'Rasolo Ramanana', '+261340000203', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Joël Ramanana', 'joël.ramanana@ecole.mg', '3ème', '2011-11-05', 'Rasolo Ramanana', 'Ambatonakanga, Antananarivo', 'masculin', 1),
(10, NULL, 'E004', 'Miora', 'Rasoanaivo', '2011-09-12', 'Antananarivo', 'female', 'Andohalo, Antananarivo', '+261340000104', 'Ranaivo Mamy', '+261340000204', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Miora Rasoanaivo', 'miora.rasoanaivo@ecole.mg', '3ème', '2011-09-12', 'Ranaivo Mamy', 'Andohalo, Antananarivo', 'feminin', 1),
(11, NULL, 'E005', 'Hery', 'Rakotovao', '2010-01-30', 'Antananarivo', 'male', 'Ambanidia, Antananarivo', '+261340000105', 'Rasoa Rakotovao', '+261340000205', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Hery Rakotovao', 'hery.rakotovao@ecole.mg', '2nde', '2010-01-30', 'Rasoa Rakotovao', 'Ambanidia, Antananarivo', 'masculin', 1),
(12, NULL, 'E006', 'Lova', 'Ramaroson', '2010-07-09', 'Antananarivo', 'female', 'Anosibe, Antananarivo', '+261340000106', 'Ramaroson Jean', '+261340000206', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Lova Ramaroson', 'lova.ramaroson@ecole.mg', '2nde', '2010-07-09', 'Ramaroson Jean', 'Anosibe, Antananarivo', 'feminin', 1),
(13, NULL, 'E007', 'Nantenaina', 'Rabe', '2009-04-18', 'Antananarivo', 'male', '67Ha, Antananarivo', '+261340000107', 'Rabe Saholy', '+261340000207', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Nantenaina Rabe', 'nantenaina.rabe@ecole.mg', '1ère', '2009-04-18', 'Rabe Saholy', '67Ha, Antananarivo', 'masculin', 1),
(14, NULL, 'E008', 'Santatra', 'Andrianina', '2009-09-25', 'Antananarivo', 'female', 'Anosy, Antananarivo', '+261340000108', 'Andrianina Lalao', '+261340000208', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Santatra Andrianina', 'santatra.andrianina@ecole.mg', '1ère', '2009-09-25', 'Andrianina Lalao', 'Anosy, Antananarivo', 'feminin', 1),
(15, NULL, 'E009', 'Patrick', 'Ravelo', '2013-02-11', 'Antananarivo', 'male', 'Ivandry, Antananarivo', '+261340000109', 'Ravelo Fanja', '+261340000209', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Patrick Ravelo', 'patrick.ravelo@ecole.mg', '5ème', '2013-02-11', 'Ravelo Fanja', 'Ivandry, Antananarivo', 'masculin', 1),
(16, NULL, 'E010', 'Soa', 'Razanaka', '2013-08-03', 'Antananarivo', 'female', 'Ankadilalana, Antananarivo', '+261340000110', 'Razanaka Hery', '+261340000210', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Soa Razanaka', 'soa.razanaka@ecole.mg', '5ème', '2013-08-03', 'Razanaka Hery', 'Ankadilalana, Antananarivo', 'feminin', 1),
(17, NULL, 'E011', 'Jimmy', 'Ratsimba', '2014-01-22', 'Antananarivo', 'male', 'Ampefiloha, Antananarivo', '+261340000111', 'Ratsimba Noro', '+261340000211', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Jimmy Ratsimba', 'jimmy.ratsimba@ecole.mg', '6ème', '2014-01-22', 'Ratsimba Noro', 'Ampefiloha, Antananarivo', 'masculin', 1),
(18, NULL, 'E012', 'Malala', 'Andriani', '2014-05-14', 'Antananarivo', 'female', 'Isoraka, Antananarivo', '+261340000112', 'Andriani Fara', '+261340000212', NULL, '2024-09-01', 'active', NULL, NULL, '2025-11-28 07:22:06', '2025-11-28 10:45:55', 'Malala Andriani', 'malala.andriani@ecole.mg', '6ème', '2014-05-14', 'Andriani Fara', 'Isoraka, Antananarivo', 'feminin', 1);

-- --------------------------------------------------------

--
-- Structure de la table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text,
  `coefficient` decimal(3,1) DEFAULT '1.0',
  `color` varchar(7) DEFAULT '#20B2AA',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `code`, `description`, `coefficient`, `color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Mathématiques', 'MATH', 'Mathématiques générales', '4.0', '#FF6B6B', 1, '2025-11-12 10:17:10', '2025-11-12 10:17:10'),
(2, 'Français', 'FR', 'Langue française', '4.0', '#4ECDC4', 1, '2025-11-12 10:17:10', '2025-11-12 10:17:10'),
(3, 'Anglais', 'EN', 'Langue anglaise', '3.0', '#45B7D1', 1, '2025-11-12 10:17:10', '2025-11-12 10:17:10'),
(4, 'Sciences Physiques', 'PHY', 'Physique et Chimie', '3.0', '#96CEB4', 1, '2025-11-12 10:17:10', '2025-11-12 10:17:10'),
(5, 'Sciences Naturelles', 'SVT', 'Sciences de la Vie et de la Terre', '3.0', '#FFEAA7', 1, '2025-11-12 10:17:10', '2025-11-12 10:17:10'),
(6, 'Histoire-Géographie', 'HG', 'Histoire et Géographie', '3.0', '#DDA0DD', 1, '2025-11-12 10:17:10', '2025-11-12 10:17:10'),
(7, 'Éducation Physique', 'EPS', 'Éducation Physique et Sportive', '2.0', '#98D8C8', 1, '2025-11-12 10:17:10', '2025-11-12 10:17:10');

-- --------------------------------------------------------

--
-- Structure de la table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `teacher_number` varchar(50) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `qualification` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `photo` varchar(255) DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `emergency_phone` varchar(20) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teachers_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `teacher_number`, `first_name`, `last_name`, `email`, `date_of_birth`, `gender`, `phone`, `address`, `qualification`, `specialization`, `hire_date`, `salary`, `status`, `photo`, `emergency_contact`, `emergency_phone`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, NULL, 'PROF20250001', 'Timothe', 'Angelus', 'timothe@gmail.com', '1994-06-23', 'male', '0334569875', 'Itaosy', 'Licence', 'Mathématiques', '2025-11-24', '800000.00', 'active', NULL, NULL, NULL, NULL, '2025-11-23 12:11:05', '2025-11-23 12:11:05', NULL),
(5, NULL, 'PROF20250002', 'Jean', 'Nizar', 'Nizar@gmail.com', '1975-01-23', 'male', '0334569875', 'Anjomakely', 'Master', 'Français', '2025-11-24', '1000000.00', 'active', NULL, NULL, NULL, NULL, '2025-11-23 12:14:56', '2025-11-23 12:24:58', NULL),
(6, NULL, 'PROF20250003', 'Fatima', 'Goulzaraly', 'fatima@gmail.com', '1990-03-23', 'female', '0344883319', 'Andranomena', 'Doctorat', 'Sciences', '2025-11-24', '1300000.00', 'active', NULL, NULL, NULL, NULL, '2025-11-23 12:24:45', '2025-11-23 12:24:45', NULL),
(7, 15, 'TANG001', 'Tojo', 'Rasoanaivo', 'prof.anglais@ecole.mg', '1985-01-10', 'male', '+261340001001', 'Analakely, Antananarivo', 'Licence Anglais', 'Anglais', '2023-09-01', '900000.00', 'active', NULL, NULL, NULL, NULL, '2025-11-28 07:34:43', '2025-11-28 08:00:31', NULL),
(8, 16, 'THG001', 'Haja', 'Ravelomanana', 'prof.histoiregeo@ecole.mg', '1980-06-22', 'male', '+261340001002', 'Anosy, Antananarivo', 'Maîtrise Histoire-Géographie', 'Histoire-Géographie', '2022-09-01', '1100000.00', 'active', NULL, NULL, NULL, NULL, '2025-11-28 07:34:43', '2025-11-28 08:00:31', NULL),
(9, 17, 'TSVT001', 'Lalao', 'Rakotondrazaka', 'prof.svt@ecole.mg', '1987-03-05', 'female', '+261340001003', 'Ivandry, Antananarivo', 'Licence SVT', 'Sciences Naturelles', '2021-09-01', '950000.00', 'active', NULL, NULL, NULL, NULL, '2025-11-28 07:34:43', '2025-11-28 08:00:31', NULL),
(10, 18, 'TEPS001', 'Patrick', 'Ratsimba', 'prof.eps@ecole.mg', '1983-11-18', 'male', '+261340001004', '67Ha, Antananarivo', 'Licence STAPS', 'Éducation Physique', '2020-09-01', '750000.00', 'active', NULL, NULL, NULL, NULL, '2025-11-28 07:34:43', '2025-11-28 08:00:31', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `teacher_classes`
--

DROP TABLE IF EXISTS `teacher_classes`;
CREATE TABLE IF NOT EXISTS `teacher_classes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `class_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `is_class_teacher` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_class_subject_unique` (`teacher_id`,`class_id`,`subject_id`),
  KEY `class_id` (`class_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `classe` varchar(50) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `parent_tuteur` varchar(255) DEFAULT NULL,
  `adresse` text,
  `address` text,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `genre` enum('masculin','feminin') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `classe`, `date_naissance`, `parent_tuteur`, `adresse`, `address`, `avatar`, `is_active`, `last_login_at`, `remember_token`, `created_at`, `updated_at`, `genre`) VALUES
(1, 'Administrateur EduMaster', 'admin@edumaster.mg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261 34 00 000 01', NULL, NULL, NULL, NULL, 'Antananarivo, Madagascar', NULL, 1, NULL, NULL, '2025-11-12 10:17:10', '2025-11-12 10:17:10', NULL),
(2, 'Directeur École', 'director@edumaster.mg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261 34 00 000 02', NULL, NULL, NULL, NULL, 'Antananarivo, Madagascar', NULL, 1, NULL, NULL, '2025-11-12 10:17:10', '2025-11-12 10:17:10', NULL),
(3, 'Secrétaire École', 'secretary@edumaster.mg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261 34 00 000 03', NULL, NULL, NULL, NULL, 'Antananarivo, Madagascar', NULL, 1, NULL, NULL, '2025-11-12 10:17:10', '2025-11-12 10:17:10', NULL),
(4, 'Professeur Rakoto', 'teacher@edumaster.mg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261 34 00 000 04', NULL, NULL, NULL, NULL, 'Antananarivo, Madagascar', NULL, 1, NULL, NULL, '2025-11-12 10:17:10', '2025-11-12 10:17:10', NULL),
(5, 'Parent Rabe', 'parent@edumaster.mg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261 34 00 000 05', NULL, NULL, NULL, NULL, 'Antananarivo, Madagascar', NULL, 1, NULL, NULL, '2025-11-12 10:17:10', '2025-11-12 10:17:10', NULL),
(10, 'Fatima Goulzaraly', 'fatima@gmail.com', NULL, '$2y$12$m6mrG7dCeHr3GR4jfpgI.OP276QwPPbHrkAuCSA18vRRjO4z027MW', '0344883319', NULL, NULL, NULL, NULL, 'Alasora', NULL, 1, NULL, NULL, '2025-11-12 11:27:43', '2025-11-12 11:27:43', NULL),
(14, 'Afa Glory', 'alfa@gmail.com', NULL, '$2y$12$VeCS5zdN/Y6IstylTLEbGebSR1BqIM2M410rnW/8jN0kZPKMo5RIW', '0344883319', '7ème', '2025-11-05', 'Miguel', 'Alasora', NULL, NULL, 1, NULL, NULL, '2025-11-12 17:07:01', '2025-11-12 17:07:01', 'feminin'),
(15, 'Prof Anglais', 'prof.anglais@ecole.mg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261340001001', NULL, NULL, NULL, NULL, 'Antananarivo, Madagascar', NULL, 1, NULL, NULL, '2025-11-28 07:22:56', '2025-11-28 07:22:56', NULL),
(16, 'Prof Histoire-Géo', 'prof.histoiregeo@ecole.mg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261340001002', NULL, NULL, NULL, NULL, 'Antananarivo, Madagascar', NULL, 1, NULL, NULL, '2025-11-28 07:22:56', '2025-11-28 07:22:56', NULL),
(17, 'Prof SVT', 'prof.svt@ecole.mg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261340001003', NULL, NULL, NULL, NULL, 'Antananarivo, Madagascar', NULL, 1, NULL, NULL, '2025-11-28 07:22:56', '2025-11-28 07:22:56', NULL),
(18, 'Prof EPS', 'prof.eps@ecole.mg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+261340001004', NULL, NULL, NULL, NULL, 'Antananarivo, Madagascar', NULL, 1, NULL, NULL, '2025-11-28 07:22:56', '2025-11-28 07:22:56', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
