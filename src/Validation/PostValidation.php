<?php

namespace App\Validation;

class PostValidation
{
    private $checkTitle = false;

    private $checkPicture = false;

    private $checkIntro = false;

    private $checkContent = false;

    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function checkTitle(string $title)
    {
        $this->checkTitleField($title);
        $this->checkTitleLength($title);
    }

    public function checkIntro(string $intro)
    {
        $this->checkIntroField($intro);
        $this->checkIntroLength($intro);
    }

    public function checkPicture(string $picture)
    {
        $this->checkPictureField($picture);
    }

    public function checkContent(string $content)
    {
        $this->checkContentField($content);
        $this->checkContentLength($content);
    }

    public function checkTitleField(string $title)
    {
        if (empty($title)) {
            $this->setCheckTitle(false);
            $_SESSION['checkPostTitle'] = "Le titre ne doit pas être vide !";
            header('Location: ' . $this->url);
        } else {
            $this->setCheckTitle(true);
        }
    }

    public function checkTitleLength(string $title)
    {
        if (strlen($title) < 10) {
            $this->setCheckTitle(false);
            $_SESSION['checkPostTitle'] = "Le titre doit comporter au minimum 10 caractères !";
            header('Location: ' . $this->url);
        } else {
            $this->setCheckTitle(true);
        }
    }

    public function checkPictureField(string $picture)
    {
        if (empty($picture)) {
            $this->setCheckPicture(false);
            $_SESSION['checkPostPicture'] = "L'image de l'article ne doit pas être vide";
            header('Location: ' . $this->url);
        } else {
            $this->setCheckPicture(true);
        }
    }

    public function checkIntroField(string $intro)
    {
        if (empty($intro)) {
            $this->setCheckIntro(false);
            $_SESSION['checkPostIntro'] = "L'introduction de l'article ne doit pas être vide !";
            header('Location: ' . $this->url);
        } else {
            $this->setCheckIntro(true);
        }
    }

    public function checkIntroLength(string $intro)
    {
        if (strlen($intro) < 50) {
            $this->setCheckIntro(false);
            $_SESSION['checkPostIntro'] = "L'introduction doit comporter au minimum 50 caractères !";
            header('Location: ' . $this->url);
        } else {
            $this->setCheckIntro(true);
        }
    }

    public function checkContentField(string $content)
    {
        if (empty($content)) {
            $this->setCheckContent(false);
            $_SESSION['checkPostContent'] = "Le contenu de l'article ne doit pas être vide !";
            header('Location: ' . $this->url);
        } else {
            $this->setCheckContent(true);
        }
    }

    public function checkContentLength(string $content)
    {
        if (strlen($content) < 100) {
            $this->setCheckContent(false);
            $_SESSION['checkPostContent'] = "Le contenu de l'article doit comporter au minimum 100 caractères !";
            header('Location: ' . $this->url);
        } else {
            $this->setCheckContent(true);
        }
    }

    public function isCheckTitle(): bool
    {
        return $this->checkTitle;
    }
    
    public function setCheckTitle(bool $checkTitle): void
    {
        $this->checkTitle = $checkTitle;
    }

    public function isCheckPicture(): bool
    {
        return $this->checkPicture;
    }

    public function setCheckPicture(bool $checkPicture): void
    {
        $this->checkPicture = $checkPicture;
    }

    public function isCheckIntro(): bool
    {
        return $this->checkIntro;
    }

    public function setCheckIntro(bool $checkIntro): void
    {
        $this->checkIntro = $checkIntro;
    }

    public function isCheckContent(): bool
    {
        return $this->checkContent;
    }

    public function setCheckContent(bool $checkContent): void
    {
        $this->checkContent = $checkContent;
    }
}
