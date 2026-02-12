<?php
define('ROOT', dirname(__DIR__));
// Protection contre le Clickjacking
header('X-Frame-Options: DENY');
require(ROOT . '/app/App.php');
App::load();

if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 'home';
}

// convention de nommage : contrôleur + route

// Routeur pour charger le contrôleur en fonction de la page appelée
// US1
if ($page === 'home') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->index();
}
// US2
elseif ($page === 'legale') {
    $controller = new \NsAppEcoride\Controller\LegalesController();
    $controller->index();
} elseif ($page === 'philosophie') {
    $controller = new \NsAppEcoride\Controller\HtmlController();
    $controller->philosophie();
} elseif ($page === 'covoiturage') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->all();
} elseif ($page === 'contact') {
    $controller = new \NsAppEcoride\Controller\HtmlController();
    $controller->contact();
}
// US3 US4
// US3 US4
elseif ($page === 'trajet') {
    $controller = new \NsAppEcoride\Controller\TrajetsController();
    $controller->show();
}
// US5 - Page complète
elseif ($page === 'trajet-detail') {
    $controller = new \NsAppEcoride\Controller\TrajetsController();
    $controller->detail();
}
// US9
elseif ($page === 'trajets.nouveau') {
    $controller = new \NsAppEcoride\Controller\TrajetsController();
    $controller->nouveau();
} elseif ($page === 'trajets.sauvegarder') {
    $controller = new \NsAppEcoride\Controller\TrajetsController();
    $controller->sauvegarder();
} elseif ($page === 'trajets.edit') {
    $controller = new \NsAppEcoride\Controller\TrajetsController();
    $controller->edit();
} elseif ($page === 'trajets.update') {
    $controller = new \NsAppEcoride\Controller\TrajetsController();
    $controller->update();
} elseif ($page === 'trajets.delete') {
    $controller = new \NsAppEcoride\Controller\TrajetsController();
    $controller->delete();
} elseif ($page === 'trajets.annuler') {
    $controller = new \NsAppEcoride\Controller\TrajetsController();
    $controller->annuler();
}
// US6
elseif ($page === 'utilisateurs.login') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->login();
} elseif ($page === 'utilisateurs.index') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->index();
} elseif ($page === 'logout') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->logout();
} elseif ($page === 'covoiturages.participer') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->participer();
} elseif ($page === 'utilisateurs.recupererPassword') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->recupererPassword();
}
// US7
elseif ($page === 'utilisateurs.inscrir') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->inscrir();
} elseif ($page === 'utilisateurs.validerEmail') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->validerEmail();
} elseif ($page === 'utilisateurs.finaliserInscription') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->finaliserInscription();
}
// US8
elseif ($page === 'utilisateurs.edit' || $page === 'utilisateurs.profile.edit') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->edit();
} elseif ($page === 'utilisateurs.voitures.index') {
    $controller = new \NsAppEcoride\Controller\VoituresController();
    $controller->index();
} elseif ($page === 'utilisateurs.voitures.add') {
    $controller = new \NsAppEcoride\Controller\VoituresController();
    $controller->add();
} elseif ($page === 'utilisateurs.voitures.edit') {
    $controller = new \NsAppEcoride\Controller\VoituresController();
    $controller->edit();
} elseif ($page === 'utilisateurs.voitures.delete') {
    $controller = new \NsAppEcoride\Controller\VoituresController();
    $controller->delete();
} elseif ($page === 'utilisateurs.profile.index') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->index();
// US10
} elseif ($page === 'utilisateurs.historique') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->historique();
} elseif ($page === 'trajets.annuler') {
    $controller = new \NsAppEcoride\Controller\TrajetsController();
    $controller->annuler();
} 
// US11
elseif ($page === 'covoiturages.start') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->start();
} elseif ($page === 'covoiturages.stop') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->stop();
} elseif ($page === 'covoiturages.stop') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->stop();
} elseif ($page === 'participant.validate') {
    $controller = new \NsAppEcoride\Controller\ParticipantController();
    $controller->validate();
} elseif ($page === 'participant.submitValidation') {
    $controller = new \NsAppEcoride\Controller\ParticipantController();
    $controller->submitValidation();
// US12
} elseif ($page === 'admin.employes.login') {
    $controller = new \NsAppEcoride\Controller\Admin\EmployesController();
    $controller->login();
} elseif ($page === 'admin.employes.logout') {
    $controller = new \NsAppEcoride\Controller\Admin\EmployesController();
    $controller->logout();
} elseif ($page === 'admin.employes.dashboard') {
    $controller = new \NsAppEcoride\Controller\Admin\EmployesController();
    $controller->dashboard();
} elseif ($page === 'admin.employes.validateAvis') {
    $controller = new \NsAppEcoride\Controller\Admin\EmployesController();
    $controller->validateAvis();
} elseif ($page === 'admin.employes.refuseAvis') {
    $controller = new \NsAppEcoride\Controller\Admin\EmployesController();
    $controller->refuseAvis();
} 
// US13 - Admin Employes Management
elseif ($page === 'admin.employes.index') {
    $controller = new \NsAppEcoride\Controller\Admin\EmployesController();
    $controller->index();
} elseif ($page === 'admin.employes.add') {
    $controller = new \NsAppEcoride\Controller\Admin\EmployesController();
    $controller->add();
} elseif ($page === 'admin.employes.edit') {
    $controller = new \NsAppEcoride\Controller\Admin\EmployesController();
    $controller->edit();
} elseif ($page === 'admin.employes.delete') {
    $controller = new \NsAppEcoride\Controller\Admin\EmployesController();
    $controller->delete();
} elseif ($page === 'admin.employes.suspendre') {
    $controller = new \NsAppEcoride\Controller\Admin\EmployesController();
    $controller->suspendre();
} elseif ($page === 'admin.employes.reactiver') {
    $controller = new \NsAppEcoride\Controller\Admin\EmployesController();
    $controller->reactiver();
}
// US13 - Administrateur Dashboard & User Management
elseif ($page === 'admin.dashboard') {
    $controller = new \NsAppEcoride\Controller\Admin\AdministrateurController();
    $controller->dashboard();
} elseif ($page === 'admin.logout') {
    $controller = new \NsAppEcoride\Controller\Admin\AdministrateurController();
    $controller->logout();
} elseif ($page === 'admin.index') {
    $controller = new \NsAppEcoride\Controller\Admin\AdministrateurController();
    $controller->utilisateurs();
} elseif ($page === 'admin.suspendreUtilisateur') {
    $controller = new \NsAppEcoride\Controller\Admin\AdministrateurController();
    $controller->suspendreUtilisateur();
} elseif ($page === 'admin.reactiverUtilisateur') {
    $controller = new \NsAppEcoride\Controller\Admin\AdministrateurController();
    $controller->reactiverUtilisateur();
}

