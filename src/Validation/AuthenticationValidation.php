<?php

namespace App\Validation;

use App\Repository\UserRepository;

class AuthenticationValidation
{

    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function checkEmail(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return header('Location: /login?access-denied=1');
        }

        $user = $this->userRepo->findEmail($email);

        return $user;
    }

    public function checkPassword($password, $user)
    {
        if ($user === false) {
            return header('Location: /login?access-denied=1');
        }

        $password = password_verify($password, $user->getPassword());

        return $password;
    }
}
