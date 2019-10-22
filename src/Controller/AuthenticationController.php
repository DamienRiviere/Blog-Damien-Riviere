<?php

namespace App\Controller;

use App\Services\Authentication;

class AuthenticationController extends Controller {

    private $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = new Authentication();
    }

    public function showLogin($errors = null)
    {
        $this->twig->display('authentication/login.html.twig', [
            'errors' => $errors
        ]);           
    }

    public function login()
    {
        $this->auth->authentication($_POST['email'], $_POST['password']);
    }

    public function logout()
    {
        $this->auth->logout();
    }
}