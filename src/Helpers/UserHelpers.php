<?php

namespace App\Helpers;

use App\Repository\UserRepository;

class UserHelpers
{

    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
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
}
