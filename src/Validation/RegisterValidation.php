<?php

namespace App\Validation;

use App\Helpers\Session;
use App\Repository\UserRepository;

class RegisterValidation
{
    private $checkNameLength = true;

    private $checkNameExist = true;

    private $checkEmailFormat = true;

    private $checkEmailExist = true;

    private $checkPassword = true;

    private $userRepo;

    private $session;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->session = new Session();
    }

    /**
     * Load the methods to check the name
     * @param string $name
     * @return void
     */
    public function checkName(string $name)
    {
        $this->checkNameExist($name);
        $this->checkNameLength($name);
    }

    /**
     * Load the method to check the email
     * @param string $email
     * @return void
     */
    public function checkEmail(string $email)
    {
        $this->checkEmailFormat($email);
        $this->checkEmailExist($email);
    }

    /**
     * Load the method to check the password
     * @param string $password
     */
    public function checkPassword(string $password)
    {
        $this->checkPasswordLength($password);
    }

    /**
     * Check if a name is already present in database
     * @param string $name
     * @return void
     */
    public function checkNameExist(string $name)
    {
        if ($this->userRepo->findName($name) == true) {
            $this->setCheckNameExist(false);
            $this->session->setSession("check_name", "Ce pseudo est déjà utilisé, veuillez en choisir un autre !");
            header('Location: /register');
        }
    }

    /**
     * Check the name length
     * @param string $name
     * @return void
     */
    public function checkNameLength(string $name)
    {
        if (strlen($name) <= 3) {
            $this->setCheckNameLength(false);
            $this->session->setSession("check_name", "Votre pseudo doit comporter au minimum 3 caractères");
            header('Location: /register');
        }
    }

    /**
     * Check if the email format is right
     * @param string $email
     * @return void
     */
    public function checkEmailFormat(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $this->setCheckEmailFormat(false);
            $this->session->setSession("check_email", "Veuillez entrer un bon format d'email !");
            header('Location: /register');
        }
    }

    /**
     * Check if the email is already present in database
     * @param string $email
     * @return void
     */
    public function checkEmailExist(string $email)
    {
        if ($this->userRepo->findEmail($email) == true) {
            $this->setCheckEmailExist(false);
            $this->session->setSession("check_email", "Cet email est déjà utilisé, veuillez en choisir un autre !");
            header('Location: /register');
        }
    }

    /**
     * Check password length
     * @param string $password
     * @return void
     */
    public function checkPasswordLength(string $password)
    {
        if (strlen($password) < 4) {
            $this->setCheckPassword(false);
            $this->session->setSession("check_password", "Votre mot de passe doit comporter au minimum 4 caractères !");
            return header('Location: /register');
        }
    }

    public function isCheckNameLength(): bool
    {
        return $this->checkNameLength;
    }

    public function setCheckNameLength(bool $checkNameLength): void
    {
        $this->checkNameLength = $checkNameLength;
    }

    public function isCheckNameExist(): bool
    {
        return $this->checkNameExist;
    }

    public function setCheckNameExist(bool $checkNameExist): void
    {
        $this->checkNameExist = $checkNameExist;
    }

    public function isCheckEmailFormat(): bool
    {
        return $this->checkEmailFormat;
    }

    public function setCheckEmailFormat(bool $checkEmailFormat): void
    {
        $this->checkEmailFormat = $checkEmailFormat;
    }

    public function isCheckEmailExist(): bool
    {
        return $this->checkEmailExist;
    }

    public function setCheckEmailExist(bool $checkEmailExist): void
    {
        $this->checkEmailExist = $checkEmailExist;
    }

    public function isCheckPassword(): bool
    {
        return $this->checkPassword;
    }

    public function setCheckPassword(bool $checkPassword): void
    {
        $this->checkPassword = $checkPassword;
    }
}
