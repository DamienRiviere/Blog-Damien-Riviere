<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

class PostController extends Controller
{
    private $posts;

    private $categories;

    public function __construct()
    {
        parent::__construct();
        $this->posts = new PostRepository();
        $this->categories = new CategoryRepository();
    }

    public function posts(): void
    {
        $this->twig->display(
            'post/index.html.twig',
            [
                'posts' => $this->posts->findAllPosts(),
                'categories' => $this->categories->all()
            ]
        );
    }

    public function show($id): void
    {
        $this->twig->display(
            'post/show.html.twig',
            [
                'post' => $this->posts->findPost($id),
                'categories' => $this->categories->all()
            ]
        );
    }

    public function category($id): void
    {
        $this->twig->display(
            'post/category.html.twig',
            [
                'category' => $this->categories->find($id),
                'posts' => $this->posts->findPostByCategory($id)
            ]
        );
    }
}
