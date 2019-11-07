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
     * @param array $data
     * @return void
     */
    public function registration(array $data)
    {
        $this->checkValidation($data);

        if (
            $this->validation->isCheckNameExist()
            && $this->validation->isCheckNameLength()
            && $this->validation->isCheckEmailExist()
            && $this->validation->isCheckEmailFormat()
            && $this->validation->isCheckPassword() != false
        ) {
            $this->setUser($data);
        }
    }

    public function setUser(array $data)
    {
        $user = new User();
        $user
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setSlug($this->slugify->slugify($user->getName()))
            ->setPassword(password_hash($data['password'], PASSWORD_BCRYPT))
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
     * @param array $data
     */
    public function checkValidation(array $data)
    {
        $this->validation->checkName($data['name']);
        $this->validation->checkEmail($data['email']);
        $this->validation->checkPassword($data['password']);
    }
}
