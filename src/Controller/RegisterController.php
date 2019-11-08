<?php

namespace App\Controller;

use App\Services\Register;

class RegisterController extends Controller
{

    private $register;

    public function __construct()
    {
        $this->register = new Register();
        parent::__construct();
    }

    public function showRegister()
    {
        $this->twig->display('register/register.html.twig');
    }

    public function register(array $data)
    {
        $this->register->registration($data);
    }
}
