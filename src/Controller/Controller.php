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
            'cache' => false,
            'debug' => true
        ];

        $this->twig = new \Twig\Environment($this->loader, $params);

        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->twig->addGlobal('get', $_GET);
        $this->twig->addGlobal('session', $_SESSION);
    }
}
