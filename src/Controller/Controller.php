<?php

namespace App\Controller;

class Controller {    

    public $twig;

    public $loader;

    public $templatePath = "..\\templates\\";

    public function __construct() {
        $this->loader = new \Twig\Loader\FilesystemLoader([
            $this->templatePath . 'home',
            $this->templatePath . 'post',
            $this->templatePath . 'layout',
        ]);
        $this->twig = new \Twig\Environment($this->loader, [
            'cache' => false, //dirname(__DIR__) . '/tmp',
        ]);
    }
    
    // public function loadTwig() {
    //     $loader = new \Twig\Loader\FilesystemLoader(
    //         [
    //             $this->templatePath . '/home',
    //             $this->templatePath . '/layout',
    //             $this->templatePath . '/post',
    //         ]
    //     );

    //     $params = [
    //         'cache' => false
    //     ];

    //     $this->twig = new Twig_Environment($loader, $params);
    // }

    // public function render($view, $data = []) {
    //     ob_start();
    //     require '../templates/' . $view . '.php';
    //     $content = ob_get_clean();
    //     require '../templates/layout/default.php';
    // }
}