<?php

namespace Controllers\LoginBundle;

use Controllers\LoginBundle\forms\LoginFormType;
use Core\Abstracts\Controller;
use Core\Request;
use Model\Admin\Admin;

class LoginController extends Controller
{
    public function loginAction(Request $request)
    {
        $admin = new Admin();
        $form = $this->createForm(LoginFormType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dump(Admin::getOneBy(['admin_id' => 1]));die;
        }

        return $this->render('Login/login.php', [
            'form' => $form->createView()
        ]);
    }
}