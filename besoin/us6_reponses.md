#### Point 4 - Crédit utilisateur :

  * La colonne `credit_restant` ce n’est pas un champ mais ça doit être une variable php. La colonne qui faut utiliser s’appelle `credit`, table `utilisateur`.
  * Il faut utiliser la table `utilisateur`. Ce que tu vas enregistrer c’est une participation de l’utilisateur à un trajet donné, et non un nouveau trajet.
  * Pour le format exact de la structure c’est la table `utilisateur` qui faut utiliser. Tu peux te baser sur le fichier `Ecoride/scripts_DB/DDL.sql`. Tu peux aussi vérifier et lire le contenu du répertoire `Ecordie/scripts_DB`.

#### Point 4 - Message de redirection :

  * Non, pas de message.
  * Il s’agit d' un remplacement dynamique car l’utilisateur non connecté suit ce chemin: accueil \> recherche \> liste de trajets \> details du trajet. Du coup l'indication prendra la place temporaire de connexion. Dans les autres cas on laisse ‘Connexion’.

#### Point 5 - Vérification crédit :

  * Le crédit doit-il être vérifié côté backend (contrôleur)?
  * `session_start();` est créé dans la fonction `load` de la classe `EcoRide\app\App`.
  * `$_SESSION['auth']` dans la fonction `login` de la classe `EcoRide\core\Auth\DbAuth`.
  * `$_SESSION['search_criteria']` dans la fontion `show` de la classe `EcoRide\app\Controller\CovoituragesController`.

#### Point 6 - Récupération mot de passe :

  * Pour l'instant, on affiche uniquement le formulaire de réinitialisation. Pour cela tu peux utiliser le même box de la page login.
  * Répondu au point précédent.
