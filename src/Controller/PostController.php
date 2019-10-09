<?php

namespace App\Controller;

use App\Repository\PostRepository;

class PostController extends Controller
{

    public function posts(): void
    {
        $posts = new PostRepository();
        $this->twig->display('@post/index.twig', [
            'posts' => $posts->all() 
        ]);
    }
}
