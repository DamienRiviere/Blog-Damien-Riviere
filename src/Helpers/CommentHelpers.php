<?php

namespace App\Helpers;

use App\Model\Comment;
use App\Repository\CommentRepository;
use App\Validation\CommentValidation;

class CommentHelpers
{

    private $commentRepo;

    private $validation;

    private $session;

    public function __construct()
    {
        $this->commentRepo = new CommentRepository();
        $this->session = new Session();
    }

    /**
     * Start the steps for checking and adding a new comment in post
     * @param array $comment
     * @param string $url
     * @throws \Exception
     */
    public function newComment(array $comment, string $url)
    {
        $this->checkValidation($comment['content'], $this->getPostNewUrl($url));

        if ($this->validation->isCheckComment() != false) {
            $this->setNewComment($comment, $this->getPostNewUrl($url));
        }
    }

    /**
     * Start the steps for checking and adding an edit comment in post
     * @param array $comment
     * @param string $url
     * @throws \Exception
     */
    public function editComment(array $comment, string $url)
    {
        $this->checkValidation($comment['content'], $url);

        if ($this->validation->isCheckComment() != false) {
            $this->setEditComment($comment, $url);
        }
    }

    /**
     * Set a new comment in post
     * @param array $data
     * @param string $url
     * @throws \Exception
     */
    public function setNewComment(array $data, string $url)
    {
        $comment = new Comment();

        $comment
            ->setContent($data['content'])
            ->setPostId($this->getIdPost($url))
            ->setUserId($this->session->getItem("id"))
            ->setCreatedAt(new \DateTime())
            ->setStatusId(1);

        $this->commentRepo->createComment($comment);

        $this->unsetSessionCheck();
        header('Location: ' . $url . '?created=1');
    }

    /**
     * Set an edit comment in post
     * @param array $data
     * @param string $url
     * @throws \Exception
     */
    public function setEditComment(array $data, string $url)
    {
        $comment = $this->commentRepo->find($this->getIdComment($url));

        $comment
            ->setContent($data['content'])
            ->setModifyAt(new \DateTime());

        $this->commentRepo->updateComment($comment, $this->getIdComment($url));

        $this->unsetSessionCheck();
        header('Location: ' . $this->getPostEditUrl($url) . '?edit=1');
    }

    /**
     * DataUrl the id of the post
     * @param string $url
     * @return mixed
     */
    public function getIdPost(string $url)
    {
        return explode('/', $url)[2];
    }

    /**
     * DataUrl the id of edit comment
     * @param string $url
     * @return mixed
     */
    public function getIdComment(string $url)
    {
        return explode('/', $url)[5];
    }

    /**
     * DataUrl the url redirect for the new comment
     * @param string $url
     * @return string
     */
    public function getPostNewUrl(string $url)
    {
        $array = explode('/', $url);
        unset($array[4]);
        $cleanUrl = implode('/', $array);
        return $cleanUrl;
    }

    /**
     * DataUrl the url redirect for the edit comment
     * @param string $url
     * @return string
     */
    public function getPostEditUrl(string $url)
    {
        $array = explode('/', $url);
        unset($array[4], $array[5]);
        $cleanUrl = implode('/', $array);
        return $cleanUrl;
    }

    /**
     * Validate the data posted
     * @param string $comment
     * @param string $url
     */
    public function checkValidation(string $comment, string $url)
    {
        $this->validation = new CommentValidation($url);
        $this->validation->checkComment($comment);
    }

    /**
     * Unset all session checking messages
     */
    public function unsetSessionCheck()
    {
        $this->session->deleteItem("checkPostComment");
    }
}
