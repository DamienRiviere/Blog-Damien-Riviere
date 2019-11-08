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
        $this->twig->addGlobal('get', $this->get());
        $this->twig->addGlobal('session', $this->session());
    }

    /**
     * Check if the user in session is an admin
     *
     * @return void
     */
    public function checkRole(): void
    {
        if (empty($this->session()) or $this->session()['role_id'] != 1) {
            header('Location: /login?forbidden=1');
        }
    }

    public function checkSession(): void
    {
        if ($this->session()['id'] === null) {
            header('Location: /login?forbidden=1');
        }
    }

    public function session()
    {
        return $_SESSION;
    }

    public function post()
    {
        return $_POST;
    }

    public function get()
    {
        return $_GET;
    }

    public function server()
    {
        return $_SERVER;
    }
}
