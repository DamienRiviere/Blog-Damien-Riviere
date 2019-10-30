<?php

namespace App\Controller;

use App\Repository\PostRepository;

class HomeController extends Controller
{
    private $posts;

    public function __construct()
    {
        $this->posts = new PostRepository();
        parent::__construct();
    }

    public function home(): void
    {
        $this->twig->display('home/index.html.twig', [
            "posts" => $this->posts->findLastPosts()
        ]);
    }
}
