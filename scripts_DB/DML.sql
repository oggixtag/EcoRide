INSERT INTO `role` (`role_id`, `libelle`) VALUES
(1, 'Administrateur'),
(2, 'Conducteur'),
(3, 'Passager'),
(4, 'Visiteur');


-- Insertion dans la table `configuration` (point d'ancrage)
INSERT INTO `configuration` (`id_configuration`) VALUES
(1);

-- Insertion dans la table `parametre` (lié à id_configuration = 1)
INSERT INTO `parametre` (`parametre_id`, `propriete`, `valeur`, `id_configuration`) VALUES
(1, 'Titre_Site', 'EcoRide - Covoiturage Écolo', 1),
(2, 'Devise', 'EUR', 1),
(3, 'Taux_Commission', '0.05', 1),
(4, 'Support_Email', 'support@ecoride.fr', 1),
(5, 'Version_App', '1.0.0', 1);

INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `date_naissance`, `photo`, `pseudo`, `role_id`) VALUES
(1, 'Dupont', 'Alice', 'alice.dupont@mail.com', 'password123', '0612345678', '10 Rue de Paris, 75001 Paris', '1990-05-15', NULL, 'AliceCovoit', 2), -- Conducteur
(2, 'Bernard', 'Bob', 'bob.bernard@mail.com', 'password123', '0798765432', '25 Avenue de Lyon, 69002 Lyon', '1985-11-20', NULL, 'BobTheRider', 3),   -- Passager
(3, 'Charles', 'Cécile', 'cecile.charles@mail.com', 'password123', '0660606060', '5 Bd des Plages, 06600 Antibes', '1995-03-01', NULL, 'Cece06', 2),        -- Conducteur
(4, 'David', 'Diana', 'diana.david@mail.com', 'password123', '0711223344', '8 Rue de Marseille, 13008 Marseille', '2000-08-22', NULL, 'DianaTravel', 3), -- Passager
(5, 'Emmanuel', 'Eva', 'eva.emmanuel@mail.com', 'password123', '0655443322', '3 Place de Bordeaux, 33000 Bordeaux', '1978-01-10', NULL, 'EcoEva', 2);      -- Conducteur

INSERT INTO `marque` (`marque_id`, `libelle`) VALUES
(1, 'Renault'),
(2, 'Peugeot'),
(3, 'Toyota'),
(4, 'Tesla'),
(5, 'Volkswagen');

INSERT INTO `voiture` (`voiture_id`, `modele`, `immatriculation`, `energie`, `couleur`, `date_premiere_immatriculation`, `marque_id`) VALUES
(1, 'Clio V', 'AA-123-BB', 'Diesel', 'Rouge', '2020-01-20', 1),   -- Renault
(2, '208', 'CC-456-DD', 'Essence', 'Bleu', '2019-06-10', 2),      -- Peugeot
(3, 'Yaris Hybride', 'EE-789-FF', 'Hybride', 'Blanc', '2021-03-05', 3), -- Toyota
(4, 'Model 3', 'GG-012-HH', 'Électrique', 'Noir', '2022-10-15', 4), -- Tesla
(5, 'Golf VII', 'II-345-JJ', 'Essence', 'Gris', '2018-02-28', 5);  -- Volkswagen

 

INSERT INTO `covoiturage` (`covoiturage_id`, `date_depart`, `heure_depart`, `lieu_depart`, `date_arrivee`, `heure_arrivee`, `lieu_arrivee`, `statut`, `nb_place`, `prix_personne`, `voiture_id`, `utilisateur_id`) VALUES
(1, '2025-12-15', '08:00:00', 'Paris', '2025-12-15', '12:30:00', 'Lyon', 'Confirmé', 3, 25.50, 1, 1),
(2, '2025-12-10', '14:30:00', 'Antibes', '2025-12-10', '15:15:00', 'Nice', 'Confirmé', 2, 5.00, 3, 3),
(3, '2025-12-20', '10:00:00', 'Marseille', '2025-12-20', '11:30:00', 'Toulon', 'Annulé', 4, 12.00, 4, 5),
(4, '2025-12-25', '16:00:00', 'Bordeaux', '2025-12-25', '21:00:00', 'Nantes', 'Confirmé', 3, 35.00, 1, 1),
(5, '2026-01-05', '07:00:00', 'Lyon', '2026-01-05', '11:30:00', 'Paris', 'Prévu', 4, 28.00, 3, 3);

INSERT INTO `participe` (`utilisateur_id`, `covoiturage_id`) VALUES
(2, 1), -- Bob participe au trajet 1 (Paris - Lyon)
(4, 1), -- Diana participe au trajet 1
(2, 4), -- Bob participe au trajet 4 (Bordeaux - Nantes)
(4, 2), -- Diana participe au trajet 2 (Antibes - Nice)
(2, 5); -- Bob participe au trajet 5 (Lyon - Paris)

INSERT INTO `avis` (`avis_id`, `commentaire`, `note`, `statut`, `utilisateur_id`) VALUES
(1, 'Super trajet, Alice est très sympathique et la voiture propre.', '5', 'Publié', 2), -- Avis déposé par Bob (utilisateur_id=2)
(2, 'Le départ était en retard de 15 minutes, dommage.', '4', 'Publié', 4), -- Avis déposé par Diana (utilisateur_id=4)
(3, 'Cécile conduit très prudemment.', '5', 'Publié', 2), -- Avis déposé par Bob (utilisateur_id=2)
(4, 'Trajet agréable, je recommande.', '5', 'Publié', 4), -- Avis déposé par Diana (utilisateur_id=4)
(5, 'Avis en attente de modération.', '4', 'Modération', 1); -- Avis déposé par Alice (utilisateur_id=1)


