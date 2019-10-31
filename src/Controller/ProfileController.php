<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;

class ProfileController extends Controller
{

    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->post = new PostRepository();
        $this->comment = new CommentRepository();
        parent::__construct();
    }

    public function showProfile($id)
    {
        $this->twig->display('profile/index.html.twig', [
            'user' =>   $this->userRepo->find($id),
            'countPosts' => $this->post->findCountPostsByUser($id),
            'countComments' => $this->comment->findCountCommentsByUser($id)
        ]);
    }
}
