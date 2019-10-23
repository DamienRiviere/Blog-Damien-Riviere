<?php

namespace App\Controller;

use App\Model\Comment;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;

class PostController extends Controller
{
    private $post;

    private $category;

    public function __construct()
    {
        parent::__construct();
        $this->post = new PostRepository();
        $this->category = new CategoryRepository();
        $this->comment = new CommentRepository();
    }

    public function posts(): void
    {
        $this->twig->display(
            'post/index.html.twig',
            [
                'posts' => $this->post->findAllPosts(),
                'categories' => $this->category->all()
            ]
        );
    }

    public function show($id): void
    {
        $this->twig->display(
            'post/show.html.twig',
            [
                'post' => $this->post->findPost($id),
                'categories' => $this->category->all()
            ]
        );
    }

    public function category($id): void
    {
        $this->twig->display(
            'post/category.html.twig',
            [
                'category' => $this->category->find($id),
                'posts' => $this->post->findPostByCategory($id)
            ]
        );
    }

    public function newComment($id, $slug): void
    {
        if (!in_array("", $_POST)) {
            $comment = new Comment();

            $comment
                ->setContent($_POST['content'])
                ->setPostId($id)
                ->setUserId($_SESSION['id'])
                ->setCreatedAt(new \DateTime());
                
            $this->comment->createComment($comment);

            header('Location: /post/' . $id . '/' . $slug . '?created=1');
        } else {
            throw new \Exception("Veuillez remplir le champ des commentaires");
        }
    }
}
