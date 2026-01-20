
---------------------------------------------------------
-- US2
---------------------------------------------------------

-- vue trajet  
select 
	c.covoiturage_id ,
	c.date_depart	 ,
	c.heure_depart	 ,
	c.lieu_depart	 ,
	c.date_arrivee	 ,
	c.heure_arrivee	 ,
	c.lieu_arrivee	 ,
	c.statut		 ,
	c.nb_place		 ,
	c.prix_personne	 ,
	u.pseudo		 ,
	u.photo			 ,
	v.energie		 , 
	a.note
from covoiturage c
join utilisateur u on (u.utilisateur_id = c.utilisateur_id)
join voiture v on (v.voiture_id = c.voiture_id)
left join avis a on (a.utilisateur_id = u.utilisateur_id)
where c.lieu_depart = 'antibes';

-- vue trajet : montre les departs
select c.lieu_depart, COUNT(c.lieu_depart) cnt_ld
from covoiturage c
group by c.lieu_depart
having cnt_ld>1
order by c.lieu_depart;

-- vue trajet : montre les departs et les arrivées
select c.lieu_depart, COUNT(c.lieu_depart) cnt_ld, c.lieu_arrivee, COUNT(c.lieu_arrivee)cnt_la
from covoiturage c
group by c.lieu_depart,c.lieu_arrivee
order by c.lieu_depart;


---------------------------------------------------------
-- role
---------------------------------------------------------

-- Récupération du profil complet + role
SELECT u.*, r.libelle
FROM utilisateur u 
JOIN role r ON u.role_id = r.role_id 
WHERE u.utilisateur_id = 1 ;

-- Récupération du role
SELECT r.libelle 
FROM utilisateur u 
JOIN role r ON u.role_id = r.role_id 
WHERE u.utilisateur_id = 1 ;


-- Recherche de trajets disponibles selon une destination et une date
SELECT c.*, v.modele, m.libelle AS marque
FROM covoiturage c
JOIN voiture v ON c.voiture_id = v.voiture_id
JOIN marque m ON v.marque_id = m.marque_id
WHERE c.lieu_arrivee = 'Lyon' AND c.date_depart = '2026-02-15' AND c.statut = 'Ouvert';

-- Extraction des véhicules associés à un utilisateur
SELECT DISTINCT 
    v.voiture_id,
    m.libelle AS marque,
    v.modele,
    v.immatriculation,
    v.energie,
    v.couleur
FROM voiture v
-- Jointure pour obtenir le nom de la marque
JOIN marque m ON v.marque_id = m.marque_id
-- Jointure avec covoiturage pour faire le lien avec le conducteur
JOIN covoiturage c ON v.voiture_id = c.voiture_id
-- Filtrage sur l'ID de l'utilisateur qui gère le trajet
WHERE c.utilisateur_id = :utilisateur_id;

-- Lister les avis pour un utilisateur donné
SELECT 
    a.avis_id,
    a.note,
    a.commentaire,
    a.statut
FROM avis a
WHERE a.utilisateur_id = :utilisateur_id;

-- Lister les avis déposés par un utilisateur
SELECT 
    a.avis_id,
    a.note,
    a.commentaire,
    a.statut,
    u.pseudo 
FROM avis a
-- Jointure pour confirmer l'identité de l'auteur
JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
WHERE a.utilisateur_id = :utilisateur_id;

---------------------------------------------------------
-- US admin
---------------------------------------------------------
-- Liste des employés avec leur poste et leur manager
SELECT e.nom, e.prenom, p.intitule, m.nom AS manager_nom
FROM EMPLOYE e
JOIN POSTE p ON e.id_poste = p.id_poste
LEFT JOIN EMPLOYE m ON e.id_manager = m.id_emp;
 