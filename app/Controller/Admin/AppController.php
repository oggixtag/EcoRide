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

        if (!$auth->logged()) {
            $this->forbidden();
        }
    }
}
