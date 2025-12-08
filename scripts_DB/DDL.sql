-- -----------------------------------------------------
-- Schéma : ecoride_db
-- Base de données pour le projet de covoiturage EcoRide
-- -----------------------------------------------------

-- Create the EcoRide database
CREATE DATABASE IF NOT EXISTS ecoride_db;
USE ecoride_db;

-- Désactiver temporairement la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `role` (
  `role_id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`role_id`)
) ;

-- -----------------------------------------------------
-- Table `utilisateur`
-- -------------------------------------
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
  PRIMARY KEY (`utilisateur_id`),
  INDEX `fk_utilisateur_role_idx` (`role_id` ASC),
  CONSTRAINT `fk_utilisateur_role`
    FOREIGN KEY (`role_id`)
    REFERENCES `role` (`role_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

-- -----------------------------------------------------
-- Table `avis`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `avis` (
  `avis_id` INT NOT NULL AUTO_INCREMENT,
  `commentaire` VARCHAR(50) NULL, -- VARCHAR(50) semble court pour un commentaire, TEXT pourrait être mieux.
  `note` VARCHAR(50) NOT NULL, -- Le type INT(1) ou FLOAT serait plus approprié pour une note.
  `statut` VARCHAR(50) NOT NULL,
  `utilisateur_id` INT NOT NULL, -- Clé étrangère vers l'utilisateur qui DÉPOSE l'avis
  PRIMARY KEY (`avis_id`),
  INDEX `fk_avis_utilisateur_depose_idx` (`utilisateur_id` ASC),
  CONSTRAINT `fk_avis_utilisateur_depose`
    FOREIGN KEY (`utilisateur_id`)
    REFERENCES `utilisateur` (`utilisateur_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ;

-- -----------------------------------------------------
-- Table `marque`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `marque` (
  `marque_id` INT NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`marque_id`)
) ;

-- -----------------------------------------------------
-- Table `voiture`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voiture` (
  `voiture_id` INT NOT NULL AUTO_INCREMENT,
  `modele` VARCHAR(50) NOT NULL,
  `immatriculation` VARCHAR(50) NOT NULL UNIQUE,
  `energie` VARCHAR(50) NOT NULL,
  `couleur` VARCHAR(50) NULL,
  `date_premiere_immatriculation` VARCHAR(50) NULL, -- DATE serait plus approprié
  `marque_id` INT NOT NULL, -- Clé étrangère vers `marque`
  PRIMARY KEY (`voiture_id`),
  INDEX `fk_voiture_marque_detient_idx` (`marque_id` ASC),
  CONSTRAINT `fk_voiture_marque_detient`
    FOREIGN KEY (`marque_id`)
    REFERENCES `marque` (`marque_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

-- -----------------------------------------------------
-- Table `covoiturage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage` (
  `covoiturage_id` INT NOT NULL AUTO_INCREMENT,
  `date_depart` DATE NOT NULL,
  `heure_depart` TIME NOT NULL, -- Utilisation du type TIME pour l'heure
  `lieu_depart` VARCHAR(50) NOT NULL,
  `date_arrivee` DATE NULL,
  `heure_arrivee` TIME NULL,
  `lieu_arrivee` VARCHAR(50) NOT NULL,
  `statut` VARCHAR(50) NOT NULL,
  `nb_place` INT NOT NULL,
  `prix_personne` FLOAT NOT NULL,
  `voiture_id` INT NOT NULL, -- Clé étrangère vers la voiture UTILISÉE pour le covoiturage
  `utilisateur_id` INT NOT NULL, -- Clé étrangère vers l'utilisateur qui GÈRE (est le conducteur)
  PRIMARY KEY (`covoiturage_id`),
  INDEX `fk_covoiturage_voiture_utilise_idx` (`voiture_id` ASC),
  INDEX `fk_covoiturage_utilisateur_gere_idx` (`utilisateur_id` ASC),
  CONSTRAINT `fk_covoiturage_voiture_utilise`
    FOREIGN KEY (`voiture_id`)
    REFERENCES `voiture` (`voiture_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_covoiturage_utilisateur_gere`
    FOREIGN KEY (`utilisateur_id`)
    REFERENCES `utilisateur` (`utilisateur_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ;

-- -----------------------------------------------------
-- Table d'association : `participe`
-- Relation n,n entre `utilisateur` et `covoiturage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `participe` (
  `utilisateur_id` INT NOT NULL,
  `covoiturage_id` INT NOT NULL,
  PRIMARY KEY (`utilisateur_id`, `covoiturage_id`),
  INDEX `fk_participe_utilisateur_idx` (`utilisateur_id` ASC),
  INDEX `fk_participe_covoiturage_idx` (`covoiturage_id` ASC),
  CONSTRAINT `fk_participe_utilisateur`
    FOREIGN KEY (`utilisateur_id`)
    REFERENCES `utilisateur` (`utilisateur_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_participe_covoiturage`
    FOREIGN KEY (`covoiturage_id`)
    REFERENCES `covoiturage` (`covoiturage_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ;

-- -----------------------------------------------------
-- Table `configuration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `configuration` (
  `id_configuration` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_configuration`)
) ;

-- -----------------------------------------------------
-- Table `parametre`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `parametre` (
  `parametre_id` INT NOT NULL AUTO_INCREMENT,
  `propriete` VARCHAR(50) NOT NULL UNIQUE,
  `valeur` VARCHAR(50) NOT NULL,
  `id_configuration` INT NOT NULL, -- Clé étrangère vers `configuration`
  PRIMARY KEY (`parametre_id`),
  INDEX `fk_parametre_configuration_idx` (`id_configuration` ASC),
  CONSTRAINT `fk_parametre_configuration_dispose`
    FOREIGN KEY (`id_configuration`)
    REFERENCES `configuration` (`id_configuration`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ;

-- Rétablir la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;