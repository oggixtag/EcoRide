
-- psudo: AliceCovoit
update utilisateur set credit=4 where utilisateur_id=1; 

-- reservations 
select u.pseudo,c.*
from utilisateur u
join covoiturage c on (c.utilisateur_id=u.utilisateur_id)
where u.utilisateur_id=1;

select u.utilisateur_id,u.pseudo,
	c.*
from utilisateur u
join covoiturage c on (c.utilisateur_id=u.utilisateur_id)
where c.lieu_depart='Nice';

DELETE FROM participe WHERE `participe`.`utilisateur_id` = 1 ; 

-- avis
select u.pseudo,a.* from avis a JOIN utilisateur u on a.utilisateur_id=u.utilisateur_id where u.utilisateur_id=1; 


