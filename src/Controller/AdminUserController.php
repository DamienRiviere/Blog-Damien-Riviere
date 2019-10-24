<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use App\Repository\UserRepository;

class AdminUserController extends Controller
{

    private $userRepo;

    private $roleRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository();
        $this->roleRepo = new RoleRepository();
    }
    
    public function users()
    {
        $this->twig->display('admin/user/index.html.twig', [
            'users' => $this->userRepo->findAllUsers()
        ]);
    }
    
    public function showEdit(int $id)
    {
        $this->twig->display('/admin/user/edit.html.twig', [
            'user'  => $this->userRepo->find($id),
            'roles' => $this->roleRepo->all()
        ]);
    }

    public function edit(int $id)
    {
        if (!in_array("", $_POST)) {
            $user = $this->userRepo->find($id);
            $user->setRoleId($_POST['role']);

            $this->userRepo->updateRole($user, $user->getId());

            header('Location: /admin/users?edit=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs");
        }
    }

    public function delete(int $id)
    {
        $this->userRepo->delete($id);
        header('Location: /admin/users?delete=1');
    }
}
