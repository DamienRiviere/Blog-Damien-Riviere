<?php

namespace App\Controller;

use App\Repository\UserRepository;

class UserController extends Controller
{

    private $userRepo;

    public function __construct()
    {
        $this->checkSession();
        $this->userRepo = new UserRepository();
        parent::__construct();
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

    public function showEditPassword($id)
    {
        $this->twig->display('user/edit_password.html.twig');
    }

    public function editPassword($id)
    {
        if (!in_array("", $_POST)) {
            $password = $this->userRepo->find($id);

            $password->setPassword(password_hash($_POST['password'], PASSWORD_BCRYPT));

            $this->userRepo->updatePassword($password, $id);
            header('Location: /account?edit-password=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }
}
