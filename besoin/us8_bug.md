**Dans index**

1.Changer la route, de ‘p=utilisateurs.index’ à ‘p=utilisateurs.profile.index’

2.Afficher ‘Mon Rôle’ dans la section ‘Mes Informations Personnelles’

3.Concernant le bouton “Gérer” il faut revoir l’implémentation car il y a cette erreur “Fatal error: Declaration of NsAppEcoride\Model\VoitureModel::delete($id, $utilisateur_id) must be compatible with NsCoreEcoride\Model\Model::delete($id) in C:\xampp\htdocs\EcoRide\app\Model\VoitureModel.php on line 88”, confer ‘EcoRide\besoin\us8.md’ -> section Implémentation, point 5

4.affichage à revoir, c’est trop étalé

**Dans edit**

1.Changer la route, de ‘p=utilisateurs.edit’ à ‘p=utilisateurs.profile.edit’

2.ajouter la case à cocher “Passager-Chauffeur”

3.si l’utilisateur selectionne “Chauffeur”, le systeme doit lui demander de saisir les informationes: confer ‘EcoRide\besoin\us8.md’ -> section Implémentation, point 4

4.affichage à revoir, c’est trop étalé 


