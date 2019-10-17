<?php

namespace App\Controller;

use App\Model\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;

class AdminPostController extends Controller
{
    private $posts;

    private $categories;

    public function __construct()
    {
        parent::__construct();
        $this->posts = new PostRepository();
        $this->slugify = new Slugify();
        $this->categories = new CategoryRepository();
    }

    public function posts()
    {
        $this->twig->display('admin/post/index.html.twig', [
            'posts' => $this->posts->findAllPosts()
        ]);
    }
    
    public function showNew()
    {
        $this->twig->display('admin/post/new.html.twig', [
            'categories' => $this->categories->all()
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
                ->setSlug($this->slugify->slugify($_POST['title']));

            $this->posts->createPost($post);
            $this->posts->attachCategoriesToPost($post->getId(), $_POST['categories']);

            header('Location: /admin/posts?created=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }

    public function showEdit(int $id)
    {
        $this->twig->display('admin/post/edit.html.twig', [
            'post' => $this->posts->findPost($id),
            'categories' => $this->categories->all()
        ]);
    }

    public function edit(int $id)
    {
        if (!in_array("", $_POST)) {
            $post = $this->posts->find($id);

            $post
                ->setTitle($_POST['title'])
                ->setIntroduction($_POST['introduction'])
                ->setContent($_POST['content'])
                ->setModifyAt(new \DateTime())
                ->setCoverImage($_POST['image'])
                ->setSlug($this->slugify->slugify($_POST['title']));

            $this->posts->updatePost($post);
            $this->posts->attachCategoriesToPost($post->getId(), $_POST['categories']);

            header('Location: /admin/posts?edit=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }

    public function delete(int $id)
    {
        $this->posts->delete($id);
        header('Location: /admin/posts?delete=1');
    }
}
