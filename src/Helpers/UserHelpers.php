<?php

namespace App\Helpers;

use App\Repository\UserRepository;
use App\Validation\AccountValidation;

class UserHelpers
{

    private $userRepo;

    private $validation;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->validation = new AccountValidation();
    }

    /**
     * Start the steps for checking and adding an new email to database
     * @param string $email
     * @param int $id
     * @throws \Exception
     */
    public function email(string $email, int $id)
    {
        $this->validation->checkEmail($email, $id);

        if ($this->validation->isCheckEmailFormat() && $this->validation->isCheckEmailExist() != false) {
            $this->setEditEmail($email, $id);
        }
    }

    /**
     * Start the steps for checking and adding a new password to database
     * @param string $password
     * @param int $id
     * @throws \Exception
     */
    public function password(string $password, int $id)
    {
        $this->validation->checkPassword($password, $id);

        if ($this->validation->isCheckPasswordLength() != false) {
            $this->setEditPassword($password, $id);
        }
    }

    /**
     * Set a new role for user
     * @param int $id
     * @throws \Exception
     */
    public function setEditUser(int $id)
    {
        $user = $this->userRepo->find($id);
        $user->setRoleId($_POST['role']);

        $this->userRepo->updateRole($user, $user->getId());

        header('Location: /admin/users?edit=1');
    }

    /**
     * Set a new email for user
     * @param string $email
     * @param int $id
     * @throws \Exception
     */
    public function setEditEmail(string $email, int $id)
    {
        $email = $this->userRepo->find($id);
        $email->setEmail($_POST['email']);

        $this->userRepo->updateEmail($email, $id);
        $_SESSION['email'] = $_POST['email'];

        $this->unsetSessionCheckEmail();
        header('Location: /account?edit-email=1');
    }

    /**
     * Set a new password for user
     * @param string $password
     * @param int $id
     * @throws \Exception
     */
    public function setEditPassword(string $password, int $id)
    {
        $password = $this->userRepo->find($id);
        $password->setPassword(password_hash($_POST['password'], PASSWORD_BCRYPT));

        $this->userRepo->updatePassword($password, $id);
        $this->unsetSessionCheckPassword();
        header('Location: /account?edit-password=1');
    }

    /**
     * Set a new picture for user
     * @param string $picture
     * @param int $id
     * @throws \Exception
     */
    public function setEditPicture(string $picture, int $id)
    {
        $picture = $this->userRepo->find($id);
        $picture->setPicture($_POST['picture']);

        $this->userRepo->updatePicture($picture, $id);
        header('Location: /account?edit-picture=1');
    }

    /**
     * Unset session check mail if email is edited
     */
    public function unsetSessionCheckEmail()
    {
        unset($_SESSION['checkAccountEmail']);
    }

    /**
     * Unset session check password if password is edited
     */
    public function unsetSessionCheckPassword()
    {
        unset($_SESSION['checkAccountPassword']);
    }
}
