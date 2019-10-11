<?php

namespace App\Controller;

abstract class Controller
{
    
    protected $twig;

    protected $loader;

    private $templatePath = "..\\templates\\";

    public function __construct()
    {
        $this->loadTwig();
    }
    
    public function loadTwig(): void
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(
            [
                $this->templatePath
            ]
        );

        $params = [
            'cache' => false
        ];

        $this->twig = new \Twig\Environment($this->loader, $params);
    }
}
