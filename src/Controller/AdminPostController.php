<?php

namespace App\Controller;

use App\Model\Post;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;

class AdminPostController extends Controller
{
    private $post;

    private $category;

    private $comment;

    public function __construct()
    {
        $this->checkRole();
        $this->post = new PostRepository();
        $this->slugify = new Slugify();
        $this->category = new CategoryRepository();
        $this->comment = new CommentRepository();
        parent::__construct();
    }

    public function posts()
    {
        $this->twig->display('admin/post/index.html.twig', [
            'posts' => $this->post->findPostsPaginated()[0],
            'pagination' => $this->post->findPostsPaginated()[1]
        ]);
    }
    
    public function showNew()
    {
        $this->twig->display('admin/post/new.html.twig', [
            'categories' => $this->category->all()
        ]);
    }

    public function new()
    {
        if (!in_array("", $_POST)) {
            $post = new Post();

            $post
                ->setTitle($_POST['title'])
                ->setIntroduction($_POST['introduction'])
                ->setContent($_POST['content'])
                ->setCreatedAt(new \DateTime())
                ->setCoverImage($_POST['image'])
                ->setSlug($this->slugify->slugify($_POST['title']))
                ->setUserId(1);

            $this->post->createPost($post);
            $this->post->attachCategoriesToPost($post->getId(), $_POST['categories']);

            header('Location: /admin/posts?created=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }

    public function showEdit(int $id)
    {
        $this->twig->display('admin/post/edit.html.twig', [
            'post' => $this->post->findPost($id),
            'categories' => $this->category->all()
        ]);
    }

    public function edit(int $id)
    {
        if (!in_array("", $_POST)) {
            $post = $this->post->find($id);

            $post
                ->setTitle($_POST['title'])
                ->setIntroduction($_POST['introduction'])
                ->setContent($_POST['content'])
                ->setModifyAt(new \DateTime())
                ->setCoverImage($_POST['image'])
                ->setSlug($this->slugify->slugify($_POST['title']));

            $this->post->updatePost($post);
            $this->post->attachCategoriesToPost($post->getId(), $_POST['categories']);

            header('Location: /admin/posts?edit=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }

    public function delete(int $id)
    {
        $this->post->delete($id);
        header('Location: /admin/posts?delete=1');
    }

    public function comments(int $id)
    {
        $this->twig->display('admin/post/comments.html.twig', [
            'post' => $this->post->findPost($id),
            'comments' => $this->comment->findCommentsPaginated($id)[0],
            'pagination' => $this->comment->findCommentsPaginated($id)[1]
        ]);
    }

    public function deleteComment(int $id, int $idComment)
    {
        $this->comment->delete($idComment);
        header('Location: /admin/post/' . $id . '/comments?delete=1');
    }
}
