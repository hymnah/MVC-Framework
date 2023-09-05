<?php

namespace Controllers\IndexBundle;

use Core\Abstracts\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->render('Default/index.php', [
            'pageTitle' => 'Welcome'
        ]);
    }
}