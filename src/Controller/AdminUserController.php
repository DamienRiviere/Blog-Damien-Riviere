<?php

namespace App\Controller;

use App\Helpers\UserHelpers;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;

class AdminUserController extends Controller
{

    private $userRepo;

    private $roleRepo;

    private $userHelpers;

    public function __construct()
    {
        $this->checkRole();
        $this->userRepo = new UserRepository();
        $this->roleRepo = new RoleRepository();
        $this->userHelpers = new UserHelpers();
        parent::__construct();
    }
    
    public function users()
    {
        $this->twig->display('admin/user/index.html.twig', [
            'users' => $this->userRepo->findUsersPaginated()[0],
            'pagination' => $this->userRepo->findUsersPaginated()[1]
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
        $this->userHelpers->setEditUser($id, $this->post());
    }

    public function delete(int $id)
    {
        $this->userRepo->delete($id);
        header('Location: /admin/users?delete=1');
    }
}
