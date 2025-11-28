-- Ajout des colonnes pour les élèves dans la table users
USE edumaster_school;

ALTER TABLE users 
ADD COLUMN classe VARCHAR(50) NULL AFTER phone,
ADD COLUMN date_naissance DATE NULL AFTER classe,
ADD COLUMN parent_tuteur VARCHAR(255) NULL AFTER date_naissance,
ADD COLUMN adresse TEXT NULL AFTER parent_tuteur,
ADD COLUMN is_active BOOLEAN DEFAULT TRUE AFTER adresse;
