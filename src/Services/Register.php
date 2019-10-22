<?php

namespace App\Services;

use App\Model\User;
use Cocur\Slugify\Slugify;
use App\Repository\UserRepository;

class Register {

    private $userRepo;

    private $slugify;

    private $checkName = false;

    private $checkEmail = false;

    private $checkPassword = false;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->slugify = new Slugify();
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
        $this->checkName($_POST['name']);
        $this->checkEmail($_POST['email']);
        $this->checkPasswordLength($_POST['password']);

        if($this->checkName && $this->checkEmail && $this->checkPassword != false) {
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
     * Load the methods to check the name
     *
     * @param string $name
     * @return void
     */
    public function checkName(string $name)
    {
        $this->checkNameExist($name);
        $this->checkNameLength($name);
    }
    
    /**
     * Check if a name is already present in database
     *
     * @param string $name
     * @return void
     */
    public function checkNameExist(string $name)
    {
        if($this->userRepo->findName($name) == true) {
            $this->checkName = false;
            $_SESSION['check_name'] = "Ce pseudo est déjà utilisé, veuillez en choisir un autre !";
            header('Location: /register');
        } else {
            $this->checkName = true;
        }
    }

    /**
     * Check the name length
     *
     * @param string $name
     * @return void
     */
    public function checkNameLength(string $name)
    {
        if(strlen($name) < 3) {
            $this->checkName = false;
            $_SESSION['check_name'] = "Votre pseudo doit comporter au minimum 3 caractères";
            header('Location: /register');
        } else {
            $this->checkName = true;
        }
    }

    /**
     * Load the method to check the email
     *
     * @param string $email
     * @return void
     */
    public function checkEmail(string $email)
    {
        $this->checkEmailFormat($email);
        $this->checkEmailExist($email);
    }

    /**
     * Check if the email format is right
     *
     * @param string $email
     * @return void
     */
    public function checkEmailFormat(string $email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $this->checkEmail = false;
            $_SESSION['check_email'] = "Veuillez entrer un bon format d'email !";
            header('Location: /register');
        } else {
            $this->checkEmail = true;
        }
    }

    /**
     * Check if the email is already present in database
     *
     * @param string $email
     * @return void
     */
    public function checkEmailExist(string $email)
    {
        if($this->userRepo->findEmail($email) == true) {
            $this->checkEmail = false;
            $_SESSION['check_email'] = "Cet email est déjà utilisé, veuillez en choisir un autre !";
            header('Location: /register');
        } else {
            $this->checkEmail = true;
        }
    }

    /**
     * Check password length
     *
     * @param string $password
     * @return void
     */
    public function checkPasswordLength(string $password)
    {
        if(strlen($password) < 4) {
            $this->checkPassword = false;
            $_SESSION['check_password'] = "Votre mot de passe doit comporter au minimum 4 caractères";
            header('Location: /register');
        } else {
            $this->checkPassword = true;
        }
    }
}