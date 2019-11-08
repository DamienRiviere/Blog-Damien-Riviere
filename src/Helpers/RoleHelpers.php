<?php

namespace App\Helpers;

use App\Model\Role;
use App\Repository\RoleRepository;

class RoleHelpers extends Helpers
{

    private $roleRepo;

    public function __construct()
    {
        $this->roleRepo = new RoleRepository();
    }

    public function newRole(array $data)
    {
        $role = new Role();
        $role->setName($data['name']);
        $this->roleRepo->createRole($role);

        header('Location: /admin/roles?created=1');
    }

    public function editRole(int $id, array $data)
    {
        $role = $this->roleRepo->find($id);
        $role->setName($data['name']);
        $this->roleRepo->updateRole($role);

        header('Location: /admin/roles?edit=1');
    }
}
