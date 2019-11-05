<?php

namespace App\Helpers;

use App\Model\Post;
use App\Repository\PostRepository;
use App\Validation\PostValidation;
use Cocur\Slugify\Slugify;

class PostHelpers
{
    private $slugify;

    private $postRepo;

    private $validation;

    public function __construct()
    {
        $this->slugify = new Slugify();
        $this->postRepo = new PostRepository();
    }

    /**
     * Start the steps for checking and adding a post in database
     * @param array $post
     * @param string $url
     * @throws \Exception
     */
    public function post(array $post, string $url)
    {
        $this->setValueIfErrors($post);
        $this->checkValidation($post, $url);

        if (
            $this->validation->isCheckTitle()
            && $this->validation->isCheckIntro()
            && $this->validation->isCheckPicture()
            && $this->validation->isCheckContent() != false
        ) {
            $this->newOrEdit($post, $url);
        }
    }

    /**
     * Checking url and redirect in the right direction
     * if it's a new post or an edit post
     * @param $post
     * @param $url
     * @throws \Exception
     */
    public function newOrEdit($post, $url)
    {
        $type = explode("/", $url)[3];

        if ($type === "new") {
            $this->setNewPost($post);
        } else {
            $this->setEditPost($post, $url);
        }
    }

    /**
     * Set a new Post in database
     * @param array $post
     * @throws \Exception
     */
    public function setNewPost(array $post)
    {
        $post = new Post();

        $post
            ->setTitle($_POST['title'])
            ->setIntroduction($_POST['introduction'])
            ->setContent($_POST['content'])
            ->setCreatedAt(new \DateTime())
            ->setCoverImage($_POST['image'])
            ->setSlug($this->slugify->slugify($_POST['title']))
            ->setUserId($_SESSION['id']);

        $this->postRepo->createPost($post);
        $this->postRepo->attachCategoriesToPost($post->getId(), $_POST['categories']);

        $this->unsetSessionCheck();
        $this->unsetSessionValue();
        header('Location: /admin/posts?created=1');
    }

    /**
     * Set an edit post in database
     * @param array $post
     * @param string $url
     * @throws \Exception
     */
    public function setEditPost(array $post, string $url)
    {
        $post = $this->postRepo->find($this->getId($url));

        $post
            ->setTitle($_POST['title'])
            ->setIntroduction($_POST['introduction'])
            ->setContent($_POST['content'])
            ->setModifyAt(new \DateTime())
            ->setCoverImage($_POST['image'])
            ->setSlug($this->slugify->slugify($_POST['title']));

        $this->postRepo->updatePost($post);
        $this->postRepo->attachCategoriesToPost($post->getId(), $_POST['categories']);

        $this->unsetSessionCheck();
        $this->unsetSessionValue();
        header('Location: /admin/posts?edit=1');
    }

    /**
     * Get id from the url for edit post
     * @param $url
     * @return mixed
     */
    public function getId($url)
    {
        return $id = explode("/", $url)[4];
    }

    /**
     * Validate the data posted
     * @param $post
     * @param $url
     */
    public function checkValidation($post, $url)
    {
        $this->validation = new PostValidation($url);
        $this->validation->checkTitle($_POST['title']);
        $this->validation->checkIntro($_POST['introduction']);
        $this->validation->checkPicture($_POST['image']);
        $this->validation->checkContent($_POST['content']);
    }

    /**
     * Unset all session checking messages
     */
    public function unsetSessionCheck()
    {
        unset($_SESSION['checkPostTitle']);
        unset($_SESSION['checkPostPicture']);
        unset($_SESSION['checkPostIntro']);
        unset($_SESSION['checkPostContent']);
    }

    /**
     * Add to session all field from post
     * When an error appeared the page is refresh
     * and form field display the data from session
     * @param array $post
     */
    public function setValueIfErrors(array $post)
    {
        $_SESSION['postTitle'] = $_POST['title'];
        $_SESSION['postIntro'] = $_POST['introduction'];
        $_SESSION['postImage'] = $_POST['image'];
        $_SESSION['postContent'] = $_POST['content'];
    }

    /**
     * Unset all session data from post
     */
    public function unsetSessionValue()
    {
        unset($_SESSION['postTitle']);
        unset($_SESSION['postIntro']);
        unset($_SESSION['postImage']);
        unset($_SESSION['postContent']);
    }
}
