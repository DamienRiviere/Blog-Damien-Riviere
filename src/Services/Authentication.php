<?php

namespace App\Services;

use App\Helpers\Session;
use App\Repository\UserRepository;
use App\Validation\AuthenticationValidation;

class Authentication
{
    
    private $userRepo;

    private $validation;

    private $session;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->validation = new AuthenticationValidation();
        $this->session = new Session();
    }

    /**
     * Check the email and password and get user to authenticate him
     * @param array $data
     */
    public function authentication(array $data)
    {
        $user = $this->validation->checkEmail($data['email']);

        if ($this->validation->checkPassword($data['password'], $user)) {
            $this->setSession($user);
            return header('Location: /');
        }

        return header('Location: /login?access-denied=1');
    }

    /**
     * Set user session when he is connected
     * @param $user
     */
    private function setSession($user)
    {
        $this->session->setSession("id", $user->getId());
        $this->session->setSession("name", $user->getName());
        $this->session->setSession("email", $user->getEmail());
        $this->session->setSession("slug", $user->getSlug());
        $this->session->setSession("picture", $user->getPicture());
        $this->session->setSession("created_at", $user->getCreatedAt());
        $this->session->setSession("role_id", $user->getRoleId());
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
    }
}
