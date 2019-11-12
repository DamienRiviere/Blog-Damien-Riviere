<?php

namespace App\Controller;

use App\Helpers\Data;
use App\Helpers\DataUrl;
use App\Helpers\Server;
use App\Helpers\Session;

abstract class Controller
{
    
    protected $twig;

    protected $loader;

    private $templatePath = "..\\templates\\";

    protected $session;

    protected $data;

    protected $server;

    protected $dataUrl;

    public function __construct()
    {
        $this->loadTwig();
        $this->session = new Session();
        $this->data = new Data();
        $this->server = new Server();
        $this->dataUrl = new DataUrl();
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

    /**
     * Check if the user in session is an admin
     */
    public function checkRole()
    {
        if (empty($_SESSION) or $_SESSION['role_id'] != 1) {
            return header('Location: /login?forbidden=1');
        }
    }

    public function checkSession()
    {
        if ($_SESSION['id'] === null) {
            return header('Location: /login?forbidden=1');
        }
    }
}
