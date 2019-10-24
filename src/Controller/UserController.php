<?php

namespace App\Controller;

use App\Repository\UserRepository;

class UserController extends Controller
{

    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->checkSession();
        $this->userRepo = new UserRepository();
    }

    public function account()
    {
        $this->twig->display('user/account.html.twig');
    }

    public function showEditEmail($id)
    {
        $this->twig->display('user/edit_email.html.twig', [
            'email' => $_SESSION['email']
        ]);
    }

    public function editEmail($id)
    {
        if (!in_array("", $_POST)) {
            $email = $this->userRepo->find($id);

            $email->setEmail($_POST['email']);

            $this->userRepo->updateEmail($email, $id);
            $_SESSION['email'] = $_POST['email'];

            header('Location: /account?edit-email=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }
}
