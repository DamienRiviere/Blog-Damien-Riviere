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

    public function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $user = $this->userRepo->findEmail($email);

            if (is_bool($user) === true) {
                header('Location: /login?access-denied=1');
            }
        } else {
            header('Location: /login?access-denied=1');
        }

        return $user;
    }

    public function checkPassword($password, $user)
    {
        $password = password_verify($password, $user->getPassword());

        return $password;
    }
}
