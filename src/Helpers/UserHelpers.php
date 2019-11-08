<?php

namespace App\Helpers;

use App\Repository\UserRepository;
use App\Validation\AccountValidation;

class UserHelpers extends Helpers
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
     * @param array $data
     * @param int $id
     * @throws \Exception
     */
    public function email(array $data, int $id)
    {
        $this->validation->checkEmail($data, $id);

        if ($this->validation->isCheckEmailFormat() && $this->validation->isCheckEmailExist() != false) {
            $this->setEditEmail($data, $id);
        }
    }

    /**
     * Start the steps for checking and adding a new password to database
     * @param array $data
     * @param int $id
     * @throws \Exception
     */
    public function password(array $data, int $id)
    {
        $this->validation->checkPassword($data, $id);

        if ($this->validation->isCheckPasswordLength() != false) {
            $this->setEditPassword($data, $id);
        }
    }

    /**
     * Set a new role for user
     * @param int $id
     * @param array $data
     * @throws \Exception
     */
    public function setEditUser(int $id, array $data)
    {
        $user = $this->userRepo->find($id);
        $user->setRoleId($data['role']);

        $this->userRepo->updateRole($user, $user->getId());

        header('Location: /admin/users?edit=1');
    }

    /**
     * Set a new email for user
     * @param array $data
     * @param int $id
     * @throws \Exception
     */
    public function setEditEmail(array $data, int $id)
    {
        $email = $this->userRepo->find($id);
        $email->setEmail($data['email']);

        $this->userRepo->updateEmail($email, $id);
        $_SESSION['email'] = $data['email'];

        $this->unsetSessionCheckEmail();
        header('Location: /account?edit-email=1');
    }

    /**
     * Set a new password for user
     * @param array $data
     * @param int $id
     * @throws \Exception
     */
    public function setEditPassword(array $data, int $id)
    {
        $password = $this->userRepo->find($id);
        $password->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));

        $this->userRepo->updatePassword($password, $id);
        $this->unsetSessionCheckPassword();
        header('Location: /account?edit-password=1');
    }

    /**
     * Set a new picture for user
     * @param array $data
     * @param int $id
     * @throws \Exception
     */
    public function setEditPicture(array $data, int $id)
    {
        $picture = $this->userRepo->find($id);
        $picture->setPicture($data['picture']);

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
