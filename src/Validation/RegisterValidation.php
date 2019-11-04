<?php

namespace App\Validation;

use App\Repository\UserRepository;

class RegisterValidation
{
    private $checkName = false;

    private $checkEmail = false;

    private $checkPassword = false;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
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
            $this->setCheckName(false);
            $_SESSION['check_name'] = "Ce pseudo est déjà utilisé, veuillez en choisir un autre !";
            header('Location: /register');
        } else {
            $this->setCheckName(true);
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
            $this->setCheckName(false);
            $_SESSION['check_name'] = "Votre pseudo doit comporter au minimum 3 caractères";
            header('Location: /register');
        } else {
            $this->setCheckName(true);
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
            $this->setCheckEmail(false);
            $_SESSION['check_email'] = "Veuillez entrer un bon format d'email !";
            header('Location: /register');
        } else {
            $this->setCheckEmail(true);
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
            $this->setCheckEmail(false);
            $_SESSION['check_email'] = "Cet email est déjà utilisé, veuillez en choisir un autre !";
            header('Location: /register');
        } else {
            $this->setCheckEmail(true);
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
            $_SESSION['check_password'] = "Votre mot de passe doit comporter au minimum 4 caractères";
            header('Location: /register');
        } else {
            $this->setCheckPassword(true);
        }
    }

    public function isCheckName(): bool
    {
        return $this->checkName;
    }

    public function setCheckName(bool $checkName): void
    {
        $this->checkName = $checkName;
    }

    public function isCheckEmail(): bool
    {
        return $this->checkEmail;
    }

    public function setCheckEmail(bool $checkEmail): void
    {
        $this->checkEmail = $checkEmail;
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
