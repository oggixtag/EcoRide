-- -----------------------------------------------------
-- Script de suppression des tables (DROP)
-- Ordre basé sur les contraintes de clés étrangères (enfants avant parents)
-- -----------------------------------------------------

-- Désactiver la vérification des clés étrangères pour permettre la suppression
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Tables d'association (dépendent de deux autres tables)
DROP TABLE IF EXISTS `participe`;

-- 2. Tables dépendant de `utilisateur`, `voiture`, `configuration` ou `marque`
DROP TABLE IF EXISTS `covoiturage`;  -- Dépend de voiture et utilisateur
DROP TABLE IF EXISTS `avis`;         -- Dépend de utilisateur
DROP TABLE IF EXISTS `parametre`;    -- Dépend de configuration

-- 3. Tables dépendant uniquement d'elles-mêmes ou de petites entités
DROP TABLE IF EXISTS `voiture`;      -- Dépend de marque
DROP TABLE IF EXISTS `utilisateur`;  -- Dépend de role

-- 4. Tables principales (qui ne dépendent d'aucune autre table)
DROP TABLE IF EXISTS `role`;
DROP TABLE IF EXISTS `marque`;
DROP TABLE IF EXISTS `configuration`;

-- Rétablir la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;