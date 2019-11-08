<?php

namespace App\Helpers;

use App\Model\Post;
use App\Repository\PostRepository;
use App\Validation\PostValidation;
use Cocur\Slugify\Slugify;

class PostHelpers extends Helpers
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
    public function newOrEdit(array $post, string $url)
    {
        $type = explode("/", $url)[3];

        if ($type === "new") {
            $this->setNewPost($post);
        }

        $this->setEditPost($post, $url);
    }

    /**
     * Set a new Post in database
     * @param array $data
     * @throws \Exception
     */
    public function setNewPost(array $data)
    {
        $post = new Post();

        $post
            ->setTitle($data['title'])
            ->setIntroduction($data['introduction'])
            ->setContent($data['content'])
            ->setCreatedAt(new \DateTime())
            ->setCoverImage($data['image'])
            ->setSlug($this->slugify->slugify($data['title']))
            ->setUserId($this->session()['id']);

        $this->postRepo->createPost($post);
        $this->postRepo->attachCategoriesToPost($post->getId(), $data['categories']);

        $this->unsetSessionCheck();
        $this->unsetSessionValue();
        header('Location: /admin/posts?created=1');
    }

    /**
     * Set an edit post in database
     * @param array $data
     * @param string $url
     * @throws \Exception
     */
    public function setEditPost(array $data, $url)
    {
        $post = $this->postRepo->find($this->getId($url));

        $post
            ->setTitle($data['title'])
            ->setIntroduction($data['introduction'])
            ->setContent($data['content'])
            ->setModifyAt(new \DateTime())
            ->setCoverImage($data['image'])
            ->setSlug($this->slugify->slugify($data['title']));

        $this->postRepo->updatePost($post);
        $this->postRepo->attachCategoriesToPost($post->getId(), $data['categories']);

        $this->unsetSessionCheck();
        $this->unsetSessionValue();
        header('Location: /admin/posts?edit=1');
    }

    /**
     * Get id from the url for edit post
     * @param $url
     * @return mixed
     */
    public function getId(string $url)
    {
        return explode("/", $url)[4];
    }

    /**
     * Validate the data posted
     * @param $post
     * @param $url
     */
    public function checkValidation(array $post, string $url)
    {
        $this->validation = new PostValidation($url);
        $this->validation->checkTitle($post['title']);
        $this->validation->checkIntro($post['introduction']);
        $this->validation->checkPicture($post['image']);
        $this->validation->checkContent($post['content']);
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
        $_SESSION['postTitle'] = $post['title'];
        $_SESSION['postIntro'] = $post['introduction'];
        $_SESSION['postImage'] = $post['image'];
        $_SESSION['postContent'] = $post['content'];
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
