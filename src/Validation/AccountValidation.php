<?php

namespace App\Validation;

use App\Repository\UserRepository;

class AccountValidation
{

    private $checkEmailFormat = false;

    private $checkEmailExist = false;

    private $checkPasswordLength = false;

    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    /**
     * Load the methods to check the email
     * @param string $email
     * @param int $id
     * @return void
     */
    public function checkEmail(string $email, int $id)
    {
        $this->checkEmailFormat($email, $id);
        $this->checkEmailExist($email, $id);
    }

    /**
     * Load the method to check the password
     * @param string $password
     * @param int $id
     */
    public function checkPassword(string $password, int $id)
    {
        $this->checkPasswordLength($password, $id);
    }

    /**
     * Check if the email format is right
     * @param string $email
     * @param int $id
     * @return void
     */
    public function checkEmailFormat(string $email, int $id)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $this->setCheckEmailFormat(false);
            $_SESSION['checkAccountEmail'] = "Veuillez entrer un bon format d'email !";
            header('Location: /account/email/edit/' . $id);
        } else {
            $this->setCheckEmailFormat(true);
        }
    }

    /**
     * Check if the email is already present in database
     * @param string $email
     * @param int $id
     * @return void
     */
    public function checkEmailExist(string $email, int $id)
    {
        if ($this->userRepo->findEmail($email) == true) {
            $this->setCheckEmailExist(false);
            $_SESSION['checkAccountEmail'] = "Cet email est déjà utilisé, veuillez en choisir un autre !";
            header('Location: /account/email/edit/' . $id);
        } else {
            $this->setCheckEmailExist(true);
        }
    }

    /**
     * Check password length
     * @param string $password
     * @param int $id
     * @return void
     */
    public function checkPasswordLength(string $password, int $id)
    {
        if (strlen($password) < 4) {
            $this->setCheckPasswordLength(false);
            $_SESSION['checkAccountPassword'] = "Votre mot de passe doit comporter au minimum 4 caractères";
            header('Location: /account/password/edit/' . $id);
        } else {
            $this->setCheckPasswordLength(true);
        }
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

    public function isCheckPasswordLength(): bool
    {
        return $this->checkPasswordLength;
    }

    public function setCheckPasswordLength(bool $checkPasswordLength): void
    {
        $this->checkPasswordLength = $checkPasswordLength;
    }
}
