<?php

namespace App\Controller;

use App\Model\Role;
use App\Repository\RoleRepository;

class AdminRoleController extends Controller
{

    private $roleRepo;

    public function __construct()
    {
        $this->roleRepo = new RoleRepository();
        parent::__construct();
    }

    public function roles()
    {
        $this->twig->display('admin/role/index.html.twig', [
            'roles' => $this->roleRepo->all()
        ]);
    }

    public function showNew()
    {
        $this->twig->display('admin/role/new.html.twig');
    }

    public function new()
    {
        if (!in_array("", $_POST)) {
            $role = new Role();
            $role->setName($_POST['name']);

            $this->roleRepo->createRole($role);

            header('Location: /admin/roles?created=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }

    public function showEdit(int $id)
    {
        $this->twig->display('admin/role/edit.html.twig', [
            'role'  => $this->roleRepo->find($id)
        ]);
    }

    public function edit(int $id)
    {
        if (!in_array("", $_POST)) {
            $role = $this->roleRepo->find($id);

            $role->setName($_POST['name']);

            $this->roleRepo->updateRole($role);

            header('Location: /admin/roles?edit=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs");
        }
    }

    public function delete(int $id)
    {
        $this->roleRepo->delete($id);
        header('Location: /admin/roles?delete=1');
    }
}
