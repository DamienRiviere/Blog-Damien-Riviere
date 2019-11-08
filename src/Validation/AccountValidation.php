<?php

namespace App\Validation;

use App\Helpers\Session;
use App\Repository\UserRepository;

class AccountValidation
{

    private $checkEmailFormat = true;

    private $checkEmailExist = true;

    private $checkPasswordLength = true;

    private $userRepo;

    private $session;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->session = new Session();
    }

    /**
     * Load the methods to check the email
     * @param array $data
     * @param int $id
     * @return void
     */
    public function checkEmail(array $data, int $id)
    {
        $this->checkEmailFormat($data, $id);
        $this->checkEmailExist($data, $id);
    }

    /**
     * Load the method to check the password
     * @param array $data
     * @param int $id
     */
    public function checkPassword(array $data, int $id)
    {
        $this->checkPasswordLength($data, $id);
    }

    /**
     * Check if the email format is right
     * @param array $data
     * @param int $id
     * @return void
     */
    public function checkEmailFormat(array $data, int $id)
    {
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) == false) {
            $this->setCheckEmailFormat(false);
            $this->session->setSession(
                "checkAccountEmail",
                "Veuillez entrer un bon format d'email !"
            );
            header('Location: /account/email/edit/' . $id);
        }
    }

    /**
     * Check if the email is already present in database
     * @param array $data
     * @param int $id
     * @return void
     */
    public function checkEmailExist(array $data, int $id)
    {
        if ($this->userRepo->findEmail($data['email']) == true) {
            $this->setCheckEmailExist(false);
            $this->session->setSession(
                "checkAccountEmail",
                "Cet email est déjà utilisé, veuillez en choisir un autre !"
            );
            header('Location: /account/email/edit/' . $id);
        }
    }

    /**
     * Check password length
     * @param array $data
     * @param int $id
     * @return void
     */
    public function checkPasswordLength(array $data, int $id)
    {
        if (strlen($data['password']) < 4) {
            $this->setCheckPasswordLength(false);
            $this->session->setSession(
                "checkAccountPassword",
                "Votre mot de passe doit comporter au minimum 4 caractères"
            );
            return header('Location: /account/password/edit/' . $id);
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
