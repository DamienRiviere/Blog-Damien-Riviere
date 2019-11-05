<?php

namespace App\Services;

use App\Repository\UserRepository;
use App\Validation\AuthenticationValidation;

class Authentication
{
    
    private $userRepo;

    private $validation;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->validation = new AuthenticationValidation();
    }

    public function authentication($email, $password)
    {
        $user = $this->validation->checkEmail($email);

        if ($this->validation->checkPassword($password, $user)) {
            $this->setSession($user);
            header('Location: /');
        } else {
            header('Location: /login?access-denied=1');
        }
    }

    private function setSession($user)
    {
        $_SESSION['id'] = $user->getId();
        $_SESSION['name'] = $user->getName();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['slug'] = $user->getSlug();
        $_SESSION['picture'] = $user->getPicture();
        $_SESSION['created_at'] = $user->getCreatedAt();
        $_SESSION['role_id'] = $user->getRoleId();
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
    }
}
