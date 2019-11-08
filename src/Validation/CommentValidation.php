<?php

namespace App\Validation;

use App\Helpers\Session;

class CommentValidation
{

    private $checkComment = true;

    private $url;

    private $session;

    public function __construct($url)
    {
        $this->url = $url;
        $this->session = new Session();
    }

    public function checkComment(string $comment)
    {
        $this->checkCommentField($comment);
    }

    public function checkCommentField(string $comment)
    {
        if (empty($comment)) {
            $this->setCheckComment(false);
            $this->session->setSession("checkPostComment", "Veuillez écrire un commentaire !");
            return header('Location: ' . $this->url);
        }
    }

    public function isCheckComment(): bool
    {
        return $this->checkComment;
    }

    public function setCheckComment(bool $checkComment): void
    {
        $this->checkComment = $checkComment;
    }
}
