<?php

namespace App\Controller;

use App\Model\Post;
use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;

class AdminPostController extends Controller
{
    private $repo;

    public function __construct()
    {
        parent::__construct();
        $this->repo = new PostRepository();
        $this->slugify = new Slugify();
    }

    public function posts()
    {
        $this->twig->display('admin/post/index.html.twig', [
            'posts' => $this->repo->all(["ORDER BY created_at DESC"])
        ]);
    }
    
    public function showNew()
    {
        $this->twig->display('admin/post/create.html.twig');
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

            $this->repo->createPost($post);

            header('Location: /admin/posts?created=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }

    public function showEdit(int $id)
    {
        $this->twig->display('admin/post/edit.html.twig', [
            'post' => $this->repo->find($id)
        ]);
    }

    public function edit(int $id)
    {
        if (!in_array("", $_POST)) {
            $post = $this->repo->find($id);

            $post
                ->setTitle($_POST['title'])
                ->setIntroduction($_POST['introduction'])
                ->setContent($_POST['content'])
                ->setModifyAt(new \DateTime())
                ->setCoverImage($_POST['image'])
                ->setSlug($this->slugify->slugify($_POST['title']));

            $this->repo->updatePost($post);

            header('Location: /admin/posts?edit=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }

    public function delete(int $id)
    {
        $this->repo->delete($id);
        header('Location: /admin/posts?delete=1');
    }
}
