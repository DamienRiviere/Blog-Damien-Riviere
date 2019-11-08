<?php

namespace App\Controller;

use App\Helpers\Session;
use App\Helpers\UserHelpers;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;

class AccountController extends Controller
{

    private $userRepo;

    private $commentRepo;

    private $postRepo;

    private $userHelpers;

    public function __construct()
    {
        $this->checkSession();
        $this->userRepo = new UserRepository();
        $this->commentRepo = new CommentRepository();
        $this->postRepo = new PostRepository();
        $this->userHelpers = new UserHelpers();
        parent::__construct();
    }

    public function account()
    {
        $this->twig->display('user/account.html.twig', [
            'countComments' => $this->commentRepo->findCountCommentsByUser($this->session->getItem('id')),
            'countPosts' => $this->postRepo->findCountPostsByUser($this->session->getItem('id')),
            'posts' => $this->postRepo->findPostsLikeByUser($this->session->getItem('id'))[0],
            'pagination' => $this->postRepo->findPostsLikeByUser($this->session->getItem('id'))[1]
        ]);
    }

    public function showEditEmail()
    {
        $this->twig->display('user/edit_email.html.twig', [
            'email' => $_SESSION['email'],
            'countComments' => $this->commentRepo->findCountCommentsByUser($this->session->getItem('id')),
            'countPosts' => $this->postRepo->findCountPostsByUser($this->session->getItem('id')),
            'posts' => $this->postRepo->findPostsLikeByUser($this->session->getItem('id'))[0],
            'pagination' => $this->postRepo->findPostsLikeByUser($this->session->getItem('id'))[1]
        ]);
    }

    public function editEmail(int $id)
    {
        $this->userHelpers->email($this->data->getData(), $id);
    }

    public function showEditPassword()
    {
        $this->twig->display('user/edit_password.html.twig', [
            'countComments' => $this->commentRepo->findCountCommentsByUser($this->session->getItem('id')),
            'countPosts' => $this->postRepo->findCountPostsByUser($this->session->getItem('id')),
            'posts' => $this->postRepo->findPostsLikeByUser($this->session->getItem('id'))[0],
            'pagination' => $this->postRepo->findPostsLikeByUser($this->session->getItem('id'))[1]
        ]);
    }

    public function editPassword(int $id)
    {
        $this->userHelpers->password($this->data->getData(), $id);
    }

    public function showEditPicture()
    {
        $this->twig->display('user/edit_picture.html.twig', [
            'countComments' => $this->commentRepo->findCountCommentsByUser($this->session->getItem('id')),
            'countPosts' => $this->postRepo->findCountPostsByUser($this->session->getItem('id')),
            'posts' => $this->postRepo->findPostsLikeByUser($this->session->getItem('id'))[0],
            'pagination' => $this->postRepo->findPostsLikeByUser($this->session->getItem('id'))[1]
        ]);
    }

    public function editPicture(int $id)
    {
        $this->userHelpers->setEditPicture($this->data->getData(), $id);
    }
}
