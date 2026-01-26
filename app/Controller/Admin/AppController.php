<?php

namespace NsAppEcoride\Controller\Admin;

use \App;
use NsCoreEcoride\Auth\DbAuth;

class AppController extends \NsAppEcoride\Controller\AppController
{
    protected $template = 'admin';

    public function __construct()
    {
        parent::__construct();

        $app = App::getInstance();

        $auth = new DbAuth($app->getDb());

        // Check if connected as Employee (since this is the Admin/Employee area)
        if (!$auth->isEmploye() && isset($_GET['p']) && $_GET['p'] !== 'admin.employe.login') {
            $this->forbidden();
        }
    }
}
