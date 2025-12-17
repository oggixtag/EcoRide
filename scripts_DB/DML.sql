-- Fichier DML (Data Manipulation Language) étendu pour la base de données EcoRide.
-- Contient les enregistrements initiaux ainsi que l'ajout d'environ 150 nouveaux enregistrements.

-- Nettoyage optionnel (à décommenter si vous exécutez ce script plusieurs fois)
DELETE FROM `avis`;
DELETE FROM `participe`;
DELETE FROM `covoiturage`;
DELETE FROM `voiture`;
DELETE FROM `marque`;
DELETE FROM `utilisateur`;
DELETE FROM `parametre`;
DELETE FROM `configuration`;
DELETE FROM `role`;
ALTER TABLE `role` AUTO_INCREMENT = 1; -- Réinitialisation des compteurs PK
ALTER TABLE `configuration` AUTO_INCREMENT = 1;
ALTER TABLE `parametre` AUTO_INCREMENT = 1;
ALTER TABLE `utilisateur` AUTO_INCREMENT = 1;
ALTER TABLE `marque` AUTO_INCREMENT = 1;
ALTER TABLE `voiture` AUTO_INCREMENT = 1;
ALTER TABLE `covoiturage` AUTO_INCREMENT = 1;
ALTER TABLE `participe` AUTO_INCREMENT = 1;
ALTER TABLE `avis` AUTO_INCREMENT = 1;


-- 1. Insertion dans la table `role`
INSERT INTO `role` (`role_id`, `libelle`) VALUES
(1, 'Administrateur'),
(2, 'Conducteur'),
(3, 'Passager'),
(4, 'Visiteur');


-- 2. Insertion dans la table `configuration`
INSERT INTO `configuration` (`id_configuration`) VALUES
(1);

-- 3. Insertion dans la table `parametre`
INSERT INTO `parametre` (`parametre_id`, `propriete`, `valeur`, `id_configuration`) VALUES
(1, 'Titre_Site', 'EcoRide - Covoiturage Écolo', 1),
(2, 'Devise', 'EUR', 1),
(3, 'Taux_Commission', '0.05', 1),
(4, 'Support_Email', 'support@ecoride.fr', 1),
(5, 'Version_App', '1.0.0', 1);


-- 4. Insertion initiale dans la table `utilisateur` (5 premiers utilisateurs)
INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `photo`, `pseudo`, `role_id`) VALUES
(1, 'Dupont', 'Alice', 'alice.dupont@mail.com', 'password123', '0612345678', '10 Rue de Paris, 75001 Paris', '1990-05-15', NULL, 'AliceCovoit', 2), -- Conducteur
(2, 'Bernard', 'Bob', 'bob.bernard@mail.com', 'password123', '0798765432', '25 Avenue de Lyon, 69002 Lyon', '1985-11-20', NULL, 'BobTheRider', 3),   -- Passager
(3, 'Charles', 'Cécile', 'cecile.charles@mail.com', 'password123', '0660606060', '5 Bd des Plages, 06600 Antibes', '1995-03-01', NULL, 'Cece06', 2),        -- Conducteur
(4, 'David', 'Diana', 'diana.david@mail.com', 'password123', '0711223344', '8 Rue de Marseille, 13008 Marseille', '2000-08-22', NULL, 'DianaTravel', 3), -- Passager
(5, 'Emmanuel', 'Eva', 'eva.emmanuel@mail.com', 'password123', '0655443322', '3 Place de Bordeaux, 33000 Bordeaux', '1978-01-10', NULL, 'EcoEva', 2);      -- Conducteur

INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `photo`, `pseudo`, `role_id`) VALUES
(6, 'Fournier', 'François', 'francois.fournier@mail.com', 'pass456', '0699887766', '1 Rue de Lille, 59000 Lille', '1992-07-25', NULL, 'F-Ride', 3),
(7, 'Garnier', 'Gina', 'gina.garnier@mail.com', 'pass789', '0710203040', '12 Av. des Fleurs, 44000 Nantes', '1988-02-14', NULL, 'GinaG', 2),
(8, 'Henry', 'Hugo', 'hugo.henry@mail.com', 'pass101', '0633445566', '33 Pl. de Bretagne, 35000 Rennes', '1997-12-05', NULL, 'Huguito', 3),
(9, 'Ibanez', 'Ingrid', 'ingrid.ibanez@mail.com', 'pass202', '0755667788', '45 Rue de l''Est, 67000 Strasbourg', '1980-06-30', NULL, 'StrasRide', 2),
(10, 'Joly', 'Jules', 'jules.joly@mail.com', 'pass303', '0677889900', '19 Bd du Midi, 83000 Toulon', '2002-04-18', NULL, 'JulesJ', 3),
(11, 'Klein', 'Karine', 'karine.klein@mail.com', 'pass404', '0700112233', '2 Rue du Lac, 74000 Annecy', '1993-09-01', NULL, 'Kiki74', 2),
(12, 'Lambert', 'Louis', 'louis.lambert@mail.com', 'pass505', '0622334455', '8 Av. du Nord, 59000 Lille', '1987-11-29', NULL, 'LuluCar', 3),
(13, 'Martin', 'Manon', 'manon.martin@mail.com', 'pass606', '0744556677', '14 Rue de l''Opéra, 69001 Lyon', '1999-01-20', NULL, 'ManonM', 2),
(14, 'Noël', 'Nicolas', 'nicolas.noel@mail.com', 'pass707', '0688990011', '7 Av. du Sud, 13008 Marseille', '1975-03-12', NULL, 'NicoTravel', 3),
(15, 'Olivier', 'Ophélie', 'ophelie.olivier@mail.com', 'pass808', '0799001122', '22 Rue des Vignes, 33000 Bordeaux', '1991-10-08', NULL, 'OphélieO', 2),
(16, 'Petit', 'Paul', 'paul.petit@mail.com', 'pass909', '0611223344', '3 Pl. de la Liberté, 44000 Nantes', '1984-05-23', NULL, 'Polo44', 3),
(17, 'Quentin', 'Quitterie', 'quitterie.quentin@mail.com', 'pass110', '0733445566', '5 Rue du Château, 35000 Rennes', '1996-02-17', NULL, 'QuittR', 2),
(18, 'Robert', 'Romain', 'romain.robert@mail.com', 'pass111', '0655667788', '17 Av. de l''Europe, 67000 Strasbourg', '1982-08-03', NULL, 'RoroStr', 3),
(19, 'Simon', 'Sarah', 'sarah.simon@mail.com', 'pass112', '0777889900', '9 Bd des Palmiers, 83000 Toulon', '1994-11-11', NULL, 'SarahS', 2),
(20, 'Thomas', 'Théo', 'theo.thomas@mail.com', 'pass113', '0699001122', '24 Rue du Rhône, 69002 Lyon', '2001-01-01', NULL, 'Theo69', 3),
(21, 'Vincent', 'Valérie', 'valerie.vincent@mail.com', 'pass114', '0710111213', '1 Bd de la Loire, 44000 Nantes', '1989-07-07', NULL, 'ValV', 2),
(22, 'Weber', 'William', 'william.weber@mail.com', 'pass115', '0620212223', '30 Rue des Arts, 33000 Bordeaux', '1998-10-19', NULL, 'WillBdx', 3),
(23, 'Xavier', 'Xenia', 'xenia.xavier@mail.com', 'pass116', '0740414243', '15 Rue des Etoiles, 75001 Paris', '1976-04-28', NULL, 'XeniaX', 2),
(24, 'Yann', 'Yasmine', 'yasmine.yann@mail.com', 'pass117', '0660616263', '21 Av. du Soleil, 13008 Marseille', '1990-12-01', NULL, 'YasmineY', 3),
(25, 'Zimmer', 'Zachary', 'zachary.zimmer@mail.com', 'pass118', '0780818283', '4 Rue de la Paix, 67000 Strasbourg', '1983-03-09', NULL, 'ZackZ', 2);


-- 5. Insertion initiale dans la table `marque`
INSERT INTO `marque` (`marque_id`, `libelle`) VALUES
(1, 'Renault'),
(2, 'Peugeot'),
(3, 'Toyota'),
(4, 'Tesla'),
(5, 'Volkswagen'),
(6, 'Citroën'),
(7, 'BMW'),
(8, 'Mercedes');


-- 6. Insertion initiale dans la table `voiture` (5 premières voitures)
INSERT INTO `voiture` (`voiture_id`, `modele`, `immatriculation`, `energie`, `couleur`, `date_premiere_immatriculation`, `marque_id`) VALUES
(1, 'Clio V', 'AA-123-BB', 'Diesel', 'Rouge', '2020-01-20', 1),   -- Renault (Alice, ID 1)
(2, '208', 'CC-456-DD', 'Essence', 'Bleu', '2019-06-10', 2),      -- Peugeot (Cécile, ID 3)
(3, 'Yaris Hybride', 'EE-789-FF', 'Hybride', 'Blanc', '2021-03-05', 3), -- Toyota (Eva, ID 5)
(4, 'Model 3', 'GG-012-HH', 'Électrique', 'Noir', '2022-10-15', 4), -- Tesla (Alice, ID 1)
(5, 'Golf VII', 'II-345-JJ', 'Essence', 'Gris', '2018-02-28', 5);  -- Volkswagen (Cécile, ID 3)

INSERT INTO `voiture` (`voiture_id`, `modele`, `immatriculation`, `energie`, `couleur`, `date_premiere_immatriculation`, `marque_id`) VALUES
(6, 'C4 Cactus', 'KK-678-LL', 'Diesel', 'Vert', '2017-08-10', 6), -- Citroën (Gina, ID 7)
(7, 'Série 1', 'MM-901-NN', 'Essence', 'Bleu Marine', '2020-04-25', 7), -- BMW (Ingrid, ID 9)
(8, 'Classe C', 'OO-234-PP', 'Hybride', 'Noir Métal', '2022-01-01', 8), -- Mercedes (Karine, ID 11)
(9, 'Captur', 'QQ-567-RR', 'Essence', 'Orange', '2023-09-19', 1), -- Renault (Manon, ID 13)
(10, '308 SW', 'SS-890-TT', 'Diesel', 'Gris', '2019-11-30', 2),  -- Peugeot (Ophélie, ID 15)
(11, 'CHR', 'UU-123-VV', 'Hybride', 'Rouge', '2021-06-05', 3),  -- Toyota (Quitterie, ID 17)
(12, 'Model Y', 'WW-456-XX', 'Électrique', 'Blanc', '2023-01-15', 4), -- Tesla (Sarah, ID 19)
(13, 'Polo', 'YY-789-ZZ', 'Essence', 'Jaune', '2016-03-17', 5),  -- Volkswagen (Valérie, ID 21)
(14, 'C3 Aircross', 'AB-012-CD', 'Diesel', 'Gris Clair', '2020-10-10', 6), -- Citroën (Xenia, ID 23)
(15, 'Série 3', 'EF-345-GH', 'Essence', 'Blanc', '2021-05-01', 7);  -- BMW (Zachary, ID 25)


-- 7. Insertion initiale dans la table `covoiturage` (5 premiers trajets)
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut`, `nb_place`, `prix_personne`, `voiture_id`, `utilisateur_id`) VALUES
(1, '2025-12-15', '08:00:00', 'Paris', '2025-12-15', '12:30:00', 'Lyon', 'Confirmé', 3, 25.50, 1, 1),
(2, '2025-12-10', '14:30:00', 'Antibes', '2025-12-10', '15:15:00', 'Nice', 'Confirmé', 2, 5.00, 3, 3),
(3, '2025-12-20', '10:00:00', 'Marseille', '2025-12-20', '11:30:00', 'Toulon', 'Annulé', 4, 12.00, 4, 5),
(4, '2025-12-25', '16:00:00', 'Bordeaux', '2025-12-25', '21:00:00', 'Nantes', 'Confirmé', 3, 35.00, 1, 1),
(5, '2026-01-05', '07:00:00', 'Lyon', '2026-01-05', '11:30:00', 'Paris', 'Prévu', 4, 28.00, 3, 3);

INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut`, `nb_place`, `prix_personne`, `voiture_id`, `utilisateur_id`) VALUES
-- Trajets Alice (ID 1, voitures 1, 4)
(6, '2026-01-10', '15:00:00', 'Lyon', '2026-01-10', '19:30:00', 'Bordeaux', 'Prévu', 3, 30.00, 4, 1),
(7, '2026-01-15', '06:30:00', 'Paris', '2026-01-15', '10:00:00', 'Rennes', 'Confirmé', 2, 40.00, 1, 1),
(8, '2026-01-20', '11:00:00', 'Rennes', '2026-01-20', '14:30:00', 'Paris', 'Prévu', 3, 40.00, 1, 1),
-- Trajets Cécile (ID 3, voitures 2, 5)
(9, '2026-02-01', '09:00:00', 'Nice', '2026-02-01', '13:00:00', 'Marseille', 'Confirmé', 0, 22.00, 4, 3),
(10, '2026-02-05', '17:30:00', 'Marseille', '2026-02-05', '18:30:00', 'Aix-en-Provence', 'Confirmé', 3, 6.50, 2, 3),
-- Trajets Eva (ID 5, voiture 3)
(11, '2026-02-10', '08:45:00', 'Bordeaux', '2026-02-10', '13:00:00', 'Toulouse', 'Prévu', 4, 29.00, 3, 5),
(12, '2026-02-15', '14:00:00', 'Toulouse', '2026-02-15', '18:15:00', 'Bordeaux', 'Annulé', 3, 29.00, 3, 5),
-- Trajets Gina (ID 7, voiture 6)
(13, '2026-03-01', '10:00:00', 'Nantes', '2026-03-01', '13:30:00', 'La Rochelle', 'Confirmé', 4, 18.00, 6, 7),
(14, '2026-03-05', '12:00:00', 'La Rochelle', '2026-03-05', '15:30:00', 'Nantes', 'Prévu', 4, 18.00, 6, 7),
-- Trajets Ingrid (ID 9, voiture 7)
(15, '2026-03-10', '07:00:00', 'Strasbourg', '2026-03-10', '11:00:00', 'Metz', 'Confirmé', 3, 20.50, 7, 9),
(16, '2026-03-15', '16:00:00', 'Metz', '2026-03-15', '20:00:00', 'Strasbourg', 'Confirmé', 3, 20.50, 7, 9),
-- Trajets Karine (ID 11, voiture 8)
(17, '2026-04-01', '13:00:00', 'Annecy', '2026-04-01', '15:30:00', 'Genève', 'Confirmé', 2, 15.00, 8, 11),
(18, '2026-04-05', '09:30:00', 'Genève', '2026-04-05', '12:00:00', 'Annecy', 'Prévu', 2, 15.00, 8, 11),
-- Trajets Manon (ID 13, voiture 9)
(19, '2026-04-10', '18:00:00', 'Lyon', '2026-04-10', '22:00:00', 'Dijon', 'Confirmé', 4, 26.00, 9, 13),
(20, '2026-04-15', '07:45:00', 'Dijon', '2026-04-15', '11:45:00', 'Lyon', 'Prévu', 4, 26.00, 9, 13),
-- Trajets Ophélie (ID 15, voiture 10)
(21, '2026-05-01', '09:15:00', 'Bordeaux', '2026-05-01', '14:30:00', 'Limoges', 'Confirmé', 3, 21.50, 10, 15),
(22, '2026-05-05', '15:00:00', 'Limoges', '2026-05-05', '20:15:00', 'Bordeaux', 'Prévu', 3, 21.50, 10, 15),
-- Trajets Quitterie (ID 17, voiture 11)
(23, '2026-05-10', '11:00:00', 'Rennes', '2026-05-10', '14:00:00', 'St-Malo', 'Confirmé', 4, 10.00, 11, 17),
(24, '2026-05-15', '16:00:00', 'St-Malo', '2026-05-15', '19:00:00', 'Rennes', 'Annulé', 4, 10.00, 11, 17),
-- Trajets Sarah (ID 19, voiture 12)
(25, '2026-06-01', '06:00:00', 'Toulon', '2026-06-01', '10:00:00', 'Nice', 'Confirmé', 3, 19.50, 12, 19),
(26, '2026-06-05', '13:00:00', 'Nice', '2026-06-05', '17:00:00', 'Toulon', 'Prévu', 3, 19.50, 12, 19),
-- Trajets Valérie (ID 21, voiture 13)
(27, '2026-06-10', '09:00:00', 'Nantes', '2026-06-10', '13:00:00', 'Angers', 'Confirmé', 4, 12.50, 13, 21),
(28, '2026-06-15', '14:30:00', 'Angers', '2026-06-15', '18:30:00', 'Nantes', 'Confirmé', 4, 12.50, 13, 21),
-- Trajets Xenia (ID 23, voiture 14)
(29, '2026-07-01', '11:00:00', 'Paris', '2026-07-01', '15:00:00', 'Orléans', 'Prévu', 3, 17.00, 14, 23),
(30, '2026-07-05', '16:00:00', 'Orléans', '2026-07-05', '20:00:00', 'Paris', 'Confirmé', 3, 17.00, 14, 23),
-- Trajets Zachary (ID 25, voiture 15)
(31, '2026-07-10', '08:30:00', 'Strasbourg', '2026-07-10', '12:30:00', 'Nancy', 'Confirmé', 2, 24.50, 15, 25),
(32, '2026-07-15', '13:00:00', 'Nancy', '2026-07-15', '17:00:00', 'Strasbourg', 'Prévu', 2, 24.50, 15, 25),
-- Trajets supplémentaires
(33, '2026-08-01', '10:00:00', 'Paris', '2026-08-01', '16:00:00', 'Lille', 'Confirmé', 4, 28.50, 9, 13),
(34, '2026-08-05', '14:00:00', 'Lille', '2026-08-05', '20:00:00', 'Paris', 'Prévu', 4, 28.50, 9, 13),
(35, '2026-08-10', '07:30:00', 'Lyon', '2026-08-10', '10:30:00', 'Grenoble', 'Confirmé', 3, 15.00, 7, 9),
(36, '2026-08-15', '17:00:00', 'Grenoble', '2026-08-15', '20:00:00', 'Lyon', 'Prévu', 3, 15.00, 7, 9),
(37, '2026-08-20', '13:30:00', 'Bordeaux', '2026-08-20', '17:00:00', 'La Rochelle', 'Confirmé', 2, 19.00, 10, 15),
(38, '2026-08-25', '11:00:00', 'La Rochelle', '2026-08-25', '14:30:00', 'Bordeaux', 'Prévu', 2, 19.00, 10, 15),
(39, '2026-09-01', '05:00:00', 'Marseille', '2026-09-01', '11:00:00', 'Nice', 'Confirmé', 4, 32.00, 1, 1),
(40, '2026-09-05', '18:00:00', 'Nice', '2026-09-05', '20:30:00', 'Cannes', 'Prévu', 3, 8.00, 3, 5),
(41, '2026-09-10', '09:00:00', 'Strasbourg', '2026-09-10', '13:00:00', 'Colmar', 'Confirmé', 4, 11.50, 15, 25),
(42, '2026-09-15', '14:00:00', 'Colmar', '2026-09-15', '18:00:00', 'Strasbourg', 'Confirmé', 4, 11.50, 15, 25),
(43, '2026-09-20', '10:00:00', 'Nantes', '2026-09-20', '13:00:00', 'Vannes', 'Prévu', 3, 14.00, 6, 7),
(44, '2026-09-25', '15:00:00', 'Vannes', '2026-09-25', '18:00:00', 'Nantes', 'Confirmé', 3, 14.00, 6, 7),
(45, '2026-10-01', '06:45:00', 'Rennes', '2026-10-01', '10:45:00', 'Le Mans', 'Confirmé', 2, 18.00, 14, 23),
(46, '2026-10-05', '11:00:00', 'Le Mans', '2026-10-05', '15:00:00', 'Rennes', 'Prévu', 2, 18.00, 14, 23),
(47, '2026-10-10', '14:00:00', 'Toulouse', '2026-10-10', '17:00:00', 'Albi', 'Annulé', 4, 10.00, 11, 17),
(48, '2026-10-15', '18:00:00', 'Albi', '2026-10-15', '21:00:00', 'Toulouse', 'Prévu', 4, 10.00, 11, 17),
(49, '2026-10-20', '08:00:00', 'Marseille', '2026-10-20', '14:00:00', 'Lyon', 'Confirmé', 3, 45.00, 12, 19),
(50, '2026-10-25', '16:30:00', 'Lyon', '2026-10-25', '22:30:00', 'Marseille', 'Confirmé', 3, 45.00, 12, 19),
(51, '2026-11-01', '07:00:00', 'Paris', '2026-11-01', '13:00:00', 'Strasbourg', 'Confirmé', 4, 50.00, 8, 11),
(52, '2026-11-05', '14:00:00', 'Strasbourg', '2026-11-05', '20:00:00', 'Paris', 'Prévu', 4, 50.00, 8, 11),
(53, '2025-12-17', '10:00:00', 'Nice', '2025-12-17', '15:00:00', 'Montpellier', 'Confirmé', 2, 30.00, 5, 3),
(54, '2026-11-15', '16:00:00', 'Montpellier', '2026-11-15', '21:00:00', 'Nice', 'Prévu', 2, 30.00, 5, 3),
(55, '2026-12-01', '12:00:00', 'Bordeaux', '2026-12-01', '16:00:00', 'Toulouse', 'Confirmé', 3, 29.00, 4, 1);


-- 8. Insertion initiale dans la table `participe` (5 premières participations)
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES
(2, 1), -- Bob participe au trajet 1 (Paris - Lyon)
(4, 1), -- Diana participe au trajet 1
(2, 4), -- Bob participe au trajet 4 (Bordeaux - Nantes)
(4, 2), -- Diana participe au trajet 2 (Antibes - Nice)
(2, 5); -- Bob participe au trajet 5 (Lyon - Paris)


INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES
(6, 6), -- François - Lyon -> Bordeaux
(8, 7), -- Hugo - Paris -> Rennes
(10, 9), -- Jules - Nice -> Marseille
(12, 10), -- Louis - Marseille -> Aix
(14, 11), -- Nicolas - Bordeaux -> Toulouse
(16, 13), -- Paul - Nantes -> La Rochelle
(18, 15), -- Romain - Strasbourg -> Metz
(20, 17), -- Théo - Annecy -> Genève
(22, 19), -- William - Lyon -> Dijon
(24, 21), -- Yasmine - Bordeaux -> Limoges

(2, 23), -- Bob - Rennes -> St-Malo
(4, 25), -- Diana - Toulon -> Nice
(6, 27), -- François - Nantes -> Angers
(8, 28), -- Hugo - Angers -> Nantes
(10, 29), -- Jules - Paris -> Orléans
(12, 30), -- Louis - Orléans -> Paris
(14, 31), -- Nicolas - Strasbourg -> Nancy
(16, 33), -- Paul - Paris -> Lille
(18, 35), -- Romain - Lyon -> Grenoble
(20, 37), -- Théo - Bordeaux -> La Rochelle

(22, 39), -- William - Marseille -> Nice
(24, 41), -- Yasmine - Strasbourg -> Colmar
(2, 42), -- Bob - Colmar -> Strasbourg
(4, 43), -- Diana - Nantes -> Vannes
(6, 44), -- François - Vannes -> Nantes
(8, 45), -- Hugo - Rennes -> Le Mans
(10, 46), -- Jules - Le Mans -> Rennes
(12, 49), -- Louis - Marseille -> Lyon
(14, 50), -- Nicolas - Lyon -> Marseille
(16, 51), -- Paul - Paris -> Strasbourg

(18, 52), -- Romain - Strasbourg -> Paris
(20, 53), -- Théo - Nice -> Montpellier
(22, 54), -- William - Montpellier -> Nice
(24, 55), -- Yasmine - Bordeaux -> Toulouse
(2, 6), -- Bob - Lyon -> Bordeaux
(4, 7), -- Diana - Paris -> Rennes
(6, 8), -- François - Rennes -> Paris
(8, 9), -- Hugo - Nice -> Marseille
(10, 11), -- Jules - Bordeaux -> Toulouse
(12, 13); -- Louis - Nantes -> La Rochelle


-- 9. Insertion initiale dans la table `avis` (5 premiers avis)
INSERT INTO `avis` (`avis_id`, `commentaire`, `note`, `statut`, `utilisateur_id`) VALUES
(1, 'Super trajet, Alice est très sympathique et la voiture propre.', '5', 'Publié', 2), -- Avis déposé par Bob (passager)
(2, 'Le départ était en retard de 15 minutes, dommage.', '4', 'Publié', 4), -- Avis déposé par Diana (passager)
(3, 'Cécile conduit très prudemment.', '5', 'Publié', 2), -- Avis déposé par Bob (passager)
(4, 'Trajet agréable, je recommande.', '5', 'Publié', 4), -- Avis déposé par Diana (passager)
(5, 'Avis en attente de modération.', '4', 'Modération', 1); -- Avis déposé par Alice (conducteur)


INSERT INTO `avis` (`avis_id`, `commentaire`, `note`, `statut`, `utilisateur_id`) VALUES
(6, 'Très bonne expérience, François était ponctuel.', '5', 'Publié', 6), -- François
(7, 'Voiture très confortable, Gina est une conductrice fiable.', '5', 'Publié', 7), -- Gina
(8, 'Hugo a été très bavard, trajet moins reposant que prévu.', '3', 'Publié', 8), -- Hugo
(9, 'Super trajet à Strasbourg. Ingrid connait bien la région.', '5', 'Publié', 9), -- Ingrid
(10, 'Jules est un passager agréable et discret.', '4', 'Publié', 10), -- Jules
(11, 'Karine a une belle voiture, mais elle roulait un peu vite.', '4', 'Publié', 11), -- Karine
(12, 'Louis a annulé au dernier moment, ce n''est pas sérieux.', '2', 'Publié', 12), -- Louis
(13, 'Manon est professionnelle et la course s''est bien passée.', '5', 'Publié', 13), -- Manon
(14, 'Nicolas m''a attendu 5 minutes. Très apprécié.', '5', 'Publié', 14), -- Nicolas
(15, 'Ophélie a été flexible sur le point de RDV.', '5', 'Publié', 15), -- Ophélie
(16, 'Paul est un passager très courtois.', '5', 'Publié', 16), -- Paul
(17, 'Quitterie m''a donné de bons conseils pour St-Malo.', '4', 'Publié', 17), -- Quitterie
(18, 'Romain a bien géré le trafic.', '5', 'Publié', 18), -- Romain
(19, 'Sarah est une excellente conductrice, vive l''électrique !', '5', 'Publié', 19), -- Sarah
(20, 'Théo a laissé des miettes dans la voiture. À revoir.', '3', 'Publié', 20), -- Théo
(21, 'Valérie est toujours souriante. Parfait.', '5', 'Publié', 21), -- Valérie
(22, 'William a des anecdotes très intéressantes.', '5', 'Publié', 22), -- William
(23, 'Xenia est une conductrice sûre et prudente.', '5', 'Publié', 23), -- Xenia
(24, 'Yasmine a apporté le café. Geste très gentil.', '5', 'Publié', 24), -- Yasmine
(25, 'Zachary est ponctuel, mais la voiture sentait un peu le tabac.', '3', 'Publié', 25), -- Zachary
(26, 'Rien à signaler, bon trajet.', '4', 'Publié', 2), -- Bob
(27, 'Super voyage entre Nice et Marseille !', '5', 'Publié', 4), -- Diana
(28, 'Très satisfaite de mon premier covoiturage.', '5', 'Publié', 6), -- François
(29, 'Le meilleur trajet que j''ai fait cette année.', '5', 'Publié', 8), -- Hugo
(30, 'Un peu cher, mais le service était impeccable.', '4', 'Publié', 10), -- Jules
(31, 'Conducteur très professionnel.', '5', 'Publié', 12), -- Louis
(32, 'Je reprendrai ce conducteur sans hésiter.', '5', 'Publié', 14), -- Nicolas
(33, 'Avis positif dans l''ensemble.', '4', 'Publié', 16), -- Paul
(34, 'Parfait pour un long voyage.', '5', 'Publié', 18), -- Romain
(35, 'Commentaire en attente de validation.', '4', 'Modération', 20); -- Théo