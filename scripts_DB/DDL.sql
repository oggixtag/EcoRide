-- --------------------------------------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------------------------

-- -----------------------------------------------------
-- Schéma : abcrdv_ecoride_db
-- Base de données pour le projet de covoiturage EcoRide
-- -----------------------------------------------------

-- -----------------------------------------------------
-- en local
-- -----------------------------------------------------
DROP DATABASE IF EXISTS `abcrdv_ecoride_db`;

-- Create the EcoRide database
CREATE DATABASE IF NOT EXISTS abcrdv_ecoride_db;
USE abcrdv_ecoride_db;

-- -----------------------------------------------------
-- alwaysdata
-- -----------------------------------------------------

USE abcrdv_ecoride_db;
-- 1. Désactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- 2. Liste des suppressions (l'ordre n'a plus d'importance ici)
DROP TABLE IF EXISTS `avis`;
DROP TABLE IF EXISTS `preference`;
DROP TABLE IF EXISTS `visiteur_utilisateur`;
DROP TABLE IF EXISTS `employe`;
DROP TABLE IF EXISTS `statut_mail`;
DROP TABLE IF EXISTS `note`;
DROP TABLE IF EXISTS `poste`;
DROP TABLE IF EXISTS `departement`;
--
DROP TABLE IF EXISTS `statut_avis`;
DROP TABLE IF EXISTS `participe`;
DROP TABLE IF EXISTS `covoiturage`;
DROP TABLE IF EXISTS `voiture`;
DROP TABLE IF EXISTS `statut_covoiturage`;
DROP TABLE IF EXISTS `avis_covoiturage`;
DROP TABLE IF EXISTS `marque`;
DROP TABLE IF EXISTS `utilisateur`;
DROP TABLE IF EXISTS `role`; 

-- 0. Réactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------------------------

-- -----------------------------------------------------
-- PARTIE UTILISATEUR
-- ---------------------------------------------------
USE abcrdv_ecoride_db;
-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `role` (
  `role_id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`role_id`)
) ;

-- -----------------------------------------------------
-- Table `statut_mail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `statut_mail` (
  `statut_mail_id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`statut_mail_id`)
) ;

-- -----------------------------------------------------
-- Table `utilisateur`
-- Relation 'possede' (1,1) vers role
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `utilisateur_id` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(50) NOT NULL,
  `prenom` VARCHAR(50) NOT NULL,
  `email` VARCHAR(50) NOT NULL UNIQUE, -- Assurer l'unicité de l'email
  `password` VARCHAR(50) NOT NULL,
  `telephone` VARCHAR(50) NULL,
  `adresse` VARCHAR(50) NULL,
  `date_naissance` VARCHAR(50) NULL,
  `photo` BLOB NULL,
  `pseudo` VARCHAR(50) NOT NULL UNIQUE,
  `role_id` INT NOT NULL, -- Clé étrangère vers `role`
  `credit` INT NOT NULL DEFAULT 20,
  `est_suspendu` INT NOT NULL DEFAULT 0, -- 0: Actif, 1: Suspendu
  PRIMARY KEY (`utilisateur_id`),
  INDEX `fk_utilisateur_role_idx` (`role_id` ASC),
  CONSTRAINT `fk_utilisateur_role`
    FOREIGN KEY (`role_id`)
    REFERENCES `role` (`role_id`)
);

-- -----------------------------------------------------
-- Table `visiteur_utilisateur`
-- Relation (1,1) vers utilisateur
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `visiteur_utilisateur` (
  `visiteur_utilisateur_id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(50) NOT NULL,
  `pseudo` VARCHAR(50) NOT NULL UNIQUE,
  `statut_mail_id` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`visiteur_utilisateur_id`),
  INDEX `fk_visiteur_utilisateur_email_idx` (`email` ASC),
  INDEX `fk_pseudo_idx` (`pseudo` ASC),
  INDEX `fk_statut_mail_idx` (`statut_mail_id` ASC),
  CONSTRAINT `fk_statut_mail_id`
    FOREIGN KEY (`statut_mail_id`)
    REFERENCES `statut_mail` (`statut_mail_id`)
);

-- -----------------------------------------------------
-- Table `marque`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `marque` (
  `marque_id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`marque_id`)
) ;

-- -----------------------------------------------------
-- Table `statut_covoiturage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `statut_covoiturage` (
  `statut_covoiturage_id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`statut_covoiturage_id`)
) ;

-- -----------------------------------------------------
-- Table `statut_avis`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `statut_avis` (
  `statut_avis_id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`statut_avis_id`)
) ;


-- -----------------------------------------------------
-- Table `note`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `note` (
  `note_id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`note_id`)
) ;

-- -----------------------------------------------------
-- Table `voiture`
-- Relation 'detient' (1,1) vers marque
-- Relation 'gere' (1,1) vers utilisateur
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voiture` (
  `voiture_id` INT NOT NULL AUTO_INCREMENT,
  `modele` VARCHAR(50) NOT NULL,
  `immatriculation` VARCHAR(50) NOT NULL UNIQUE,
  `energie` VARCHAR(50) NOT NULL,
  `couleur` VARCHAR(50) NULL,
  `date_premiere_immatriculation` VARCHAR(50) NULL, -- DATE serait plus approprié
  `marque_id` INT NOT NULL, -- Clé étrangère vers `marque`
  `utilisateur_id` INT NOT NULL,
  PRIMARY KEY (`voiture_id`),
  INDEX `fk_voiture_marque_detient_idx` (`marque_id` ASC),
  INDEX `fk_voiture_utilisateur_idx` (`utilisateur_id` ASC),
  CONSTRAINT `fk_voiture_marque_detient`
    FOREIGN KEY (`marque_id`)
    REFERENCES `marque` (`marque_id`),
  CONSTRAINT `fk_voiture_utilisateur` 
	FOREIGN KEY (`utilisateur_id`) 
    REFERENCES `utilisateur` (`utilisateur_id`)
);


-- -----------------------------------------------------
-- Table `avis_covoiturage`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `avis_covoiturage` (
  `avis_covoiturage_id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`avis_covoiturage_id`)
) ;


-- -----------------------------------------------------
-- Table `covoiturage`
-- Relation dispose (1,1) vers voiture
-- Relation 'fk_statut_covoiturage_id' (1,1) vers statut_covoiturage
-- Relation 'fk_avis_covoiturage_id' (1,1) vers avis_covoiturage
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage` (
  `covoiturage_id` INT NOT NULL AUTO_INCREMENT,
  `date_depart` DATE NOT NULL,
  `heure_depart` TIME NOT NULL, -- Stocke HH:MM:SS, mais on insère et affiche HH:MM
  `lieu_depart` VARCHAR(50) NOT NULL,
  `date_arrivee` DATE NULL,
  `heure_arrivee` TIME NULL,	-- Stocke HH:MM:SS, mais on insère et affiche HH:MM
  `lieu_arrivee` VARCHAR(50) NOT NULL,
  `statut_covoiturage_id` INT NOT NULL, -- Clé étrangère 
  `avis_covoiturage_id` INT NULL, -- Clé étrangère 
  `nb_place` INT NOT NULL,
  `prix_personne` FLOAT NOT NULL,
  `voiture_id` INT NOT NULL, -- Clé étrangère vers la voiture UTILISÉE pour le covoiturage
  PRIMARY KEY (`covoiturage_id`),
  INDEX `fk_covoiturage_voiture_utilise_idx` (`voiture_id` ASC),
  INDEX `fk_statut_covoiturage_idx` (`statut_covoiturage_id` ASC),
  INDEX `fk_avis_covoiturage_idx` (`avis_covoiturage_id` ASC),
  CONSTRAINT `fk_covoiturage_voiture_utilise`
    FOREIGN KEY (`voiture_id`)
    REFERENCES `voiture` (`voiture_id`),
  CONSTRAINT `fk_statut_covoiturage_id`
    FOREIGN KEY (`statut_covoiturage_id`)
    REFERENCES `statut_covoiturage` (`statut_covoiturage_id`),
  CONSTRAINT `fk_avis_covoiturage_id`
    FOREIGN KEY (`avis_covoiturage_id`)
    REFERENCES `avis_covoiturage` (`avis_covoiturage_id`)
) ;

-- -----------------------------------------------------
-- Table `participe` (Table d'association n,n)
-- Entre `utilisateur` (Passager) et `covoiturage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `participe` (
  `utilisateur_id` INT NOT NULL,
  `covoiturage_id` INT NOT NULL,
  PRIMARY KEY (`utilisateur_id`, `covoiturage_id`),
  INDEX `fk_participe_utilisateur_idx` (`utilisateur_id` ASC),
  INDEX `fk_participe_covoiturage_idx` (`covoiturage_id` ASC),
  CONSTRAINT `fk_participe_utilisateur`
    FOREIGN KEY (`utilisateur_id`)
    REFERENCES `utilisateur` (`utilisateur_id`),
  CONSTRAINT `fk_participe_covoiturage`
    FOREIGN KEY (`covoiturage_id`)
    REFERENCES `covoiturage` (`covoiturage_id`)
) ;

-- -----------------------------------------------------
-- Table `avis`
-- Relation 'depose' (0,n) par utilisateur
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `avis` (
  `avis_id` INT NOT NULL AUTO_INCREMENT,
  `commentaire` VARCHAR(500) NULL,
  `note_id` INT NOT NULL, -- Clé étrangère
  `statut_avis_id` INT NOT NULL, -- Clé étrangère
  `utilisateur_id` INT NOT NULL, -- Clé étrangère vers l'utilisateur qui DÉPOSE l'avis
  PRIMARY KEY (`avis_id`),
  INDEX `fk_avis_utilisateur_depose_idx` (`utilisateur_id` ASC),
  INDEX `fk_statut_avis_idx` (`statut_avis_id` ASC),
  INDEX `fk_note_idx` (`note_id` ASC),
  CONSTRAINT `fk_avis_utilisateur_depose`
    FOREIGN KEY (`utilisateur_id`)
    REFERENCES `utilisateur` (`utilisateur_id`),
  CONSTRAINT `fk_statut_avis_id`
    FOREIGN KEY (`statut_avis_id`)
    REFERENCES `statut_avis` (`statut_avis_id`),
  CONSTRAINT `fk_note_id`
    FOREIGN KEY (`note_id`)
    REFERENCES `note` (`note_id`)
) ;


-- -----------------------------------------------------
-- Table `preference`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `preference` (
  `preference_id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(100) NOT NULL,
  `utilisateur_id` INT NOT NULL,
  PRIMARY KEY (`preference_id`),
  INDEX `fk_preference_utilisateur_idx` (`utilisateur_id` ASC),
  CONSTRAINT `fk_preference_utilisateur`
    FOREIGN KEY (`utilisateur_id`)
    REFERENCES `utilisateur` (`utilisateur_id`)
    ON DELETE CASCADE
) ENGINE=InnoDB;



-- -----------------------------------------------------
-- PARTIE ADMINISTRATEUR
-- -----------------------------------------------------


-- -----------------------------------------------------
-- Table `poste`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `poste` (
    id_poste     INT     AUTO_INCREMENT,
    intitule     VARCHAR(100) NOT NULL,
    salaire_min  DECIMAL(10,2),
    salaire_max  DECIMAL(10,2),
	PRIMARY KEY (`id_poste`)
);

-- -----------------------------------------------------
-- Table `departement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `departement` (
    id_dept      INT AUTO_INCREMENT,
    nom_dept     VARCHAR(100) NOT NULL,
    lieu         VARCHAR(100),
	PRIMARY KEY (`id_dept`)
);

-- -----------------------------------------------------
-- Table `employe`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `employe` (
    id_emp       	INT     AUTO_INCREMENT,
    nom          	VARCHAR(50)  NOT NULL,
    prenom       	VARCHAR(50)  NOT NULL,
    email        	VARCHAR(100) UNIQUE,
    password     	VARCHAR(50)  NOT NULL,
    date_embauche 	DATE        NOT NULL,
	date_fin 		DATE        ,
    salaire      	INT(10) NOT NULL,
    id_poste     	INT NOT NULL,
    id_dept      	INT NOT NULL,
    id_manager   	INT NULL,	-- Doit être NULL pour ON DELETE SET NULL
    est_suspendu 	INT NOT NULL DEFAULT 0, -- 0: Actif, 1: Suspendu
	pseudo 			VARCHAR(50) NOT NULL UNIQUE,
	PRIMARY KEY (`id_emp`),
	INDEX `fk_poste_idx` (`id_poste` ASC),
	INDEX `fk_dept_idx` (`id_dept` ASC),
	INDEX `fk_manqger_idx` (`id_manager` ASC),
    CONSTRAINT fk_emp_poste
		FOREIGN KEY (`id_poste`)
		REFERENCES `poste` (`id_poste`),
    CONSTRAINT fk_emp_dept
		FOREIGN KEY (`id_dept`) 
		REFERENCES `departement` (`id_dept`),
    CONSTRAINT fk_emp_mgr
		FOREIGN KEY (`id_manager`)
		REFERENCES `employe` (`id_emp`)
		ON DELETE SET NULL	-- Solution pour l'auto-relation
);
