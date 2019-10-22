<?php

namespace App\Controller;

use App\Services\Register;

class RegisterController extends Controller {

    private $register;

    public function __construct()
    {
        parent::__construct();
        $this->register = new Register();
    }

    public function showRegister()
    {
        $this->twig->display('register/register.html.twig');
    }

    public function register()
    {
        $this->register->registration($_POST);
    }
}