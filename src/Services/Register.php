<?php

namespace App\Services;

use App\Model\User;
use App\Validation\RegisterValidation;
use Cocur\Slugify\Slugify;
use App\Repository\UserRepository;

class Register
{

    private $userRepo;

    private $slugify;

    private $validation;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->slugify = new Slugify();
        $this->validation = new RegisterValidation();
    }
    
    /**
     * Check if every field send by user is valid
     * If all field are valid, a new user is created
     *
     * @param array $user
     * @return void
     */
    public function registration(array $user)
    {
        $this->checkValidation($user);

        if (
            $this->validation->isCheckName()
            && $this->validation->isCheckEmail()
            && $this->validation->isCheckPassword() != false
        ) {
            $this->setUser($user);
        }
    }

    public function setUser(array $user)
    {
        $user = new User();
        $user
            ->setName($_POST['name'])
            ->setEmail($_POST['email'])
            ->setSlug($this->slugify->slugify($user->getName()))
            ->setPassword(password_hash($_POST['password'], PASSWORD_BCRYPT))
            ->setPicture("http://image.jeuxvideo.com/avatar-md/default.jpg")
            ->setCreatedAt(new \DateTime())
            ->setRoleId(2);

        $this->userRepo->createUser($user);
        $this->unsetSessionCheck();

        header('Location: /login?created=1');
    }

    /**
     * Unset all session if a new user is created
     *
     * @return void
     */
    public function unsetSessionCheck()
    {
        unset($_SESSION['check_name']);
        unset($_SESSION['check_email']);
        unset($_SESSION['check_password']);
    }

    /**
     * Validate the data posted
     * @param $user
     */
    public function checkValidation($user)
    {
        $this->validation->checkName($_POST['name']);
        $this->validation->checkEmail($_POST['email']);
        $this->validation->checkPassword($_POST['password']);
    }
}
