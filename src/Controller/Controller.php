<?php

namespace App\Controller;

class Controller {    

    public $twig;

    public $loader;

    public $templatePath = "..\\templates\\";

    public function __construct() {
        $this->loadTwig();
    }
    
    public function loadTwig() {
        $this->loader = new \Twig\Loader\FilesystemLoader(
            [
                $this->templatePath . '/home',
                $this->templatePath . '/layout',
                $this->templatePath . '/post',
            ]
        );

        $this->loader->addPath($this->templatePath . '/home', 'home');
        $this->loader->addPath($this->templatePath . '/post', 'post');

        $params = [
            'cache' => false
        ];

        $this->twig = new \Twig\Environment($this->loader, $params);
    }
}