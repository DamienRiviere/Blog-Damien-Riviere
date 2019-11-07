<?php

namespace App\Validation;

class CommentValidation
{
    private $checkComment = false;

    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function checkComment(string $comment)
    {
        $this->checkCommentField($comment);
    }

    public function checkCommentField(string $comment)
    {
        if (empty($comment)) {
            $this->setCheckComment(false);
            $_SESSION['checkPostComment'] = "Veuillez écrire un commentaire !";
            return header('Location: ' . $this->url);
        }

        $this->setCheckComment(true);
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
