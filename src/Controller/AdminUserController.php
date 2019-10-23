<?php

namespace App\Controller;

use App\Repository\UserRepository;

class AdminUserController extends Controller
{

    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository();
    }
    
    public function users()
    {
        $this->twig->display('admin/users/index.html.twig', [
            'users' => $this->userRepo->findAllUsers()
        ]);
    }

    public function delete(int $id)
    {
        $this->userRepo->delete($id);
        header('Location: /admin/users?delete=1');
    }
}
