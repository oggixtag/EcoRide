            
---------------------------------------------------------
-- vue journey  
---------------------------------------------------------
use ecoride_db;
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

---------------------------------------------------------
-- vue journey : montre les departs
---------------------------------------------------------
use ecoride_db;
select c.lieu_depart, COUNT(c.lieu_depart) cnt_ld
from covoiturage c
group by c.lieu_depart
having cnt_ld>1
order by c.lieu_depart;

---------------------------------------------------------
-- vue journey : montre les departs et les arrivées
---------------------------------------------------------			
use ecoride_db;
select c.lieu_depart, COUNT(c.lieu_depart) cnt_ld, c.lieu_arrivee, COUNT(c.lieu_arrivee)cnt_la
from covoiturage c
group by c.lieu_depart,c.lieu_arrivee
order by c.lieu_depart;