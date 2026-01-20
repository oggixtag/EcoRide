-- -----------------------------------------------------
-- Schéma : ecoride_db
-- Base de données pour le projet de covoiturage EcoRide
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Script de suppression des tables (DROP)
-- Ordre basé sur les contraintes de clés étrangères (enfants avant parents)
-- -----------------------------------------------------

-- Désactiver la vérification des clés étrangères pour permettre la suppression
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Tables d'association (dépendent de deux autres tables)
DROP TABLE IF EXISTS `participe`;

-- 2. Tables dépendant de `utilisateur`, `voiture`, `marque`
DROP TABLE IF EXISTS `covoiturage`;   
DROP TABLE IF EXISTS `avis`;          
DROP TABLE IF EXISTS `visiteur_utilisateur`;

-- 3. Tables dépendant uniquement d'elles-mêmes
DROP TABLE IF EXISTS `voiture`;      
DROP TABLE IF EXISTS `utilisateur`;  

-- 4. Tables qui ne dépendent d'aucune autre table
DROP TABLE IF EXISTS `role`;
DROP TABLE IF EXISTS `marque`;
DROP TABLE IF EXISTS `statut_covoiturage`;
DROP TABLE IF EXISTS `statut_avis`;
DROP TABLE IF EXISTS `note`;

-- 5. Partie admin
DROP TABLE IF EXISTS `employe` ;
DROP TABLE IF EXISTS `departement`  ;
DROP TABLE IF EXISTS `poste`   ;

-- Rétablir la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;


-- Create the EcoRide database
CREATE DATABASE IF NOT EXISTS ecoride_db;
USE ecoride_db;

-- -----------------------------------------------------
-- PARTIE UTILISATEUR
-- ---------------------------------------------------

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
  `date_naissance` VARCHAR(50) NULL, -- Le type DATE serait plus approprié, mais je respecte VARCHAR(50) du schéma
  `photo` BLOB NULL,
  `pseudo` VARCHAR(50) NOT NULL UNIQUE,
  `role_id` INT NOT NULL, -- Clé étrangère vers `role`
  `credit` INT NOT NULL DEFAULT 20,
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
-- Table `covoiturage`
-- Relation 'utilise' (1,1) vers voiture
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage` (
  `covoiturage_id` INT NOT NULL AUTO_INCREMENT,
  `date_depart` DATE NOT NULL,
  `heure_depart` TIME NOT NULL, -- Utilisation du type TIME pour l'heure
  `lieu_depart` VARCHAR(50) NOT NULL,
  `date_arrivee` DATE NULL,
  `heure_arrivee` TIME NULL,
  `lieu_arrivee` VARCHAR(50) NOT NULL,
  `statut_covoiturage_id` INT NOT NULL, -- Clé étrangère 
  `nb_place` INT NOT NULL,
  `prix_personne` FLOAT NOT NULL,
  `voiture_id` INT NOT NULL, -- Clé étrangère vers la voiture UTILISÉE pour le covoiturage
  PRIMARY KEY (`covoiturage_id`),
  INDEX `fk_covoiturage_voiture_utilise_idx` (`voiture_id` ASC),
  INDEX `fk_statut_covoiturage_idx` (`statut_covoiturage_id` ASC),
  CONSTRAINT `fk_covoiturage_voiture_utilise`
    FOREIGN KEY (`voiture_id`)
    REFERENCES `voiture` (`voiture_id`),
  CONSTRAINT `fk_statut_covoiturage_id`
    FOREIGN KEY (`statut_covoiturage_id`)
    REFERENCES `statut_covoiturage` (`statut_covoiturage_id`)
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
-- PARTIE ADMINISTRATEUR
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `departement`
-- -----------------------------------------------------
CREATE TABLE `departement` (
    id_dept      INT AUTO_INCREMENT,
    nom_dept     VARCHAR(100) NOT NULL,
    lieu         VARCHAR(100),
	PRIMARY KEY (`id_dept`)
);

-- -----------------------------------------------------
-- Table `poste`
-- -----------------------------------------------------
CREATE TABLE `poste` (
    id_poste     INT     AUTO_INCREMENT,
    intitule     VARCHAR(100) NOT NULL,
    salaire_min  DECIMAL(10,2),
    salaire_max  DECIMAL(10,2),
	PRIMARY KEY (`id_poste`)
);

-- -----------------------------------------------------
-- Table `employe`
-- -----------------------------------------------------
CREATE TABLE `employe` (
    id_emp       INT     AUTO_INCREMENT,
    nom          VARCHAR(50)  NOT NULL,
    prenom       VARCHAR(50)  NOT NULL,
    email        VARCHAR(100) UNIQUE,
    date_embauche DATE        NOT NULL,
	date_fin 	DATE        ,
    salaire      DECIMAL(10,2) NOT NULL,
    id_poste     INT NOT NULL,
    id_dept      INT NOT NULL,
    id_manager   INT NULL,
	PRIMARY KEY (`id_emp`),
	INDEX `fk_poste_idx` (`id_poste` ASC),
	INDEX `fk_dept_idx` (`id_dept` ASC),
	INDEX `fk_manqger_idx` (`id_manager` ASC),
    CONSTRAINT fk_emp_poste
		FOREIGN KEY (`id_poste`)
		REFERENCES POSTE(`id_poste`),
    CONSTRAINT fk_emp_dept
		FOREIGN KEY (`id_dept`) 
		REFERENCES DEPARTEMENT(`id_dept`),
    CONSTRAINT fk_emp_mgr
		FOREIGN KEY (`id_manager`)
		REFERENCES EMPLOYE(`id_emp`)
		ON DELETE SET NULL -- Si le manager est supprimé, id_manager devient NULL
);
