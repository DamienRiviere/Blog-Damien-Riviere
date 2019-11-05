<?php

namespace App\Helpers;

use App\Model\Role;
use App\Repository\RoleRepository;

class RoleHelpers
{

    private $roleRepo;

    public function __construct()
    {
        $this->roleRepo = new RoleRepository();
    }

    public function newRole()
    {
        $role = new Role();
        $role->setName($_POST['name']);
        $this->roleRepo->createRole($role);

        header('Location: /admin/roles?created=1');
    }

    public function editRole(int $id)
    {
        $role = $this->roleRepo->find($id);
        $role->setName($_POST['name']);
        $this->roleRepo->updateRole($role);

        header('Location: /admin/roles?edit=1');
    }
}
