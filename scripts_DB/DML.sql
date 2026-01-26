-- Fichier DML (Data Manipulation Language) étendu pour la base de données EcoRide.
-- Contient les enregistrements initiaux.

-- USE abcrdv_ecoride_db;


-- Nettoyage 
DELETE FROM `preference`;
DELETE FROM `avis`;
DELETE FROM `avis_covoiturage`;
DELETE FROM `participe`;
DELETE FROM `covoiturage`;
DELETE FROM `voiture`;
DELETE FROM `marque`;
DELETE FROM `utilisateur`;
DELETE FROM `statut_covoiturage`;
DELETE FROM `statut_avis`;
DELETE FROM `statut_mail`;
DELETE FROM `note`;
DELETE FROM `role`;
DELETE FROM `employe`;
DELETE FROM `poste`;
DELETE FROM `departement`;
ALTER TABLE `role` AUTO_INCREMENT = 1; -- Réinitialisation des compteurs PK
ALTER TABLE `utilisateur` AUTO_INCREMENT = 1;
ALTER TABLE `marque` AUTO_INCREMENT = 1;
ALTER TABLE `voiture` AUTO_INCREMENT = 1;
ALTER TABLE `covoiturage` AUTO_INCREMENT = 1;
ALTER TABLE `participe` AUTO_INCREMENT = 1;
ALTER TABLE `preference` AUTO_INCREMENT = 1;
ALTER TABLE `avis` AUTO_INCREMENT = 1;
ALTER TABLE `avis_covoiturage` AUTO_INCREMENT = 1;
ALTER TABLE `statut_covoiturage` AUTO_INCREMENT = 1;
ALTER TABLE `statut_avis` AUTO_INCREMENT = 1;
ALTER TABLE `statut_mail` AUTO_INCREMENT = 1;
ALTER TABLE `note` AUTO_INCREMENT = 1;
ALTER TABLE `departement` AUTO_INCREMENT = 1;
ALTER TABLE `poste` AUTO_INCREMENT = 1;
ALTER TABLE `employe` AUTO_INCREMENT = 1;

-- 1. Insertion dans la table `role`
INSERT INTO `role` (`libelle`) VALUES
('Chauffeur'),
('Passager'),
('Chauffeur-Passager');

-- 2. Insertion dans la table `configuration`

-- 3. Insertion dans la table `parametre`

-- 4. Insertion initiale dans la table `utilisateur` 

-- -----------------------------------------------------
-- 5. -- Insertion initiale dans la table `utilisateur`
-- -----------------------------------------------------

-- Utilisateur ID 1 : Rôle Chauffeur (1)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (1, 'Dupont', 'Alice', 'alice.d@mail.fr', 'pwd1', '0601010101', '12 Rue de Paris, Paris', '1985-05-12', 'Alice1', 1);

-- Utilisateur ID 2 : Rôle Chauffeur (1)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (2, 'Durand', 'Bob', 'bob.d@mail.fr', 'pwd2', '0602020202', '5 Av. de Lyon, Lyon', '1988-03-20', 'Bob2', 1);

-- Utilisateur ID 3 : Rôle Chauffeur (1)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (3, 'Leroy', 'Cecile', 'cecile.l@mail.fr', 'pwd3', '0603030303', '8 Bd. de la Mer, Nice', '1990-11-02', 'Cecile3', 1);

-- Utilisateur ID 4 : Rôle Chauffeur (1)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (4, 'Morel', 'David', 'david.m@mail.fr', 'pwd4', '0604040404', '3 Rue du Port, Nantes', '1982-07-15', 'David4', 1);

-- Utilisateur ID 5 : Rôle Chauffeur (1)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (5, 'Simon', 'Eva', 'eva.s@mail.fr', 'pwd5', '0605050505', '10 Pl. du Capitole, Toulouse', '1995-01-22', 'Eva5', 1);

-- Utilisateur ID 6 : Rôle Passager (2)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (6, 'Laurent', 'Frank', 'frank.l@mail.fr', 'pwd6', '0706060606', '1 Pl. de Lille, Lille', '1992-09-10', 'Frank6', 2);

-- Utilisateur ID 7 : Rôle Passager (2)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (7, 'Lefebvre', 'Gina', 'gina.l@mail.fr', 'pwd7', '0707070707', '22 Av. Verte, Bordeaux', '1998-04-30', 'Gina7', 2);

-- Utilisateur ID 8 : Rôle Passager (2)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (8, 'Michel', 'Hugo', 'hugo.m@mail.fr', 'pwd8', '0708080808', '4 Rue de l''Est, Strasbourg', '2000-12-05', 'Hugo8', 2);

-- Utilisateur ID 9 : Rôle Passager (2)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (9, 'Garcia', 'Ingrid', 'ingrid.g@mail.fr', 'pwd9', '0709090909', '9 Bd. Sud, Marseille', '1993-02-14', 'Ingrid9', 2);

-- Utilisateur ID 10 : Rôle Passager (2)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (10, 'Bertrand', 'Jules', 'jules.b@mail.fr', 'pwd10', '0710101010', '15 Rue du Lac, Annecy', '1997-06-18', 'Jules10', 2);

-- Utilisateur ID 11 : Rôle Chauffeur-Passager (3)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (11, 'Roux', 'Karine', 'karine.r@mail.fr', 'pwd11', '0611111111', '1 Pl. Royale, Reims', '1989-10-25', 'Karine11', 3);

-- Utilisateur ID 12 : Rôle Chauffeur-Passager (3)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (12, 'Vincent', 'Louis', 'louis.v@mail.fr', 'pwd12', '0612121212', '3 Rue de la Paix, Paris', '1986-08-12', 'Louis12', 3);

-- Utilisateur ID 13 : Rôle Chauffeur-Passager (3)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (13, 'Fournier', 'Manon', 'manon.f@mail.fr', 'pwd13', '0613131313', '10 Av. Foch, Metz', '1994-03-01', 'Manon13', 3);

-- Utilisateur ID 14 : Rôle Chauffeur-Passager (3)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (14, 'Moreau', 'Nicolas', 'nicolas.m@mail.fr', 'pwd14', '0614141414', '88 Bd. Central, Lyon', '1991-05-30', 'Nicolas14', 3);

-- Utilisateur ID 15 : Rôle Chauffeur-Passager (3)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `pseudo`, `role_id`) 
VALUES (15, 'Girard', 'Ophelie', 'ophelie.g@mail.fr', 'pwd15', '0615151515', '2 Pl. Bellecour, Lyon', '1999-11-15', 'Ophelie15', 3);


-- -----------------------------------------------------
-- 5. Insertion initiale dans la table `marque`
-- -----------------------------------------------------

INSERT INTO `marque` (`libelle`) VALUES
('Renault'),
('Peugeot'),
('Toyota'),
('Tesla'),
('Volkswagen'),
('Citroën'),
('BMW'),
('Mercedes');


-- -----------------------------------------------------
-- 5.a. Insertion initiale dans la table `statut_covoiturage`
-- -----------------------------------------------------

INSERT INTO `statut_covoiturage` (`libelle`) VALUES
('annulé'),
('prévu'),
('confirmé'),
('en_cours'),
('terminé');


-- -----------------------------------------------------
-- 5.b. Insertion initiale dans la table `statut_avis`
-- -----------------------------------------------------

INSERT INTO `statut_avis` (`libelle`) VALUES
('publié'),
('modération'),
('refusé');


-- -----------------------------------------------------
-- 5.c. Insertion initiale dans la table `statut_mail`
-- -----------------------------------------------------

INSERT INTO `statut_mail` (`libelle`) VALUES
('non_confirmmé'),
('confirmmé');


-- -----------------------------------------------------
-- 5.c. Insertion initiale dans la table `note`
-- -----------------------------------------------------

INSERT INTO `note` (`libelle`) VALUES
('Très insatisfait(e)'),
('Plutôt mécontent(e)'),
('Ni insatisfait(e), ni mécontent(e) '),
('Plutôt content'),
('Très content');


-- -----------------------------------------------------
-- 6. -- Insertion initiale dans la table `voiture`
-- -----------------------------------------------------

INSERT INTO `voiture` (`voiture_id`, `modele`, `immatriculation`, `energie`, `couleur`, `date_premiere_immatriculation`, `marque_id`, `utilisateur_id`)  VALUES
(1, 'Clio V', 'AA-101-BB', 'Essence', 'Rouge', '2020-01-20', 1, 1),			-- Voiture ID 1 : Marque Renault (1) appartenant à Alice1 (ID 1) 
(2, '208', 'CC-202-DD', 'Diesel', 'Bleu', '2019-06-10', 2, 2),				-- Voiture ID 2 : Marque Peugeot (2) appartenant à Bob2 (ID 2)
(3, 'Yaris', 'EE-303-FF', 'Hybride', 'Blanc', '2021-03-05', 3, 3),			-- Voiture ID 3 : Marque Toyota (3) appartenant à Cecile3 (ID 3)
(4, 'Model 3', 'GG-404-HH', 'Électrique', 'Noir', '2017-08-10', 4, 4),		-- Voiture ID 4 : Marque Tesla (4) appartenant à David4 (ID 4)
(5, 'Golf 8', 'II-505-JJ', 'Essence', 'Gris', '2022-01-01', 5, 5),			-- Voiture ID 5 : Marque Volkswagen (5) appartenant à Eva5 (ID 5)
(6, 'C3 Aircross', 'KK-606-LL', 'Diesel', 'Gris', '2019-11-30', 6, 11),		-- Voiture ID 6 : Marque Citroën (6) appartenant à Karine11 (ID 11)
(7, 'Serie 1', 'MM-707-NN', 'Essence', 'Blanc', '2021-06-05', 7, 12),		-- Voiture ID 7 : Marque BMW (7) appartenant à Louis12 (ID 12)
(8, 'Classe A', 'OO-808-PP', 'Hybride', 'Noir', '2021-06-05', 8, 13),		-- Voiture ID 8 : Marque Mercedes (8) appartenant à Manon13 (ID 13)
(9, 'Captur', 'QQ-909-RR', 'Essence', 'Orange', '2021-06-05', 1, 14),		-- Voiture ID 9 : Marque Renault (1) appartenant à Nicolas14 (ID 14)
(10, '3008', 'SS-010-TT', 'Diesel', 'Bleu Marine', '2016-03-17', 2, 15),	-- Voiture ID 10 : Marque Peugeot (2) appartenant à Ophelie15 (ID 15)
(11, 'Corolla', 'UU-111-VV', 'Hybride', 'Gris', '2021-05-01', 3, 1);		-- Voiture ID 11 : Marque Toyota (3) appartenant à Alice1 (ID 1)

-- Voiture ID 12 : Marque Tesla (4) appartenant à Bob2 (ID 2)
INSERT INTO `voiture` (`voiture_id`, `modele`, `immatriculation`, `energie`, `couleur`, `marque_id`, `utilisateur_id`) 
VALUES (12, 'Model Y', 'WW-212-XX', 'Électrique', 'Blanc', 4, 2);

-- Voiture ID 13 : Marque Volkswagen (5) appartenant à Cecile3 (ID 3)
INSERT INTO `voiture` (`voiture_id`, `modele`, `immatriculation`, `energie`, `couleur`, `marque_id`, `utilisateur_id`) 
VALUES (13, 'Polo', 'YY-313-ZZ', 'Essence', 'Rouge', 5, 3);

-- Voiture ID 14 : Marque Citroën (6) appartenant à David4 (ID 4)
INSERT INTO `voiture` (`voiture_id`, `modele`, `immatriculation`, `energie`, `couleur`, `marque_id`, `utilisateur_id`) 
VALUES (14, 'C4 Cactus', 'AB-414-CD', 'Diesel', 'Jaune', 6, 4);

-- Voiture ID 15 : Marque BMW (7) appartenant à Eva5 (ID 5)
INSERT INTO `voiture` (`voiture_id`, `modele`, `immatriculation`, `energie`, `couleur`, `marque_id`, `utilisateur_id`) 
VALUES (15, 'X3', 'EF-515-GH', 'Essence', 'Noir', 7, 5);


-- -----------------------------------------------------
-- 7. -- Insertion initiale dans la table `covoiturage`
-- -----------------------------------------------------

-- Covoiturage 1 : Voiture ID 1, Statut prévu (2). Créé par Alice1
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (1, '2026-06-01', '08:00', 'Paris', '2026-06-01', '12:30', 'Lyon', 2, NULL, 3, 25.50, 1);

-- Covoiturage 2 : Voiture ID 2, Statut confirmé (3). Créé par Bob2
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (2, '2026-06-02', '09:30', 'Lyon', '2026-06-02', '13:00', 'Marseille', 3, NULL, 2, 18.00, 2);

-- Covoiturage 3 : Voiture ID 3, Statut prévu (2). Créé par Cecile3
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (3, '2026-06-03', '14:00', 'Nice', '2026-06-03', '15:30', 'Antibes', 2, NULL, 4, 8.00, 3);

-- Covoiturage 4 : Voiture ID 4, Statut prévu (2). Créé par David4
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (4, '2026-06-04', '07:15', 'Nantes', '2026-06-04', '11:45', 'Bordeaux', 2, NULL, 3, 22.00, 4);

-- Covoiturage 5 : Voiture ID 5, Statut confirmé (3). Créé par Eva5
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (5, '2026-06-05', '10:00', 'Toulouse', '2026-06-05', '14:30', 'Montpellier', 3, NULL, 3, 19.50, 5);

-- Covoiturage 6 : Voiture ID 6, Statut prévu (2). Créé par Karine11
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (6, '2026-06-06', '06:45', 'Reims', '2026-06-06', '08:30', 'Paris', 2, NULL, 4, 15.00, 6);

-- Covoiturage 7 : Voiture ID 7, Statut confirmé (3). Créé par Louis12
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (7, '2026-06-07', '18:20', 'Paris', '2026-06-07', '21:00', 'Lille', 3, NULL, 2, 28.00, 7);

-- Covoiturage 8 : Voiture ID 8, Statut prévu (2). Créé par Manon13
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (8, '2026-06-08', '08:00', 'Metz', '2026-06-08', '09:45', 'Nancy', 2, NULL, 3, 10.00, 8);

-- Covoiturage 9 : Voiture ID 9, Statut confirmé (3). Créé par Nicolas14
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (9, '2026-06-09', '11:30', 'Lyon', '2026-06-09', '16:00', 'Grenoble', 3, NULL, 4, 12.00, 9);

-- Covoiturage 10 : Voiture ID 10, Statut annulé (1). Créé par Ophelie15
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (10, '2026-06-10', '07:00', 'Saint-Étienne', '2026-06-10', '08:15', 'Lyon', 1, NULL, 3, 7.50, 10);

-- Covoiturage 11 : Voiture ID 1, Statut prévu (2). Créé par Alice1
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (11, '2026-06-11', '13:00', 'Paris', '2026-06-11', '15:30', 'Orléans', 2, NULL, 3, 14.00, 1);

-- Covoiturage 12 : Voiture ID 2, Statut confirmé (3). Créé par Bob2
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (12, '2026-06-12', '05:30', 'Lyon', '2026-06-12', '10:00', 'Toulouse', 3, NULL, 2, 45.00, 2);

-- Covoiturage 13 : Voiture ID 3, Statut prévu (2). Créé par Cecile3
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (13, '2026-06-13', '09:15', 'Antibes', '2026-06-13', '10:00', 'Cannes', 2, NULL, 3, 5.00, 3);

-- Covoiturage 14 : Voiture ID 4, Statut prévu (2). Créé par David4
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (14, '2026-06-14', '08:00', 'Bordeaux', '2026-06-14', '11:00', 'Anglet', 2, NULL, 2, 19.00, 4);

-- Covoiturage 15 : Voiture ID 5, Statut confirmé (3). Créé par Eva5
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `avis_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) 
VALUES (15, '2026-06-15', '16:45', 'Montpellier', '2026-06-15', '18:15', 'Narbonne', 3, NULL, 3, 11.00, 5);


-- -----------------------------------------------------
-- 8. -- Insertion initiale dans la table `participe`
-- -----------------------------------------------------

-- Frank6 (ID 6) participe au trajet 1
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (6, 1);
-- Gina7 (ID 7) participe au trajet 2
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (7, 2);
-- Hugo8 (ID 8) participe au trajet 3
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (8, 3);
-- Ingrid9 (ID 9) participe au trajet 4
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (9, 4);
-- Jules10 (ID 10) participe au trajet 5
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (10, 5);
-- Frank6 (ID 6) participe au trajet 6
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (6, 6);
-- Gina7 (ID 7) participe au trajet 7
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (7, 7);
-- Hugo8 (ID 8) participe au trajet 8
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (8, 8);
-- Ingrid9 (ID 9) participe au trajet 9
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (9, 9);
-- Jules10 (ID 10) participe au trajet 11
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (10, 11);
-- Karine11 (ID 11) participe au trajet 12
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (11, 12);
-- Louis12 (ID 12) participe au trajet 13
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (12, 13);
-- Manon13 (ID 13) participe au trajet 14
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (13, 14);
-- Nicolas14 (ID 14) participe au trajet 15
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (14, 15);
-- Ophelie15 (ID 15) participe au trajet 1
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES (15, 1);


-- -----------------------------------------------------
-- 9. -- Insertion initiale dans la table `avis`
-- -----------------------------------------------------

-- Note 5 (Très content), Statut 1 (publié). Déposé par Frank6 (ID 6)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (1, 'Super trajet, Alice est ponctuelle.', 5, 1, 6);

-- Note 4 (Plutôt content), Statut 1 (publié). Déposé par Gina7 (ID 7)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (2, 'Conduite sûre, je recommande Bob.', 4, 1, 7);

-- Note 5 (Très content), Statut 1 (publié). Déposé par Hugo8 (ID 8)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (3, 'Voiture très propre et trajet rapide.', 5, 1, 8);

-- Note 4 (Plutôt content), Statut 2 (modération). Déposé par Ingrid9 (ID 9)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (4, 'Bonne expérience globale.', 4, 2, 9);

-- Note 3 (Ni-ni), Statut 1 (publié). Déposé par Jules10 (ID 10)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (5, 'Trajet correct mais un peu de retard.', 3, 1, 10);

-- Note 5 (Très content), Statut 1 (publié). Déposé par Karine11 (ID 11)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (6, 'Génial, Karine est une super conductrice.', 5, 1, 11);

-- Note 4 (Plutôt content), Statut 1 (publié). Déposé par Louis12 (ID 12)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (7, 'Bien passé.', 4, 1, 12);

-- Note 3 (Ni-ni), Statut 1 (publié). Déposé par Manon13 (ID 13)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (8, 'Moyen, pas très bavard.', 3, 1, 13);

-- Note 5 (Très content), Statut 1 (publié). Déposé par Nicolas14 (ID 14)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (9, 'Top service, merci Nicolas.', 5, 1, 14);

-- Note 4 (Plutôt content), Statut 1 (publié). Déposé par Ophelie15 (ID 15)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (10, 'Satisfaite.', 4, 1, 15);

-- Note 5 (Très content), Statut 1 (publié). Déposé par Frank6 (ID 6)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (11, 'Encore un super trajet!', 5, 1, 6);

-- Note 5 (Très content), Statut 1 (publié). Déposé par Gina7 (ID 7)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (12, 'Parfait.', 5, 1, 7);

-- Note 4 (Plutôt content), Statut 1 (publié). Déposé par Hugo8 (ID 8)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (13, 'Très bien.', 4, 1, 8);

-- Note 5 (Très content), Statut 2 (modération). Déposé par Ingrid9 (ID 9)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (14, 'Excellent!', 5, 2, 9);

-- Note 5 (Très content), Statut 1 (publié). Déposé par Jules10 (ID 10)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) 
VALUES (15, 'Rien à redire.', 5, 1, 10);


-- -----------------------------------------------------
-- 10. -- Insertion initiale dans la table `avis_covoiturage`
-- -----------------------------------------------------

INSERT INTO `avis_covoiturage` (`libelle`) VALUES
('s’est bien passé'),
('s’est mal passé');


-- -----------------------------------------------------
-- PARTIE ADMINISTRATEUR
-- -----------------------------------------------------

INSERT INTO `departement` (nom_dept, lieu) VALUES
('Ressources Humaines', 'Paris'),
('Informatique',        'Lyon');

INSERT INTO `poste` (intitule, salaire_min, salaire_max) VALUES
('Admiinstrateur', 60000, 70000),
('Employe', 50000, 60000);

INSERT INTO `employe` (nom, prenom, email, password, date_embauche, salaire, id_poste, id_dept, id_manager) VALUES
('ndr', 'ndr', 'ndr@mail.com', 'pwd_admin', '2019-09-01',65000, 1, 2, NULL), 	-- manager
('Durand', 'Sophie', 'sophie.durand@mail.com', 'pwd_sophie', '2021-03-10', 42000, 2, 2, 1); -- Employe rattachés au manager