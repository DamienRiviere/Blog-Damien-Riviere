<?php

namespace App\Controller;

use App\Helpers\RoleHelpers;
use App\Repository\RoleRepository;

class AdminRoleController extends Controller
{

    private $roleRepo;

    private $roleHelpers;

    public function __construct()
    {
        $this->checkRole();
        $this->roleRepo = new RoleRepository();
        $this->roleHelpers = new RoleHelpers();
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
        $this->roleHelpers->newRole($this->data->getData());
    }

    public function showEdit(int $id)
    {
        $this->twig->display('admin/role/edit.html.twig', [
            'role'  => $this->roleRepo->find($id)
        ]);
    }

    public function edit(int $id)
    {
        $this->roleHelpers->editRole($id, $this->data->getData());
    }

    public function delete(int $id)
    {
        $this->roleRepo->delete($id);
        header('Location: /admin/roles?delete=1');
    }
}
