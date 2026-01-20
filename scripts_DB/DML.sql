-- Fichier DML (Data Manipulation Language) étendu pour la base de données EcoRide.
-- Contient les enregistrements initiaux.



-- Nettoyage 
DELETE FROM `avis`;
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
ALTER TABLE `avis` AUTO_INCREMENT = 1;
ALTER TABLE `statut_covoiturage` AUTO_INCREMENT = 1;
ALTER TABLE `statut_avis` AUTO_INCREMENT = 1;
ALTER TABLE `statut_mail` AUTO_INCREMENT = 1;
ALTER TABLE `note` AUTO_INCREMENT = 1;
ALTER TABLE `departement` AUTO_INCREMENT = 1;
ALTER TABLE `poste` AUTO_INCREMENT = 1;
ALTER TABLE `employe` AUTO_INCREMENT = 1;

-- 1. Insertion dans la table `role`
INSERT INTO `role` (`libelle`) VALUES
('Administrateur'),
('Conducteur'),
('Passager');

-- 2. Insertion dans la table `configuration`

-- 3. Insertion dans la table `parametre`

-- 4. Insertion initiale dans la table `utilisateur` 
INSERT INTO `utilisateur` (`nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `photo`, `pseudo`, `role_id`,`credit`) VALUES
('Dupont', 'Alice', 'alice.dupont@mail.com', 'password123', '0612345678', '10 Rue de Paris, 75001 Paris', '1990-05-15', NULL, 'AliceCovoit', 2,20), -- Conducteur
('Bernard', 'Bob', 'bob.bernard@mail.com', 'password123', '0798765432', '25 Avenue de Lyon, 69002 Lyon', '1985-11-20', NULL, 'BobTheRider', 3,20),   -- Passager
('Charles', 'Cécile', 'cecile.charles@mail.com', 'password123', '0660606060', '5 Bd des Plages, 06600 Antibes', '1995-03-01', NULL, 'Cece06', 2,20),        -- Conducteur
('David', 'Diana', 'diana.david@mail.com', 'password123', '0711223344', '8 Rue de Marseille, 13008 Marseille', '2000-08-22', NULL, 'DianaTravel', 3,20), -- Passager
('Emmanuel', 'Eva', 'eva.emmanuel@mail.com', 'password123', '0655443322', '3 Place de Bordeaux, 33000 Bordeaux', '1978-01-10', NULL, 'EcoEva', 2,20),      -- Conducteur
('Fournier', 'François', 'francois.fournier@mail.com', 'pass456', '0699887766', '1 Rue de Lille, 59000 Lille', '1992-07-25', NULL, 'F-Ride', 3,20),
('Garnier', 'Gina', 'gina.garnier@mail.com', 'pass789', '0710203040', '12 Av. des Fleurs, 44000 Nantes', '1988-02-14', NULL, 'GinaG', 2,20),
('Henry', 'Hugo', 'hugo.henry@mail.com', 'pass101', '0633445566', '33 Pl. de Bretagne, 35000 Rennes', '1997-12-05', NULL, 'Huguito', 3,20),
('Ibanez', 'Ingrid', 'ingrid.ibanez@mail.com', 'pass202', '0755667788', '45 Rue de l''Est, 67000 Strasbourg', '1980-06-30', NULL, 'StrasRide', 2,20),
('Joly', 'Jules', 'jules.joly@mail.com', 'pass303', '0677889900', '19 Bd du Midi, 83000 Toulon', '2002-04-18', NULL, 'JulesJ', 3,20),
('Klein', 'Karine', 'karine.klein@mail.com', 'pass404', '0700112233', '2 Rue du Lac, 74000 Annecy', '1993-09-01', NULL, 'Kiki74', 2,20),
('Lambert', 'Louis', 'louis.lambert@mail.com', 'pass505', '0622334455', '8 Av. du Nord, 59000 Lille', '1987-11-29', NULL, 'LuluCar', 3,20),
('Martin', 'Manon', 'manon.martin@mail.com', 'pass606', '0744556677', '14 Rue de l''Opéra, 69001 Lyon', '1999-01-20', NULL, 'ManonM', 2,20),
('Noël', 'Nicolas', 'nicolas.noel@mail.com', 'pass707', '0688990011', '7 Av. du Sud, 13008 Marseille', '1975-03-12', NULL, 'NicoTravel', 3,20),
('Olivier', 'Ophélie', 'ophelie.olivier@mail.com', 'pass808', '0799001122', '22 Rue des Vignes, 33000 Bordeaux', '1991-10-08', NULL, 'OphélieO', 2,20),
('Petit', 'Paul', 'paul.petit@mail.com', 'pass909', '0611223344', '3 Pl. de la Liberté, 44000 Nantes', '1984-05-23', NULL, 'Polo44', 3,20),
('Quentin', 'Quitterie', 'quitterie.quentin@mail.com', 'pass110', '0733445566', '5 Rue du Château, 35000 Rennes', '1996-02-17', NULL, 'QuittR', 2,20),
('Robert', 'Romain', 'romain.robert@mail.com', 'pass111', '0655667788', '17 Av. de l''Europe, 67000 Strasbourg', '1982-08-03', NULL, 'RoroStr', 3,20),
('Simon', 'Sarah', 'sarah.simon@mail.com', 'pass112', '0777889900', '9 Bd des Palmiers, 83000 Toulon', '1994-11-11', NULL, 'SarahS', 2,20),
('Thomas', 'Théo', 'theo.thomas@mail.com', 'pass113', '0699001122', '24 Rue du Rhône, 69002 Lyon', '2001-01-01', NULL, 'Theo69', 3,20),
('Vincent', 'Valérie', 'valerie.vincent@mail.com', 'pass114', '0710111213', '1 Bd de la Loire, 44000 Nantes', '1989-07-07', NULL, 'ValV', 2,20),
('Weber', 'William', 'william.weber@mail.com', 'pass115', '0620212223', '30 Rue des Arts, 33000 Bordeaux', '1998-10-19', NULL, 'WillBdx', 3,20),
('Xavier', 'Xenia', 'xenia.xavier@mail.com', 'pass116', '0740414243', '15 Rue des Etoiles, 75001 Paris', '1976-04-28', NULL, 'XeniaX', 2,20),
('Yann', 'Yasmine', 'yasmine.yann@mail.com', 'pass117', '0660616263', '21 Av. du Soleil, 13008 Marseille', '1990-12-01', NULL, 'YasmineY', 3,20),
('Zimmer', 'Zachary', 'zachary.zimmer@mail.com', 'pass118', '0780818283', '4 Rue de la Paix, 67000 Strasbourg', '1983-03-09', NULL, 'ZackZ', 2,20);


-- 5. Insertion initiale dans la table `marque`
INSERT INTO `marque` (`libelle`) VALUES
('Renault'),
('Peugeot'),
('Toyota'),
('Tesla'),
('Volkswagen'),
('Citroën'),
('BMW'),
('Mercedes');

-- 5.a. Insertion initiale dans la table `statut_covoiturage`
INSERT INTO `statut_covoiturage` (`libelle`) VALUES
('annulé'),
('prévu'),
('confirmé');

-- 5.b. Insertion initiale dans la table `statut_avis`
INSERT INTO `statut_avis` (`libelle`) VALUES
('publié'),
('modération');

-- 5.c. Insertion initiale dans la table `statut_mail`
INSERT INTO `statut_mail` (`libelle`) VALUES
('non_confirmmé'),
('confirmmé');

-- 5.c. Insertion initiale dans la table `note`
INSERT INTO `note` (`libelle`) VALUES
('Très insatisfait(e)'),
('Plutôt mécontent(e)'),
('Ni insatisfait(e), ni mécontent(e) '),
('Plutôt content'),
('Très content');

-- 6. Insertion initiale dans la table `voiture` 
INSERT INTO `voiture` (`modele`, `immatriculation`, `energie`, `couleur`, `date_premiere_immatriculation`, `marque_id`,`utilisateur_id`) VALUES
('Clio V', 'AA-123-BB', 'Diesel', 'Rouge', '2020-01-20', 1, 1),   -- Renault (Alice, ID 1)
('208', 'CC-456-DD', 'Essence', 'Bleu', '2019-06-10', 2, 3),      -- Peugeot (Cécile, ID 3)
('Yaris Hybride', 'EE-789-FF', 'Hybride', 'Blanc', '2021-03-05', 3, 5), -- Toyota (Eva, ID 5)
('Model 3', 'GG-012-HH', 'Électrique', 'Noir', '2022-10-15', 4, 1), -- Tesla (Alice, ID 1)
('Golf VII', 'II-345-JJ', 'Essence', 'Gris', '2018-02-28', 5, 7),  -- Volkswagen (Cécile, ID 3)
('C4 Cactus', 'KK-678-LL', 'Diesel', 'Vert', '2017-08-10', 6, 9), -- Citroën (Gina, ID 7)
('Série 1', 'MM-901-NN', 'Essence', 'Bleu Marine', '2020-04-25', 7, 11), -- BMW (Ingrid, ID 9)
('Classe C', 'OO-234-PP', 'Hybride', 'Noir Métal', '2022-01-01', 8, 13), -- Mercedes (Karine, ID 11)
('Captur', 'QQ-567-RR', 'Essence', 'Orange', '2023-09-19', 1, 15), -- Renault (Manon, ID 13)
('308 SW', 'SS-890-TT', 'Diesel', 'Gris', '2019-11-30', 2, 17),  -- Peugeot (Ophélie, ID 15)
('CHR', 'UU-123-VV', 'Hybride', 'Rouge', '2021-06-05', 3, 19),  -- Toyota (Quitterie, ID 17)
('Model Y', 'WW-456-XX', 'Électrique', 'Blanc', '2023-01-15', 4, 21), -- Tesla (Sarah, ID 19)
('Polo', 'YY-789-ZZ', 'Essence', 'Jaune', '2016-03-17', 5, 23),  -- Volkswagen (Valérie, ID 21)
('C3 Aircross', 'AB-012-CD', 'Diesel', 'Gris Clair', '2020-10-10', 6, 3), -- Citroën (Xenia, ID 23)
('Série 3', 'EF-345-GH', 'Essence', 'Blanc', '2021-05-01', 7, 1);  -- BMW (Zachary, ID 25)


-- 7. Insertion initiale dans la table `covoiturage` 
INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) VALUES
(1, '2025-12-15', '08:00:00', 'Paris', '2025-12-15', '12:30:00', 'Lyon', 3, 3, 25.50, 1),
(2, '2025-12-10', '14:30:00', 'Antibes', '2025-12-10', '15:15:00', 'Nice', 3, 2, 5.00, 3),
(3, '2025-12-20', '10:00:00', 'Marseille', '2025-12-20', '11:30:00', 'Toulon', 1, 4, 12.00, 4),
(4, '2025-12-25', '16:00:00', 'Bordeaux', '2025-12-25', '21:00:00', 'Nantes', 3, 3, 35.00, 1),
(5, '2026-01-05', '07:00:00', 'Lyon', '2026-01-05', '11:30:00', 'Paris', 2, 4, 28.00, 3);

INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut_covoiturage_id`, `nb_place`, `prix_personne`, `voiture_id`) VALUES
-- Trajets Alice (ID 1, voitures 1, 4)
(6, '2026-01-10', '15:00:00', 'Lyon', '2026-01-10', '19:30:00', 'Bordeaux', 2, 3, 30.00, 4),
(7, '2026-01-15', '06:30:00', 'Paris', '2026-01-15', '10:00:00', 'Rennes', 3, 2, 40.00, 1),
(8, '2026-01-20', '11:00:00', 'Rennes', '2026-01-20', '14:30:00', 'Paris', 2, 3, 40.00, 1),
-- Trajets Cécile (ID 3, voitures 2, 5)
(9, '2026-02-01', '09:00:00', 'Nice', '2026-02-01', '13:00:00', 'Marseille', 3, 0, 22.00, 4),
(10, '2026-02-05', '17:30:00', 'Marseille', '2026-02-05', '18:30:00', 'Aix-en-Provence', 3, 3, 6.50, 2),
-- Trajets Eva (ID 5, voiture 3)
(11, '2026-02-10', '08:45:00', 'Bordeaux', '2026-02-10', '13:00:00', 'Toulouse', 2, 4, 29.00, 3),
(12, '2026-02-15', '14:00:00', 'Toulouse', '2026-02-15', '18:15:00', 'Bordeaux', 1, 3, 29.00, 3),
-- Trajets Gina (ID 7, voiture 6)
(13, '2026-03-01', '10:00:00', 'Nantes', '2026-03-01', '13:30:00', 'La Rochelle', 3, 4, 18.00, 6),
(14, '2026-03-05', '12:00:00', 'La Rochelle', '2026-03-05', '15:30:00', 'Nantes', 2, 4, 18.00, 6),
-- Trajets Ingrid (ID 9, voiture 7)
(15, '2026-03-10', '07:00:00', 'Strasbourg', '2026-03-10', '11:00:00', 'Metz', 3, 3, 20.50, 7),
(16, '2026-03-15', '16:00:00', 'Metz', '2026-03-15', '20:00:00', 'Strasbourg', 3, 3, 20.50, 7),
-- Trajets Karine (ID 11, voiture 8)
(17, '2026-04-01', '13:00:00', 'Annecy', '2026-04-01', '15:30:00', 'Genève', 3, 2, 15.00, 8),
(18, '2026-04-05', '09:30:00', 'Genève', '2026-04-05', '12:00:00', 'Annecy', 2, 2, 15.00, 8),
-- Trajets Manon (ID 13, voiture 9)
(19, '2026-04-10', '18:00:00', 'Lyon', '2026-04-10', '22:00:00', 'Dijon', 3, 4, 26.00, 9),
(20, '2026-04-15', '07:45:00', 'Dijon', '2026-04-15', '11:45:00', 'Lyon', 2, 4, 26.00, 9),
-- Trajets Ophélie (ID 15, voiture 10)
(21, '2026-05-01', '09:15:00', 'Bordeaux', '2026-05-01', '14:30:00', 'Limoges', 3, 3, 21.50, 10),
(22, '2026-05-05', '15:00:00', 'Limoges', '2026-05-05', '20:15:00', 'Bordeaux', 2, 3, 21.50, 10),
-- Trajets Quitterie (ID 17, voiture 11)
(23, '2026-05-10', '11:00:00', 'Rennes', '2026-05-10', '14:00:00', 'St-Malo', 3, 4, 10.00, 11),
(24, '2026-05-15', '16:00:00', 'St-Malo', '2026-05-15', '19:00:00', 'Rennes', 1, 4, 10.00, 11),
-- Trajets Sarah (ID 19, voiture 12)
(25, '2026-06-01', '06:00:00', 'Toulon', '2026-06-01', '10:00:00', 'Nice', 3, 3, 19.50, 12),
(26, '2026-06-05', '13:00:00', 'Nice', '2026-06-05', '17:00:00', 'Toulon', 2, 3, 19.50, 12),
-- Trajets Valérie (ID 21, voiture 13)
(27, '2026-06-10', '09:00:00', 'Nantes', '2026-06-10', '13:00:00', 'Angers', 3, 4, 12.50, 13),
(28, '2026-06-15', '14:30:00', 'Angers', '2026-06-15', '18:30:00', 'Nantes', 3, 4, 12.50, 13),
-- Trajets Xenia (ID 23, voiture 14)
(29, '2026-07-01', '11:00:00', 'Paris', '2026-07-01', '15:00:00', 'Orléans', 2, 3, 17.00, 14),
(30, '2026-07-05', '16:00:00', 'Orléans', '2026-07-05', '20:00:00', 'Paris', 3, 3, 17.00, 14),
-- Trajets Zachary (ID 25, voiture 15)
(31, '2026-07-10', '08:30:00', 'Strasbourg', '2026-07-10', '12:30:00', 'Nancy', 3, 2, 24.50, 15),
(32, '2026-07-15', '13:00:00', 'Nancy', '2026-07-15', '17:00:00', 'Strasbourg', 2, 2, 24.50, 15),
-- Trajets supplémentaires
(33, '2026-08-01', '10:00:00', 'Paris', '2026-08-01', '16:00:00', 'Lille', 3, 4, 28.50, 9),
(34, '2026-08-05', '14:00:00', 'Lille', '2026-08-05', '20:00:00', 'Paris', 2, 4, 28.50, 9),
(35, '2026-08-10', '07:30:00', 'Lyon', '2026-08-10', '10:30:00', 'Grenoble', 3, 3, 15.00, 7),
(36, '2026-08-15', '17:00:00', 'Grenoble', '2026-08-15', '20:00:00', 'Lyon', 2, 3, 15.00, 7),
(37, '2026-08-20', '13:30:00', 'Bordeaux', '2026-08-20', '17:00:00', 'La Rochelle', 3, 2, 19.00, 10),
(38, '2026-08-25', '11:00:00', 'La Rochelle', '2026-08-25', '14:30:00', 'Bordeaux', 2, 2, 19.00, 10),
(39, '2026-09-01', '05:00:00', 'Marseille', '2026-09-01', '11:00:00', 'Nice', 3, 4, 32.00, 1),
(40, '2026-09-05', '18:00:00', 'Nice', '2026-09-05', '20:30:00', 'Cannes', 2, 3, 8.00, 3),
(41, '2026-09-10', '09:00:00', 'Strasbourg', '2026-09-10', '13:00:00', 'Colmar', 3, 4, 11.50, 15),
(42, '2026-09-15', '14:00:00', 'Colmar', '2026-09-15', '18:00:00', 'Strasbourg', 3, 4, 11.50, 15);


-- 8. Insertion initiale dans la table `participe` (5 premières participations)
INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES
(2, 1), -- Bob participe au trajet 1 (Paris - Lyon)
(4, 1), -- Diana participe au trajet 1
(2, 4), -- Bob participe au trajet 4 (Bordeaux - Nantes)
(4, 2), -- Diana participe au trajet 2 (Antibes - Nice)
(2, 5), -- Bob participe au trajet 5 (Lyon - Paris)
(6, 6), -- François - Lyon -> Bordeaux
(8, 7), -- Hugo - Paris -> Rennes
(1, 9), -- Jules - Nice -> Marseille
(1, 10), -- Louis - Marseille -> Aix
(1, 11), -- Nicolas - Bordeaux -> Toulouse
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
(2, 6), -- Bob - Lyon -> Bordeaux
(4, 7), -- Diana - Paris -> Rennes
(6, 8), -- François - Rennes -> Paris
(8, 9), -- Hugo - Nice -> Marseille
(10, 11), -- Jules - Bordeaux -> Toulouse
(12, 13); -- Louis - Nantes -> La Rochelle


-- 9. Insertion initiale dans la table `avis` (5 premiers avis)
INSERT INTO `avis` (`commentaire`, `note_id`, `statut_avis_id`, `utilisateur_id`) VALUES
('Super trajet, Alice est très sympathique et la voiture propre.', 5, 1, 2), -- Avis déposé par Bob (passager)
('Le départ était en retard de 15 minutes, dommage.', 4, 1, 4), -- Avis déposé par Diana (passager)
('Cécile conduit très prudemment.', 5, 1, 2), -- Avis déposé par Bob (passager)
('Trajet agréable, je recommande.', 5, 1, 4), -- Avis déposé par Diana (passager)
('Voyage très agréable, passagers au top !', 4, 1, 1), -- Avis déposé par Alice (conducteur)
('Très bonne expérience, François était ponctuel.', 5, 1, 6), -- François
('Voiture très confortable, Gina est une conductrice fiable.', 5, 1, 7), -- Gina
('Hugo a été très bavard, trajet moins reposant que prévu.', 3, 1, 8), -- Hugo
('Super trajet à Strasbourg. Ingrid connait bien la région.', 5, 1, 9), -- Ingrid
('Jules est un passager agréable et discret.', 4, 1, 10), -- Jules
('Karine a une belle voiture, mais elle roulait un peu vite.', 4, 1, 11), -- Karine
('Louis a annulé au dernier moment, ce n''est pas sérieux.', 2, 1, 12), -- Louis
('Manon est professionnelle et la course s''est bien passée.', 5, 1, 13), -- Manon
('Nicolas m''a attendu 5 minutes. Très apprécié.', 5, 1, 14), -- Nicolas
('Ophélie a été flexible sur le point de RDV.', 5, 1, 15), -- Ophélie
('Paul est un passager très courtois.', 5, 1, 16), -- Paul
('Quitterie m''a donné de bons conseils pour St-Malo.', 4, 1, 17), -- Quitterie
('Romain a bien géré le trafic.', 5, 1, 18), -- Romain
('Sarah est une excellente conductrice, vive l''électrique !', 5, 1, 19), -- Sarah
('Théo a laissé des miettes dans la voiture. À revoir.', 3, 1, 20), -- Théo
('Valérie est toujours souriante. Parfait.', 5, 1, 21), -- Valérie
('William a des anecdotes très intéressantes.', 5, 1, 22), -- William
('Xenia est une conductrice sûre et prudente.', 5, 1, 23), -- Xenia
('Yasmine a apporté le café. Geste très gentil.', 5, 1, 24), -- Yasmine
('Zachary est ponctuel, mais la voiture sentait un peu le tabac.', 3, 1, 25), -- Zachary
('Rien à signaler, bon trajet.', 4, 1, 2), -- Bob
('Super voyage entre Nice et Marseille !', 5, 1, 4), -- Diana
('Très satisfaite de mon premier covoiturage.', 5, 1, 6), -- François
('Le meilleur trajet que j''ai fait cette année.', 5, 1, 8), -- Hugo
('Un peu cher, mais le service était impeccable.', 4, 1, 10), -- Jules
('Conducteur très professionnel.', 5, 1, 12), -- Louis
('Je reprendrai ce conducteur sans hésiter.', 5, 1, 14), -- Nicolas
('Avis positif dans l''ensemble.', 4, 1, 16), -- Paul
('Parfait pour un long voyage.', 5, 1, 18), -- Romain
('Commentaire en attente de validation.', 4, 2, 20); -- Théo


-- -----------------------------------------------------
-- PARTIE ADMINISTRATEUR
-- -----------------------------------------------------

INSERT INTO DEPARTEMENT (nom_dept, lieu) VALUES
('Ressources Humaines', 'Paris'),
('Informatique',        'Lyon');

INSERT INTO POSTE (intitule, salaire_min, salaire_max) VALUES
('Admiinstrateur', 60000, 70000),
('Employe', 50000, 60000);

INSERT INTO EMPLOYE (nom, prenom, email, date_embauche, salaire, id_poste, id_dept, id_manager) VALUES
('ndr', 'ndr', 'ndr@mail.com', '2019-09-01',65000, 1, 2, NULL), 	-- manager
('Durand', 'Sophie', 'sophie.durand@mail.com', '2021-03-10', 42000, 2, 2, 1); -- Employe rattachés au manager

