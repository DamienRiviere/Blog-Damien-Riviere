<?php

namespace App\Services;

use App\Controller\AuthenticationController;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;

class Authentication
{
    
    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function authentication($email, $password)
    {
        $user = $this->checkEmail($email);

        if ($this->checkPassword($password, $user)) {
            $this->setSession($user);
            header('Location: /');
        } else {
            header('Location: /login?access-denied=1');
        }
    }

    private function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $user = $this->userRepo->findEmail($email);
        } else {
            header('Location: /login?access-denied=1');
        }

        return $user;
    }

    private function checkPassword($password, $user)
    {
        $password = password_verify($password, $user->getPassword());

        return $password;
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
