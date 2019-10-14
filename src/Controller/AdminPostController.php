<?php

namespace App\Controller;

use App\Model\Post;
use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;

class AdminPostController extends Controller
{
    
    public function create()
    {
        $this->twig->display('admin/post/create.html.twig');
    }

    public function new()
    {
        if (!in_array("", $_POST)) {
            $post = new Post();
            $repo = new PostRepository();
            $slugify = new Slugify();

            $post
                ->setTitle($_POST['title'])
                ->setIntroduction($_POST['introduction'])
                ->setContent($_POST['content'])
                ->setCreatedAt(new \DateTime())
                ->setCoverImage($_POST['image'])
                ->setSlug($slugify->slugify($_POST['title']));

            $repo->createPost($post);
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }
}
