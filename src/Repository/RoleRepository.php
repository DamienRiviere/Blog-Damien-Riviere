<?php 

namespace App\Repository;

use App\Model\Role;

class RoleRepository extends Repository {

    protected $repository = "role";

    protected $class = Role::class;

    public function createRole(Role $role): void
    {
        $id = $this->create([
            'name' => $role->getName()
        ]);
        $role->setId($id);
    }
}