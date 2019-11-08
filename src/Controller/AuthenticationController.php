<?php

namespace App\Controller;

use App\Services\Authentication;
use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;

class AuthenticationController extends Controller
{

    private $auth;

    private $userRepo;

    public function __construct()
    {
        $this->auth = new Authentication();
        $this->userRepo = new UserRepository();
        $this->slugify = new Slugify();
        parent::__construct();
    }

    public function showLogin($errors = null)
    {
        $this->twig->display('authentication/login.html.twig', [
            'errors' => $errors
        ]);
    }

    public function login()
    {
        $this->auth->authentication($this->data->getData());
    }

    public function logout()
    {
        $this->auth->logout();
    }
}
