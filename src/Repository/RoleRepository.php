<?php

namespace App\Repository;

use PDO;
use App\Model\Role;

class RoleRepository extends Repository
{

    protected $repository = "role";

    protected $class = Role::class;

    public function createRole(Role $role): void
    {
        $id = $this->create([
            'name' => $role->getName()
        ]);
        $role->setId($id);
    }

    public function updateRole(Role $role): void
    {
        $this->update([
            'name' => $role->getName()
        ], $role->getId());
    }

    /**
     * Method to hydrate a user with his role
     *
     * @param array|null $users
     * @return void
     */
    public function hydrateUsersWithRole(?array $users): void
    {
        $usersByRoleId = [];
        foreach ($users as $user) {
            $usersByRoleId[$user->getRoleId()] = $user;
        }

        $query = self::getDb()->prepare('
            SELECT r.*
            FROM role r
            JOIN user u ON r.id = u.role_id
            WHERE u.role_id
            IN (' . implode(',', array_keys($usersByRoleId)) . ')
        ');

        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $roles = $query->fetchAll();

        foreach ($users as $user) {
            foreach ($roles as $role) {
                if ($user->getRoleId() == $role->getId()) {
                    $user->addRole($role);
                }
            }
        }
    }
}
