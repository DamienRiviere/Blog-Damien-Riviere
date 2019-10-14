<?php

namespace App\Controller;

use App\Repository\PostRepository;

class PostController extends Controller
{

    public function posts(): void
    {
        $posts = new PostRepository();
        $this->twig->display(
            'post/index.html.twig',
            [
                'posts' => $posts->all(["ORDER BY created_at DESC"])
            ]
        );
    }

    public function show($id): void
    {
        $post = new PostRepository();
        $this->twig->display(
            'post/show.html.twig',
            [
                'post' => $post->find($id)
            ]
        );
    }
}
